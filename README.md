# Touche pas au klaxon

## Objectif
L'objectif du site "Touche Pas Au Klaxon" est de pouvoir organiser du covoiturage au sein d'une entreprise.

## Installation
Pour installer cette application web, deux moyen s’offre a vous:

### Xampp/lampp

#### Linux

```sh
cd /opt/lampp/htdocs; git clone "https://github.com/Corentin-pqt87/Touche_Pas_Au_Klaxon.git"
```

##### 1.
Déplacer vous dans le dossier de xampp/lampp:
```sh
cd /opt/lampp/htdocs
```

##### 2.
Télécharger le projet via GitHub$\to$Code$\to$ [Download Zip](https://github.com/Corentin-pqt87/Touche_Pas_Au_Klaxon/archive/refs/heads/main.zip) ou via la commande suivante
```sh
git clone "https://github.com/Corentin-pqt87/Touche_Pas_Au_Klaxon.git"
```
##### 3.
Initialiser la base de donnée en vous rendent sur la page phpmyadmin et créer une nouvelle base de donnée nommé `TPAK`.\
Vous trouverez dans [`./Db/dump-TPAK-202607011730.sql`](./Db/dump-TPAK-202607011730.sql) toute la base de donnée initiale.
##### 4.
Ajouter à la racine du projet un fichier nommé `.env` qui aura comme contenue les accès a la base de donnée
*Exemple :*
```
DB_HOST=localhost
DB_PORT=33306
DB_NAME=TPAK
DB_USER=name
DB_PASSWORD=password
```

---

### Local
##### 1.
Déplacez vous dans le répertoire que vous souhaiter
##### 2.
Télécharger le projet via GitHub$\to$Code$\to$ [Download Zip](https://github.com/Corentin-pqt87/Touche_Pas_Au_Klaxon/archive/refs/heads/main.zip) ou via la commande suivante
```sh
git clone "https://github.com/Corentin-pqt87/Touche_Pas_Au_Klaxon.git"
```
##### 3.
Initialiser la base de donnée en vous rendent sur l'application MySQL ou DBeaver.
Crée une nouvelle base de donnée nommé `TPAK`.
Pour DBeaver faite clic droit sur la base de donnée puis **outil$\to$Restore database** et sélectionner le fichier [`./Db/dump-TPAK-202607011730.sql`](./Db/dump-TPAK-202607011730.sql).

##### 4.
jouter à la racine du projet un fichier nommé `.env` qui aura comme contenue les accès a la base de donnée
*Exemple :*
```
DB_HOST=localhost
DB_PORT=33306
DB_NAME=TPAK
DB_USER=name
DB_PASSWORD=password
```

## Lancement du projet

### local
Pour exécuter le projet, vous trouverez 2 fichiers.
1. [`./Db/start.sh`](./Db/start.sh) : sert à l'ancer la base de donnée si cela n'est pas déja fait.
2. [`./run.sh`](./run.sh) : sert à l'ancer le site internet.
3. aller sur [`http://localhost:8000/`](http://localhost:8000/), vous serez automatiquement rediriger sur la page d'accueil.

## Ajouter un/des compte(s) administrateur
Pour ajouter un compte administrateur, connecter vous a votre base de donnée SQL et sur la page du internet du projet. Cliquer sur le bouton "inscription" et remplissez le formulaire. Une fois le compte créer et ajouter automatiquement à la base de donnée rendez vous sur votre application de gestion de de base de donnée SQL (MySQL ou DBeaver) puis sur la table "users" changer la valeur de "idrole" de 2 à 1 pour votre compte administrateur.\
Une fois fait, sur le site déconnectez vous et reconnectez vous, vous serait désormais en cession administrateur.
