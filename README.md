# Pet Care

Application de suivi de la santé des animaux de compagnie.

Chaque utilisateur crée un compte, y enregistre ses animaux (chien ou chat) et suit
dans le temps leur poids, leurs vaccins, leurs notes de santé et leurs visites
chez le vétérinaire.

> **État du projet :** v1 en cours de développement. L'authentification, la gestion
> des fiches animaux, le suivi du poids et le suivi des vaccins sont fonctionnels.
> Restent les finitions : tableau de bord récapitulatif, mise en avant des rappels
> de vaccin à venir/dépassés, poids le plus récent affiché sur la fiche animal,
> traduction complète en français et données de démonstration. Le design de
> l'interface est également en cours.

## Fonctionnalités de la v1

### Compte utilisateur

- Inscription : nom, prénom, e-mail, mot de passe
- Connexion / déconnexion
- Réinitialisation du mot de passe par e-mail

### Animaux

Chaque animal appartient à un utilisateur et comporte :

- Nom
- Espèce : chien ou chat
- Race
- Date de naissance
- Sexe
- Poids actuel (issu de la dernière pesée enregistrée)
- Photo
- Vaccins
- Notes de santé
- Date de la dernière visite chez le vétérinaire

### Historiques

- Historique du poids de chaque animal
- Historique des vaccins de chaque animal

## Modèle de données (cible v1)

| Table | Clés | Contenu | Relations |
| --- | --- | --- | --- |
| `users` | `id` | `firstname`, `lastname`, `email`, `password` | possède plusieurs `pets` |
| `pets` | `id`, `user_id` | `name`, `species`, `breed`, `birth_date`, `sex`, `photo_path`, `health_notes`, `last_vet_visit_at` | appartient à un `user` |
| `weight_records` | `id`, `pet_id` | `weight`, `measured_at` | appartient à un `pet` |
| `vaccination_records` | `id`, `pet_id` | `vaccine_name`, `administered_at`, `next_due_at` | appartient à un `pet` |

Le poids actuel de l'animal n'est pas stocké sur sa fiche : c'est toujours l'entrée
la plus récente de son historique de poids.

## Stack technique

- **Backend :** PHP 8.3+, Laravel 13
- **Vues :** Blade
- **CSS :** Tailwind CSS 4, compilé par Vite
- **Base de données :** SQLite (par défaut, en local)
- **Tests :** Pest
- **Style de code :** Laravel Pint

### Évolution envisagée

À plus long terme, l'application pourra être empaquetée en application de bureau
et mobile avec [NativePHP](https://nativephp.com). Ce n'est pas au programme de la
v1 : pour l'instant, il s'agit d'une application web exécutée en local.

## Installation

Prérequis : PHP 8.3+, Composer et Node.js.

```bash
git clone <url-du-depot> pet-care
cd pet-care
composer setup
```

Le script `composer setup` installe les dépendances PHP et JS, crée le fichier
`.env`, génère la clé d'application, joue les migrations et compile les assets.

## Lancer le projet

```bash
composer dev
```

Cette commande démarre le serveur PHP, le worker de queue, les logs et Vite.
L'application est disponible sur http://localhost:8000.

## Tests

```bash
composer test
```

## Structure du projet

```
app/
  Enums/              Enums (PetGender, …)
  Http/Controllers/   Contrôleurs (AuthController, PetController, WeightRecordController, VaccinationRecordController)
  Http/Requests/      Form Requests de validation
  Models/             Modèles Eloquent (User, Pet, Breed, WeightRecord, Vaccine, VaccinationRecord)
  Policies/           Policies d'autorisation (une par ressource appartenant à un pet)
database/
  data/               Données de référence statiques (races, vaccins)
  migrations/         Migrations de la base
  factories/          Factories pour les tests
  seeders/            Seeders (races et vaccins de référence)
resources/
  views/              Vues Blade (auth/, pets/, weight-records/, vaccination-records/, layouts/)
  css/ js/            Assets compilés par Vite (Tailwind pour l'app, Sass pour la landing page)
routes/
  web.php             Routes web
tests/                Tests Pest
```

## Feuille de route

La v1 avance par étapes, chacune apportant quelque chose d'utilisable.

### 1. Compte utilisateur

- [x] Créer un compte
- [x] Se connecter et se déconnecter
- [x] Réinitialiser son mot de passe par e-mail

### 2. Espace personnel

- [x] Arriver sur son espace privé après connexion
- [x] Naviguer entre les pages de l'application
- [x] Réserver l'accès aux personnes connectées

### 3. Fiches animaux

- [x] Ajouter un animal : nom, espèce, race, date de naissance, sexe
- [x] Ajouter une photo à la fiche
- [x] Renseigner les notes de santé et la date de la dernière visite chez le vétérinaire
- [x] Consulter la fiche d'un animal
- [x] Modifier et supprimer un animal
- [x] Ne voir que ses propres animaux

### 4. Suivi du poids

- [x] Enregistrer une pesée
- [x] Consulter l'historique du poids d'un animal
- [ ] Voir sur la fiche le poids le plus récent

### 5. Suivi des vaccins

- [x] Enregistrer un vaccin avec sa date d'administration
- [x] Indiquer la date du prochain rappel
- [x] Consulter l'historique des vaccins d'un animal
- [ ] Repérer les rappels à venir et ceux dépassés

### 6. Finitions

- [ ] Voir sur le tableau de bord le poids actuel et le prochain rappel de chaque animal
- [ ] Disposer d'une application entièrement en français
- [ ] Utiliser l'application confortablement sur mobile
- [ ] Démarrer avec des données de démonstration

## Au-delà de la v1

Pistes à trancher une fois la v1 livrée :

- [ ] Rappels par e-mail avant l'échéance d'un vaccin
- [ ] D'autres espèces que le chien et le chat
- [ ] Documents joints : ordonnances, comptes rendus vétérinaires
- [ ] Export PDF du carnet de santé d'un animal
- [ ] Partage d'un animal entre plusieurs personnes (foyer, garde partagée)
- [ ] Version bureau et mobile avec NativePHP
