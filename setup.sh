#!/bin/bash
cp .env.example .env
docker compose up --build -d
docker compose exec app composer install
docker compose exec app php artisan migrate
