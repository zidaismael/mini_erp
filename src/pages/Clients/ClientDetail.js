import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Edit, Trash2, Receipt, User, MapPin, Globe } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';
import './ClientDetail.css';

const ClientDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [client, setClient] = useState(null);
  const [transactions, setTransactions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchClientData();
  }, [id]);

  const fetchClientData = async () => {
    try {
      setLoading(true);
      const [clientResponse, transactionsResponse] = await Promise.all([
        apiService.clients.getById(id),
        apiService.clients.getTransactions(id).catch(() => ({ data: [] }))
      ]);
      
      setClient(clientResponse.data);
      setTransactions(transactionsResponse.data || []);
    } catch (err) {
      setError('Erreur lors du chargement des données du client');
      console.error('Error fetching client data:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleEdit = () => {
    navigate(`/clients/${id}/edit`);
  };

  const handleDelete = async () => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
      try {
        await apiService.clients.delete(id);
        navigate('/clients');
      } catch (err) {
        setError('Erreur lors de la suppression du client');
        console.error('Error deleting client:', err);
      }
    }
  };

  if (loading) return <LoadingSpinner />;
  if (error) return <div className="error-message">{error}</div>;
  if (!client) return <div className="error-message">Client non trouvé</div>;

  return (
    <div className="client-detail">
      <PageHeader
        title={`${client.firstname} ${client.lastname}`}
        subtitle={`Client - ${client.reference}`}
        showBackButton={true}
      >
        <button onClick={handleEdit} className="btn btn-primary">
          <Edit size={16} />
          Modifier
        </button>
        <button onClick={handleDelete} className="btn btn-danger">
          <Trash2 size={16} />
          Supprimer
        </button>
      </PageHeader>

      <div className="detail-content">
        <div className="detail-grid">
          <div className="detail-card">
            <div className="card-header">
              <User size={20} />
              <h3>Informations personnelles</h3>
            </div>
            <div className="card-content">
              <div className="info-grid">
                <div className="info-item">
                  <label>Référence</label>
                  <span className="reference">{client.reference}</span>
                </div>
                <div className="info-item">
                  <label>Nom complet</label>
                  <span>{client.firstname} {client.lastname}</span>
                </div>
                <div className="info-item">
                  <label>Adresse</label>
                  <span className="address">
                    <MapPin size={14} />
                    {client.address}
                  </span>
                </div>
                <div className="info-item">
                  <label>Pays</label>
                  <span>
                    <Globe size={14} />
                    {client.country}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div className="detail-card">
            <div className="card-header">
              <Receipt size={20} />
              <h3>Transactions récentes</h3>
            </div>
            <div className="card-content">
              {transactions.length === 0 ? (
                <p className="no-data">Aucune transaction trouvée</p>
              ) : (
                <div className="transactions-list">
                  {transactions.slice(0, 5).map(transaction => (
                    <div key={transaction.id} className="transaction-item">
                      <div className="transaction-info">
                        <span className="transaction-ref">{transaction.reference}</span>
                        <span className="transaction-date">
                          {new Date(transaction.created_at).toLocaleDateString()}
                        </span>
                      </div>
                      <div className="transaction-amount">
                        {transaction.total_price}€
                      </div>
                    </div>
                  ))}
                  {transactions.length > 5 && (
                    <div className="show-more">
                      <button 
                        onClick={() => navigate('/transactions')}
                        className="btn btn-link"
                      >
                        Voir toutes les transactions
                      </button>
                    </div>
                  )}
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ClientDetail;