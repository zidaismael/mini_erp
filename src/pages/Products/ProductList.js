import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Edit, Eye, Trash2, Search, Package } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';

const ProductList = () => {
  const navigate = useNavigate();
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    // Note: Il n'y a pas d'endpoint pour lister tous les produits
    // Dans votre API, les produits sont liés aux entreprises ou fournisseurs
    setLoading(false);
  }, []);

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'EUR'
    }).format(amount);
  };

  if (loading) return <LoadingSpinner />;

  return (
    <div className="product-list">
      <PageHeader
        title="Produits"
        subtitle="Gestion des produits"
        showAddButton={true}
        addButtonText="Nouveau Produit"
        onAddClick={() => navigate('/products/new')}
      />

      <div className="empty-state">
        <Package size={48} />
        <p>Les produits sont gérés via les entreprises et fournisseurs</p>
        <div style={{ marginTop: '20px', display: 'flex', gap: '12px' }}>
          <button 
            onClick={() => navigate('/companies')} 
            className="btn btn-primary"
          >
            Voir les entreprises
          </button>
          <button 
            onClick={() => navigate('/providers')} 
            className="btn btn-secondary"
          >
            Voir les fournisseurs
          </button>
        </div>
      </div>
    </div>
  );
};

export default ProductList;