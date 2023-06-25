# Greencare (Pour les collègues)

## Configuration et Installation

1. Cloner le projet
2. Démarrer son serveur Apache
3. Installer les dépendances PHP : `composer install`
4. Installer les dépendances front : `à voir`
5. Créer les fichiers `.env.local` à la racine
6. Copier le contenu de .env et coller dans le fichier de l'étape 5
7. Décommenter MySQL et commenter Postgres dans le nouveau .env (LA BASE EST CRÉÉE PAR DOCTRINE)
8. Update le lien avec ses infos (username MySQL, password, et nom de la base de données)
9. Créer un nouveau fichier `.env.test.local` et copier le contenu de `.env.local` dans ce nouveau fichier
10. Créer la base(s) de données de dev : `composer prepare-db`
11. Créer la base(s) de données de test : `composer prepare-test`

## Normes de codage et Protocole de commit

Le projet a été configuré de sorte à respecter les normes de programmation (PSR pour PHP).
Nous utilisons un make file exploitant le conteneur `jakzal/phpqa:php8.2` pour analyser (PHPStan) et réparer les parties de code ne respectant pas les normes (PHPCSFixer). Ces process se lanceront avant chaque commit, ne vous étonnez donc pas si un commit échoue, il s'agit juste d'un non-respect des normes détecté 😉.

## Test

Pour assurer la robustesse et la fonctionnalité de nos fonctionnalités, nous mettrons en œuvre divers tests. Ces tests seront lancés avant chaque commit pour assurer la qualité du code commité, et avant chaque déploiement automatique.

Assurez-vous que votre code passe tous les tests avant de commit.

## Contrôles de Qualité de Code et Utilisation de Makefile

Pour garantir la qualité de votre code avant de commit, vous devrez installer Chocolatey et utiliser un Makefile pour effectuer une analyse de code statique.

Tout d'abord, installez `Chocolatey` en suivant les instructions sur Installation de Chocolatey.

Ensuite, avant chaque commit, lancez la commande `make before-commit`. Cela déclenchera PHPStan pour une analyse de code statique afin de garantir que votre code respecte nos normes de qualité.

## Note

Ce guide de configuration est sujet à modifications au fur et à mesure de l'évolution du projet, alors assurez-vous toujours d'être à jour avec le processus de configuration actuel du projet.

By l'equipe
