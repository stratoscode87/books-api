#!/bin/bash
docker compose up --build -d
docker compose exec app composer install
docker compose exec app composer
docker compose exec app php artisan migrate
docker compose exec app php artisan install:api
