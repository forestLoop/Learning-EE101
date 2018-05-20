# coding:utf8
import time
import functools
import os
import feature
import tensorflow as tf
import numpy as np
from sklearn import preprocessing


def timer(func):
    @functools.wraps(func)
    def wrapper(*args, **kw):
        tic = time.time()
        result = func(*args, **kw)
        tok = time.time()
        print("Runtime:{:.3f}s".format(tok-tic))
        return result
    return wrapper


def train_input_fn(features, labels, batch_size):
    """An input function for training"""
    # Convert the inputs to a Dataset.
    dataset = tf.data.Dataset.from_tensor_slices((dict(features), labels))

    # Shuffle, repeat, and batch the examples.
    dataset = dataset.shuffle(1000).repeat().batch(batch_size)

    # Return the dataset.
    return dataset


def eval_input_fn(features, labels, batch_size):
    """An input function for evaluation or prediction"""
    features = dict(features)
    if labels is None:
        # No labels, use only features.
        inputs = features
    else:
        inputs = (features, labels)

    # Convert the inputs to a Dataset.
    dataset = tf.data.Dataset.from_tensor_slices(inputs)

    # Batch the examples
    assert batch_size is not None, "batch_size must not be None"
    dataset = dataset.batch(batch_size)

    # Return the dataset.
    return dataset


@timer
def load_from_raw_file(filepath, feature_extracter, save_into_file=None):
    with open(filepath, mode="r", encoding="utf8") as file:
        X, y = list(), list()
        i = 1
        for line in file:
            print(i, end="\r")
            i += 1
            author1, author2, relation = line.strip().split()
            # print(author1, author2, relation, sep="|")
            features = feature_extracter.extract_feature(
                author1, author2)
            X.append(features)
            y.append(int(relation))
        if(save_into_file):
            with open(save_into_file, mode="w", encoding="utf8") as saving_file:
                for feature in zip(y, X):
                    saving_file.write(
                        str(feature[0])+" "+" ".join(map(str, feature[1]))+"\n")
        return X, y


@timer
def load_from_feature_file(filepath):
    with open(filepath, mode="r", encoding="utf8") as file:
        X, y = list(), list()
        i = 1
        for line in file:
            print(i, end="\r")
            i += 1
            temp_list = line.strip().split()
            y.append(int(temp_list[0]))
            X.append(list(map(float, temp_list[1:])))
        return X, y


def load_data(data_path="../Data/"):
    try:
        extracter = feature.FeatureExtracter()
        extracter.connect("root", "", "academicdb")
    except:
        connected = False
    else:
        connected = True
    if os.path.exists(data_path+"train_feature.txt"):
        print("Start to load training data from feature file.")
        X_train, y_train = load_from_feature_file(data_path+"train_feature.txt")
    else:
        assert connected
        print("Start to load training data from raw file.")
        X_train, y_train = load_from_raw_file(
            data_path+"train.txt", extracter, save_into_file=data_path+"train_feature.txt")
    if os.path.exists(data_path+"test_feature.txt"):
        print("Start to load testinging data from feature file.")
        X_test, y_test = load_from_feature_file(data_path+"test_feature.txt")
    else:
        assert connected
        print("Start to load testing data from raw file.")
        X_test, y_test = load_from_raw_file(
            data_path+"test.txt", extracter, save_into_file=data_path+"test_feature.txt")
    return X_train, y_train, X_test, y_test


class data_set(object):
    def __init__(self):
        X_train, y_train, X_test, y_test = load_data()
        self.y_train = np.array(self.one_hot(y_train))
        self.y_test = np.array(self.one_hot(y_test))
        self.normalizer = preprocessing.Normalizer().fit(np.array(X_train))
        self.X_train = self.normalizer.transform(np.array(X_train))
        self.X_test = self.normalizer.transform(np.array(X_test))
        '''
        self.scaler = preprocessing.StandardScaler().fit(np.array(X_train))
        self.X_train = self.scaler.transform(np.array(X_train))
        self.X_test = self.scaler.transform(np.array(X_test))
        '''

    def one_hot(self, y):
        result = list()
        for yi in y:
            if yi:
                result.append([0, 1])
            else:
                result.append([1, 0])
        return result

    def train_next_batch(self, batch_size):
        mask = np.random.random_integers(
            0, self.X_train.shape[0]-1, batch_size)
        return (self.X_train[mask], self.y_train[mask])
