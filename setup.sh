#!/bin/bash
docker compose up --build -d
docker compose exec app composer install
docker compose exec app php artisan migrate
