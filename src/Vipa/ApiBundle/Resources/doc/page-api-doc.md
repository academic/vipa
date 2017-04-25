# page #

## /api/v1/pages ##

### `GET` /api/v1/pages.{_format} ###

_List all Pages._

List all Pages.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Pages.

limit:

  * Requirement: \d+
  * Description: How many Pages to return.
  * Default: 5


### `POST` /api/v1/pages.{_format} ###

_Creates a new Page from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/pages/new ##

### `GET` /api/v1/pages/new.{_format} ###

_Presents the form to use to create a new Page._

Presents the form to use to create a new Page.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/pages/{id} ##

### `DELETE` /api/v1/pages/{id}.{_format} ###

_Delete Page_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Page ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/pages/{id}.{_format} ###

_Gets a Page for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Page id


### `PATCH` /api/v1/pages/{id}.{_format} ###

_Update existing page from the submitted data or create a new page at a specific location._

Update existing page from the submitted data or create a new page at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the page id


### `PUT` /api/v1/pages/{id}.{_format} ###

_Update existing Page from the submitted data or create a new Page at a specific location._

Update existing Page from the submitted data or create a new Page at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Page id
