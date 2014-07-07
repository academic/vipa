#! /bin/sh
echo "\nRunning composer install"
sudo composer install
echo "\nRunning bower install"
bower install
echo "\nDumping assets"
sudo php app/console assets:install web --symlink
sudo php app/console assetic:dump
echo "\nOjs installation"
sudo php app/console ojs:install
echo "\nNow ojs:install will add sample data"
sudo php app/console doctrine:fixtures:load --append -v
echo "\nsample author"
echo "\tusername: demo_author"
echo "\tpassword: demo"
echo "\nsample editor"
echo "\tusername: demo_editor"
echo "\tpassword: demo\n"
