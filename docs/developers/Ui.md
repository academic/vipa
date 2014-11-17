#Ui development

This project uses bower and assetic. 
Bower components are not included to repo. You should `bower update` first time, as mentioned in INSTALL documentation.

In every change of assets you must run `php app/console assetic:dump` or you can dump all assetics realtime with `php app/console assetic:watch`

##Third Party Libraries and Requirements for Ui Development

Uglifycss and uglifyjs

###Bootstrap

###Jquery

###Font-awesome

###jquery-file-upload

###Modals
We are using bootstrap-dialog for any type of modals.

Documentation http://nakupanda.github.io/bootstrap3-dialog/


###bootstrap3-wysihtml5-bower
