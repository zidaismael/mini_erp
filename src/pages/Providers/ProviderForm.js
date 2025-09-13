import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { Save, X } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';

const ProviderForm = () => {
  const navigate = useNavigate();
  const { id } = useParams();
  const isEdit = Boolean(id);

  const [formData, setFormData] = useState({
    name: '',
    address: '',
    country: ''
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    if (isEdit) {
      fetchProvider();
    }
  }, [id, isEdit]);

  const fetchProvider = async () => {
    try {
      setLoading(true);
      const response = await apiService.providers.getById(id);
      const provider = response.data;
      setFormData({
        name: provider.name || '',
        address: provider.address || '',
        country: provider.country || ''
      });
    } catch (err) {
      setError('Erreur lors du chargement du fournisseur');
      console.error('Error fetching provider:', err);
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
        await apiService.providers.update(id, formData);
      } else {
        await apiService.providers.create(formData);
      }
      navigate('/providers');
    } catch (err) {
      setError(isEdit ? 'Erreur lors de la modification' : 'Erreur lors de la création');
      console.error('Error saving provider:', err);
    } finally {
      setSaving(false);
    }
  };

  if (loading) return <LoadingSpinner />;

  return (
    <div className="provider-form">
      <PageHeader
        title={isEdit ? 'Modifier le fournisseur' : 'Nouveau fournisseur'}
        showBackButton={true}
      />

      {error && <div className="error-message">{error}</div>}

      <div className="form-container">
        <form onSubmit={handleSubmit} className="form-content">
          <div className="form-grid">
            <div className="form-group">
              <label htmlFor="name">Nom de l'entreprise *</label>
              <input
                type="text"
                id="name"
                name="name"
                value={formData.name}
                onChange={handleChange}
                required
                className="form-control"
                placeholder="Nom du fournisseur"
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
          </div>

          <div className="form-actions">
            <button
              type="button"
              onClick={() => navigate('/providers')}
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

export default ProviderForm;