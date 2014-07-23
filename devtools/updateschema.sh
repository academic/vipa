# run from project root
sudo service memcached restart
php app/console doctrine:schema:update --force  -v
