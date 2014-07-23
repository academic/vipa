Citation
--------

- **source **
- **type**
    - common citation types :
        - book
        - journal
        - article 
- **orderNum**




CitationSetting
----------------
A key-value citation metadata container

- **citationId **
- **setting**
- **value**

**Sample Record**

-----------------------------------------
| id | citation_id | setting | value    |
|----|-------------|---------|----------|
|  1 |           1 | doi     | 32132132 |




Description of Citation Types
-----------------------------

Citation types defined in `app/config/bibliography_params.yml` 


- **article**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_ARTICLE  

An article from a journal or magazine.

Required fields: author, title, journal, year

Optional fields: volume, number, pages, month, note, key

- **book**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_BOOK 

A book with an explicit publisher.

Required fields: author/editor, title, publisher, year

Optional fields: volume/number, series, address, edition, month, note, key

- **booklet**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_BOOKLET 

A work that is printed and bound, but without a named publisher or sponsoring institution.

Required fields: title

Optional fields: author, howpublished, address, month, year, note, key

- **conference**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_CONFERENCE 

The same as inproceedings, included for Scribe compatibility.

- **inbook**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_INBOOK 

A part of a book, usually untitled. May be a chapter (or section, etc.) and/or a range of pages.

Required fields: author/editor, title, chapter/pages, publisher, year

Optional fields: volume/number, series, type, address, edition, month, note, key

- **incollection**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_INCOLLECTION 

A part of a book having its own title.

Required fields: author, title, booktitle, publisher, year

Optional fields: editor, volume/number, series, type, chapter, pages, address, edition, month, note, key

- **inproceedings**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_INPROCEEDINGS 

An article in a conference proceedings.

Required fields: author, title, booktitle, year

Optional fields: editor, volume/number, series, pages, address, month, organization, publisher, note, key

- **manual**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_MANUAL 

Technical documentation.

Required fields: title

Optional fields: author, organization, address, edition, month, year, note, key

- **mastersthesis**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_MASTERTHESIS 

A Master's thesis.

Required fields: author, title, school, year

Optional fields: type, address, month, note, key

- **misc**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_MISC 

For use when nothing else fits.

Required fields: none

Optional fields: author, title, howpublished, month, year, note, key

- **phdthesis**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_PHDTHESIS 

A Ph.D. thesis.

Required fields: author, title, school, year

Optional fields: type, address, month, note, key

- **proceedings**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_PROCEEDINGS 

The proceedings of a conference.

Required fields: title, year

Optional fields: editor, volume/number, series, address, month, publisher, organization, note, key

- **techreport**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_TECHREPORT 

A report published by a school or other institution, usually numbered within a series.

Required fields: author, title, institution, year

Optional fields: type, number, address, month, note, key

- **unpublished**
        Ojstr\Common\Params\CitationParams::$CITATION_TYPE_UNPUBLISHED 

A document having an author and title, but not formally published.

Required fields: author, title, note

Optional fields: month, year, key




Standart Bibliographic Attributes
--------------------------------

Some Entity\CitationSetting::setting values

- **address**: Publisher's address (usually just the city, but can be the full address for lesser-known publishers)
- **annote**: An annotation for annotated bibliography styles (not typical)
- **author**: The name(s) of the author(s) (in the case of more than one author, separated by and)
- **booktitle**: The title of the book, if only part of it is being cited
- **chapter**: The chapter number
- **crossref**: The key of the cross-referenced entry
- **edition**: The edition of a book, long form (such as "First" or "Second")
- **editor**: The name(s) of the editor(s)
- **eprint**: A specification of an electronic publication, often a preprint or a technical report
- **howpublished**: How it was published, if the publishing method is nonstandard
- **institution**: The institution that was involved in the publishing, but not necessarily the publisher
- **journal**: The journal or magazine the work was published in
- **key**: A hidden field used for specifying or overriding the alphabetical order of entries (when the "author" and "editor" fields are missing). Note that this is very different from the key (mentioned just after this list) that is used to cite or cross-reference the entry.
- **month**: The month of publication (or, if unpublished, the month of creation)
- **note**: Miscellaneous extra information
- **number**: The "(issue) number" of a journal, magazine, or tech-report, if applicable. (Most publications have a "volume", but no "number" field.)
- **organization**: The conference sponsor
- **pages**: Page numbers, separated either by commas or double-hyphens.
- **publisher**: The publisher's name
- **school**: The school where the thesis was written
- **series**: The series of books the book was published in (e.g. "The Hardy Boys" or "Lecture Notes in Computer Science")
- **title**: The title of the work
- **type**: The field overriding the default type of publication (e.g. "Research Note" for techreport, "{PhD} dissertation" for phdthesis, "Section" for inbook/incollection)
- **url**: The WWW address
- **volume**: The volume of a journal or multi-volume book
- **year**: The year of publication (or, if unpublished, the year of creation)