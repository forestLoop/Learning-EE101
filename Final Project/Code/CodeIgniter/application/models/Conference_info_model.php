<?php
class Conference_info_model extends CI_Model{

	public function __construct()
	{
		$this->load->database();
	}

	public function get_basic_info($conferenceID)
	{
		$queryForBasicInfo=$this->db->query(
			"SELECT conferenceName,paperNum,authorNum,influence
			FROM conferences
			WHERE conferenceid='$conferenceID';"
		);
		return $queryForBasicInfo->row_array();
	}

	public function get_top_authors($conferenceID,$begin=0,$num=10)
	//authors that publish most papers in this conference
	{
		$queryForTopAuthorsID=$this->db->query(	//it's strange that if I query for the authorname aythe same time, it will get extremely slow
			"SELECT authors.authorID,count(*) as num 
			FROM authors,paper_author_affiliation,conferences,papers 
			WHERE conferences.conferenceid='$conferenceID' 
				AND conferences.conferenceid=papers.conferenceid 
				AND papers.paperid=paper_author_affiliation.paperid 
				AND paper_author_affiliation.authorid=authors.authorid 
			GROUP BY authors.authorid 
			ORDER BY num DESC 
			LIMIT $begin,$num;"
		);
		$result=array();
		foreach($queryForTopAuthorsID->result_array() as $row){
			$author=array();
			$author["authorID"]=$authorID=$row["authorID"];
			$author["papersNum"]=(int)$row["num"];
			$queryForAuthorInfo=$this->db->query(
				"SELECT * FROM authors WHERE authorid='$authorID';"
			);
			$authorInfo=$queryForAuthorInfo->row_array();
			$author["authorName"]=ucwords($authorInfo["AuthorName"]);
			$author["allPapersNum"]=(int)$authorInfo["PaperNum"];
			array_push($result, $author);
		}
		return $result;
	}

	public function get_top_papers($conferenceID,$begin=0,$num=10)
	//papers that have most citations in this conference
	{
		$queryForTopPapers=$this->db->query(
			"SELECT paperID,title,paperPublishYear,citations FROM papers WHERE conferenceid='$conferenceID' 
			ORDER BY citations DESC 
			LIMIT $begin,$num;"
		);
		$result=array();
		foreach($queryForTopPapers->result_array() as $row){
			$row["title"]=ucwords($row["title"]);
			$row["paperPublishYear"]=(int)$row["paperPublishYear"];
			$row["citations"]=(int)$row["citations"];
			array_push($result, $row);
		}
		return $result;
	}

	public function get_papers_num_yearly($conferenceID)
	{
		$queryForPapersNumYearly=$this->db->query(
			"SELECT paperPublishYear AS year,count(*) AS num 
			FROM papers 
			WHERE conferenceid='$conferenceID' 
			GROUP BY year 
			ORDER BY year ASC;"
		);
		$prevYear=0;
		$result=array();
		foreach($queryForPapersNumYearly->result_array() as $row){
			if($prevYear==0){
				$prevYear=(int)$row["year"]-1;
				array_push($result,array("year"=>$prevYear,"num"=>0));
			}
			while($prevYear+1!=$row["year"]){
				$prevYear+=1;
				array_push($result, array("year"=>$prevYear,"num"=>0));
			}
			array_push($result, array("year"=>(int)$row["year"],"num"=>(int)$row["num"]));
			$prevYear=(int)$row["year"];
		}
		array_push($result, array("year"=>$prevYear+1,"num"=>0));
		return $result;
	}

}
?>
