# coding:utf-8

import feature
import functools
import time
from sklearn.linear_model import LogisticRegression
from sklearn.naive_bayes import GaussianNB
from sklearn.svm import SVC


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
def load_from_file(filepath, feature_extracter):
    with open(filepath, mode="r", encoding="utf8") as file:
        X, y = list(), list()
        i = 1
        for line in file:
            print(i, end="\r")
            i += 1
            author1, author2, relation = line.strip().split()
            #print(author1, author2, relation, sep="|")
            features = feature_extracter.extract_feature(
                author1, author2)
            X.append(features)
            y.append(int(relation))
        return X, y


@timer
def use_logistic_regression(X_train, y_train, X_test, y_test):
    model = LogisticRegression()
    print("Start to train a logistic regression model.")
    model.fit(X_train, y_train)
    score = model.score(X_test, y_test)
    print("Score of logistic regression:", score)


@timer
def use_naive_bayes(X_train, y_train, X_test, y_test):
    model = GaussianNB()
    print("Start to train a naive bayes model.")
    model.fit(X_train, y_train)
    score = model.score(X_test, y_test)
    print("Score of naive bayes:", score)


@timer
def use_SVM(X_train, y_train, X_test, y_test):
    model = SVC()
    print("Start to train a SVM model:")
    model.fit(X_train, y_train)
    score = model.score(X_test, y_test)
    print("Score of SVM:", score)


if __name__ == '__main__':
    extracter = feature.FeatureExtracter()
    extracter.connect("root", "", "academicdb")
    print("Start to load training data from file.")
    X_train, y_train = load_from_file("../Data/train.txt", extracter)
    print("Start to load testing data from file.")
    X_test, y_test = load_from_file("../Data/test.txt", extracter)
    use_logistic_regression(X_train, y_train, X_test, y_test)
    use_naive_bayes(X_train, y_train, X_test, y_test)
    use_SVM(X_train, y_train, X_test, y_test)


'''Sample Output:
Start to load training data from file.
Runtime:54.356s
Start to load testing data from file.
Runtime:13.156s
Start to train a logistic regression model.
Score of logistic regression: 0.75
Runtime:0.019s
Start to train a naive bayes model.
Score of naive bayes: 0.720543806647
Runtime:0.010s
Start to train a SVM model:
Score of SVM: 0.690332326284
Runtime:1.448s
'''
