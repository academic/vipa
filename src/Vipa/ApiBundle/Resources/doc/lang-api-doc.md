# lang #

## /api/v1/langs ##

### `GET` /api/v1/langs.{_format} ###

_List all Langs._

List all Langs.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Langs.

limit:

  * Requirement: \d+
  * Description: How many Langs to return.
  * Default: 5


### `POST` /api/v1/langs.{_format} ###

_Creates a new Lang from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/langs/new ##

### `GET` /api/v1/langs/new.{_format} ###

_Presents the form to use to create a new Lang._

Presents the form to use to create a new Lang.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/langs/{id} ##

### `DELETE` /api/v1/langs/{id}.{_format} ###

_Delete Lang_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Lang ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/langs/{id}.{_format} ###

_Gets a Lang for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Lang id


### `PATCH` /api/v1/langs/{id}.{_format} ###

_Update existing lang from the submitted data or create a new lang at a specific location._

Update existing lang from the submitted data or create a new lang at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the lang id


### `PUT` /api/v1/langs/{id}.{_format} ###

_Update existing Lang from the submitted data or create a new Lang at a specific location._

Update existing Lang from the submitted data or create a new Lang at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Lang id
