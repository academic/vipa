**Warning**: This project will be released at late Q3 of 2015.

#Open Journal Software

[![Build Status](https://img.shields.io/travis/okulbilisim/ojs/master.svg?style=flat-square)](https://travis-ci.org/okulbilisim/ojs)
[![Quality Status](https://img.shields.io/scrutinizer/g/okulbilisim/ojs.svg?style=flat-square)](https://scrutinizer-ci.com/g/okulbilisim/ojs/)

**Open Journal Software (OJS)** is a journal management and publishing software bulit with **Symfony 2 Framework**.

As a company we, [OkulBilişim](http://www.okulbilisim.com), analyzed journal management systems and needs of the community and decided to make a harder, better, faster and stronger content management and workflow software using [Symfony 2](http://en.wikipedia.org/wiki/Symfony) and [cloud](http://en.wikipedia.org/wiki/Cloud_computing) in parnership with [TÜBİTAK-ULAKBİM](http://www.ulakbim.gov.tr)

## Installing

Read [Install.md](https://github.com/okulbilisim/ojs/tree/master/docs/INSTALL.md)

###TL;DR I'm a guru

```
composer update -vvv -o&& bower update && php app/console assets:install web --symlink && php app/console assetic:dump && mysql -u root -p -e "DROP DATABASE IF EXISTS ojs;create database ojs;" && php app/console ojs:install && app/console ojs:install:initial-data
```
