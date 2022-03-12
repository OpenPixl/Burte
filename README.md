# OpenPixl - Stack Docker
<small>pour la production de site Symfony5 et Symfony6</small>

**Pour du DEV ou du PROD**

Construit à partir de l'image php8:apache et largement inspiré du dépot de [@yoanbernabeu](https://github.com/yoanbernabeu).  
Merci à lui pour son travail.

A venir prochainemement :
- [ ] Ajout du port https,

### AVANT LA PROCEDURE
Cloner le projet dans votre dossier de dev ou de prod
```bash
  git@github.com:Corwin40/StackSymfony.git
```
Le fichier _docker-compose.yaml_ s'appuie sur un fichier _.env_ contenant vos futures variables nécessaire : à la base de donnée, aux differents ports attribués à vos conteneurs, ...
Créez ce fichier avec les commandes suivantes :

```
  cd StackSymfony
  nano .env
```
Puis copier/coller le contenu suivant.

```
# Variable Project
PROJECT="nom_project"

# Variables Mariadb
PMA_HOST=db
MARIA_ROOT_PASSWORD="votre_mot_de_passe"
MARIADB_USER="votre_nom_user"
MARIADB_PASSWORD="votre_password_user"
MARIADB_DBNAME="votre_nom_db"

# Variables Serveur apache php
HTTP_HOST_PORT=80
```

### PROCEDURE DE DEPLOIEMENT

#### I. Docker-compose
Lancer docker-compose avec la commande suivante.  
La commande _build_ va construire l'image de votre serveur www contenant apache, php8, composer, symfony et yarn. Ainsi que l'ensemble des extensions nécessaire à _PHP_ et les _frameworks_ actuels. 

```bash
  docker-compose build
  docker-compose up -d
```
#### II. déploiement du projet

##### II. A déploiement d'un nouveau projet Symfony pour développement
Connectez-vous au terminal du conteneur PHP, à partir de ce point, toutes les commandes se feront depuis le terminal du conteneur en root.  
**Attention** : utilisez le nom du conteneur approprié selon votre projet.

```bash
  docker exec -it "nom_conteneur_www" bash
```

Utilisez la commande du _CLI Symfony_ pour créer votre nouveau projet. 
**Attention** : donner un nom différent à votre projet sans les guillements.

```bash
  symfony new "nouveau_projet" --full
```

> Cette partie n'a pas encore été testée concernant le serveur interne à symfony.  

```
  cd new-project
  symfony serve -d
```

Pour assurer votre développement futur depuis un IDE, il vous faut créer un compte _user_ dans votre conteneur. Ce dernier sera identique à votre session Linux et et nous lui donnerons les droits d'accès dans ce conteneur.

```bash
  adduser username
  chown username:username -R .
```

*L'application devrait être accessible à cette adresse : [http://127.0.0.1:8001](http://127.0.0.1:8001)*

##### II. B déploiement d'un projet Symfony existant depuis votre dépot Git.


##### Connexion de l'application à la basse de données. 
apr 

```yaml
  DATABASE_URL="postgresql://symfony:ChangeMe@database:5432/app?serverVersion=13&charset=utf8"
```

## Ready to use with

This docker-compose provides you :

- PHP-8.0.13-cli (Debian)
    - Composer
    - Symfony CLI
    - and some other php extentions
    - nodejs, npm, yarn
- postgres:13-alpine
- mailcatcher


## Requirements

Out of the box, this docker-compose is designed for a Linux operating system, provide adaptations for a Mac or Windows environment.

- Linux (Ubuntu 20.04 or other)
- Docker
- Docker-compose
## Author
- xavier Burke - OpenPixl.fr    |     [Email](xavier.burke@openpixl.fr)  /  [Web](ww.openpixl.fr)
