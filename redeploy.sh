#!/bin/sh

php /var/www/francescroy.com/vendor/bin/drush.php un senyal_vida
php /var/www/francescroy.com/vendor/bin/drush.php en senyal_vida
php /var/www/francescroy.com/vendor/bin/drush.php cache-rebuild
