# journalsection #

## /api/v1/journal/{journalId}/sections ##

### `GET` /api/v1/journal/{journalId}/sections.{_format} ###

_List all Sections._

List all Sections.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Sections.

limit:

  * Requirement: \d+
  * Description: How many Sections to return.
  * Default: 5


### `POST` /api/v1/journal/{journalId}/sections.{_format} ###

_Creates a new Section from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/sections/new ##

### `GET` /api/v1/journal/{journalId}/sections/new.{_format} ###

_Presents the form to use to create a new Section._

Presents the form to use to create a new Section.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/sections/{id} ##

### `DELETE` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Delete Section_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Section ID
**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


### `GET` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Gets a Section for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Section id


### `PATCH` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Update existing journalsection from the submitted data or create a new journalsection at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the journal_section id


### `PUT` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Update existing Section from the submitted data or create a new Section at a specific location._

Update existing Section from the submitted data or create a new Section at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Section id
