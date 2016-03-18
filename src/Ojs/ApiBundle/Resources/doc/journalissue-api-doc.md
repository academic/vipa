# journalissue #

## /api/v1/journal/{journalId}/issues ##

### `GET` /api/v1/journal/{journalId}/issues.{_format} ###

_List all Issues._

List all Issues.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Issues.

limit:

  * Requirement: \d+
  * Description: How many Issues to return.
  * Default: 5


### `POST` /api/v1/journal/{journalId}/issues.{_format} ###

_Creates a new Issue from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/issues/new ##

### `GET` /api/v1/journal/{journalId}/issues/new.{_format} ###

_Presents the form to use to create a new Issue._

Presents the form to use to create a new Issue.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/issues/{id} ##

### `DELETE` /api/v1/journal/{journalId}/issues/{id}.{_format} ###

_Delete Issue_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Issue ID
**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


### `GET` /api/v1/journal/{journalId}/issues/{id}.{_format} ###

_Gets a Issue for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Issue id


### `PATCH` /api/v1/journal/{journalId}/issues/{id}.{_format} ###

_Update existing journalissue from the submitted data or create a new journalissue at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the journal_issue id


### `PUT` /api/v1/journal/{journalId}/issues/{id}.{_format} ###

_Update existing Issue from the submitted data or create a new Issue at a specific location._

Update existing Issue from the submitted data or create a new Issue at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Issue id
