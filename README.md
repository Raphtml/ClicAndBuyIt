# Test technique Clic and Fit

## Environnement de développement

###Pré-requis

* PHP 7.4
* Composer
* Symfony CLI
* Docker
* Docker-compose

###Lancer l'environnement de développement

````bash
docker compose up -d
symfony serve -d
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
````