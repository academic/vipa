sudo composer install
bower install
sudo php app/console assets:install web --symlink
sudo php app/console assetic:dump
sudo php app/console ojs:install
sudo php app/console doctrine:fixtures:load --append -v
