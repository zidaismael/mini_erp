import React from 'react';
import { Bell, Search, User } from 'lucide-react';
import './Header.css';

const Header = () => {
  return (
    <header className="header">
      <div className="header-left">
        <div className="search-container">
          <Search size={20} />
          <input 
            type="text" 
            placeholder="Rechercher..." 
            className="search-input"
          />
        </div>
      </div>
      
      <div className="header-right">
        <button className="notification-btn">
          <Bell size={20} />
          <span className="notification-badge">3</span>
        </button>
        
        <div className="user-menu">
          <button className="user-btn">
            <User size={20} />
            <span>Administrateur</span>
          </button>
        </div>
      </div>
    </header>
  );
};

export default Header;