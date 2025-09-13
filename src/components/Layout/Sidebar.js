import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { 
  Home, 
  Users, 
  Building2, 
  UserCheck, 
  Truck, 
  Package, 
  Receipt,
  BarChart3
} from 'lucide-react';
import './Sidebar.css';

const Sidebar = () => {
  const location = useLocation();

  const menuItems = [
    { path: '/', icon: Home, label: 'Tableau de bord' },
    { path: '/clients', icon: Users, label: 'Clients' },
    { path: '/companies', icon: Building2, label: 'Entreprises' },
    { path: '/employees', icon: UserCheck, label: 'Employés' },
    { path: '/providers', icon: Truck, label: 'Fournisseurs' },
    { path: '/products', icon: Package, label: 'Produits' },
    { path: '/transactions', icon: Receipt, label: 'Transactions' },
  ];

  const isActive = (path) => {
    if (path === '/') {
      return location.pathname === '/';
    }
    return location.pathname.startsWith(path);
  };

  return (
    <div className="sidebar">
      <div className="sidebar-header">
        <div className="logo">
          <BarChart3 size={32} />
          <h1>Mini ERP</h1>
        </div>
      </div>
      <nav className="sidebar-nav">
        <ul>
          {menuItems.map((item) => {
            const Icon = item.icon;
            return (
              <li key={item.path}>
                <Link 
                  to={item.path} 
                  className={`nav-link ${isActive(item.path) ? 'active' : ''}`}
                >
                  <Icon size={20} />
                  <span>{item.label}</span>
                </Link>
              </li>
            );
          })}
        </ul>
      </nav>
    </div>
  );
};

export default Sidebar;