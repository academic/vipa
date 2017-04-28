# index #

## /api/v1/indexes ##

### `GET` /api/v1/indexes.{_format} ###

_List all Indexs._

List all Indexs.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Indexs.

limit:

  * Requirement: \d+
  * Description: How many Indexs to return.
  * Default: 5


### `POST` /api/v1/indexes.{_format} ###

_Creates a new Index from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/indexes/new ##

### `GET` /api/v1/indexes/new.{_format} ###

_Presents the form to use to create a new Index._

Presents the form to use to create a new Index.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/indexes/{id} ##

### `DELETE` /api/v1/indexes/{id}.{_format} ###

_Delete Index_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Index ID
**_format**

  - Requirement: xml|json|html


### `PATCH` /api/v1/indexes/{id}.{_format} ###

_Update existing index from the submitted data or create a new index at a specific location._

Update existing index from the submitted data or create a new index at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the index id


### `PUT` /api/v1/indexes/{id}.{_format} ###

_Update existing Index from the submitted data or create a new Index at a specific location._

Update existing Index from the submitted data or create a new Index at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Index id


## /api/v1/indexs/{id} ##

### `GET` /api/v1/indexs/{id}.{_format} ###

_Gets a Index for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Index id
