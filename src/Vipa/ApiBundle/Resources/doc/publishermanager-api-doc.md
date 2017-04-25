# publishermanager #

## /api/v1/publishermanagers ##

### `GET` /api/v1/publishermanagers.{_format} ###

_List all PublisherManager._

List all PublisherManager.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PublisherManager.

limit:

  * Requirement: \d+
  * Description: How many PublisherManager to return.
  * Default: 5


### `POST` /api/v1/publishermanagers.{_format} ###

_Creates a new PublisherManager from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishermanagers/new ##

### `GET` /api/v1/publishermanagers/new.{_format} ###

_Presents the form to use to create a new PublisherManager._

Presents the form to use to create a new PublisherManager.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishermanagers/{id} ##

### `DELETE` /api/v1/publishermanagers/{id}.{_format} ###

_Delete PublisherManager_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PublisherManager ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/publishermanagers/{id}.{_format} ###

_Gets a PublisherManager for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherManager id


### `PATCH` /api/v1/publishermanagers/{id}.{_format} ###

_Update existing publishermanager from the submitted data or create a new publishermanager at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisher_manager id


### `PUT` /api/v1/publishermanagers/{id}.{_format} ###

_Update existing PublisherManager from the submitted data or create a new PublisherManager at a specific location._

Update existing PublisherManager from the submitted data or create a new PublisherManager at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherManager id
