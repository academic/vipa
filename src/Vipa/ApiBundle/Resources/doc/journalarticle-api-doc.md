# journalarticle #

## /api/v1/journal/{journalId}/articles ##

### `GET` /api/v1/journal/{journalId}/articles.{_format} ###

_List all Articles._

List all Articles.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Articles.

limit:

  * Requirement: \d+
  * Description: How many Articles to return.
  * Default: 5


### `POST` /api/v1/journal/{journalId}/articles.{_format} ###

_Creates a new Article from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/articles/new ##

### `GET` /api/v1/journal/{journalId}/articles/new.{_format} ###

_Presents the form to use to create a new Article._

Presents the form to use to create a new Article.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/articles/{id} ##

### `DELETE` /api/v1/journal/{journalId}/articles/{id}.{_format} ###

_Delete Article_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Article ID
**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


### `GET` /api/v1/journal/{journalId}/articles/{id}.{_format} ###

_Gets a Article for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Article id


### `PATCH` /api/v1/journal/{journalId}/articles/{id}.{_format} ###

_Update existing journalarticle from the submitted data or create a new journalarticle at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the journal_article id


### `PUT` /api/v1/journal/{journalId}/articles/{id}.{_format} ###

_Update existing Article from the submitted data or create a new Article at a specific location._

Update existing Article from the submitted data or create a new Article at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Article id
