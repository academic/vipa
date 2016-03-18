# articletype #

## /api/v1/articletypes ##

### `GET` /api/v1/articletypes.{_format} ###

_List all ArticleTypes._

List all ArticleTypes.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing ArticleTypes.

limit:

  * Requirement: \d+
  * Description: How many ArticleTypes to return.
  * Default: 5


### `POST` /api/v1/articletypes.{_format} ###

_Creates a new ArticleType from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/articletypes/new ##

### `GET` /api/v1/articletypes/new.{_format} ###

_Presents the form to use to create a new ArticleType._

Presents the form to use to create a new ArticleType.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/articletypes/{id} ##

### `DELETE` /api/v1/articletypes/{id}.{_format} ###

_Delete ArticleType_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: ArticleType ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/articletypes/{id}.{_format} ###

_Gets a ArticleType for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the ArticleType id


### `PATCH` /api/v1/articletypes/{id}.{_format} ###

_Update existing articletype from the submitted data or create a new articletype at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the article_type id


### `PUT` /api/v1/articletypes/{id}.{_format} ###

_Update existing ArticleType from the submitted data or create a new ArticleType at a specific location._

Update existing ArticleType from the submitted data or create a new ArticleType at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the ArticleType id
