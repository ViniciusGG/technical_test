
# Technical Test Back End

## API Documentation

### Requirments
-   php 8.2 on your machine
-   Composer

### PHP Extensions
-   Before proceeding with the installation, please ensure that the following PHP extensions are enabled on your machine:


### Starting in this project

1. Clone this project
2. Run the `composer install` command
3. Run the `cp .env.example .env` command
4. Run the `docker compose up` command
5. Run the `docker exec -it technical_api php artisan key:generate` command
6. Run the `docker exec -it technical_api php artisan migrate:fresh --seed` command

### Create a new user CLI
1. Run the `docker exec -it technical_api php artisan bank-account:create` command

### Run schedule transactions CLI
1. Run the `docker exec -it technical_api php artisan transaction:process` command

### Run the tests
1. Run the `docker exec -it technical_api php artisan test` command

