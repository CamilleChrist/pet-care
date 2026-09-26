# Pet Care

[![tests](https://github.com/CamilleChrist/pet-care/actions/workflows/tests.yml/badge.svg)](https://github.com/CamilleChrist/pet-care/actions/workflows/tests.yml)

Application de suivi de la santé des animaux de compagnie.

Chaque utilisateur crée un compte, y enregistre ses animaux (chien ou chat) et suit
dans le temps leur poids, leurs vaccins ainsi que leurs notes de santé.

## Fonctionnalités

- **Compte utilisateur** — inscription, connexion, réinitialisation du mot de
  passe par e-mail, édition du profil
- **Fiches animaux** — nom, espèce, race, date de naissance, sexe, photo, notes
  de santé et date de la dernière visite chez le vétérinaire
- **Suivi du poids** — une pesée à la fois, historique complet, et le poids le
  plus récent repris sur la fiche de l'animal
- **Suivi des vaccins** — date d'administration et date de rappel, avec les
  échéances proches et dépassées mises en avant sur le tableau de bord
- **Notifications** — rappel de vaccin 7 jours avant l'échéance, puis le jour
  même, pour penser à prendre rendez-vous chez le vétérinaire. Une semaine après
  l'échéance, si aucune nouvelle injection n'a été saisie, un dernier message
  demande si le rappel a été fait et invite à l'ajouter à la fiche. Les rappels
  du jour, tous animaux confondus, sont regroupés en un seul message, envoyé par
  e-mail et en notification push sur téléphone ou ordinateur (sur iPhone, une
  fois l'app ajoutée à l'écran d'accueil). Depuis son profil, chacun peut
  couper les e-mails et activer le push appareil par appareil
- **Espace d'administration** — sur `/admin-pet-care`, réservé aux comptes dont
  le rôle est `admin` : gestion des inscrits, de leurs animaux et des
  référentiels races et vaccins
- **Interface** — entièrement en français, utilisable sur mobile

## Stack technique

- **Backend :** PHP 8.3+, Laravel 13
- **Vues :** Blade
- **CSS :** Sass + BEM, compilé par Vite
- **Notifications :** e-mail et Web Push
  ([laravel-notification-channels/webpush](https://github.com/laravel-notification-channels/webpush))
- **Tests :** Pest
- **Style de code :** Laravel Pint

## Installation

### 1. Prérequis

- PHP 8.3 ou plus récent, avec Composer
- Node.js

Aucun serveur de base de données à installer : le projet utilise SQLite par
défaut, c'est-à-dire un simple fichier, `database/database.sqlite`, créé
automatiquement à l'étape suivante.

### 2. Cloner et installer

```bash
git clone <url-du-depot> pet-care
cd pet-care
composer setup
```

### 3. Créer le lien vers les images

```bash
php artisan storage:link
```

Les photos des animaux sont enregistrées dans `storage/app/public`, un dossier
situé hors de la racine web et donc invisible depuis un navigateur. Cette
commande crée un raccourci vers lui depuis `public/storage` ; sans elle, les
photos s'affichent en erreur 404. Une seule fois par clone du dépôt : le lien
est ignoré par Git.

### 4. Vérifier le `.env`

L'application fonctionne sans y toucher. Néanmoins, vous pouvez changer
certains paramètres ici (notamment les accès à la base de données).
- `DB_CONNECTION=sqlite` — pour utiliser MySQL ou PostgreSQL, renseigner à la
  place `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` et `DB_PASSWORD`,
  commentés juste en dessous. Aucun code n'est spécifique à SQLite.

> [!IMPORTANT]
> `composer setup` crée le `.env` **et** joue les migrations dans la foulée.
> Pour partir sur autre chose que SQLite, copier `.env.example` en `.env` et le
> modifier **avant** de lancer `composer setup`.

### 5. Générer les clés des notifications push

```bash
php artisan webpush:vapid
```

Les notifications push sont signées avec une paire de clés VAPID, propre à
chaque installation. La commande écrit `VAPID_PUBLIC_KEY` et `VAPID_PRIVATE_KEY`
dans le `.env`. Une seule fois par installation : si les clés changent, les
appareils déjà abonnés ne reçoivent plus rien.

> [!IMPORTANT]
> `VAPID_SUBJECT` doit être rempli avec une adresse `mailto:` ou une URL
> `https`, sinon l'iPhone refuse les notifications. Sans cette variable, le
> package prend l'URL du site, et Apple refuse une URL en `http://`.

### Réinitialiser la base

`php artisan db:seed` ne se rejoue pas sur une base déjà remplie : la table
`breeds` porte un index unique sur `[name, species]`, la seconde exécution
échoue sur un doublon. Pour repartir d'une base propre :

```bash
php artisan migrate:fresh --seed
```

`migrate:fresh` supprime toutes les tables, rejoue l'intégralité des migrations,
puis `--seed` relance `DatabaseSeeder` : les races, les vaccins, les comptes de
démonstration et leurs animaux.

> [!WARNING]
> Tout le contenu de la base est perdu, y compris les comptes et les animaux
> créés à la main. Les photos déjà envoyées restent, elles, dans
> `storage/app/public` — la base ne les référence simplement plus.

## Lancer le projet

```bash
composer dev
```

Cette commande démarre le serveur PHP, le worker de queue, les logs et Vite.
L'application est disponible sur http://localhost:8000.

> [!NOTE]
> **Comptes de démonstration**, mot de passe `password` pour les deux :
>
> - `test@example.com` — utilisateur, avec deux animaux, leur historique de
>   poids et leurs rappels de vaccin
> - `admin@example.com` — administrateur, accès au back-office sur
>   `/admin-pet-care`

## Lire les e-mails en local

Par défaut, `MAIL_MAILER=log` : aucun e-mail ne part sur le réseau. Laravel écrit
le message complet — en-têtes, version texte et version HTML — dans
`storage/logs/laravel.log`. 

> [!TIP]
> Pour une vraie boîte de réception, avec le rendu HTML des e-mails, installer
> [Mailpit](https://mailpit.axllent.org) (fourni avec Laravel Herd) puis passer
> le `.env` en `MAIL_MAILER=smtp`, `MAIL_HOST=127.0.0.1`, `MAIL_PORT=1025`. Les
> messages s'affichent sur http://localhost:8025.

## Rappels de vaccin en local

Les rappels partent d'une commande que le scheduler de Laravel lance chaque jour
à 8 h. Elle notifie les propriétaires dont un vaccin arrive à échéance dans 7
jours ou le jour même, et leur demande 7 jours après l'échéance si le rappel a
été fait. Chaque propriétaire reçoit au plus un e-mail et une notification par
jour, qui regroupent tous ses rappels. `composer dev` ne lance pas le scheduler : pour tester,
appeler la commande directement, ou démarrer le scheduler à côté.

```bash
php artisan app:vaccine-notification   # envoie les rappels du jour, tout de suite
php artisan schedule:work              # lance le scheduler (rappels chaque jour à 8 h)
```

Les notifications passent par la queue : le worker démarré par `composer dev`
doit tourner. Les e-mails arrivent comme expliqué dans la section précédente.

Pour recevoir les notifications push, activer « Sur cet appareil » dans la carte
« Notifications » du profil :

- **Sur ordinateur** (Chrome, Firefox, Safari), `http://localhost:8000` suffit.
- **Sur un téléphone**, le site doit être servi en HTTPS, par exemple avec
  `herd secure` ou un tunnel.
- **Sur iPhone** (iOS 16.4 ou plus récent), le push n'existe que dans l'app
  ajoutée à l'écran d'accueil. Dans Safari, Partager → « Sur l'écran
  d'accueil » depuis une page où l'on est connecté (la page d'accueil publique
  n'a pas de manifest), puis ouvrir PetCare depuis l'icône.

> [!NOTE]
> **En production**, il faut une tâche cron qui lance
> `php artisan schedule:run` chaque minute, un worker de queue permanent et le
> site en HTTPS.

## Tests

Les tests sont écrits avec [Pest](https://pestphp.com) et vivent dans
`tests/Feature`. Ils tournent sur une base SQLite en mémoire, recréée à chaque
test (`phpunit.xml`) : la base de développement n'est jamais touchée.

```bash
composer test
```

Le style du code est vérifié séparément, avec [Pint](https://laravel.com/docs/pint) :

```bash
vendor/bin/pint --test   # signale les écarts
vendor/bin/pint          # les corrige
```

## Feuille de route évolution

- [x] Rappels par e-mail et notification push avant l'échéance d'un vaccin
- [ ] Ajout des rappels de médicaments / vermifuges.
- [ ] Documents joints : ordonnances, comptes rendus vétérinaires
- [ ] Export PDF du carnet de santé d'un animal
- [ ] Version bureau et mobile avec NativePHP
