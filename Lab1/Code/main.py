# coding: utf-8

import pymysql


class DataInserter(object):

    def __init__(self):
        self.connected = False

    def connect(self, user, password, db,
                host="localhost", port=3306, charset="utf8"):
        try:
            self.connection = pymysql.connect(
                host=host, user=user, password=password,
                db=db, port=port, charset=charset)
            self.cursor = self.connection.cursor()
        except:
            print("Failed to connect to the database!")
        else:
            self.connected = True

    def insert_from_file(self, table, file_name, to_digit=()):
        if not self.connected:
            print("Haven't connected to the database yet!")
            return
        all_line = 0
        with open(file_name, encoding="utf8", mode="r") as file:
            for line in file:
                all_line += 1
        print("{0} has {1} line{2} to handle.".format(
            file_name, all_line, "s" if all_line >= 2 else ""))
        with open(file_name, encoding="utf8", mode="r") as file:
            current_line = 1
            for line in file:
                print("{0}/{1}".format(current_line, all_line), end="\r")
                data = line.strip().split("\t")
                for i in to_digit:
                    data[i] = int(data[i])
                sql = "INSERT INTO {0} VALUES {1};".format(table, tuple(data))
                # print(sql)
                try:
                    self.cursor.execute(sql)
                except:
                    print("Failed to insert data {0} into table {1}(Line {2} of {3})".format(
                        data, table, current_line, file_name))
                    self.connection.rollback()
                    break
                current_line += 1
            self.connection.commit()
        print("Done successfully!")


if __name__ == '__main__':
    inserter = DataInserter()
    inserter.connect(user="root", password="", db="AcademicDB")
    inserter.insert_from_file(
        table="authors", file_name="data/authors.txt")
    inserter.insert_from_file(
        table="papers", file_name="data/papers.txt", to_digit=(2,))
    inserter.insert_from_file(table="affiliations",
                              file_name="data/affiliations.txt")
    inserter.insert_from_file(
        table="conferences", file_name="data/conferences.txt")
    inserter.insert_from_file(table="paper_reference",
                              file_name="data/paper_reference.txt")
    inserter.insert_from_file(
        table="paper_author_affiliation",
        file_name="data/paper_author_affiliation.txt")
