#!/bin/bash
# setup-performance-vps.sh — aplica todas as otimizações de performance na VPS
# Uso: sudo bash setup-performance-vps.sh
set -euo pipefail

# ── helpers ───────────────────────────────────────────────────────────────
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'
BLUE='\033[0;34m'; CYAN='\033[0;36m'; BOLD='\033[1m'; RESET='\033[0m'

info()    { echo -e "${CYAN}[INFO]${RESET}  $*"; }
ok()      { echo -e "${GREEN}[ OK ]${RESET}  $*"; }
warn()    { echo -e "${YELLOW}[WARN]${RESET}  $*"; }
fail()    { echo -e "${RED}[ERRO]${RESET}  $*"; exit 1; }
section() { echo -e "\n${BOLD}${BLUE}━━━ $* ━━━${RESET}"; }

APP_DIR="/var/www/abraao-calcados"
NGINX_CONF="/etc/nginx/sites-available/abraao-calcados"
PHP_FPM_POOL="/etc/php/8.3/fpm/pool.d/www.conf"
PHP_OPCACHE_CONF="/etc/php/8.3/fpm/conf.d/10-opcache.ini"
MAINT_SECRET="abraao-$(openssl rand -hex 6)"

# ── pré-checks ────────────────────────────────────────────────────────────
echo -e "\n${BOLD}${GREEN}"
echo "  ╔════════════════════════════════════════════╗"
echo "  ║  Setup de Performance — Abraão Calçados   ║"
echo "  ╚════════════════════════════════════════════╝"
echo -e "${RESET}"

[[ $EUID -ne 0 ]] && fail "Execute como root: sudo bash setup-performance-vps.sh"
[[ ! -d "$APP_DIR" ]]    && fail "Diretório não encontrado: $APP_DIR"
[[ ! -f "$NGINX_CONF" ]] && fail "Config Nginx não encontrada: $NGINX_CONF"

info "Secret de manutenção: ${BOLD}${MAINT_SECRET}${RESET}"
info "Durante o deploy acesse o site com:"
info "  https://abraaoshoes.tech/${MAINT_SECRET}\n"

# ─────────────────────────────────────────────────────────────────────────
section "1/7  Swap (evita OOM durante npm)"
# ─────────────────────────────────────────────────────────────────────────

if swapon --show | grep -q '/swapfile'; then
    ok "Swap já existe, pulando."
else
    info "Criando swap de 1 GB..."
    fallocate -l 1G /swapfile
    chmod 600 /swapfile
    mkswap  /swapfile
    swapon  /swapfile
    grep -q '/swapfile' /etc/fstab \
        || echo '/swapfile none swap sw 0 0' >> /etc/fstab
    grep -q 'vm.swappiness' /etc/sysctl.conf \
        || echo 'vm.swappiness=10' >> /etc/sysctl.conf
    sysctl -p -q
    ok "Swap de 1 GB criado e persistido."
fi

# ─────────────────────────────────────────────────────────────────────────
section "2/7  Redis"
# ─────────────────────────────────────────────────────────────────────────

if systemctl is-active --quiet redis-server 2>/dev/null; then
    ok "Redis já está rodando."
else
    info "Instalando Redis..."
    apt-get install -y -q redis-server
    systemctl enable --now redis-server
    ok "Redis instalado e iniciado."
fi

# ─────────────────────────────────────────────────────────────────────────
section "3/7  OPcache"
# ─────────────────────────────────────────────────────────────────────────

cat > "$PHP_OPCACHE_CONF" << 'EOF'
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=0
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.fast_shutdown=1
EOF
ok "OPcache configurado em $PHP_OPCACHE_CONF"

# ─────────────────────────────────────────────────────────────────────────
section "4/7  PHP-FPM Pool"
# ─────────────────────────────────────────────────────────────────────────

cp "$PHP_FPM_POOL" "${PHP_FPM_POOL}.bak.$(date +%Y%m%d%H%M%S)"

# Atualiza ou descomenta cada diretiva (padrão do www.conf tem linhas comentadas)
_fpm_set() {
    local key="$1" val="$2"
    if grep -qE "^;?\s*${key}\s*=" "$PHP_FPM_POOL"; then
        sed -i "s|^;*\s*${key}\s*=.*|${key} = ${val}|" "$PHP_FPM_POOL"
    else
        echo "${key} = ${val}" >> "$PHP_FPM_POOL"
    fi
}

_fpm_set "pm"                   "dynamic"
_fpm_set "pm.max_children"      "10"
_fpm_set "pm.start_servers"     "3"
_fpm_set "pm.min_spare_servers" "2"
_fpm_set "pm.max_spare_servers" "5"
_fpm_set "pm.max_requests"      "500"

# reload gracioso — workers em andamento terminam normalmente
systemctl reload php8.3-fpm
ok "PHP-FPM pool recarregado (graceful)."

# ─────────────────────────────────────────────────────────────────────────
section "5/7  Nginx (gzip + cache de assets)"
# ─────────────────────────────────────────────────────────────────────────

cp "$NGINX_CONF" "${NGINX_CONF}.bak.$(date +%Y%m%d%H%M%S)"

# Criar snippet de performance separado para não tocar no bloco SSL do certbot
mkdir -p /etc/nginx/snippets
cat > /etc/nginx/snippets/abraao-performance.conf << 'EOF'
# Gzip melhorado
gzip on;
gzip_comp_level 5;
gzip_min_length 256;
gzip_proxied any;
gzip_vary on;
gzip_types text/plain text/css application/json application/javascript
           text/xml application/xml image/svg+xml font/woff2;

# Cache longo para assets do Vite (nome com hash — nunca mudam)
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

# FastCGI buffers
fastcgi_buffers 16 16k;
fastcgi_buffer_size 32k;
fastcgi_read_timeout 60;
EOF

# Injetar include no config principal (antes do último }) se ainda não estiver lá
if ! grep -q 'abraao-performance' "$NGINX_CONF"; then
    python3 - "$NGINX_CONF" << 'PYEOF'
import sys, re
path = sys.argv[1]
content = open(path).read()
# Remove gzip simples anterior para evitar conflito com o snippet
content = re.sub(r'\n\s*gzip\s+on;\n', '\n', content)
content = re.sub(r'\n\s*gzip_types[^\n]+;\n', '\n', content)
# Injeta include antes do último } do arquivo
last_brace = content.rfind('}')
if last_brace != -1:
    content = (content[:last_brace]
               + '    include snippets/abraao-performance.conf;\n'
               + content[last_brace:])
open(path, 'w').write(content)
print("Snippet injetado.")
PYEOF
else
    ok "Snippet já estava incluído."
fi

nginx -t && systemctl reload nginx
ok "Nginx recarregado (graceful)."

# ─────────────────────────────────────────────────────────────────────────
section "6/7  Deploy da Aplicação"
# ─────────────────────────────────────────────────────────────────────────

cd "$APP_DIR"

info "Ativando modo manutenção..."
php artisan down --secret="$MAINT_SECRET" 2>/dev/null || true

info "Atualizando código (git pull)..."
git pull origin main

info "Instalando dependências PHP..."
composer install --no-dev --optimize-autoloader -q

info "Compilando assets frontend (npm ci + vite build)..."
npm install --silent
npm run build

info "Rodando migrations (índices de performance)..."
php artisan migrate --force

info "Atualizando .env — trocando drivers para Redis..."
_env_set() {
    local key="$1" val="$2"
    if grep -q "^${key}=" .env; then
        sed -i "s|^${key}=.*|${key}=${val}|" .env
    else
        echo "${key}=${val}" >> .env
    fi
}
_env_set "CACHE_STORE"      "redis"
_env_set "SESSION_DRIVER"   "redis"
_env_set "QUEUE_CONNECTION" "redis"

info "Reconstruindo cache Laravel..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache

info "Voltando ao ar..."
php artisan up

ok "Aplicação online."

# ─────────────────────────────────────────────────────────────────────────
section "7/7  Verificação Final"
# ─────────────────────────────────────────────────────────────────────────

FAIL=0
for svc in nginx php8.3-fpm mysql redis-server; do
    if systemctl is-active --quiet "$svc" 2>/dev/null; then
        ok "$svc está rodando"
    else
        warn "$svc NÃO está rodando — verifique com: systemctl status $svc"
        FAIL=1
    fi
done

# Queue worker (não crítico para o site, mas verifica)
if systemctl is-active --quiet abraao-queue 2>/dev/null; then
    ok "abraao-queue está rodando"
else
    warn "abraao-queue não está ativo (rode: systemctl start abraao-queue)"
fi

echo
if [[ $FAIL -eq 0 ]]; then
    echo -e "${BOLD}${GREEN}"
    echo "  ╔════════════════════════════════════════════╗"
    echo "  ║        Tudo pronto! Site no ar.           ║"
    echo "  ╚════════════════════════════════════════════╝"
    echo -e "${RESET}"
else
    echo -e "${YELLOW}Setup concluído com avisos. Verifique os serviços acima.${RESET}"
fi

echo -e "  ${BOLD}Catálogo:${RESET} https://abraaoshoes.tech"
echo -e "  ${BOLD}Admin:${RESET}    https://abraaoshoes.tech/admin"
echo
echo -e "  ${YELLOW}Sessões de admin foram invalidadas — faça login novamente.${RESET}"
echo
