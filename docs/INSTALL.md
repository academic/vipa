Installing OJS
==============

This guide will explain how you can install OJS on an Ubuntu server.

Required Software
-----------------
Install and run these services and extensions before attempting to install OJS.

* Apache (`# apt-get install apache2`)
* MySQL (`# apt-get install mysql-server`)
* MongoDB (`# apt-get install mongodb`)
* Memcached (`# apt-get install memcached`)
* PHP (`# apt-get install php5 php5-mysql php5-mongo php5-mcrypt php5-memcached php5-curl`)
* Elasticsearch (download it from [here](https://www.elastic.co/downloads/elasticsearch))

You can also use nginx as your HTTP server and PostgreSQL as your RDBMS server.

Installing Dependencies
-----------------------
Right before installing dependencies, create a database for OJS because OJS's installation wizard will ask for a database name after dependencies are installed.

To install dependencies you will need Composer and bower. If you don't have those installed, refer to [Composer's own installation guide](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) and [Bower's own installation guide](http://bower.io/#install-bower).

You might want to move Composer to a directory in PATH, so you can access Composer from anywhere. Run `# mv composer.phar /usr/local/bin/composer` to do that.

Bower needs `npm` for installation. To install it on Ubuntu, use`# apt-get install npm`. Since Bower will use `node` command, you will need to create a symlink to nodejs using `# ln -s /usr/bin/nodejs /usr/bin/node`.

After installing both Composer and Bower, run `$ composer install`. It will download and install any dependency that OJS requires. While installing some depenencies, Composer will ask for a GitHub access token. You don't have to proivde one, as it will try to download from the source but you will need to press `ENTER` each time it tries. If you want to provide, see [GitHub's help article](https://help.github.com/articles/creating-an-access-token-for-command-line-use/).

When installation is complete, you will need to provide some parameters to OJS. Some of those are:

* *Database parameters*: Use the one you have created before installing dependencies. Type them carefully as you might have to re-run this wizard if anything goes wrong.
 * `database_driver` (`pdo_mysql` by default)
 * `database_host` (`127.0.0.1` by default)
 * `database_port` (`3306` by default)
 * `database_name` (`ojs` by default)
 * `database_user` (`root` by default)
 * `database_password` (`null` by default)
* `base_host`: Treat this as your domain name. If you want to use a virtualhost, make sure you pass its name here.

After the wizard is done, install tne initial data if you would like: `$ php app/console ojs:install:initial-data`

You need to run `$ bower install` to get external JavaScript and CSS libraries. After Bower is done  run `$ php app/console assets:install web --symlink` to install assets using symlinks and run `$ php app/console assetic:dump`to dump them.

Troubleshooting
----------------
If anything goes wrong (ie. you get a blank page instead of OJS home) check logs under app/log directory and Apache's own log file.
