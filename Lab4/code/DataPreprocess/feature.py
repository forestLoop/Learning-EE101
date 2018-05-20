# coding:utf-8

import pymysql
import time


class FeatureExtracter(object):

    def __init__(self):
        self.connected = False

    def __del__(self):
        if self.connected:
            self.connection.close()
            #print("The connection has been closed safely.")

    def connect(self, user, password, db,
                host="localhost", port=3306, charset="utf8"):
        try:
            self.connection = pymysql.connect(host=host, user=user,
                                              password=password, db=db,
                                              port=port, charset=charset)
            self.cursor = self.connection.cursor()
        except:
            print("Failed to connect to the database!")
        else:
            self.connected = True

    def get_all_papers(self, author1, author2):
        if not self.connected:
            raise RuntimeError("Haven't connected to the database yet!")
        query = """
                SELECT paper_author_affiliation.*,papers.paperpublishyear
                FROM paper_author_affiliation,papers
                WHERE paper_author_affiliation.paperid=papers.paperid
                    AND ( paper_author_affiliation.authorid="{0}"
                            OR paper_author_affiliation.authorid="{1}")
                ORDER BY papers.paperpublishyear ASC,
                    paper_author_affiliation.paperid;
                """.format(author1, author2)
        try:
            self.cursor.execute(query)
        except:
            print("Something unexpected happened when handling the SQL query:")
            print(query)
            self.connection.rollback()
            return ()
        else:
            raw_result = self.cursor.fetchall()
            result = list()
            for row in raw_result:
                if len(result) == 0 or row[0] != result[-1][0]:
                    paper_info = [row[0], row[3] if row[1] == author1 else 0,
                                  row[3] if row[1] == author2 else 0, row[4]]
                    result.append(paper_info)
                else:
                    if(row[1] == author1):
                        result[-1][1] = row[3]
                    else:
                        result[-1][2] = row[3]
            return result

    def extract_feature(self, author1, author2):
        all_papers = self.get_all_papers(author1, author2)
        if not all_papers:
            return False
        '''
        for paper in all_papers:
            print(paper)
        '''
        feature = list()
        cooperations = [i for i in range(
            len(all_papers)) if all_papers[i][1] and all_papers[i][2]]
        papers_of_author1 = [i for i in range(
            len(all_papers)) if all_papers[i][1]]
        papers_of_author2 = [i for i in range(
            len(all_papers)) if all_papers[i][2]]
        feature.append(
            len([i for i in range(cooperations[0]) if all_papers[i][1]]))
        feature.append(
            len([i for i in range(cooperations[0]) if all_papers[i][2]]))
        feature.append((feature[0]-feature[1])/len(cooperations))
        feature.append(all_papers[cooperations[0]]
                       [3]-all_papers[papers_of_author1[0]][3])
        feature.append(all_papers[cooperations[0]]
                       [3]-all_papers[papers_of_author2[0]][3])
        feature.append(
            (feature[3]-feature[4])/(all_papers[cooperations[-1]][3]-all_papers[cooperations[0]][3]+1))
        feature.append(len([i for i in set(papers_of_author1).difference(set(papers_of_author2))
                            if all_papers[cooperations[0]][3] <= all_papers[i][3] <= all_papers[cooperations[-1]][3]]))

        feature.append(len([i for i in set(papers_of_author2).difference(set(papers_of_author1))
                            if all_papers[cooperations[0]][3] <= all_papers[i][3] <= all_papers[cooperations[-1]][3]]))
        feature.append((feature[6]-feature[7])/len(cooperations))
        # print(feature)
        return feature


if __name__ == '__main__':
    extracter = FeatureExtracter()
    extracter.connect("root", "", "academicdb")
    tic = time.time()
    for i in range(1000):
        extracter.extract_feature("0F0A4DA6", "75671191")
    toc = time.time()
    print(toc-tic)
