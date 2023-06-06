# Greencare (Pour les collègues)

## Installation
1. Cloner le projet
2. Démarrer son serveur Apache
3. Installer les dépendances PHP : ```composer install```
4. Installer les dépendances front : ```à voir```
5. Créer les fichiers ```.env.local```  à la racine
6. Copier le contenu de .env et coller dans les 2 fichiers de l'étape 5 
7. Décommenter MySQL et commenter Postgres dans le nouveau .env 
8. Update le lien avec ses infos (username MySQL, password, et nom de la base de données)
9. Créer un nouveau fichier ```.env.test.local``` et copier le contenu de ```.env.local``` dans ce nouveau fichier 
10. Créer la base(s) de données de dev : ```composer prepare-db```
11. Créer la base(s) de données de test : ```composer prepare-test```


## Commit
Le projet a été configuré de sorte à respecter les normes de programmation (PSR pour PHP).
Des packages ont été installés pour analyser (PHPStan) et réparer les parties de code ne respectant pas les normes (PHPCSFixer). Ces process se lanceront avant chaque commit, ne vous étonnez donc pas si un commit échoue, il s'agit juste d'un non-respect des normes détecté 😉.

## Test
Le projet implémentera différents tests pour s'assurer de la qualité et du fonctionnement des features. Ils se lanceront avant chaque commit également, pour s'assurer que de la qualité du commit effectué, et avant chaque déploiement automatique.


By WilFrite
