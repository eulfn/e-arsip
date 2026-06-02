#!/bin/bash
set -e

echo "=== Step 1: Remove existing symlink or folder at /srv/http/ci3 ==="
rm -rf /srv/http/ci3

echo "=== Step 2: Copy project directly to /srv/http/ci3 ==="
cp -r /home/eulfen/Downloads/ci3 /srv/http/ci3

echo "=== Step 3: Set ownership and permissions for Apache web server ==="
chown -R http:http /srv/http/ci3
chmod -R 755 /srv/http/ci3
chmod -R 777 /srv/http/ci3/application/cache
chmod -R 777 /srv/http/ci3/application/logs
chmod -R 777 /srv/http/ci3/application/sessions
chmod -R 777 /srv/http/ci3/uploads

echo "=== Step 4: Restart Apache server to apply ==="
systemctl restart httpd

echo "=== MIGRATION COMPLETE ==="
echo "The project is now located directly in the secure Apache web root at /srv/http/ci3/"
