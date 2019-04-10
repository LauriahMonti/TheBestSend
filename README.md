# TheBestSend
Projet création site le bon coin avec symfony 3
composer create-project symfony/website-skeleton boncoin
composer require --dev symfony/web-server-bundle
phpstorm + install symfony driver

Le projet comporte :
● Réalisation des pages FAQ et CGU
● Un menu de navigation
● Entité Ad (annonce) avec les champs suivants :
o Title
o Description
o City
o Zip
o Price
o DateCreated
● Page "Déposer une annonce" avec creation de formulaire et traitement
● Page "Liste des annonces" par ordre de date de sortie
● Page "Détail d'une annonce"
● Entité Category, les annonces dispose d'une catégorie, relation OneToMany, choix de catégorie dans l'ajout d'une annonce.
● Entité User avec les champs suivants :
o Username
o Email
o Password
o Roles
o DateRegistered
● Page d'inscription , création et traitement du formulaire.
● Page de connexion
● Déconnexion
● Sécurisation de la page de dépôt d'annonce
● Relation entre User et Ad OneToMany
● Page "Mes annonces"
● Bouton "Ajouter à mes favoris"
● Relation entre User et Ad, pour les favoris. Entité Favoris avec relation OneToMany entre User et Ad
● Page "Mes favoris"


