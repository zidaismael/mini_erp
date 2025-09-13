import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import './App.css';
import Sidebar from './components/Layout/Sidebar';
import Header from './components/Layout/Header';
import Dashboard from './pages/Dashboard/Dashboard';

// Client pages
import ClientList from './pages/Clients/ClientList';
import ClientForm from './pages/Clients/ClientForm';
import ClientDetail from './pages/Clients/ClientDetail';

// Company pages
import CompanyList from './pages/Companies/CompanyList';
import CompanyForm from './pages/Companies/CompanyForm';
import CompanyDetail from './pages/Companies/CompanyDetail';

// Employee pages
import EmployeeList from './pages/Employees/EmployeeList';
import EmployeeForm from './pages/Employees/EmployeeForm';
import EmployeeDetail from './pages/Employees/EmployeeDetail';

// Provider pages
import ProviderList from './pages/Providers/ProviderList';
import ProviderForm from './pages/Providers/ProviderForm';
import ProviderDetail from './pages/Providers/ProviderDetail';

// Product pages
import ProductList from './pages/Products/ProductList';
import ProductForm from './pages/Products/ProductForm';
import ProductDetail from './pages/Products/ProductDetail';

// Transaction pages
import TransactionList from './pages/Transactions/TransactionList';
import TransactionDetail from './pages/Transactions/TransactionDetail';

function App() {
  return (
    <Router>
      <div className="App">
        <Sidebar />
        <div className="main-content">
          <Header />
          <div className="content">
            <Routes>
              <Route path="/" element={<Dashboard />} />
              
              {/* Clients */}
              <Route path="/clients" element={<ClientList />} />
              <Route path="/clients/new" element={<ClientForm />} />
              <Route path="/clients/:id" element={<ClientDetail />} />
              <Route path="/clients/:id/edit" element={<ClientForm />} />
              
              {/* Companies */}
              <Route path="/companies" element={<CompanyList />} />
              <Route path="/companies/new" element={<CompanyForm />} />
              <Route path="/companies/:id" element={<CompanyDetail />} />
              <Route path="/companies/:id/edit" element={<CompanyForm />} />
              
              {/* Employees */}
              <Route path="/employees" element={<EmployeeList />} />
              <Route path="/employees/new" element={<EmployeeForm />} />
              <Route path="/employees/:id" element={<EmployeeDetail />} />
              <Route path="/employees/:id/edit" element={<EmployeeForm />} />
              
              {/* Providers */}
              <Route path="/providers" element={<ProviderList />} />
              <Route path="/providers/new" element={<ProviderForm />} />
              <Route path="/providers/:id" element={<ProviderDetail />} />
              <Route path="/providers/:id/edit" element={<ProviderForm />} />
              
              {/* Products */}
              <Route path="/products" element={<ProductList />} />
              <Route path="/products/new" element={<ProductForm />} />
              <Route path="/products/:id" element={<ProductDetail />} />
              <Route path="/products/:id/edit" element={<ProductForm />} />
              
              {/* Transactions */}
              <Route path="/transactions" element={<TransactionList />} />
              <Route path="/transactions/:id" element={<TransactionDetail />} />
            </Routes>
          </div>
        </div>
      </div>
    </Router>
  );
}

export default App;