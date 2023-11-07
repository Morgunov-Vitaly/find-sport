#!/bin/sh

# Запуск приложения в докере
docker-compose up -d

sleep 5

# Запуск миграций
docker exec -w /var/www fs_app_php php commands/migrations.php

# Запуск сидов
docker exec -w /var/www fs_app_php php commands/seeds.php

# Переход в контейнер php
docker exec -it fs_app_php bash