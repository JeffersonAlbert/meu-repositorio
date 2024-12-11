#!/bin/bash

git pull origin main;
if [ -z "$1" ]; then
     echo "O argumento 'nome do container' não foi passado para o script."
else
     # Lida com o caso em que o argumento não foi passado
     if command -v docker compose &>/dev/null; then 
          docker compose exec $1 php artisan route:cache
          docker compose exec $1 php artisan migrate
          docker compose exec $1 bash -c "minify public/css/sb-admin-2.css > public/css/sb-admin-2.min.css"
          docker compose exec $1 bash -c "minify resources/css/sb-admin-2.css > resources/css/sb-admin-2.min.css"
     elif command -v podman &>/dev/null; then
          podman-compose exec $1 php artisan route:cache
          podman-compose exec $1 php artisan migrate
          podman-compose exec $1 bash -c "minify public/css/sb-admin-2.css > public/css/sb-admin-2.min.css"
          podman-compose exec $1 bash -c "minify resources/css/sb-admin-2.css > resources/css/sb-admin-2.min.css"
     fi
fi

chmod 777 ./faturamento/storage/framework/sessions/
chmod 777 ./faturamento/storage/logs/laravel.log;
echo teste


