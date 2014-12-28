Api documentation can be found under "http://<ojshost>/api/doc/", after installation.

## /api/analytics/download/{entity}/{id}/ ##

### `GET` /api/analytics/download/{entity}/{id}/.{_format} ###

_Get object total download count_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**entity**

**id**



### `PUT` /api/analytics/download/{entity}/{id}/.{_format} ###

_Increment object download count_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**entity**

**id**



## /api/analytics/view/{entity}/{id} ##

### `PUT` /api/analytics/view/{entity}/{id}.{_format} ###

_Increment object view count_

#### Requirements ####

**page_url**

  - Type: string
  - Description: Requested page url
**_format**

  - Requirement: xml|json|html
**entity**

**id**



## /api/analytics/view/{entity}/{id}/ ##

### `GET` /api/analytics/view/{entity}/{id}/.{_format} ###

_Get object total Views_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**entity**

**id**



## /api/article/{id} ##

### `GET` /api/article/{id}.{_format} ###

_Get Specific Article_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**



## /api/article/{id}/citations ##

### `GET` /api/article/{id}/citations.{_format} ###

_Get citation data of an article_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**



## /api/articles/bulk/{page}/{limit} ##

### `GET` /api/articles/bulk/{page}/{limit}.{_format} ###

_Get Articles_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**page**

**limit**



## /api/articles/{id}/bulkcitations ##

### `POST` /api/articles/{id}/bulkcitations.{_format} ###

_Add bulk citation data to an article_

#### Requirements ####

**cites**

  - Requirement: \d+
  - Type: string
  - Description: json encoded citations
**_format**

  - Requirement: xml|json|html
**id**



## /api/articles/{id}/citations ##

### `POST` /api/articles/{id}/citations.{_format} ###

_Add a citation data to an article_

#### Requirements ####

**raw**

  - Requirement: \d+
  - Type: string
  - Description: raw citation in any format
**_format**

  - Requirement: xml|json|html
**id**



## /api/authors ##

### `GET` /api/authors.{_format} ###

_Get Authors_

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/citation/parse ##

### `POST` /api/citation/parse.{_format} ###

_Parse citations_

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Parameters ####

citations:

  * type: string
  * required: true
  * description: citations separated with newline

apikey:

  * type: string
  * required: true
  * description: Apikey


## /api/citations/{id} ##

### `DELETE` /api/citations/{id}.{_format} ###

_Delete a citation with it's settings_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**



## /api/contacts ##

### `GET` /api/contacts.{_format} ###

_Get Contacts_

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/journal/{id} ##

### `GET` /api/journal/{id}.{_format} ###

_Get Specific Journal_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**



## /api/journal/{id}/citations ##

### `GET` /api/journal/{id}/citations.{_format} ###

_Get Citation Action_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**



## /api/journal/{id}/users ##

### `GET` /api/journal/{id}/users.{_format} ###

_Get Specific Journal Of Users Action_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**


#### Parameters ####

page:

  * type: integer
  * required: true
  * description: offset page

limit:

  * type: integer
  * required: true
  * description: limit


## /api/role/{id} ##

### `GET` /api/role/{id}.{_format} ###

_Get Role Action_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**


#### Filters ####

id:

  * DataType: integer


## /api/role/{roleId}/journal/{journalId}/users ##

### `GET` /api/role/{roleId}/journal/{journalId}/users.{_format} ###

_Get Users with this role for this journal_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**roleId**

**journalId**


#### Parameters ####

role_id:

  * type: integer
  * required: true
  * description: role id

journal_id:

  * type: integer
  * required: true
  * description: role id

page:

  * type: integer
  * required: true
  * description: offset page

limit:

  * type: integer
  * required: true
  * description: limit


## /api/role/{roleId}/users ##

### `GET` /api/role/{roleId}/users.{_format} ###

_Get Users with this role_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**roleId**


#### Parameters ####

id:

  * type: integer
  * required: true
  * description: role id

page:

  * type: integer
  * required: true
  * description: offset page

limit:

  * type: integer
  * required: true
  * description: limit


## /api/test/{id} ##

### `GET` /api/test/{id}.{_format} ###

_Get Test Action_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**


#### Filters ####

id:

  * DataType: integer


## /api/user/{username} ##

### `GET` /api/user/{username}.{_format} ###

_Get User Action_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**username**


#### Filters ####

username:

  * DataType: string


## /api/user/{username}/journals ##

### `GET` /api/user/{username}/journals.{_format} ###

_Get User Journals_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**username**


#### Parameters ####

page:

  * type: integer
  * required: true
  * description: offset page

limit:

  * type: integer
  * required: true
  * description: limit


## /api/user/{username}/roles ##

### `GET` /api/user/{username}/roles.{_format} ###

_Get User Roles_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**username**



## /api/users ##

### `GET` /api/users.{_format} ###

_Get Users Action_

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Parameters ####

page:

  * type: integer
  * required: true
  * description: offset page

limit:

  * type: integer
  * required: true
  * description: how many objects to return


### `POST` /api/users.{_format} ###

_Delete User Action_

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/users/{user_id} ##

### `PUT` /api/users/{user_id}.{_format} ###

_Update User Action_

#### Requirements ####

**user_id**

  - Requirement: \d+
  - Type: integer
  - Description: user id
**_format**

  - Requirement: xml|json|html


### `DELETE` /api/users/{user_id}.{_format} ###

_Delete User Action_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**user_id**


#### Filters ####

user_id:

  * DataType: integer


### `PATCH` /api/users/{user_id}/active.{_format} ###

_Change user 'isActive'_

#### Requirements ####

**isActive**

  - Requirement: \d+
  - Type: boolean
  - Description: 0|1
**_format**

  - Requirement: xml|json|html
**user_id**


#### Filters ####

user_id:

  * DataType: integer


### `PATCH` /api/users/{user_id}/status.{_format} ###

_Change user status_

#### Requirements ####

**status**

  - Requirement: \d+
  - Type: integer
  - Description: new user status
**_format**

  - Requirement: xml|json|html
**user_id**


#### Filters ####

user_id:

  * DataType: integer


## /api/wiki/page/create/{object}/{type} ##

### `PUT` /api/wiki/page/create/{object}/{type}.{_format} ###

_Create new page_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**object**

**type**


#### Filters ####

id:

  * Requirement: 
  * Description: null


## /api/wiki/page/delete/{id} ##

### `DELETE` /api/wiki/page/delete/{id}.{_format} ###

_Delete a page_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**



### `PATCH` /api/articles/{articleId}/order/down.{_format} ###

_Increment Article 'orderNum'_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**articleId**



### `PATCH` /api/articles/{articleId}/order/up.{_format} ###

_Increment Article 'orderNum'_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**articleId**



### `PATCH` /api/articles/{article_id}/order.{_format} ###

_Change article 'orderNum'_

#### Requirements ####

**orderNum**

  - Requirement: \d+
  - Type: integer
  - Description: change Article issue order
**_format**

  - Requirement: xml|json|html
**article_id**



### `PATCH` /api/articles/{article_id}/status.{_format} ###

_Change article 'status'_

#### Requirements ####

**status**

  - Requirement: \d+
  - Type: integer
  - Description: Change Article status
**_format**

  - Requirement: xml|json|html
**article_id**
