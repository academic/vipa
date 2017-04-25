# journal #

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

### `DELETE` /api/v1/journals/{id}.{_format} ###

_Delete Journal_

#### Requirements ####

**id**

  - Requirement: Numeric
  - Type: integer
  - Description: Journal ID
**_format**

  - Requirement: xml|json|html


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

  * type: 

mandatoryLang:

  * type: 

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

  * type: 

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

  * type: 

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

  * type: 

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

  * type: 

theme[journal][articles][][articleFiles]:

  * type: 

theme[journal][articles][][articleAuthors]:

  * type: 

theme[journal][articles][][submissionDate]:

  * type: 

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

  * type: 

theme[journal][issues][][issue_files][][file]:

  * type: 

theme[journal][issues][][issue_files][][type]:

  * type: 

theme[journal][issues][][issue_files][][langCode]:

  * type: 

theme[journal][issues][][view_count]:

  * type: integer

theme[journal][issues][][download_count]:

  * type: integer

theme[journal][issues][][translations]:

  * type: 

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

  * type: 

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

  * type: 

theme[journal][mandatoryLang]:

  * type: 

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

  * type: 

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

  * type: 

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

  * type: 

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

  * type: 

design[owner][articles][][articleFiles]:

  * type: 

design[owner][articles][][articleAuthors]:

  * type: 

design[owner][articles][][submissionDate]:

  * type: 

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

  * type: 

design[owner][issues][][issue_files][][file]:

  * type: 

design[owner][issues][][issue_files][][type]:

  * type: 

design[owner][issues][][issue_files][][langCode]:

  * type: 

design[owner][issues][][view_count]:

  * type: integer

design[owner][issues][][download_count]:

  * type: integer

design[owner][issues][][translations]:

  * type: 

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

  * type: 

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

  * type: 

design[owner][mandatoryLang]:

  * type: 

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

  * type: 

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

  * type: 

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

  * type: 

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

  * type: 

articles[][articleFiles]:

  * type: 

articles[][articleAuthors]:

  * type: 

articles[][submissionDate]:

  * type: 

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

  * type: 

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

  * type: 

issues[][journal][mandatoryLang]:

  * type: 

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

  * type: 

issues[][issue_files][][file]:

  * type: 

issues[][issue_files][][type]:

  * type: 

issues[][issue_files][][langCode]:

  * type: 

issues[][view_count]:

  * type: integer

issues[][download_count]:

  * type: integer

issues[][translations]:

  * type: 

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

  * type: 

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

  * type: 

journal_indexs[][journal][mandatoryLang]:

  * type: 

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

  * type: 

submission_checklist[][journal][mandatoryLang]:

  * type: 

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

  * type: 

journal_submission_files[][journal][mandatoryLang]:

  * type: 

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

  * type: 

journal_application_upload_files[][journal][mandatoryLang]:

  * type: 

printed:

  * type: boolean

mandatory_lang:

  * type: object (Lang)

view_count:

  * type: integer

download_count:

  * type: integer


### `PATCH` /api/v1/journals/{id}.{_format} ###

_Update existing journal from the submitted data or create a new journal at a specific location._

Update existing journal from the submitted data or create a new journal at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the journal id


### `PUT` /api/v1/journals/{id}.{_format} ###

_Update existing Journal from the submitted data or create a new Journal at a specific location._

Update existing Journal from the submitted data or create a new Journal at a specific location.

#### Requirements ####

**_format**

  - Requirement: xml|json|html
**id**

  - Type: int
  - Description: the Journal id
