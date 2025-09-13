import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Edit, Eye, Trash2, Search, Building2 } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';
import './CompanyList.css';

const CompanyList = () => {
  const navigate = useNavigate();
  const [companies, setCompanies] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    fetchCompanies();
  }, []);

  const fetchCompanies = async () => {
    try {
      setLoading(true);
      const response = await apiService.companies.getAll();
      setCompanies(response.data || []);
    } catch (err) {
      setError('Erreur lors du chargement des entreprises');
      console.error('Error fetching companies:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')) {
      try {
        await apiService.companies.delete(id);
        setCompanies(companies.filter(company => company.id !== id));
      } catch (err) {
        setError('Erreur lors de la suppression de l\'entreprise');
        console.error('Error deleting company:', err);
      }
    }
  };

  const handleAddCompany = () => {
    navigate('/companies/new');
  };

  const filteredCompanies = companies.filter(company =>
    company.name?.toLowerCase().includes(searchTerm.toLowerCase()) ||
    company.reference?.toLowerCase().includes(searchTerm.toLowerCase()) ||
    company.country?.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'EUR'
    }).format(amount);
  };

  if (loading) return <LoadingSpinner />;

  return (
    <div className="company-list">
      <PageHeader
        title="Entreprises"
        subtitle={`${companies.length} entreprise(s) enregistrée(s)`}
        showAddButton={true}
        addButtonText="Nouvelle Entreprise"
        onAddClick={handleAddCompany}
      />

      <div className="list-controls">
        <div className="search-box">
          <Search size={20} />
          <input
            type="text"
            placeholder="Rechercher une entreprise..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="search-input"
          />
        </div>
      </div>

      {error && <div className="error-message">{error}</div>}

      <div className="company-grid">
        {filteredCompanies.length === 0 ? (
          <div className="empty-state">
            <Building2 size={48} />
            <p>Aucune entreprise trouvée</p>
          </div>
        ) : (
          filteredCompanies.map(company => (
            <div key={company.id} className="company-card">
              <div className="company-card-header">
                <div className="company-icon">
                  <Building2 size={24} />
                </div>
                <div className="company-info">
                  <h3 className="company-name">{company.name}</h3>
                  <p className="company-reference">{company.reference}</p>
                </div>
              </div>
              
              <div className="company-card-content">
                <div className="company-details">
                  <div className="detail-item">
                    <span className="label">Pays:</span>
                    <span className="value">{company.country}</span>
                  </div>
                  <div className="detail-item">
                    <span className="label">Balance:</span>
                    <span className={`value balance ${company.balance >= 0 ? 'positive' : 'negative'}`}>
                      {formatCurrency(company.balance)}
                    </span>
                  </div>
                </div>
              </div>
              
              <div className="company-card-actions">
                <button 
                  onClick={() => navigate(`/companies/${company.id}`)}
                  className="btn-icon btn-view"
                  title="Voir les détails"
                >
                  <Eye size={16} />
                </button>
                <button 
                  onClick={() => navigate(`/companies/${company.id}/edit`)}
                  className="btn-icon btn-edit"
                  title="Modifier"
                >
                  <Edit size={16} />
                </button>
                <button 
                  onClick={() => handleDelete(company.id)}
                  className="btn-icon btn-delete"
                  title="Supprimer"
                >
                  <Trash2 size={16} />
                </button>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  );
};

export default CompanyList;