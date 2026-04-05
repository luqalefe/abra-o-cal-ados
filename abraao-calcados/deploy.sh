#!/bin/bash
set -e

echo "🚀 Iniciando deploy..."

cd /var/www/abraao-calcados

echo "📥 Puxando código..."
git pull origin main

echo "📦 Instalando dependências PHP..."
composer install --no-dev --optimize-autoloader

echo "🎨 Compilando assets..."
npm install
npm run build

echo "🗄️ Rodando migrations..."
php artisan migrate --force

echo "🔗 Garantindo storage link..."
php artisan storage:link 2>/dev/null || true

echo "⚡ Otimizando cache..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache

echo "🔄 Reiniciando PHP-FPM..."
sudo systemctl restart php8.3-fpm

echo "✅ Deploy concluído com sucesso!"
