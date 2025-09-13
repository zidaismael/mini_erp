import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { Save, X } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';
import './ClientForm.css';

const ClientForm = () => {
  const navigate = useNavigate();
  const { id } = useParams();
  const isEdit = Boolean(id);

  const [formData, setFormData] = useState({
    lastname: '',
    firstname: '',
    address: '',
    country: ''
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    if (isEdit) {
      fetchClient();
    }
  }, [id, isEdit]);

  const fetchClient = async () => {
    try {
      setLoading(true);
      const response = await apiService.clients.getById(id);
      const client = response.data;
      setFormData({
        lastname: client.lastname || '',
        firstname: client.firstname || '',
        address: client.address || '',
        country: client.country || ''
      });
    } catch (err) {
      setError('Erreur lors du chargement du client');
      console.error('Error fetching client:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    setError(null);

    try {
      if (isEdit) {
        await apiService.clients.update(id, formData);
      } else {
        await apiService.clients.create(formData);
      }
      navigate('/clients');
    } catch (err) {
      setError(isEdit ? 'Erreur lors de la modification' : 'Erreur lors de la création');
      console.error('Error saving client:', err);
    } finally {
      setSaving(false);
    }
  };

  const handleCancel = () => {
    navigate('/clients');
  };

  if (loading) return <LoadingSpinner />;

  return (
    <div className="client-form">
      <PageHeader
        title={isEdit ? 'Modifier le client' : 'Nouveau client'}
        showBackButton={true}
      />

      {error && <div className="error-message">{error}</div>}

      <div className="form-container">
        <form onSubmit={handleSubmit} className="client-form-content">
          <div className="form-grid">
            <div className="form-group">
              <label htmlFor="lastname">Nom *</label>
              <input
                type="text"
                id="lastname"
                name="lastname"
                value={formData.lastname}
                onChange={handleChange}
                required
                className="form-control"
                placeholder="Nom de famille"
              />
            </div>

            <div className="form-group">
              <label htmlFor="firstname">Prénom *</label>
              <input
                type="text"
                id="firstname"
                name="firstname"
                value={formData.firstname}
                onChange={handleChange}
                required
                className="form-control"
                placeholder="Prénom"
              />
            </div>

            <div className="form-group full-width">
              <label htmlFor="address">Adresse *</label>
              <textarea
                id="address"
                name="address"
                value={formData.address}
                onChange={handleChange}
                required
                rows="3"
                className="form-control"
                placeholder="Adresse complète"
              />
            </div>

            <div className="form-group">
              <label htmlFor="country">Pays *</label>
              <input
                type="text"
                id="country"
                name="country"
                value={formData.country}
                onChange={handleChange}
                required
                className="form-control"
                placeholder="Pays"
              />
            </div>
          </div>

          <div className="form-actions">
            <button
              type="button"
              onClick={handleCancel}
              className="btn btn-secondary"
              disabled={saving}
            >
              <X size={16} />
              Annuler
            </button>
            <button
              type="submit"
              disabled={saving}
              className="btn btn-primary"
            >
              <Save size={16} />
              {saving ? 'Enregistrement...' : (isEdit ? 'Modifier' : 'Créer')}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ClientForm;