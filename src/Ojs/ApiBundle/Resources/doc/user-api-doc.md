# user #

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

  * type: 

password:

  * type: 

firstName:

  * type: 

lastName:

  * type: 

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

  * type: 

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

  * type: 

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

  * type: 

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

  * type: 

journal_users[][journal][articles][][articleFiles]:

  * type: 

journal_users[][journal][articles][][articleAuthors]:

  * type: 

journal_users[][journal][articles][][submissionDate]:

  * type: 

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

  * type: 

journal_users[][journal][issues][][issue_files][][file]:

  * type: 

journal_users[][journal][issues][][issue_files][][type]:

  * type: 

journal_users[][journal][issues][][issue_files][][langCode]:

  * type: 

journal_users[][journal][issues][][view_count]:

  * type: integer

journal_users[][journal][issues][][download_count]:

  * type: integer

journal_users[][journal][issues][][translations]:

  * type: 

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

  * type: 

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

  * type: 

journal_users[][journal][mandatoryLang]:

  * type: 

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


### `PATCH` /api/v1/users/{id}.{_format} ###

_Update existing user from the submitted data or create a new user at a specific location._

Update existing user from the submitted data or create a new user at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the user id


### `PUT` /api/v1/users/{id}.{_format} ###

_Update existing User from the submitted data or create a new User at a specific location._

Update existing User from the submitted data or create a new User at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the User id
