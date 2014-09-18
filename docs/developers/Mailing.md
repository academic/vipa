Mailing
-------

We use SwiftMailer to send mails. Spool option is active in configuration. However you can change it.

Here is a sample code to send (or add to spool) an email.
```
	$msgBody = $this->renderView(
            'OjstrUserBundle:Mails:testMail.html.twig', array(
                'testMessage' => "test"
            )
        );
        
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('noreply@okulbilisim.com')
            ->setTo('sacrosancttayyar@gmail.com')
            ->setBody($msgBody)
            ->setContentType('text/html');
        $this->get('mailer')->send($message);
```

If you didn't change configuration of swiftmailer, when you send en email, it will not actually be sent but instead added to the spool.
Sending the messages from the spool is done separately.
If you do not want to spool emails you can disable it from app/config/config.yml by commenting  spool section under swiftmailer.
 
There is a console command to send the messages in the spool:
```
$ php app/console swiftmailer:spool:send --env=prod
```
It has an option to limit the number of messages to be sent:
```
$ php app/console swiftmailer:spool:send --message-limit=10 --env=prod
```
You can also set the time limit in seconds:
```
$ php app/console swiftmailer:spool:send --time-limit=10 --env=prod
```

You can write a cronjob to run spool:send commands.


####Warning
In development environment swift mailer will send all mails to email that referred in config_dev under swiftmailer .
You can disable this feature by commenting these lines under config_dev.yml
```
swiftmailer:
    delivery_address: tayyar.besik@okulbilisim.com
```