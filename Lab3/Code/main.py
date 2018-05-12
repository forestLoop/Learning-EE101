# coding:utf-8

from load_data import load_data, timer
from sklearn.linear_model import LogisticRegression
from sklearn.naive_bayes import GaussianNB
from sklearn.svm import SVC
from sklearn.model_selection import StratifiedShuffleSplit
from sklearn.model_selection import GridSearchCV
import numpy as np
import pandas as pd


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
def use_SVM(X_train, y_train, X_test, y_test, kernel="linear"):
    try:
        model = SVC(kernel=kernel, C=10.0, gamma=0.001)
        print("Start to train a SVM model(kernel: {0}).".format(kernel))
        model.fit(X_train, y_train)
        score = model.score(X_test, y_test)
        print("Score of SVM(kernel: {0}):".format(kernel), score)
    except:
        print("Error!")


def optimize_SVM(X_train, y_train, X_test, y_test):
    C_range = np.logspace(-4, 3, 8)
    gamma_range = np.logspace(-4, 3, 8)
    kernel_range = ["linear", "rbf"]
    param_grid = dict(gamma=gamma_range, C=C_range, kernel=kernel_range)
    grid = GridSearchCV(SVC(),
                        param_grid=param_grid, n_jobs=-1,)
    grid.fit(X_train[:100], y_train[:100])
    print("The best parameters are %s with a score of %0.2f"
          % (grid.best_params_, grid.best_score_))


if __name__ == '__main__':
    X_train, y_train, X_test, y_test = load_data()
    #use_logistic_regression(X_train, y_train, X_test, y_test)
    #use_naive_bayes(X_train, y_train, X_test, y_test)
    SVM_kernels = ["linear", "rbf", "sigmoid"]
    for kernel in SVM_kernels:
        use_SVM(X_train, y_train, X_test, y_test, kernel)
    #optimize_SVM(X_train, y_train, X_test, y_test)


'''Sample Output:
Start to load training data from file.
Runtime:54.356s
Start to load testing data from file.
Runtime:13.156s
Start to load training data from feature file.
Runtime:0.276s
Start to load testinging data from feature file.
Runtime:0.068s
Start to train a logistic regression model.
Score of logistic regression: 0.75
Runtime:0.026s
Start to train a naive bayes model.
Score of naive bayes: 0.720543806647
Runtime:0.016s
Start to train a SVM model(kernel: linear).
Score of SVM(kernel: linear): 0.730362537764
Runtime:6.807s
Start to train a SVM model(kernel: rbf).
Score of SVM(kernel: rbf): 0.690332326284
Runtime:2.324s
Start to train a SVM model(kernel: sigmoid).
Score of SVM(kernel: sigmoid): 0.615558912387
Runtime:1.207s
'''

# The best parameters are {'C': 1, 'gamma': 0.125, 'kernel': 'linear'} with a score of 0.78
