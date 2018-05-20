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

	public function search($query=NULL,$page=1,$pageSize=10)
	{
		$json=array();
		$data=array();
		if(!$query){
			$json["success"]=0;
			$json["reason"]="The query is NULL.";
		}else{
			$resultNum=$this->Search_result_model->get_result_number($query);
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
					$json["searchResult"] = 
						$this->Search_result_model->get_search_result($query,$begin,$pageSize);
					$json["itemNum"]=count($json["searchResult"]);
				}
			}
		}
		$data["json"]=$json;
		$this->load->view("templates/json.php",$data);
	}

	public function papers($ID=NULL,$page=1,$pageSize=10)
	{
		$json=array();
		$data=array();
		if(!$ID){
			$json["success"]=0;
			$json["reason"]="Please specify the ID.";
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
					$json["papers"]=$this->Author_info_model->get_author_info($ID,$begin,$pageSize,$paperNum)["papers"];
					$json["itemNum"]=count($json["papers"]);
				}
			}
		}
		$data["json"]=$json;
		$this->load->view("templates/json.php",$data);
	}

	public function graph($query=NULL,$type=1)
	{
		$data=array();
		$json=array();
		if($query!=NULL){
			
		}
	}

}

 ?>
