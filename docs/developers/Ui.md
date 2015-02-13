#Ui development

This project uses bower and assetic. 
Bower components are not included to repo. You should `bower update` first time, as mentioned in INSTALL documentation.

In every change of assets you must run `php app/console assetic:dump` or you can dump all assetics realtime with `php app/console assetic:watch`

##Third Party Libraries and Requirements for Ui Development

###Uglifycss and uglifyjs
Both Uglifycss and uglifyjs projects are using in _assetic_ asset management configuration. These two projects' code included in the master repo. But if you want to install them to your system you can use npm : `npm install uglifycss -g`

###Bower
Ojs project is using [**bower**](http://bower.io) for Js and Css package management. It can be installed via npm `npm install -g bower`.
Bower packages are configured under `bower.json`. Packages will be automatically installed while _ojs:install_ process. If you want to update packages or in stall new package run `bower install && bower update` 

Here some important packages that will be installed via bower.
####Bootstrap
> Bootstrap is the most popular HTML, CSS, and JS framework for developing **responsive**, **mobile first** projects on the web. The Ojs project's all pages are based on Bootstrap.

####Jquery
> jQuery is a fast, small, and feature-rich JavaScript library. It makes things like **HTML document traversal** and manipulation, **event handling**, animation, and **Ajax** much simpler with an easy-to-use API that works across a multitude of browsers.

####Font-awesome
Font Awesome(Fa) is a css library for icons. 
> Fa gives you scalable vector icons that can instantly be customized.

####Modals
The Ojs project is using bootstrap-dialog for any type of modals.

Documentation http://nakupanda.github.io/bootstrap3-dialog/

