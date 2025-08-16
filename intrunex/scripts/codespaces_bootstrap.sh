#!/usr/bin/env bash
set -euo pipefail

# --- Config ---
PORT="${PORT:-8000}"
APP_ENV_FILE="${APP_ENV_FILE:-.env}"
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

cd "$PROJECT_ROOT"

echo "→ Working dir: $PROJECT_ROOT"

# 1) Ensure .env exists
if [ ! -f "$APP_ENV_FILE" ]; then
  echo "→ Creating $APP_ENV_FILE from .env.example"
  cp .env.example "$APP_ENV_FILE"
fi

# 2) Compute Codespaces URL and write APP_URL
APP_URL_FROM_ENV="$(grep -E '^APP_URL=' "$APP_ENV_FILE" | cut -d= -f2- || true)"
if [ -n "${CODESPACE_NAME:-}" ] && [ -n "${GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN:-}" ]; then
  CODESPACES_URL="https://${CODESPACE_NAME}-${PORT}.${GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN}"
else
  # Fallback (older domains); you can hardcode if needed
  CODESPACES_URL="${APP_URL_FROM_ENV:-http://localhost:$PORT}"
fi

if grep -q '^APP_URL=' "$APP_ENV_FILE"; then
  sed -i "s#^APP_URL=.*#APP_URL=${CODESPACES_URL}#g" "$APP_ENV_FILE"
else
  echo "APP_URL=${CODESPACES_URL}" >> "$APP_ENV_FILE"
fi
echo "→ APP_URL set to: $CODESPACES_URL"

# 3) Ensure SQLite path and file exist if using sqlite
DB_CONN="$(grep -E '^DB_CONNECTION=' "$APP_ENV_FILE" | cut -d= -f2- || echo '')"
if [ "$DB_CONN" = "sqlite" ]; then
  DB_PATH="$(grep -E '^DB_DATABASE=' "$APP_ENV_FILE" | cut -d= -f2- || echo '')"
  if [ -z "$DB_PATH" ]; then
    DB_PATH="$PROJECT_ROOT/database/database.sqlite"
    if grep -q '^DB_DATABASE=' "$APP_ENV_FILE"; then
      sed -i "s#^DB_DATABASE=.*#DB_DATABASE=${DB_PATH}#g" "$APP_ENV_FILE"
    else
      echo "DB_DATABASE=${DB_PATH}" >> "$APP_ENV_FILE"
    fi
  fi
  mkdir -p "$(dirname "$DB_PATH")"
  [ -f "$DB_PATH" ] || touch "$DB_PATH"
  echo "→ SQLite ready at: $DB_PATH"
fi

# 4) Dependencies and key
if [ ! -d vendor ]; then
  echo "→ Installing Composer deps"
  composer install --no-interaction --prefer-dist
fi

if ! grep -qE '^APP_KEY=base64:.+' "$APP_ENV_FILE"; then
  echo "→ Generating APP_KEY"
  php artisan key:generate --no-interaction
fi

# 5) Cache clear and migrate
php artisan optimize:clear
php artisan migrate --force || true

# 6) Build Vite assets (optional but prevents 404 on manifest)
if [ -f package.json ]; then
  if [ ! -d node_modules ]; then
    echo "→ Installing Node deps"
    npm ci || npm install
  fi
  echo "→ Building assets"
  npm run build || true
fi

# 7) Start server bound to 0.0.0.0 (avoids localhost redirects)
if [ "${1:-}" = "start" ]; then
  echo "→ Starting PHP server on 0.0.0.0:${PORT}"
  exec php -S 0.0.0.0:"$PORT" -t public
else
  echo "✓ Prep complete. Start the server with:"
  echo "   php -S 0.0.0.0:${PORT} -t public"
fi
