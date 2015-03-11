##JournalWorkflowSteps 


###Attributes

- **id** : `@MongoDb\Id` mongodb object id
- **journalid** : `@MongoDb\Int` 
- **title** :  `@MongoDb\String`
- **status** :  `@MongoDb\String` 
- **firstStep** : `@MongoDb\Boolean` is this step first step in the workflow?
- **lastStep** : `@MongoDb\Boolean` is this step last step in the workflow?
- **onlyreply** : `@MongoDb\Boolean` if true the user who works on this step can only reply to parent/redirected step.
- **nextSteps** : `@MongoDB\Hash` possisble next steps that user can forward.
    ````{
     "0" : {
         "id" : "53ba97facf93a1cf5e8b4567",
         "title" : "First Review"
     },
     "1" : {
         "id" : "53baa7aecf93a1dc268b456a",
         "title" : "Redaction"
     }
    }````
- **roles** : `@MongoDB\Hash` roles that can see and work on this step.
    ````{
     "0" : {
         "id" : 7,
         "name" : "Editor",
         "role" : "ROLE_EDITOR"
     },
     "1" : {
         "id" : 11,
         "name" : "Copyeditor",
         "role" : "ROLE_COPYEDITOR"
     }
    }````
- **maxdays** : `@MongoDB\Int`  Default maxdays for this step for review
- **canSeeAuthor** : `@MongoDb\Boolean` `default: true` if false user that works on this step can't see the author
- **isVisible** : `@MongoDb\Boolean`  `default: true` if false user that works on this step can't be seen by anybody except editor and journal manager.

**Note for double-blind reviews** : canSeeAuthor and isVisible attributes is false this step can act like a double-blind step.