<?php

class Page extends CI_Controller{

    public function __construct()
    {
            parent::__construct();
            $this->load->model('Search_result_model');
            $this->load->model('Author_info_model');
            $this->load->helper('url_helper');
    }

    public function index()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('queryString', 'Query String', 'required');
        $this->form_validation->set_rules('type[]','Search Type','required');
        if ($this->form_validation->run() === FALSE){
            $this->load->view('templates/home.php');
        }
        else{
            $queryString=$this->input->post("queryString");
            $type="";
            $allTypes=array("author","paper","conference","affiliation");
            for($i=0;$i!=4;$i++){
                if(in_array($allTypes[$i],$this->input->post("type[]"))){
                    $type.="1";
                }
                else
                    $type.="0";
            }
            redirect("/result/$queryString?type=$type");
        }


    }

    public function hint($term=NULL)
    {
        if($term){
            $data["queryResult"]=$this->Search_result_model->get_hint($term);
            $this->load->view("templates/hint.php",$data);
        }
    }

    public function result($query=NULL)
    {

        if(!$query){
            $data["title"]="Error";
            $data["errorMsg"]="Invalid Query!";
            $this->load->view("templates/header.php",$data);
            $this->load->view("templates/error.php",$data);
            $this->load->view("templates/footer.php");
        }else{
            $queryString=str_replace("%20", " ", $query);
            $type=$this->input->get_post("type")??"1111";
            $data["title"]="Search result of ".ucwords($queryString);
            if($type[0]){
                $data["authorNum"]=$this->Search_result_model->get_author_number($queryString);
                $data["authorResult"]=$this->Search_result_model->get_author_result($queryString);
            }
            if($type[1]){
                $data["paperNum"]=$this->Search_result_model->get_paper_numer($queryString);
                $data["paperResult"]=$this->Search_result_model->get_paper_result($queryString);
            }
            if($type[2]){
                $data["conferenceNum"]=$this->Search_result_model->get_conference_number($queryString);
                $data["conferenceResult"]=$this->Search_result_model->get_conference_result($queryString);
            }
            if($type[3]){
                $data["affiliationNum"]=$this->Search_result_model->get_affiliation_number($queryString);
                $data["affiliationResult"]=
                    $this->Search_result_model->get_affiliation_result($queryString);
            }
            $this->load->view("templates/header.php",$data);
            $this->load->view("templates/result.php",$data);
            $this->load->view("templates/footer.php");
        }   
    }
    /*
    $data["resultNum"]=$this->Search_result_model->get_result_number($authorname);
            $maxPage=(int)(($data["resultNum"]-1)/10)+1;
            $data["script"]= "
        currentPage=1;
        maxPage=$maxPage;
        pageSize=10;
        query='$authorname';
        apiUrl='/api/search';
        $(function(){
            checkButtonStatus('#resultPrev','#resultNext');
        });
    ";
            $this->load->view("templates/header.php",$data);
            if($data["resultNum"]==0){
                $data["errorMsg"]="No Result Found!";
                $this->load->view("templates/error.php",$data);
            }else{
                $data["currentPage"]=1;
                $data["maxPage"]=$maxPage;
                $data["searchResult"]=$this->Search_result_model->get_search_result($authorname);
                $this->load->view("templates/result.php",$data);
            }
            $this->load->view("templates/footer.php");

        }
*/

    public function author($authorID=NULL)
    {
        if(!$authorID){
            $data["title"]="Error";
            $data["errorMsg"]="Invalid Author ID!";
            $this->load->view("templates/header.php",$data);
            $this->load->view("templates/error.php",$data);
            $this->load->view("templates/footer.php");
        }else{
            $data["author_info"]=$this->Author_info_model->get_author_info($authorID);
            $data["paperNum"]=$this->Author_info_model->get_paper_number($authorID)["all"];
            $data["currentPage"]=1;
            $maxPage=(int)(($data["paperNum"]-1)/10)+1;
            $data["maxPage"]=$maxPage;
            $data["script"]= "
        currentPage=1;
        maxPage=$maxPage;
        pageSize=10;
        query='$authorID';
        apiUrl='/api/papers';
        $(function(){
            checkButtonStatus('#papersPrev','#papersNext');
            });
    ";
            if($data["author_info"]==NULL){
                $data["title"]="Error";
                $data["errorMsg"]="Invalid Author ID!";
                $this->load->view("templates/header.php",$data);
                $this->load->view("templates/error.php",$data);
            }else{
                $data["title"]=ucwords($data["author_info"]["authorName"])."'s Page";
                $data["extra"]="<script src=\"/static/js/relation-graph.js\"></script>";
                $this->load->view("templates/header.php",$data);
                $this->load->view("templates/author.php",$data);
            }
            
            $this->load->view("templates/footer.php",$data);

        }
    }
}

?>
