import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { 
  Users, 
  Building2, 
  UserCheck, 
  Truck, 
  Package, 
  Receipt, 
  TrendingUp, 
  TrendingDown,
  Plus,
  Eye
} from 'lucide-react';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';
import './Dashboard.css';

const Dashboard = () => {
  const navigate = useNavigate();
  const [stats, setStats] = useState({
    clients: 0,
    companies: 0,
    employees: 0,
    providers: 0,
    products: 0,
    transactions: 0
  });
  const [recentData, setRecentData] = useState({
    clients: [],
    companies: [],
    transactions: []
  });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    try {
      setLoading(true);
      const [
        clientsResponse,
        companiesResponse,
        employeesResponse,
        providersResponse
      ] = await Promise.all([
        apiService.clients.getAll().catch(() => ({ data: [] })),
        apiService.companies.getAll().catch(() => ({ data: [] })),
        apiService.employees.getAll().catch(() => ({ data: [] })),
        apiService.providers.getAll().catch(() => ({ data: [] }))
      ]);

      const clients = clientsResponse.data || [];
      const companies = companiesResponse.data || [];
      const employees = employeesResponse.data || [];
      const providers = providersResponse.data || [];

      setStats({
        clients: clients.length,
        companies: companies.length,
        employees: employees.length,
        providers: providers.length,
        products: 0, // Will be calculated from company products
        transactions: 0 // Will be calculated from company transactions
      });

      setRecentData({
        clients: clients.slice(0, 5),
        companies: companies.slice(0, 5),
        transactions: [] // Will be fetched separately if needed
      });

    } catch (err) {
      setError('Erreur lors du chargement des données du tableau de bord');
      console.error('Error fetching dashboard data:', err);
    } finally {
      setLoading(false);
    }
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'EUR'
    }).format(amount);
  };

  const quickActions = [
    { 
      icon: Users, 
      label: 'Nouveau Client', 
      action: () => navigate('/clients/new'),
      color: 'blue'
    },
    { 
      icon: Building2, 
      label: 'Nouvelle Entreprise', 
      action: () => navigate('/companies/new'),
      color: 'green'
    },
    { 
      icon: UserCheck, 
      label: 'Nouvel Employé', 
      action: () => navigate('/employees/new'),
      color: 'purple'
    },
    { 
      icon: Package, 
      label: 'Nouveau Produit', 
      action: () => navigate('/products/new'),
      color: 'orange'
    }
  ];

  if (loading) return <LoadingSpinner message="Chargement du tableau de bord..." />;

  return (
    <div className="dashboard">
      <div className="dashboard-header">
        <h1>Tableau de bord</h1>
        <p>Vue d'ensemble de votre système ERP</p>
      </div>

      {error && <div className="error-message">{error}</div>}

      {/* Statistics Cards */}
      <div className="stats-grid">
        <div className="stat-card clients">
          <div className="stat-icon">
            <Users size={24} />
          </div>
          <div className="stat-content">
            <h3>{stats.clients}</h3>
            <p>Clients</p>
            <div className="stat-trend positive">
              <TrendingUp size={16} />
              <span>+5.2%</span>
            </div>
          </div>
        </div>

        <div className="stat-card companies">
          <div className="stat-icon">
            <Building2 size={24} />
          </div>
          <div className="stat-content">
            <h3>{stats.companies}</h3>
            <p>Entreprises</p>
            <div className="stat-trend positive">
              <TrendingUp size={16} />
              <span>+2.1%</span>
            </div>
          </div>
        </div>

        <div className="stat-card employees">
          <div className="stat-icon">
            <UserCheck size={24} />
          </div>
          <div className="stat-content">
            <h3>{stats.employees}</h3>
            <p>Employés</p>
            <div className="stat-trend positive">
              <TrendingUp size={16} />
              <span>+8.4%</span>
            </div>
          </div>
        </div>

        <div className="stat-card providers">
          <div className="stat-icon">
            <Truck size={24} />
          </div>
          <div className="stat-content">
            <h3>{stats.providers}</h3>
            <p>Fournisseurs</p>
            <div className="stat-trend negative">
              <TrendingDown size={16} />
              <span>-1.2%</span>
            </div>
          </div>
        </div>

        <div className="stat-card products">
          <div className="stat-icon">
            <Package size={24} />
          </div>
          <div className="stat-content">
            <h3>{stats.products}</h3>
            <p>Produits</p>
            <div className="stat-trend positive">
              <TrendingUp size={16} />
              <span>+12.5%</span>
            </div>
          </div>
        </div>

        <div className="stat-card transactions">
          <div className="stat-icon">
            <Receipt size={24} />
          </div>
          <div className="stat-content">
            <h3>{stats.transactions}</h3>
            <p>Transactions</p>
            <div className="stat-trend positive">
              <TrendingUp size={16} />
              <span>+15.8%</span>
            </div>
          </div>
        </div>
      </div>

      {/* Quick Actions */}
      <div className="quick-actions">
        <h2>Actions rapides</h2>
        <div className="actions-grid">
          {quickActions.map((action, index) => {
            const Icon = action.icon;
            return (
              <button
                key={index}
                onClick={action.action}
                className={`action-card ${action.color}`}
              >
                <Icon size={24} />
                <span>{action.label}</span>
                <Plus size={16} className="action-plus" />
              </button>
            );
          })}
        </div>
      </div>

      {/* Recent Data */}
      <div className="recent-data">
        <div className="recent-section">
          <div className="section-header">
            <h3>Clients récents</h3>
            <button 
              onClick={() => navigate('/clients')}
              className="btn btn-outline btn-sm"
            >
              <Eye size={16} />
              Voir tout
            </button>
          </div>
          <div className="recent-list">
            {recentData.clients.length === 0 ? (
              <p className="no-data">Aucun client récent</p>
            ) : (
              recentData.clients.map(client => (
                <div key={client.id} className="recent-item">
                  <div className="item-info">
                    <span className="item-name">
                      {client.firstname} {client.lastname}
                    </span>
                    <span className="item-detail">{client.reference}</span>
                  </div>
                  <span className="item-meta">{client.country}</span>
                </div>
              ))
            )}
          </div>
        </div>

        <div className="recent-section">
          <div className="section-header">
            <h3>Entreprises récentes</h3>
            <button 
              onClick={() => navigate('/companies')}
              className="btn btn-outline btn-sm"
            >
              <Eye size={16} />
              Voir tout
            </button>
          </div>
          <div className="recent-list">
            {recentData.companies.length === 0 ? (
              <p className="no-data">Aucune entreprise récente</p>
            ) : (
              recentData.companies.map(company => (
                <div key={company.id} className="recent-item">
                  <div className="item-info">
                    <span className="item-name">{company.name}</span>
                    <span className="item-detail">{company.reference}</span>
                  </div>
                  <span className={`item-meta balance ${company.balance >= 0 ? 'positive' : 'negative'}`}>
                    {formatCurrency(company.balance)}
                  </span>
                </div>
              ))
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;