<?php


class MyVillage extends CI_Controller
{
    public function __construct() {
         parent::__construct();
         $this->load->database();
      }


      public function getMyVillageData(){

                //get data from url
                $village_id=  $this->uri->segment('3');
                //quert to get village data
                $sql="SELECT * FROM event WHERE village_id=? and status=1 and  event_date>=DATE(NOW()) GROUP by event_date ASC";
                $query=  $this->db->query($sql,array($village_id));
                //final result
                $events=$query->result();
                //quert to get village data
                $sql="SELECT * FROM vb_news WHERE village_id=? AND status=1 AND news_date = DATE(NOW()) GROUP by news_date ASC";
                $query=  $this->db->query($sql,array($village_id));
                //final result
                $news=$query->result();

                //quert to get village data
                $sql="SELECT vb_news.*
                        FROM vb_news 
                        INNER JOIN assembly_cons
                        ON vb_news.constituency_id = assembly_cons.constituency_id
                        WHERE assembly_cons.village_id = ?";
                $query=  $this->db->query($sql,array($village_id));
                //final result
                $acnews=$query->result();

                    //show response
                    if(!empty($events) || !empty($news) || $acnews){
                        
                        //create rerspose
                        $response = array("status" => 1, "message" => "success","AssemblyNews"=>$acnews,"MyVillageNews"=>$news,"MyVillageEvent"=>$events);

                        //return data in json format
                        echo json_encode($response);

                    }else{

                        //create rerspose
                        $response = array("status" => -1, "message" => "No data Available !");

                        //return data in json format
                        echo json_encode($response);

                    }


          }

      public function getEventPhotos(){

                    //get data from url
                    $event_id=  $this->uri->segment('3');
                    //quert to get village data
                    $sql="SELECT * FROM event_media WHERE event_id=?";
                    $query=  $this->db->query($sql,array($event_id));
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
