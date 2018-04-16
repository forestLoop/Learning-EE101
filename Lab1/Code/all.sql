mysql -u root;


CREATE Database AcademicDB character SET utf8;

USE AcademicDB;


CREATE TABLE papers (PaperID char(8), Title text, PaperPublishYear integer(4), ConferenceID char(8)) DEFAULT charset utf8;


CREATE TABLE authors (AuthorID char(8), AuthorName tinytext) DEFAULT charset utf8;


CREATE TABLE conferences (ConferenceID char(8), ConferenceName tinytext)DEFAULT charset utf8;


CREATE TABLE affiliations (AffliationID char(8), AffliationName tinytext)DEFAULT charset utf8;


CREATE TABLE paper_author_affiliation (PaperID char(8), AuthorID char(8), AffliationID char(8), AuthorSequence tinyint unsigned)DEFAULT charset utf8;


CREATE TABLE paper_reference (PaperID char(8), ReferenceID char(8))DEFAULT charset utf8;


SELECT title,
       paperpublishyear
FROM papers
WHERE paperid="58EA85EE";


SELECT authorid
FROM paper_author_affiliation
WHERE paperid="58EA85EE"
ORDER BY AuthorSequence ASC;


SELECT authors.authorname
FROM authors
INNER JOIN paper_author_affiliation ON authors.authorid=paper_author_affiliation.authorid
WHERE paper_author_affiliation.paperid="58EA85EE"
ORDER BY paper_author_affiliation.AuthorSequence ASC;


SELECT count(*)
FROM paper_reference
WHERE referenceid="800F1DB6";


CREATE INDEX index_paperid ON paper_author_affiliation(paperid);

 2.54s->0.24s
