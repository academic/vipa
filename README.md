[![Build Status](https://travis-ci.org/okulbilisim/ojs.png?branch=master)](https://travis-ci.org/okulbilisim/ojs)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/okulbilisim/ojs/badges/quality-score.png?s=1f77d7ffae4541cee084070f5fb33819abd2c561)](https://scrutinizer-ci.com/g/okulbilisim/ojs/)
[![License](https://poser.pugx.org/okulbilisim/ojs/license.png)](https://packagist.org/packages/okulbilisim/ojs)
[![Latest Unstable Version](https://poser.pugx.org/okulbilisim/ojs/v/unstable.png)](https://packagist.org/packages/okulbilisim/ojs)
[![DOI](https://zenodo.org/badge/doi/10.5281/zenodo.11908.png)](http://dx.doi.org/10.5281/zenodo.11908)


**Warning** : This project is under development.

#Open Journal Systems

**Open Journal Systems (OJS)** is a journal management and publishing software with **Symfony 2 Framework**.

As a company we [OkulBilişim](http://www.okulbilisim.com), analyze journal management systems and needs and We make a harder, better, faster and stronger content management and workflow software with [Symfony 2](http://en.wikipedia.org/wiki/Symfony) and [cloud](http://en.wikipedia.org/wiki/Cloud_computing) with parnership of [TÜBİTAK-ULAKBİM](http://www.ulakbim.gov.tr)

- please do [fork](https://github.com/okulbilisim/ojs/fork) us
- please [open](https://github.com/okulbilisim/ojs/issues/new) an issue for improvement/feature/bug  


## Installing

Read [Install.md](https://github.com/okulbilisim/ojs/tree/master/INSTALL.md)

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

## More information

[contact](mailto:info@okulbilisim.com) with [us](http://okulbilisim.com)

and you may visit [OJS.io](http://ojs.io)