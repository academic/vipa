# period #

## /api/v1/periods ##

### `GET` /api/v1/periods.{_format} ###

_List all Periods._

List all Periods.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Periods.

limit:

  * Requirement: \d+
  * Description: How many Periods to return.
  * Default: 5


### `POST` /api/v1/periods.{_format} ###

_Creates a new Period from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/periods/new ##

### `GET` /api/v1/periods/new.{_format} ###

_Presents the form to use to create a new Period._

Presents the form to use to create a new Period.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/periods/{id} ##

### `DELETE` /api/v1/periods/{id}.{_format} ###

_Delete Period_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Period ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/periods/{id}.{_format} ###

_Gets a Period for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Period id


### `PATCH` /api/v1/periods/{id}.{_format} ###

_Update existing period from the submitted data or create a new period at a specific location._

Update existing period from the submitted data or create a new period at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the period id


### `PUT` /api/v1/periods/{id}.{_format} ###

_Update existing Period from the submitted data or create a new Period at a specific location._

Update existing Period from the submitted data or create a new Period at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Period id
