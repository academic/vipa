#! /bin/sh
echo "\nRunning composer install"
composer install
echo "\nRunning bower install"
bower install
echo "\nDumping assets"
php app/console assets:install web --symlink
php app/console assetic:dump
echo "\nOjs installation"
php app/console ojs:install
echo "\nNow ojs:install will add sample data"
php app/console doctrine:fixtures:load --append -v
php app/console doctrine:mongodb:fixtures:load --append -v
echo "\nsample author"
echo "\tusername: demo_author"
echo "\tpassword: demo"
echo "\nsample editor"
echo "\tusername: demo_editor"
echo "\tpassword: demo\n"
