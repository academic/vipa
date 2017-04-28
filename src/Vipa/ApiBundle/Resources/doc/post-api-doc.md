# post #

## /api/v1/posts ##

### `GET` /api/v1/posts.{_format} ###

_List all Posts._

List all Posts.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Posts.

limit:

  * Requirement: \d+
  * Description: How many Posts to return.
  * Default: 5


### `POST` /api/v1/posts.{_format} ###

_Creates a new Post from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/posts/new ##

### `GET` /api/v1/posts/new.{_format} ###

_Presents the form to use to create a new Post._

Presents the form to use to create a new Post.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/posts/{id} ##

### `DELETE` /api/v1/posts/{id}.{_format} ###

_Delete Post_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Post ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/posts/{id}.{_format} ###

_Gets a Post for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Post id


### `PATCH` /api/v1/posts/{id}.{_format} ###

_Update existing post from the submitted data or create a new post at a specific location._

Update existing post from the submitted data or create a new post at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the post id


### `PUT` /api/v1/posts/{id}.{_format} ###

_Update existing Post from the submitted data or create a new Post at a specific location._

Update existing Post from the submitted data or create a new Post at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Post id
