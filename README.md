[![Build Status](https://travis-ci.org/okulbilisim/ojs.png?branch=master)](https://travis-ci.org/okulbilisim/ojs)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/okulbilisim/ojs/badges/quality-score.png?s=1f77d7ffae4541cee084070f5fb33819abd2c561)](https://scrutinizer-ci.com/g/okulbilisim/ojs/)
[![License](https://poser.pugx.org/okulbilisim/ojs/license.png)](https://packagist.org/packages/okulbilisim/ojs)
[![Latest Unstable Version](https://poser.pugx.org/okulbilisim/ojs/v/unstable.png)](https://packagist.org/packages/okulbilisim/ojs)
[![Code Climate](https://codeclimate.com/github/okulbilisim/ojs.png)](https://codeclimate.com/github/okulbilisim/ojs)

**Warning** : This project is under development.

#Open Journal Systems

**Open Journal Systems (OJS)** is a journal management and publishing system with **Symfony 2 Framework** that has been developed by the Public Knowledge Project through its federally funded efforts to expand and improve access to research.

As a company we [OkulBilişim](http://www.okulbilisim.com), analyze [PKP/OJS](http://pkp.sfu.ca/ojs/) and make it: harder, better, faster and stronger with [SF2](http://en.wikipedia.org/wiki/Symfony) and [cloud](http://en.wikipedia.org/wiki/Cloud_computing).

- please do [fork](https://github.com/okulbilisim/ojs/fork) us
- please [open](https://github.com/okulbilisim/ojs/issues/new) a issue for improvement/feature/bug  


##OJS Features

OJS is open source software made freely available to journals worldwide for the purpose of making open access publishing a viable option for more journals, as open access can increase a journal’s readership as well as its contribution to the public good on a global scale.

OJS assists with every stage of the refereed publishing process, from submissions through to online publication and indexing. Through its management systems, its finely grained indexing of research, and the context it provides for research, OJS seeks to improve both the scholarly and public quality of refereed research.

##### Main Features

1. OJS is installed locally and locally controlled.
2. Editors configure requirements, sections, review process, etc.
3. Online submission and management of all content.
4. Subscription module with delayed open access options.
5. Comprehensive indexing of content part of global system.
6. Reading Tools for content, based on field and editors’ choice.
7. Email notification and commenting ability for readers.
8. Complete context-sensitive online Help support.

##### Platform Feautures

1. PHP +5.4
2. Doctrine ORM
3. Composer
4. PHPunit



## 1) Installing


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


if you have database for OJS then installer ask you:

`Create db? (y/n) : `

if not installer create a database and tables that required.

Or create a database and user; grant all privilages then start install script.
```bash
create database ojs;
create user ojs;
grant all on ojs.* to 'ojs'@'localhost' identified by 'ojs';
```

### Installer

```bash
# Ojs first run configuration  
$ php app/console ojs:install
```



Ojs sample data :
 
```bash
$ php app/console doctrine:fixtures:load --append
```  

Run on your local environment : 

```bash
php app/console server:run
``` 


## 2) Checking System Configuration

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


## 3) Framework

You may have a look at [Symfony2 cheatsheet](http://www.symfony2cheatsheet.com/)

### 3.1. API
### 3.2. UI
### 3.3. DB
Db schema can be viewed at [dbpatterns.com](http://dbpatterns.com/documents/531096ba9785db3d7764801e/edit) .
### 3.4. Search
### 3.5. Log



## 4) RoadMap


1. TODO


## 5) Troubleshooting

1. PHP Fatal error:  Allowed memory size of # bytes exhauste
	* set ```memory_limit = 1024M``` at your  (_php.ini_) 

2.
 ```
[Symfony\Component\Debug\Exception\ContextErrorException]                                                                                                                                                                                                   
  Warning: date_default_timezone_get(): Invalid date.timezone value 'XX/XX', we selected the timezone 'UTC' for now.
```
	* set data.timezone with correct timezone at php.ini


3. ```
 GitHub API rate limit exceeded for XXX.XXX.XXX.XXX. (But here's the good news: Authenticated requests get a higher rate limit. Check out the documentation for more details.)
Try again in 13 minutes 11 seconds, or create an API token:
  https://github.com/settings/applications
and then set HOMEBREW_GITHUB_API_TOKEN.
```