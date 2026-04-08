#!/bin/bash
set -e

echo "🚀 Iniciando deploy..."

cd /var/www/abraao-calcados

# Verificar se .env existe
if [ ! -f .env ]; then
    echo "❌ Arquivo .env não encontrado. Crie o .env antes de fazer deploy."
    exit 1
fi

echo "📥 Puxando código..."
git pull origin main

echo "📦 Instalando dependências PHP..."
composer install --no-dev --optimize-autoloader

echo "🎨 Compilando assets..."
npm ci
npm run build

echo "🗄️ Rodando migrations..."
php artisan migrate --force

echo "🔗 Garantindo storage link..."
php artisan storage:link 2>/dev/null || true

echo "📁 Garantindo diretórios de upload..."
mkdir -p storage/app/estoque-imports
mkdir -p storage/app/livewire-tmp
chown -R www-data:www-data storage/
chmod -R 775 storage/

echo "⚡ Limpando e recriando caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache

echo "🔄 Reiniciando PHP-FPM..."
sudo systemctl restart php8.3-fpm

echo "✅ Deploy concluído com sucesso!"
