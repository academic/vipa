[![Build Status](https://travis-ci.org/okulbilisim/ojs.png?branch=master)](https://travis-ci.org/okulbilisim/ojs)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/okulbilisim/ojs/badges/quality-score.png?s=1f77d7ffae4541cee084070f5fb33819abd2c561)](https://scrutinizer-ci.com/g/okulbilisim/ojs/)
[![License](https://poser.pugx.org/okulbilisim/ojs/license.png)](https://packagist.org/packages/okulbilisim/ojs)
[![Latest Unstable Version](https://poser.pugx.org/okulbilisim/ojs/v/unstable.png)](https://packagist.org/packages/okulbilisim/ojs)
[![DOI](https://zenodo.org/badge/doi/10.5281/zenodo.11908.png)](http://dx.doi.org/10.5281/zenodo.11908)


**Warning** : This project is under development.

#Open Journal Systems

**Open Journal Systems (OJS)** is a journal management and publishing system with **Symfony 2 Framework** that has been developed by the Public Knowledge Project through its federally funded efforts to expand and improve access to research.

As a company we [OkulBilişim](http://www.okulbilisim.com), analyze [PKP/OJS](http://pkp.sfu.ca/ojs/) and make it: harder, better, faster and stronger with [SF2](http://en.wikipedia.org/wiki/Symfony) and [cloud](http://en.wikipedia.org/wiki/Cloud_computing) with sponsorship of [TÜBİTAK-ULAKBİM](http://www.ulakbim.gov.tr)

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



## 1) Installing

Read [Install.md](https://github.com/okulbilisim/ojs/tree/master/INSTALL.md)


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

OJS will have two type of API: read-only and full write access. The early version of project's admin panel and core will be more integrated but the initial release will be fully working over REST-API.

OJS-API will aslo have oAuth 2.0a authentication feature.

### 3.2. UI

All the UI components are based on [Bootstrap](http://getbootstrap.com). So anyone can modify any elements with a small html/css knowledge.

We'll also provide a WP alike Theme and Plugin mechanism.

### 3.3. DB

Db schema can be viewed at [dbpatterns.com](http://dbpatterns.com/documents/531096ba9785db3d7764801e/edit) .

### 3.4. Search

We built the search on top of [ElasticSearch](htttp://elasticsearch.org) so all binding are ready for the search.

### 3.5. Log

Log mechanism is based on `symfony/monolog-bundle` but in the near future GrayLog2 will implemented

## 4) RoadMap

Our roadmap is driven by the [issues on Github](https://github.com/okulbilisim/ojs/milestones)

Our effords to finish abow the main features

## 5) Troubleshooting

1. PHP Fatal error:  Allowed memory size of # bytes exhauste
	* set `memory_limit = 1024M` at your  (_php.ini_) 

2. set data.timezone with correct timezone at php.ini

 ```
[Symfony\Component\Debug\Exception\ContextErrorException]                                                                                                                                                                                                   
  Warning: date_default_timezone_get(): Invalid date.timezone value 'XX/XX', we selected the timezone 'UTC' for now.
```

