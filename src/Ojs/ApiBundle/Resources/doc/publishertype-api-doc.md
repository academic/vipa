# publishertype #

## /api/v1/publishertypes ##

### `GET` /api/v1/publishertypes.{_format} ###

_List all PublisherTypes._

List all PublisherTypes.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PublisherTypes.

limit:

  * Requirement: \d+
  * Description: How many PublisherTypes to return.
  * Default: 5


### `POST` /api/v1/publishertypes.{_format} ###

_Creates a new PublisherType from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishertypes/new ##

### `GET` /api/v1/publishertypes/new.{_format} ###

_Presents the form to use to create a new PublisherType._

Presents the form to use to create a new PublisherType.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishertypes/{id} ##

### `DELETE` /api/v1/publishertypes/{id}.{_format} ###

_Delete PublisherType_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PublisherType ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/publishertypes/{id}.{_format} ###

_Gets a PublisherType for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherType id


### `PATCH` /api/v1/publishertypes/{id}.{_format} ###

_Update existing publisherType from the submitted data or create a new publisherType at a specific location._

Update existing publisherType from the submitted data or create a new publisherType at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisherType id


### `PUT` /api/v1/publishertypes/{id}.{_format} ###

_Update existing PublisherType from the submitted data or create a new PublisherType at a specific location._

Update existing PublisherType from the submitted data or create a new PublisherType at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherType id
