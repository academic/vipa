Mailing
-------


If you want to send an email without adding it to mail queue you can use SwiftMailer 


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


When you send en email, it will not actually be sent but instead added to the spool. Sending the messages from the spool is done separately. If you do not want to spool emails you can disable it from app/config/config.yml by commenting  spool section under swiftmailer.
 
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

