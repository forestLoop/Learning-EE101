<?php  

class Affiliation_info_model extends CI_Model{

	public function __construct()
	{
		$this->load->database();
		$this->load->model("Search_result_model");
	}

	public function get_basic_info($affiliationID)
	{
		$queryForBasicInfo=$this->db->query(
			"SELECT * FROM affiliations WHERE affiliationid='$affiliationID';"
		);
		$row=$queryForBasicInfo->row_array();
		$result=array();
		$result["affiliationName"]=ucwords($row["AffiliationName"]);
		$result["paperNum"]=$row["PaperNum"];
		$result["authorNum"]=$row["AuthorNum"];
		$result["influence"]=$row["Influence"];
		return $result;
	}

	public function get_top_authors($affiliationID,$begin=0,$num=10)
	//by top authors, I mean authors that have the strongest influence
	{
		$queryForTopAuthors=$this->db->query(
			"SELECT authors.* FROM authors,paper_author_affiliation 
			WHERE authors.authorid=paper_author_affiliation.authorid 
				AND paper_author_affiliation.affiliationid='$affiliationID' 
			GROUP BY authors.authorid
			ORDER BY influence DESC 
			LIMIT $begin,$num;"
		);
		$result=array();
		foreach($queryForTopAuthors->result_array() as $row){
			$author=array();
			$author["authorID"]=$row["AuthorID"];
			$author["authorName"]=ucwords($row["AuthorName"]);
			$author["paperNum"]=(int)$row["PaperNum"];
			$author["influence"]=number_format($row["Influence"],2);
			array_push($result, $author);
		}
		return $result;
	}

	public function get_top_papers($affiliationID,$begin=0,$num=10)
	//by top papers, I mean papers that are most cited and whose first, second or third authors are in this affiliation
	{
		$queryForTopPapers=$this->db->query(
			"SELECT papers.*,conferences.ConferenceName 
			FROM papers,paper_author_affiliation,conferences 
			WHERE papers.paperid=paper_author_affiliation.paperid 
				AND paper_author_affiliation.authorsequence<=3 
				AND paper_author_affiliation.affiliationid='$affiliationID'
				AND papers.conferenceid=conferences.conferenceid 
			GROUP BY papers.paperid 
			ORDER BY citations DESC ,paperpublishyear DESC
			LIMIT $begin,$num;"
		);
		$result=array();
		foreach ($queryForTopPapers->result_array() as $row) {
			$paper=array();
			$paper["paperID"]=$row["PaperID"];
			$paper["title"]=ucwords($row["Title"]);
			$paper["paperPublishYear"]=(int)$row["PaperPublishYear"];
			$paper["conferenceID"]=$row["ConferenceID"];
			$paper["conferenceName"]=$row["ConferenceName"];
			$paper["citations"]=$row["Citations"];
			$paper["authors"]=$this->Search_result_model->get_authors_of_paper($paper["paperID"]);
			array_push($result, $paper);
		}
		return $result;
	}

	public function get_papers_num_yearly($affiliationID)
	{
		$queryForPapersNumYearly=$this->db->query(
			"SELECT papers.paperpublishyear AS year,count(distinct papers.paperid) AS num 
			FROM papers,paper_author_affiliation 
			WHERE papers.paperid=paper_author_affiliation.paperid 
				AND paper_author_affiliation.affiliationid='$affiliationID' 
			GROUP BY paperpublishyear 
			ORDER BY paperpublishyear ASC;"
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
		//array_push($result, array("year"=>$prevYear+1,"num"=>0));
		return $result;
	}
}

?>
