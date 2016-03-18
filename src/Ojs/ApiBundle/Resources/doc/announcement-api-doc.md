# announcement #

## /api/v1/announcements ##

### `GET` /api/v1/announcements.{_format} ###

_List all Announcements._

List all Announcements.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Announcements.

limit:

  * Requirement: \d+
  * Description: How many Announcements to return.
  * Default: 5


### `POST` /api/v1/announcements.{_format} ###

_Creates a new Announcement from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/announcements/new ##

### `GET` /api/v1/announcements/new.{_format} ###

_Presents the form to use to create a new Announcement._

Presents the form to use to create a new Announcement.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/announcements/{id} ##

### `DELETE` /api/v1/announcements/{id}.{_format} ###

_Delete Announcement_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Announcement ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/announcements/{id}.{_format} ###

_Gets a Announcement for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Announcement id


### `PATCH` /api/v1/announcements/{id}.{_format} ###

_Update existing announcement from the submitted data or create a new announcement at a specific location._

Update existing announcement from the submitted data or create a new announcement at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the announcement id


### `PUT` /api/v1/announcements/{id}.{_format} ###

_Update existing Announcement from the submitted data or create a new Announcement at a specific location._

Update existing Announcement from the submitted data or create a new Announcement at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Announcement id
