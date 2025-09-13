import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Edit, Trash2, Users, Package, Receipt, Building2, Globe, DollarSign } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';
import './CompanyDetail.css';

const CompanyDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [company, setCompany] = useState(null);
  const [employees, setEmployees] = useState([]);
  const [products, setProducts] = useState([]);
  const [transactions, setTransactions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchCompanyData();
  }, [id]);

  const fetchCompanyData = async () => {
    try {
      setLoading(true);
      const [companyResponse, employeesResponse, productsResponse, transactionsResponse] = await Promise.all([
        apiService.companies.getById(id),
        apiService.companies.getEmployees(id).catch(() => ({ data: [] })),
        apiService.companies.getProducts(id).catch(() => ({ data: [] })),
        apiService.companies.getTransactions(id).catch(() => ({ data: [] }))
      ]);
      
      setCompany(companyResponse.data);
      setEmployees(employeesResponse.data || []);
      setProducts(productsResponse.data || []);
      setTransactions(transactionsResponse.data || []);
    } catch (err) {
      setError('Erreur lors du chargement des données de l\'entreprise');
      console.error('Error fetching company data:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleEdit = () => {
    navigate(`/companies/${id}/edit`);
  };

  const handleDelete = async () => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')) {
      try {
        await apiService.companies.delete(id);
        navigate('/companies');
      } catch (err) {
        setError('Erreur lors de la suppression de l\'entreprise');
        console.error('Error deleting company:', err);
      }
    }
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'EUR'
    }).format(amount);
  };

  if (loading) return <LoadingSpinner />;
  if (error) return <div className="error-message">{error}</div>;
  if (!company) return <div className="error-message">Entreprise non trouvée</div>;

  return (
    <div className="company-detail">
      <PageHeader
        title={company.name}
        subtitle={`Entreprise - ${company.reference}`}
        showBackButton={true}
      >
        <button onClick={handleEdit} className="btn btn-primary">
          <Edit size={16} />
          Modifier
        </button>
        <button onClick={handleDelete} className="btn btn-danger">
          <Trash2 size={16} />
          Supprimer
        </button>
      </PageHeader>

      <div className="detail-content">
        <div className="detail-grid">
          <div className="detail-card">
            <div className="card-header">
              <Building2 size={20} />
              <h3>Informations générales</h3>
            </div>
            <div className="card-content">
              <div className="info-grid">
                <div className="info-item">
                  <label>Référence</label>
                  <span className="reference">{company.reference}</span>
                </div>
                <div className="info-item">
                  <label>Nom</label>
                  <span>{company.name}</span>
                </div>
                <div className="info-item">
                  <label>Pays</label>
                  <span>
                    <Globe size={14} />
                    {company.country}
                  </span>
                </div>
                <div className="info-item">
                  <label>Balance</label>
                  <span className={`balance ${company.balance >= 0 ? 'positive' : 'negative'}`}>
                    <DollarSign size={14} />
                    {formatCurrency(company.balance)}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div className="stats-grid">
            <div className="stat-card">
              <div className="stat-icon employees">
                <Users size={24} />
              </div>
              <div className="stat-content">
                <h4>{employees.length}</h4>
                <p>Employés</p>
              </div>
            </div>

            <div className="stat-card">
              <div className="stat-icon products">
                <Package size={24} />
              </div>
              <div className="stat-content">
                <h4>{products.length}</h4>
                <p>Produits</p>
              </div>
            </div>

            <div className="stat-card">
              <div className="stat-icon transactions">
                <Receipt size={24} />
              </div>
              <div className="stat-content">
                <h4>{transactions.length}</h4>
                <p>Transactions</p>
              </div>
            </div>
          </div>

          <div className="detail-card">
            <div className="card-header">
              <Users size={20} />
              <h3>Employés</h3>
            </div>
            <div className="card-content">
              {employees.length === 0 ? (
                <p className="no-data">Aucun employé trouvé</p>
              ) : (
                <div className="employees-list">
                  {employees.slice(0, 5).map(employee => (
                    <div key={employee.id} className="employee-item">
                      <div className="employee-info">
                        <span className="employee-name">
                          {employee.firstname} {employee.lastname}
                        </span>
                        <span className="employee-ref">{employee.reference}</span>
                      </div>
                      <button 
                        onClick={() => navigate(`/employees/${employee.id}`)}
                        className="btn btn-sm btn-outline"
                      >
                        Voir
                      </button>
                    </div>
                  ))}
                  {employees.length > 5 && (
                    <div className="show-more">
                      <button 
                        onClick={() => navigate('/employees')}
                        className="btn btn-link"
                      >
                        Voir tous les employés
                      </button>
                    </div>
                  )}
                </div>
              )}
            </div>
          </div>

          <div className="detail-card">
            <div className="card-header">
              <Package size={20} />
              <h3>Produits</h3>
            </div>
            <div className="card-content">
              {products.length === 0 ? (
                <p className="no-data">Aucun produit trouvé</p>
              ) : (
                <div className="products-list">
                  {products.slice(0, 5).map(product => (
                    <div key={product.id} className="product-item">
                      <div className="product-info">
                        <span className="product-name">{product.name}</span>
                        <span className="product-price">{formatCurrency(product.price)}</span>
                      </div>
                      <span className="product-stock">Stock: {product.stock}</span>
                    </div>
                  ))}
                  {products.length > 5 && (
                    <div className="show-more">
                      <button 
                        onClick={() => navigate('/products')}
                        className="btn btn-link"
                      >
                        Voir tous les produits
                      </button>
                    </div>
                  )}
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default CompanyDetail;