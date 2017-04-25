# institution #

## /api/v1/institutions ##

### `GET` /api/v1/institutions.{_format} ###

_List all Institutions._

List all Institutions.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Institutions.

limit:

  * Requirement: \d+
  * Description: How many Institutions to return.
  * Default: 5


### `POST` /api/v1/institutions.{_format} ###

_Creates a new Institution from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/institutions/new ##

### `GET` /api/v1/institutions/new.{_format} ###

_Presents the form to use to create a new Institution._

Presents the form to use to create a new Institution.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/institutions/{id} ##

### `DELETE` /api/v1/institutions/{id}.{_format} ###

_Delete Institution_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Institution ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/institutions/{id}.{_format} ###

_Gets a Institution for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Institution id


### `PATCH` /api/v1/institutions/{id}.{_format} ###

_Update existing institution from the submitted data or create a new institution at a specific location._

Update existing institution from the submitted data or create a new institution at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the institution id


### `PUT` /api/v1/institutions/{id}.{_format} ###

_Update existing Institution from the submitted data or create a new Institution at a specific location._

Update existing Institution from the submitted data or create a new Institution at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Institution id
