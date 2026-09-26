# Pet Care

Application de suivi de la santé des animaux de compagnie.

Chaque utilisateur crée un compte, y enregistre ses animaux (chien ou chat) et suit
dans le temps leur poids, leurs vaccins, leurs notes de santé et leurs visites
chez le vétérinaire.

> **État du projet :** v1 presque terminée. L'authentification, la gestion des
> fiches animaux, le suivi du poids et le suivi des vaccins sont fonctionnels, de
> même que le tableau de bord récapitulatif, la mise en avant des rappels de vaccin
> à venir/dépassés, le poids le plus récent sur la fiche animal, l'édition du
> profil, l'espace d'administration, la traduction en français, l'usage confortable
> sur mobile et l'intégration des maquettes. Reste une finition : les données de
> démonstration, aujourd'hui inexistantes — `composer setup` ne sème rien et
> `php artisan db:seed` ne crée que deux comptes, sans race ni vaccin.

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

### Espace d'administration

Réservé aux comptes dont le rôle est `admin`, sur `/admin-pet-care`. Il ouvre
directement sur la liste des inscrits ; il n'y a pas de tableau de bord.

- Utilisateurs : liste, fiche avec ses animaux, modification du nom, de l'e-mail
  et du rôle, suppression du compte (un admin ne peut pas supprimer le sien)
- Animaux : liste filtrable par propriétaire, fiche regroupant ses vaccinations
  et ses pesées, création, modification, suppression
- Races et vaccins : gestion des deux référentiels, qui n'étaient jusque-là
  modifiables qu'en éditant un seeder
- Vaccinations et pesées : ajoutées et corrigées depuis la fiche de l'animal,
  elles n'ont pas de liste à part

Un admin reste lui-même : le back-office a ses propres écrans et ne passe pas
par les comptes des utilisateurs.

## Modèle de données (cible v1)

| Table | Clés | Contenu | Relations |
| --- | --- | --- | --- |
| `users` | `id` | `name`, `email`, `password`, `role` (`user`/`admin`) | possède plusieurs `pets` |
| `pets` | `id`, `user_id`, `breed_id` | `name`, `gender`, `birth_date`, `photo_path`, `health_notes`, `last_vet_visit_at` | appartient à un `user` et à une `breed` |
| `weight_records` | `id`, `pet_id` | `weight`, `recorded_at` | appartient à un `pet` |
| `breeds` | `id` | `name`, `species` (`dog`/`cat`) | référencée par les `pets` |
| `vaccines` | `id` | `name`, `species`, `description` | catalogue par espèce, référencé par les `vaccination_records` |
| `vaccination_records` | `id`, `pet_id`, `vaccine_id` | `custom_name`, `administered_at`, `next_due_at`, `veterinarian_name`, `clinic_name`, `lot_number`, `notes` | appartient à un `pet`, référence une `vaccine` du catalogue (ou `custom_name` si absente) |

Le poids actuel de l'animal n'est pas stocké sur sa fiche : c'est toujours l'entrée
la plus récente de son historique de poids.

## Stack technique

- **Backend :** PHP 8.3+, Laravel 13
- **Vues :** Blade
- **CSS :** Sass + BEM, compilé par Vite (migration depuis Tailwind CSS 4 terminée, Tailwind n'est plus chargé)
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

## Feuille de route

La v1 avance par étapes, chacune apportant quelque chose d'utilisable.

- [x] **Compte utilisateur** — inscription, connexion/déconnexion, réinitialisation du mot de passe par e-mail
- [x] **Espace personnel** — accès réservé aux personnes connectées, navigation entre les pages
- [x] **Fiches animaux** — création (nom, espèce, race, naissance, sexe), photo, notes de santé et dernière visite, consultation, modification, suppression, cloisonnement par propriétaire
- [x] **Suivi du poids** — enregistrement d'une pesée, historique, poids le plus récent sur la fiche
- [x] **Suivi des vaccins** — administration et date de rappel, historique, repérage des rappels à venir et dépassés

### Finitions

- [x] Voir sur le tableau de bord le poids actuel et le prochain rappel de chaque animal
- [x] Disposer d'une application entièrement en français
- [x] Éditer son profil
- [x] Utiliser l'application confortablement sur mobile
- [x] Intégrer les maquettes
- [x] Administrer les comptes, les animaux et les référentiels races/vaccins
- [ ] Démarrer avec des données de démonstration

## Au-delà de la v1

Pistes à trancher une fois la v1 livrée :

- [ ] Rappels par e-mail avant l'échéance d'un vaccin
- [ ] D'autres espèces que le chien et le chat
- [ ] Documents joints : ordonnances, comptes rendus vétérinaires
- [ ] Export PDF du carnet de santé d'un animal
- [ ] Partage d'un animal entre plusieurs personnes (foyer, garde partagée)
- [ ] Réinitialisation du mot de passe d'un utilisateur depuis l'espace d'administration
- [ ] Version bureau et mobile avec NativePHP
