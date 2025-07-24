# Match Meuble - Plateforme de montage de meubles

🔧 **Application moderne de mise en relation pour le montage de meubles**, développée avec **Symfony 7** et **Tailwind CSS + Flowbite**.

**Match Meuble** facilite la rencontre entre l'offre et la demande pour le montage de meubles pour n'importe quel événement.

## ✨ Fonctionnalités

### 🎯 **Mise en relation intelligente**
- **Dépôt d'offres** : Proposez vos services de montage
- **Dépôt de demandes** : Trouvez des monteurs compétents
- **Système de contact** : Communication facilitée
- **Gestion des comptes** : Profils utilisateurs et abonnements

### 🎨 **Interface moderne**
- **Design responsive** : Optimisé mobile/tablet/desktop
- **Dark mode** : Basculement automatique jour/nuit
- **Composants Flowbite** : Interface utilisateur moderne
- **Tailwind CSS** : Styling utilitaire performant

## 🛠️ Technologies

### **Backend**
- **Symfony 7** - Framework PHP moderne
- **Doctrine ORM** - Gestion de base de données
- **Twig** - Moteur de templates

### **Frontend**
- **Tailwind CSS** - Framework CSS utilitaire
- **Flowbite** - Composants UI modernes
- **Webpack Encore** - Build et bundling des assets
- **Font Awesome** - Icônes

## 🚀 Installation

### **Prérequis**
- PHP 8.2+
- Node.js 18+
- Composer
- Git

### **Installation rapide**

```bash
# Cloner le projet
git clone <repository-url>
cd match-meuble

# Installer les dépendances PHP
composer install

# Installer les dépendances JavaScript
npm install

# Configurer l'environnement
cp .env .env.local
# Éditer .env.local avec vos configurations

# Compiler les assets
npm run build

# Démarrer le serveur
php -S localhost:8000 -t public
```

## 📁 Structure du projet

```
match-meuble/
├── assets/                 # Assets frontend
│   ├── styles/
│   │   └── app.css         # Styles Tailwind CSS
│   ├── app.js              # JavaScript principal
│   └── controllers.json    # Configuration Stimulus
├── src/                    # Code source Symfony
│   ├── Controller/         # Contrôleurs
│   ├── Entity/            # Entités Doctrine
│   ├── Form/              # Formulaires
│   └── Repository/        # Repositories
├── templates/             # Templates Twig
│   ├── base.html.twig     # Template de base
│   ├── header.html.twig   # Header moderne
│   ├── footer.html.twig   # Footer moderne
│   └── home/              # Pages d'accueil
├── public/                # Assets publics
├── webpack.config.js      # Configuration Webpack
├── tailwind.config.js     # Configuration Tailwind
└── postcss.config.js      # Configuration PostCSS
```

## 🧪 Développement

```bash
# Mode développement avec watch
npm run watch

# Build de production
npm run build

# Serveur de développement
php -S localhost:8000 -t public
```

## 🎨 Personnalisation

### **Couleurs**
Les couleurs principales sont configurées dans `tailwind.config.js` :
- Primary : Bleu (#3b82f6)
- Personnalisable via les variables CSS

### **Dark Mode**
Le dark mode est géré automatiquement via :
- Détection des préférences système
- Bouton de basculement dans le header
- Persistance en localStorage

## 📝 Fonctionnalités à venir

- [ ] Système de notation
- [ ] Chat en temps réel
- [ ] Géolocalisation
- [ ] Paiement intégré
- [ ] Application mobile

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/amazing-feature`)
3. Commit les changements (`git commit -m 'Add amazing feature'`)
4. Push vers la branche (`git push origin feature/amazing-feature`)
5. Ouvrir une Pull Request

## 📝 Licence

Ce projet est sous licence MIT.

---

**Développé avec ❤️ par JeremieCode™ Corp**  
*Migration Tailwind CSS + Flowbite terminée le 24 juillet 2025*
