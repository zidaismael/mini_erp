import axios from 'axios';

// Configuration de base de l'API
const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8888/api';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Intercepteur pour gérer les erreurs de réponse
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('API Error:', error.response?.data || error.message);
    return Promise.reject(error);
  }
);

export const apiService = {
  // Méthodes CRUD génériques
  get: (endpoint) => apiClient.get(endpoint),
  post: (endpoint, data) => apiClient.post(endpoint, data),
  put: (endpoint, data) => apiClient.put(endpoint, data),
  delete: (endpoint) => apiClient.delete(endpoint),

  // === CLIENTS ===
  clients: {
    getAll: () => apiClient.get('/clients'),
    getById: (id) => apiClient.get(`/client/${id}`),
    create: (data) => apiClient.post('/client', data),
    update: (id, data) => apiClient.put(`/client/${id}`, data),
    delete: (id) => apiClient.delete(`/client/${id}`),
    getTransactions: (id) => apiClient.get(`/client/${id}/transactions`),
  },

  // === COMPANIES ===
  companies: {
    getAll: () => apiClient.get('/companies'),
    getById: (id) => apiClient.get(`/company/${id}`),
    create: (data) => apiClient.post('/company', data),
    update: (id, data) => apiClient.put(`/company/${id}`, data),
    delete: (id) => apiClient.delete(`/company/${id}`),
    getEmployees: (id) => apiClient.get(`/company/${id}/employees`),
    getProducts: (id) => apiClient.get(`/company/${id}/products`),
    getTransactions: (id) => apiClient.get(`/company/${id}/transactions`),
    buyProducts: (id, data) => apiClient.post(`/company/${id}/buyproducts`, data),
    sellProducts: (id, data) => apiClient.post(`/company/${id}/sellproducts`, data),
  },

  // === EMPLOYEES ===
  employees: {
    getAll: () => apiClient.get('/employees'),
    getById: (id) => apiClient.get(`/employee/${id}`),
    create: (data) => apiClient.post('/employee', data),
    update: (id, data) => apiClient.put(`/employee/${id}`, data),
    delete: (id) => apiClient.delete(`/employee/${id}`),
    getTransactions: (id) => apiClient.get(`/employee/${id}/transactions`),
  },

  // === PROVIDERS ===
  providers: {
    getAll: () => apiClient.get('/providers'),
    getById: (id) => apiClient.get(`/provider/${id}`),
    create: (data) => apiClient.post('/provider', data),
    update: (id, data) => apiClient.put(`/provider/${id}`, data),
    delete: (id) => apiClient.delete(`/provider/${id}`),
    getProducts: (id) => apiClient.get(`/provider/${id}/products`),
  },

  // === PRODUCTS ===
  products: {
    getById: (id) => apiClient.get(`/product/${id}`),
    create: (data) => apiClient.post('/product', data),
    update: (id, data) => apiClient.put(`/product/${id}`, data),
    delete: (id) => apiClient.delete(`/product/${id}`),
  },

  // === TRANSACTIONS ===
  transactions: {
    getById: (id) => apiClient.get(`/transaction/${id}`),
  },
};

export default apiService;