import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Edit, Trash2, Truck, MapPin, Globe, Package } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';

const ProviderDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [provider, setProvider] = useState(null);
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchProviderData();
  }, [id]);

  const fetchProviderData = async () => {
    try {
      setLoading(true);
      const [providerResponse, productsResponse] = await Promise.all([
        apiService.providers.getById(id),
        apiService.providers.getProducts(id).catch(() => ({ data: [] }))
      ]);
      
      setProvider(providerResponse.data);
      setProducts(productsResponse.data || []);
    } catch (err) {
      setError('Erreur lors du chargement des données du fournisseur');
      console.error('Error fetching provider data:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async () => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')) {
      try {
        await apiService.providers.delete(id);
        navigate('/providers');
      } catch (err) {
        setError('Erreur lors de la suppression du fournisseur');
        console.error('Error deleting provider:', err);
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
  if (!provider) return <div className="error-message">Fournisseur non trouvé</div>;

  return (
    <div className="provider-detail">
      <PageHeader
        title={provider.name}
        subtitle={`Fournisseur - ${provider.reference}`}
        showBackButton={true}
      >
        <button onClick={() => navigate(`/providers/${id}/edit`)} className="btn btn-primary">
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
              <Truck size={20} />
              <h3>Informations générales</h3>
            </div>
            <div className="card-content">
              <div className="info-grid">
                <div className="info-item">
                  <label>Référence</label>
                  <span className="reference">{provider.reference}</span>
                </div>
                <div className="info-item">
                  <label>Nom</label>
                  <span>{provider.name}</span>
                </div>
                <div className="info-item">
                  <label>Pays</label>
                  <span>
                    <Globe size={14} />
                    {provider.country}
                  </span>
                </div>
                <div className="info-item">
                  <label>Adresse</label>
                  <span className="address">
                    <MapPin size={14} />
                    {provider.address}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div className="detail-card">
            <div className="card-header">
              <Package size={20} />
              <h3>Produits fournis</h3>
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
                        <span className="product-ref">{product.reference}</span>
                      </div>
                      <div className="product-details">
                        <span className="product-price">{formatCurrency(product.price)}</span>
                        <span className="product-stock">Stock: {product.stock}</span>
                      </div>
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

export default ProviderDetail;