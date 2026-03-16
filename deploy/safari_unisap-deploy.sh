#!/usr/bin/env bash
set -euo pipefail

APP_NAME="safari-unisap"
REPO_URL="https://github.com/kodingsil-lab/safari-unisap.git"
BRANCH="${1:-main}"

BASE_DIR="$HOME/apps/$APP_NAME"
RELEASES_DIR="$BASE_DIR/releases"
SHARED_DIR="$BASE_DIR/shared"
CURRENT_LINK="$BASE_DIR/current"
PUBLIC_HTML="/home/u541589701/domains/safari.unisap.ac.id/public_html"

TIMESTAMP="$(date +%Y%m%d%H%M%S)"
RELEASE_DIR="$RELEASES_DIR/$TIMESTAMP"

say() {
    printf '\n[%s] %s\n' "$APP_NAME" "$1"
}

ensure_dir() {
    mkdir -p "$1"
}

link_shared() {
    local target="$1"
    local source="$2"

    rm -rf "$target"
    ln -sfn "$source" "$target"
}

say "Menyiapkan folder deploy"
ensure_dir "$RELEASES_DIR"
ensure_dir "$SHARED_DIR"
ensure_dir "$SHARED_DIR/writable/cache"
ensure_dir "$SHARED_DIR/writable/logs"
ensure_dir "$SHARED_DIR/writable/session"
ensure_dir "$SHARED_DIR/writable/uploads"
ensure_dir "$SHARED_DIR/writable/debugbar"
ensure_dir "$SHARED_DIR/public/uploads"

if [[ ! -f "$SHARED_DIR/.env" ]]; then
    say "Membuat template .env awal di shared"
    cat > "$SHARED_DIR/.env" <<'ENVEOF'
CI_ENVIRONMENT = production

app.baseURL = 'https://safari.unisap.ac.id/'
app.indexPage = ''
app.forceGlobalSecureRequests = true

database.default.hostname =
database.default.database =
database.default.username =
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_unicode_ci

app.appTimezone = Asia/Makassar

logger.threshold = 4

email.fromEmail = ''
email.fromName = 'SAFARI UNISAP'
email.protocol = 'smtp'
email.SMTPHost = 'smtp.gmail.com'
email.SMTPUser = ''
email.SMTPPass = ''
email.SMTPPort = 587
email.SMTPCrypto = 'tls'
email.mailType = 'html'
ENVEOF
fi

say "Mengambil kode dari branch $BRANCH"
git clone --depth 1 --branch "$BRANCH" "$REPO_URL" "$RELEASE_DIR"

say "Menghubungkan shared storage"
link_shared "$RELEASE_DIR/.env" "$SHARED_DIR/.env"
link_shared "$RELEASE_DIR/writable" "$SHARED_DIR/writable"
link_shared "$RELEASE_DIR/public/uploads" "$SHARED_DIR/public/uploads"

say "Install dependency Composer"
cd "$RELEASE_DIR"
composer install --no-dev --prefer-dist --optimize-autoloader

say "Menjalankan migration"
php spark migrate --all --no-header

say "Mengaktifkan release baru"
ln -sfn "$RELEASE_DIR" "$CURRENT_LINK"
ln -sfn "$CURRENT_LINK/public" "$PUBLIC_HTML"

say "Deploy selesai"
say "Release aktif: $RELEASE_DIR"
say "Public path: $PUBLIC_HTML -> $CURRENT_LINK/public"
