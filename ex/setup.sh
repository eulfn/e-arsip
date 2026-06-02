#!/bin/bash
set -e

echo "=== Step 1: Symlink project to Apache web root ==="
sudo ln -sf /home/eulfen/Downloads/ci3 /srv/http/ci3
echo "Done: /srv/http/ci3 -> /home/eulfen/Downloads/ci3"

echo ""
echo "=== Step 2: Set writable permissions for CI3 ==="
chmod -R 777 /home/eulfen/Downloads/ci3/application/cache
chmod -R 777 /home/eulfen/Downloads/ci3/application/logs
chmod -R 777 /home/eulfen/Downloads/ci3/application/sessions
chmod -R 777 /home/eulfen/Downloads/ci3/uploads
echo "Done: permissions set"

echo ""
echo "=== Step 3: Make home dir traversable for Apache (http user) ==="
sudo chmod o+x /home/eulfen
sudo chmod o+x /home/eulfen/Downloads
echo "Done: Apache can traverse to project"

echo ""
echo "=== Step 4: Initialize MariaDB if needed ==="
if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "Initializing MariaDB data directory..."
    sudo mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
else
    echo "MariaDB already initialized, skipping."
fi

echo ""
echo "=== Step 5: Start services (manual, no boot) ==="
sudo systemctl start mariadb
sudo systemctl start httpd
echo "Done: httpd + mariadb started"

echo ""
echo "=== Step 6: Create database and import schema ==="
sudo mariadb -u root -e "CREATE DATABASE IF NOT EXISTS e_archive CHARACTER SET utf8 COLLATE utf8_general_ci;"
sudo mariadb -u root e_archive < /home/eulfen/Downloads/ci3/e_archive.sql
echo "Done: e_archive database imported"

echo ""
echo "=== Step 7: Verify ==="
echo "Services:"
systemctl is-active httpd
systemctl is-active mariadb
echo ""
echo "Boot status (should be disabled):"
systemctl is-enabled httpd 2>&1 || true
systemctl is-enabled mariadb 2>&1 || true
echo ""
echo "Database tables:"
sudo mariadb -u root -e "USE e_archive; SHOW TABLES;"
echo ""
echo "=== ALL DONE ==="
echo "Open: http://localhost/ci3/"
echo "Admin login: admin / password"
