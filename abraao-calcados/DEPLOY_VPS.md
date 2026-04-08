# 🚀 Deploy — Abraão Calçados na VPS Ubuntu

**Stack:** Laravel 13 · PHP 8.3 · MySQL 8 · Nginx · Filament · Livewire  
**Domínio:** abraaoshoes.tech

---

## Pré-requisitos

- VPS com Ubuntu 22.04 ou 24.04 (mínimo 1 GB RAM)
- Acesso root ou usuário com `sudo`
- Domínio `abraaoshoes.tech` com DNS apontando para o IP da VPS (registro tipo A)

---

## Etapa 1 — Conectar na VPS

```bash
ssh root@IP_DA_VPS
```

---

## Etapa 2 — Atualizar o sistema e criar Swap

```bash
apt update && apt upgrade -y
```

### Criar Swap (essencial para VPS com 1 GB RAM)

O `npm install` durante o deploy consome ~500 MB de RAM. Sem swap, o processo pode ser morto pelo OOM killer.

```bash
fallocate -l 1G /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile

# Tornar permanente
echo '/swapfile none swap sw 0 0' >> /etc/fstab

# Reduzir uso de swap (usar memória RAM primeiro)
echo 'vm.swappiness=10' >> /etc/sysctl.conf
sysctl -p
```

---

## Etapa 3 — Instalar PHP 8.3 e extensões

```bash
apt install -y software-properties-common curl
add-apt-repository ppa:ondrej/php -y
apt update

apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-mysql \
  php8.3-xml php8.3-curl php8.3-mbstring php8.3-zip \
  php8.3-bcmath php8.3-intl php8.3-gd php8.3-redis \
  php8.3-tokenizer php8.3-fileinfo unzip

# Verificar
php -v
```

### Configurar OPcache (obrigatório para produção)

Sem OPcache, o PHP recompila todos os arquivos a cada requisição.

```bash
cat > /etc/php/8.3/fpm/conf.d/10-opcache.ini << 'EOF'
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=0
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.fast_shutdown=1
EOF

systemctl restart php8.3-fpm
```

### Configurar PHP-FPM (pool de workers)

```bash
sed -i 's/^pm = .*/pm = dynamic/' /etc/php/8.3/fpm/pool.d/www.conf
sed -i 's/^pm.max_children = .*/pm.max_children = 10/' /etc/php/8.3/fpm/pool.d/www.conf
sed -i 's/^pm.start_servers = .*/pm.start_servers = 3/' /etc/php/8.3/fpm/pool.d/www.conf
sed -i 's/^pm.min_spare_servers = .*/pm.min_spare_servers = 2/' /etc/php/8.3/fpm/pool.d/www.conf
sed -i 's/^pm.max_spare_servers = .*/pm.max_spare_servers = 5/' /etc/php/8.3/fpm/pool.d/www.conf

# Reiniciar worker a cada 500 requisições para evitar memory leak
echo "pm.max_requests = 500" >> /etc/php/8.3/fpm/pool.d/www.conf

systemctl restart php8.3-fpm
```

---

## Etapa 4 — Instalar Nginx e Redis

```bash
apt install -y nginx redis-server
systemctl enable nginx redis-server
systemctl start nginx redis-server
```

---

## Etapa 5 — Instalar MySQL 8

```bash
apt install -y mysql-server
systemctl enable mysql
systemctl start mysql

# Configuração segura
mysql_secure_installation
```

**Criar banco de dados e usuário:**

```bash
mysql -u root -p
```

```sql
CREATE DATABASE abraao_calcados CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'abraao'@'localhost' IDENTIFIED BY 'SUA_SENHA_FORTE_AQUI';
GRANT ALL PRIVILEGES ON abraao_calcados.* TO 'abraao'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

> **⚠️ Atenção:** Anote a senha que você definiu. Você vai precisar dela na Etapa 9.

---

## Etapa 6 — Instalar Composer

```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer --version
```

---

## Etapa 7 — Instalar Node.js 20

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
node -v && npm -v
```

---

## Etapa 8 — Clonar o projeto

O repositório no GitHub tem a estrutura `abra-o-cal-ados/abraao-calcados/`, então precisamos clonar e mover a pasta interna:

```bash
cd /var/www

# Clonar o repositório
git clone https://github.com/luqalefe/abra-o-cal-ados.git temp-clone

# Mover a pasta do projeto para o local correto
mv temp-clone/abraao-calcados /var/www/abraao-calcados

# Limpar o clone temporário
rm -rf temp-clone

cd /var/www/abraao-calcados
```

---

## Etapa 9 — Configurar o `.env` de produção

```bash
cd /var/www/abraao-calcados
cp .env.example .env
nano .env
```

**Substitua o conteúdo inteiro por:**

```env
APP_NAME="Abraão Calçados"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://abraaoshoes.tech

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=abraao_calcados
DB_USERNAME=abraao
DB_PASSWORD=SUA_SENHA_FORTE_AQUI

SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis

CACHE_STORE=redis

MAIL_MAILER=log

VITE_APP_NAME="${APP_NAME}"

STORE_NAME="Abraão Calçados"
STORE_ADDRESS="Estr. Juarez Távora, 206 - Alto Alegre"
STORE_DESCRIPTION="As melhores promoções da semana em calçados!"
STORE_PHONE="(68)99260-7794"
WHATSAPP_NUMBER=5568992607794
```

> **🚨 Cuidado:**
> - Troque `SUA_SENHA_FORTE_AQUI` pela senha que você criou na Etapa 5
> - Nunca deixe `APP_DEBUG=true` em produção

---

## Etapa 10 — Instalar dependências e fazer build

```bash
cd /var/www/abraao-calcados

# Instalar dependências PHP (sem dev)
composer install --no-dev --optimize-autoloader

# Gerar APP_KEY
php artisan key:generate

# Instalar dependências Node e buildar assets
npm install
npm run build
```

---

## Etapa 11 — Rodar migrations e seeders

```bash
cd /var/www/abraao-calcados

# Rodar migrations
php artisan migrate --force

# Criar link do storage (para imagens de produtos)
php artisan storage:link

# Criar diretórios de upload necessários
mkdir -p storage/app/estoque-imports
mkdir -p storage/app/livewire-tmp

# Popular categorias iniciais
php artisan db:seed --class=CategorySeeder
```

> **📦 Importar produtos do ERP:**  
> Não rode o `EstoqueSeeder` manualmente na VPS.  
> Acesse o painel em `/admin/importar-estoque` e faça o upload do CSV do ERP.  
> Os produtos serão importados sem categoria — o admin deve editar cada um para adicionar foto e categoria antes de marcar como promovido.

---

## Etapa 12 — Configurar permissões

```bash
chown -R www-data:www-data /var/www/abraao-calcados
chmod -R 755 /var/www/abraao-calcados
chmod -R 775 /var/www/abraao-calcados/storage
chmod -R 775 /var/www/abraao-calcados/bootstrap/cache
```

---

## Etapa 13 — Otimizar para produção

```bash
cd /var/www/abraao-calcados
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache
php artisan optimize
```

---

## Etapa 14 — Configurar Nginx

```bash
nano /etc/nginx/sites-available/abraao-calcados
```

**Cole exatamente isto:**

```nginx
server {
    listen 80;
    server_name abraaoshoes.tech www.abraaoshoes.tech;

    root /var/www/abraao-calcados/public;
    index index.php index.html;

    charset utf-8;
    client_max_body_size 20M;

    # Gzip melhorado
    gzip on;
    gzip_comp_level 5;
    gzip_min_length 256;
    gzip_proxied any;
    gzip_vary on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml image/svg+xml font/woff2;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # Cache longo para assets do Vite (nomes com hash)
    location ~* \.(css|js|woff2?|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Cache para imagens
    location ~* \.(jpg|jpeg|png|gif|ico|webp|svg)$ {
        expires 30d;
        add_header Cache-Control "public";
        access_log off;
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 60;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Ativar o site e remover o default:**

```bash
# Remover site default do nginx (opcional)
rm -f /etc/nginx/sites-enabled/default

# Ativar o nosso site
ln -s /etc/nginx/sites-available/abraao-calcados /etc/nginx/sites-enabled/

# Testar e recarregar
nginx -t
systemctl reload nginx
```

---

## Etapa 15 — Iniciar o Queue Worker

```bash
nano /etc/systemd/system/abraao-queue.service
```

**Cole:**

```ini
[Unit]
Description=Abraão Calçados Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/abraao-calcados/artisan queue:work --sleep=3 --tries=3 --max-time=3600
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
```

```bash
systemctl daemon-reload
systemctl enable abraao-queue
systemctl start abraao-queue
systemctl status abraao-queue
```

---

## Etapa 16 — Configurar HTTPS com Certbot

**Pré-requisito:** O domínio `abraaoshoes.tech` já deve estar com DNS tipo A apontando para o IP da VPS.

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d abraaoshoes.tech -d www.abraaoshoes.tech

# Testar renovação automática
certbot renew --dry-run
```

---

## Etapa 17 — Firewall

```bash
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw enable
ufw status
```

---

## ✅ Verificação Final

Acesse no navegador:
- **Catálogo:** https://abraaoshoes.tech
- **Painel Admin:** https://abraaoshoes.tech/admin

```bash
# Checar todos os serviços
systemctl status nginx
systemctl status php8.3-fpm
systemctl status mysql
systemctl status abraao-queue

# Ver logs
tail -f /var/log/nginx/error.log
tail -f /var/www/abraao-calcados/storage/logs/laravel.log
```

---

## 🔄 Atualizações Futuras

O `deploy.sh` já está no projeto. Para atualizar:

```bash
cd /var/www/abraao-calcados && bash deploy.sh
```
