<?php 

class API extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Search_result_model');
		$this->load->model('Author_info_model');
	}
 
	public function index()
	{
		$data["title"]="API Here!";
		$this->load->view('templates/header.php',$data);
		$this->load->view('templates/footer.php');
	}

	public function search($type=NULL,$query=NULL,$page=1,$pageSize=10)
	{
		$json=array();
		$data=array();
		if(!$query or !$type){
			$json["success"]=0;
			$json["reason"]="The query or type is NULL.";
		}else if(in_array($type, array("author","paper","conference","affiliation"))==false){
			$json["success"]=0;
			$json["reason"]="Invalid search type!";
		}else{
			$get_result_number="get_".$type."_number";	//I love this feature of PHP!
			$resultNum=$this->Search_result_model->$get_result_number($query);
			if($resultNum==0){
				$json["success"]=0;
				$json["reason"]="No result found.";
			}else{
				$maxPage=(int)(($resultNum-1)/$pageSize) + 1;
				if($page>$maxPage){
					$json["success"]=0;
					$json["reason"]="Out of page limit.";
					$json["resultNum"]=$resultNum;
					$json["pageSize"]=$pageSize;
					$json["maxPage"]=$maxPage;
					$json["page"]=$page;
					$json["itemNum"]=0;
				}else{
					$json["success"]=1;
					$json["resultNum"]=$resultNum;
					$json["pageSize"]=$pageSize;
					$json["maxPage"]=$maxPage;
					$json["page"]=$page;
					$begin=$pageSize*($page-1);
					$get_search_result="get_".$type."_result";	//Again, loving it!
					$json["searchResult"] = 
						$this->Search_result_model->$get_search_result($query,$begin,$pageSize);
					$json["itemNum"]=count($json["searchResult"]);
				}
			}
		}
		$data["json"]=$json;
		$this->load->view("templates/json.php",$data);
	}

	public function get_papers($type=NULL,$ID=NULL,$page=1,$pageSize=10)
	{
		$json=array();
		$data=array();
		if(!$ID or !$type){
			$json["success"]=0;
			$json["reason"]="Please specify the ID.";
		}else if(!in_array($type, array("author","conference","affiliation"))){
			$json["success"]=0;
			$json["reason"]="Invalid type!";
		}else{
			$paperNum=$this->Author_info_model->get_paper_number($ID);
			if($paperNum["all"]==0){
				$json["success"]=0;
				$json["reason"]="No paper found!";
			}else{
				$maxPage=(int)(($paperNum["all"]-1)/$pageSize)+1;
				if($page>$maxPage){
					$json["success"]=0;
					$json["paperNum"]=$paperNum;
					$json["pageSize"]=$pageSize;
					$json["maxPage"]=$maxPage;
					$json["page"]=$page;
					$json["reason"]="Out of page limit!";
				}else{
					$json["success"]=1;
					$json["paperNum"]=$paperNum;
					$json["pageSize"]=$pageSize;
					$json["maxPage"]=$maxPage;
					$json["page"]=$page;
					$begin=$pageSize*($page-1);
					$json["papers"]=$this->Author_info_model->get_papers($ID,$begin,$pageSize,$paperNum);
					$json["itemNum"]=count($json["papers"]);
				}
			}
		}
		$data["json"]=$json;
		$this->load->view("templates/json.php",$data);
	}

	public function graph($type=NULL,$query=NULL)
	{
		if(!$type or !$query){
			$data=array();
		}
		$data=array();
		$json=array();
		switch($type){
			case "author-relation":
				$json=$this->Author_info_model->get_graph_data($query);
				$data["json"]=$json;
				$this->load->view("templates/json.php",$data);
				break;
			case "author-activity":
				$json=$this->Author_info_model->get_author_activity($query);
				$data["json"]=$json;
				$this->load->view("templates/json.php",$data);
				break;
			case "author-tagcloud":
				$json=$this->Author_info_model->get_author_words($query,25);
				$data["json"]=$json;
				$this->load->view("templates/json.php",$data);
			default:
				break;
		}
	}

}

 ?>
