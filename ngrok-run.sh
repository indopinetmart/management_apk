#!/bin/bash

LARAVEL_PORT=8000
NGROK_BIN="ngrok"
ENV_FILE=".env"

# Cek PHP dan ngrok
if ! command -v php &>/dev/null; then
  echo "❌ PHP tidak ditemukan, install dulu ya."
  exit 1
fi

if ! command -v $NGROK_BIN &>/dev/null; then
  echo "❌ ngrok tidak ditemukan, install dulu ya."
  exit 1
fi

# Buat env file jika belum ada
if [ ! -f "$ENV_FILE" ]; then
  cp .env $ENV_FILE
fi

# Jalankan ngrok dulu di background
echo "🌐 Menjalankan ngrok..."
$NGROK_BIN http $LARAVEL_PORT > /dev/null &
NGROK_PID=$!

# Tunggu ngrok dapat URL
echo "⏳ Menunggu ngrok mendapatkan URL..."
NGROK_URL=""
for i in {1..15}; do
  NGROK_URL=$(curl -s http://127.0.0.1:4040/api/tunnels \
      | grep -o "https://[a-zA-Z0-9.-]*\.ngrok-free\.app" | head -n 1)
  if [ ! -z "$NGROK_URL" ]; then
      break
  fi
  sleep 1
done

if [ -z "$NGROK_URL" ]; then
  echo "❌ Gagal mendapatkan URL ngrok setelah menunggu."
  kill $NGROK_PID
  exit 1
fi

# Update APP_URL di .env.ngrok
echo "🔄 Update $ENV_FILE dengan URL ngrok: $NGROK_URL"
sed -i "s|APP_URL=.*|APP_URL=$NGROK_URL|g" $ENV_FILE

# Jalankan Laravel dengan env yang sudah diupdate
echo "🚀 Menjalankan Laravel di background..."
php artisan serve --host=0.0.0.0 --port=$LARAVEL_PORT --env=$ENV_FILE &
LARAVEL_PID=$!

trap "echo '🛑 Menghentikan proses...'; kill $LARAVEL_PID $NGROK_PID; exit 0" SIGINT SIGTERM

echo "✅ Laravel jalan di: $NGROK_URL"
echo "Tekan CTRL+C untuk menghentikan"

wait
