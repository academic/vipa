[![Build Status](https://travis-ci.org/okulbilisim/ojs.png?branch=master)](https://travis-ci.org/okulbilisim/ojs)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/okulbilisim/ojs/badges/quality-score.png?s=1f77d7ffae4541cee084070f5fb33819abd2c561)](https://scrutinizer-ci.com/g/okulbilisim/ojs/)


**Warning** : This project is under development.

#Open Journal Systems

**Open Journal Systems (OJS)** is a journal management and publishing system with **Symfony 2 Framework** that has been developed by the Public Knowledge Project through its federally funded efforts to expand and improve access to research.

We fork [PKP/OJS](http://pkp.sfu.ca/ojs/)

please do fork us


##OJS Features

1. OJS is installed locally and locally controlled.
2. Editors configure requirements, sections, review process, etc.
3. Online submission and management of all content.
4. Subscription module with delayed open access options.
5. Comprehensive indexing of content part of global system.
6. Reading Tools for content, based on field and editors’ choice.
7. Email notification and commenting ability for readers.
8. Complete context-sensitive online Help support.

OJS assists with every stage of the refereed publishing process, from submissions through to online publication and indexing. Through its management systems, its finely grained indexing of research, and the context it provides for research, OJS seeks to improve both the scholarly and public quality of refereed research.

OJS is open source software made freely available to journals worldwide for the purpose of making open access publishing a viable option for more journals, as open access can increase a journal’s readership as well as its contribution to the public good on a global scale (see PKP Publications).

1) Installing
----------------------------------

When it comes to installing the Symfony Standard Edition, you have the
following options.

### Use Composer (*recommended*)

As Symfony uses [Composer][2] to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then, use the `update` command to generate a new Symfony application:

    php composer.phar update doctrine/doctrine-fixtures-bundle


Generate shemas after installation : 

    php app/console ojs:install


Run on your local environment : 

    php app/console server:run



2) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the `check.php` script from the command line:

    php app/check.php

The script returns a status code of `0` if all mandatory requirements are met,
`1` otherwise.

Access the `config.php` script from a browser:

    http://localhost/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.


3) Symfony 2 Framework
-------------------------------------

You may have a look at [Symfony2 cheatsheet](http://www.symfony2cheatsheet.com/)


4) Documentation
-------------------------------------

There is no documentation yet. 

Db schema can be viewed at http://dbpatterns.com/documents/531096ba9785db3d7764801e/edit .


5) RoadMap
-------------------------------------

1. TODO


