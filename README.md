# Test technique Clic and Fit

## Environnement de développement

###Pré-requis

* <a href="https://www.php.net/manual/fr/mysql-xdevapi.installation.php" target="_blank">PHP ^7.2</a>
* <a href="https://getcomposer.org/download/" target="_blank">Composer</a>
* <a href="https://symfony.com/download" target="_blank">Symfony CLI</a>
* <a href="https://docs.docker.com/get-docker/" target="_blank">Docker</a>
* <a href="https://docs.docker.com/compose/install/" target="_blank">Docker-compose</a>
* <a href="https://classic.yarnpkg.com/en/docs/install" target="_blank">Yarn</a>

###Lancer l'environnement de développement

Lancer les commandes suivantes dans cet ordre :

````bash
composer install
yarn install
yarn encore dev
docker compose up -d
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load --no-interaction
symfony serve -d
````

Un user est déjà créé avec les fixtures <br>
email : postmaster@clicandbuyit.fr <br>
mot de passe: password