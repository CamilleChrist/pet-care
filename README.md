# Pet Care

Application de suivi de la santé des animaux de compagnie.

Chaque utilisateur crée un compte, y enregistre ses animaux (chien ou chat) et suit
dans le temps leur poids, leurs vaccins, leurs notes de santé et leurs visites
chez le vétérinaire.

> **État du projet :** v1 en cours de développement. Seule l'authentification est
> implémentée pour le moment (inscription, connexion, déconnexion, réinitialisation
> du mot de passe).

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
  Http/Controllers/   Contrôleurs (AuthController, …)
  Models/             Modèles Eloquent (User, …)
database/
  migrations/         Migrations de la base
  factories/          Factories pour les tests
resources/
  views/              Vues Blade (auth/, layouts/)
  css/ js/            Assets compilés par Vite
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

- [ ] Arriver sur son espace privé après connexion
- [ ] Naviguer entre les pages de l'application
- [ ] Réserver l'accès aux personnes connectées

### 3. Fiches animaux

- [ ] Ajouter un animal : nom, espèce, race, date de naissance, sexe
- [ ] Ajouter une photo à la fiche
- [ ] Renseigner les notes de santé et la date de la dernière visite chez le vétérinaire
- [ ] Consulter la fiche d'un animal
- [ ] Modifier et supprimer un animal
- [ ] Ne voir que ses propres animaux

### 4. Suivi du poids

- [ ] Enregistrer une pesée
- [ ] Consulter l'historique du poids d'un animal
- [ ] Voir sur la fiche le poids le plus récent

### 5. Suivi des vaccins

- [ ] Enregistrer un vaccin avec sa date d'administration
- [ ] Indiquer la date du prochain rappel
- [ ] Consulter l'historique des vaccins d'un animal
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
