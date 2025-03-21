#!/bin/bash
set -e

# Wait for the database to be ready
/usr/local/bin/waitforit.sh ${MOODLE_DB_HOST}:${MOODLE_DB_PORT} -t 60 -- echo "Database is up"

# Check if Moodle is installed
if [ ! -f /var/www/moodledata/moodledata.lock ]; then
    echo "Moodle is not installed. Running installation..."
    php /var/www/html/admin/cli/install_database.php --agree-license \
        --adminuser="${MOODLE_ADMIN_USER}" \
        --adminpass="${MOODLE_ADMIN_PASSWORD}" \
        --adminemail="${MOODLE_ADMIN_EMAIL}" \
        --fullname="${MOODLE_APP_FULLNAME}" \
        --shortname="${MOODLE_APP_SHORTNAME}" \
        --summary="${MOODLE_APP_SUMMARY}"
    touch /var/www/moodledata/moodledata.lock
    echo "Moodle installation completed."
else
    echo "Moodle is already installed."
fi

# Run database upgrades if necessary
echo "Running Moodle upgrades if necessary..."
php /var/www/html/admin/cli/upgrade.php --non-interactive

# Set up cron job
echo "Setting up Moodle cron job..."
sudo sh -c 'echo "* * * * * www-data /usr/local/bin/php /var/www/html/admin/cli/cron.php >/dev/null 2>&1" > /etc/cron.d/moodle-cron'
sudo chmod 0644 /etc/cron.d/moodle-cron

# Ensure proper permissions before starting supervisor
sudo chmod -R 777 /var/run/supervisor /var/log/supervisor

# Start supervisor as root
exec sudo /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf

# Start cron service
#sudo cron

# Start PHP-FPM
#php-fpm -D

# Start NGINX
#echo "Starting NGINX..."
#sudo nginx  -g 'daemon off;'
#sudo service start nginx;
