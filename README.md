# Greencare (Pour les collègues)

## Installation
1. Cloner le projet
2. Installer les dépendances PHP : ```composer install```
3. Installer les dépendances front : ```à voir```
4. Créer la base(s) de données de dev : ```composer prepare-db```
5. Créer la base(s) de données de test : ```composer prepare-test```


## Commit
Le projet a été configuré de sorte à respecter les normes de programmation (PSR pour PHP).
Des packages ont été installés pour analyser (PHPStan) et réparer les parties de code ne respectant pas les normes (PHPCSFixer). Ces process se lanceront avant chaque commit, ne vous étonnez donc pas si un commit échoue, il s'agit juste d'un non-respect des normes détecté 😉.

## Test
Le projet implémentera différents tests pour s'assurer de la qualité et du fonctionnement des features. Ils se lanceront avant chaque commit également, pour s'assurer que de la qualité du commit effectué, et avant chaque déploiement automatique.


By WilFrite