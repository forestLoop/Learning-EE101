<?php
	class Paper_info_model extends CI_Model{

		public function __construct()
		{
			$this->load->database();
		}

		public function get_number_papers_cited_by_this($paperID=NULL)
		{
			$queryForNum=$this->db->query(
				"SELECT count(*) AS num FROM paper_reference WHERE paperid='$paperID';");
			return (int)($queryForNum->row_array()["num"]);
		}

		public function get_number_papers_citing_this($paperID=NULL)
		{
			$queryForNum=$this->db->query(
				"SELECT count(*) AS num FROM paper_reference WHERE referenceid='$paperID';");
			return (int)($queryForNum->row_array()["num"]);
		}

		public function get_papers_cited_by_this($paperID,$begin=0,$num=10)
		//get papers that are cited by this paper
		{
			$queryForCitedPapers=$this->db->query(
				"SELECT papers.*,conferences.ConferenceName 
				FROM paper_reference,papers,conferences 
				WHERE paper_reference.paperid='$paperID' 
					AND papers.paperid=paper_reference.referenceid
					AND conferences.conferenceid=papers.conferenceid
			 	ORDER BY papers.citations DESC
			 	LIMIT $begin,$num;"
			 );
			$result=array();
			foreach($queryForCitedPapers->result_array() as $row){
				$singlePaper=array();
				$singlePaper["paperID"]=$row["PaperID"];
				$singlePaper["title"]=ucwords($row["Title"]);
				$singlePaper["paperPublishYear"]=(int)$row["PaperPublishYear"];
				$singlePaper["conferenceID"]=$row["ConferenceID"];
				$singlePaper["conferenceName"]=$row["ConferenceName"];
				$singlePaper["citations"]=(int)$row["Citations"];
				array_push($result, $singlePaper);
			}
			return $result;
		}

		public function get_papers_citing_this($paperID,$begin=0,$num=10)
		//get papers that cite this paper
		{
			$queryForPapersCitingThis=$this->db->query(
				"SELECT papers.*,conferences.ConferenceName 
				FROM paper_reference,papers,conferences 
				WHERE paper_reference.referenceid='$paperID' 
					AND papers.paperid=paper_reference.paperid
					AND conferences.conferenceid=papers.conferenceid
			 	ORDER BY papers.citations DESC
			 	LIMIT $begin,$num;"
			 );
			$result=array();
			foreach($queryForPapersCitingThis->result_array() as $row){
				$singlePaper=array();
				$singlePaper["paperID"]=$row["PaperID"];
				$singlePaper["title"]=ucwords($row["Title"]);
				$singlePaper["paperPublishYear"]=(int)$row["PaperPublishYear"];
				$singlePaper["conferenceID"]=$row["ConferenceID"];
				$singlePaper["conferenceName"]=$row["ConferenceName"];
				$singlePaper["citations"]=(int)$row["Citations"];
				array_push($result, $singlePaper);
			}
			return $result;
		}

    	public function get_paper_authors($paperID=NULL)
    	//given paper's ID, returns all its authors
    	{
        	$queryForAuthors=$this->db->query(
        	    "SELECT authors.AuthorName,paper_author_affiliation.*
        	    FROM authors, (SELECT * FROM paper_author_affiliation WHERE paperid='$paperID')  paper_author_affiliation
        	    WHERE paper_author_affiliation.authorid=authors.authorid
        	    ORDER BY paper_author_affiliation.authorsequence ASC;"
        	);
        	$authors=array();
        	foreach ( $queryForAuthors->result_array() as $subAuthor) {
        	    $temp["authorName"]=ucwords($subAuthor["AuthorName"]);
        	    $temp["authorID"]=$subAuthor["AuthorID"];
        	    $temp["authorSequence"]=$subAuthor["AuthorSequence"];
        	    array_push($authors, $temp);
        	}
        	return $authors;
    	}		

		public function get_basic_info($paperID)
		{
			if(!$paperID)
				return NULL;
			$queryForBasicInfo=$this->db->query(
				"SELECT papers.*,conferences.ConferenceName 
				FROM papers,conferences 
				WHERE papers.paperid='$paperID' 
					AND papers.conferenceid=conferences.conferenceid;"
			);
			$row=$queryForBasicInfo->row_array();
			$result=array();
			$result["paperID"]=$row["PaperID"];
			$result["title"]=ucwords($row["Title"]);
			$result["paperPublishYear"]=(int)$row["PaperPublishYear"];
			$result["conferenceID"]=$row["ConferenceID"];
			$result["conferenceName"]=$row["ConferenceName"];
			$result["citations"]=$row["Citations"];
			$result["authors"]=$this->get_paper_authors($paperID);
			return $result;
		}

		public function get_citations_yearly($paperID)
		{
			$queryForCitationsYearly=$this->db->query(
				"SELECT paperPublishYear AS year,count(*) AS num FROM papers,paper_reference 
				WHERE paper_reference.referenceid='$paperID' 
					AND papers.paperid=paper_reference.paperid 
				GROUP BY paperpublishyear 
				ORDER BY paperpublishyear ASC;"
			);
			$prevYear=0;
			$result=array();
			foreach($queryForCitationsYearly->result_array() as $row){
				if($prevYear==0){
					$prevYear=(int)$row["year"]-1;
					array_push($result,array("year"=>$prevYear,"citations"=>0));
				}
				while($prevYear+1!=$row["year"]){
					$prevYear+=1;
					array_push($result, array("year"=>$prevYear,"citations"=>0));
				}
				array_push($result, array("year"=>(int)$row["year"],"citations"=>(int)$row["num"]));
				$prevYear=(int)$row["year"];
			}
			array_push($result, array("year"=>$prevYear+1,"citations"=>0));
			return $result;
		}

	}

?>
