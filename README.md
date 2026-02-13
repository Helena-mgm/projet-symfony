# Projet Symfony — TD Symfony

## Vue d'ensemble

- Framework : Symfony
- Langage : PHP
- ORM : Doctrine
- Fixtures : DoctrineFixturesBundle
- Auth : système utilisateur (Users entity). Lexik JWT peut être utilisé pour les API si nécessaire.
- Front : templates Twig, Bootstrap 5 

## Prérequis

- PHP 8.1+ (vérifier avec `php -v`)
- Composer (`composer`) installé
- Symfony CLI (optionnel mais recommandé) : https://symfony.com/download
- Une base de données (sqlite/postgres/mysql) configurée via `DATABASE_URL` dans `.env.local`

## Première installation (nouveau dev)

Ouvrez un terminal à la racine du projet et exécutez :

```bash
# installer les dépendances PHP
composer install
```

Copiez le fichier d'environnement et ajustez les variables :

```bash
cp .env .env.local
# éditez .env.local et mettez DATABASE_URL, etc.
```

### Clé JWT (si vous utilisez JWT pour l'API)

Le projet peut utiliser LexikJWTAuthenticationBundle. Si vous devez (re)générer les clés :

```bash
# génère private.pem et public.pem dans config/jwt
php bin/console lexik:jwt:generate-keypair
```

Ajoutez ensuite dans `.env.local` (exemple) :

```
JWT_PRIVATE_KEY_PATH=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY_PATH=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=VotrePassphraseIci
```

> Attention : ne commitez pas `config/jwt/private.pem` dans Git.

## Base de données

Créer la base / appliquer les migrations :

```bash
# créer la base (si nécessaire)
php bin/console doctrine:database:create

# générer une migration si vous avez changé des entités
php bin/console make:migration

# exécuter les migrations
php bin/console doctrine:migrations:migrate
```

## Charger les fixtures

Pour remplir la base avec des données d'exemple :

```bash
# purge et chargement (non interactif)
php bin/console doctrine:fixtures:load --no-interaction

# ou charger un groupe particulier (ex : PostFixtures)
php bin/console doctrine:fixtures:load --group=PostFixtures
```

## Lancer l'application

Avec Symfony CLI (recommandé) :

```bash
symfony server:start
# puis ouvrir http://localhost:8000
```

Ou avec le serveur PHP intégré :

```bash
php -S 127.0.0.1:8000 -t public
```

## Commandes utiles

- Lister les routes : `php bin/console debug:router`
- Vérifier la syntaxe PHP d'un fichier : `php -l path/to/file.php`
- Vérifier les validations : `php bin/console debug:validator App\\Entity\\Users`
- Nettoyer le cache : `php bin/console cache:clear`

## Architecture rapide

- `src/Controller/` : contrôleurs Symfony (Admin, Post, Users, etc.)
- `src/Entity/` : entités Doctrine (Users, Post, Category, Comments)
- `src/Form/` : formulaires Symfony (RegistrationFormType, PostType, ...)
- `src/DataFixtures/` : fixtures pour pré-remplir la DB
- `templates/` : Twig templates (partial/navbar.html.twig, admin/*, home/* ...)
- `config/` : configuration (security.yaml, packages/...)

## Particularités du projet

- Validation : les messages de validation sont dans `translations/validators.*.yaml` pour l'internationalisation.
- Admin panel : accessible à `/admin` et protégé par `ROLE_ADMIN`.
- Profil utilisateur : route `/profile` pour consulter/modifier son profil.
