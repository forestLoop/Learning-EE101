# coding:utf8

from load_data import load_data, timer
from feature import FeatureExtracter
from sklearn.svm import SVC
from sklearn.linear_model import LogisticRegression
import pymysql
from itertools import combinations


@timer
def train_SVM(X_train, y_train):
    model = SVC(kernel="linear", C=1, gamma=0.125)
    #model = LogisticRegression()
    print("Start to train the SVM model.")
    model.fit(X_train, y_train)
    print("Finished.")
    return model


def score_SVM(model, X_test, y_test):
    return model.score(X_test, y_test)


def connect_to_db(user, password, db, host="localhost", port=3306, charset="utf8"):
    connection = pymysql.connect(host=host, user=user, password=password, db=db, port=port, charset=charset)
    cursor = connection.cursor()
    return connection, cursor


def process_one_paper(paper_id, feature_extracter, db_cursor, model):
    query_for_authors = """SELECT authorid FROM paper_author_affiliation WHERE paperid="{0}";""".format(paper_id)
    try:
        db_cursor.execute(query_for_authors)
        authors = [row[0] for row in db_cursor.fetchall()]
        authors.sort()  # notice
        # print(authors)
        # input()
        for pair in combinations(authors, 2):
            author1, author2 = pair
            query_for_existence = """SELECT * FROM author_relationship WHERE authorid1="{0}" AND authorid2="{1}";""".format(author1, author2)
            existence = db_cursor.execute(query_for_existence)
            # print(existence)
            if not existence:
                feature = feature_extracter.extract_feature(author1, author2)
                # print(feature)
                relation = model.predict([feature])[0]
                # print(relation)
                if(relation == 0):  # predict whether author2 is the instructor of author1
                    feature = feature_extracter.extract_feature(author2, author1)
                    relation = -model.predict([feature])[0]
                query_to_insert = """INSERT INTO author_relationship VALUES("{0}","{1}",{2},{3})""".format(author1, author2, 1, relation)
                db_cursor.execute(query_to_insert)
            else:
                # print("HERE!!!!!!")
                times = db_cursor.fetchone()[2]+1
                # print(times)
                query_to_update = """
    			UPDATE author_relationship SET cooperationtimes={0} WHERE authorid1="{1}" AND authorid2="{2}";""".format(times, author1, author2)
                db_cursor.execute(query_to_update)

    except Exception as e:
        print(e)


def get_all_papers(db_cursor):
    query = """SELECT paperid FROM papers;"""
    try:
        result_num = db_cursor.execute(query)
        print("Result num:{0}".format(result_num))
        for i in range(result_num):
            yield db_cursor.fetchone()[0]
    except:
        print("ERROR")


@timer
def main():
    X_train, y_train, X_test, y_test = load_data("")
    model = train_SVM(X_train, y_train)
    feature_extracter = FeatureExtracter()
    feature_extracter.connect("root", "", "academicdb")
    db_connection1, db_cursor1 = connect_to_db("root", "", "academicdb")
    db_connection2, db_cursor2 = connect_to_db("root", "", "academicdb")
    cnt = 0
    for paper in get_all_papers(db_cursor1):
        try:
            # print(paper)
            cnt += 1
            print("\r{0}".format(cnt), end="")
            process_one_paper(paper, feature_extracter, db_cursor2, model)
            if(cnt % 100 == 0):
                db_connection2.commit()

        except:
            print("ERROR")


if __name__ == '__main__':
    main()


'''
Start to load training data from feature file.
Runtime:0.239s
Start to load testinging data from feature file.
Runtime:0.147s
Start to train the SVM model.
Finished.
Runtime:4.348s
Result num:98215
98215Runtime:2473.511s
'''
