# contact #

## /api/v1/contacts ##

### `GET` /api/v1/contacts.{_format} ###

_List all Contacts._

List all Contacts.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Contacts.

limit:

  * Requirement: \d+
  * Description: How many Contacts to return.
  * Default: 5


### `POST` /api/v1/contacts.{_format} ###

_Creates a new Contact from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/contacts/new ##

### `GET` /api/v1/contacts/new.{_format} ###

_Presents the form to use to create a new Contact._

Presents the form to use to create a new Contact.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/contacts/{id} ##

### `DELETE` /api/v1/contacts/{id}.{_format} ###

_Delete Contact_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Contact ID
**_format**

  - Requirement: xml|json|html


### `GET` /api/v1/contacts/{id}.{_format} ###

_Gets a Contact for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Contact id


### `PATCH` /api/v1/contacts/{id}.{_format} ###

_Update existing contact from the submitted data or create a new contact at a specific location._

Update existing contact from the submitted data or create a new contact at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the contact id


### `PUT` /api/v1/contacts/{id}.{_format} ###

_Update existing Contact from the submitted data or create a new Contact at a specific location._

Update existing Contact from the submitted data or create a new Contact at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Contact id
