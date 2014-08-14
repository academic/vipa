##Install

When it comes to installing the Symfony Standard Edition, you have the
following options.

### Requirements
1. php +5.4 with mcrypt, mysql and mongodb extensions
2. apache or nginx
3. mysql 5.5 or equilent
4. Enough storage
5. MongoDb
6. Mysql



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
```

### Installer

```bash
# Ojs first run configuration  
$ php app/console doctrine:database:create --env=test #create ojs test database
$ php app/console doctrine:database:create #create ojs database with given name from parameters.yml 
$ php app/console ojs:install
```

Ojs sample data :
 
```bash
$ php app/console doctrine:fixtures:load --append -v
$ php app/console doctrine:mongodb:fixtures:load --append -v
```  

Run on your local environment : 

```bash
php app/console server:run
``` 
