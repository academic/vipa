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
- please [open](https://github.com/okulbilisim/ojs/issues/new) an issue for improvement/feature/bug  


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

[Read Installation page](https://github.com/okulbilisim/ojs/blob/master/INSTALL.md)

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
	* set `memory_limit = 1024M` at your  (_php.ini_) 

2. set data.timezone with correct timezone at php.ini

 ```
[Symfony\Component\Debug\Exception\ContextErrorException]                                                                                                                                                                                                   
  Warning: date_default_timezone_get(): Invalid date.timezone value 'XX/XX', we selected the timezone 'UTC' for now.
```

