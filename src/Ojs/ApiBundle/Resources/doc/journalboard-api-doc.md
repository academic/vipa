# journalboard #

## /api/v1/journal/{journalId}/boards ##

### `GET` /api/v1/journal/{journalId}/boards.{_format} ###

_List all Boards._

List all Boards.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Boards.

limit:

  * Requirement: \d+
  * Description: How many Boards to return.
  * Default: 5


### `POST` /api/v1/journal/{journalId}/boards.{_format} ###

_Creates a new Board from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/boards/new ##

### `GET` /api/v1/journal/{journalId}/boards/new.{_format} ###

_Presents the form to use to create a new Board._

Presents the form to use to create a new Board.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/boards/{id} ##

### `DELETE` /api/v1/journal/{journalId}/boards/{id}.{_format} ###

_Delete Board_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Board ID
**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


### `GET` /api/v1/journal/{journalId}/boards/{id}.{_format} ###

_Gets a Board for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Board id


### `PATCH` /api/v1/journal/{journalId}/boards/{id}.{_format} ###

_Update existing journalboard from the submitted data or create a new journalboard at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the journal_board id


### `PUT` /api/v1/journal/{journalId}/boards/{id}.{_format} ###

_Update existing Board from the submitted data or create a new Board at a specific location._

Update existing Board from the submitted data or create a new Board at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Board id
