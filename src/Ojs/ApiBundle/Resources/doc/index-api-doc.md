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


### `DELETE` /api/v1/announcements/{id}.{_format} ###

_Delete Announcement_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Announcement ID
**_format**

  - Requirement: xml|json|html


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

### `PATCH` /api/v1/articletypes/{id}.{_format} ###

_Update existing articletype from the submitted data or create a new articletype at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the article_type id


### `GET` /api/v1/articletypes/{id}.{_format} ###

_Gets a ArticleType for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the ArticleType id


### `PUT` /api/v1/articletypes/{id}.{_format} ###

_Update existing ArticleType from the submitted data or create a new ArticleType at a specific location._

Update existing ArticleType from the submitted data or create a new ArticleType at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the ArticleType id


### `DELETE` /api/v1/articletypes/{id}.{_format} ###

_Delete ArticleType_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: ArticleType ID
**_format**

  - Requirement: xml|json|html


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


### `DELETE` /api/v1/contacts/{id}.{_format} ###

_Delete Contact_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Contact ID
**_format**

  - Requirement: xml|json|html


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

### `PATCH` /api/v1/contacttypes/{id}.{_format} ###

_Update existing contact type from the submitted data or create a new contact type at a specific location._

Update existing contact type from the submitted data or create a new contact type at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the contact type id


### `GET` /api/v1/contacttypes/{id}.{_format} ###

_Gets a Contact Type for a given id_

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


### `DELETE` /api/v1/contacttypes/{id}.{_format} ###

_Delete Contact Type_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Contact Type ID
**_format**

  - Requirement: xml|json|html


## /api/v1/indexes ##

### `GET` /api/v1/indexes.{_format} ###

_List all Indexs._

List all Indexs.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Indexs.

limit:

  * Requirement: \d+
  * Description: How many Indexs to return.
  * Default: 5


### `POST` /api/v1/indexes.{_format} ###

_Creates a new Index from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/indexes/new ##

### `GET` /api/v1/indexes/new.{_format} ###

_Presents the form to use to create a new Index._

Presents the form to use to create a new Index.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/indexes/{id} ##

### `PATCH` /api/v1/indexes/{id}.{_format} ###

_Update existing index from the submitted data or create a new index at a specific location._

Update existing index from the submitted data or create a new index at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the index id


### `PUT` /api/v1/indexes/{id}.{_format} ###

_Update existing Index from the submitted data or create a new Index at a specific location._

Update existing Index from the submitted data or create a new Index at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Index id


### `DELETE` /api/v1/indexes/{id}.{_format} ###

_Delete Index_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Index ID
**_format**

  - Requirement: xml|json|html


## /api/v1/indexs/{id} ##

### `GET` /api/v1/indexs/{id}.{_format} ###

_Gets a Index for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Index id


## /api/v1/institutions ##

### `GET` /api/v1/institutions.{_format} ###

_List all Institutions._

List all Institutions.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Institutions.

limit:

  * Requirement: \d+
  * Description: How many Institutions to return.
  * Default: 5


### `POST` /api/v1/institutions.{_format} ###

_Creates a new Institution from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/institutions/new ##

### `GET` /api/v1/institutions/new.{_format} ###

_Presents the form to use to create a new Institution._

Presents the form to use to create a new Institution.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/institutions/{id} ##

### `GET` /api/v1/institutions/{id}.{_format} ###

_Gets a Institution for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Institution id


### `PATCH` /api/v1/institutions/{id}.{_format} ###

_Update existing institution from the submitted data or create a new institution at a specific location._

Update existing institution from the submitted data or create a new institution at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the institution id


### `PUT` /api/v1/institutions/{id}.{_format} ###

_Update existing Institution from the submitted data or create a new Institution at a specific location._

Update existing Institution from the submitted data or create a new Institution at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Institution id


### `DELETE` /api/v1/institutions/{id}.{_format} ###

_Delete Institution_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Institution ID
**_format**

  - Requirement: xml|json|html


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


## /api/v1/journal/{journalId}/sections ##

### `GET` /api/v1/journal/{journalId}/sections.{_format} ###

_List all Sections._

List all Sections.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Sections.

limit:

  * Requirement: \d+
  * Description: How many Sections to return.
  * Default: 5


### `POST` /api/v1/journal/{journalId}/sections.{_format} ###

_Creates a new Section from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/sections/new ##

### `GET` /api/v1/journal/{journalId}/sections/new.{_format} ###

_Presents the form to use to create a new Section._

Presents the form to use to create a new Section.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/sections/{id} ##

### `PATCH` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Update existing journalsection from the submitted data or create a new journalsection at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the journal_section id


### `GET` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Gets a Section for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Section id


### `PUT` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Update existing Section from the submitted data or create a new Section at a specific location._

Update existing Section from the submitted data or create a new Section at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Section id


### `DELETE` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Delete Section_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Section ID
**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/themes ##

### `GET` /api/v1/journal/{journalId}/themes.{_format} ###

_List all Themes._

List all Themes.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Themes.

limit:

  * Requirement: \d+
  * Description: How many Themes to return.
  * Default: 5


### `POST` /api/v1/journal/{journalId}/themes.{_format} ###

_Creates a new Theme from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/themes/new ##

### `GET` /api/v1/journal/{journalId}/themes/new.{_format} ###

_Presents the form to use to create a new Theme._

Presents the form to use to create a new Theme.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/themes/{id} ##

### `PATCH` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Update existing journaltheme from the submitted data or create a new journaltheme at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the journal_theme id


### `GET` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Gets a Theme for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Theme id


### `PUT` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Update existing Theme from the submitted data or create a new Theme at a specific location._

Update existing Theme from the submitted data or create a new Theme at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Theme id


### `DELETE` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Delete Theme_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Theme ID
**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journals ##

### `GET` /api/v1/journals.{_format} ###

_List all Journals._

List all Journals.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Journals.

limit:

  * Requirement: \d+
  * Description: How many Journals to return.
  * Default: 5


### `POST` /api/v1/journals.{_format} ###

_Creates a new Journal from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/journals/new ##

### `GET` /api/v1/journals/new.{_format} ###

_Presents the form to use to create a new Journal._

Presents the form to use to create a new Journal.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/journals/{id} ##

### `PATCH` /api/v1/journals/{id}.{_format} ###

_Update existing journal from the submitted data or create a new journal at a specific location._

Update existing journal from the submitted data or create a new journal at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the journal id


### `GET` /api/v1/journals/{id}.{_format} ###

_Gets a Journal for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Journal id

#### Response ####

translations:

  * type: string

mandatoryLang:

  * type: string

languages[]:

  * type: array of objects (Lang)

languages[][id]:

  * type: integer

languages[][code]:

  * type: string

languages[][name]:

  * type: string

languages[][rtl]:

  * type: boolean

issn:

  * type: string

eissn:

  * type: string

founded:

  * type: DateTime

id:

  * type: integer

title_transliterated:

  * type: string

path:

  * type: string

domain:

  * type: string

url:

  * type: string

address:

  * type: string

phone:

  * type: string

email:

  * type: string

country:

  * type: object (Country)

country[id]:

  * type: integer

country[name]:

  * type: string

published:

  * type: boolean

status:

  * type: integer

image:

  * type: string

header:

  * type: string

google_analytics_id:

  * type: string

slug:

  * type: string

theme:

  * type: object (JournalTheme)

theme[id]:

  * type: integer

theme[title]:

  * type: string

theme[css]:

  * type: string

theme[public]:

  * type: boolean

theme[journal]:

  * type: object (Journal)

theme[journal][id]:

  * type: integer

theme[journal][title_transliterated]:

  * type: string

theme[journal][path]:

  * type: string

theme[journal][domain]:

  * type: string

theme[journal][issn]:

  * type: string

theme[journal][eissn]:

  * type: string

theme[journal][founded]:

  * type: DateTime

theme[journal][url]:

  * type: string

theme[journal][address]:

  * type: string

theme[journal][phone]:

  * type: string

theme[journal][email]:

  * type: string

theme[journal][country]:

  * type: object (Country)

theme[journal][published]:

  * type: boolean

theme[journal][status]:

  * type: integer

theme[journal][image]:

  * type: string

theme[journal][header]:

  * type: string

theme[journal][google_analytics_id]:

  * type: string

theme[journal][slug]:

  * type: string

theme[journal][theme]:

  * type: object (JournalTheme)

theme[journal][design]:

  * type: object (Design)

theme[journal][design][id]:

  * type: integer

theme[journal][design][title]:

  * type: string

theme[journal][design][content]:

  * type: string

theme[journal][design][editable_content]:

  * type: string

theme[journal][design][public]:

  * type: boolean

theme[journal][design][owner]:

  * type: object (Journal)

theme[journal][design][created_by]:

  * type: string
  * description: @var string

theme[journal][design][updated_by]:

  * type: string
  * description: @var string

theme[journal][design][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[journal][design][created]:

  * type: DateTime
  * description: @var \DateTime $created

theme[journal][design][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

theme[journal][configured]:

  * type: boolean

theme[journal][articles][]:

  * type: array of objects (Article)

theme[journal][articles][][id]:

  * type: integer
  * description: auto-incremented article unique id

theme[journal][articles][][abstract_transliterated]:

  * type: string
  * description: (optional) English transliterated abstract

theme[journal][articles][][status]:

  * type: integer

theme[journal][articles][][doi]:

  * type: string
  * description: (optional)

theme[journal][articles][][title_transliterated]:

  * type: string
  * description: Roman transliterated title

theme[journal][articles][][anonymous]:

  * type: boolean
  * description: Some articles carries no authorship

theme[journal][articles][][submission_date]:

  * type: DateTime

theme[journal][articles][][pubdate]:

  * type: DateTime

theme[journal][articles][][pubdate_season]:

  * type: string

theme[journal][articles][][first_page]:

  * type: integer

theme[journal][articles][][last_page]:

  * type: integer

theme[journal][articles][][uri]:

  * type: string

theme[journal][articles][][primary_language]:

  * type: string

theme[journal][articles][][order_num]:

  * type: integer

theme[journal][articles][][subjects][]:

  * type: array of objects (Subject)

theme[journal][articles][][subjects][][id]:

  * type: integer

theme[journal][articles][][subjects][][parent]:

  * type: object (Subject)

theme[journal][articles][][subjects][][translations]:

  * type: string

theme[journal][articles][][languages][]:

  * type: array of objects (Lang)

theme[journal][articles][][languages][][id]:

  * type: integer

theme[journal][articles][][languages][][code]:

  * type: string

theme[journal][articles][][languages][][name]:

  * type: string

theme[journal][articles][][languages][][rtl]:

  * type: boolean

theme[journal][articles][][article_type]:

  * type: object (ArticleTypes)

theme[journal][articles][][article_type][id]:

  * type: integer

theme[journal][articles][][article_type][translations][]:

  * type: array of objects (ArticleTypesTranslation)

theme[journal][articles][][article_type][translations][][translatable]:

  * type: object (ArticleTypes)

theme[journal][articles][][article_type][translations][][name]:

  * type: string

theme[journal][articles][][article_type][translations][][description]:

  * type: string

theme[journal][articles][][citations][]:

  * type: array of objects (Citation)

theme[journal][articles][][citations][][id]:

  * type: integer

theme[journal][articles][][citations][][raw]:

  * type: string

theme[journal][articles][][citations][][type]:

  * type: string

theme[journal][articles][][article_authors][]:

  * type: array of objects (ArticleAuthor)

theme[journal][articles][][article_authors][][id]:

  * type: integer

theme[journal][articles][][article_authors][][author_order]:

  * type: integer

theme[journal][articles][][article_authors][][author]:

  * type: object (Author)

theme[journal][articles][][article_authors][][author][id]:

  * type: integer

theme[journal][articles][][article_authors][][author][first_name]:

  * type: string

theme[journal][articles][][article_authors][][author][middle_name]:

  * type: string

theme[journal][articles][][article_authors][][author][last_name]:

  * type: string

theme[journal][articles][][article_authors][][author][email]:

  * type: string

theme[journal][articles][][article_authors][][author][first_name_transliterated]:

  * type: string

theme[journal][articles][][article_authors][][author][middle_name_transliterated]:

  * type: string

theme[journal][articles][][article_authors][][author][last_name_transliterated]:

  * type: string

theme[journal][articles][][article_authors][][author][initials]:

  * type: string

theme[journal][articles][][article_authors][][author][address]:

  * type: string

theme[journal][articles][][article_authors][][author][institution]:

  * type: object (Institution)

theme[journal][articles][][article_authors][][author][institution][id]:

  * type: integer

theme[journal][articles][][article_authors][][author][institution][name]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][address]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][city]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][country]:

  * type: object (Country)

theme[journal][articles][][article_authors][][author][institution][address_lat]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][address_long]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][phone]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][fax]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][email]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][url]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][wiki]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][logo]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][header]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][domain]:

  * type: string

theme[journal][articles][][article_authors][][author][author_details]:

  * type: string

theme[journal][articles][][article_authors][][author][user]:

  * type: object (User)

theme[journal][articles][][article_authors][][author][user][username]:

  * type: string

theme[journal][articles][][article_authors][][author][user][text]:

  * type: string

theme[journal][articles][][article_authors][][author][user][first_name]:

  * type: string

theme[journal][articles][][article_authors][][author][user][last_name]:

  * type: string

theme[journal][articles][][article_authors][][author][user][email]:

  * type: string

theme[journal][articles][][article_authors][][author][user][settings]:

  * type: string
  * description: Json encoded settings string

theme[journal][articles][][article_authors][][author][user][about]:

  * type: string

theme[journal][articles][][article_authors][][author][user][country]:

  * type: object (Country)

theme[journal][articles][][article_authors][][author][user][journal_users][]:

  * type: array of objects (JournalUser)

theme[journal][articles][][article_authors][][author][user][journal_users][][id]:

  * type: integer

theme[journal][articles][][article_authors][][author][user][journal_users][][journal]:

  * type: object (Journal)

theme[journal][articles][][article_authors][][author][user][journal_users][][user]:

  * type: object (User)

theme[journal][articles][][article_authors][][author][user][journal_users][][roles][]:

  * type: array of objects (Role)

theme[journal][articles][][article_authors][][author][user][journal_users][][roles][][id]:

  * type: integer

theme[journal][articles][][article_authors][][author][user][journal_users][][roles][][name]:

  * type: string

theme[journal][articles][][article_authors][][author][user][journal_users][][roles][][role]:

  * type: string

theme[journal][articles][][article_authors][][author][orcid]:

  * type: string

theme[journal][articles][][article_authors][][author][institution_not_listed]:

  * type: boolean

theme[journal][articles][][article_authors][][author][institution_name]:

  * type: string

theme[journal][articles][][article_authors][][author][article_authors][]:

  * type: array of objects (ArticleAuthor)

theme[journal][articles][][article_authors][][authorOrder]:

  * type: string

theme[journal][articles][][article_files][]:

  * type: array of objects (ArticleFile)

theme[journal][articles][][article_files][][id]:

  * type: integer

theme[journal][articles][][article_files][][type]:

  * type: integer

theme[journal][articles][][article_files][][file]:

  * type: string

theme[journal][articles][][article_files][][version]:

  * type: integer

theme[journal][articles][][article_files][][article]:

  * type: object (Article)

theme[journal][articles][][article_files][][keywords]:

  * type: string

theme[journal][articles][][article_files][][description]:

  * type: string

theme[journal][articles][][article_files][][title]:

  * type: string

theme[journal][articles][][article_files][][lang_code]:

  * type: string

theme[journal][articles][][article_files][][created_by]:

  * type: string
  * description: @var string

theme[journal][articles][][article_files][][updated_by]:

  * type: string
  * description: @var string

theme[journal][articles][][article_files][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[journal][articles][][article_files][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

theme[journal][articles][][article_files][][langCode]:

  * type: string

theme[journal][articles][][article_submission_files][]:

  * type: array of objects (ArticleSubmissionFile)

theme[journal][articles][][article_submission_files][][id]:

  * type: integer

theme[journal][articles][][article_submission_files][][title]:

  * type: string

theme[journal][articles][][article_submission_files][][detail]:

  * type: string

theme[journal][articles][][article_submission_files][][visible]:

  * type: boolean

theme[journal][articles][][article_submission_files][][required]:

  * type: boolean

theme[journal][articles][][article_submission_files][][deleted_at]:

  * type: DateTime

theme[journal][articles][][article_submission_files][][article]:

  * type: object (Article)

theme[journal][articles][][article_submission_files][][locale]:

  * type: string

theme[journal][articles][][article_submission_files][][file]:

  * type: string

theme[journal][articles][][view_count]:

  * type: integer

theme[journal][articles][][download_count]:

  * type: integer

theme[journal][articles][][translations]:

  * type: string

theme[journal][articles][][articleFiles]:

  * type: string

theme[journal][articles][][articleAuthors]:

  * type: string

theme[journal][articles][][submissionDate]:

  * type: string

theme[journal][issues][]:

  * type: array of objects (Issue)

theme[journal][issues][][id]:

  * type: integer

theme[journal][issues][][journal]:

  * type: object (Journal)

theme[journal][issues][][volume]:

  * type: string

theme[journal][issues][][number]:

  * type: string

theme[journal][issues][][cover]:

  * type: string

theme[journal][issues][][special]:

  * type: boolean

theme[journal][issues][][year]:

  * type: string

theme[journal][issues][][date_published]:

  * type: DateTime

theme[journal][issues][][articles][]:

  * type: array of objects (Article)

theme[journal][issues][][header]:

  * type: string

theme[journal][issues][][supplement]:

  * type: boolean

theme[journal][issues][][full_file]:

  * type: string

theme[journal][issues][][issue_files][]:

  * type: array of objects (IssueFile)

theme[journal][issues][][issue_files][][translations]:

  * type: string

theme[journal][issues][][issue_files][][file]:

  * type: string

theme[journal][issues][][issue_files][][type]:

  * type: string

theme[journal][issues][][issue_files][][langCode]:

  * type: string

theme[journal][issues][][view_count]:

  * type: integer

theme[journal][issues][][download_count]:

  * type: integer

theme[journal][issues][][translations]:

  * type: string

theme[journal][languages][]:

  * type: array of objects (Lang)

theme[journal][languages][][id]:

  * type: integer

theme[journal][languages][][code]:

  * type: string

theme[journal][languages][][name]:

  * type: string

theme[journal][languages][][rtl]:

  * type: boolean

theme[journal][periods][]:

  * type: array of objects (Period)

theme[journal][periods][][id]:

  * type: integer

theme[journal][periods][][translations][]:

  * type: array of objects (PeriodTranslation)

theme[journal][periods][][translations][][translatable]:

  * type: object (Period)

theme[journal][periods][][translations][][period]:

  * type: string

theme[journal][periods][][created_by]:

  * type: string
  * description: @var string

theme[journal][periods][][updated_by]:

  * type: string
  * description: @var string

theme[journal][periods][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[journal][periods][][created]:

  * type: DateTime
  * description: @var \DateTime $created

theme[journal][periods][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

theme[journal][subjects][]:

  * type: array of objects (Subject)

theme[journal][subjects][][id]:

  * type: integer

theme[journal][subjects][][parent]:

  * type: object (Subject)

theme[journal][subjects][][translations]:

  * type: string

theme[journal][publisher]:

  * type: object (Publisher)

theme[journal][publisher][id]:

  * type: integer

theme[journal][publisher][lft]:

  * type: integer

theme[journal][publisher][name]:

  * type: string

theme[journal][publisher][address]:

  * type: string

theme[journal][publisher][city]:

  * type: string

theme[journal][publisher][country]:

  * type: object (Country)

theme[journal][publisher][address_lat]:

  * type: string

theme[journal][publisher][address_long]:

  * type: string

theme[journal][publisher][phone]:

  * type: string

theme[journal][publisher][fax]:

  * type: string

theme[journal][publisher][email]:

  * type: string

theme[journal][publisher][url]:

  * type: string

theme[journal][publisher][wiki]:

  * type: string

theme[journal][publisher][logo]:

  * type: string

theme[journal][publisher][header]:

  * type: string

theme[journal][publisher][domain]:

  * type: string

theme[journal][publisher][publisher_themes][]:

  * type: array of objects (PublisherTheme)

theme[journal][publisher][publisher_themes][][id]:

  * type: integer

theme[journal][publisher][publisher_themes][][title]:

  * type: string

theme[journal][publisher][publisher_themes][][css]:

  * type: string

theme[journal][publisher][publisher_themes][][public]:

  * type: boolean

theme[journal][publisher][publisher_themes][][publisher]:

  * type: object (Publisher)

theme[journal][publisher][publisher_themes][][created_by]:

  * type: string
  * description: @var string

theme[journal][publisher][publisher_themes][][updated_by]:

  * type: string
  * description: @var string

theme[journal][publisher][publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[journal][publisher][publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

theme[journal][publisher][publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

theme[journal][publisher][publisher_designs][]:

  * type: array of objects (PublisherDesign)

theme[journal][publisher][publisher_designs][][id]:

  * type: integer

theme[journal][publisher][publisher_designs][][title]:

  * type: string

theme[journal][publisher][publisher_designs][][content]:

  * type: string

theme[journal][publisher][publisher_designs][][editable_content]:

  * type: string

theme[journal][publisher][publisher_designs][][public]:

  * type: boolean

theme[journal][publisher][publisher_designs][][publisher]:

  * type: object (Publisher)

theme[journal][publisher][publisher_designs][][created_by]:

  * type: string
  * description: @var string

theme[journal][publisher][publisher_designs][][updated_by]:

  * type: string
  * description: @var string

theme[journal][publisher][publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[journal][publisher][publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

theme[journal][publisher][publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

theme[journal][logo]:

  * type: string

theme[journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

theme[journal][journal_indexs][][id]:

  * type: integer

theme[journal][journal_indexs][][link]:

  * type: string

theme[journal][journal_indexs][][journal]:

  * type: object (Journal)

theme[journal][journal_indexs][][index]:

  * type: object (Index)

theme[journal][journal_indexs][][index][id]:

  * type: integer

theme[journal][journal_indexs][][index][name]:

  * type: string

theme[journal][journal_indexs][][index][logo]:

  * type: string

theme[journal][journal_indexs][][index][status]:

  * type: boolean

theme[journal][journal_indexs][][verified]:

  * type: boolean

theme[journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

theme[journal][submission_checklist][][id]:

  * type: integer

theme[journal][submission_checklist][][label]:

  * type: string

theme[journal][submission_checklist][][detail]:

  * type: string

theme[journal][submission_checklist][][visible]:

  * type: boolean

theme[journal][submission_checklist][][deleted_at]:

  * type: DateTime

theme[journal][submission_checklist][][journal]:

  * type: object (Journal)

theme[journal][submission_checklist][][locale]:

  * type: string

theme[journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

theme[journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

theme[journal][journal_submission_files][][id]:

  * type: integer

theme[journal][journal_submission_files][][title]:

  * type: string

theme[journal][journal_submission_files][][detail]:

  * type: string

theme[journal][journal_submission_files][][visible]:

  * type: boolean

theme[journal][journal_submission_files][][required]:

  * type: boolean

theme[journal][journal_submission_files][][deleted_at]:

  * type: DateTime

theme[journal][journal_submission_files][][locale]:

  * type: string

theme[journal][journal_submission_files][][file]:

  * type: string

theme[journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

theme[journal][journal_application_upload_files][][id]:

  * type: integer

theme[journal][journal_application_upload_files][][title]:

  * type: string

theme[journal][journal_application_upload_files][][detail]:

  * type: string

theme[journal][journal_application_upload_files][][visible]:

  * type: boolean

theme[journal][journal_application_upload_files][][required]:

  * type: boolean

theme[journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

theme[journal][journal_application_upload_files][][locale]:

  * type: string

theme[journal][journal_application_upload_files][][file]:

  * type: string

theme[journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

theme[journal][printed]:

  * type: boolean

theme[journal][mandatory_lang]:

  * type: object (Lang)

theme[journal][view_count]:

  * type: integer

theme[journal][download_count]:

  * type: integer

theme[journal][translations]:

  * type: string

theme[journal][mandatoryLang]:

  * type: string

theme[created_by]:

  * type: string
  * description: @var string

theme[updated_by]:

  * type: string
  * description: @var string

theme[deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[created]:

  * type: DateTime
  * description: @var \DateTime $created

theme[updated]:

  * type: DateTime
  * description: @var \DateTime $updated

design:

  * type: object (Design)

design[id]:

  * type: integer

design[title]:

  * type: string

design[content]:

  * type: string

design[editable_content]:

  * type: string

design[public]:

  * type: boolean

design[owner]:

  * type: object (Journal)

design[owner][id]:

  * type: integer

design[owner][title_transliterated]:

  * type: string

design[owner][path]:

  * type: string

design[owner][domain]:

  * type: string

design[owner][issn]:

  * type: string

design[owner][eissn]:

  * type: string

design[owner][founded]:

  * type: DateTime

design[owner][url]:

  * type: string

design[owner][address]:

  * type: string

design[owner][phone]:

  * type: string

design[owner][email]:

  * type: string

design[owner][country]:

  * type: object (Country)

design[owner][published]:

  * type: boolean

design[owner][status]:

  * type: integer

design[owner][image]:

  * type: string

design[owner][header]:

  * type: string

design[owner][google_analytics_id]:

  * type: string

design[owner][slug]:

  * type: string

design[owner][theme]:

  * type: object (JournalTheme)

design[owner][design]:

  * type: object (Design)

design[owner][configured]:

  * type: boolean

design[owner][articles][]:

  * type: array of objects (Article)

design[owner][articles][][id]:

  * type: integer
  * description: auto-incremented article unique id

design[owner][articles][][abstract_transliterated]:

  * type: string
  * description: (optional) English transliterated abstract

design[owner][articles][][status]:

  * type: integer

design[owner][articles][][doi]:

  * type: string
  * description: (optional)

design[owner][articles][][title_transliterated]:

  * type: string
  * description: Roman transliterated title

design[owner][articles][][anonymous]:

  * type: boolean
  * description: Some articles carries no authorship

design[owner][articles][][submission_date]:

  * type: DateTime

design[owner][articles][][pubdate]:

  * type: DateTime

design[owner][articles][][pubdate_season]:

  * type: string

design[owner][articles][][first_page]:

  * type: integer

design[owner][articles][][last_page]:

  * type: integer

design[owner][articles][][uri]:

  * type: string

design[owner][articles][][primary_language]:

  * type: string

design[owner][articles][][order_num]:

  * type: integer

design[owner][articles][][subjects][]:

  * type: array of objects (Subject)

design[owner][articles][][subjects][][id]:

  * type: integer

design[owner][articles][][subjects][][parent]:

  * type: object (Subject)

design[owner][articles][][subjects][][translations]:

  * type: string

design[owner][articles][][languages][]:

  * type: array of objects (Lang)

design[owner][articles][][languages][][id]:

  * type: integer

design[owner][articles][][languages][][code]:

  * type: string

design[owner][articles][][languages][][name]:

  * type: string

design[owner][articles][][languages][][rtl]:

  * type: boolean

design[owner][articles][][article_type]:

  * type: object (ArticleTypes)

design[owner][articles][][article_type][id]:

  * type: integer

design[owner][articles][][article_type][translations][]:

  * type: array of objects (ArticleTypesTranslation)

design[owner][articles][][article_type][translations][][translatable]:

  * type: object (ArticleTypes)

design[owner][articles][][article_type][translations][][name]:

  * type: string

design[owner][articles][][article_type][translations][][description]:

  * type: string

design[owner][articles][][citations][]:

  * type: array of objects (Citation)

design[owner][articles][][citations][][id]:

  * type: integer

design[owner][articles][][citations][][raw]:

  * type: string

design[owner][articles][][citations][][type]:

  * type: string

design[owner][articles][][article_authors][]:

  * type: array of objects (ArticleAuthor)

design[owner][articles][][article_authors][][id]:

  * type: integer

design[owner][articles][][article_authors][][author_order]:

  * type: integer

design[owner][articles][][article_authors][][author]:

  * type: object (Author)

design[owner][articles][][article_authors][][author][id]:

  * type: integer

design[owner][articles][][article_authors][][author][first_name]:

  * type: string

design[owner][articles][][article_authors][][author][middle_name]:

  * type: string

design[owner][articles][][article_authors][][author][last_name]:

  * type: string

design[owner][articles][][article_authors][][author][email]:

  * type: string

design[owner][articles][][article_authors][][author][first_name_transliterated]:

  * type: string

design[owner][articles][][article_authors][][author][middle_name_transliterated]:

  * type: string

design[owner][articles][][article_authors][][author][last_name_transliterated]:

  * type: string

design[owner][articles][][article_authors][][author][initials]:

  * type: string

design[owner][articles][][article_authors][][author][address]:

  * type: string

design[owner][articles][][article_authors][][author][institution]:

  * type: object (Institution)

design[owner][articles][][article_authors][][author][institution][id]:

  * type: integer

design[owner][articles][][article_authors][][author][institution][name]:

  * type: string

design[owner][articles][][article_authors][][author][institution][address]:

  * type: string

design[owner][articles][][article_authors][][author][institution][city]:

  * type: string

design[owner][articles][][article_authors][][author][institution][country]:

  * type: object (Country)

design[owner][articles][][article_authors][][author][institution][address_lat]:

  * type: string

design[owner][articles][][article_authors][][author][institution][address_long]:

  * type: string

design[owner][articles][][article_authors][][author][institution][phone]:

  * type: string

design[owner][articles][][article_authors][][author][institution][fax]:

  * type: string

design[owner][articles][][article_authors][][author][institution][email]:

  * type: string

design[owner][articles][][article_authors][][author][institution][url]:

  * type: string

design[owner][articles][][article_authors][][author][institution][wiki]:

  * type: string

design[owner][articles][][article_authors][][author][institution][logo]:

  * type: string

design[owner][articles][][article_authors][][author][institution][header]:

  * type: string

design[owner][articles][][article_authors][][author][institution][domain]:

  * type: string

design[owner][articles][][article_authors][][author][author_details]:

  * type: string

design[owner][articles][][article_authors][][author][user]:

  * type: object (User)

design[owner][articles][][article_authors][][author][user][username]:

  * type: string

design[owner][articles][][article_authors][][author][user][text]:

  * type: string

design[owner][articles][][article_authors][][author][user][first_name]:

  * type: string

design[owner][articles][][article_authors][][author][user][last_name]:

  * type: string

design[owner][articles][][article_authors][][author][user][email]:

  * type: string

design[owner][articles][][article_authors][][author][user][settings]:

  * type: string
  * description: Json encoded settings string

design[owner][articles][][article_authors][][author][user][about]:

  * type: string

design[owner][articles][][article_authors][][author][user][country]:

  * type: object (Country)

design[owner][articles][][article_authors][][author][user][journal_users][]:

  * type: array of objects (JournalUser)

design[owner][articles][][article_authors][][author][user][journal_users][][id]:

  * type: integer

design[owner][articles][][article_authors][][author][user][journal_users][][journal]:

  * type: object (Journal)

design[owner][articles][][article_authors][][author][user][journal_users][][user]:

  * type: object (User)

design[owner][articles][][article_authors][][author][user][journal_users][][roles][]:

  * type: array of objects (Role)

design[owner][articles][][article_authors][][author][user][journal_users][][roles][][id]:

  * type: integer

design[owner][articles][][article_authors][][author][user][journal_users][][roles][][name]:

  * type: string

design[owner][articles][][article_authors][][author][user][journal_users][][roles][][role]:

  * type: string

design[owner][articles][][article_authors][][author][orcid]:

  * type: string

design[owner][articles][][article_authors][][author][institution_not_listed]:

  * type: boolean

design[owner][articles][][article_authors][][author][institution_name]:

  * type: string

design[owner][articles][][article_authors][][author][article_authors][]:

  * type: array of objects (ArticleAuthor)

design[owner][articles][][article_authors][][authorOrder]:

  * type: string

design[owner][articles][][article_files][]:

  * type: array of objects (ArticleFile)

design[owner][articles][][article_files][][id]:

  * type: integer

design[owner][articles][][article_files][][type]:

  * type: integer

design[owner][articles][][article_files][][file]:

  * type: string

design[owner][articles][][article_files][][version]:

  * type: integer

design[owner][articles][][article_files][][article]:

  * type: object (Article)

design[owner][articles][][article_files][][keywords]:

  * type: string

design[owner][articles][][article_files][][description]:

  * type: string

design[owner][articles][][article_files][][title]:

  * type: string

design[owner][articles][][article_files][][lang_code]:

  * type: string

design[owner][articles][][article_files][][created_by]:

  * type: string
  * description: @var string

design[owner][articles][][article_files][][updated_by]:

  * type: string
  * description: @var string

design[owner][articles][][article_files][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

design[owner][articles][][article_files][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

design[owner][articles][][article_files][][langCode]:

  * type: string

design[owner][articles][][article_submission_files][]:

  * type: array of objects (ArticleSubmissionFile)

design[owner][articles][][article_submission_files][][id]:

  * type: integer

design[owner][articles][][article_submission_files][][title]:

  * type: string

design[owner][articles][][article_submission_files][][detail]:

  * type: string

design[owner][articles][][article_submission_files][][visible]:

  * type: boolean

design[owner][articles][][article_submission_files][][required]:

  * type: boolean

design[owner][articles][][article_submission_files][][deleted_at]:

  * type: DateTime

design[owner][articles][][article_submission_files][][article]:

  * type: object (Article)

design[owner][articles][][article_submission_files][][locale]:

  * type: string

design[owner][articles][][article_submission_files][][file]:

  * type: string

design[owner][articles][][view_count]:

  * type: integer

design[owner][articles][][download_count]:

  * type: integer

design[owner][articles][][translations]:

  * type: string

design[owner][articles][][articleFiles]:

  * type: string

design[owner][articles][][articleAuthors]:

  * type: string

design[owner][articles][][submissionDate]:

  * type: string

design[owner][issues][]:

  * type: array of objects (Issue)

design[owner][issues][][id]:

  * type: integer

design[owner][issues][][journal]:

  * type: object (Journal)

design[owner][issues][][volume]:

  * type: string

design[owner][issues][][number]:

  * type: string

design[owner][issues][][cover]:

  * type: string

design[owner][issues][][special]:

  * type: boolean

design[owner][issues][][year]:

  * type: string

design[owner][issues][][date_published]:

  * type: DateTime

design[owner][issues][][articles][]:

  * type: array of objects (Article)

design[owner][issues][][header]:

  * type: string

design[owner][issues][][supplement]:

  * type: boolean

design[owner][issues][][full_file]:

  * type: string

design[owner][issues][][issue_files][]:

  * type: array of objects (IssueFile)

design[owner][issues][][issue_files][][translations]:

  * type: string

design[owner][issues][][issue_files][][file]:

  * type: string

design[owner][issues][][issue_files][][type]:

  * type: string

design[owner][issues][][issue_files][][langCode]:

  * type: string

design[owner][issues][][view_count]:

  * type: integer

design[owner][issues][][download_count]:

  * type: integer

design[owner][issues][][translations]:

  * type: string

design[owner][languages][]:

  * type: array of objects (Lang)

design[owner][languages][][id]:

  * type: integer

design[owner][languages][][code]:

  * type: string

design[owner][languages][][name]:

  * type: string

design[owner][languages][][rtl]:

  * type: boolean

design[owner][periods][]:

  * type: array of objects (Period)

design[owner][periods][][id]:

  * type: integer

design[owner][periods][][translations][]:

  * type: array of objects (PeriodTranslation)

design[owner][periods][][translations][][translatable]:

  * type: object (Period)

design[owner][periods][][translations][][period]:

  * type: string

design[owner][periods][][created_by]:

  * type: string
  * description: @var string

design[owner][periods][][updated_by]:

  * type: string
  * description: @var string

design[owner][periods][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

design[owner][periods][][created]:

  * type: DateTime
  * description: @var \DateTime $created

design[owner][periods][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

design[owner][subjects][]:

  * type: array of objects (Subject)

design[owner][subjects][][id]:

  * type: integer

design[owner][subjects][][parent]:

  * type: object (Subject)

design[owner][subjects][][translations]:

  * type: string

design[owner][publisher]:

  * type: object (Publisher)

design[owner][publisher][id]:

  * type: integer

design[owner][publisher][lft]:

  * type: integer

design[owner][publisher][name]:

  * type: string

design[owner][publisher][address]:

  * type: string

design[owner][publisher][city]:

  * type: string

design[owner][publisher][country]:

  * type: object (Country)

design[owner][publisher][address_lat]:

  * type: string

design[owner][publisher][address_long]:

  * type: string

design[owner][publisher][phone]:

  * type: string

design[owner][publisher][fax]:

  * type: string

design[owner][publisher][email]:

  * type: string

design[owner][publisher][url]:

  * type: string

design[owner][publisher][wiki]:

  * type: string

design[owner][publisher][logo]:

  * type: string

design[owner][publisher][header]:

  * type: string

design[owner][publisher][domain]:

  * type: string

design[owner][publisher][publisher_themes][]:

  * type: array of objects (PublisherTheme)

design[owner][publisher][publisher_themes][][id]:

  * type: integer

design[owner][publisher][publisher_themes][][title]:

  * type: string

design[owner][publisher][publisher_themes][][css]:

  * type: string

design[owner][publisher][publisher_themes][][public]:

  * type: boolean

design[owner][publisher][publisher_themes][][publisher]:

  * type: object (Publisher)

design[owner][publisher][publisher_themes][][created_by]:

  * type: string
  * description: @var string

design[owner][publisher][publisher_themes][][updated_by]:

  * type: string
  * description: @var string

design[owner][publisher][publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

design[owner][publisher][publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

design[owner][publisher][publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

design[owner][publisher][publisher_designs][]:

  * type: array of objects (PublisherDesign)

design[owner][publisher][publisher_designs][][id]:

  * type: integer

design[owner][publisher][publisher_designs][][title]:

  * type: string

design[owner][publisher][publisher_designs][][content]:

  * type: string

design[owner][publisher][publisher_designs][][editable_content]:

  * type: string

design[owner][publisher][publisher_designs][][public]:

  * type: boolean

design[owner][publisher][publisher_designs][][publisher]:

  * type: object (Publisher)

design[owner][publisher][publisher_designs][][created_by]:

  * type: string
  * description: @var string

design[owner][publisher][publisher_designs][][updated_by]:

  * type: string
  * description: @var string

design[owner][publisher][publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

design[owner][publisher][publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

design[owner][publisher][publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

design[owner][logo]:

  * type: string

design[owner][journal_indexs][]:

  * type: array of objects (JournalIndex)

design[owner][journal_indexs][][id]:

  * type: integer

design[owner][journal_indexs][][link]:

  * type: string

design[owner][journal_indexs][][journal]:

  * type: object (Journal)

design[owner][journal_indexs][][index]:

  * type: object (Index)

design[owner][journal_indexs][][index][id]:

  * type: integer

design[owner][journal_indexs][][index][name]:

  * type: string

design[owner][journal_indexs][][index][logo]:

  * type: string

design[owner][journal_indexs][][index][status]:

  * type: boolean

design[owner][journal_indexs][][verified]:

  * type: boolean

design[owner][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

design[owner][submission_checklist][][id]:

  * type: integer

design[owner][submission_checklist][][label]:

  * type: string

design[owner][submission_checklist][][detail]:

  * type: string

design[owner][submission_checklist][][visible]:

  * type: boolean

design[owner][submission_checklist][][deleted_at]:

  * type: DateTime

design[owner][submission_checklist][][journal]:

  * type: object (Journal)

design[owner][submission_checklist][][locale]:

  * type: string

design[owner][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

design[owner][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

design[owner][journal_submission_files][][id]:

  * type: integer

design[owner][journal_submission_files][][title]:

  * type: string

design[owner][journal_submission_files][][detail]:

  * type: string

design[owner][journal_submission_files][][visible]:

  * type: boolean

design[owner][journal_submission_files][][required]:

  * type: boolean

design[owner][journal_submission_files][][deleted_at]:

  * type: DateTime

design[owner][journal_submission_files][][locale]:

  * type: string

design[owner][journal_submission_files][][file]:

  * type: string

design[owner][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

design[owner][journal_application_upload_files][][id]:

  * type: integer

design[owner][journal_application_upload_files][][title]:

  * type: string

design[owner][journal_application_upload_files][][detail]:

  * type: string

design[owner][journal_application_upload_files][][visible]:

  * type: boolean

design[owner][journal_application_upload_files][][required]:

  * type: boolean

design[owner][journal_application_upload_files][][deleted_at]:

  * type: DateTime

design[owner][journal_application_upload_files][][locale]:

  * type: string

design[owner][journal_application_upload_files][][file]:

  * type: string

design[owner][journal_application_upload_files][][journal]:

  * type: object (Journal)

design[owner][printed]:

  * type: boolean

design[owner][mandatory_lang]:

  * type: object (Lang)

design[owner][view_count]:

  * type: integer

design[owner][download_count]:

  * type: integer

design[owner][translations]:

  * type: string

design[owner][mandatoryLang]:

  * type: string

design[created_by]:

  * type: string
  * description: @var string

design[updated_by]:

  * type: string
  * description: @var string

design[deleted_at]:

  * type: DateTime
  * description: @var \DateTime

design[created]:

  * type: DateTime
  * description: @var \DateTime $created

design[updated]:

  * type: DateTime
  * description: @var \DateTime $updated

configured:

  * type: boolean

articles[]:

  * type: array of objects (Article)

articles[][id]:

  * type: integer
  * description: auto-incremented article unique id

articles[][abstract_transliterated]:

  * type: string
  * description: (optional) English transliterated abstract

articles[][status]:

  * type: integer

articles[][doi]:

  * type: string
  * description: (optional)

articles[][title_transliterated]:

  * type: string
  * description: Roman transliterated title

articles[][anonymous]:

  * type: boolean
  * description: Some articles carries no authorship

articles[][submission_date]:

  * type: DateTime

articles[][pubdate]:

  * type: DateTime

articles[][pubdate_season]:

  * type: string

articles[][first_page]:

  * type: integer

articles[][last_page]:

  * type: integer

articles[][uri]:

  * type: string

articles[][primary_language]:

  * type: string

articles[][order_num]:

  * type: integer

articles[][subjects][]:

  * type: array of objects (Subject)

articles[][subjects][][id]:

  * type: integer

articles[][subjects][][parent]:

  * type: object (Subject)

articles[][subjects][][translations]:

  * type: string

articles[][languages][]:

  * type: array of objects (Lang)

articles[][languages][][id]:

  * type: integer

articles[][languages][][code]:

  * type: string

articles[][languages][][name]:

  * type: string

articles[][languages][][rtl]:

  * type: boolean

articles[][article_type]:

  * type: object (ArticleTypes)

articles[][article_type][id]:

  * type: integer

articles[][article_type][translations][]:

  * type: array of objects (ArticleTypesTranslation)

articles[][article_type][translations][][translatable]:

  * type: object (ArticleTypes)

articles[][article_type][translations][][name]:

  * type: string

articles[][article_type][translations][][description]:

  * type: string

articles[][citations][]:

  * type: array of objects (Citation)

articles[][citations][][id]:

  * type: integer

articles[][citations][][raw]:

  * type: string

articles[][citations][][type]:

  * type: string

articles[][article_authors][]:

  * type: array of objects (ArticleAuthor)

articles[][article_authors][][id]:

  * type: integer

articles[][article_authors][][author_order]:

  * type: integer

articles[][article_authors][][author]:

  * type: object (Author)

articles[][article_authors][][author][id]:

  * type: integer

articles[][article_authors][][author][first_name]:

  * type: string

articles[][article_authors][][author][middle_name]:

  * type: string

articles[][article_authors][][author][last_name]:

  * type: string

articles[][article_authors][][author][email]:

  * type: string

articles[][article_authors][][author][first_name_transliterated]:

  * type: string

articles[][article_authors][][author][middle_name_transliterated]:

  * type: string

articles[][article_authors][][author][last_name_transliterated]:

  * type: string

articles[][article_authors][][author][initials]:

  * type: string

articles[][article_authors][][author][address]:

  * type: string

articles[][article_authors][][author][institution]:

  * type: object (Institution)

articles[][article_authors][][author][institution][id]:

  * type: integer

articles[][article_authors][][author][institution][name]:

  * type: string

articles[][article_authors][][author][institution][address]:

  * type: string

articles[][article_authors][][author][institution][city]:

  * type: string

articles[][article_authors][][author][institution][country]:

  * type: object (Country)

articles[][article_authors][][author][institution][address_lat]:

  * type: string

articles[][article_authors][][author][institution][address_long]:

  * type: string

articles[][article_authors][][author][institution][phone]:

  * type: string

articles[][article_authors][][author][institution][fax]:

  * type: string

articles[][article_authors][][author][institution][email]:

  * type: string

articles[][article_authors][][author][institution][url]:

  * type: string

articles[][article_authors][][author][institution][wiki]:

  * type: string

articles[][article_authors][][author][institution][logo]:

  * type: string

articles[][article_authors][][author][institution][header]:

  * type: string

articles[][article_authors][][author][institution][domain]:

  * type: string

articles[][article_authors][][author][author_details]:

  * type: string

articles[][article_authors][][author][user]:

  * type: object (User)

articles[][article_authors][][author][user][username]:

  * type: string

articles[][article_authors][][author][user][text]:

  * type: string

articles[][article_authors][][author][user][first_name]:

  * type: string

articles[][article_authors][][author][user][last_name]:

  * type: string

articles[][article_authors][][author][user][email]:

  * type: string

articles[][article_authors][][author][user][settings]:

  * type: string
  * description: Json encoded settings string

articles[][article_authors][][author][user][about]:

  * type: string

articles[][article_authors][][author][user][country]:

  * type: object (Country)

articles[][article_authors][][author][user][journal_users][]:

  * type: array of objects (JournalUser)

articles[][article_authors][][author][user][journal_users][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal]:

  * type: object (Journal)

articles[][article_authors][][author][user][journal_users][][journal][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][title_transliterated]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][path]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][domain]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issn]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][eissn]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][founded]:

  * type: DateTime

articles[][article_authors][][author][user][journal_users][][journal][url]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][address]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][phone]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][email]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][country]:

  * type: object (Country)

articles[][article_authors][][author][user][journal_users][][journal][published]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][status]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][image]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][header]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][google_analytics_id]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][slug]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][theme]:

  * type: object (JournalTheme)

articles[][article_authors][][author][user][journal_users][][journal][design]:

  * type: object (Design)

articles[][article_authors][][author][user][journal_users][][journal][configured]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][articles][]:

  * type: array of objects (Article)

articles[][article_authors][][author][user][journal_users][][journal][issues][]:

  * type: array of objects (Issue)

articles[][article_authors][][author][user][journal_users][][journal][issues][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][issues][][journal]:

  * type: object (Journal)

articles[][article_authors][][author][user][journal_users][][journal][issues][][volume]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][number]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][cover]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][special]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][issues][][year]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][date_published]:

  * type: DateTime

articles[][article_authors][][author][user][journal_users][][journal][issues][][articles][]:

  * type: array of objects (Article)

articles[][article_authors][][author][user][journal_users][][journal][issues][][header]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][supplement]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][issues][][full_file]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][issue_files][]:

  * type: array of objects (IssueFile)

articles[][article_authors][][author][user][journal_users][][journal][issues][][view_count]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][issues][][download_count]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][languages][]:

  * type: array of objects (Lang)

articles[][article_authors][][author][user][journal_users][][journal][periods][]:

  * type: array of objects (Period)

articles[][article_authors][][author][user][journal_users][][journal][periods][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][periods][][translations][]:

  * type: array of objects (PeriodTranslation)

articles[][article_authors][][author][user][journal_users][][journal][periods][][translations][][translatable]:

  * type: object (Period)

articles[][article_authors][][author][user][journal_users][][journal][periods][][translations][][period]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][periods][][created_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][periods][][updated_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][periods][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

articles[][article_authors][][author][user][journal_users][][journal][periods][][created]:

  * type: DateTime
  * description: @var \DateTime $created

articles[][article_authors][][author][user][journal_users][][journal][periods][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

articles[][article_authors][][author][user][journal_users][][journal][subjects][]:

  * type: array of objects (Subject)

articles[][article_authors][][author][user][journal_users][][journal][publisher]:

  * type: object (Publisher)

articles[][article_authors][][author][user][journal_users][][journal][publisher][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][publisher][lft]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][publisher][name]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][address]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][city]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][country]:

  * type: object (Country)

articles[][article_authors][][author][user][journal_users][][journal][publisher][address_lat]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][address_long]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][phone]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][fax]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][email]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][url]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][wiki]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][logo]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][header]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][domain]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][]:

  * type: array of objects (PublisherTheme)

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][title]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][css]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][public]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][publisher]:

  * type: object (Publisher)

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][created_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][updated_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][]:

  * type: array of objects (PublisherDesign)

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][title]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][content]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][editable_content]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][public]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][publisher]:

  * type: object (Publisher)

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][created_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][updated_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

articles[][article_authors][][author][user][journal_users][][journal][logo]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][link]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][journal]:

  * type: object (Journal)

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][index]:

  * type: object (Index)

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][index][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][index][name]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][index][logo]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][index][status]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][verified]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][label]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][detail]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][visible]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][deleted_at]:

  * type: DateTime

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][journal]:

  * type: object (Journal)

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][locale]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][title]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][detail]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][visible]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][required]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][deleted_at]:

  * type: DateTime

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][locale]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][file]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][title]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][detail]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][visible]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][required]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][locale]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][file]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

articles[][article_authors][][author][user][journal_users][][journal][printed]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][mandatory_lang]:

  * type: object (Lang)

articles[][article_authors][][author][user][journal_users][][journal][view_count]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][download_count]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][user]:

  * type: object (User)

articles[][article_authors][][author][user][journal_users][][roles][]:

  * type: array of objects (Role)

articles[][article_authors][][author][user][journal_users][][roles][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][roles][][name]:

  * type: string

articles[][article_authors][][author][user][journal_users][][roles][][role]:

  * type: string

articles[][article_authors][][author][orcid]:

  * type: string

articles[][article_authors][][author][institution_not_listed]:

  * type: boolean

articles[][article_authors][][author][institution_name]:

  * type: string

articles[][article_authors][][author][article_authors][]:

  * type: array of objects (ArticleAuthor)

articles[][article_authors][][authorOrder]:

  * type: string

articles[][article_files][]:

  * type: array of objects (ArticleFile)

articles[][article_files][][id]:

  * type: integer

articles[][article_files][][type]:

  * type: integer

articles[][article_files][][file]:

  * type: string

articles[][article_files][][version]:

  * type: integer

articles[][article_files][][article]:

  * type: object (Article)

articles[][article_files][][keywords]:

  * type: string

articles[][article_files][][description]:

  * type: string

articles[][article_files][][title]:

  * type: string

articles[][article_files][][lang_code]:

  * type: string

articles[][article_files][][created_by]:

  * type: string
  * description: @var string

articles[][article_files][][updated_by]:

  * type: string
  * description: @var string

articles[][article_files][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

articles[][article_files][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

articles[][article_files][][langCode]:

  * type: string

articles[][article_submission_files][]:

  * type: array of objects (ArticleSubmissionFile)

articles[][article_submission_files][][id]:

  * type: integer

articles[][article_submission_files][][title]:

  * type: string

articles[][article_submission_files][][detail]:

  * type: string

articles[][article_submission_files][][visible]:

  * type: boolean

articles[][article_submission_files][][required]:

  * type: boolean

articles[][article_submission_files][][deleted_at]:

  * type: DateTime

articles[][article_submission_files][][article]:

  * type: object (Article)

articles[][article_submission_files][][locale]:

  * type: string

articles[][article_submission_files][][file]:

  * type: string

articles[][view_count]:

  * type: integer

articles[][download_count]:

  * type: integer

articles[][translations]:

  * type: string

articles[][articleFiles]:

  * type: string

articles[][articleAuthors]:

  * type: string

articles[][submissionDate]:

  * type: string

issues[]:

  * type: array of objects (Issue)

issues[][id]:

  * type: integer

issues[][journal]:

  * type: object (Journal)

issues[][journal][id]:

  * type: integer

issues[][journal][title_transliterated]:

  * type: string

issues[][journal][path]:

  * type: string

issues[][journal][domain]:

  * type: string

issues[][journal][issn]:

  * type: string

issues[][journal][eissn]:

  * type: string

issues[][journal][founded]:

  * type: DateTime

issues[][journal][url]:

  * type: string

issues[][journal][address]:

  * type: string

issues[][journal][phone]:

  * type: string

issues[][journal][email]:

  * type: string

issues[][journal][country]:

  * type: object (Country)

issues[][journal][published]:

  * type: boolean

issues[][journal][status]:

  * type: integer

issues[][journal][image]:

  * type: string

issues[][journal][header]:

  * type: string

issues[][journal][google_analytics_id]:

  * type: string

issues[][journal][slug]:

  * type: string

issues[][journal][theme]:

  * type: object (JournalTheme)

issues[][journal][design]:

  * type: object (Design)

issues[][journal][configured]:

  * type: boolean

issues[][journal][articles][]:

  * type: array of objects (Article)

issues[][journal][issues][]:

  * type: array of objects (Issue)

issues[][journal][languages][]:

  * type: array of objects (Lang)

issues[][journal][languages][][id]:

  * type: integer

issues[][journal][languages][][code]:

  * type: string

issues[][journal][languages][][name]:

  * type: string

issues[][journal][languages][][rtl]:

  * type: boolean

issues[][journal][periods][]:

  * type: array of objects (Period)

issues[][journal][periods][][id]:

  * type: integer

issues[][journal][periods][][translations][]:

  * type: array of objects (PeriodTranslation)

issues[][journal][periods][][translations][][translatable]:

  * type: object (Period)

issues[][journal][periods][][translations][][period]:

  * type: string

issues[][journal][periods][][created_by]:

  * type: string
  * description: @var string

issues[][journal][periods][][updated_by]:

  * type: string
  * description: @var string

issues[][journal][periods][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

issues[][journal][periods][][created]:

  * type: DateTime
  * description: @var \DateTime $created

issues[][journal][periods][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

issues[][journal][subjects][]:

  * type: array of objects (Subject)

issues[][journal][subjects][][id]:

  * type: integer

issues[][journal][subjects][][parent]:

  * type: object (Subject)

issues[][journal][subjects][][translations]:

  * type: string

issues[][journal][publisher]:

  * type: object (Publisher)

issues[][journal][publisher][id]:

  * type: integer

issues[][journal][publisher][lft]:

  * type: integer

issues[][journal][publisher][name]:

  * type: string

issues[][journal][publisher][address]:

  * type: string

issues[][journal][publisher][city]:

  * type: string

issues[][journal][publisher][country]:

  * type: object (Country)

issues[][journal][publisher][address_lat]:

  * type: string

issues[][journal][publisher][address_long]:

  * type: string

issues[][journal][publisher][phone]:

  * type: string

issues[][journal][publisher][fax]:

  * type: string

issues[][journal][publisher][email]:

  * type: string

issues[][journal][publisher][url]:

  * type: string

issues[][journal][publisher][wiki]:

  * type: string

issues[][journal][publisher][logo]:

  * type: string

issues[][journal][publisher][header]:

  * type: string

issues[][journal][publisher][domain]:

  * type: string

issues[][journal][publisher][publisher_themes][]:

  * type: array of objects (PublisherTheme)

issues[][journal][publisher][publisher_themes][][id]:

  * type: integer

issues[][journal][publisher][publisher_themes][][title]:

  * type: string

issues[][journal][publisher][publisher_themes][][css]:

  * type: string

issues[][journal][publisher][publisher_themes][][public]:

  * type: boolean

issues[][journal][publisher][publisher_themes][][publisher]:

  * type: object (Publisher)

issues[][journal][publisher][publisher_themes][][created_by]:

  * type: string
  * description: @var string

issues[][journal][publisher][publisher_themes][][updated_by]:

  * type: string
  * description: @var string

issues[][journal][publisher][publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

issues[][journal][publisher][publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

issues[][journal][publisher][publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

issues[][journal][publisher][publisher_designs][]:

  * type: array of objects (PublisherDesign)

issues[][journal][publisher][publisher_designs][][id]:

  * type: integer

issues[][journal][publisher][publisher_designs][][title]:

  * type: string

issues[][journal][publisher][publisher_designs][][content]:

  * type: string

issues[][journal][publisher][publisher_designs][][editable_content]:

  * type: string

issues[][journal][publisher][publisher_designs][][public]:

  * type: boolean

issues[][journal][publisher][publisher_designs][][publisher]:

  * type: object (Publisher)

issues[][journal][publisher][publisher_designs][][created_by]:

  * type: string
  * description: @var string

issues[][journal][publisher][publisher_designs][][updated_by]:

  * type: string
  * description: @var string

issues[][journal][publisher][publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

issues[][journal][publisher][publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

issues[][journal][publisher][publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

issues[][journal][logo]:

  * type: string

issues[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

issues[][journal][journal_indexs][][id]:

  * type: integer

issues[][journal][journal_indexs][][link]:

  * type: string

issues[][journal][journal_indexs][][journal]:

  * type: object (Journal)

issues[][journal][journal_indexs][][index]:

  * type: object (Index)

issues[][journal][journal_indexs][][index][id]:

  * type: integer

issues[][journal][journal_indexs][][index][name]:

  * type: string

issues[][journal][journal_indexs][][index][logo]:

  * type: string

issues[][journal][journal_indexs][][index][status]:

  * type: boolean

issues[][journal][journal_indexs][][verified]:

  * type: boolean

issues[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

issues[][journal][submission_checklist][][id]:

  * type: integer

issues[][journal][submission_checklist][][label]:

  * type: string

issues[][journal][submission_checklist][][detail]:

  * type: string

issues[][journal][submission_checklist][][visible]:

  * type: boolean

issues[][journal][submission_checklist][][deleted_at]:

  * type: DateTime

issues[][journal][submission_checklist][][journal]:

  * type: object (Journal)

issues[][journal][submission_checklist][][locale]:

  * type: string

issues[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

issues[][journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

issues[][journal][journal_submission_files][][id]:

  * type: integer

issues[][journal][journal_submission_files][][title]:

  * type: string

issues[][journal][journal_submission_files][][detail]:

  * type: string

issues[][journal][journal_submission_files][][visible]:

  * type: boolean

issues[][journal][journal_submission_files][][required]:

  * type: boolean

issues[][journal][journal_submission_files][][deleted_at]:

  * type: DateTime

issues[][journal][journal_submission_files][][locale]:

  * type: string

issues[][journal][journal_submission_files][][file]:

  * type: string

issues[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

issues[][journal][journal_application_upload_files][][id]:

  * type: integer

issues[][journal][journal_application_upload_files][][title]:

  * type: string

issues[][journal][journal_application_upload_files][][detail]:

  * type: string

issues[][journal][journal_application_upload_files][][visible]:

  * type: boolean

issues[][journal][journal_application_upload_files][][required]:

  * type: boolean

issues[][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

issues[][journal][journal_application_upload_files][][locale]:

  * type: string

issues[][journal][journal_application_upload_files][][file]:

  * type: string

issues[][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

issues[][journal][printed]:

  * type: boolean

issues[][journal][mandatory_lang]:

  * type: object (Lang)

issues[][journal][view_count]:

  * type: integer

issues[][journal][download_count]:

  * type: integer

issues[][journal][translations]:

  * type: string

issues[][journal][mandatoryLang]:

  * type: string

issues[][volume]:

  * type: string

issues[][number]:

  * type: string

issues[][cover]:

  * type: string

issues[][special]:

  * type: boolean

issues[][year]:

  * type: string

issues[][date_published]:

  * type: DateTime

issues[][articles][]:

  * type: array of objects (Article)

issues[][header]:

  * type: string

issues[][supplement]:

  * type: boolean

issues[][full_file]:

  * type: string

issues[][issue_files][]:

  * type: array of objects (IssueFile)

issues[][issue_files][][translations]:

  * type: string

issues[][issue_files][][file]:

  * type: string

issues[][issue_files][][type]:

  * type: string

issues[][issue_files][][langCode]:

  * type: string

issues[][view_count]:

  * type: integer

issues[][download_count]:

  * type: integer

issues[][translations]:

  * type: string

periods[]:

  * type: array of objects (Period)

periods[][id]:

  * type: integer

periods[][translations][]:

  * type: array of objects (PeriodTranslation)

periods[][translations][][translatable]:

  * type: object (Period)

periods[][translations][][period]:

  * type: string

periods[][created_by]:

  * type: string
  * description: @var string

periods[][updated_by]:

  * type: string
  * description: @var string

periods[][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

periods[][created]:

  * type: DateTime
  * description: @var \DateTime $created

periods[][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

subjects[]:

  * type: array of objects (Subject)

subjects[][id]:

  * type: integer

subjects[][parent]:

  * type: object (Subject)

subjects[][translations]:

  * type: string

publisher:

  * type: object (Publisher)

publisher[id]:

  * type: integer

publisher[lft]:

  * type: integer

publisher[name]:

  * type: string

publisher[address]:

  * type: string

publisher[city]:

  * type: string

publisher[country]:

  * type: object (Country)

publisher[address_lat]:

  * type: string

publisher[address_long]:

  * type: string

publisher[phone]:

  * type: string

publisher[fax]:

  * type: string

publisher[email]:

  * type: string

publisher[url]:

  * type: string

publisher[wiki]:

  * type: string

publisher[logo]:

  * type: string

publisher[header]:

  * type: string

publisher[domain]:

  * type: string

publisher[publisher_themes][]:

  * type: array of objects (PublisherTheme)

publisher[publisher_themes][][id]:

  * type: integer

publisher[publisher_themes][][title]:

  * type: string

publisher[publisher_themes][][css]:

  * type: string

publisher[publisher_themes][][public]:

  * type: boolean

publisher[publisher_themes][][publisher]:

  * type: object (Publisher)

publisher[publisher_themes][][created_by]:

  * type: string
  * description: @var string

publisher[publisher_themes][][updated_by]:

  * type: string
  * description: @var string

publisher[publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

publisher[publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

publisher[publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

publisher[publisher_designs][]:

  * type: array of objects (PublisherDesign)

publisher[publisher_designs][][id]:

  * type: integer

publisher[publisher_designs][][title]:

  * type: string

publisher[publisher_designs][][content]:

  * type: string

publisher[publisher_designs][][editable_content]:

  * type: string

publisher[publisher_designs][][public]:

  * type: boolean

publisher[publisher_designs][][publisher]:

  * type: object (Publisher)

publisher[publisher_designs][][created_by]:

  * type: string
  * description: @var string

publisher[publisher_designs][][updated_by]:

  * type: string
  * description: @var string

publisher[publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

publisher[publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

publisher[publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

logo:

  * type: string

journal_indexs[]:

  * type: array of objects (JournalIndex)

journal_indexs[][id]:

  * type: integer

journal_indexs[][link]:

  * type: string

journal_indexs[][journal]:

  * type: object (Journal)

journal_indexs[][journal][id]:

  * type: integer

journal_indexs[][journal][title_transliterated]:

  * type: string

journal_indexs[][journal][path]:

  * type: string

journal_indexs[][journal][domain]:

  * type: string

journal_indexs[][journal][issn]:

  * type: string

journal_indexs[][journal][eissn]:

  * type: string

journal_indexs[][journal][founded]:

  * type: DateTime

journal_indexs[][journal][url]:

  * type: string

journal_indexs[][journal][address]:

  * type: string

journal_indexs[][journal][phone]:

  * type: string

journal_indexs[][journal][email]:

  * type: string

journal_indexs[][journal][country]:

  * type: object (Country)

journal_indexs[][journal][published]:

  * type: boolean

journal_indexs[][journal][status]:

  * type: integer

journal_indexs[][journal][image]:

  * type: string

journal_indexs[][journal][header]:

  * type: string

journal_indexs[][journal][google_analytics_id]:

  * type: string

journal_indexs[][journal][slug]:

  * type: string

journal_indexs[][journal][theme]:

  * type: object (JournalTheme)

journal_indexs[][journal][design]:

  * type: object (Design)

journal_indexs[][journal][configured]:

  * type: boolean

journal_indexs[][journal][articles][]:

  * type: array of objects (Article)

journal_indexs[][journal][issues][]:

  * type: array of objects (Issue)

journal_indexs[][journal][languages][]:

  * type: array of objects (Lang)

journal_indexs[][journal][periods][]:

  * type: array of objects (Period)

journal_indexs[][journal][subjects][]:

  * type: array of objects (Subject)

journal_indexs[][journal][publisher]:

  * type: object (Publisher)

journal_indexs[][journal][logo]:

  * type: string

journal_indexs[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

journal_indexs[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

journal_indexs[][journal][submission_checklist][][id]:

  * type: integer

journal_indexs[][journal][submission_checklist][][label]:

  * type: string

journal_indexs[][journal][submission_checklist][][detail]:

  * type: string

journal_indexs[][journal][submission_checklist][][visible]:

  * type: boolean

journal_indexs[][journal][submission_checklist][][deleted_at]:

  * type: DateTime

journal_indexs[][journal][submission_checklist][][journal]:

  * type: object (Journal)

journal_indexs[][journal][submission_checklist][][locale]:

  * type: string

journal_indexs[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

journal_indexs[][journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

journal_indexs[][journal][journal_submission_files][][id]:

  * type: integer

journal_indexs[][journal][journal_submission_files][][title]:

  * type: string

journal_indexs[][journal][journal_submission_files][][detail]:

  * type: string

journal_indexs[][journal][journal_submission_files][][visible]:

  * type: boolean

journal_indexs[][journal][journal_submission_files][][required]:

  * type: boolean

journal_indexs[][journal][journal_submission_files][][deleted_at]:

  * type: DateTime

journal_indexs[][journal][journal_submission_files][][locale]:

  * type: string

journal_indexs[][journal][journal_submission_files][][file]:

  * type: string

journal_indexs[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

journal_indexs[][journal][journal_application_upload_files][][id]:

  * type: integer

journal_indexs[][journal][journal_application_upload_files][][title]:

  * type: string

journal_indexs[][journal][journal_application_upload_files][][detail]:

  * type: string

journal_indexs[][journal][journal_application_upload_files][][visible]:

  * type: boolean

journal_indexs[][journal][journal_application_upload_files][][required]:

  * type: boolean

journal_indexs[][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

journal_indexs[][journal][journal_application_upload_files][][locale]:

  * type: string

journal_indexs[][journal][journal_application_upload_files][][file]:

  * type: string

journal_indexs[][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

journal_indexs[][journal][printed]:

  * type: boolean

journal_indexs[][journal][mandatory_lang]:

  * type: object (Lang)

journal_indexs[][journal][view_count]:

  * type: integer

journal_indexs[][journal][download_count]:

  * type: integer

journal_indexs[][journal][translations]:

  * type: string

journal_indexs[][journal][mandatoryLang]:

  * type: string

journal_indexs[][index]:

  * type: object (Index)

journal_indexs[][index][id]:

  * type: integer

journal_indexs[][index][name]:

  * type: string

journal_indexs[][index][logo]:

  * type: string

journal_indexs[][index][status]:

  * type: boolean

journal_indexs[][verified]:

  * type: boolean

submission_checklist[]:

  * type: array of objects (SubmissionChecklist)

submission_checklist[][id]:

  * type: integer

submission_checklist[][label]:

  * type: string

submission_checklist[][detail]:

  * type: string

submission_checklist[][visible]:

  * type: boolean

submission_checklist[][deleted_at]:

  * type: DateTime

submission_checklist[][journal]:

  * type: object (Journal)

submission_checklist[][journal][id]:

  * type: integer

submission_checklist[][journal][title_transliterated]:

  * type: string

submission_checklist[][journal][path]:

  * type: string

submission_checklist[][journal][domain]:

  * type: string

submission_checklist[][journal][issn]:

  * type: string

submission_checklist[][journal][eissn]:

  * type: string

submission_checklist[][journal][founded]:

  * type: DateTime

submission_checklist[][journal][url]:

  * type: string

submission_checklist[][journal][address]:

  * type: string

submission_checklist[][journal][phone]:

  * type: string

submission_checklist[][journal][email]:

  * type: string

submission_checklist[][journal][country]:

  * type: object (Country)

submission_checklist[][journal][published]:

  * type: boolean

submission_checklist[][journal][status]:

  * type: integer

submission_checklist[][journal][image]:

  * type: string

submission_checklist[][journal][header]:

  * type: string

submission_checklist[][journal][google_analytics_id]:

  * type: string

submission_checklist[][journal][slug]:

  * type: string

submission_checklist[][journal][theme]:

  * type: object (JournalTheme)

submission_checklist[][journal][design]:

  * type: object (Design)

submission_checklist[][journal][configured]:

  * type: boolean

submission_checklist[][journal][articles][]:

  * type: array of objects (Article)

submission_checklist[][journal][issues][]:

  * type: array of objects (Issue)

submission_checklist[][journal][languages][]:

  * type: array of objects (Lang)

submission_checklist[][journal][periods][]:

  * type: array of objects (Period)

submission_checklist[][journal][subjects][]:

  * type: array of objects (Subject)

submission_checklist[][journal][publisher]:

  * type: object (Publisher)

submission_checklist[][journal][logo]:

  * type: string

submission_checklist[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

submission_checklist[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

submission_checklist[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

submission_checklist[][journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

submission_checklist[][journal][journal_submission_files][][id]:

  * type: integer

submission_checklist[][journal][journal_submission_files][][title]:

  * type: string

submission_checklist[][journal][journal_submission_files][][detail]:

  * type: string

submission_checklist[][journal][journal_submission_files][][visible]:

  * type: boolean

submission_checklist[][journal][journal_submission_files][][required]:

  * type: boolean

submission_checklist[][journal][journal_submission_files][][deleted_at]:

  * type: DateTime

submission_checklist[][journal][journal_submission_files][][locale]:

  * type: string

submission_checklist[][journal][journal_submission_files][][file]:

  * type: string

submission_checklist[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

submission_checklist[][journal][journal_application_upload_files][][id]:

  * type: integer

submission_checklist[][journal][journal_application_upload_files][][title]:

  * type: string

submission_checklist[][journal][journal_application_upload_files][][detail]:

  * type: string

submission_checklist[][journal][journal_application_upload_files][][visible]:

  * type: boolean

submission_checklist[][journal][journal_application_upload_files][][required]:

  * type: boolean

submission_checklist[][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

submission_checklist[][journal][journal_application_upload_files][][locale]:

  * type: string

submission_checklist[][journal][journal_application_upload_files][][file]:

  * type: string

submission_checklist[][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

submission_checklist[][journal][printed]:

  * type: boolean

submission_checklist[][journal][mandatory_lang]:

  * type: object (Lang)

submission_checklist[][journal][view_count]:

  * type: integer

submission_checklist[][journal][download_count]:

  * type: integer

submission_checklist[][journal][translations]:

  * type: string

submission_checklist[][journal][mandatoryLang]:

  * type: string

submission_checklist[][locale]:

  * type: string

journal_submission_files[]:

  * type: array of objects (JournalSubmissionFile)

journal_submission_files[][journal]:

  * type: object (Journal)
  * description: @var  Journal

journal_submission_files[][journal][id]:

  * type: integer

journal_submission_files[][journal][title_transliterated]:

  * type: string

journal_submission_files[][journal][path]:

  * type: string

journal_submission_files[][journal][domain]:

  * type: string

journal_submission_files[][journal][issn]:

  * type: string

journal_submission_files[][journal][eissn]:

  * type: string

journal_submission_files[][journal][founded]:

  * type: DateTime

journal_submission_files[][journal][url]:

  * type: string

journal_submission_files[][journal][address]:

  * type: string

journal_submission_files[][journal][phone]:

  * type: string

journal_submission_files[][journal][email]:

  * type: string

journal_submission_files[][journal][country]:

  * type: object (Country)

journal_submission_files[][journal][published]:

  * type: boolean

journal_submission_files[][journal][status]:

  * type: integer

journal_submission_files[][journal][image]:

  * type: string

journal_submission_files[][journal][header]:

  * type: string

journal_submission_files[][journal][google_analytics_id]:

  * type: string

journal_submission_files[][journal][slug]:

  * type: string

journal_submission_files[][journal][theme]:

  * type: object (JournalTheme)

journal_submission_files[][journal][design]:

  * type: object (Design)

journal_submission_files[][journal][configured]:

  * type: boolean

journal_submission_files[][journal][articles][]:

  * type: array of objects (Article)

journal_submission_files[][journal][issues][]:

  * type: array of objects (Issue)

journal_submission_files[][journal][languages][]:

  * type: array of objects (Lang)

journal_submission_files[][journal][periods][]:

  * type: array of objects (Period)

journal_submission_files[][journal][subjects][]:

  * type: array of objects (Subject)

journal_submission_files[][journal][publisher]:

  * type: object (Publisher)

journal_submission_files[][journal][logo]:

  * type: string

journal_submission_files[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

journal_submission_files[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

journal_submission_files[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

journal_submission_files[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

journal_submission_files[][journal][journal_application_upload_files][][id]:

  * type: integer

journal_submission_files[][journal][journal_application_upload_files][][title]:

  * type: string

journal_submission_files[][journal][journal_application_upload_files][][detail]:

  * type: string

journal_submission_files[][journal][journal_application_upload_files][][visible]:

  * type: boolean

journal_submission_files[][journal][journal_application_upload_files][][required]:

  * type: boolean

journal_submission_files[][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

journal_submission_files[][journal][journal_application_upload_files][][locale]:

  * type: string

journal_submission_files[][journal][journal_application_upload_files][][file]:

  * type: string

journal_submission_files[][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

journal_submission_files[][journal][printed]:

  * type: boolean

journal_submission_files[][journal][mandatory_lang]:

  * type: object (Lang)

journal_submission_files[][journal][view_count]:

  * type: integer

journal_submission_files[][journal][download_count]:

  * type: integer

journal_submission_files[][journal][translations]:

  * type: string

journal_submission_files[][journal][mandatoryLang]:

  * type: string

journal_submission_files[][id]:

  * type: integer

journal_submission_files[][title]:

  * type: string

journal_submission_files[][detail]:

  * type: string

journal_submission_files[][visible]:

  * type: boolean

journal_submission_files[][required]:

  * type: boolean

journal_submission_files[][deleted_at]:

  * type: DateTime

journal_submission_files[][locale]:

  * type: string

journal_submission_files[][file]:

  * type: string

journal_application_upload_files[]:

  * type: array of objects (JournalApplicationUploadFile)

journal_application_upload_files[][id]:

  * type: integer

journal_application_upload_files[][title]:

  * type: string

journal_application_upload_files[][detail]:

  * type: string

journal_application_upload_files[][visible]:

  * type: boolean

journal_application_upload_files[][required]:

  * type: boolean

journal_application_upload_files[][deleted_at]:

  * type: DateTime

journal_application_upload_files[][locale]:

  * type: string

journal_application_upload_files[][file]:

  * type: string

journal_application_upload_files[][journal]:

  * type: object (Journal)

journal_application_upload_files[][journal][id]:

  * type: integer

journal_application_upload_files[][journal][title_transliterated]:

  * type: string

journal_application_upload_files[][journal][path]:

  * type: string

journal_application_upload_files[][journal][domain]:

  * type: string

journal_application_upload_files[][journal][issn]:

  * type: string

journal_application_upload_files[][journal][eissn]:

  * type: string

journal_application_upload_files[][journal][founded]:

  * type: DateTime

journal_application_upload_files[][journal][url]:

  * type: string

journal_application_upload_files[][journal][address]:

  * type: string

journal_application_upload_files[][journal][phone]:

  * type: string

journal_application_upload_files[][journal][email]:

  * type: string

journal_application_upload_files[][journal][country]:

  * type: object (Country)

journal_application_upload_files[][journal][published]:

  * type: boolean

journal_application_upload_files[][journal][status]:

  * type: integer

journal_application_upload_files[][journal][image]:

  * type: string

journal_application_upload_files[][journal][header]:

  * type: string

journal_application_upload_files[][journal][google_analytics_id]:

  * type: string

journal_application_upload_files[][journal][slug]:

  * type: string

journal_application_upload_files[][journal][theme]:

  * type: object (JournalTheme)

journal_application_upload_files[][journal][design]:

  * type: object (Design)

journal_application_upload_files[][journal][configured]:

  * type: boolean

journal_application_upload_files[][journal][articles][]:

  * type: array of objects (Article)

journal_application_upload_files[][journal][issues][]:

  * type: array of objects (Issue)

journal_application_upload_files[][journal][languages][]:

  * type: array of objects (Lang)

journal_application_upload_files[][journal][periods][]:

  * type: array of objects (Period)

journal_application_upload_files[][journal][subjects][]:

  * type: array of objects (Subject)

journal_application_upload_files[][journal][publisher]:

  * type: object (Publisher)

journal_application_upload_files[][journal][logo]:

  * type: string

journal_application_upload_files[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

journal_application_upload_files[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

journal_application_upload_files[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

journal_application_upload_files[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

journal_application_upload_files[][journal][printed]:

  * type: boolean

journal_application_upload_files[][journal][mandatory_lang]:

  * type: object (Lang)

journal_application_upload_files[][journal][view_count]:

  * type: integer

journal_application_upload_files[][journal][download_count]:

  * type: integer

journal_application_upload_files[][journal][translations]:

  * type: string

journal_application_upload_files[][journal][mandatoryLang]:

  * type: string

printed:

  * type: boolean

mandatory_lang:

  * type: object (Lang)

view_count:

  * type: integer

download_count:

  * type: integer


### `PUT` /api/v1/journals/{id}.{_format} ###

_Update existing Journal from the submitted data or create a new Journal at a specific location._

Update existing Journal from the submitted data or create a new Journal at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Journal id


### `DELETE` /api/v1/journals/{id}.{_format} ###

_Delete Journal_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Journal ID
**_format**

  - Requirement: xml|json|html


## /api/v1/langs ##

### `GET` /api/v1/langs.{_format} ###

_List all Langs._

List all Langs.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Langs.

limit:

  * Requirement: \d+
  * Description: How many Langs to return.
  * Default: 5


### `POST` /api/v1/langs.{_format} ###

_Creates a new Lang from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/langs/new ##

### `GET` /api/v1/langs/new.{_format} ###

_Presents the form to use to create a new Lang._

Presents the form to use to create a new Lang.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/langs/{id} ##

### `GET` /api/v1/langs/{id}.{_format} ###

_Gets a Lang for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Lang id


### `PATCH` /api/v1/langs/{id}.{_format} ###

_Update existing lang from the submitted data or create a new lang at a specific location._

Update existing lang from the submitted data or create a new lang at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the lang id


### `PUT` /api/v1/langs/{id}.{_format} ###

_Update existing Lang from the submitted data or create a new Lang at a specific location._

Update existing Lang from the submitted data or create a new Lang at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Lang id


### `DELETE` /api/v1/langs/{id}.{_format} ###

_Delete Lang_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Lang ID
**_format**

  - Requirement: xml|json|html


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

### `PATCH` /api/v1/pages/{id}.{_format} ###

_Update existing page from the submitted data or create a new page at a specific location._

Update existing page from the submitted data or create a new page at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the page id


### `GET` /api/v1/pages/{id}.{_format} ###

_Gets a Page for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Page id


### `PUT` /api/v1/pages/{id}.{_format} ###

_Update existing Page from the submitted data or create a new Page at a specific location._

Update existing Page from the submitted data or create a new Page at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Page id


### `DELETE` /api/v1/pages/{id}.{_format} ###

_Delete Page_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Page ID
**_format**

  - Requirement: xml|json|html


## /api/v1/periods ##

### `GET` /api/v1/periods.{_format} ###

_List all Periods._

List all Periods.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Periods.

limit:

  * Requirement: \d+
  * Description: How many Periods to return.
  * Default: 5


### `POST` /api/v1/periods.{_format} ###

_Creates a new Period from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/periods/new ##

### `GET` /api/v1/periods/new.{_format} ###

_Presents the form to use to create a new Period._

Presents the form to use to create a new Period.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/periods/{id} ##

### `PATCH` /api/v1/periods/{id}.{_format} ###

_Update existing period from the submitted data or create a new period at a specific location._

Update existing period from the submitted data or create a new period at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the period id


### `GET` /api/v1/periods/{id}.{_format} ###

_Gets a Period for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Period id


### `PUT` /api/v1/periods/{id}.{_format} ###

_Update existing Period from the submitted data or create a new Period at a specific location._

Update existing Period from the submitted data or create a new Period at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Period id


### `DELETE` /api/v1/periods/{id}.{_format} ###

_Delete Period_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Period ID
**_format**

  - Requirement: xml|json|html


## /api/v1/persontitles ##

### `GET` /api/v1/persontitles.{_format} ###

_List all PersonTitles._

List all PersonTitles.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PersonTitles.

limit:

  * Requirement: \d+
  * Description: How many PersonTitles to return.
  * Default: 5


### `POST` /api/v1/persontitles.{_format} ###

_Creates a new PersonTitle from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/persontitles/new ##

### `GET` /api/v1/persontitles/new.{_format} ###

_Presents the form to use to create a new PersonTitle._

Presents the form to use to create a new PersonTitle.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/persontitles/{id} ##

### `GET` /api/v1/persontitles/{id}.{_format} ###

_Gets a PersonTitle for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PersonTitle id


### `PATCH` /api/v1/persontitles/{id}.{_format} ###

_Update existing persontitle from the submitted data or create a new persontitle at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the person_title id


### `PUT` /api/v1/persontitles/{id}.{_format} ###

_Update existing PersonTitle from the submitted data or create a new PersonTitle at a specific location._

Update existing PersonTitle from the submitted data or create a new PersonTitle at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PersonTitle id


### `DELETE` /api/v1/persontitles/{id}.{_format} ###

_Delete PersonTitle_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PersonTitle ID
**_format**

  - Requirement: xml|json|html


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


### `DELETE` /api/v1/posts/{id}.{_format} ###

_Delete Post_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Post ID
**_format**

  - Requirement: xml|json|html


## /api/v1/publishermanagers ##

### `GET` /api/v1/publishermanagers.{_format} ###

_List all PublisherManager._

List all PublisherManager.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PublisherManager.

limit:

  * Requirement: \d+
  * Description: How many PublisherManager to return.
  * Default: 5


### `POST` /api/v1/publishermanagers.{_format} ###

_Creates a new PublisherManager from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishermanagers/new ##

### `GET` /api/v1/publishermanagers/new.{_format} ###

_Presents the form to use to create a new PublisherManager._

Presents the form to use to create a new PublisherManager.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishermanagers/{id} ##

### `GET` /api/v1/publishermanagers/{id}.{_format} ###

_Gets a PublisherManager for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherManager id


### `PATCH` /api/v1/publishermanagers/{id}.{_format} ###

_Update existing publishermanager from the submitted data or create a new publishermanager at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisher_manager id


### `PUT` /api/v1/publishermanagers/{id}.{_format} ###

_Update existing PublisherManager from the submitted data or create a new PublisherManager at a specific location._

Update existing PublisherManager from the submitted data or create a new PublisherManager at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherManager id


### `DELETE` /api/v1/publishermanagers/{id}.{_format} ###

_Delete PublisherManager_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PublisherManager ID
**_format**

  - Requirement: xml|json|html


## /api/v1/publishers ##

### `GET` /api/v1/publishers.{_format} ###

_List all Publishers._

List all Publishers.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Publishers.

limit:

  * Requirement: \d+
  * Description: How many Publishers to return.
  * Default: 5


### `POST` /api/v1/publishers.{_format} ###

_Creates a new Publisher from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishers/new ##

### `GET` /api/v1/publishers/new.{_format} ###

_Presents the form to use to create a new Publisher._

Presents the form to use to create a new Publisher.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishers/{id} ##

### `PATCH` /api/v1/publishers/{id}.{_format} ###

_Update existing publisher from the submitted data or create a new publisher at a specific location._

Update existing publisher from the submitted data or create a new publisher at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisher id


### `GET` /api/v1/publishers/{id}.{_format} ###

_Gets a Publisher for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Publisher id


### `PUT` /api/v1/publishers/{id}.{_format} ###

_Update existing Publisher from the submitted data or create a new Publisher at a specific location._

Update existing Publisher from the submitted data or create a new Publisher at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Publisher id


### `DELETE` /api/v1/publishers/{id}.{_format} ###

_Delete Publisher_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Publisher ID
**_format**

  - Requirement: xml|json|html


## /api/v1/publisherthemes ##

### `GET` /api/v1/publisherthemes.{_format} ###

_List all PublisherTheme._

List all PublisherTheme.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PublisherTheme.

limit:

  * Requirement: \d+
  * Description: How many PublisherTheme to return.
  * Default: 5


### `POST` /api/v1/publisherthemes.{_format} ###

_Creates a new PublisherTheme from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publisherthemes/new ##

### `GET` /api/v1/publisherthemes/new.{_format} ###

_Presents the form to use to create a new PublisherTheme._

Presents the form to use to create a new PublisherTheme.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publisherthemes/{id} ##

### `PATCH` /api/v1/publisherthemes/{id}.{_format} ###

_Update existing publishertheme from the submitted data or create a new publishertheme at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisher_theme id


### `GET` /api/v1/publisherthemes/{id}.{_format} ###

_Gets a PublisherTheme for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherTheme id


### `PUT` /api/v1/publisherthemes/{id}.{_format} ###

_Update existing PublisherTheme from the submitted data or create a new PublisherTheme at a specific location._

Update existing PublisherTheme from the submitted data or create a new PublisherTheme at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherTheme id


### `DELETE` /api/v1/publisherthemes/{id}.{_format} ###

_Delete PublisherTheme_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PublisherTheme ID
**_format**

  - Requirement: xml|json|html


## /api/v1/publishertypes ##

### `GET` /api/v1/publishertypes.{_format} ###

_List all PublisherTypes._

List all PublisherTypes.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PublisherTypes.

limit:

  * Requirement: \d+
  * Description: How many PublisherTypes to return.
  * Default: 5


### `POST` /api/v1/publishertypes.{_format} ###

_Creates a new PublisherType from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishertypes/new ##

### `GET` /api/v1/publishertypes/new.{_format} ###

_Presents the form to use to create a new PublisherType._

Presents the form to use to create a new PublisherType.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishertypes/{id} ##

### `PATCH` /api/v1/publishertypes/{id}.{_format} ###

_Update existing publisherType from the submitted data or create a new publisherType at a specific location._

Update existing publisherType from the submitted data or create a new publisherType at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisherType id


### `GET` /api/v1/publishertypes/{id}.{_format} ###

_Gets a PublisherType for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherType id


### `PUT` /api/v1/publishertypes/{id}.{_format} ###

_Update existing PublisherType from the submitted data or create a new PublisherType at a specific location._

Update existing PublisherType from the submitted data or create a new PublisherType at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherType id


### `DELETE` /api/v1/publishertypes/{id}.{_format} ###

_Delete PublisherType_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PublisherType ID
**_format**

  - Requirement: xml|json|html


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

### `PATCH` /api/v1/subjects/{id}.{_format} ###

_Update existing subject from the submitted data or create a new subject at a specific location._

Update existing subject from the submitted data or create a new subject at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the subject id


### `GET` /api/v1/subjects/{id}.{_format} ###

_Gets a Subject for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Subject id


### `PUT` /api/v1/subjects/{id}.{_format} ###

_Update existing Subject from the submitted data or create a new Subject at a specific location._

Update existing Subject from the submitted data or create a new Subject at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Subject id


### `DELETE` /api/v1/subjects/{id}.{_format} ###

_Delete Subject_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Subject ID
**_format**

  - Requirement: xml|json|html


## /api/v1/users ##

### `GET` /api/v1/users.{_format} ###

_List all Users._

List all Users.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Users.

limit:

  * Requirement: \d+
  * Description: How many Users to return.
  * Default: 5


### `POST` /api/v1/users.{_format} ###

_Creates a new User from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/users/new ##

### `GET` /api/v1/users/new.{_format} ###

_Presents the form to use to create a new User._

Presents the form to use to create a new User.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/users/{id} ##

### `PATCH` /api/v1/users/{id}.{_format} ###

_Update existing user from the submitted data or create a new user at a specific location._

Update existing user from the submitted data or create a new user at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the user id


### `GET` /api/v1/users/{id}.{_format} ###

_Gets a User for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the User id

#### Response ####

username:

  * type: string

email:

  * type: string

plainPassword:

  * type: string

password:

  * type: string

firstName:

  * type: string

lastName:

  * type: string

text:

  * type: string

first_name:

  * type: string

last_name:

  * type: string

settings:

  * type: string
  * description: Json encoded settings string

about:

  * type: string

country:

  * type: object (Country)

country[id]:

  * type: integer

country[name]:

  * type: string

journal_users[]:

  * type: array of objects (JournalUser)

journal_users[][id]:

  * type: integer

journal_users[][journal]:

  * type: object (Journal)

journal_users[][journal][id]:

  * type: integer

journal_users[][journal][title_transliterated]:

  * type: string

journal_users[][journal][path]:

  * type: string

journal_users[][journal][domain]:

  * type: string

journal_users[][journal][issn]:

  * type: string

journal_users[][journal][eissn]:

  * type: string

journal_users[][journal][founded]:

  * type: DateTime

journal_users[][journal][url]:

  * type: string

journal_users[][journal][address]:

  * type: string

journal_users[][journal][phone]:

  * type: string

journal_users[][journal][email]:

  * type: string

journal_users[][journal][country]:

  * type: object (Country)

journal_users[][journal][published]:

  * type: boolean

journal_users[][journal][status]:

  * type: integer

journal_users[][journal][image]:

  * type: string

journal_users[][journal][header]:

  * type: string

journal_users[][journal][google_analytics_id]:

  * type: string

journal_users[][journal][slug]:

  * type: string

journal_users[][journal][theme]:

  * type: object (JournalTheme)

journal_users[][journal][theme][id]:

  * type: integer

journal_users[][journal][theme][title]:

  * type: string

journal_users[][journal][theme][css]:

  * type: string

journal_users[][journal][theme][public]:

  * type: boolean

journal_users[][journal][theme][journal]:

  * type: object (Journal)

journal_users[][journal][theme][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][theme][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][theme][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][theme][created]:

  * type: DateTime
  * description: @var \DateTime $created

journal_users[][journal][theme][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][design]:

  * type: object (Design)

journal_users[][journal][design][id]:

  * type: integer

journal_users[][journal][design][title]:

  * type: string

journal_users[][journal][design][content]:

  * type: string

journal_users[][journal][design][editable_content]:

  * type: string

journal_users[][journal][design][public]:

  * type: boolean

journal_users[][journal][design][owner]:

  * type: object (Journal)

journal_users[][journal][design][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][design][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][design][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][design][created]:

  * type: DateTime
  * description: @var \DateTime $created

journal_users[][journal][design][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][configured]:

  * type: boolean

journal_users[][journal][articles][]:

  * type: array of objects (Article)

journal_users[][journal][articles][][id]:

  * type: integer
  * description: auto-incremented article unique id

journal_users[][journal][articles][][abstract_transliterated]:

  * type: string
  * description: (optional) English transliterated abstract

journal_users[][journal][articles][][status]:

  * type: integer

journal_users[][journal][articles][][doi]:

  * type: string
  * description: (optional)

journal_users[][journal][articles][][title_transliterated]:

  * type: string
  * description: Roman transliterated title

journal_users[][journal][articles][][anonymous]:

  * type: boolean
  * description: Some articles carries no authorship

journal_users[][journal][articles][][submission_date]:

  * type: DateTime

journal_users[][journal][articles][][pubdate]:

  * type: DateTime

journal_users[][journal][articles][][pubdate_season]:

  * type: string

journal_users[][journal][articles][][first_page]:

  * type: integer

journal_users[][journal][articles][][last_page]:

  * type: integer

journal_users[][journal][articles][][uri]:

  * type: string

journal_users[][journal][articles][][primary_language]:

  * type: string

journal_users[][journal][articles][][order_num]:

  * type: integer

journal_users[][journal][articles][][subjects][]:

  * type: array of objects (Subject)

journal_users[][journal][articles][][subjects][][id]:

  * type: integer

journal_users[][journal][articles][][subjects][][parent]:

  * type: object (Subject)

journal_users[][journal][articles][][subjects][][translations]:

  * type: string

journal_users[][journal][articles][][languages][]:

  * type: array of objects (Lang)

journal_users[][journal][articles][][languages][][id]:

  * type: integer

journal_users[][journal][articles][][languages][][code]:

  * type: string

journal_users[][journal][articles][][languages][][name]:

  * type: string

journal_users[][journal][articles][][languages][][rtl]:

  * type: boolean

journal_users[][journal][articles][][article_type]:

  * type: object (ArticleTypes)

journal_users[][journal][articles][][article_type][id]:

  * type: integer

journal_users[][journal][articles][][article_type][translations][]:

  * type: array of objects (ArticleTypesTranslation)

journal_users[][journal][articles][][article_type][translations][][translatable]:

  * type: object (ArticleTypes)

journal_users[][journal][articles][][article_type][translations][][name]:

  * type: string

journal_users[][journal][articles][][article_type][translations][][description]:

  * type: string

journal_users[][journal][articles][][citations][]:

  * type: array of objects (Citation)

journal_users[][journal][articles][][citations][][id]:

  * type: integer

journal_users[][journal][articles][][citations][][raw]:

  * type: string

journal_users[][journal][articles][][citations][][type]:

  * type: string

journal_users[][journal][articles][][article_authors][]:

  * type: array of objects (ArticleAuthor)

journal_users[][journal][articles][][article_authors][][id]:

  * type: integer

journal_users[][journal][articles][][article_authors][][author_order]:

  * type: integer

journal_users[][journal][articles][][article_authors][][author]:

  * type: object (Author)

journal_users[][journal][articles][][article_authors][][author][id]:

  * type: integer

journal_users[][journal][articles][][article_authors][][author][first_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][middle_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][last_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][email]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][first_name_transliterated]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][middle_name_transliterated]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][last_name_transliterated]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][initials]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][address]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution]:

  * type: object (Institution)

journal_users[][journal][articles][][article_authors][][author][institution][id]:

  * type: integer

journal_users[][journal][articles][][article_authors][][author][institution][name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][address]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][city]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][country]:

  * type: object (Country)

journal_users[][journal][articles][][article_authors][][author][institution][address_lat]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][address_long]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][phone]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][fax]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][email]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][url]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][wiki]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][logo]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][header]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][domain]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][author_details]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user]:

  * type: object (User)

journal_users[][journal][articles][][article_authors][][author][user][username]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][text]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][first_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][last_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][email]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][settings]:

  * type: string
  * description: Json encoded settings string

journal_users[][journal][articles][][article_authors][][author][user][about]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][country]:

  * type: object (Country)

journal_users[][journal][articles][][article_authors][][author][user][journal_users][]:

  * type: array of objects (JournalUser)

journal_users[][journal][articles][][article_authors][][author][orcid]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution_not_listed]:

  * type: boolean

journal_users[][journal][articles][][article_authors][][author][institution_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][article_authors][]:

  * type: array of objects (ArticleAuthor)

journal_users[][journal][articles][][article_authors][][authorOrder]:

  * type: string

journal_users[][journal][articles][][article_files][]:

  * type: array of objects (ArticleFile)

journal_users[][journal][articles][][article_files][][id]:

  * type: integer

journal_users[][journal][articles][][article_files][][type]:

  * type: integer

journal_users[][journal][articles][][article_files][][file]:

  * type: string

journal_users[][journal][articles][][article_files][][version]:

  * type: integer

journal_users[][journal][articles][][article_files][][article]:

  * type: object (Article)

journal_users[][journal][articles][][article_files][][keywords]:

  * type: string

journal_users[][journal][articles][][article_files][][description]:

  * type: string

journal_users[][journal][articles][][article_files][][title]:

  * type: string

journal_users[][journal][articles][][article_files][][lang_code]:

  * type: string

journal_users[][journal][articles][][article_files][][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][articles][][article_files][][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][articles][][article_files][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][articles][][article_files][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][articles][][article_files][][langCode]:

  * type: string

journal_users[][journal][articles][][article_submission_files][]:

  * type: array of objects (ArticleSubmissionFile)

journal_users[][journal][articles][][article_submission_files][][id]:

  * type: integer

journal_users[][journal][articles][][article_submission_files][][title]:

  * type: string

journal_users[][journal][articles][][article_submission_files][][detail]:

  * type: string

journal_users[][journal][articles][][article_submission_files][][visible]:

  * type: boolean

journal_users[][journal][articles][][article_submission_files][][required]:

  * type: boolean

journal_users[][journal][articles][][article_submission_files][][deleted_at]:

  * type: DateTime

journal_users[][journal][articles][][article_submission_files][][article]:

  * type: object (Article)

journal_users[][journal][articles][][article_submission_files][][locale]:

  * type: string

journal_users[][journal][articles][][article_submission_files][][file]:

  * type: string

journal_users[][journal][articles][][view_count]:

  * type: integer

journal_users[][journal][articles][][download_count]:

  * type: integer

journal_users[][journal][articles][][translations]:

  * type: string

journal_users[][journal][articles][][articleFiles]:

  * type: string

journal_users[][journal][articles][][articleAuthors]:

  * type: string

journal_users[][journal][articles][][submissionDate]:

  * type: string

journal_users[][journal][issues][]:

  * type: array of objects (Issue)

journal_users[][journal][issues][][id]:

  * type: integer

journal_users[][journal][issues][][journal]:

  * type: object (Journal)

journal_users[][journal][issues][][volume]:

  * type: string

journal_users[][journal][issues][][number]:

  * type: string

journal_users[][journal][issues][][cover]:

  * type: string

journal_users[][journal][issues][][special]:

  * type: boolean

journal_users[][journal][issues][][year]:

  * type: string

journal_users[][journal][issues][][date_published]:

  * type: DateTime

journal_users[][journal][issues][][articles][]:

  * type: array of objects (Article)

journal_users[][journal][issues][][header]:

  * type: string

journal_users[][journal][issues][][supplement]:

  * type: boolean

journal_users[][journal][issues][][full_file]:

  * type: string

journal_users[][journal][issues][][issue_files][]:

  * type: array of objects (IssueFile)

journal_users[][journal][issues][][issue_files][][translations]:

  * type: string

journal_users[][journal][issues][][issue_files][][file]:

  * type: string

journal_users[][journal][issues][][issue_files][][type]:

  * type: string

journal_users[][journal][issues][][issue_files][][langCode]:

  * type: string

journal_users[][journal][issues][][view_count]:

  * type: integer

journal_users[][journal][issues][][download_count]:

  * type: integer

journal_users[][journal][issues][][translations]:

  * type: string

journal_users[][journal][languages][]:

  * type: array of objects (Lang)

journal_users[][journal][languages][][id]:

  * type: integer

journal_users[][journal][languages][][code]:

  * type: string

journal_users[][journal][languages][][name]:

  * type: string

journal_users[][journal][languages][][rtl]:

  * type: boolean

journal_users[][journal][periods][]:

  * type: array of objects (Period)

journal_users[][journal][periods][][id]:

  * type: integer

journal_users[][journal][periods][][translations][]:

  * type: array of objects (PeriodTranslation)

journal_users[][journal][periods][][translations][][translatable]:

  * type: object (Period)

journal_users[][journal][periods][][translations][][period]:

  * type: string

journal_users[][journal][periods][][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][periods][][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][periods][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][periods][][created]:

  * type: DateTime
  * description: @var \DateTime $created

journal_users[][journal][periods][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][subjects][]:

  * type: array of objects (Subject)

journal_users[][journal][subjects][][id]:

  * type: integer

journal_users[][journal][subjects][][parent]:

  * type: object (Subject)

journal_users[][journal][subjects][][translations]:

  * type: string

journal_users[][journal][publisher]:

  * type: object (Publisher)

journal_users[][journal][publisher][id]:

  * type: integer

journal_users[][journal][publisher][lft]:

  * type: integer

journal_users[][journal][publisher][name]:

  * type: string

journal_users[][journal][publisher][address]:

  * type: string

journal_users[][journal][publisher][city]:

  * type: string

journal_users[][journal][publisher][country]:

  * type: object (Country)

journal_users[][journal][publisher][address_lat]:

  * type: string

journal_users[][journal][publisher][address_long]:

  * type: string

journal_users[][journal][publisher][phone]:

  * type: string

journal_users[][journal][publisher][fax]:

  * type: string

journal_users[][journal][publisher][email]:

  * type: string

journal_users[][journal][publisher][url]:

  * type: string

journal_users[][journal][publisher][wiki]:

  * type: string

journal_users[][journal][publisher][logo]:

  * type: string

journal_users[][journal][publisher][header]:

  * type: string

journal_users[][journal][publisher][domain]:

  * type: string

journal_users[][journal][publisher][publisher_themes][]:

  * type: array of objects (PublisherTheme)

journal_users[][journal][publisher][publisher_themes][][id]:

  * type: integer

journal_users[][journal][publisher][publisher_themes][][title]:

  * type: string

journal_users[][journal][publisher][publisher_themes][][css]:

  * type: string

journal_users[][journal][publisher][publisher_themes][][public]:

  * type: boolean

journal_users[][journal][publisher][publisher_themes][][publisher]:

  * type: object (Publisher)

journal_users[][journal][publisher][publisher_themes][][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][publisher][publisher_themes][][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][publisher][publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][publisher][publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

journal_users[][journal][publisher][publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][publisher][publisher_designs][]:

  * type: array of objects (PublisherDesign)

journal_users[][journal][publisher][publisher_designs][][id]:

  * type: integer

journal_users[][journal][publisher][publisher_designs][][title]:

  * type: string

journal_users[][journal][publisher][publisher_designs][][content]:

  * type: string

journal_users[][journal][publisher][publisher_designs][][editable_content]:

  * type: string

journal_users[][journal][publisher][publisher_designs][][public]:

  * type: boolean

journal_users[][journal][publisher][publisher_designs][][publisher]:

  * type: object (Publisher)

journal_users[][journal][publisher][publisher_designs][][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][publisher][publisher_designs][][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][publisher][publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][publisher][publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

journal_users[][journal][publisher][publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][logo]:

  * type: string

journal_users[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

journal_users[][journal][journal_indexs][][id]:

  * type: integer

journal_users[][journal][journal_indexs][][link]:

  * type: string

journal_users[][journal][journal_indexs][][journal]:

  * type: object (Journal)

journal_users[][journal][journal_indexs][][index]:

  * type: object (Index)

journal_users[][journal][journal_indexs][][index][id]:

  * type: integer

journal_users[][journal][journal_indexs][][index][name]:

  * type: string

journal_users[][journal][journal_indexs][][index][logo]:

  * type: string

journal_users[][journal][journal_indexs][][index][status]:

  * type: boolean

journal_users[][journal][journal_indexs][][verified]:

  * type: boolean

journal_users[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

journal_users[][journal][submission_checklist][][id]:

  * type: integer

journal_users[][journal][submission_checklist][][label]:

  * type: string

journal_users[][journal][submission_checklist][][detail]:

  * type: string

journal_users[][journal][submission_checklist][][visible]:

  * type: boolean

journal_users[][journal][submission_checklist][][deleted_at]:

  * type: DateTime

journal_users[][journal][submission_checklist][][journal]:

  * type: object (Journal)

journal_users[][journal][submission_checklist][][locale]:

  * type: string

journal_users[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

journal_users[][journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

journal_users[][journal][journal_submission_files][][id]:

  * type: integer

journal_users[][journal][journal_submission_files][][title]:

  * type: string

journal_users[][journal][journal_submission_files][][detail]:

  * type: string

journal_users[][journal][journal_submission_files][][visible]:

  * type: boolean

journal_users[][journal][journal_submission_files][][required]:

  * type: boolean

journal_users[][journal][journal_submission_files][][deleted_at]:

  * type: DateTime

journal_users[][journal][journal_submission_files][][locale]:

  * type: string

journal_users[][journal][journal_submission_files][][file]:

  * type: string

journal_users[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

journal_users[][journal][journal_application_upload_files][][id]:

  * type: integer

journal_users[][journal][journal_application_upload_files][][title]:

  * type: string

journal_users[][journal][journal_application_upload_files][][detail]:

  * type: string

journal_users[][journal][journal_application_upload_files][][visible]:

  * type: boolean

journal_users[][journal][journal_application_upload_files][][required]:

  * type: boolean

journal_users[][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

journal_users[][journal][journal_application_upload_files][][locale]:

  * type: string

journal_users[][journal][journal_application_upload_files][][file]:

  * type: string

journal_users[][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

journal_users[][journal][printed]:

  * type: boolean

journal_users[][journal][mandatory_lang]:

  * type: object (Lang)

journal_users[][journal][view_count]:

  * type: integer

journal_users[][journal][download_count]:

  * type: integer

journal_users[][journal][translations]:

  * type: string

journal_users[][journal][mandatoryLang]:

  * type: string

journal_users[][user]:

  * type: object (User)

journal_users[][user][username]:

  * type: string

journal_users[][user][text]:

  * type: string

journal_users[][user][first_name]:

  * type: string

journal_users[][user][last_name]:

  * type: string

journal_users[][user][email]:

  * type: string

journal_users[][user][settings]:

  * type: string
  * description: Json encoded settings string

journal_users[][user][about]:

  * type: string

journal_users[][user][country]:

  * type: object (Country)

journal_users[][user][journal_users][]:

  * type: array of objects (JournalUser)

journal_users[][roles][]:

  * type: array of objects (Role)

journal_users[][roles][][id]:

  * type: integer

journal_users[][roles][][name]:

  * type: string

journal_users[][roles][][role]:

  * type: string


### `PUT` /api/v1/users/{id}.{_format} ###

_Update existing User from the submitted data or create a new User at a specific location._

Update existing User from the submitted data or create a new User at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the User id
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


### `DELETE` /api/v1/announcements/{id}.{_format} ###

_Delete Announcement_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Announcement ID
**_format**

  - Requirement: xml|json|html


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

### `PATCH` /api/v1/articletypes/{id}.{_format} ###

_Update existing articletype from the submitted data or create a new articletype at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the article_type id


### `GET` /api/v1/articletypes/{id}.{_format} ###

_Gets a ArticleType for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the ArticleType id


### `PUT` /api/v1/articletypes/{id}.{_format} ###

_Update existing ArticleType from the submitted data or create a new ArticleType at a specific location._

Update existing ArticleType from the submitted data or create a new ArticleType at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the ArticleType id


### `DELETE` /api/v1/articletypes/{id}.{_format} ###

_Delete ArticleType_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: ArticleType ID
**_format**

  - Requirement: xml|json|html


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


### `DELETE` /api/v1/contacts/{id}.{_format} ###

_Delete Contact_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Contact ID
**_format**

  - Requirement: xml|json|html


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

### `PATCH` /api/v1/contacttypes/{id}.{_format} ###

_Update existing contact type from the submitted data or create a new contact type at a specific location._

Update existing contact type from the submitted data or create a new contact type at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the contact type id


### `GET` /api/v1/contacttypes/{id}.{_format} ###

_Gets a Contact Type for a given id_

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


### `DELETE` /api/v1/contacttypes/{id}.{_format} ###

_Delete Contact Type_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Contact Type ID
**_format**

  - Requirement: xml|json|html


## /api/v1/indexes ##

### `GET` /api/v1/indexes.{_format} ###

_List all Indexs._

List all Indexs.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Indexs.

limit:

  * Requirement: \d+
  * Description: How many Indexs to return.
  * Default: 5


### `POST` /api/v1/indexes.{_format} ###

_Creates a new Index from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/indexes/new ##

### `GET` /api/v1/indexes/new.{_format} ###

_Presents the form to use to create a new Index._

Presents the form to use to create a new Index.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/indexes/{id} ##

### `PATCH` /api/v1/indexes/{id}.{_format} ###

_Update existing index from the submitted data or create a new index at a specific location._

Update existing index from the submitted data or create a new index at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the index id


### `PUT` /api/v1/indexes/{id}.{_format} ###

_Update existing Index from the submitted data or create a new Index at a specific location._

Update existing Index from the submitted data or create a new Index at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Index id


### `DELETE` /api/v1/indexes/{id}.{_format} ###

_Delete Index_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Index ID
**_format**

  - Requirement: xml|json|html


## /api/v1/indexs/{id} ##

### `GET` /api/v1/indexs/{id}.{_format} ###

_Gets a Index for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Index id


## /api/v1/institutions ##

### `GET` /api/v1/institutions.{_format} ###

_List all Institutions._

List all Institutions.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Institutions.

limit:

  * Requirement: \d+
  * Description: How many Institutions to return.
  * Default: 5


### `POST` /api/v1/institutions.{_format} ###

_Creates a new Institution from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/institutions/new ##

### `GET` /api/v1/institutions/new.{_format} ###

_Presents the form to use to create a new Institution._

Presents the form to use to create a new Institution.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/institutions/{id} ##

### `GET` /api/v1/institutions/{id}.{_format} ###

_Gets a Institution for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Institution id


### `PATCH` /api/v1/institutions/{id}.{_format} ###

_Update existing institution from the submitted data or create a new institution at a specific location._

Update existing institution from the submitted data or create a new institution at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the institution id


### `PUT` /api/v1/institutions/{id}.{_format} ###

_Update existing Institution from the submitted data or create a new Institution at a specific location._

Update existing Institution from the submitted data or create a new Institution at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Institution id


### `DELETE` /api/v1/institutions/{id}.{_format} ###

_Delete Institution_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Institution ID
**_format**

  - Requirement: xml|json|html


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


## /api/v1/journal/{journalId}/sections ##

### `GET` /api/v1/journal/{journalId}/sections.{_format} ###

_List all Sections._

List all Sections.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Sections.

limit:

  * Requirement: \d+
  * Description: How many Sections to return.
  * Default: 5


### `POST` /api/v1/journal/{journalId}/sections.{_format} ###

_Creates a new Section from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/sections/new ##

### `GET` /api/v1/journal/{journalId}/sections/new.{_format} ###

_Presents the form to use to create a new Section._

Presents the form to use to create a new Section.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/sections/{id} ##

### `PATCH` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Update existing journalsection from the submitted data or create a new journalsection at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the journal_section id


### `GET` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Gets a Section for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Section id


### `PUT` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Update existing Section from the submitted data or create a new Section at a specific location._

Update existing Section from the submitted data or create a new Section at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Section id


### `DELETE` /api/v1/journal/{journalId}/sections/{id}.{_format} ###

_Delete Section_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Section ID
**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/themes ##

### `GET` /api/v1/journal/{journalId}/themes.{_format} ###

_List all Themes._

List all Themes.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Themes.

limit:

  * Requirement: \d+
  * Description: How many Themes to return.
  * Default: 5


### `POST` /api/v1/journal/{journalId}/themes.{_format} ###

_Creates a new Theme from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/themes/new ##

### `GET` /api/v1/journal/{journalId}/themes/new.{_format} ###

_Presents the form to use to create a new Theme._

Presents the form to use to create a new Theme.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journal/{journalId}/themes/{id} ##

### `PATCH` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Update existing journaltheme from the submitted data or create a new journaltheme at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the journal_theme id


### `GET` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Gets a Theme for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Theme id


### `PUT` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Update existing Theme from the submitted data or create a new Theme at a specific location._

Update existing Theme from the submitted data or create a new Theme at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+
**id**

  - Type: int
  - Description: the Theme id


### `DELETE` /api/v1/journal/{journalId}/themes/{id}.{_format} ###

_Delete Theme_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Theme ID
**_format**

  - Requirement: xml|json|html
**journalId**

  - Requirement: \d+


## /api/v1/journals ##

### `GET` /api/v1/journals.{_format} ###

_List all Journals._

List all Journals.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Journals.

limit:

  * Requirement: \d+
  * Description: How many Journals to return.
  * Default: 5


### `POST` /api/v1/journals.{_format} ###

_Creates a new Journal from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/journals/new ##

### `GET` /api/v1/journals/new.{_format} ###

_Presents the form to use to create a new Journal._

Presents the form to use to create a new Journal.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/journals/{id} ##

### `PATCH` /api/v1/journals/{id}.{_format} ###

_Update existing journal from the submitted data or create a new journal at a specific location._

Update existing journal from the submitted data or create a new journal at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the journal id


### `GET` /api/v1/journals/{id}.{_format} ###

_Gets a Journal for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Journal id

#### Response ####

translations:

  * type: string

mandatoryLang:

  * type: string

languages[]:

  * type: array of objects (Lang)

languages[][id]:

  * type: integer

languages[][code]:

  * type: string

languages[][name]:

  * type: string

languages[][rtl]:

  * type: boolean

issn:

  * type: string

eissn:

  * type: string

founded:

  * type: DateTime

id:

  * type: integer

title_transliterated:

  * type: string

path:

  * type: string

domain:

  * type: string

url:

  * type: string

address:

  * type: string

phone:

  * type: string

email:

  * type: string

country:

  * type: object (Country)

country[id]:

  * type: integer

country[name]:

  * type: string

published:

  * type: boolean

status:

  * type: integer

image:

  * type: string

header:

  * type: string

google_analytics_id:

  * type: string

slug:

  * type: string

theme:

  * type: object (JournalTheme)

theme[id]:

  * type: integer

theme[title]:

  * type: string

theme[css]:

  * type: string

theme[public]:

  * type: boolean

theme[journal]:

  * type: object (Journal)

theme[journal][id]:

  * type: integer

theme[journal][title_transliterated]:

  * type: string

theme[journal][path]:

  * type: string

theme[journal][domain]:

  * type: string

theme[journal][issn]:

  * type: string

theme[journal][eissn]:

  * type: string

theme[journal][founded]:

  * type: DateTime

theme[journal][url]:

  * type: string

theme[journal][address]:

  * type: string

theme[journal][phone]:

  * type: string

theme[journal][email]:

  * type: string

theme[journal][country]:

  * type: object (Country)

theme[journal][published]:

  * type: boolean

theme[journal][status]:

  * type: integer

theme[journal][image]:

  * type: string

theme[journal][header]:

  * type: string

theme[journal][google_analytics_id]:

  * type: string

theme[journal][slug]:

  * type: string

theme[journal][theme]:

  * type: object (JournalTheme)

theme[journal][design]:

  * type: object (Design)

theme[journal][design][id]:

  * type: integer

theme[journal][design][title]:

  * type: string

theme[journal][design][content]:

  * type: string

theme[journal][design][editable_content]:

  * type: string

theme[journal][design][public]:

  * type: boolean

theme[journal][design][owner]:

  * type: object (Journal)

theme[journal][design][created_by]:

  * type: string
  * description: @var string

theme[journal][design][updated_by]:

  * type: string
  * description: @var string

theme[journal][design][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[journal][design][created]:

  * type: DateTime
  * description: @var \DateTime $created

theme[journal][design][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

theme[journal][configured]:

  * type: boolean

theme[journal][articles][]:

  * type: array of objects (Article)

theme[journal][articles][][id]:

  * type: integer
  * description: auto-incremented article unique id

theme[journal][articles][][abstract_transliterated]:

  * type: string
  * description: (optional) English transliterated abstract

theme[journal][articles][][status]:

  * type: integer

theme[journal][articles][][doi]:

  * type: string
  * description: (optional)

theme[journal][articles][][title_transliterated]:

  * type: string
  * description: Roman transliterated title

theme[journal][articles][][anonymous]:

  * type: boolean
  * description: Some articles carries no authorship

theme[journal][articles][][submission_date]:

  * type: DateTime

theme[journal][articles][][pubdate]:

  * type: DateTime

theme[journal][articles][][pubdate_season]:

  * type: string

theme[journal][articles][][first_page]:

  * type: integer

theme[journal][articles][][last_page]:

  * type: integer

theme[journal][articles][][uri]:

  * type: string

theme[journal][articles][][primary_language]:

  * type: string

theme[journal][articles][][order_num]:

  * type: integer

theme[journal][articles][][subjects][]:

  * type: array of objects (Subject)

theme[journal][articles][][subjects][][id]:

  * type: integer

theme[journal][articles][][subjects][][parent]:

  * type: object (Subject)

theme[journal][articles][][subjects][][translations]:

  * type: string

theme[journal][articles][][languages][]:

  * type: array of objects (Lang)

theme[journal][articles][][languages][][id]:

  * type: integer

theme[journal][articles][][languages][][code]:

  * type: string

theme[journal][articles][][languages][][name]:

  * type: string

theme[journal][articles][][languages][][rtl]:

  * type: boolean

theme[journal][articles][][article_type]:

  * type: object (ArticleTypes)

theme[journal][articles][][article_type][id]:

  * type: integer

theme[journal][articles][][article_type][translations][]:

  * type: array of objects (ArticleTypesTranslation)

theme[journal][articles][][article_type][translations][][translatable]:

  * type: object (ArticleTypes)

theme[journal][articles][][article_type][translations][][name]:

  * type: string

theme[journal][articles][][article_type][translations][][description]:

  * type: string

theme[journal][articles][][citations][]:

  * type: array of objects (Citation)

theme[journal][articles][][citations][][id]:

  * type: integer

theme[journal][articles][][citations][][raw]:

  * type: string

theme[journal][articles][][citations][][type]:

  * type: string

theme[journal][articles][][article_authors][]:

  * type: array of objects (ArticleAuthor)

theme[journal][articles][][article_authors][][id]:

  * type: integer

theme[journal][articles][][article_authors][][author_order]:

  * type: integer

theme[journal][articles][][article_authors][][author]:

  * type: object (Author)

theme[journal][articles][][article_authors][][author][id]:

  * type: integer

theme[journal][articles][][article_authors][][author][first_name]:

  * type: string

theme[journal][articles][][article_authors][][author][middle_name]:

  * type: string

theme[journal][articles][][article_authors][][author][last_name]:

  * type: string

theme[journal][articles][][article_authors][][author][email]:

  * type: string

theme[journal][articles][][article_authors][][author][first_name_transliterated]:

  * type: string

theme[journal][articles][][article_authors][][author][middle_name_transliterated]:

  * type: string

theme[journal][articles][][article_authors][][author][last_name_transliterated]:

  * type: string

theme[journal][articles][][article_authors][][author][initials]:

  * type: string

theme[journal][articles][][article_authors][][author][address]:

  * type: string

theme[journal][articles][][article_authors][][author][institution]:

  * type: object (Institution)

theme[journal][articles][][article_authors][][author][institution][id]:

  * type: integer

theme[journal][articles][][article_authors][][author][institution][name]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][address]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][city]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][country]:

  * type: object (Country)

theme[journal][articles][][article_authors][][author][institution][address_lat]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][address_long]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][phone]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][fax]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][email]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][url]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][wiki]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][logo]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][header]:

  * type: string

theme[journal][articles][][article_authors][][author][institution][domain]:

  * type: string

theme[journal][articles][][article_authors][][author][author_details]:

  * type: string

theme[journal][articles][][article_authors][][author][user]:

  * type: object (User)

theme[journal][articles][][article_authors][][author][user][username]:

  * type: string

theme[journal][articles][][article_authors][][author][user][text]:

  * type: string

theme[journal][articles][][article_authors][][author][user][first_name]:

  * type: string

theme[journal][articles][][article_authors][][author][user][last_name]:

  * type: string

theme[journal][articles][][article_authors][][author][user][email]:

  * type: string

theme[journal][articles][][article_authors][][author][user][settings]:

  * type: string
  * description: Json encoded settings string

theme[journal][articles][][article_authors][][author][user][about]:

  * type: string

theme[journal][articles][][article_authors][][author][user][country]:

  * type: object (Country)

theme[journal][articles][][article_authors][][author][user][journal_users][]:

  * type: array of objects (JournalUser)

theme[journal][articles][][article_authors][][author][user][journal_users][][id]:

  * type: integer

theme[journal][articles][][article_authors][][author][user][journal_users][][journal]:

  * type: object (Journal)

theme[journal][articles][][article_authors][][author][user][journal_users][][user]:

  * type: object (User)

theme[journal][articles][][article_authors][][author][user][journal_users][][roles][]:

  * type: array of objects (Role)

theme[journal][articles][][article_authors][][author][user][journal_users][][roles][][id]:

  * type: integer

theme[journal][articles][][article_authors][][author][user][journal_users][][roles][][name]:

  * type: string

theme[journal][articles][][article_authors][][author][user][journal_users][][roles][][role]:

  * type: string

theme[journal][articles][][article_authors][][author][orcid]:

  * type: string

theme[journal][articles][][article_authors][][author][institution_not_listed]:

  * type: boolean

theme[journal][articles][][article_authors][][author][institution_name]:

  * type: string

theme[journal][articles][][article_authors][][author][article_authors][]:

  * type: array of objects (ArticleAuthor)

theme[journal][articles][][article_authors][][authorOrder]:

  * type: string

theme[journal][articles][][article_files][]:

  * type: array of objects (ArticleFile)

theme[journal][articles][][article_files][][id]:

  * type: integer

theme[journal][articles][][article_files][][type]:

  * type: integer

theme[journal][articles][][article_files][][file]:

  * type: string

theme[journal][articles][][article_files][][version]:

  * type: integer

theme[journal][articles][][article_files][][article]:

  * type: object (Article)

theme[journal][articles][][article_files][][keywords]:

  * type: string

theme[journal][articles][][article_files][][description]:

  * type: string

theme[journal][articles][][article_files][][title]:

  * type: string

theme[journal][articles][][article_files][][lang_code]:

  * type: string

theme[journal][articles][][article_files][][created_by]:

  * type: string
  * description: @var string

theme[journal][articles][][article_files][][updated_by]:

  * type: string
  * description: @var string

theme[journal][articles][][article_files][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[journal][articles][][article_files][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

theme[journal][articles][][article_files][][langCode]:

  * type: string

theme[journal][articles][][article_submission_files][]:

  * type: array of objects (ArticleSubmissionFile)

theme[journal][articles][][article_submission_files][][id]:

  * type: integer

theme[journal][articles][][article_submission_files][][title]:

  * type: string

theme[journal][articles][][article_submission_files][][detail]:

  * type: string

theme[journal][articles][][article_submission_files][][visible]:

  * type: boolean

theme[journal][articles][][article_submission_files][][required]:

  * type: boolean

theme[journal][articles][][article_submission_files][][deleted_at]:

  * type: DateTime

theme[journal][articles][][article_submission_files][][article]:

  * type: object (Article)

theme[journal][articles][][article_submission_files][][locale]:

  * type: string

theme[journal][articles][][article_submission_files][][file]:

  * type: string

theme[journal][articles][][view_count]:

  * type: integer

theme[journal][articles][][download_count]:

  * type: integer

theme[journal][articles][][translations]:

  * type: string

theme[journal][articles][][articleFiles]:

  * type: string

theme[journal][articles][][articleAuthors]:

  * type: string

theme[journal][articles][][submissionDate]:

  * type: string

theme[journal][issues][]:

  * type: array of objects (Issue)

theme[journal][issues][][id]:

  * type: integer

theme[journal][issues][][journal]:

  * type: object (Journal)

theme[journal][issues][][volume]:

  * type: string

theme[journal][issues][][number]:

  * type: string

theme[journal][issues][][cover]:

  * type: string

theme[journal][issues][][special]:

  * type: boolean

theme[journal][issues][][year]:

  * type: string

theme[journal][issues][][date_published]:

  * type: DateTime

theme[journal][issues][][articles][]:

  * type: array of objects (Article)

theme[journal][issues][][header]:

  * type: string

theme[journal][issues][][supplement]:

  * type: boolean

theme[journal][issues][][full_file]:

  * type: string

theme[journal][issues][][issue_files][]:

  * type: array of objects (IssueFile)

theme[journal][issues][][issue_files][][translations]:

  * type: string

theme[journal][issues][][issue_files][][file]:

  * type: string

theme[journal][issues][][issue_files][][type]:

  * type: string

theme[journal][issues][][issue_files][][langCode]:

  * type: string

theme[journal][issues][][view_count]:

  * type: integer

theme[journal][issues][][download_count]:

  * type: integer

theme[journal][issues][][translations]:

  * type: string

theme[journal][languages][]:

  * type: array of objects (Lang)

theme[journal][languages][][id]:

  * type: integer

theme[journal][languages][][code]:

  * type: string

theme[journal][languages][][name]:

  * type: string

theme[journal][languages][][rtl]:

  * type: boolean

theme[journal][periods][]:

  * type: array of objects (Period)

theme[journal][periods][][id]:

  * type: integer

theme[journal][periods][][translations][]:

  * type: array of objects (PeriodTranslation)

theme[journal][periods][][translations][][translatable]:

  * type: object (Period)

theme[journal][periods][][translations][][period]:

  * type: string

theme[journal][periods][][created_by]:

  * type: string
  * description: @var string

theme[journal][periods][][updated_by]:

  * type: string
  * description: @var string

theme[journal][periods][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[journal][periods][][created]:

  * type: DateTime
  * description: @var \DateTime $created

theme[journal][periods][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

theme[journal][subjects][]:

  * type: array of objects (Subject)

theme[journal][subjects][][id]:

  * type: integer

theme[journal][subjects][][parent]:

  * type: object (Subject)

theme[journal][subjects][][translations]:

  * type: string

theme[journal][publisher]:

  * type: object (Publisher)

theme[journal][publisher][id]:

  * type: integer

theme[journal][publisher][lft]:

  * type: integer

theme[journal][publisher][name]:

  * type: string

theme[journal][publisher][address]:

  * type: string

theme[journal][publisher][city]:

  * type: string

theme[journal][publisher][country]:

  * type: object (Country)

theme[journal][publisher][address_lat]:

  * type: string

theme[journal][publisher][address_long]:

  * type: string

theme[journal][publisher][phone]:

  * type: string

theme[journal][publisher][fax]:

  * type: string

theme[journal][publisher][email]:

  * type: string

theme[journal][publisher][url]:

  * type: string

theme[journal][publisher][wiki]:

  * type: string

theme[journal][publisher][logo]:

  * type: string

theme[journal][publisher][header]:

  * type: string

theme[journal][publisher][domain]:

  * type: string

theme[journal][publisher][publisher_themes][]:

  * type: array of objects (PublisherTheme)

theme[journal][publisher][publisher_themes][][id]:

  * type: integer

theme[journal][publisher][publisher_themes][][title]:

  * type: string

theme[journal][publisher][publisher_themes][][css]:

  * type: string

theme[journal][publisher][publisher_themes][][public]:

  * type: boolean

theme[journal][publisher][publisher_themes][][publisher]:

  * type: object (Publisher)

theme[journal][publisher][publisher_themes][][created_by]:

  * type: string
  * description: @var string

theme[journal][publisher][publisher_themes][][updated_by]:

  * type: string
  * description: @var string

theme[journal][publisher][publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[journal][publisher][publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

theme[journal][publisher][publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

theme[journal][publisher][publisher_designs][]:

  * type: array of objects (PublisherDesign)

theme[journal][publisher][publisher_designs][][id]:

  * type: integer

theme[journal][publisher][publisher_designs][][title]:

  * type: string

theme[journal][publisher][publisher_designs][][content]:

  * type: string

theme[journal][publisher][publisher_designs][][editable_content]:

  * type: string

theme[journal][publisher][publisher_designs][][public]:

  * type: boolean

theme[journal][publisher][publisher_designs][][publisher]:

  * type: object (Publisher)

theme[journal][publisher][publisher_designs][][created_by]:

  * type: string
  * description: @var string

theme[journal][publisher][publisher_designs][][updated_by]:

  * type: string
  * description: @var string

theme[journal][publisher][publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[journal][publisher][publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

theme[journal][publisher][publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

theme[journal][logo]:

  * type: string

theme[journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

theme[journal][journal_indexs][][id]:

  * type: integer

theme[journal][journal_indexs][][link]:

  * type: string

theme[journal][journal_indexs][][journal]:

  * type: object (Journal)

theme[journal][journal_indexs][][index]:

  * type: object (Index)

theme[journal][journal_indexs][][index][id]:

  * type: integer

theme[journal][journal_indexs][][index][name]:

  * type: string

theme[journal][journal_indexs][][index][logo]:

  * type: string

theme[journal][journal_indexs][][index][status]:

  * type: boolean

theme[journal][journal_indexs][][verified]:

  * type: boolean

theme[journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

theme[journal][submission_checklist][][id]:

  * type: integer

theme[journal][submission_checklist][][label]:

  * type: string

theme[journal][submission_checklist][][detail]:

  * type: string

theme[journal][submission_checklist][][visible]:

  * type: boolean

theme[journal][submission_checklist][][deleted_at]:

  * type: DateTime

theme[journal][submission_checklist][][journal]:

  * type: object (Journal)

theme[journal][submission_checklist][][locale]:

  * type: string

theme[journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

theme[journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

theme[journal][journal_submission_files][][id]:

  * type: integer

theme[journal][journal_submission_files][][title]:

  * type: string

theme[journal][journal_submission_files][][detail]:

  * type: string

theme[journal][journal_submission_files][][visible]:

  * type: boolean

theme[journal][journal_submission_files][][required]:

  * type: boolean

theme[journal][journal_submission_files][][deleted_at]:

  * type: DateTime

theme[journal][journal_submission_files][][locale]:

  * type: string

theme[journal][journal_submission_files][][file]:

  * type: string

theme[journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

theme[journal][journal_application_upload_files][][id]:

  * type: integer

theme[journal][journal_application_upload_files][][title]:

  * type: string

theme[journal][journal_application_upload_files][][detail]:

  * type: string

theme[journal][journal_application_upload_files][][visible]:

  * type: boolean

theme[journal][journal_application_upload_files][][required]:

  * type: boolean

theme[journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

theme[journal][journal_application_upload_files][][locale]:

  * type: string

theme[journal][journal_application_upload_files][][file]:

  * type: string

theme[journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

theme[journal][printed]:

  * type: boolean

theme[journal][mandatory_lang]:

  * type: object (Lang)

theme[journal][view_count]:

  * type: integer

theme[journal][download_count]:

  * type: integer

theme[journal][translations]:

  * type: string

theme[journal][mandatoryLang]:

  * type: string

theme[created_by]:

  * type: string
  * description: @var string

theme[updated_by]:

  * type: string
  * description: @var string

theme[deleted_at]:

  * type: DateTime
  * description: @var \DateTime

theme[created]:

  * type: DateTime
  * description: @var \DateTime $created

theme[updated]:

  * type: DateTime
  * description: @var \DateTime $updated

design:

  * type: object (Design)

design[id]:

  * type: integer

design[title]:

  * type: string

design[content]:

  * type: string

design[editable_content]:

  * type: string

design[public]:

  * type: boolean

design[owner]:

  * type: object (Journal)

design[owner][id]:

  * type: integer

design[owner][title_transliterated]:

  * type: string

design[owner][path]:

  * type: string

design[owner][domain]:

  * type: string

design[owner][issn]:

  * type: string

design[owner][eissn]:

  * type: string

design[owner][founded]:

  * type: DateTime

design[owner][url]:

  * type: string

design[owner][address]:

  * type: string

design[owner][phone]:

  * type: string

design[owner][email]:

  * type: string

design[owner][country]:

  * type: object (Country)

design[owner][published]:

  * type: boolean

design[owner][status]:

  * type: integer

design[owner][image]:

  * type: string

design[owner][header]:

  * type: string

design[owner][google_analytics_id]:

  * type: string

design[owner][slug]:

  * type: string

design[owner][theme]:

  * type: object (JournalTheme)

design[owner][design]:

  * type: object (Design)

design[owner][configured]:

  * type: boolean

design[owner][articles][]:

  * type: array of objects (Article)

design[owner][articles][][id]:

  * type: integer
  * description: auto-incremented article unique id

design[owner][articles][][abstract_transliterated]:

  * type: string
  * description: (optional) English transliterated abstract

design[owner][articles][][status]:

  * type: integer

design[owner][articles][][doi]:

  * type: string
  * description: (optional)

design[owner][articles][][title_transliterated]:

  * type: string
  * description: Roman transliterated title

design[owner][articles][][anonymous]:

  * type: boolean
  * description: Some articles carries no authorship

design[owner][articles][][submission_date]:

  * type: DateTime

design[owner][articles][][pubdate]:

  * type: DateTime

design[owner][articles][][pubdate_season]:

  * type: string

design[owner][articles][][first_page]:

  * type: integer

design[owner][articles][][last_page]:

  * type: integer

design[owner][articles][][uri]:

  * type: string

design[owner][articles][][primary_language]:

  * type: string

design[owner][articles][][order_num]:

  * type: integer

design[owner][articles][][subjects][]:

  * type: array of objects (Subject)

design[owner][articles][][subjects][][id]:

  * type: integer

design[owner][articles][][subjects][][parent]:

  * type: object (Subject)

design[owner][articles][][subjects][][translations]:

  * type: string

design[owner][articles][][languages][]:

  * type: array of objects (Lang)

design[owner][articles][][languages][][id]:

  * type: integer

design[owner][articles][][languages][][code]:

  * type: string

design[owner][articles][][languages][][name]:

  * type: string

design[owner][articles][][languages][][rtl]:

  * type: boolean

design[owner][articles][][article_type]:

  * type: object (ArticleTypes)

design[owner][articles][][article_type][id]:

  * type: integer

design[owner][articles][][article_type][translations][]:

  * type: array of objects (ArticleTypesTranslation)

design[owner][articles][][article_type][translations][][translatable]:

  * type: object (ArticleTypes)

design[owner][articles][][article_type][translations][][name]:

  * type: string

design[owner][articles][][article_type][translations][][description]:

  * type: string

design[owner][articles][][citations][]:

  * type: array of objects (Citation)

design[owner][articles][][citations][][id]:

  * type: integer

design[owner][articles][][citations][][raw]:

  * type: string

design[owner][articles][][citations][][type]:

  * type: string

design[owner][articles][][article_authors][]:

  * type: array of objects (ArticleAuthor)

design[owner][articles][][article_authors][][id]:

  * type: integer

design[owner][articles][][article_authors][][author_order]:

  * type: integer

design[owner][articles][][article_authors][][author]:

  * type: object (Author)

design[owner][articles][][article_authors][][author][id]:

  * type: integer

design[owner][articles][][article_authors][][author][first_name]:

  * type: string

design[owner][articles][][article_authors][][author][middle_name]:

  * type: string

design[owner][articles][][article_authors][][author][last_name]:

  * type: string

design[owner][articles][][article_authors][][author][email]:

  * type: string

design[owner][articles][][article_authors][][author][first_name_transliterated]:

  * type: string

design[owner][articles][][article_authors][][author][middle_name_transliterated]:

  * type: string

design[owner][articles][][article_authors][][author][last_name_transliterated]:

  * type: string

design[owner][articles][][article_authors][][author][initials]:

  * type: string

design[owner][articles][][article_authors][][author][address]:

  * type: string

design[owner][articles][][article_authors][][author][institution]:

  * type: object (Institution)

design[owner][articles][][article_authors][][author][institution][id]:

  * type: integer

design[owner][articles][][article_authors][][author][institution][name]:

  * type: string

design[owner][articles][][article_authors][][author][institution][address]:

  * type: string

design[owner][articles][][article_authors][][author][institution][city]:

  * type: string

design[owner][articles][][article_authors][][author][institution][country]:

  * type: object (Country)

design[owner][articles][][article_authors][][author][institution][address_lat]:

  * type: string

design[owner][articles][][article_authors][][author][institution][address_long]:

  * type: string

design[owner][articles][][article_authors][][author][institution][phone]:

  * type: string

design[owner][articles][][article_authors][][author][institution][fax]:

  * type: string

design[owner][articles][][article_authors][][author][institution][email]:

  * type: string

design[owner][articles][][article_authors][][author][institution][url]:

  * type: string

design[owner][articles][][article_authors][][author][institution][wiki]:

  * type: string

design[owner][articles][][article_authors][][author][institution][logo]:

  * type: string

design[owner][articles][][article_authors][][author][institution][header]:

  * type: string

design[owner][articles][][article_authors][][author][institution][domain]:

  * type: string

design[owner][articles][][article_authors][][author][author_details]:

  * type: string

design[owner][articles][][article_authors][][author][user]:

  * type: object (User)

design[owner][articles][][article_authors][][author][user][username]:

  * type: string

design[owner][articles][][article_authors][][author][user][text]:

  * type: string

design[owner][articles][][article_authors][][author][user][first_name]:

  * type: string

design[owner][articles][][article_authors][][author][user][last_name]:

  * type: string

design[owner][articles][][article_authors][][author][user][email]:

  * type: string

design[owner][articles][][article_authors][][author][user][settings]:

  * type: string
  * description: Json encoded settings string

design[owner][articles][][article_authors][][author][user][about]:

  * type: string

design[owner][articles][][article_authors][][author][user][country]:

  * type: object (Country)

design[owner][articles][][article_authors][][author][user][journal_users][]:

  * type: array of objects (JournalUser)

design[owner][articles][][article_authors][][author][user][journal_users][][id]:

  * type: integer

design[owner][articles][][article_authors][][author][user][journal_users][][journal]:

  * type: object (Journal)

design[owner][articles][][article_authors][][author][user][journal_users][][user]:

  * type: object (User)

design[owner][articles][][article_authors][][author][user][journal_users][][roles][]:

  * type: array of objects (Role)

design[owner][articles][][article_authors][][author][user][journal_users][][roles][][id]:

  * type: integer

design[owner][articles][][article_authors][][author][user][journal_users][][roles][][name]:

  * type: string

design[owner][articles][][article_authors][][author][user][journal_users][][roles][][role]:

  * type: string

design[owner][articles][][article_authors][][author][orcid]:

  * type: string

design[owner][articles][][article_authors][][author][institution_not_listed]:

  * type: boolean

design[owner][articles][][article_authors][][author][institution_name]:

  * type: string

design[owner][articles][][article_authors][][author][article_authors][]:

  * type: array of objects (ArticleAuthor)

design[owner][articles][][article_authors][][authorOrder]:

  * type: string

design[owner][articles][][article_files][]:

  * type: array of objects (ArticleFile)

design[owner][articles][][article_files][][id]:

  * type: integer

design[owner][articles][][article_files][][type]:

  * type: integer

design[owner][articles][][article_files][][file]:

  * type: string

design[owner][articles][][article_files][][version]:

  * type: integer

design[owner][articles][][article_files][][article]:

  * type: object (Article)

design[owner][articles][][article_files][][keywords]:

  * type: string

design[owner][articles][][article_files][][description]:

  * type: string

design[owner][articles][][article_files][][title]:

  * type: string

design[owner][articles][][article_files][][lang_code]:

  * type: string

design[owner][articles][][article_files][][created_by]:

  * type: string
  * description: @var string

design[owner][articles][][article_files][][updated_by]:

  * type: string
  * description: @var string

design[owner][articles][][article_files][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

design[owner][articles][][article_files][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

design[owner][articles][][article_files][][langCode]:

  * type: string

design[owner][articles][][article_submission_files][]:

  * type: array of objects (ArticleSubmissionFile)

design[owner][articles][][article_submission_files][][id]:

  * type: integer

design[owner][articles][][article_submission_files][][title]:

  * type: string

design[owner][articles][][article_submission_files][][detail]:

  * type: string

design[owner][articles][][article_submission_files][][visible]:

  * type: boolean

design[owner][articles][][article_submission_files][][required]:

  * type: boolean

design[owner][articles][][article_submission_files][][deleted_at]:

  * type: DateTime

design[owner][articles][][article_submission_files][][article]:

  * type: object (Article)

design[owner][articles][][article_submission_files][][locale]:

  * type: string

design[owner][articles][][article_submission_files][][file]:

  * type: string

design[owner][articles][][view_count]:

  * type: integer

design[owner][articles][][download_count]:

  * type: integer

design[owner][articles][][translations]:

  * type: string

design[owner][articles][][articleFiles]:

  * type: string

design[owner][articles][][articleAuthors]:

  * type: string

design[owner][articles][][submissionDate]:

  * type: string

design[owner][issues][]:

  * type: array of objects (Issue)

design[owner][issues][][id]:

  * type: integer

design[owner][issues][][journal]:

  * type: object (Journal)

design[owner][issues][][volume]:

  * type: string

design[owner][issues][][number]:

  * type: string

design[owner][issues][][cover]:

  * type: string

design[owner][issues][][special]:

  * type: boolean

design[owner][issues][][year]:

  * type: string

design[owner][issues][][date_published]:

  * type: DateTime

design[owner][issues][][articles][]:

  * type: array of objects (Article)

design[owner][issues][][header]:

  * type: string

design[owner][issues][][supplement]:

  * type: boolean

design[owner][issues][][full_file]:

  * type: string

design[owner][issues][][issue_files][]:

  * type: array of objects (IssueFile)

design[owner][issues][][issue_files][][translations]:

  * type: string

design[owner][issues][][issue_files][][file]:

  * type: string

design[owner][issues][][issue_files][][type]:

  * type: string

design[owner][issues][][issue_files][][langCode]:

  * type: string

design[owner][issues][][view_count]:

  * type: integer

design[owner][issues][][download_count]:

  * type: integer

design[owner][issues][][translations]:

  * type: string

design[owner][languages][]:

  * type: array of objects (Lang)

design[owner][languages][][id]:

  * type: integer

design[owner][languages][][code]:

  * type: string

design[owner][languages][][name]:

  * type: string

design[owner][languages][][rtl]:

  * type: boolean

design[owner][periods][]:

  * type: array of objects (Period)

design[owner][periods][][id]:

  * type: integer

design[owner][periods][][translations][]:

  * type: array of objects (PeriodTranslation)

design[owner][periods][][translations][][translatable]:

  * type: object (Period)

design[owner][periods][][translations][][period]:

  * type: string

design[owner][periods][][created_by]:

  * type: string
  * description: @var string

design[owner][periods][][updated_by]:

  * type: string
  * description: @var string

design[owner][periods][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

design[owner][periods][][created]:

  * type: DateTime
  * description: @var \DateTime $created

design[owner][periods][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

design[owner][subjects][]:

  * type: array of objects (Subject)

design[owner][subjects][][id]:

  * type: integer

design[owner][subjects][][parent]:

  * type: object (Subject)

design[owner][subjects][][translations]:

  * type: string

design[owner][publisher]:

  * type: object (Publisher)

design[owner][publisher][id]:

  * type: integer

design[owner][publisher][lft]:

  * type: integer

design[owner][publisher][name]:

  * type: string

design[owner][publisher][address]:

  * type: string

design[owner][publisher][city]:

  * type: string

design[owner][publisher][country]:

  * type: object (Country)

design[owner][publisher][address_lat]:

  * type: string

design[owner][publisher][address_long]:

  * type: string

design[owner][publisher][phone]:

  * type: string

design[owner][publisher][fax]:

  * type: string

design[owner][publisher][email]:

  * type: string

design[owner][publisher][url]:

  * type: string

design[owner][publisher][wiki]:

  * type: string

design[owner][publisher][logo]:

  * type: string

design[owner][publisher][header]:

  * type: string

design[owner][publisher][domain]:

  * type: string

design[owner][publisher][publisher_themes][]:

  * type: array of objects (PublisherTheme)

design[owner][publisher][publisher_themes][][id]:

  * type: integer

design[owner][publisher][publisher_themes][][title]:

  * type: string

design[owner][publisher][publisher_themes][][css]:

  * type: string

design[owner][publisher][publisher_themes][][public]:

  * type: boolean

design[owner][publisher][publisher_themes][][publisher]:

  * type: object (Publisher)

design[owner][publisher][publisher_themes][][created_by]:

  * type: string
  * description: @var string

design[owner][publisher][publisher_themes][][updated_by]:

  * type: string
  * description: @var string

design[owner][publisher][publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

design[owner][publisher][publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

design[owner][publisher][publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

design[owner][publisher][publisher_designs][]:

  * type: array of objects (PublisherDesign)

design[owner][publisher][publisher_designs][][id]:

  * type: integer

design[owner][publisher][publisher_designs][][title]:

  * type: string

design[owner][publisher][publisher_designs][][content]:

  * type: string

design[owner][publisher][publisher_designs][][editable_content]:

  * type: string

design[owner][publisher][publisher_designs][][public]:

  * type: boolean

design[owner][publisher][publisher_designs][][publisher]:

  * type: object (Publisher)

design[owner][publisher][publisher_designs][][created_by]:

  * type: string
  * description: @var string

design[owner][publisher][publisher_designs][][updated_by]:

  * type: string
  * description: @var string

design[owner][publisher][publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

design[owner][publisher][publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

design[owner][publisher][publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

design[owner][logo]:

  * type: string

design[owner][journal_indexs][]:

  * type: array of objects (JournalIndex)

design[owner][journal_indexs][][id]:

  * type: integer

design[owner][journal_indexs][][link]:

  * type: string

design[owner][journal_indexs][][journal]:

  * type: object (Journal)

design[owner][journal_indexs][][index]:

  * type: object (Index)

design[owner][journal_indexs][][index][id]:

  * type: integer

design[owner][journal_indexs][][index][name]:

  * type: string

design[owner][journal_indexs][][index][logo]:

  * type: string

design[owner][journal_indexs][][index][status]:

  * type: boolean

design[owner][journal_indexs][][verified]:

  * type: boolean

design[owner][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

design[owner][submission_checklist][][id]:

  * type: integer

design[owner][submission_checklist][][label]:

  * type: string

design[owner][submission_checklist][][detail]:

  * type: string

design[owner][submission_checklist][][visible]:

  * type: boolean

design[owner][submission_checklist][][deleted_at]:

  * type: DateTime

design[owner][submission_checklist][][journal]:

  * type: object (Journal)

design[owner][submission_checklist][][locale]:

  * type: string

design[owner][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

design[owner][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

design[owner][journal_submission_files][][id]:

  * type: integer

design[owner][journal_submission_files][][title]:

  * type: string

design[owner][journal_submission_files][][detail]:

  * type: string

design[owner][journal_submission_files][][visible]:

  * type: boolean

design[owner][journal_submission_files][][required]:

  * type: boolean

design[owner][journal_submission_files][][deleted_at]:

  * type: DateTime

design[owner][journal_submission_files][][locale]:

  * type: string

design[owner][journal_submission_files][][file]:

  * type: string

design[owner][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

design[owner][journal_application_upload_files][][id]:

  * type: integer

design[owner][journal_application_upload_files][][title]:

  * type: string

design[owner][journal_application_upload_files][][detail]:

  * type: string

design[owner][journal_application_upload_files][][visible]:

  * type: boolean

design[owner][journal_application_upload_files][][required]:

  * type: boolean

design[owner][journal_application_upload_files][][deleted_at]:

  * type: DateTime

design[owner][journal_application_upload_files][][locale]:

  * type: string

design[owner][journal_application_upload_files][][file]:

  * type: string

design[owner][journal_application_upload_files][][journal]:

  * type: object (Journal)

design[owner][printed]:

  * type: boolean

design[owner][mandatory_lang]:

  * type: object (Lang)

design[owner][view_count]:

  * type: integer

design[owner][download_count]:

  * type: integer

design[owner][translations]:

  * type: string

design[owner][mandatoryLang]:

  * type: string

design[created_by]:

  * type: string
  * description: @var string

design[updated_by]:

  * type: string
  * description: @var string

design[deleted_at]:

  * type: DateTime
  * description: @var \DateTime

design[created]:

  * type: DateTime
  * description: @var \DateTime $created

design[updated]:

  * type: DateTime
  * description: @var \DateTime $updated

configured:

  * type: boolean

articles[]:

  * type: array of objects (Article)

articles[][id]:

  * type: integer
  * description: auto-incremented article unique id

articles[][abstract_transliterated]:

  * type: string
  * description: (optional) English transliterated abstract

articles[][status]:

  * type: integer

articles[][doi]:

  * type: string
  * description: (optional)

articles[][title_transliterated]:

  * type: string
  * description: Roman transliterated title

articles[][anonymous]:

  * type: boolean
  * description: Some articles carries no authorship

articles[][submission_date]:

  * type: DateTime

articles[][pubdate]:

  * type: DateTime

articles[][pubdate_season]:

  * type: string

articles[][first_page]:

  * type: integer

articles[][last_page]:

  * type: integer

articles[][uri]:

  * type: string

articles[][primary_language]:

  * type: string

articles[][order_num]:

  * type: integer

articles[][subjects][]:

  * type: array of objects (Subject)

articles[][subjects][][id]:

  * type: integer

articles[][subjects][][parent]:

  * type: object (Subject)

articles[][subjects][][translations]:

  * type: string

articles[][languages][]:

  * type: array of objects (Lang)

articles[][languages][][id]:

  * type: integer

articles[][languages][][code]:

  * type: string

articles[][languages][][name]:

  * type: string

articles[][languages][][rtl]:

  * type: boolean

articles[][article_type]:

  * type: object (ArticleTypes)

articles[][article_type][id]:

  * type: integer

articles[][article_type][translations][]:

  * type: array of objects (ArticleTypesTranslation)

articles[][article_type][translations][][translatable]:

  * type: object (ArticleTypes)

articles[][article_type][translations][][name]:

  * type: string

articles[][article_type][translations][][description]:

  * type: string

articles[][citations][]:

  * type: array of objects (Citation)

articles[][citations][][id]:

  * type: integer

articles[][citations][][raw]:

  * type: string

articles[][citations][][type]:

  * type: string

articles[][article_authors][]:

  * type: array of objects (ArticleAuthor)

articles[][article_authors][][id]:

  * type: integer

articles[][article_authors][][author_order]:

  * type: integer

articles[][article_authors][][author]:

  * type: object (Author)

articles[][article_authors][][author][id]:

  * type: integer

articles[][article_authors][][author][first_name]:

  * type: string

articles[][article_authors][][author][middle_name]:

  * type: string

articles[][article_authors][][author][last_name]:

  * type: string

articles[][article_authors][][author][email]:

  * type: string

articles[][article_authors][][author][first_name_transliterated]:

  * type: string

articles[][article_authors][][author][middle_name_transliterated]:

  * type: string

articles[][article_authors][][author][last_name_transliterated]:

  * type: string

articles[][article_authors][][author][initials]:

  * type: string

articles[][article_authors][][author][address]:

  * type: string

articles[][article_authors][][author][institution]:

  * type: object (Institution)

articles[][article_authors][][author][institution][id]:

  * type: integer

articles[][article_authors][][author][institution][name]:

  * type: string

articles[][article_authors][][author][institution][address]:

  * type: string

articles[][article_authors][][author][institution][city]:

  * type: string

articles[][article_authors][][author][institution][country]:

  * type: object (Country)

articles[][article_authors][][author][institution][address_lat]:

  * type: string

articles[][article_authors][][author][institution][address_long]:

  * type: string

articles[][article_authors][][author][institution][phone]:

  * type: string

articles[][article_authors][][author][institution][fax]:

  * type: string

articles[][article_authors][][author][institution][email]:

  * type: string

articles[][article_authors][][author][institution][url]:

  * type: string

articles[][article_authors][][author][institution][wiki]:

  * type: string

articles[][article_authors][][author][institution][logo]:

  * type: string

articles[][article_authors][][author][institution][header]:

  * type: string

articles[][article_authors][][author][institution][domain]:

  * type: string

articles[][article_authors][][author][author_details]:

  * type: string

articles[][article_authors][][author][user]:

  * type: object (User)

articles[][article_authors][][author][user][username]:

  * type: string

articles[][article_authors][][author][user][text]:

  * type: string

articles[][article_authors][][author][user][first_name]:

  * type: string

articles[][article_authors][][author][user][last_name]:

  * type: string

articles[][article_authors][][author][user][email]:

  * type: string

articles[][article_authors][][author][user][settings]:

  * type: string
  * description: Json encoded settings string

articles[][article_authors][][author][user][about]:

  * type: string

articles[][article_authors][][author][user][country]:

  * type: object (Country)

articles[][article_authors][][author][user][journal_users][]:

  * type: array of objects (JournalUser)

articles[][article_authors][][author][user][journal_users][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal]:

  * type: object (Journal)

articles[][article_authors][][author][user][journal_users][][journal][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][title_transliterated]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][path]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][domain]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issn]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][eissn]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][founded]:

  * type: DateTime

articles[][article_authors][][author][user][journal_users][][journal][url]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][address]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][phone]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][email]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][country]:

  * type: object (Country)

articles[][article_authors][][author][user][journal_users][][journal][published]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][status]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][image]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][header]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][google_analytics_id]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][slug]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][theme]:

  * type: object (JournalTheme)

articles[][article_authors][][author][user][journal_users][][journal][design]:

  * type: object (Design)

articles[][article_authors][][author][user][journal_users][][journal][configured]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][articles][]:

  * type: array of objects (Article)

articles[][article_authors][][author][user][journal_users][][journal][issues][]:

  * type: array of objects (Issue)

articles[][article_authors][][author][user][journal_users][][journal][issues][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][issues][][journal]:

  * type: object (Journal)

articles[][article_authors][][author][user][journal_users][][journal][issues][][volume]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][number]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][cover]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][special]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][issues][][year]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][date_published]:

  * type: DateTime

articles[][article_authors][][author][user][journal_users][][journal][issues][][articles][]:

  * type: array of objects (Article)

articles[][article_authors][][author][user][journal_users][][journal][issues][][header]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][supplement]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][issues][][full_file]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][issues][][issue_files][]:

  * type: array of objects (IssueFile)

articles[][article_authors][][author][user][journal_users][][journal][issues][][view_count]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][issues][][download_count]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][languages][]:

  * type: array of objects (Lang)

articles[][article_authors][][author][user][journal_users][][journal][periods][]:

  * type: array of objects (Period)

articles[][article_authors][][author][user][journal_users][][journal][periods][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][periods][][translations][]:

  * type: array of objects (PeriodTranslation)

articles[][article_authors][][author][user][journal_users][][journal][periods][][translations][][translatable]:

  * type: object (Period)

articles[][article_authors][][author][user][journal_users][][journal][periods][][translations][][period]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][periods][][created_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][periods][][updated_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][periods][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

articles[][article_authors][][author][user][journal_users][][journal][periods][][created]:

  * type: DateTime
  * description: @var \DateTime $created

articles[][article_authors][][author][user][journal_users][][journal][periods][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

articles[][article_authors][][author][user][journal_users][][journal][subjects][]:

  * type: array of objects (Subject)

articles[][article_authors][][author][user][journal_users][][journal][publisher]:

  * type: object (Publisher)

articles[][article_authors][][author][user][journal_users][][journal][publisher][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][publisher][lft]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][publisher][name]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][address]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][city]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][country]:

  * type: object (Country)

articles[][article_authors][][author][user][journal_users][][journal][publisher][address_lat]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][address_long]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][phone]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][fax]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][email]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][url]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][wiki]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][logo]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][header]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][domain]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][]:

  * type: array of objects (PublisherTheme)

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][title]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][css]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][public]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][publisher]:

  * type: object (Publisher)

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][created_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][updated_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][]:

  * type: array of objects (PublisherDesign)

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][title]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][content]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][editable_content]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][public]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][publisher]:

  * type: object (Publisher)

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][created_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][updated_by]:

  * type: string
  * description: @var string

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

articles[][article_authors][][author][user][journal_users][][journal][publisher][publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

articles[][article_authors][][author][user][journal_users][][journal][logo]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][link]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][journal]:

  * type: object (Journal)

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][index]:

  * type: object (Index)

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][index][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][index][name]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][index][logo]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][index][status]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][journal_indexs][][verified]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][label]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][detail]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][visible]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][deleted_at]:

  * type: DateTime

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][journal]:

  * type: object (Journal)

articles[][article_authors][][author][user][journal_users][][journal][submission_checklist][][locale]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][title]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][detail]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][visible]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][required]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][deleted_at]:

  * type: DateTime

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][locale]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_submission_files][][file]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][title]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][detail]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][visible]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][required]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][locale]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][file]:

  * type: string

articles[][article_authors][][author][user][journal_users][][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

articles[][article_authors][][author][user][journal_users][][journal][printed]:

  * type: boolean

articles[][article_authors][][author][user][journal_users][][journal][mandatory_lang]:

  * type: object (Lang)

articles[][article_authors][][author][user][journal_users][][journal][view_count]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][journal][download_count]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][user]:

  * type: object (User)

articles[][article_authors][][author][user][journal_users][][roles][]:

  * type: array of objects (Role)

articles[][article_authors][][author][user][journal_users][][roles][][id]:

  * type: integer

articles[][article_authors][][author][user][journal_users][][roles][][name]:

  * type: string

articles[][article_authors][][author][user][journal_users][][roles][][role]:

  * type: string

articles[][article_authors][][author][orcid]:

  * type: string

articles[][article_authors][][author][institution_not_listed]:

  * type: boolean

articles[][article_authors][][author][institution_name]:

  * type: string

articles[][article_authors][][author][article_authors][]:

  * type: array of objects (ArticleAuthor)

articles[][article_authors][][authorOrder]:

  * type: string

articles[][article_files][]:

  * type: array of objects (ArticleFile)

articles[][article_files][][id]:

  * type: integer

articles[][article_files][][type]:

  * type: integer

articles[][article_files][][file]:

  * type: string

articles[][article_files][][version]:

  * type: integer

articles[][article_files][][article]:

  * type: object (Article)

articles[][article_files][][keywords]:

  * type: string

articles[][article_files][][description]:

  * type: string

articles[][article_files][][title]:

  * type: string

articles[][article_files][][lang_code]:

  * type: string

articles[][article_files][][created_by]:

  * type: string
  * description: @var string

articles[][article_files][][updated_by]:

  * type: string
  * description: @var string

articles[][article_files][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

articles[][article_files][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

articles[][article_files][][langCode]:

  * type: string

articles[][article_submission_files][]:

  * type: array of objects (ArticleSubmissionFile)

articles[][article_submission_files][][id]:

  * type: integer

articles[][article_submission_files][][title]:

  * type: string

articles[][article_submission_files][][detail]:

  * type: string

articles[][article_submission_files][][visible]:

  * type: boolean

articles[][article_submission_files][][required]:

  * type: boolean

articles[][article_submission_files][][deleted_at]:

  * type: DateTime

articles[][article_submission_files][][article]:

  * type: object (Article)

articles[][article_submission_files][][locale]:

  * type: string

articles[][article_submission_files][][file]:

  * type: string

articles[][view_count]:

  * type: integer

articles[][download_count]:

  * type: integer

articles[][translations]:

  * type: string

articles[][articleFiles]:

  * type: string

articles[][articleAuthors]:

  * type: string

articles[][submissionDate]:

  * type: string

issues[]:

  * type: array of objects (Issue)

issues[][id]:

  * type: integer

issues[][journal]:

  * type: object (Journal)

issues[][journal][id]:

  * type: integer

issues[][journal][title_transliterated]:

  * type: string

issues[][journal][path]:

  * type: string

issues[][journal][domain]:

  * type: string

issues[][journal][issn]:

  * type: string

issues[][journal][eissn]:

  * type: string

issues[][journal][founded]:

  * type: DateTime

issues[][journal][url]:

  * type: string

issues[][journal][address]:

  * type: string

issues[][journal][phone]:

  * type: string

issues[][journal][email]:

  * type: string

issues[][journal][country]:

  * type: object (Country)

issues[][journal][published]:

  * type: boolean

issues[][journal][status]:

  * type: integer

issues[][journal][image]:

  * type: string

issues[][journal][header]:

  * type: string

issues[][journal][google_analytics_id]:

  * type: string

issues[][journal][slug]:

  * type: string

issues[][journal][theme]:

  * type: object (JournalTheme)

issues[][journal][design]:

  * type: object (Design)

issues[][journal][configured]:

  * type: boolean

issues[][journal][articles][]:

  * type: array of objects (Article)

issues[][journal][issues][]:

  * type: array of objects (Issue)

issues[][journal][languages][]:

  * type: array of objects (Lang)

issues[][journal][languages][][id]:

  * type: integer

issues[][journal][languages][][code]:

  * type: string

issues[][journal][languages][][name]:

  * type: string

issues[][journal][languages][][rtl]:

  * type: boolean

issues[][journal][periods][]:

  * type: array of objects (Period)

issues[][journal][periods][][id]:

  * type: integer

issues[][journal][periods][][translations][]:

  * type: array of objects (PeriodTranslation)

issues[][journal][periods][][translations][][translatable]:

  * type: object (Period)

issues[][journal][periods][][translations][][period]:

  * type: string

issues[][journal][periods][][created_by]:

  * type: string
  * description: @var string

issues[][journal][periods][][updated_by]:

  * type: string
  * description: @var string

issues[][journal][periods][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

issues[][journal][periods][][created]:

  * type: DateTime
  * description: @var \DateTime $created

issues[][journal][periods][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

issues[][journal][subjects][]:

  * type: array of objects (Subject)

issues[][journal][subjects][][id]:

  * type: integer

issues[][journal][subjects][][parent]:

  * type: object (Subject)

issues[][journal][subjects][][translations]:

  * type: string

issues[][journal][publisher]:

  * type: object (Publisher)

issues[][journal][publisher][id]:

  * type: integer

issues[][journal][publisher][lft]:

  * type: integer

issues[][journal][publisher][name]:

  * type: string

issues[][journal][publisher][address]:

  * type: string

issues[][journal][publisher][city]:

  * type: string

issues[][journal][publisher][country]:

  * type: object (Country)

issues[][journal][publisher][address_lat]:

  * type: string

issues[][journal][publisher][address_long]:

  * type: string

issues[][journal][publisher][phone]:

  * type: string

issues[][journal][publisher][fax]:

  * type: string

issues[][journal][publisher][email]:

  * type: string

issues[][journal][publisher][url]:

  * type: string

issues[][journal][publisher][wiki]:

  * type: string

issues[][journal][publisher][logo]:

  * type: string

issues[][journal][publisher][header]:

  * type: string

issues[][journal][publisher][domain]:

  * type: string

issues[][journal][publisher][publisher_themes][]:

  * type: array of objects (PublisherTheme)

issues[][journal][publisher][publisher_themes][][id]:

  * type: integer

issues[][journal][publisher][publisher_themes][][title]:

  * type: string

issues[][journal][publisher][publisher_themes][][css]:

  * type: string

issues[][journal][publisher][publisher_themes][][public]:

  * type: boolean

issues[][journal][publisher][publisher_themes][][publisher]:

  * type: object (Publisher)

issues[][journal][publisher][publisher_themes][][created_by]:

  * type: string
  * description: @var string

issues[][journal][publisher][publisher_themes][][updated_by]:

  * type: string
  * description: @var string

issues[][journal][publisher][publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

issues[][journal][publisher][publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

issues[][journal][publisher][publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

issues[][journal][publisher][publisher_designs][]:

  * type: array of objects (PublisherDesign)

issues[][journal][publisher][publisher_designs][][id]:

  * type: integer

issues[][journal][publisher][publisher_designs][][title]:

  * type: string

issues[][journal][publisher][publisher_designs][][content]:

  * type: string

issues[][journal][publisher][publisher_designs][][editable_content]:

  * type: string

issues[][journal][publisher][publisher_designs][][public]:

  * type: boolean

issues[][journal][publisher][publisher_designs][][publisher]:

  * type: object (Publisher)

issues[][journal][publisher][publisher_designs][][created_by]:

  * type: string
  * description: @var string

issues[][journal][publisher][publisher_designs][][updated_by]:

  * type: string
  * description: @var string

issues[][journal][publisher][publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

issues[][journal][publisher][publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

issues[][journal][publisher][publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

issues[][journal][logo]:

  * type: string

issues[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

issues[][journal][journal_indexs][][id]:

  * type: integer

issues[][journal][journal_indexs][][link]:

  * type: string

issues[][journal][journal_indexs][][journal]:

  * type: object (Journal)

issues[][journal][journal_indexs][][index]:

  * type: object (Index)

issues[][journal][journal_indexs][][index][id]:

  * type: integer

issues[][journal][journal_indexs][][index][name]:

  * type: string

issues[][journal][journal_indexs][][index][logo]:

  * type: string

issues[][journal][journal_indexs][][index][status]:

  * type: boolean

issues[][journal][journal_indexs][][verified]:

  * type: boolean

issues[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

issues[][journal][submission_checklist][][id]:

  * type: integer

issues[][journal][submission_checklist][][label]:

  * type: string

issues[][journal][submission_checklist][][detail]:

  * type: string

issues[][journal][submission_checklist][][visible]:

  * type: boolean

issues[][journal][submission_checklist][][deleted_at]:

  * type: DateTime

issues[][journal][submission_checklist][][journal]:

  * type: object (Journal)

issues[][journal][submission_checklist][][locale]:

  * type: string

issues[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

issues[][journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

issues[][journal][journal_submission_files][][id]:

  * type: integer

issues[][journal][journal_submission_files][][title]:

  * type: string

issues[][journal][journal_submission_files][][detail]:

  * type: string

issues[][journal][journal_submission_files][][visible]:

  * type: boolean

issues[][journal][journal_submission_files][][required]:

  * type: boolean

issues[][journal][journal_submission_files][][deleted_at]:

  * type: DateTime

issues[][journal][journal_submission_files][][locale]:

  * type: string

issues[][journal][journal_submission_files][][file]:

  * type: string

issues[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

issues[][journal][journal_application_upload_files][][id]:

  * type: integer

issues[][journal][journal_application_upload_files][][title]:

  * type: string

issues[][journal][journal_application_upload_files][][detail]:

  * type: string

issues[][journal][journal_application_upload_files][][visible]:

  * type: boolean

issues[][journal][journal_application_upload_files][][required]:

  * type: boolean

issues[][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

issues[][journal][journal_application_upload_files][][locale]:

  * type: string

issues[][journal][journal_application_upload_files][][file]:

  * type: string

issues[][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

issues[][journal][printed]:

  * type: boolean

issues[][journal][mandatory_lang]:

  * type: object (Lang)

issues[][journal][view_count]:

  * type: integer

issues[][journal][download_count]:

  * type: integer

issues[][journal][translations]:

  * type: string

issues[][journal][mandatoryLang]:

  * type: string

issues[][volume]:

  * type: string

issues[][number]:

  * type: string

issues[][cover]:

  * type: string

issues[][special]:

  * type: boolean

issues[][year]:

  * type: string

issues[][date_published]:

  * type: DateTime

issues[][articles][]:

  * type: array of objects (Article)

issues[][header]:

  * type: string

issues[][supplement]:

  * type: boolean

issues[][full_file]:

  * type: string

issues[][issue_files][]:

  * type: array of objects (IssueFile)

issues[][issue_files][][translations]:

  * type: string

issues[][issue_files][][file]:

  * type: string

issues[][issue_files][][type]:

  * type: string

issues[][issue_files][][langCode]:

  * type: string

issues[][view_count]:

  * type: integer

issues[][download_count]:

  * type: integer

issues[][translations]:

  * type: string

periods[]:

  * type: array of objects (Period)

periods[][id]:

  * type: integer

periods[][translations][]:

  * type: array of objects (PeriodTranslation)

periods[][translations][][translatable]:

  * type: object (Period)

periods[][translations][][period]:

  * type: string

periods[][created_by]:

  * type: string
  * description: @var string

periods[][updated_by]:

  * type: string
  * description: @var string

periods[][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

periods[][created]:

  * type: DateTime
  * description: @var \DateTime $created

periods[][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

subjects[]:

  * type: array of objects (Subject)

subjects[][id]:

  * type: integer

subjects[][parent]:

  * type: object (Subject)

subjects[][translations]:

  * type: string

publisher:

  * type: object (Publisher)

publisher[id]:

  * type: integer

publisher[lft]:

  * type: integer

publisher[name]:

  * type: string

publisher[address]:

  * type: string

publisher[city]:

  * type: string

publisher[country]:

  * type: object (Country)

publisher[address_lat]:

  * type: string

publisher[address_long]:

  * type: string

publisher[phone]:

  * type: string

publisher[fax]:

  * type: string

publisher[email]:

  * type: string

publisher[url]:

  * type: string

publisher[wiki]:

  * type: string

publisher[logo]:

  * type: string

publisher[header]:

  * type: string

publisher[domain]:

  * type: string

publisher[publisher_themes][]:

  * type: array of objects (PublisherTheme)

publisher[publisher_themes][][id]:

  * type: integer

publisher[publisher_themes][][title]:

  * type: string

publisher[publisher_themes][][css]:

  * type: string

publisher[publisher_themes][][public]:

  * type: boolean

publisher[publisher_themes][][publisher]:

  * type: object (Publisher)

publisher[publisher_themes][][created_by]:

  * type: string
  * description: @var string

publisher[publisher_themes][][updated_by]:

  * type: string
  * description: @var string

publisher[publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

publisher[publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

publisher[publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

publisher[publisher_designs][]:

  * type: array of objects (PublisherDesign)

publisher[publisher_designs][][id]:

  * type: integer

publisher[publisher_designs][][title]:

  * type: string

publisher[publisher_designs][][content]:

  * type: string

publisher[publisher_designs][][editable_content]:

  * type: string

publisher[publisher_designs][][public]:

  * type: boolean

publisher[publisher_designs][][publisher]:

  * type: object (Publisher)

publisher[publisher_designs][][created_by]:

  * type: string
  * description: @var string

publisher[publisher_designs][][updated_by]:

  * type: string
  * description: @var string

publisher[publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

publisher[publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

publisher[publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

logo:

  * type: string

journal_indexs[]:

  * type: array of objects (JournalIndex)

journal_indexs[][id]:

  * type: integer

journal_indexs[][link]:

  * type: string

journal_indexs[][journal]:

  * type: object (Journal)

journal_indexs[][journal][id]:

  * type: integer

journal_indexs[][journal][title_transliterated]:

  * type: string

journal_indexs[][journal][path]:

  * type: string

journal_indexs[][journal][domain]:

  * type: string

journal_indexs[][journal][issn]:

  * type: string

journal_indexs[][journal][eissn]:

  * type: string

journal_indexs[][journal][founded]:

  * type: DateTime

journal_indexs[][journal][url]:

  * type: string

journal_indexs[][journal][address]:

  * type: string

journal_indexs[][journal][phone]:

  * type: string

journal_indexs[][journal][email]:

  * type: string

journal_indexs[][journal][country]:

  * type: object (Country)

journal_indexs[][journal][published]:

  * type: boolean

journal_indexs[][journal][status]:

  * type: integer

journal_indexs[][journal][image]:

  * type: string

journal_indexs[][journal][header]:

  * type: string

journal_indexs[][journal][google_analytics_id]:

  * type: string

journal_indexs[][journal][slug]:

  * type: string

journal_indexs[][journal][theme]:

  * type: object (JournalTheme)

journal_indexs[][journal][design]:

  * type: object (Design)

journal_indexs[][journal][configured]:

  * type: boolean

journal_indexs[][journal][articles][]:

  * type: array of objects (Article)

journal_indexs[][journal][issues][]:

  * type: array of objects (Issue)

journal_indexs[][journal][languages][]:

  * type: array of objects (Lang)

journal_indexs[][journal][periods][]:

  * type: array of objects (Period)

journal_indexs[][journal][subjects][]:

  * type: array of objects (Subject)

journal_indexs[][journal][publisher]:

  * type: object (Publisher)

journal_indexs[][journal][logo]:

  * type: string

journal_indexs[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

journal_indexs[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

journal_indexs[][journal][submission_checklist][][id]:

  * type: integer

journal_indexs[][journal][submission_checklist][][label]:

  * type: string

journal_indexs[][journal][submission_checklist][][detail]:

  * type: string

journal_indexs[][journal][submission_checklist][][visible]:

  * type: boolean

journal_indexs[][journal][submission_checklist][][deleted_at]:

  * type: DateTime

journal_indexs[][journal][submission_checklist][][journal]:

  * type: object (Journal)

journal_indexs[][journal][submission_checklist][][locale]:

  * type: string

journal_indexs[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

journal_indexs[][journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

journal_indexs[][journal][journal_submission_files][][id]:

  * type: integer

journal_indexs[][journal][journal_submission_files][][title]:

  * type: string

journal_indexs[][journal][journal_submission_files][][detail]:

  * type: string

journal_indexs[][journal][journal_submission_files][][visible]:

  * type: boolean

journal_indexs[][journal][journal_submission_files][][required]:

  * type: boolean

journal_indexs[][journal][journal_submission_files][][deleted_at]:

  * type: DateTime

journal_indexs[][journal][journal_submission_files][][locale]:

  * type: string

journal_indexs[][journal][journal_submission_files][][file]:

  * type: string

journal_indexs[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

journal_indexs[][journal][journal_application_upload_files][][id]:

  * type: integer

journal_indexs[][journal][journal_application_upload_files][][title]:

  * type: string

journal_indexs[][journal][journal_application_upload_files][][detail]:

  * type: string

journal_indexs[][journal][journal_application_upload_files][][visible]:

  * type: boolean

journal_indexs[][journal][journal_application_upload_files][][required]:

  * type: boolean

journal_indexs[][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

journal_indexs[][journal][journal_application_upload_files][][locale]:

  * type: string

journal_indexs[][journal][journal_application_upload_files][][file]:

  * type: string

journal_indexs[][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

journal_indexs[][journal][printed]:

  * type: boolean

journal_indexs[][journal][mandatory_lang]:

  * type: object (Lang)

journal_indexs[][journal][view_count]:

  * type: integer

journal_indexs[][journal][download_count]:

  * type: integer

journal_indexs[][journal][translations]:

  * type: string

journal_indexs[][journal][mandatoryLang]:

  * type: string

journal_indexs[][index]:

  * type: object (Index)

journal_indexs[][index][id]:

  * type: integer

journal_indexs[][index][name]:

  * type: string

journal_indexs[][index][logo]:

  * type: string

journal_indexs[][index][status]:

  * type: boolean

journal_indexs[][verified]:

  * type: boolean

submission_checklist[]:

  * type: array of objects (SubmissionChecklist)

submission_checklist[][id]:

  * type: integer

submission_checklist[][label]:

  * type: string

submission_checklist[][detail]:

  * type: string

submission_checklist[][visible]:

  * type: boolean

submission_checklist[][deleted_at]:

  * type: DateTime

submission_checklist[][journal]:

  * type: object (Journal)

submission_checklist[][journal][id]:

  * type: integer

submission_checklist[][journal][title_transliterated]:

  * type: string

submission_checklist[][journal][path]:

  * type: string

submission_checklist[][journal][domain]:

  * type: string

submission_checklist[][journal][issn]:

  * type: string

submission_checklist[][journal][eissn]:

  * type: string

submission_checklist[][journal][founded]:

  * type: DateTime

submission_checklist[][journal][url]:

  * type: string

submission_checklist[][journal][address]:

  * type: string

submission_checklist[][journal][phone]:

  * type: string

submission_checklist[][journal][email]:

  * type: string

submission_checklist[][journal][country]:

  * type: object (Country)

submission_checklist[][journal][published]:

  * type: boolean

submission_checklist[][journal][status]:

  * type: integer

submission_checklist[][journal][image]:

  * type: string

submission_checklist[][journal][header]:

  * type: string

submission_checklist[][journal][google_analytics_id]:

  * type: string

submission_checklist[][journal][slug]:

  * type: string

submission_checklist[][journal][theme]:

  * type: object (JournalTheme)

submission_checklist[][journal][design]:

  * type: object (Design)

submission_checklist[][journal][configured]:

  * type: boolean

submission_checklist[][journal][articles][]:

  * type: array of objects (Article)

submission_checklist[][journal][issues][]:

  * type: array of objects (Issue)

submission_checklist[][journal][languages][]:

  * type: array of objects (Lang)

submission_checklist[][journal][periods][]:

  * type: array of objects (Period)

submission_checklist[][journal][subjects][]:

  * type: array of objects (Subject)

submission_checklist[][journal][publisher]:

  * type: object (Publisher)

submission_checklist[][journal][logo]:

  * type: string

submission_checklist[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

submission_checklist[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

submission_checklist[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

submission_checklist[][journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

submission_checklist[][journal][journal_submission_files][][id]:

  * type: integer

submission_checklist[][journal][journal_submission_files][][title]:

  * type: string

submission_checklist[][journal][journal_submission_files][][detail]:

  * type: string

submission_checklist[][journal][journal_submission_files][][visible]:

  * type: boolean

submission_checklist[][journal][journal_submission_files][][required]:

  * type: boolean

submission_checklist[][journal][journal_submission_files][][deleted_at]:

  * type: DateTime

submission_checklist[][journal][journal_submission_files][][locale]:

  * type: string

submission_checklist[][journal][journal_submission_files][][file]:

  * type: string

submission_checklist[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

submission_checklist[][journal][journal_application_upload_files][][id]:

  * type: integer

submission_checklist[][journal][journal_application_upload_files][][title]:

  * type: string

submission_checklist[][journal][journal_application_upload_files][][detail]:

  * type: string

submission_checklist[][journal][journal_application_upload_files][][visible]:

  * type: boolean

submission_checklist[][journal][journal_application_upload_files][][required]:

  * type: boolean

submission_checklist[][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

submission_checklist[][journal][journal_application_upload_files][][locale]:

  * type: string

submission_checklist[][journal][journal_application_upload_files][][file]:

  * type: string

submission_checklist[][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

submission_checklist[][journal][printed]:

  * type: boolean

submission_checklist[][journal][mandatory_lang]:

  * type: object (Lang)

submission_checklist[][journal][view_count]:

  * type: integer

submission_checklist[][journal][download_count]:

  * type: integer

submission_checklist[][journal][translations]:

  * type: string

submission_checklist[][journal][mandatoryLang]:

  * type: string

submission_checklist[][locale]:

  * type: string

journal_submission_files[]:

  * type: array of objects (JournalSubmissionFile)

journal_submission_files[][journal]:

  * type: object (Journal)
  * description: @var  Journal

journal_submission_files[][journal][id]:

  * type: integer

journal_submission_files[][journal][title_transliterated]:

  * type: string

journal_submission_files[][journal][path]:

  * type: string

journal_submission_files[][journal][domain]:

  * type: string

journal_submission_files[][journal][issn]:

  * type: string

journal_submission_files[][journal][eissn]:

  * type: string

journal_submission_files[][journal][founded]:

  * type: DateTime

journal_submission_files[][journal][url]:

  * type: string

journal_submission_files[][journal][address]:

  * type: string

journal_submission_files[][journal][phone]:

  * type: string

journal_submission_files[][journal][email]:

  * type: string

journal_submission_files[][journal][country]:

  * type: object (Country)

journal_submission_files[][journal][published]:

  * type: boolean

journal_submission_files[][journal][status]:

  * type: integer

journal_submission_files[][journal][image]:

  * type: string

journal_submission_files[][journal][header]:

  * type: string

journal_submission_files[][journal][google_analytics_id]:

  * type: string

journal_submission_files[][journal][slug]:

  * type: string

journal_submission_files[][journal][theme]:

  * type: object (JournalTheme)

journal_submission_files[][journal][design]:

  * type: object (Design)

journal_submission_files[][journal][configured]:

  * type: boolean

journal_submission_files[][journal][articles][]:

  * type: array of objects (Article)

journal_submission_files[][journal][issues][]:

  * type: array of objects (Issue)

journal_submission_files[][journal][languages][]:

  * type: array of objects (Lang)

journal_submission_files[][journal][periods][]:

  * type: array of objects (Period)

journal_submission_files[][journal][subjects][]:

  * type: array of objects (Subject)

journal_submission_files[][journal][publisher]:

  * type: object (Publisher)

journal_submission_files[][journal][logo]:

  * type: string

journal_submission_files[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

journal_submission_files[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

journal_submission_files[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

journal_submission_files[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

journal_submission_files[][journal][journal_application_upload_files][][id]:

  * type: integer

journal_submission_files[][journal][journal_application_upload_files][][title]:

  * type: string

journal_submission_files[][journal][journal_application_upload_files][][detail]:

  * type: string

journal_submission_files[][journal][journal_application_upload_files][][visible]:

  * type: boolean

journal_submission_files[][journal][journal_application_upload_files][][required]:

  * type: boolean

journal_submission_files[][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

journal_submission_files[][journal][journal_application_upload_files][][locale]:

  * type: string

journal_submission_files[][journal][journal_application_upload_files][][file]:

  * type: string

journal_submission_files[][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

journal_submission_files[][journal][printed]:

  * type: boolean

journal_submission_files[][journal][mandatory_lang]:

  * type: object (Lang)

journal_submission_files[][journal][view_count]:

  * type: integer

journal_submission_files[][journal][download_count]:

  * type: integer

journal_submission_files[][journal][translations]:

  * type: string

journal_submission_files[][journal][mandatoryLang]:

  * type: string

journal_submission_files[][id]:

  * type: integer

journal_submission_files[][title]:

  * type: string

journal_submission_files[][detail]:

  * type: string

journal_submission_files[][visible]:

  * type: boolean

journal_submission_files[][required]:

  * type: boolean

journal_submission_files[][deleted_at]:

  * type: DateTime

journal_submission_files[][locale]:

  * type: string

journal_submission_files[][file]:

  * type: string

journal_application_upload_files[]:

  * type: array of objects (JournalApplicationUploadFile)

journal_application_upload_files[][id]:

  * type: integer

journal_application_upload_files[][title]:

  * type: string

journal_application_upload_files[][detail]:

  * type: string

journal_application_upload_files[][visible]:

  * type: boolean

journal_application_upload_files[][required]:

  * type: boolean

journal_application_upload_files[][deleted_at]:

  * type: DateTime

journal_application_upload_files[][locale]:

  * type: string

journal_application_upload_files[][file]:

  * type: string

journal_application_upload_files[][journal]:

  * type: object (Journal)

journal_application_upload_files[][journal][id]:

  * type: integer

journal_application_upload_files[][journal][title_transliterated]:

  * type: string

journal_application_upload_files[][journal][path]:

  * type: string

journal_application_upload_files[][journal][domain]:

  * type: string

journal_application_upload_files[][journal][issn]:

  * type: string

journal_application_upload_files[][journal][eissn]:

  * type: string

journal_application_upload_files[][journal][founded]:

  * type: DateTime

journal_application_upload_files[][journal][url]:

  * type: string

journal_application_upload_files[][journal][address]:

  * type: string

journal_application_upload_files[][journal][phone]:

  * type: string

journal_application_upload_files[][journal][email]:

  * type: string

journal_application_upload_files[][journal][country]:

  * type: object (Country)

journal_application_upload_files[][journal][published]:

  * type: boolean

journal_application_upload_files[][journal][status]:

  * type: integer

journal_application_upload_files[][journal][image]:

  * type: string

journal_application_upload_files[][journal][header]:

  * type: string

journal_application_upload_files[][journal][google_analytics_id]:

  * type: string

journal_application_upload_files[][journal][slug]:

  * type: string

journal_application_upload_files[][journal][theme]:

  * type: object (JournalTheme)

journal_application_upload_files[][journal][design]:

  * type: object (Design)

journal_application_upload_files[][journal][configured]:

  * type: boolean

journal_application_upload_files[][journal][articles][]:

  * type: array of objects (Article)

journal_application_upload_files[][journal][issues][]:

  * type: array of objects (Issue)

journal_application_upload_files[][journal][languages][]:

  * type: array of objects (Lang)

journal_application_upload_files[][journal][periods][]:

  * type: array of objects (Period)

journal_application_upload_files[][journal][subjects][]:

  * type: array of objects (Subject)

journal_application_upload_files[][journal][publisher]:

  * type: object (Publisher)

journal_application_upload_files[][journal][logo]:

  * type: string

journal_application_upload_files[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

journal_application_upload_files[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

journal_application_upload_files[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

journal_application_upload_files[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

journal_application_upload_files[][journal][printed]:

  * type: boolean

journal_application_upload_files[][journal][mandatory_lang]:

  * type: object (Lang)

journal_application_upload_files[][journal][view_count]:

  * type: integer

journal_application_upload_files[][journal][download_count]:

  * type: integer

journal_application_upload_files[][journal][translations]:

  * type: string

journal_application_upload_files[][journal][mandatoryLang]:

  * type: string

printed:

  * type: boolean

mandatory_lang:

  * type: object (Lang)

view_count:

  * type: integer

download_count:

  * type: integer


### `PUT` /api/v1/journals/{id}.{_format} ###

_Update existing Journal from the submitted data or create a new Journal at a specific location._

Update existing Journal from the submitted data or create a new Journal at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Journal id


### `DELETE` /api/v1/journals/{id}.{_format} ###

_Delete Journal_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Journal ID
**_format**

  - Requirement: xml|json|html


## /api/v1/langs ##

### `GET` /api/v1/langs.{_format} ###

_List all Langs._

List all Langs.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Langs.

limit:

  * Requirement: \d+
  * Description: How many Langs to return.
  * Default: 5


### `POST` /api/v1/langs.{_format} ###

_Creates a new Lang from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/langs/new ##

### `GET` /api/v1/langs/new.{_format} ###

_Presents the form to use to create a new Lang._

Presents the form to use to create a new Lang.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/langs/{id} ##

### `GET` /api/v1/langs/{id}.{_format} ###

_Gets a Lang for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Lang id


### `PATCH` /api/v1/langs/{id}.{_format} ###

_Update existing lang from the submitted data or create a new lang at a specific location._

Update existing lang from the submitted data or create a new lang at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the lang id


### `PUT` /api/v1/langs/{id}.{_format} ###

_Update existing Lang from the submitted data or create a new Lang at a specific location._

Update existing Lang from the submitted data or create a new Lang at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Lang id


### `DELETE` /api/v1/langs/{id}.{_format} ###

_Delete Lang_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Lang ID
**_format**

  - Requirement: xml|json|html


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

### `PATCH` /api/v1/pages/{id}.{_format} ###

_Update existing page from the submitted data or create a new page at a specific location._

Update existing page from the submitted data or create a new page at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the page id


### `GET` /api/v1/pages/{id}.{_format} ###

_Gets a Page for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Page id


### `PUT` /api/v1/pages/{id}.{_format} ###

_Update existing Page from the submitted data or create a new Page at a specific location._

Update existing Page from the submitted data or create a new Page at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Page id


### `DELETE` /api/v1/pages/{id}.{_format} ###

_Delete Page_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Page ID
**_format**

  - Requirement: xml|json|html


## /api/v1/periods ##

### `GET` /api/v1/periods.{_format} ###

_List all Periods._

List all Periods.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Periods.

limit:

  * Requirement: \d+
  * Description: How many Periods to return.
  * Default: 5


### `POST` /api/v1/periods.{_format} ###

_Creates a new Period from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/periods/new ##

### `GET` /api/v1/periods/new.{_format} ###

_Presents the form to use to create a new Period._

Presents the form to use to create a new Period.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/periods/{id} ##

### `PATCH` /api/v1/periods/{id}.{_format} ###

_Update existing period from the submitted data or create a new period at a specific location._

Update existing period from the submitted data or create a new period at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the period id


### `GET` /api/v1/periods/{id}.{_format} ###

_Gets a Period for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Period id


### `PUT` /api/v1/periods/{id}.{_format} ###

_Update existing Period from the submitted data or create a new Period at a specific location._

Update existing Period from the submitted data or create a new Period at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Period id


### `DELETE` /api/v1/periods/{id}.{_format} ###

_Delete Period_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Period ID
**_format**

  - Requirement: xml|json|html


## /api/v1/persontitles ##

### `GET` /api/v1/persontitles.{_format} ###

_List all PersonTitles._

List all PersonTitles.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PersonTitles.

limit:

  * Requirement: \d+
  * Description: How many PersonTitles to return.
  * Default: 5


### `POST` /api/v1/persontitles.{_format} ###

_Creates a new PersonTitle from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/persontitles/new ##

### `GET` /api/v1/persontitles/new.{_format} ###

_Presents the form to use to create a new PersonTitle._

Presents the form to use to create a new PersonTitle.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/persontitles/{id} ##

### `GET` /api/v1/persontitles/{id}.{_format} ###

_Gets a PersonTitle for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PersonTitle id


### `PATCH` /api/v1/persontitles/{id}.{_format} ###

_Update existing persontitle from the submitted data or create a new persontitle at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the person_title id


### `PUT` /api/v1/persontitles/{id}.{_format} ###

_Update existing PersonTitle from the submitted data or create a new PersonTitle at a specific location._

Update existing PersonTitle from the submitted data or create a new PersonTitle at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PersonTitle id


### `DELETE` /api/v1/persontitles/{id}.{_format} ###

_Delete PersonTitle_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PersonTitle ID
**_format**

  - Requirement: xml|json|html


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


### `DELETE` /api/v1/posts/{id}.{_format} ###

_Delete Post_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Post ID
**_format**

  - Requirement: xml|json|html


## /api/v1/publishermanagers ##

### `GET` /api/v1/publishermanagers.{_format} ###

_List all PublisherManager._

List all PublisherManager.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PublisherManager.

limit:

  * Requirement: \d+
  * Description: How many PublisherManager to return.
  * Default: 5


### `POST` /api/v1/publishermanagers.{_format} ###

_Creates a new PublisherManager from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishermanagers/new ##

### `GET` /api/v1/publishermanagers/new.{_format} ###

_Presents the form to use to create a new PublisherManager._

Presents the form to use to create a new PublisherManager.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishermanagers/{id} ##

### `GET` /api/v1/publishermanagers/{id}.{_format} ###

_Gets a PublisherManager for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherManager id


### `PATCH` /api/v1/publishermanagers/{id}.{_format} ###

_Update existing publishermanager from the submitted data or create a new publishermanager at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisher_manager id


### `PUT` /api/v1/publishermanagers/{id}.{_format} ###

_Update existing PublisherManager from the submitted data or create a new PublisherManager at a specific location._

Update existing PublisherManager from the submitted data or create a new PublisherManager at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherManager id


### `DELETE` /api/v1/publishermanagers/{id}.{_format} ###

_Delete PublisherManager_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PublisherManager ID
**_format**

  - Requirement: xml|json|html


## /api/v1/publishers ##

### `GET` /api/v1/publishers.{_format} ###

_List all Publishers._

List all Publishers.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Publishers.

limit:

  * Requirement: \d+
  * Description: How many Publishers to return.
  * Default: 5


### `POST` /api/v1/publishers.{_format} ###

_Creates a new Publisher from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishers/new ##

### `GET` /api/v1/publishers/new.{_format} ###

_Presents the form to use to create a new Publisher._

Presents the form to use to create a new Publisher.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishers/{id} ##

### `PATCH` /api/v1/publishers/{id}.{_format} ###

_Update existing publisher from the submitted data or create a new publisher at a specific location._

Update existing publisher from the submitted data or create a new publisher at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisher id


### `GET` /api/v1/publishers/{id}.{_format} ###

_Gets a Publisher for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Publisher id


### `PUT` /api/v1/publishers/{id}.{_format} ###

_Update existing Publisher from the submitted data or create a new Publisher at a specific location._

Update existing Publisher from the submitted data or create a new Publisher at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Publisher id


### `DELETE` /api/v1/publishers/{id}.{_format} ###

_Delete Publisher_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Publisher ID
**_format**

  - Requirement: xml|json|html


## /api/v1/publisherthemes ##

### `GET` /api/v1/publisherthemes.{_format} ###

_List all PublisherTheme._

List all PublisherTheme.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PublisherTheme.

limit:

  * Requirement: \d+
  * Description: How many PublisherTheme to return.
  * Default: 5


### `POST` /api/v1/publisherthemes.{_format} ###

_Creates a new PublisherTheme from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publisherthemes/new ##

### `GET` /api/v1/publisherthemes/new.{_format} ###

_Presents the form to use to create a new PublisherTheme._

Presents the form to use to create a new PublisherTheme.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publisherthemes/{id} ##

### `PATCH` /api/v1/publisherthemes/{id}.{_format} ###

_Update existing publishertheme from the submitted data or create a new publishertheme at a specific location._

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisher_theme id


### `GET` /api/v1/publisherthemes/{id}.{_format} ###

_Gets a PublisherTheme for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherTheme id


### `PUT` /api/v1/publisherthemes/{id}.{_format} ###

_Update existing PublisherTheme from the submitted data or create a new PublisherTheme at a specific location._

Update existing PublisherTheme from the submitted data or create a new PublisherTheme at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherTheme id


### `DELETE` /api/v1/publisherthemes/{id}.{_format} ###

_Delete PublisherTheme_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PublisherTheme ID
**_format**

  - Requirement: xml|json|html


## /api/v1/publishertypes ##

### `GET` /api/v1/publishertypes.{_format} ###

_List all PublisherTypes._

List all PublisherTypes.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing PublisherTypes.

limit:

  * Requirement: \d+
  * Description: How many PublisherTypes to return.
  * Default: 5


### `POST` /api/v1/publishertypes.{_format} ###

_Creates a new PublisherType from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishertypes/new ##

### `GET` /api/v1/publishertypes/new.{_format} ###

_Presents the form to use to create a new PublisherType._

Presents the form to use to create a new PublisherType.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/publishertypes/{id} ##

### `PATCH` /api/v1/publishertypes/{id}.{_format} ###

_Update existing publisherType from the submitted data or create a new publisherType at a specific location._

Update existing publisherType from the submitted data or create a new publisherType at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the publisherType id


### `GET` /api/v1/publishertypes/{id}.{_format} ###

_Gets a PublisherType for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherType id


### `PUT` /api/v1/publishertypes/{id}.{_format} ###

_Update existing PublisherType from the submitted data or create a new PublisherType at a specific location._

Update existing PublisherType from the submitted data or create a new PublisherType at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the PublisherType id


### `DELETE` /api/v1/publishertypes/{id}.{_format} ###

_Delete PublisherType_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: PublisherType ID
**_format**

  - Requirement: xml|json|html


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

### `PATCH` /api/v1/subjects/{id}.{_format} ###

_Update existing subject from the submitted data or create a new subject at a specific location._

Update existing subject from the submitted data or create a new subject at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the subject id


### `GET` /api/v1/subjects/{id}.{_format} ###

_Gets a Subject for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Subject id


### `PUT` /api/v1/subjects/{id}.{_format} ###

_Update existing Subject from the submitted data or create a new Subject at a specific location._

Update existing Subject from the submitted data or create a new Subject at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Subject id


### `DELETE` /api/v1/subjects/{id}.{_format} ###

_Delete Subject_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Subject ID
**_format**

  - Requirement: xml|json|html


## /api/v1/users ##

### `GET` /api/v1/users.{_format} ###

_List all Users._

List all Users.

#### Requirements ####

**_format**

  - Requirement: xml|json|html

#### Filters ####

offset:

  * Requirement: \d+
  * Description: Offset from which to start listing Users.

limit:

  * Requirement: \d+
  * Description: How many Users to return.
  * Default: 5


### `POST` /api/v1/users.{_format} ###

_Creates a new User from the submitted data._

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/users/new ##

### `GET` /api/v1/users/new.{_format} ###

_Presents the form to use to create a new User._

Presents the form to use to create a new User.

#### Requirements ####

**_format**

  - Requirement: xml|json|html


## /api/v1/users/{id} ##

### `PATCH` /api/v1/users/{id}.{_format} ###

_Update existing user from the submitted data or create a new user at a specific location._

Update existing user from the submitted data or create a new user at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the user id


### `GET` /api/v1/users/{id}.{_format} ###

_Gets a User for a given id_

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the User id

#### Response ####

username:

  * type: string

email:

  * type: string

plainPassword:

  * type: string

password:

  * type: string

firstName:

  * type: string

lastName:

  * type: string

text:

  * type: string

first_name:

  * type: string

last_name:

  * type: string

settings:

  * type: string
  * description: Json encoded settings string

about:

  * type: string

country:

  * type: object (Country)

country[id]:

  * type: integer

country[name]:

  * type: string

journal_users[]:

  * type: array of objects (JournalUser)

journal_users[][id]:

  * type: integer

journal_users[][journal]:

  * type: object (Journal)

journal_users[][journal][id]:

  * type: integer

journal_users[][journal][title_transliterated]:

  * type: string

journal_users[][journal][path]:

  * type: string

journal_users[][journal][domain]:

  * type: string

journal_users[][journal][issn]:

  * type: string

journal_users[][journal][eissn]:

  * type: string

journal_users[][journal][founded]:

  * type: DateTime

journal_users[][journal][url]:

  * type: string

journal_users[][journal][address]:

  * type: string

journal_users[][journal][phone]:

  * type: string

journal_users[][journal][email]:

  * type: string

journal_users[][journal][country]:

  * type: object (Country)

journal_users[][journal][published]:

  * type: boolean

journal_users[][journal][status]:

  * type: integer

journal_users[][journal][image]:

  * type: string

journal_users[][journal][header]:

  * type: string

journal_users[][journal][google_analytics_id]:

  * type: string

journal_users[][journal][slug]:

  * type: string

journal_users[][journal][theme]:

  * type: object (JournalTheme)

journal_users[][journal][theme][id]:

  * type: integer

journal_users[][journal][theme][title]:

  * type: string

journal_users[][journal][theme][css]:

  * type: string

journal_users[][journal][theme][public]:

  * type: boolean

journal_users[][journal][theme][journal]:

  * type: object (Journal)

journal_users[][journal][theme][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][theme][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][theme][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][theme][created]:

  * type: DateTime
  * description: @var \DateTime $created

journal_users[][journal][theme][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][design]:

  * type: object (Design)

journal_users[][journal][design][id]:

  * type: integer

journal_users[][journal][design][title]:

  * type: string

journal_users[][journal][design][content]:

  * type: string

journal_users[][journal][design][editable_content]:

  * type: string

journal_users[][journal][design][public]:

  * type: boolean

journal_users[][journal][design][owner]:

  * type: object (Journal)

journal_users[][journal][design][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][design][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][design][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][design][created]:

  * type: DateTime
  * description: @var \DateTime $created

journal_users[][journal][design][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][configured]:

  * type: boolean

journal_users[][journal][articles][]:

  * type: array of objects (Article)

journal_users[][journal][articles][][id]:

  * type: integer
  * description: auto-incremented article unique id

journal_users[][journal][articles][][abstract_transliterated]:

  * type: string
  * description: (optional) English transliterated abstract

journal_users[][journal][articles][][status]:

  * type: integer

journal_users[][journal][articles][][doi]:

  * type: string
  * description: (optional)

journal_users[][journal][articles][][title_transliterated]:

  * type: string
  * description: Roman transliterated title

journal_users[][journal][articles][][anonymous]:

  * type: boolean
  * description: Some articles carries no authorship

journal_users[][journal][articles][][submission_date]:

  * type: DateTime

journal_users[][journal][articles][][pubdate]:

  * type: DateTime

journal_users[][journal][articles][][pubdate_season]:

  * type: string

journal_users[][journal][articles][][first_page]:

  * type: integer

journal_users[][journal][articles][][last_page]:

  * type: integer

journal_users[][journal][articles][][uri]:

  * type: string

journal_users[][journal][articles][][primary_language]:

  * type: string

journal_users[][journal][articles][][order_num]:

  * type: integer

journal_users[][journal][articles][][subjects][]:

  * type: array of objects (Subject)

journal_users[][journal][articles][][subjects][][id]:

  * type: integer

journal_users[][journal][articles][][subjects][][parent]:

  * type: object (Subject)

journal_users[][journal][articles][][subjects][][translations]:

  * type: string

journal_users[][journal][articles][][languages][]:

  * type: array of objects (Lang)

journal_users[][journal][articles][][languages][][id]:

  * type: integer

journal_users[][journal][articles][][languages][][code]:

  * type: string

journal_users[][journal][articles][][languages][][name]:

  * type: string

journal_users[][journal][articles][][languages][][rtl]:

  * type: boolean

journal_users[][journal][articles][][article_type]:

  * type: object (ArticleTypes)

journal_users[][journal][articles][][article_type][id]:

  * type: integer

journal_users[][journal][articles][][article_type][translations][]:

  * type: array of objects (ArticleTypesTranslation)

journal_users[][journal][articles][][article_type][translations][][translatable]:

  * type: object (ArticleTypes)

journal_users[][journal][articles][][article_type][translations][][name]:

  * type: string

journal_users[][journal][articles][][article_type][translations][][description]:

  * type: string

journal_users[][journal][articles][][citations][]:

  * type: array of objects (Citation)

journal_users[][journal][articles][][citations][][id]:

  * type: integer

journal_users[][journal][articles][][citations][][raw]:

  * type: string

journal_users[][journal][articles][][citations][][type]:

  * type: string

journal_users[][journal][articles][][article_authors][]:

  * type: array of objects (ArticleAuthor)

journal_users[][journal][articles][][article_authors][][id]:

  * type: integer

journal_users[][journal][articles][][article_authors][][author_order]:

  * type: integer

journal_users[][journal][articles][][article_authors][][author]:

  * type: object (Author)

journal_users[][journal][articles][][article_authors][][author][id]:

  * type: integer

journal_users[][journal][articles][][article_authors][][author][first_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][middle_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][last_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][email]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][first_name_transliterated]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][middle_name_transliterated]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][last_name_transliterated]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][initials]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][address]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution]:

  * type: object (Institution)

journal_users[][journal][articles][][article_authors][][author][institution][id]:

  * type: integer

journal_users[][journal][articles][][article_authors][][author][institution][name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][address]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][city]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][country]:

  * type: object (Country)

journal_users[][journal][articles][][article_authors][][author][institution][address_lat]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][address_long]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][phone]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][fax]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][email]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][url]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][wiki]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][logo]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][header]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution][domain]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][author_details]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user]:

  * type: object (User)

journal_users[][journal][articles][][article_authors][][author][user][username]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][text]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][first_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][last_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][email]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][settings]:

  * type: string
  * description: Json encoded settings string

journal_users[][journal][articles][][article_authors][][author][user][about]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][user][country]:

  * type: object (Country)

journal_users[][journal][articles][][article_authors][][author][user][journal_users][]:

  * type: array of objects (JournalUser)

journal_users[][journal][articles][][article_authors][][author][orcid]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][institution_not_listed]:

  * type: boolean

journal_users[][journal][articles][][article_authors][][author][institution_name]:

  * type: string

journal_users[][journal][articles][][article_authors][][author][article_authors][]:

  * type: array of objects (ArticleAuthor)

journal_users[][journal][articles][][article_authors][][authorOrder]:

  * type: string

journal_users[][journal][articles][][article_files][]:

  * type: array of objects (ArticleFile)

journal_users[][journal][articles][][article_files][][id]:

  * type: integer

journal_users[][journal][articles][][article_files][][type]:

  * type: integer

journal_users[][journal][articles][][article_files][][file]:

  * type: string

journal_users[][journal][articles][][article_files][][version]:

  * type: integer

journal_users[][journal][articles][][article_files][][article]:

  * type: object (Article)

journal_users[][journal][articles][][article_files][][keywords]:

  * type: string

journal_users[][journal][articles][][article_files][][description]:

  * type: string

journal_users[][journal][articles][][article_files][][title]:

  * type: string

journal_users[][journal][articles][][article_files][][lang_code]:

  * type: string

journal_users[][journal][articles][][article_files][][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][articles][][article_files][][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][articles][][article_files][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][articles][][article_files][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][articles][][article_files][][langCode]:

  * type: string

journal_users[][journal][articles][][article_submission_files][]:

  * type: array of objects (ArticleSubmissionFile)

journal_users[][journal][articles][][article_submission_files][][id]:

  * type: integer

journal_users[][journal][articles][][article_submission_files][][title]:

  * type: string

journal_users[][journal][articles][][article_submission_files][][detail]:

  * type: string

journal_users[][journal][articles][][article_submission_files][][visible]:

  * type: boolean

journal_users[][journal][articles][][article_submission_files][][required]:

  * type: boolean

journal_users[][journal][articles][][article_submission_files][][deleted_at]:

  * type: DateTime

journal_users[][journal][articles][][article_submission_files][][article]:

  * type: object (Article)

journal_users[][journal][articles][][article_submission_files][][locale]:

  * type: string

journal_users[][journal][articles][][article_submission_files][][file]:

  * type: string

journal_users[][journal][articles][][view_count]:

  * type: integer

journal_users[][journal][articles][][download_count]:

  * type: integer

journal_users[][journal][articles][][translations]:

  * type: string

journal_users[][journal][articles][][articleFiles]:

  * type: string

journal_users[][journal][articles][][articleAuthors]:

  * type: string

journal_users[][journal][articles][][submissionDate]:

  * type: string

journal_users[][journal][issues][]:

  * type: array of objects (Issue)

journal_users[][journal][issues][][id]:

  * type: integer

journal_users[][journal][issues][][journal]:

  * type: object (Journal)

journal_users[][journal][issues][][volume]:

  * type: string

journal_users[][journal][issues][][number]:

  * type: string

journal_users[][journal][issues][][cover]:

  * type: string

journal_users[][journal][issues][][special]:

  * type: boolean

journal_users[][journal][issues][][year]:

  * type: string

journal_users[][journal][issues][][date_published]:

  * type: DateTime

journal_users[][journal][issues][][articles][]:

  * type: array of objects (Article)

journal_users[][journal][issues][][header]:

  * type: string

journal_users[][journal][issues][][supplement]:

  * type: boolean

journal_users[][journal][issues][][full_file]:

  * type: string

journal_users[][journal][issues][][issue_files][]:

  * type: array of objects (IssueFile)

journal_users[][journal][issues][][issue_files][][translations]:

  * type: string

journal_users[][journal][issues][][issue_files][][file]:

  * type: string

journal_users[][journal][issues][][issue_files][][type]:

  * type: string

journal_users[][journal][issues][][issue_files][][langCode]:

  * type: string

journal_users[][journal][issues][][view_count]:

  * type: integer

journal_users[][journal][issues][][download_count]:

  * type: integer

journal_users[][journal][issues][][translations]:

  * type: string

journal_users[][journal][languages][]:

  * type: array of objects (Lang)

journal_users[][journal][languages][][id]:

  * type: integer

journal_users[][journal][languages][][code]:

  * type: string

journal_users[][journal][languages][][name]:

  * type: string

journal_users[][journal][languages][][rtl]:

  * type: boolean

journal_users[][journal][periods][]:

  * type: array of objects (Period)

journal_users[][journal][periods][][id]:

  * type: integer

journal_users[][journal][periods][][translations][]:

  * type: array of objects (PeriodTranslation)

journal_users[][journal][periods][][translations][][translatable]:

  * type: object (Period)

journal_users[][journal][periods][][translations][][period]:

  * type: string

journal_users[][journal][periods][][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][periods][][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][periods][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][periods][][created]:

  * type: DateTime
  * description: @var \DateTime $created

journal_users[][journal][periods][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][subjects][]:

  * type: array of objects (Subject)

journal_users[][journal][subjects][][id]:

  * type: integer

journal_users[][journal][subjects][][parent]:

  * type: object (Subject)

journal_users[][journal][subjects][][translations]:

  * type: string

journal_users[][journal][publisher]:

  * type: object (Publisher)

journal_users[][journal][publisher][id]:

  * type: integer

journal_users[][journal][publisher][lft]:

  * type: integer

journal_users[][journal][publisher][name]:

  * type: string

journal_users[][journal][publisher][address]:

  * type: string

journal_users[][journal][publisher][city]:

  * type: string

journal_users[][journal][publisher][country]:

  * type: object (Country)

journal_users[][journal][publisher][address_lat]:

  * type: string

journal_users[][journal][publisher][address_long]:

  * type: string

journal_users[][journal][publisher][phone]:

  * type: string

journal_users[][journal][publisher][fax]:

  * type: string

journal_users[][journal][publisher][email]:

  * type: string

journal_users[][journal][publisher][url]:

  * type: string

journal_users[][journal][publisher][wiki]:

  * type: string

journal_users[][journal][publisher][logo]:

  * type: string

journal_users[][journal][publisher][header]:

  * type: string

journal_users[][journal][publisher][domain]:

  * type: string

journal_users[][journal][publisher][publisher_themes][]:

  * type: array of objects (PublisherTheme)

journal_users[][journal][publisher][publisher_themes][][id]:

  * type: integer

journal_users[][journal][publisher][publisher_themes][][title]:

  * type: string

journal_users[][journal][publisher][publisher_themes][][css]:

  * type: string

journal_users[][journal][publisher][publisher_themes][][public]:

  * type: boolean

journal_users[][journal][publisher][publisher_themes][][publisher]:

  * type: object (Publisher)

journal_users[][journal][publisher][publisher_themes][][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][publisher][publisher_themes][][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][publisher][publisher_themes][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][publisher][publisher_themes][][created]:

  * type: DateTime
  * description: @var \DateTime $created

journal_users[][journal][publisher][publisher_themes][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][publisher][publisher_designs][]:

  * type: array of objects (PublisherDesign)

journal_users[][journal][publisher][publisher_designs][][id]:

  * type: integer

journal_users[][journal][publisher][publisher_designs][][title]:

  * type: string

journal_users[][journal][publisher][publisher_designs][][content]:

  * type: string

journal_users[][journal][publisher][publisher_designs][][editable_content]:

  * type: string

journal_users[][journal][publisher][publisher_designs][][public]:

  * type: boolean

journal_users[][journal][publisher][publisher_designs][][publisher]:

  * type: object (Publisher)

journal_users[][journal][publisher][publisher_designs][][created_by]:

  * type: string
  * description: @var string

journal_users[][journal][publisher][publisher_designs][][updated_by]:

  * type: string
  * description: @var string

journal_users[][journal][publisher][publisher_designs][][deleted_at]:

  * type: DateTime
  * description: @var \DateTime

journal_users[][journal][publisher][publisher_designs][][created]:

  * type: DateTime
  * description: @var \DateTime $created

journal_users[][journal][publisher][publisher_designs][][updated]:

  * type: DateTime
  * description: @var \DateTime $updated

journal_users[][journal][logo]:

  * type: string

journal_users[][journal][journal_indexs][]:

  * type: array of objects (JournalIndex)

journal_users[][journal][journal_indexs][][id]:

  * type: integer

journal_users[][journal][journal_indexs][][link]:

  * type: string

journal_users[][journal][journal_indexs][][journal]:

  * type: object (Journal)

journal_users[][journal][journal_indexs][][index]:

  * type: object (Index)

journal_users[][journal][journal_indexs][][index][id]:

  * type: integer

journal_users[][journal][journal_indexs][][index][name]:

  * type: string

journal_users[][journal][journal_indexs][][index][logo]:

  * type: string

journal_users[][journal][journal_indexs][][index][status]:

  * type: boolean

journal_users[][journal][journal_indexs][][verified]:

  * type: boolean

journal_users[][journal][submission_checklist][]:

  * type: array of objects (SubmissionChecklist)

journal_users[][journal][submission_checklist][][id]:

  * type: integer

journal_users[][journal][submission_checklist][][label]:

  * type: string

journal_users[][journal][submission_checklist][][detail]:

  * type: string

journal_users[][journal][submission_checklist][][visible]:

  * type: boolean

journal_users[][journal][submission_checklist][][deleted_at]:

  * type: DateTime

journal_users[][journal][submission_checklist][][journal]:

  * type: object (Journal)

journal_users[][journal][submission_checklist][][locale]:

  * type: string

journal_users[][journal][journal_submission_files][]:

  * type: array of objects (JournalSubmissionFile)

journal_users[][journal][journal_submission_files][][journal]:

  * type: object (Journal)
  * description: @var  Journal

journal_users[][journal][journal_submission_files][][id]:

  * type: integer

journal_users[][journal][journal_submission_files][][title]:

  * type: string

journal_users[][journal][journal_submission_files][][detail]:

  * type: string

journal_users[][journal][journal_submission_files][][visible]:

  * type: boolean

journal_users[][journal][journal_submission_files][][required]:

  * type: boolean

journal_users[][journal][journal_submission_files][][deleted_at]:

  * type: DateTime

journal_users[][journal][journal_submission_files][][locale]:

  * type: string

journal_users[][journal][journal_submission_files][][file]:

  * type: string

journal_users[][journal][journal_application_upload_files][]:

  * type: array of objects (JournalApplicationUploadFile)

journal_users[][journal][journal_application_upload_files][][id]:

  * type: integer

journal_users[][journal][journal_application_upload_files][][title]:

  * type: string

journal_users[][journal][journal_application_upload_files][][detail]:

  * type: string

journal_users[][journal][journal_application_upload_files][][visible]:

  * type: boolean

journal_users[][journal][journal_application_upload_files][][required]:

  * type: boolean

journal_users[][journal][journal_application_upload_files][][deleted_at]:

  * type: DateTime

journal_users[][journal][journal_application_upload_files][][locale]:

  * type: string

journal_users[][journal][journal_application_upload_files][][file]:

  * type: string

journal_users[][journal][journal_application_upload_files][][journal]:

  * type: object (Journal)

journal_users[][journal][printed]:

  * type: boolean

journal_users[][journal][mandatory_lang]:

  * type: object (Lang)

journal_users[][journal][view_count]:

  * type: integer

journal_users[][journal][download_count]:

  * type: integer

journal_users[][journal][translations]:

  * type: string

journal_users[][journal][mandatoryLang]:

  * type: string

journal_users[][user]:

  * type: object (User)

journal_users[][user][username]:

  * type: string

journal_users[][user][text]:

  * type: string

journal_users[][user][first_name]:

  * type: string

journal_users[][user][last_name]:

  * type: string

journal_users[][user][email]:

  * type: string

journal_users[][user][settings]:

  * type: string
  * description: Json encoded settings string

journal_users[][user][about]:

  * type: string

journal_users[][user][country]:

  * type: object (Country)

journal_users[][user][journal_users][]:

  * type: array of objects (JournalUser)

journal_users[][roles][]:

  * type: array of objects (Role)

journal_users[][roles][][id]:

  * type: integer

journal_users[][roles][][name]:

  * type: string

journal_users[][roles][][role]:

  * type: string


### `PUT` /api/v1/users/{id}.{_format} ###

_Update existing User from the submitted data or create a new User at a specific location._

Update existing User from the submitted data or create a new User at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the User id
