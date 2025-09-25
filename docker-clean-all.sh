#!/bin/bash
# ============================================================
# 🧹 Docker Cleaner - Hapus container mati, volume, network, image tak terpakai
# ============================================================

echo "🛑 Menghapus container yang mati..."
docker ps -a --filter "status=exited" --filter "status=created" --filter "status=dead" -q | xargs -r docker rm -f

echo "📦 Menghapus volume yang tidak terpakai..."
docker volume prune -f

echo "🌐 Menghapus network yang tidak terpakai..."
docker network prune -f

echo "🖼️  Menghapus image yang tidak terpakai..."
docker image prune -af

echo "✅ Pembersihan selesai!"
docker system df
