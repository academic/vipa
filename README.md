#Open Journal Software (OJS.io)

[![Build Status](https://travis-ci.org/ojs/ojs.svg?branch=master)](https://travis-ci.org/ojs/ojs)
[![Quality Status](https://img.shields.io/scrutinizer/g/bulutyazilim/ojs.svg?style=flat-square)](https://scrutinizer-ci.com/g/ojs/ojs/)

**Open Journal Software (OJS)** is a journal management and publishing software built with **Symfony 2 Framework**.

As a company we, [BulutYazilim](http://www.bulutyazilim.com), analyzed journal management systems and needs of the community and decided to make a harder, better, faster and stronger content management and workflow software using [Symfony 2](http://en.wikipedia.org/wiki/Symfony) and [cloud](http://en.wikipedia.org/wiki/Cloud_computing) in parnership with [TÜBİTAK-ULAKBİM](http://www.ulakbim.gov.tr).

This project adheres to the [Open Code of Conduct](https://github.com/bulutyazilim/ojs/tree/master/code_of_conduct.md). By participating, you are expected to honor this code.

## Download 

[Latest](https://github.com/ojs/ojs/releases/latest)

## Installing

Read [the installation document](https://github.com/ojs/ojs/tree/master/docs/INSTALL.md).<br>
Read [API v1 documents](https://github.com/ojs/ojs/tree/master/src/Ojs/ApiBundle/Resources/doc).

**Quick install command (WARNING: this command will empty the database which you specify during installation)**

```
composer update -vvv -o && bower update && php app/console assets:install web --symlink && php app/console assetic:dump && php app/console doctrine:schema:drop --force && php app/console doctrine:schema:create && php app/console ojs:install && php app/console ojs:install:samples
```

## Donate
You may [donate](https://www.paypal.me/OkulBilisim) to us.
