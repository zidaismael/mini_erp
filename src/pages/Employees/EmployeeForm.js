import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { Save, X } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';

const EmployeeForm = () => {
  const navigate = useNavigate();
  const { id } = useParams();
  const isEdit = Boolean(id);

  const [formData, setFormData] = useState({
    lastname: '',
    firstname: '',
    country: '',
    birthday: '',
    contract_date: '',
    company_id: ''
  });
  const [companies, setCompanies] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    fetchCompanies();
    if (isEdit) {
      fetchEmployee();
    }
  }, [id, isEdit]);

  const fetchCompanies = async () => {
    try {
      const response = await apiService.companies.getAll();
      setCompanies(response.data || []);
    } catch (err) {
      console.error('Error fetching companies:', err);
    }
  };

  const fetchEmployee = async () => {
    try {
      setLoading(true);
      const response = await apiService.employees.getById(id);
      const employee = response.data;
      setFormData({
        lastname: employee.lastname || '',
        firstname: employee.firstname || '',
        country: employee.country || '',
        birthday: employee.birthday || '',
        contract_date: employee.contract_date || '',
        company_id: employee.company_id || ''
      });
    } catch (err) {
      setError('Erreur lors du chargement de l\'employé');
      console.error('Error fetching employee:', err);
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

    const submitData = {
      ...formData,
      company_id: parseInt(formData.company_id) || null
    };

    try {
      if (isEdit) {
        await apiService.employees.update(id, submitData);
      } else {
        await apiService.employees.create(submitData);
      }
      navigate('/employees');
    } catch (err) {
      setError(isEdit ? 'Erreur lors de la modification' : 'Erreur lors de la création');
      console.error('Error saving employee:', err);
    } finally {
      setSaving(false);
    }
  };

  if (loading) return <LoadingSpinner />;

  return (
    <div className="employee-form">
      <PageHeader
        title={isEdit ? 'Modifier l\'employé' : 'Nouvel employé'}
        showBackButton={true}
      />

      {error && <div className="error-message">{error}</div>}

      <div className="form-container">
        <form onSubmit={handleSubmit} className="form-content">
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
              />
            </div>

            <div className="form-group">
              <label htmlFor="birthday">Date de naissance</label>
              <input
                type="date"
                id="birthday"
                name="birthday"
                value={formData.birthday}
                onChange={handleChange}
                className="form-control"
              />
            </div>

            <div className="form-group">
              <label htmlFor="contract_date">Date de contrat *</label>
              <input
                type="date"
                id="contract_date"
                name="contract_date"
                value={formData.contract_date}
                onChange={handleChange}
                required
                className="form-control"
              />
            </div>

            <div className="form-group">
              <label htmlFor="company_id">Entreprise *</label>
              <select
                id="company_id"
                name="company_id"
                value={formData.company_id}
                onChange={handleChange}
                required
                className="form-control"
              >
                <option value="">Sélectionner une entreprise</option>
                {companies.map(company => (
                  <option key={company.id} value={company.id}>
                    {company.name}
                  </option>
                ))}
              </select>
            </div>
          </div>

          <div className="form-actions">
            <button
              type="button"
              onClick={() => navigate('/employees')}
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

export default EmployeeForm;