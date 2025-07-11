# Architecture CSS - CheckIt

## 📁 Structure des fichiers

```
assets/css/
├── main.css                 # Point d'entrée principal
└── modules/
    ├── variables.css        # Variables CSS (couleurs, espacements, etc.)
    ├── base.css            # Styles de base (reset, typographie)
    ├── layout.css          # Structure (header, footer, conteneurs)
    ├── components.css      # Composants (boutons, cartes, formulaires)
    ├── utilities.css       # Classes utilitaires et responsive
    └── theme.css           # Thème métallique et icônes
```

## 🎯 Organisation modulaire

### **main.css**

Point d'entrée qui importe tous les modules dans l'ordre correct.

### **variables.css**

- Couleurs du thème (gris métallique + vert)
- Espacements standardisés
- Ombres et transitions
- Variables de typographie
- Z-index organisés

### **base.css**

- Reset et structure HTML de base
- Typographie générale
- Effets métalliques sur le body
- Scrollbar personnalisée

### **layout.css**

- Structure générale (content-wrapper, main)
- Conteneurs et grilles
- Header et navigation
- Footer sticky
- Sections spécifiques

### **components.css**

- Boutons avec effets
- Cartes avec animations
- Formulaires stylisés
- Alertes et badges
- Tables et listes

### **utilities.css**

- Classes d'aide
- Animations
- Responsive design
- États d'interaction
- Styles d'impression

### **theme.css**

- Icônes Bootstrap
- Effets métalliques avancés
- Thème sombre
- Accessibilité
- Éléments spécifiques aux tâches

## 🚀 Avantages de cette organisation

1. **Maintenabilité** : Chaque fichier a une responsabilité claire
2. **Réutilisabilité** : Composants modulaires
3. **Performance** : Import organisé et optimisé
4. **Collaboration** : Structure facile à comprendre
5. **Évolutivité** : Ajout facile de nouveaux modules

## 🔧 Comment ajouter de nouveaux styles

1. **Variables** → `variables.css`
2. **Composant global** → `components.css`
3. **Utilitaire** → `utilities.css`
4. **Thème spécifique** → `theme.css`
5. **Nouveau module** → Créer un nouveau fichier et l'importer dans `main.css`

## 🎨 Variables disponibles

```css
/* Couleurs principales */
--primary, --primary-dark, --primary-light
--surface-primary, --surface-secondary, --surface-elevated
--text-primary, --text-secondary, --text-muted

/* Espacements */
--spacing-xs, --spacing-sm, --spacing-md, --spacing-lg, --spacing-xl

/* Ombres */
--shadow-sm, --shadow-md, --shadow-lg, --shadow-accent

/* Transitions */
--transition-fast, --transition-normal, --transition-slow
```

## 📱 Support responsive

- Mobile first approach
- Breakpoints Bootstrap compatibles
- Animations réduites sur mobile
- Optimisations tactiles
