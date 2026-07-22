#!/bin/bash
set -e

# Railway assigns a dynamic $PORT; make Apache listen on it instead of 80.
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-enabled/000-default.conf

# The only two directories the app writes to at runtime are database/ (the
# SQLite file) and uploads/ (admin-uploaded media). Everything else (code,
# new_images/, images/) is static and baked into the image. Symlink those two
# into a single mounted volume so their contents survive redeploys/restarts.
PERSIST_DIR=/var/www/html/persist
mkdir -p "$PERSIST_DIR/database" "$PERSIST_DIR/uploads"

# Both directories ship a security .htaccess (deny-all on database/, block
# script execution on uploads/) baked into the image. Seed the persistent
# volume with them before swapping in the symlink, so a fresh volume doesn't
# leave the SQLite file directly downloadable or uploads/ executable.
[ -f "$PERSIST_DIR/database/.htaccess" ] || cp /var/www/html/database/.htaccess "$PERSIST_DIR/database/.htaccess"
[ -f "$PERSIST_DIR/uploads/.htaccess" ]  || cp /var/www/html/uploads/.htaccess  "$PERSIST_DIR/uploads/.htaccess"

for d in database uploads; do
  if [ ! -L "/var/www/html/$d" ]; then
    rm -rf "/var/www/html/$d"
    ln -s "$PERSIST_DIR/$d" "/var/www/html/$d"
  fi
done

chown -R www-data:www-data "$PERSIST_DIR"

exec "$@"
