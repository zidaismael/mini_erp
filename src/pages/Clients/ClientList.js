import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Edit, Eye, Trash2, Search } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';
import './ClientList.css';

const ClientList = () => {
  const navigate = useNavigate();
  const [clients, setClients] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    fetchClients();
  }, []);

  const fetchClients = async () => {
    try {
      setLoading(true);
      const response = await apiService.clients.getAll();
      setClients(response.data || []);
    } catch (err) {
      setError('Erreur lors du chargement des clients');
      console.error('Error fetching clients:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
      try {
        await apiService.clients.delete(id);
        setClients(clients.filter(client => client.id !== id));
      } catch (err) {
        setError('Erreur lors de la suppression du client');
        console.error('Error deleting client:', err);
      }
    }
  };

  const handleAddClient = () => {
    navigate('/clients/new');
  };

  const filteredClients = clients.filter(client =>
    client.lastname?.toLowerCase().includes(searchTerm.toLowerCase()) ||
    client.firstname?.toLowerCase().includes(searchTerm.toLowerCase()) ||
    client.reference?.toLowerCase().includes(searchTerm.toLowerCase()) ||
    client.country?.toLowerCase().includes(searchTerm.toLowerCase())
  );

  if (loading) return <LoadingSpinner />;

  return (
    <div className="client-list">
      <PageHeader
        title="Clients"
        subtitle={`${clients.length} client(s) enregistré(s)`}
        showAddButton={true}
        addButtonText="Nouveau Client"
        onAddClick={handleAddClient}
      />

      <div className="list-controls">
        <div className="search-box">
          <Search size={20} />
          <input
            type="text"
            placeholder="Rechercher un client..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="search-input"
          />
        </div>
      </div>

      {error && <div className="error-message">{error}</div>}

      <div className="client-table-container">
        {filteredClients.length === 0 ? (
          <div className="empty-state">
            <p>Aucun client trouvé</p>
          </div>
        ) : (
          <table className="client-table">
            <thead>
              <tr>
                <th>Référence</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Adresse</th>
                <th>Pays</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {filteredClients.map(client => (
                <tr key={client.id}>
                  <td className="reference">{client.reference}</td>
                  <td className="name">{client.lastname}</td>
                  <td>{client.firstname}</td>
                  <td className="address">{client.address}</td>
                  <td>{client.country}</td>
                  <td className="actions">
                    <button 
                      onClick={() => navigate(`/clients/${client.id}`)}
                      className="btn-icon btn-view"
                      title="Voir les détails"
                    >
                      <Eye size={16} />
                    </button>
                    <button 
                      onClick={() => navigate(`/clients/${client.id}/edit`)}
                      className="btn-icon btn-edit"
                      title="Modifier"
                    >
                      <Edit size={16} />
                    </button>
                    <button 
                      onClick={() => handleDelete(client.id)}
                      className="btn-icon btn-delete"
                      title="Supprimer"
                    >
                      <Trash2 size={16} />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>
    </div>
  );
};

export default ClientList;