Installing OJS
==============

This guide will explain how you can install OJS on an Ubuntu server.

Required Software
-----------------
Install and run these services and extensions before attempting to install OJS.

* Nginx
* PostgreSQL
* Memcached
* PHP7
* Elasticsearch
* Node
* Bower

```
# Java

$ sudo add-apt-repository -y ppa:webupd8team/java
$ sudo apt-get update 
$ sudo apt-get -y install oracle-java8-installer


# Elastic

$ wget -qO - https://packages.elastic.co/GPG-KEY-elasticsearch | sudo apt-key add -
$ echo "deb http://packages.elastic.co/elasticsearch/1.7/debian stable main" | sudo tee -a /etc/apt/sources.list.d/elasticsearch-1.7.list
$ sudo apt-get update
$ sudo apt-get -y install elasticsearch

$ sudo update-rc.d elasticsearch defaults 95 10
$ sudo service elasticsearch restart

# PostgreSQL
$ sudo apt-get postgresql git

# Add php7 repo and update

$ LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php -y
$ apt-get update

# Install PHP and its extensions

$ apt-get install -y --force-yes php7.0-cli php7.0-dev \
php-pgsql php-sqlite3 php-gd php-apcu php-curl php7.0-mcrypt \
php-imap php7.0-gd php-memcached php7.0-pgsql php7.0-readline \
php-xdebug php-mbstring php-xml php7.0-zip php7.0-intl php7.0-bcmath

# Install Composer

$ curl -sS https://getcomposer.org/installer | php
$ mv composer.phar /usr/local/bin/composer

# Add Composer executable to PATH if you're using Vagrant

$ printf "\nPATH=\"$(composer config -g home 2>/dev/null)/vendor/bin:\$PATH\"\n" | tee -a /home/vagrant/.profile

# Configure PHP CLI

$ sudo sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.0/cli/php.ini
$ sudo sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.0/cli/php.ini
$ sudo sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/7.0/cli/php.ini
$ sudo sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php/7.0/cli/php.ini

# Install Nginx & PHP-FPM

$ apt-get install -y --force-yes nginx php7.0-fpm

# If you don't have any websites which using files below
$ rm /etc/nginx/sites-enabled/default
$ rm /etc/nginx/sites-available/default
$ service nginx restart

# Configure PHP-FPM

$ sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.0/fpm/php.ini
$ sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.0/fpm/php.ini
$ sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/7.0/fpm/php.ini
$ sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/7.0/fpm/php.ini
$ sed -i "s/upload_max_filesize = .*/upload_max_filesize = 100M/" /etc/php/7.0/fpm/php.ini
$ sed -i "s/post_max_size = .*/post_max_size = 100M/" /etc/php/7.0/fpm/php.ini
$ sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php/7.0/fpm/php.ini

$ service nginx restart
$ service php7.0-fpm restart

# PostgreSQL database and user setup
$ su - postgres
$ psql -d template1 -U postgres

CREATE USER ojs WITH PASSWORD 'ojs';
CREATE DATABASE ojs;
GRANT ALL PRIVILEGES ON DATABASE ojs to ojs;
\q

$ cd $OLDPWD && su

# Node & Bower
$ sudo apt-get install nodejs nodejs-legacy
$ sudo apt-get install npm
$ sudo npm install -g bower

```

Getting most recent version
-----------------------

```
# Create the directory and set permissions
$ sudo mkdir -p /var/www
$ sudo chown -R www-data:www-data /var/www

# Switch to www-data user

$ sudo su -s /bin/bash www-data
$ cd /var/www

# Obtain the latest code

$ git clone https://github.com/ojs/ojs.git
$ cd ojs

```

Installing Dependencies
-----------------------

While installing some depenencies, Composer will ask for a GitHub access token. You don't have to proivde one, as it will try to download from the source but you will need to press `ENTER` each time it tries. If you want to provide, see [GitHub's help article](https://help.github.com/articles/creating-an-access-token-for-command-line-use/).

When installation is complete, you will need to provide some parameters to OJS. Some of those are:

* *Database parameters*: Use the one you have created before installing dependencies. Type them carefully as you might have to re-run this wizard if anything goes wrong.
 * `database_driver` (`pdo_pgsql` by default)
 * `database_host` (`127.0.0.1` by default)
 * `database_port` (`5432` by default)
 * `database_name` (`ojs` by default)
 * `database_user` (`ojs` by default)
 * `database_password` (`ojs` by default)
* `base_host`: Treat this as your domain name. If you want to use a virtualhost, make sure you pass its name here.


```
$ composer update -vvv -o && bower update && php app/console assets:install web --symlink && php app/console assetic:dump && php app/console doctrine:schema:drop --force && php app/console doctrine:schema:create && php app/console ojs:install && php app/console ojs:install:samples

```

After the wizard is done, install the initial data if you would like: `$ php app/console ojs:install:initial-data`


Web Server Configuration Examples
-------------------------
### /etc/nginx/sites-available/ojs

```
server {
    listen 80;
    server_name ojs.prod www.ojs.prod local.ojs.prod *.ojs.prod;
    client_max_body_size 1024M;

    root /var/www/ojs;

    rewrite ^/app.php?(.*)$ /$1 permanent;

    try_files $uri @rewriteapp;
    location @rewriteapp {
        rewrite ^(.*)$ /app.php/$1 last;
    }
    
    location ~ ^/(app|config)\.php(/|$) {
        fastcgi_pass            127.0.0.1:9000;
        fastcgi_buffer_size     16k;
        fastcgi_buffers         4 16k;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include                 fastcgi_params;
        fastcgi_param           SCRIPT_FILENAME     $document_root$fastcgi_script_name;
        fastcgi_param           HTTPS               off;
    }
}

server {
    listen 80;
    server_name ojs.dev www.ojs.dev local.ojs.dev *.ojs.dev;
    client_max_body_size 1024M;

    root /var/www/ojs;

    rewrite ^/app_dev.php?(.*)$ /$1 permanent;

    try_files $uri @rewriteapp;

    location @rewriteapp {
        rewrite ^(.*)$ /app_dev.php/$1 last;
    }

    location ~ ^/(app|app_dev|app_local|config)\.php(/|$) {
        fastcgi_pass            127.0.0.1:9000;
        fastcgi_buffer_size     16k;
        fastcgi_buffers         4 16k;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include                 fastcgi_params;
        fastcgi_param           SCRIPT_FILENAME     $document_root$fastcgi_script_name;
        fastcgi_param           HTTPS               off;
    }
}
```

# Restart nginx service
$ service nginx restart

Install Bundles
----------------

```
# Citation bundle

$ app/console ojs:install:package citation

```


Troubleshooting
----------------
If anything goes wrong (ie. you get a blank page instead of OJS home) check logs under app/log directory and Apache's own log file.


