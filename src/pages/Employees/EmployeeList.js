import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Edit, Eye, Trash2, Search } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';

const EmployeeList = () => {
  const navigate = useNavigate();
  const [employees, setEmployees] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    fetchEmployees();
  }, []);

  const fetchEmployees = async () => {
    try {
      setLoading(true);
      const response = await apiService.employees.getAll();
      setEmployees(response.data || []);
    } catch (err) {
      setError('Erreur lors du chargement des employés');
      console.error('Error fetching employees:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer cet employé ?')) {
      try {
        await apiService.employees.delete(id);
        setEmployees(employees.filter(employee => employee.id !== id));
      } catch (err) {
        setError('Erreur lors de la suppression de l\'employé');
        console.error('Error deleting employee:', err);
      }
    }
  };

  const filteredEmployees = employees.filter(employee =>
    employee.lastname?.toLowerCase().includes(searchTerm.toLowerCase()) ||
    employee.firstname?.toLowerCase().includes(searchTerm.toLowerCase()) ||
    employee.reference?.toLowerCase().includes(searchTerm.toLowerCase())
  );

  if (loading) return <LoadingSpinner />;

  return (
    <div className="employee-list">
      <PageHeader
        title="Employés"
        subtitle={`${employees.length} employé(s) enregistré(s)`}
        showAddButton={true}
        addButtonText="Nouvel Employé"
        onAddClick={() => navigate('/employees/new')}
      />

      <div className="list-controls">
        <div className="search-box">
          <Search size={20} />
          <input
            type="text"
            placeholder="Rechercher un employé..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="search-input"
          />
        </div>
      </div>

      {error && <div className="error-message">{error}</div>}

      <div className="table-container">
        {filteredEmployees.length === 0 ? (
          <div className="empty-state">
            <p>Aucun employé trouvé</p>
          </div>
        ) : (
          <table className="data-table">
            <thead>
              <tr>
                <th>Référence</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Pays</th>
                <th>Date de contrat</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {filteredEmployees.map(employee => (
                <tr key={employee.id}>
                  <td className="reference">{employee.reference}</td>
                  <td>{employee.lastname}</td>
                  <td>{employee.firstname}</td>
                  <td>{employee.country}</td>
                  <td>{employee.contract_date ? new Date(employee.contract_date).toLocaleDateString() : '-'}</td>
                  <td className="actions">
                    <button 
                      onClick={() => navigate(`/employees/${employee.id}`)}
                      className="btn-icon btn-view"
                      title="Voir les détails"
                    >
                      <Eye size={16} />
                    </button>
                    <button 
                      onClick={() => navigate(`/employees/${employee.id}/edit`)}
                      className="btn-icon btn-edit"
                      title="Modifier"
                    >
                      <Edit size={16} />
                    </button>
                    <button 
                      onClick={() => handleDelete(employee.id)}
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

export default EmployeeList;