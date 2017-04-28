# contacttype #

## /api/v1/contacttypes ##

### `GET` /api/v1/contacttypes.{_format} ###

_List all contact types._

List all contact types.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing contact types.

limit:

  * Requirement: \d+
  * Description: How many contact types to return.
  * Default: 5


### `POST` /api/v1/contacttypes.{_format} ###

_Creates a new Contact Type from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/contacttypes/new ##

### `GET` /api/v1/contacttypes/new.{_format} ###

_Presents the form to use to create a new contact type._

Presents the form to use to create a new contact type.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/contacttypes/{id} ##

### `DELETE` /api/v1/contacttypes/{id}.{_format} ###

_Delete Contact Type_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Contact Type ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/contacttypes/{id}.{_format} ###

_Gets a Contact Type for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the contact type id


### `PATCH` /api/v1/contacttypes/{id}.{_format} ###

_Update existing contact type from the submitted data or create a new contact type at a specific location._

Update existing contact type from the submitted data or create a new contact type at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the contact type id


### `PUT` /api/v1/contacttypes/{id}.{_format} ###

_Update existing contact type from the submitted data or create a new contact type at a specific location._

Update existing contact type from the submitted data or create a new contact type at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the contact type id
