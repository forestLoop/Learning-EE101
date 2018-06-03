# coding:utf-8

import pymysql
import time
import functools


def timer(func):
    @functools.wraps(func)
    def wrapper(*args, **kw):
        tic = time.time()
        result = func(*args, **kw)
        tok = time.time()
        print("Runtime:{:.3f}s".format(tok-tic))
        return result
    return wrapper


@timer
def handle_papers_table(cursor, connection):
    print("Begin to handle papers table.")
    query_for_unhandled_papers = """SELECT PaperID FROM papers WHERE Citations IS NULL;"""
    try:
        all_number = cursor.execute(query_for_unhandled_papers)
        all_unhandled_papers = (row[0] for row in cursor.fetchall())
    except:
        print("Failed to get unhandled papers.")
    else:
        cnt = 1
        for paper_id in all_unhandled_papers:
            query_for_citations = """SELECT count(*) AS num FROM paper_reference WHERE referenceid="{0}";""".format(paper_id)
            cursor.execute(query_for_citations)
            citations = cursor.fetchone()[0]
            query_to_update = """UPDATE papers SET Citations={0} WHERE PaperID="{1}";""".format(citations, paper_id)
            assert cursor.execute(query_to_update) == 1
            cnt += 1
            print("\r{0}/{1}".format(cnt, all_number), end="")
            if cnt % 100 == 0:
                connection.commit()
        connection.commit()  # important!


@timer
def handle_authors_table(cursor, connection):
    print("Begin to handle authors table.")
    query_for_unhandled_authors = """SELECT AuthorID FROM authors WHERE PaperNum IS NULL OR Influence IS NULL;"""
    try:
        all_number = cursor.execute(query_for_unhandled_authors)
        all_unhandled_authors = (row[0] for row in cursor.fetchall())
    except:
        print("Failed to get unhandled papers.")
    else:
        cnt = 1
        for author_id in all_unhandled_authors:
            query_for_all_papers = """SELECT paper_author_affiliation.AuthorSequence, papers.Citations FROM
    		papers,paper_author_affiliation WHERE papers.paperid=paper_author_affiliation.paperid AND
    		paper_author_affiliation.authorid="{0}";""".format(author_id)
            paper_num = cursor.execute(query_for_all_papers)
            influence = 0
            for paper in cursor.fetchall():
                influence += paper[1]/paper[0]
            query_to_update = """UPDATE authors SET PaperNum={0},Influence={1} WHERE AuthorID="{2}";""".format(paper_num, influence, author_id)
            assert cursor.execute(query_to_update) == 1
            cnt += 1
            print("\r{0}/{1}".format(cnt, all_number), end="")
            if cnt % 100 == 0:
                connection.commit()
        connection.commit()


@timer
def handle_conferences_table(cursor, connection):
    print("Begin to handle authors table.")
    query_for_unhandled_conferences = """SELECT ConferenceID FROM conferences WHERE Influence IS NULL;"""
    try:
        all_number = cursor.execute(query_for_unhandled_conferences)
        all_unhandled_conferences = (row[0] for row in cursor.fetchall())
    except:
        print("Failed to get unhandled conferences.")
    else:
        cnt = 1
        for conference_id in all_unhandled_conferences:
            print("\r{0}/{1}".format(cnt, all_number), end="")
            query_for_all_papers = """SELECT PaperID,Citations FROM papers WHERE ConferenceID="{0}";""".format(conference_id)
            paper_num = cursor.execute(query_for_all_papers)
            influence = 0
            for row in cursor.fetchall():
                influence += row[1]
            query_for_author_num = """SELECT count(DISTINCT paper_author_affiliation.authorid)
	        FROM paper_author_affiliation,papers
	        WHERE papers.paperid=paper_author_affiliation.paperid AND
	        papers.conferenceid="{0}";""".format(conference_id)
            cursor.execute(query_for_author_num)
            author_num = cursor.fetchone()[0]
            query_to_update = """UPDATE conferences SET PaperNum={0},AuthorNum={1},Influence={2}
	        WHERE ConferenceID="{3}";""".format(paper_num, author_num, influence, conference_id)
            cursor.execute(query_to_update)
            cnt += 1
            if cnt % 100 == 0:
                connection.commit()
        connection.commit()


@timer
def handle_affiliations_table(cursor, connection):
    print("Begin to handle affiliation table.")
    query_for_unhandled_affiliations = """SELECT affiliationid FROM affiliations WHERE influence is NULL;"""
    try:
        all_number = cursor.execute(query_for_unhandled_affiliations)
        all_unhandled_affiliations = (row[0] for row in cursor.fetchall())
    except:
        print("Failed to get unhandled affiliations.")
    else:
        cnt = 1
        for affiliation_id in all_unhandled_affiliations:
            print("\r{0}/{1}".format(cnt, all_number), end="")
            query_for_all_papers = """SELECT papers.paperid,papers.citations
            FROM papers,paper_author_affiliation
            WHERE papers.paperid=paper_author_affiliation.paperid AND
            	paper_author_affiliation.affiliationid="{0}" GROUP BY papers.paperid;""".format(affiliation_id)
            paper_num = cursor.execute(query_for_all_papers)
            influence = 0
            for row in cursor.fetchall():
                influence += row[1]
            query_for_author_num = """SELECT count(distinct authorid) FROM paper_author_affiliation
            WHERE affiliationid="{0}";""".format(affiliation_id)
            cursor.execute(query_for_author_num)
            author_num = cursor.fetchone()[0]
            query_to_update = """UPDATE affiliations SET papernum={0},authornum={1},influence={2}
            WHERE affiliationid="{3}";""".format(paper_num, author_num, influence, affiliation_id)
            assert cursor.execute(query_to_update) == 1
            cnt += 1
            if cnt % 100 == 0:
                connection.commit()
        connection.commit()


if __name__ == '__main__':
    try:
        connection = pymysql.connect(host="localhost", port=3306, user="root", password="", db="academicdb")
        cursor = connection.cursor()
    except:
        print("Failed to connect to the database.")
    else:
        handle_papers_table(cursor, connection)
        handle_authors_table(cursor, connection)
        handle_conferences_table(cursor, connection)
        handle_affiliations_table(cursor, connection)
