import React from 'react';
import { useNavigate } from 'react-router-dom';
import { ArrowLeft, Plus } from 'lucide-react';
import './PageHeader.css';

const PageHeader = ({ 
  title, 
  subtitle, 
  showBackButton = false, 
  showAddButton = false, 
  addButtonText = 'Ajouter', 
  onAddClick,
  children 
}) => {
  const navigate = useNavigate();

  const handleBack = () => {
    navigate(-1);
  };

  return (
    <div className="page-header">
      <div className="page-header-left">
        {showBackButton && (
          <button onClick={handleBack} className="back-button">
            <ArrowLeft size={20} />
          </button>
        )}
        <div className="page-title-section">
          <h1 className="page-title">{title}</h1>
          {subtitle && <p className="page-subtitle">{subtitle}</p>}
        </div>
      </div>
      
      <div className="page-header-right">
        {children}
        {showAddButton && (
          <button onClick={onAddClick} className="btn btn-primary">
            <Plus size={16} />
            {addButtonText}
          </button>
        )}
      </div>
    </div>
  );
};

export default PageHeader;