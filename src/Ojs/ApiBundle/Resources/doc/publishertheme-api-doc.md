# publishertheme #

## /api/v1/publisherthemes ##

### `GET` /api/v1/publisherthemes.{_format} ###

_List all PublisherTheme._

List all PublisherTheme.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PublisherTheme.

limit:

  * Requirement: \d+
  * Description: How many PublisherTheme to return.
  * Default: 5


### `POST` /api/v1/publisherthemes.{_format} ###

_Creates a new PublisherTheme from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publisherthemes/new ##

### `GET` /api/v1/publisherthemes/new.{_format} ###

_Presents the form to use to create a new PublisherTheme._

Presents the form to use to create a new PublisherTheme.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publisherthemes/{id} ##

### `DELETE` /api/v1/publisherthemes/{id}.{_format} ###

_Delete PublisherTheme_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PublisherTheme ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/publisherthemes/{id}.{_format} ###

_Gets a PublisherTheme for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherTheme id


### `PATCH` /api/v1/publisherthemes/{id}.{_format} ###

_Update existing publishertheme from the submitted data or create a new publishertheme at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisher_theme id


### `PUT` /api/v1/publisherthemes/{id}.{_format} ###

_Update existing PublisherTheme from the submitted data or create a new PublisherTheme at a specific location._

Update existing PublisherTheme from the submitted data or create a new PublisherTheme at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherTheme id
