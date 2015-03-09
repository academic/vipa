#Api

Notes about Api development.

##Development

friendsofsymfony/rest-bundle is using for rest api development. Learn more about FOS/rest-bundle https://github.com/FriendsOfSymfony/FOSRestBundle

Api documentation is generated automatically based on annotations. 
To see api documentation just type 

```
http://<your-ojs-address>/api/doc
```

###Creating New Api Controller
- Place your new controller under src/Ojs/ApiBundle/Controller
- Extend your controller from FOSRestController `class NewController extends FOSRestController`

###Routing
Add routing configuration to src/Ojs/ApiBundle/Resources/config/routing_rest.yml
```
new_routing:
  type: rest
  resource:     "OjsApiBundle:New"
  name_prefix:  api_
```

Use FOSRest annotations for routing configuration. More information at https://github.com/FriendsOfSymfony/FOSRestBundle

###Documenting
Place ApiDoc annotations above each action method.
```
/**
     * @ApiDoc(
     *  resource=true,
     *  description="Increment object view count",
     *  requirements={
     *      {
     *          "name"="page_url",
     *          "dataType"="string",
     *          "description"="Requested page url"
     *      }
     *  }
     * )
     */
```

for more information about ApiDoc configuration see  https://github.com/FriendsOfSymfony/FOSRestBundle.

Your documantation will be prepared automatically when you visit /api/doc page.

###Testing

Place your standart test classes under src/Ojs/ApiBundle/Tests