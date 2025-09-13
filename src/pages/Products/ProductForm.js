import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { Save, X } from 'lucide-react';
import PageHeader from '../../components/Common/PageHeader';
import LoadingSpinner from '../../components/Common/LoadingSpinner';
import apiService from '../../services/apiService';

const ProductForm = () => {
  const navigate = useNavigate();
  const { id } = useParams();
  const isEdit = Boolean(id);

  const [formData, setFormData] = useState({
    name: '',
    price: '',
    tax: '',
    stock: '',
    provider_id: ''
  });
  const [providers, setProviders] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    fetchProviders();
    if (isEdit) {
      fetchProduct();
    }
  }, [id, isEdit]);

  const fetchProviders = async () => {
    try {
      const response = await apiService.providers.getAll();
      setProviders(response.data || []);
    } catch (err) {
      console.error('Error fetching providers:', err);
    }
  };

  const fetchProduct = async () => {
    try {
      setLoading(true);
      const response = await apiService.products.getById(id);
      const product = response.data;
      setFormData({
        name: product.name || '',
        price: product.price || '',
        tax: product.tax || '',
        stock: product.stock || '',
        provider_id: product.provider_id || ''
      });
    } catch (err) {
      setError('Erreur lors du chargement du produit');
      console.error('Error fetching product:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    setError(null);

    const submitData = {
      ...formData,
      price: parseFloat(formData.price) || 0,
      tax: parseFloat(formData.tax) || 0,
      stock: parseInt(formData.stock) || 0,
      provider_id: parseInt(formData.provider_id) || null
    };

    try {
      if (isEdit) {
        await apiService.products.update(id, submitData);
      } else {
        await apiService.products.create(submitData);
      }
      navigate('/products');
    } catch (err) {
      setError(isEdit ? 'Erreur lors de la modification' : 'Erreur lors de la création');
      console.error('Error saving product:', err);
    } finally {
      setSaving(false);
    }
  };

  if (loading) return <LoadingSpinner />;

  return (
    <div className="product-form">
      <PageHeader
        title={isEdit ? 'Modifier le produit' : 'Nouveau produit'}
        showBackButton={true}
      />

      {error && <div className="error-message">{error}</div>}

      <div className="form-container">
        <form onSubmit={handleSubmit} className="form-content">
          <div className="form-grid">
            <div className="form-group">
              <label htmlFor="name">Nom du produit *</label>
              <input
                type="text"
                id="name"
                name="name"
                value={formData.name}
                onChange={handleChange}
                required
                className="form-control"
                placeholder="Nom du produit"
              />
            </div>

            <div className="form-group">
              <label htmlFor="price">Prix (€) *</label>
              <input
                type="number"
                id="price"
                name="price"
                value={formData.price}
                onChange={handleChange}
                required
                step="0.01"
                className="form-control"
                placeholder="0.00"
              />
            </div>

            <div className="form-group">
              <label htmlFor="tax">TVA (%) *</label>
              <input
                type="number"
                id="tax"
                name="tax"
                value={formData.tax}
                onChange={handleChange}
                required
                step="0.01"
                className="form-control"
                placeholder="20.00"
              />
            </div>

            <div className="form-group">
              <label htmlFor="stock">Stock *</label>
              <input
                type="number"
                id="stock"
                name="stock"
                value={formData.stock}
                onChange={handleChange}
                required
                className="form-control"
                placeholder="0"
              />
            </div>

            <div className="form-group">
              <label htmlFor="provider_id">Fournisseur *</label>
              <select
                id="provider_id"
                name="provider_id"
                value={formData.provider_id}
                onChange={handleChange}
                required
                className="form-control"
              >
                <option value="">Sélectionner un fournisseur</option>
                {providers.map(provider => (
                  <option key={provider.id} value={provider.id}>
                    {provider.name}
                  </option>
                ))}
              </select>
            </div>
          </div>

          <div className="form-actions">
            <button
              type="button"
              onClick={() => navigate('/products')}
              className="btn btn-secondary"
              disabled={saving}
            >
              <X size={16} />
              Annuler
            </button>
            <button
              type="submit"
              disabled={saving}
              className="btn btn-primary"
            >
              <Save size={16} />
              {saving ? 'Enregistrement...' : (isEdit ? 'Modifier' : 'Créer')}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ProductForm;