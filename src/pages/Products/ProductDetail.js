import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Edit, Trash2, Package, DollarSign, Percent, Archive } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';

const ProductDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchProduct();
  }, [id]);

  const fetchProduct = async () => {
    try {
      setLoading(true);
      const response = await apiService.products.getById(id);
      setProduct(response.data);
    } catch (err) {
      setError('Erreur lors du chargement du produit');
      console.error('Error fetching product:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async () => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
      try {
        await apiService.products.delete(id);
        navigate('/products');
      } catch (err) {
        setError('Erreur lors de la suppression du produit');
        console.error('Error deleting product:', err);
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
  if (!product) return <div className="error-message">Produit non trouvé</div>;

  return (
    <div className="product-detail">
      <PageHeader
        title={product.name}
        subtitle={`Produit - ${product.reference}`}
        showBackButton={true}
      >
        <button onClick={() => navigate(`/products/${id}/edit`)} className="btn btn-primary">
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
            <Package size={20} />
            <h3>Informations produit</h3>
          </div>
          <div className="card-content">
            <div className="info-grid">
              <div className="info-item">
                <label>Référence</label>
                <span className="reference">{product.reference}</span>
              </div>
              <div className="info-item">
                <label>Nom</label>
                <span>{product.name}</span>
              </div>
              <div className="info-item">
                <label>Prix unitaire</label>
                <span>
                  <DollarSign size={14} />
                  {formatCurrency(product.price)}
                </span>
              </div>
              <div className="info-item">
                <label>TVA</label>
                <span>
                  <Percent size={14} />
                  {product.tax}%
                </span>
              </div>
              <div className="info-item">
                <label>Stock disponible</label>
                <span className={`stock ${product.stock > 0 ? 'positive' : 'negative'}`}>
                  <Archive size={14} />
                  {product.stock} unités
                </span>
              </div>
              <div className="info-item">
                <label>Référence externe</label>
                <span className="reference">{product.external_reference || 'Non définie'}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductDetail;