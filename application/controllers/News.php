<?php

class News extends CI_Controller
{
    public function __construct() {
         parent::__construct();
         $this->load->database();
      }

          public function getRssFeed(){

                    //get data from url
                    $dist_id =  $this->uri->segment('3');
                    //quert to get village data
                    $sql="SELECT * FROM rss_feed WHERE dist_id=?";
                    $query=  $this->db->query($sql,array($dist_id));
                    //final result
                    $feed=$query->result();

                        //show response
                        if(!empty($feed)){

                            //create rerspose
                            $response = array("status" => 1, "message" => "success","RssFeed"=>$feed);

                            //return data in json format
                            echo json_encode($response);

                        }else{

                            //create rerspose
                            $response = array("status" => -1, "message" => "No data Available !");

                            //return data in json format
                            echo json_encode($response);

                        }

              }
              
        
        public function getNewsPhotos(){

                    //get data from url
                    $news_id=  $this->uri->segment('3');
                    //quert to get village data
                    $sql="SELECT * FROM news_media WHERE news_id=?";
                    $query=  $this->db->query($sql,array($news_id));
                    //final result
                    $photos=$query->result();

                        //show response
                        if(!empty($photos)){

                            //create rerspose
                            $response = array("status" => 1, "message" => "success","photos"=>$photos);

                            //return data in json format
                            echo json_encode($response);

                        }else{

                            //create rerspose
                            $response = array("status" => -1, "message" => "No data Available !");

                            //return data in json format
                            echo json_encode($response);

                        }

              }      





}


 ?>
