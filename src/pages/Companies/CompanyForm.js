import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { Save, X } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';
import './CompanyForm.css';

const CompanyForm = () => {
  const navigate = useNavigate();
  const { id } = useParams();
  const isEdit = Boolean(id);

  const [formData, setFormData] = useState({
    name: '',
    balance: '',
    country: ''
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    if (isEdit) {
      fetchCompany();
    }
  }, [id, isEdit]);

  const fetchCompany = async () => {
    try {
      setLoading(true);
      const response = await apiService.companies.getById(id);
      const company = response.data;
      setFormData({
        name: company.name || '',
        balance: company.balance || '',
        country: company.country || ''
      });
    } catch (err) {
      setError('Erreur lors du chargement de l\'entreprise');
      console.error('Error fetching company:', err);
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

    // Convert balance to number
    const submitData = {
      ...formData,
      balance: parseFloat(formData.balance) || 0
    };

    try {
      if (isEdit) {
        await apiService.companies.update(id, submitData);
      } else {
        await apiService.companies.create(submitData);
      }
      navigate('/companies');
    } catch (err) {
      setError(isEdit ? 'Erreur lors de la modification' : 'Erreur lors de la création');
      console.error('Error saving company:', err);
    } finally {
      setSaving(false);
    }
  };

  const handleCancel = () => {
    navigate('/companies');
  };

  if (loading) return <LoadingSpinner />;

  return (
    <div className="company-form">
      <PageHeader
        title={isEdit ? 'Modifier l\'entreprise' : 'Nouvelle entreprise'}
        showBackButton={true}
      />

      {error && <div className="error-message">{error}</div>}

      <div className="form-container">
        <form onSubmit={handleSubmit} className="company-form-content">
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
                placeholder="Nom de l'entreprise"
              />
            </div>

            <div className="form-group">
              <label htmlFor="balance">Balance (€)</label>
              <input
                type="number"
                id="balance"
                name="balance"
                value={formData.balance}
                onChange={handleChange}
                step="0.01"
                className="form-control"
                placeholder="0.00"
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

export default CompanyForm;