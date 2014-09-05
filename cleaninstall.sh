mysql -uroot -proot ojssf -e "drop database ojssf;"; mysql -uroot -proot -e "create database ojssf charset utf8"; sudo composer install; sudo php app/console ojs:install
