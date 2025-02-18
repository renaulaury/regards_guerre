# Projet de Gestion d'Expositions et de Réservations

## Description
Ce projet est une plateforme de gestion d'expositions permettant aux administrateurs de créer, modifier et gérer des expositions. Les visiteurs peuvent réserver des tickets et les ajouter à un panier afin de les réserver. Une interface d'administration est disponible pour gérer les expositions, les utilisateurs et les réservations.

---

## Fonctionnalités Principales (MVP)

### Gestion des Expositions
- Création, modification, suppression d'expositions (CRUD)
- Association des œuvres et des artistes aux expositions
- Génération dynamique des pages d'exposition

### Réservation de Tickets
- Sélection et ajout de tickets dans un panier
- Affichage du panier avec les tickets sélectionnés
- Validation des réservations (sans paiement pour le MVP)
- Gestion avancée des stocks de tickets

### Gestion des Utilisateurs
- Système d’authentification pour l’administration (connexion, sessions, protection des pages admin)
- Différenciation des rôles : admin root, gestionnaires, utilisateurs

### Interface Admin
- Tableau de bord pour la gestion des expositions et des réservations
- Gestion des utilisateurs et des rôles
- Visualisation des réservations effectuées

---

## Fonctionnalités Techniques

### Backend (Symfony 7)
- Framework : **Symfony 7**
- Base de données : **MySQL**
- ORM : **Doctrine**
- Gestion des sessions et de l’authentification : **SecurityBundle**
- Système de routing et de templating avec **Twig**

### Frontend
- HTML/CSS 
- JavaScript pour certaines interactions dynamiques 

### Déploiement et Outils
- Serveur local : **Laragon**
- Gestion des dépendances : **Composer** (backend) 
- Versionnement : **Git/GitHub**
- PHP 

---

## Installation et Configuration

### Prérequis
- PHP 8+
- Composer
- MySQL
- Symfony CLI
- Symfony 7

### Étapes d’Installation
1. **Cloner le projet**
   ```sh
   git clone https://github.com/ton-utilisateur/nom-du-projet.git
   cd nom-du-projet
   ```
2. **Installer les dépendances Symfony**
   ```sh
   composer install
   ```
3. **Configurer l'environnement**
   - Copier le fichier `.env` en `.env.local`
   - Modifier les informations de connexion à la base de données
4. **Créer la base de données**
   ```sh
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```
5. **Lancer le serveur Symfony**
   ```sh
   symfony server:start
   ```
6. **Installer les dépendances frontend**
   ```sh
   npm install
   ```
7. **Lancer le serveur de développement frontend (si nécessaire)**
   ```sh
   npm run dev
   ```

---

## Utilisation
- Accéder au back-office : `http://localhost:8000/admin`
- Gérer les expositions et les réservations depuis l’interface admin
- Simuler un utilisateur visitant les expositions et réservant des tickets

---

## Améliorations Futures (Should Have)
- Paiement en ligne via Stripe
- Gestion des fournisseurs et commandes
- Mise en place d'un shop en ligne

---

Projet d'examen de développeur web et web mobile avec Elan Formation.


