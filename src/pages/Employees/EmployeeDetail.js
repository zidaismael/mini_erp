import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Edit, Trash2, User, Building2, Calendar } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';

const EmployeeDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [employee, setEmployee] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchEmployee();
  }, [id]);

  const fetchEmployee = async () => {
    try {
      setLoading(true);
      const response = await apiService.employees.getById(id);
      setEmployee(response.data);
    } catch (err) {
      setError('Erreur lors du chargement de l\'employé');
      console.error('Error fetching employee:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async () => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer cet employé ?')) {
      try {
        await apiService.employees.delete(id);
        navigate('/employees');
      } catch (err) {
        setError('Erreur lors de la suppression de l\'employé');
        console.error('Error deleting employee:', err);
      }
    }
  };

  if (loading) return <LoadingSpinner />;
  if (error) return <div className="error-message">{error}</div>;
  if (!employee) return <div className="error-message">Employé non trouvé</div>;

  return (
    <div className="employee-detail">
      <PageHeader
        title={`${employee.firstname} ${employee.lastname}`}
        subtitle={`Employé - ${employee.reference}`}
        showBackButton={true}
      >
        <button onClick={() => navigate(`/employees/${id}/edit`)} className="btn btn-primary">
          <Edit size={16} />
          Modifier
        </button>
        <button onClick={handleDelete} className="btn btn-danger">
          <Trash2 size={16} />
          Supprimer
        </button>
      </PageHeader>

      <div className="detail-content">
        <div className="detail-card">
          <div className="card-header">
            <User size={20} />
            <h3>Informations personnelles</h3>
          </div>
          <div className="card-content">
            <div className="info-grid">
              <div className="info-item">
                <label>Référence</label>
                <span className="reference">{employee.reference}</span>
              </div>
              <div className="info-item">
                <label>Nom complet</label>
                <span>{employee.firstname} {employee.lastname}</span>
              </div>
              <div className="info-item">
                <label>Pays</label>
                <span>{employee.country}</span>
              </div>
              <div className="info-item">
                <label>Date de naissance</label>
                <span>
                  <Calendar size={14} />
                  {employee.birthday ? new Date(employee.birthday).toLocaleDateString() : 'Non renseignée'}
                </span>
              </div>
              <div className="info-item">
                <label>Date de contrat</label>
                <span>
                  <Calendar size={14} />
                  {employee.contract_date ? new Date(employee.contract_date).toLocaleDateString() : 'Non renseignée'}
                </span>
              </div>
              <div className="info-item">
                <label>Entreprise</label>
                <span>
                  <Building2 size={14} />
                  {employee.company_id ? `Entreprise ID: ${employee.company_id}` : 'Non assignée'}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default EmployeeDetail;