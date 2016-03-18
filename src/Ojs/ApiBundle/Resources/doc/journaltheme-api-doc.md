# journaltheme #

## /api/v1/journal/{journalId}/themes ##

### `GET` /api/v1/journal/{journalId}/themes.{_format} ###

_List all Themes._

List all Themes.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Themes.

limit:

  * Requirement: \d+
  * Description: How many Themes to return.
  * Default: 5


### `POST` /api/v1/journal/{journalId}/themes.{_format} ###

_Creates a new Theme from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/themes/new ##

### `GET` /api/v1/journal/{journalId}/themes/new.{_format} ###

_Presents the form to use to create a new Theme._

Presents the form to use to create a new Theme.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/themes/{id} ##

### `DELETE` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Delete Theme_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Theme ID
**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


### `GET` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Gets a Theme for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Theme id


### `PATCH` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Update existing journaltheme from the submitted data or create a new journaltheme at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the journal_theme id


### `PUT` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Update existing Theme from the submitted data or create a new Theme at a specific location._

Update existing Theme from the submitted data or create a new Theme at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Theme id
