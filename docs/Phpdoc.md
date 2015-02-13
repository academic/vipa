Installing Phpdoc
-----------------


**Pear**

```
pear channel-discover pear.phpdoc.org
pear install phpdoc/phpDocumentor
```

**Composer**

You can also install  phpdoc via composer 

```
{
    "require-dev": {
        "phpdocumentor/phpdocumentor": "2.*"
    }
}
```

**Direct Download**

http://phpdoc.org/phpDocumentor.phar


Generate Docs
-------------

Run `phpdoc -d ./src/Ojs -t ./docs/phpdoc` in applicatioın root folder
