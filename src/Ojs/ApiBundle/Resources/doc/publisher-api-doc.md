# publisher #

## /api/v1/publishers ##

### `GET` /api/v1/publishers.{_format} ###

_List all Publishers._

List all Publishers.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Publishers.

limit:

  * Requirement: \d+
  * Description: How many Publishers to return.
  * Default: 5


### `POST` /api/v1/publishers.{_format} ###

_Creates a new Publisher from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishers/new ##

### `GET` /api/v1/publishers/new.{_format} ###

_Presents the form to use to create a new Publisher._

Presents the form to use to create a new Publisher.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishers/{id} ##

### `DELETE` /api/v1/publishers/{id}.{_format} ###

_Delete Publisher_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Publisher ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/publishers/{id}.{_format} ###

_Gets a Publisher for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Publisher id


### `PATCH` /api/v1/publishers/{id}.{_format} ###

_Update existing publisher from the submitted data or create a new publisher at a specific location._

Update existing publisher from the submitted data or create a new publisher at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisher id


### `PUT` /api/v1/publishers/{id}.{_format} ###

_Update existing Publisher from the submitted data or create a new Publisher at a specific location._

Update existing Publisher from the submitted data or create a new Publisher at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Publisher id
