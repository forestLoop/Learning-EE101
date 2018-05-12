# coding:utf8

import load_data
import tensorflow as tf
import numpy as np


def convert_into_dict(X_raw):
    X = dict()
    for key in range(len(X_raw[0])):
        X[str(key)] = np.array([row[key] for row in X_raw])
    return X


def main(argv):
    # Fetch the data
    X_train, y_train, X_test, y_test = load_data.load_data()
    X_train = convert_into_dict(X_train)
    # print(X_train)
    X_test = convert_into_dict(X_test)
    # Feature columns describe how to use the input.
    my_feature_columns = []
    for key in X_train.keys():
        my_feature_columns.append(tf.feature_column.numeric_column(key=key))

    # Build 2 hidden layer DNN with 10, 10 units respectively.
    classifier = tf.estimator.DNNClassifier(
        feature_columns=my_feature_columns,
        # Two hidden layers of 10 nodes each.
        hidden_units=[10, 20, 10],
        # The model must choose between 3 classes.
        n_classes=2)

    # Train the Model.

    classifier.train(
        input_fn=lambda: load_data.train_input_fn(X_train, y_train,
                                                  200),
        steps=10000)

    # Evaluate the model.
    eval_result = classifier.evaluate(
        input_fn=lambda: load_data.eval_input_fn(X_test, y_test,
                                                 200))

    print('\nTest set accuracy: {accuracy:0.3f}\n'.format(**eval_result))


if __name__ == '__main__':
    tf.logging.set_verbosity(tf.logging.INFO)
    tf.app.run(main)
