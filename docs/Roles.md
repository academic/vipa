Roles
-----

Ojs has 14 types of role definition.


| Role Name 					| Role Description							|
|-------------------------------|-------------------------------------------|
| ROLE_USER 					| Standart User								|
| ROLE_ADMIN 					| Administrator								|
| ROLE_SYSTEM_ADMIN				| System Administrator						|
| ROLE_SUPER_EDITOR			 	| Super Editor. Editor for all journals		|
| ROLE_SUPER_AUTHOR			 	| Super Author. Author for all journals		|
| ROLE_JOURNAL_MANAGER		 	| Journal Manager 							|
| ROLE_SUBSCRIPTION_MANAGER	 	| Subscription Manager 						|
| ROLE_EDITOR 				 	| Editor 									|
| ROLE_SECTION_EDITOR			| Section Editor 							|
| ROLE_LAYOUT_EDITOR			| Layout Editor 							|
| ROLE_REVIEWER				 	| Reviewer 									|
| ROLE_COPYEDITOR 			 	| Copyeditor 								|
| ROLE_PROOFREADER			 	| Proofreader 								|
| ROLE_AUTHOR 				 	| Author 									|
| ROLE_READER 				 	| Standart Reader 							|


Journal Roles
-------------

Journal roles are stored on `UserJournalRole` table;

Each journal has standard roles which listed belove. But every journal has its own special role with its' system defined unique id.

**Example**

*User:*

    id: 123
    username: adam

*Journal:* 

    id: 45
    title: Applied Mathematics

*Role:*

    id: 6
    name: ROLE_EDITOR


*User_Journal_Role:*

    journal_id: 45
    user_id: 123
    role_id: 6 



System Roles
------------

System roles are stored on `User_Role`


