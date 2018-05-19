<?php

class Author_info_model extends CI_Model{

    public function __construct()
    {
        $this->load->database();
    }

    private function handle_one_paper($row)
    {
        $temp["paperID"]=$row["PaperID"];
        $temp["paperTitle"]=$row["Title"];
        $temp["paperPublishYear"]=$row["PaperPublishYear"];
        $temp["conferenceID"]=$row["ConferenceID"];
        $temp["conferenceName"]=$row["ConferenceName"];
        $temp["citation"]=$row["citation"] ?? 0;
        $temp["authors"]=$this->get_paper_author($row["PaperID"]);
        return $temp;
    }

    public function get_paper_author($paperID=NULL)
    {
        $queryForAuthors=$this->db->query(
            "SELECT authors.AuthorName,paper_author_affiliation.*
            FROM authors, (SELECT * FROM paper_author_affiliation WHERE paperid=\"$paperID\")  paper_author_affiliation
            WHERE paper_author_affiliation.authorid=authors.authorid
            ORDER BY paper_author_affiliation.authorsequence ASC;"
        );
        $authors=array();
        foreach ( $queryForAuthors->result_array() as $subAuthor) {
            $temp["subAuthorName"]=$subAuthor["AuthorName"];
            $temp["subAuthorID"]=$subAuthor["AuthorID"];
            $temp["subAuthorSequence"]=$subAuthor["AuthorSequence"];
            array_push($authors, $temp);
        }
        return $authors;

    }


    public function get_author_info($authorID=NULL,$maxPaperNum=10)
    {
        $queryForExistence=$this->db->query("SELECT count(1) AS num FROM paper_author_affiliation WHERE authorid=\"$authorID\";");
        if($queryForExistence->row_array()["num"]==0)
            return NULL;
        $result=array();
        $queryForName=$this->db->query("SELECT AuthorName From authors where AuthorID=\"$authorID\"");
        $result["authorName"]=($queryForName->row_array())["AuthorName"];
        $result["authorDescription"]="<p>This is the description. This is the description.
            This is the description. This is the description. This is the description. This is the description.
            This is the description. This is the description. </p><p>This is the description. This is the description.
            This is the description. This is the description. This is the description. This is the description.
            This is the description. This is the description. </p>";
        $result["authorImg"]="/static/img/author.jpg";
        $queryForPaper=$this->db->query(
            "SELECT papers.*,conferences.ConferenceName,paper_reference.citation
            FROM papers,conferences,paper_author_affiliation,
                (SELECT referenceid,count(1) AS citation FROM paper_reference GROUP BY referenceid)paper_reference
            WHERE papers.paperid=paper_reference.referenceid
                AND conferences.conferenceid=papers.conferenceid
                AND papers.paperid=paper_author_affiliation.paperid
                AND paper_author_affiliation.authorid=\"$authorID\"
            ORDER BY citation DESC
            LIMIT $maxPaperNum;"
        );
        $paperCnt=0;
        $papers=array();
        foreach ($queryForPaper->result_array() as $row) {
            $paperCnt+=1;
            array_push($papers, $this->handle_one_paper($row));
        }
        if($paperCnt<10){
            $extraNum=10-$paperCnt;
            $queryForExtraPaper=$this->db->query(
                "SELECT papers.*,conferences.ConferenceName
                FROM papers,conferences,paper_author_affiliation
                WHERE papers.paperid=paper_author_affiliation.paperid AND paper_author_affiliation.authorid=\"$authorID\" AND
                    (Select count(1) FROM paper_reference WHERE paper_reference.referenceid=papers.paperid)=0 AND
                    papers.conferenceid=conferences.conferenceid
                LIMIT $extraNum;"
            );
            foreach ($queryForExtraPaper->result_array() as $row) {
                $paperCnt+=1;
                $row["citation"]=0;
                array_push($papers, $this->handle_one_paper($row));
            }
        }
        $result["papers"]=$papers;
        return $result;
    }

}

?>
