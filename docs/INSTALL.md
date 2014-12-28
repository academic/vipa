##Install

When it comes to installing the Symfony Standard Edition, you have the
following options.

### Requirements
1. php +5.4 with mcrypt, mysql and mongodb extensions
2. apache or nginx
3. mysql 5.5 or equilent
4. Enough storage
5. [MongoDb](https://github.com/okulbilisim/ojs/tree/master/docs/developers/Mongodb.md)
6. Mysql
7. [ElasticSearch](https://github.com/okulbilisim/ojs/tree/master/docs/developers/ElasticSearch.md)


extreme update command:

```bash
git pull origin master && bower update && composer update -vvv && mysql -u root -p -e "drop database ojs;" && php app/console doctrine:database:create && php app/console ojs:install:travis && php app/console h4cc_alice_fixtures:load:sets
```

## Checking System Configuration

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the `check.php` script from the command line:

```bash
php app/check.php
```

The script returns a status code of `0` if all mandatory requirements are met,
`1` otherwise.

Access the `config.php` script from a browser:
```bash
http://localhost/path/to/symfony/app/web/config.php
```

If you get any warnings or recommendations, fix them before moving on.


#### Using Composer ( _recommended_ )

As Symfony uses [Composer][2] to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

```bash
curl -s http://getcomposer.org/installer | php

# get composer packages more verbosely
$ php composer.phar -vv update
```

install [node.js](http://nodejs.org/download/) and [bower](http://bower.io)

```bash
$ curl https://www.npmjs.org/install.sh | sudo sh
$ npm install -g bower

# get bower packages
$ bower install 

# generate assets
$ php app/console assets:install web --symlink
$ php app/console assetic:dump
$ php app/console cache:clear --env=dev --no-debug
```

### Installer

```bash
# Ojs first run configuration  
$ php app/console doctrine:database:create #create ojs database with given name from parameters.yml 
$ php app/console ojs:install
```

Ojs sample data :
 
```bash
$ php app/console h4cc_alice_fixtures:load:sets 
$ php app/console doctrine:mongodb:fixtures:load --append -v
```  

### Update

You need to update the database on each pull

```bash
$ php app/console doctrine:schema:update --force
```

### Additional Packages

Citation parser service is runnig with Ruby using Sinatra.

```
$ [sudo] gem install sinatra
$ [sudo] gem install anystyle-parser
```

### Run on local environment 

```bash
php app/console server:run
``` 
