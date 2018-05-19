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
        $this->form_validation->set_rules('authorname', 'Author Name', 'required');
        if ($this->form_validation->run() === FALSE){
            $this->load->view('templates/home.php');
        }
        else{
            $authorName=$this->input->post("authorname");
            redirect("/result/$authorName");
        }


    }

    public function hint($term=NULL)
    {
        if($term){
            $data["queryResult"]=$this->Search_result_model->get_hint($term);
            $this->load->view("templates/hint.php",$data);
        }
    }

    public function result($authorname=NULL)
    {

        if(!$authorname){
            $data["title"]="Error";
            $data["errorMsg"]="Invalid Author Name!";
            $this->load->view("templates/header.php",$data);
            $this->load->view("templates/error.php",$data);
            $this->load->view("templates/footer.php");
        }else{
            $authorname=str_replace("%20", " ", $authorname);
            $data["title"]="Result of ".ucwords($authorname);
            $data["searchResult"]=$this->Search_result_model->get_search_result($authorname);
            $this->load->view("templates/header.php",$data);
            if(!$data["searchResult"]){
                $data["errorMsg"]="No Author Found!";
                $this->load->view("templates/error.php",$data);
            }else{
                $this->load->view("templates/result.php",$data);
            }
            $this->load->view("templates/footer.php");

        }

    }

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
            if($data["author_info"]==NULL){
                $data["title"]="Error";
                $data["errorMsg"]="Invalid Author ID!";
                $this->load->view("templates/header.php",$data);
                $this->load->view("templates/error.php",$data);
            }else{
                $data["title"]=ucwords($data["author_info"]["authorName"])."'s Page";
                $this->load->view("templates/header.php",$data);
                $this->load->view("templates/author.php",$data);
            }
            $this->load->view("templates/footer.php");

        }
    }
}

?>
