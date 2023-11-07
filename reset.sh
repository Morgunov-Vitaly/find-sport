#!/bin/sh

# Удаление миграций
docker exec -w /var/www/ fs_app_php php commands/migrations-reset.php

# Остановка docker-контейнеров
docker-compose down --remove-orphans
