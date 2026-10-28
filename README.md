# WorkTogether Data Solutions

Application web de gestion de la **location de baies de datacenter** pour WorkTogether Data Solutions, développée avec **Symfony 7.4 (LTS)**, Doctrine ORM et Twig.

Ce dépôt contient la **phase 1 du projet** (démarrage), qui correspond aux 6 premières consignes : installation, repository, gestion de projet, entités et fixtures, authentification, pages de gestion des unités et baies.

> Le projet est en cours de développement. La liste des fonctionnalités restantes figure en fin de document.

---

## Contexte

WorkTogether Data Solutions gère des datacenters et lance un service de location de baies. Elle dispose de **30 baies de 42U** (B001 à B030). Les clients peuvent louer une baie complète, une demi-baie, un quart de baie ou des unités individuelles « U » (U01 en bas de la baie).

Offres commerciales prévues :

| Offre | Unités | Prix mensuel | Remise |
|---|---|---|---|
| Base | 1 | 100 € | 0 % |
| Start-up | 10 | 900 € | -10 % |
| PME | 21 | 1 680 € | -20 % |
| Entreprise | 42 | 2 940 € | -30 % |

La tarification annuelle ajoute 10 % de remise supplémentaire.

---

## Technologies

| Outil | Version |
|---|---|
| PHP | 8.4.0 |
| Symfony | 7.4 (LTS) |
| Doctrine ORM / Migrations / Fixtures | inclus avec `--webapp` |
| Twig | inclus avec `--webapp` |
| Base de données | MySQL (WampServer) |
| Gestion de version | Git / GitHub |

**Remarque :** Symfony 8.1 exige PHP 8.4.1 ou plus. L'environnement fournissant PHP 8.4.0, la version **LTS 7.4** a été retenue (stable, compatible et bien documentée).

---

## Prérequis

- PHP 8.4 ou plus
- Composer
- Symfony CLI
- MySQL / MariaDB (par exemple via WampServer)
- Git

---

## Installation

```bash
# 1. Récupérer le projet
git clone https://github.com/BLN53z00/W2Gether.git
cd W2Gether/worktogether

# 2. Installer les dépendances
composer install
```

3. Créer le fichier `.env.local` (non versionné) et y indiquer l'accès à la base de données :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/worktogether?serverVersion=8.0&charset=utf8mb4"
```

```bash
# 4. Créer la base et les tables
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# 5. Charger les données de test
php bin/console doctrine:fixtures:load

# 6. Lancer le serveur
symfony server:start -d
```

L'application est accessible sur `https://127.0.0.1:8000` (ou `http://127.0.0.1:8000` selon la configuration).

---

## Comptes de test

Créés par les fixtures :

| Rôle | E-mail | Mot de passe |
|---|---|---|
| Administrateur | `admin@worktogether.test` | `Admin123!` |
| Comptable | `compta@worktogether.test` | `Compta123!` |
| Client (Paul Durand) | `client@worktogether.test` | `Client123!` |
| Client (Sophie Martin) | `sophie@worktogether.test` | `Client123!` |
| Client (Luc Bernard) | `luc@worktogether.test` | `Client123!` |

Ces comptes servent uniquement au développement et aux tests.

---

## Modèle de données

```
Utilisateur (abstraite, héritage SINGLE_TABLE)
 ├── Administrateur   (ROLE_ADMIN)
 ├── Comptable        (ROLE_COMPTABLE)
 └── Client           (ROLE_CLIENT)

Client  1 ──── N  Commande  N ──── 1  Offre
Commande 1 ──── N  Unite
Baie     1 ──── N  Unite
```

| Entité | Rôle |
|---|---|
| `Utilisateur` | Classe abstraite : nom, prénom, e-mail (unique), mot de passe hashé |
| `Administrateur`, `Comptable`, `Client` | Rôles de l'application (le client possède aussi entreprise et téléphone) |
| `Baie` | Baie de datacenter (B001 à B030, 42 unités) |
| `Unite` | Unité « U » d'une baie : numéro, état, nom, type, couleur |
| `Offre` | Offre commerciale : nom, nombre d'unités, prix mensuel, remise, actif |
| `Commande` | Location d'une offre par un client : dates, périodicité, prix total |
| `EtatUnite` (enum) | `ok`, `incident`, `maintenance` |

### Fixtures

Une fixture par entité, chargées dans l'ordre grâce à `DependentFixtureInterface` :

| Fixture | Données créées |
|---|---|
| `UtilisateurFixtures` | 1 administrateur, 1 comptable, 3 clients |
| `OffreFixtures` | 4 offres (Base, Start-up, PME, Entreprise) |
| `BaieFixtures` | 30 baies (B001 à B030) |
| `UniteFixtures` | 1 260 unités (42 par baie) |
| `CommandeFixtures` | 3 commandes avec des unités contiguës dans une même baie |

---

## Sécurité et authentification

- Connexion par formulaire, protégée par un **jeton CSRF**
- Déconnexion
- Inscription d'un **compte Client** avec connexion automatique
- Mots de passe **hashés** (algorithme `auto`), 8 caractères minimum
- E-mail unique
- Hiérarchie des rôles : **Administrateur > Comptable**
- Contrôle d'accès par préfixe d'URL (`access_control`)
- Redirection selon le rôle après connexion

| URL | Rôle requis |
|---|---|
| `/admin` | `ROLE_ADMIN` |
| `/comptable` | `ROLE_COMPTABLE` |
| `/mon-espace` | `ROLE_CLIENT` |

---

## Pages disponibles

| URL | Description |
|---|---|
| `/login` | Connexion |
| `/register` | Création d'un compte Client |
| `/logout` | Déconnexion |
| `/redirection` | Redirige l'utilisateur vers son espace selon son rôle |
| `/mon-espace` | Tableau de bord client : nombre d'unités, de baies, incidents |
| `/mon-espace/unites` | Liste des unités du client (emplacement `B014-U06`, nom, type, couleur, état) |
| `/mon-espace/unites/{id}/modifier` | Modification du nom, du type et de la couleur d'une unité |
| `/mon-espace/baies` | Vue graphique des baies du client (42 emplacements, U01 en bas) |

Un client ne peut consulter et modifier **que ses propres unités** : un accès à l'unité d'un autre client renvoie une erreur 403.

---

## Structure du projet

```
worktogether/
├── config/packages/security.yaml   # firewall, rôles, access_control
├── migrations/                     # migrations Doctrine
├── src/
│   ├── Controller/                 # SecurityController, RegistrationController, EspaceClientController
│   ├── DataFixtures/               # une fixture par entité
│   ├── Entity/                     # entités Doctrine
│   ├── Enum/                       # EtatUnite
│   ├── Form/                       # RegistrationFormType, UniteType
│   └── Repository/                 # UniteRepository::findByClient()
└── templates/
    ├── registration/               # inscription
    ├── security/                   # connexion
    └── espace/                     # espace client
```

---

## Gestion de projet

- **Dépôt** : branche `main`, commits normalisés (`feat:`, `fix:`, `docs:`, `chore:`)
- **GitHub Projects** : board Kanban (Backlog, À faire, En cours, En revue, Terminé) et vue Roadmap
- **Milestones** correspondant aux 6 consignes :

| Milestone | Contenu |
|---|---|
| 1 | Installation du projet Symfony |
| 2 | Repository GitHub |
| 3 | Gestion de projet |
| 4 | Entités Doctrine et fixtures |
| 5 | Authentification |
| 6 | Pages de gestion des unités et baies |

- **Labels** : `back`, `front`, `sécurité`, `base de données`, `documentation`, `bug`

---

## Reste à développer

**Première situation professionnelle (application de gestion du personnel)**
- Administrateur : gestion des utilisateurs, des stocks de baies et des offres commerciales
- Comptable : consultation de la liste des clients et des commandes
- Protection renforcée des données personnelles et financières
- Optionnel : rapport mensuel des réservations, rapport d'occupation des baies

**Seconde situation professionnelle (site internet et API)**
- Site vitrine : présentation de la société et des offres
- Prise de commande, calcul du prix (mensuel, annuel -10 %) et attribution des unités
- Espace client complet : données personnelles, informations de connexion, commandes
- Entité `Intervention` et API client authentifiée (état des unités, historique des interventions)
- Optionnel : multilingue, centre de ressources, double authentification, tickets de support, évaluations

**Décisions de conception à documenter** : systèmes de paiement, facturation annuelle, commande de plusieurs packs, tacite reconduction, stratégie d'attribution des unités.

---

## Auteur

Projet réalisé dans le cadre de la formation SLAM, situation professionnelle « WorkTogether Data Solutions ».

