# myWishList

Projet PHP @IUT-NC 2020/2021

-----------------

## Installation locale

-> Utiliser [composer](https://getcomposer.org/) pour installer MyWishList.

```bash
git clone git@github.com:liza02/myWishList.git
cd myWishList
composer install
```

-> Créer/remplacer le fichier config/**conf.ini** pour accéder à la base de donnée dans le répertoire:

```ini
driver=mysql
username=root
password=
host=localhost
database=mywishlist
charset=utf8
collation=utf8_unicode_ci
```

| Paramètre     | Valeur d'exemple | Description               |
| :------------:|:----------------:|:-------------------------:|
| driver        | mysql            | Driver de votre SGBD      |
| host          | localhost        | Hôte de votre BDD         |
| database      | mywishlist       | Nom de votre BDD          |
| username      | root             | Nom d'user de votre BDD   |
| password      |                  | Mot de passe de votre BDD |
| charset       | utf8             | Méthode d'encodage        |
| collation     | utf8_unicode_ci  | Collation de la BDD       |

-----------------

## Utilisation

-> Lancer un serveur XAMPP, importez le fichier de création de la base MySQL ([mywishlist.sql](https://github.com/liza02/myWishList/blob/main/mywishlist.sql)), executez le et connectez-vous sur le site via index.php

OU

-> Accéder au site en ligne : [disponible ici](https://alessi.cailacmaxime.ovh/)

OU 

-> Accéder au site hebergé sur Webetu : *disponible prochainement*


## Jeu de données pour tester

### Comptes
Login : *alessi* | Mot de passe : *aless*

Login : *theo* | Mot de passe : *theo*

Login : *lisa* | Mot de passe : *lisa*

Login : *thomas* | Mot de passe : *test*

### Listes 
Liste 1 - Pour fêter le bac ! | Message de Jean

Liste 2 - Liste de mariage d'Alice et de Bob - Cagnotte sur le premier item

Liste 3 - C'est l'anniversaire de Charlie

Liste 4 - Pour le déconfinement

Liste 5 - Ma liste pour plus tard | Privée

Liste 6 - Noël 2017 | Privée et Expirée 


-----------------

## Fiche de suivi du projet

[Lien vers fiche excel en ligne](https://docs.google.com/spreadsheets/d/1c6Gno93pC22lAoNt-PZcFQJxETBG9XXCOx5xNwqz7ko/edit?usp=sharing)

-----------------

## Techniques et concepts

- [x] *architecture MVC (Modèle - Vue - Controleur)*
- [x] *utilisation de l'ORM Eloquent*
- [x] *utilisation d'un micro framework Slim 3*
- [x] *utilisation de Bootstrap*

-----------------

## Fonctionnalités

### Participant

- [x] *Afficher une liste de souhaits*
- [x] *Afficher un item d'une liste*
- [x] *Réserver un item*
- [x] *Ajouter un message avec sa réservation*
- [x] *Ajouter un message sur une liste*

### Créateur
- [x] *Créer une liste* 
- [x] *Modifier les informations générales d'une de ses listes* 
- [x] *Ajouter des items*
- [x] *Modifier un item*
- [x] *Supprimer un item* 
- [x] *Rajouter une image à un item* 
- [x] *Modifier une image à un item* 
- [x] *Supprimer une image d'un item* 
- [x] *Partager une liste*
- [x] *Consulter les réservations d'une de ses listes avant échéance* 
- [x] *Consulter les réservations et messages d'une de ses listes après échéance* 

### Extensions
- [x] *Créer un compte* 
- [x] *S'authentifier* 
- [x] *Modifier son compte* 
- [x] *Rendre une liste publique* 
- [x] *Afficher les listes de souhaits publiques* 
- [x] *Créer une cagnotte sur un item*
- [x] *Participer à une cagnotte*
- [x] *Uploader une image*
- [ ] *Créer un compte participant*
- [x] *Afficher la liste des créateurs*
- [x] *Supprimer son compte*
- [ ] *Joindre les listes à son compte*

### Nos extensions
- [x] *Supprimer une liste*
- [x] *Modifier le mot de passe*
- [x] *Se déconnecter du compte*
- [x] *Afficher toutes nos listes*
- [x] *Design*
- [x] *Maquettage*
- [x] *Débogage*

-----------------

## Contributions
**CARRIER Lisa** - S3D @[liza02](https://github.com/liza02/myWishList/commits?author=liza02)

**DEMANGE Alessi** - S3C @[aless57](https://github.com/liza02/myWishList/commits?author=aless57)

**SLIMANI Théo** - S3D @[theosli](https://github.com/liza02/myWishList/commits?author=theosli)


**RZEPKA Thomas** - S3D @[rzepka2u](https://github.com/liza02/myWishList/commits?author=rzepka2u)