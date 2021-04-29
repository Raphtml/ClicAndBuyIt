# Test technique Clic and Fit

## Environnement de développement

###Pré-requis

* PHP 7.4
* Composer
* Symfony CLI
* Docker
* Docker-compose

###Lancer l'environnement de développement

Lancer les commandes suivantes dans cet ordre :

````bash
composer install
yarn install
yarn encore dev
docker compose up -d
symfony serve -d
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
````

Un user est déjà créé avec les fixtures <br>
email : postmaster@clicandbuyit.fr <br>
mot de passe: password