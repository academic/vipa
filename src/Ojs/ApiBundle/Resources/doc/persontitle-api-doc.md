# persontitle #

## /api/v1/persontitles ##

### `GET` /api/v1/persontitles.{_format} ###

_List all PersonTitles._

List all PersonTitles.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PersonTitles.

limit:

  * Requirement: \d+
  * Description: How many PersonTitles to return.
  * Default: 5


### `POST` /api/v1/persontitles.{_format} ###

_Creates a new PersonTitle from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/persontitles/new ##

### `GET` /api/v1/persontitles/new.{_format} ###

_Presents the form to use to create a new PersonTitle._

Presents the form to use to create a new PersonTitle.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/persontitles/{id} ##

### `DELETE` /api/v1/persontitles/{id}.{_format} ###

_Delete PersonTitle_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PersonTitle ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/persontitles/{id}.{_format} ###

_Gets a PersonTitle for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PersonTitle id


### `PATCH` /api/v1/persontitles/{id}.{_format} ###

_Update existing persontitle from the submitted data or create a new persontitle at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the person_title id


### `PUT` /api/v1/persontitles/{id}.{_format} ###

_Update existing PersonTitle from the submitted data or create a new PersonTitle at a specific location._

Update existing PersonTitle from the submitted data or create a new PersonTitle at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PersonTitle id
