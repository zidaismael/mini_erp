import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Edit, Eye, Trash2, Search, Truck } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';

const ProviderList = () => {
  const navigate = useNavigate();
  const [providers, setProviders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    fetchProviders();
  }, []);

  const fetchProviders = async () => {
    try {
      setLoading(true);
      const response = await apiService.providers.getAll();
      setProviders(response.data || []);
    } catch (err) {
      setError('Erreur lors du chargement des fournisseurs');
      console.error('Error fetching providers:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')) {
      try {
        await apiService.providers.delete(id);
        setProviders(providers.filter(provider => provider.id !== id));
      } catch (err) {
        setError('Erreur lors de la suppression du fournisseur');
        console.error('Error deleting provider:', err);
      }
    }
  };

  const filteredProviders = providers.filter(provider =>
    provider.name?.toLowerCase().includes(searchTerm.toLowerCase()) ||
    provider.reference?.toLowerCase().includes(searchTerm.toLowerCase()) ||
    provider.country?.toLowerCase().includes(searchTerm.toLowerCase())
  );

  if (loading) return <LoadingSpinner />;

  return (
    <div className="provider-list">
      <PageHeader
        title="Fournisseurs"
        subtitle={`${providers.length} fournisseur(s) enregistré(s)`}
        showAddButton={true}
        addButtonText="Nouveau Fournisseur"
        onAddClick={() => navigate('/providers/new')}
      />

      <div className="list-controls">
        <div className="search-box">
          <Search size={20} />
          <input
            type="text"
            placeholder="Rechercher un fournisseur..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="search-input"
          />
        </div>
      </div>

      {error && <div className="error-message">{error}</div>}

      <div className="provider-grid">
        {filteredProviders.length === 0 ? (
          <div className="empty-state">
            <Truck size={48} />
            <p>Aucun fournisseur trouvé</p>
          </div>
        ) : (
          filteredProviders.map(provider => (
            <div key={provider.id} className="provider-card">
              <div className="provider-card-header">
                <div className="provider-icon">
                  <Truck size={24} />
                </div>
                <div className="provider-info">
                  <h3 className="provider-name">{provider.name}</h3>
                  <p className="provider-reference">{provider.reference}</p>
                </div>
              </div>
              
              <div className="provider-card-content">
                <div className="provider-details">
                  <div className="detail-item">
                    <span className="label">Pays:</span>
                    <span className="value">{provider.country}</span>
                  </div>
                  <div className="detail-item">
                    <span className="label">Adresse:</span>
                    <span className="value address">{provider.address}</span>
                  </div>
                </div>
              </div>
              
              <div className="provider-card-actions">
                <button 
                  onClick={() => navigate(`/providers/${provider.id}`)}
                  className="btn-icon btn-view"
                  title="Voir les détails"
                >
                  <Eye size={16} />
                </button>
                <button 
                  onClick={() => navigate(`/providers/${provider.id}/edit`)}
                  className="btn-icon btn-edit"
                  title="Modifier"
                >
                  <Edit size={16} />
                </button>
                <button 
                  onClick={() => handleDelete(provider.id)}
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

export default ProviderList;