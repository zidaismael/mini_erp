import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Receipt, Calendar, DollarSign, User, Building2 } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';

const TransactionDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [transaction, setTransaction] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchTransaction();
  }, [id]);

  const fetchTransaction = async () => {
    try {
      setLoading(true);
      const response = await apiService.transactions.getById(id);
      setTransaction(response.data);
    } catch (err) {
      setError('Erreur lors du chargement de la transaction');
      console.error('Error fetching transaction:', err);
    } finally {
      setLoading(false);
    }
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'EUR'
    }).format(amount);
  };

  if (loading) return <LoadingSpinner />;
  if (error) return <div className="error-message">{error}</div>;
  if (!transaction) return <div className="error-message">Transaction non trouvée</div>;

  return (
    <div className="transaction-detail">
      <PageHeader
        title={`Transaction ${transaction.reference || transaction.id}`}
        subtitle={`Détails de la transaction`}
        showBackButton={true}
      />

      <div className="detail-content">
        <div className="detail-card">
          <div className="card-header">
            <Receipt size={20} />
            <h3>Informations de la transaction</h3>
          </div>
          <div className="card-content">
            <div className="info-grid">
              <div className="info-item">
                <label>Référence</label>
                <span className="reference">{transaction.reference || transaction.id}</span>
              </div>
              <div className="info-item">
                <label>Date</label>
                <span>
                  <Calendar size={14} />
                  {transaction.created_at ? new Date(transaction.created_at).toLocaleDateString() : 'Non définie'}
                </span>
              </div>
              <div className="info-item">
                <label>Montant total</label>
                <span>
                  <DollarSign size={14} />
                  {formatCurrency(transaction.total_price || 0)}
                </span>
              </div>
              <div className="info-item">
                <label>Type</label>
                <span>{transaction.type || 'Non défini'}</span>
              </div>
              <div className="info-item">
                <label>Client</label>
                <span>
                  <User size={14} />
                  {transaction.client_id ? `Client ID: ${transaction.client_id}` : 'Non défini'}
                </span>
              </div>
              <div className="info-item">
                <label>Employé</label>
                <span>
                  <Building2 size={14} />
                  {transaction.employee_id ? `Employé ID: ${transaction.employee_id}` : 'Non défini'}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default TransactionDetail;