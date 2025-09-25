#!/bin/bash
# ============================================================
# 🚀 Laravel Management + Cloudflare Quick Tunnel Runner
# ============================================================

ROOT_DIR=$(pwd)
TUNNEL_LOG="$ROOT_DIR/tunnel.log"
TUNNEL_URL_FILE="$ROOT_DIR/tunnel-url.txt"
CLOUDFLARED_BIN="cloudflared"

# ------------------------------
# Container PHP Laravel
# ------------------------------
PHP_CONTAINER="management-app"

# ------------------------------
# Nginx Gateway (publish 8080)
# ------------------------------
NGINX_HOST="localhost"
NGINX_PORT=8080

# ------------------------------
# Warna log
# ------------------------------
YELLOW='\033[1;33m'
NC='\033[0m'

log() { echo -e "$1"; }

# ------------------------------
# Cleanup saat CTRL+C
# ------------------------------
cleanup() {
  log "\n🛑 Menghentikan Cloudflare Tunnel..."
  pkill -f "cloudflared tunnel" >/dev/null 2>&1 || true
  exit 0
}
trap cleanup SIGINT SIGTERM

# ------------------------------
# Bersihkan cache Laravel
# ------------------------------
clear_laravel_cache() {
  log "⚡ Membersihkan cache Laravel..."
  docker exec -i $PHP_CONTAINER php artisan optimize:clear || true
  log "✅ Cache Laravel dibersihkan"
}

# ------------------------------
# Hentikan tunnel lama
# ------------------------------
stop_old_tunnel() {
  log "🛑 Menghentikan tunnel lama..."
  pkill -f "cloudflared tunnel" >/dev/null 2>&1 || true
}

# ------------------------------
# Jalankan tunnel baru
# ------------------------------
start_tunnel() {
  log "🌐 Menjalankan Cloudflare Quick Tunnel ke $NGINX_HOST:$NGINX_PORT..."
  : > "$TUNNEL_LOG"
  nohup $CLOUDFLARED_BIN tunnel --url "http://$NGINX_HOST:$NGINX_PORT" --no-autoupdate > "$TUNNEL_LOG" 2>&1 &

  log "⏳ Menunggu tunnel aktif..."
  TUNNEL_URL=""
  TIMEOUT=60
  SECONDS=0
  while [ -z "$TUNNEL_URL" ] && [ $SECONDS -lt $TIMEOUT ]; do
    sleep 1
    TUNNEL_URL=$(grep -o 'https://[a-z0-9.-]*\.trycloudflare.com' "$TUNNEL_LOG" | tail -n 1)
  done

  if [ -z "$TUNNEL_URL" ]; then
    log "❌ Gagal mendapatkan URL tunnel"
    cleanup
  fi

  log "🔗 Tunnel aktif: $TUNNEL_URL"
}

# ------------------------------
# Update APP_URL di .env
# ------------------------------
update_app_url() {
  ENV_FILE="$ROOT_DIR/.env"
  if [ -f "$ENV_FILE" ]; then
    if grep -q "^APP_URL=" "$ENV_FILE"; then
      sed -i "s|^APP_URL=.*|APP_URL=$TUNNEL_URL|" "$ENV_FILE"
    else
      echo "APP_URL=$TUNNEL_URL" >> "$ENV_FILE"
    fi
    log "✅ APP_URL diperbarui → $TUNNEL_URL ($ENV_FILE)"
  else
    log "⚠️ File .env tidak ditemukan"
  fi
}

# ------------------------------
# Simpan URL tunnel
# ------------------------------
save_tunnel_url() {
  echo "$TUNNEL_URL" > "$TUNNEL_URL_FILE"
  log "📄 Tunnel URL disimpan → $TUNNEL_URL_FILE"
}

# ------------------------------
# Ambil credential DB dari .env
# ------------------------------
load_db_credentials() {
  ENV_FILE="$ROOT_DIR/.env"
  if [ ! -f "$ENV_FILE" ]; then
    log "❌ File .env tidak ditemukan, tidak bisa cek DB"
    exit 1
  fi

  DB_HOST=$(grep -E "^DB_HOST=" "$ENV_FILE" | cut -d '=' -f2-)
  DB_PORT=$(grep -E "^DB_PORT=" "$ENV_FILE" | cut -d '=' -f2-)
  DB_NAME=$(grep -E "^DB_DATABASE=" "$ENV_FILE" | cut -d '=' -f2-)
  DB_USER=$(grep -E "^DB_USERNAME=" "$ENV_FILE" | cut -d '=' -f2-)
  DB_PASS=$(grep -E "^DB_PASSWORD=" "$ENV_FILE" | cut -d '=' -f2-)
}

# ------------------------------
# Cek koneksi Laravel ↔ Database
# ------------------------------
check_connection() {
  log "${YELLOW}==== PING Laravel ↔ Database ====${NC}"
  docker exec $PHP_CONTAINER php -r "
    \$conn = @mysqli_connect('$DB_HOST','$DB_USER','$DB_PASS','$DB_NAME');
    echo \$conn ? '✅ Koneksi DB berhasil' : '❌ Gagal koneksi DB';
  "
  echo ""
}

# ------------------------------
# Tampilkan info akhir
# ------------------------------
show_info() {
  log "==============================================="
  log "🌍 URL Development (Cloudflare Quick Tunnel):"
  log "  - Management Panel → $TUNNEL_URL"
  log "==============================================="
  log "✅ Tunnel siap! Tekan CTRL+C untuk hentikan."
}

# ------------------------------
# Main Execution Flow
# ------------------------------
clear_laravel_cache
stop_old_tunnel
start_tunnel
update_app_url
save_tunnel_url
load_db_credentials
check_connection
show_info
