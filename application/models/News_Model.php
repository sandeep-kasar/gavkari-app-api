<?php

class News_Model extends CI_Model{


    function __construct(){
        parent::__construct();
    }


    //insert data in news table
    public function insert_news($data){
        if ($this->db->insert("vb_news",$data)){
             return $this->db->insert_id();
        }
    }

     //insert data in news_images table
    public function insert_media($data){
        if ($this->db->insert("news_media",$data)){
            return TRUE;
        }
    }

}


?>
