mysql -uroot -proot -e "drop database ojssf;"
sudo php app/console doctrine:database:create
sudo php app/console ojs:install
sudo php app/console doctrine:fixtures:load --append -v
