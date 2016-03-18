# subject #

## /api/v1/subjects ##

### `GET` /api/v1/subjects.{_format} ###

_List all Subjects._

List all Subjects.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Subjects.

limit:

  * Requirement: \d+
  * Description: How many Subjects to return.
  * Default: 5


### `POST` /api/v1/subjects.{_format} ###

_Creates a new Subject from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/subjects/new ##

### `GET` /api/v1/subjects/new.{_format} ###

_Presents the form to use to create a new Subject._

Presents the form to use to create a new Subject.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/subjects/{id} ##

### `DELETE` /api/v1/subjects/{id}.{_format} ###

_Delete Subject_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Subject ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/subjects/{id}.{_format} ###

_Gets a Subject for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Subject id


### `PATCH` /api/v1/subjects/{id}.{_format} ###

_Update existing subject from the submitted data or create a new subject at a specific location._

Update existing subject from the submitted data or create a new subject at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the subject id


### `PUT` /api/v1/subjects/{id}.{_format} ###

_Update existing Subject from the submitted data or create a new Subject at a specific location._

Update existing Subject from the submitted data or create a new Subject at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Subject id
