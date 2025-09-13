# Mini ERP - Interface Frontend

Interface React moderne pour le système ERP développé avec Phalcon.

## Fonctionnalités

- 📊 **Dashboard complet** avec statistiques et aperçu
- 👥 **Gestion des clients** - CRUD complet avec détails et transactions
- 🏢 **Gestion des entreprises** - Avec balance, employés et produits
- 👷 **Gestion des employés** - Assignation aux entreprises
- 🚚 **Gestion des fournisseurs** - Avec catalogue produits
- 📦 **Gestion des produits** - Prix, stock, TVA
- 🧾 **Consultation des transactions** - Historique détaillé
- 🔍 **Recherche avancée** dans tous les modules
- 📱 **Interface responsive** - Compatible mobile et desktop
- ⚡ **Performance optimisée** - Chargement rapide et fluide

## Technologies utilisées

- **React 18** - Framework JavaScript moderne
- **React Router 6** - Navigation et routing
- **Axios** - Client HTTP pour les appels API
- **Lucide React** - Icônes modernes et élégantes
- **CSS3** - Styles modernes avec gradients et animations

## Installation

1. **Cloner le projet**
   ```bash
   git clone <votre-repo>
   cd mini-erp-frontend
   ```

2. **Installer les dépendances**
   ```bash
   npm install
   ```

3. **Configuration**
   - Copiez le fichier `.env` et ajustez l'URL de votre API
   - Par défaut: `http://localhost:8888/api`

4. **Lancer l'application**
   ```bash
   npm start
   ```

L'application sera disponible sur `http://localhost:3000`

## Structure du projet

```
src/
├── components/           # Composants réutilisables
│   ├── Common/          # Composants génériques
│   └── Layout/          # Composants de layout
├── pages/               # Pages de l'application
│   ├── Dashboard/       # Tableau de bord
│   ├── Clients/         # Module clients
│   ├── Companies/       # Module entreprises
│   ├── Employees/       # Module employés
│   ├── Providers/       # Module fournisseurs
│   ├── Products/        # Module produits
│   └── Transactions/    # Module transactions
├── services/            # Services API
└── App.js              # Composant principal
```

## API Endpoints utilisés

L'application utilise les endpoints suivants de votre API Phalcon :

### Clients
- `GET /api/clients` - Liste des clients
- `GET /api/client/{id}` - Détails d'un client
- `POST /api/client` - Créer un client
- `PUT /api/client/{id}` - Modifier un client
- `DELETE /api/client/{id}` - Supprimer un client
- `GET /api/client/{id}/transactions` - Transactions du client

### Entreprises
- `GET /api/companies` - Liste des entreprises
- `GET /api/company/{id}` - Détails d'une entreprise
- `POST /api/company` - Créer une entreprise
- `PUT /api/company/{id}` - Modifier une entreprise
- `DELETE /api/company/{id}` - Supprimer une entreprise
- `GET /api/company/{id}/employees` - Employés de l'entreprise
- `GET /api/company/{id}/products` - Produits de l'entreprise
- `GET /api/company/{id}/transactions` - Transactions de l'entreprise

### Employés
- `GET /api/employees` - Liste des employés
- `GET /api/employee/{id}` - Détails d'un employé
- `POST /api/employee` - Créer un employé
- `PUT /api/employee/{id}` - Modifier un employé
- `DELETE /api/employee/{id}` - Supprimer un employé

### Fournisseurs
- `GET /api/providers` - Liste des fournisseurs
- `GET /api/provider/{id}` - Détails d'un fournisseur
- `POST /api/provider` - Créer un fournisseur
- `PUT /api/provider/{id}` - Modifier un fournisseur
- `DELETE /api/provider/{id}` - Supprimer un fournisseur
- `GET /api/provider/{id}/products` - Produits du fournisseur

### Produits
- `GET /api/product/{id}` - Détails d'un produit
- `POST /api/product` - Créer un produit
- `PUT /api/product/{id}` - Modifier un produit
- `DELETE /api/product/{id}` - Supprimer un produit

### Transactions
- `GET /api/transaction/{id}` - Détails d'une transaction

## Fonctionnalités avancées

### Dashboard
- Statistiques en temps réel
- Graphiques de tendances
- Actions rapides
- Données récentes

### Gestion des erreurs
- Messages d'erreur contextuels
- Gestion des timeouts
- États de chargement

### Interface utilisateur
- Design moderne et intuitif
- Animations fluides
- Thème cohérent
- Navigation intuitive

## Scripts disponibles

- `npm start` - Lance l'application en mode développement
- `npm build` - Construit l'application pour la production
- `npm test` - Lance les tests
- `npm eject` - Éjecte la configuration (irréversible)

## Contribution

1. Fork le projet
2. Créez une branche feature (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## Configuration CORS

Assurez-vous que votre API Phalcon autorise les requêtes CORS depuis `http://localhost:3000` pour le développement.

## Production

Pour déployer en production :

1. Construisez l'application : `npm run build`
2. Servez les fichiers statiques depuis le dossier `build/`
3. Configurez votre serveur web (Apache/Nginx)
4. Ajustez la variable `REACT_APP_API_URL` pour pointer vers votre API de production

## Support

Pour toute question ou problème, veuillez ouvrir une issue sur le repository.

---

**Note**: Cette interface a été développée pour s'adapter parfaitement à votre API ERP existante. Toutes les fonctionnalités respectent la structure de données et les endpoints de votre backend Phalcon.