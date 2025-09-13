import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Eye, Receipt } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';

const TransactionList = () => {
  const navigate = useNavigate();
  const [transactions] = useState([]);

  return (
    <div className="transaction-list">
      <PageHeader
        title="Transactions"
        subtitle="Historique des transactions"
      />

      <div className="empty-state">
        <Receipt size={48} />
        <p>Les transactions sont consultables via les clients, entreprises et employés</p>
        <div style={{ marginTop: '20px', display: 'flex', gap: '12px' }}>
          <button 
            onClick={() => navigate('/clients')} 
            className="btn btn-primary"
          >
            Voir les clients
          </button>
          <button 
            onClick={() => navigate('/companies')} 
            className="btn btn-secondary"
          >
            Voir les entreprises
          </button>
        </div>
      </div>
    </div>
  );
};

export default TransactionList;