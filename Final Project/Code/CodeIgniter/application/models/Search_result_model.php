<?php

class Search_result_model extends CI_Model{

    public function __construct()
    {
        $this->load->database();
    }

    public function get_hint($authorname=NULL)
    {
        $query=$this->db->query(
            "SELECT authors.*,paper_author_affiliation.num
            FROM authors,
                (SELECT authorid,count(1) AS num FROM paper_author_affiliation GROUP BY authorid)paper_author_affiliation
            WHERE authors.authorid=paper_author_affiliation.authorid AND authors.authorname LIKE '%$authorname%'
            ORDER BY num DESC
            limit 10;"
        );
        return $query->result_array();

    }

    public function get_author_number($authorname=NULL)
    {
        $queryForResultNumber=$this->db->query(
            "SELECT count(*) AS num FROM authors WHERE authorname LIKE '%$authorname%'");
        $result=$queryForResultNumber->result_array();
        return $result[0]["num"];
    }


    public function get_author_result($authorname=NULL,$begin=0,$num=10)
    {
        if(!$authorname)
            return NULL;
        $queryForAuthor=$this->db->query(
            "SELECT authors.*,paper_author_affiliation.num
            FROM authors,
                (SELECT authorid,count(1) AS num FROM paper_author_affiliation GROUP BY authorid)paper_author_affiliation
            WHERE authors.authorid=paper_author_affiliation.authorid AND authors.authorname LIKE '%$authorname%'
            ORDER BY num DESC, authors.authorname ASC
            LIMIT $begin,$num;"
        );
        if(!$queryForAuthor->result_array())
            return NULL;
        else{
            $result=array();
            foreach($queryForAuthor->result_array() as $row){
                $singleAuthor["authorID"]=ucwords($row["AuthorID"]);
                $singleAuthor["authorName"]=ucwords($row["AuthorName"]);
                $singleAuthor["paperNum"]=$row["num"];
                $queryForAffiliation=$this->db->query(
                    "SELECT affiliations.*
                    FROM affiliations,
                        (SELECT affiliationid,count(1)
                        FROM paper_author_affiliation
                        WHERE authorid='".$singleAuthor["authorID"]."'
                        GROUP BY affiliationid
                        ORDER BY count(1) DESC)paper_author_affiliation
                    WHERE affiliations.affiliationid=paper_author_affiliation.affiliationid
                    LIMIT 1;"
                );
                $rowAff=$queryForAffiliation->row_array();
                $singleAuthor["affiliationID"]=$rowAff["AffiliationID"]??"00000000";
                $singleAuthor["affiliationName"]=($rowAff["AffiliationName"])?ucwords($rowAff["AffiliationName"]):"NULL";
                array_push($result, $singleAuthor);
            }
            return $result;
        }
    }

    public function get_paper_number($paperTitle=NULL)
    {
        $queryForPaperNumber=$this->db->query(
            "SELECT count(*) AS num FROM papers WHERE title LIKE '%$paperTitle%';");
        $result=$queryForPaperNumber->result_array();
        return $result[0]["num"];
    }

    public function get_authors_of_paper($paperID=NULL)
    {
        $queryForAuthorsOfPaper=$this->db->query(
            "SELECT paper_author_affiliation.*, authors.AuthorName 
            FROM paper_author_affiliation,authors 
            WHERE paper_author_affiliation.authorid=authors.authorid 
                    AND paper_author_affiliation.paperid='$paperID' 
            ORDER BY paper_author_affiliation.authorsequence ASC;"
        );
        $authors=array();
        foreach($queryForAuthorsOfPaper->result_array() as $row){
            $singleAuthor["authorSequence"]=$row["AuthorSequence"];
            $singleAuthor["authorName"]=ucwords($row["AuthorName"]);
            $singleAuthor["authorID"]=$row["AuthorID"];
            array_push($authors, $singleAuthor);
        }
        return $authors;
    }

    public function get_paper_result($paperTitle=NULL,$begin=0,$num=10)
    {
        $queryForPaper=$this->db->query(
            "SELECT papers.*, conferences.ConferenceName 
             FROM papers,conferences
             WHERE papers.title LIKE '%$paperTitle%' 
                AND papers.conferenceid=conferences.conferenceid
             ORDER BY papers.citations
             LIMIT $begin,$num;"
        );
        $result=array();
        foreach($queryForPaper->result_array() as $row){
            $singlePaper["paperID"]=$row["PaperID"];
            $singlePaper["title"]=ucwords($row["Title"]);
            $singlePaper["paperPublishYear"]=$row["PaperPublishYear"];
            $singlePaper["conferenceID"]=$row["ConferenceID"];
            $singlePaper["conferenceName"]=ucwords($row["ConferenceName"]);
            $singlePaper["authors"]=$this->get_authors_of_paper($row["PaperID"]);
            array_push($result, $singlePaper);
        }
        return $result;
    }

    public function get_conference_number($conferenceName=NULL)
    {
        $queryForConferenceNumber=$this->db->query("SELECT count(*) AS num FROM conferences WHERE conferencename LIKE '%$conferenceName%';
        ");
        $result=$queryForConferenceNumber->result_array();
        return $result[0]["num"];
    }

    public function get_conference_result($conferenceName=NULL,$begin=0,$num=10)
    {
        $queryForConference=$this->db->query("
            SELECT *  FROM conferences 
            WHERE conferencename LIKE '%$conferenceName%' 
            ORDER BY influence DESC 
            LIMIT $begin,$num;"
        );
        $result=array();
        foreach($queryForConference->result_array() as $row){
            $singleConference["conferenceID"]=$row["ConferenceID"];
            $singleConference["conferenceName"]=ucwords($row["ConferenceName"]);
            $singleConference["paperNum"]=$row["PaperNum"];
            $singleConference["authorNum"]=$row["AuthorNum"];
            $singleConference["influence"]=$row["Influence"];
            array_push($result, $singleConference);
        }
        return $result;
    }


    public function get_affiliation_number($affiliationName=NULL)
    {
        $queryForAffiliationNumber=$this->db->query(
            "SELECT count(*) AS num FROM affiliations 
            WHERE affiliationname LIKE '%$affiliationName%';"
        );
        $result=$queryForAffiliationNumber->result_array();
        return $result[0]["num"];
    }

    public function get_affiliation_result($affiliationName=NULL,$begin=0,$num=10)
    {
        $queryForAffiliation=$this->db->query(
            "SELECT * FROM affiliations 
            WHERE affiliationname LIKE '%$affiliationName%' 
            ORDER BY influence DESC
            LIMIT $begin,$num;"
        );
        $result=array();
        foreach($queryForAffiliation->result_array() as $row){
            $singleAffiliation["affiliationID"]=$row["AffiliationID"];
            $singleAffiliation["affiliationName"]=ucwords($row["AffiliationName"]);
            $singleAffiliation["paperNum"]=$row["PaperNum"];
            $singleAffiliation["authorNum"]=$row["AuthorNum"];
            $singleAffiliation["influence"]=$row["Influence"];
            array_push($result, $singleAffiliation);
        }
        return $result;
    }
}
?>

