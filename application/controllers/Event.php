  <?php

  class Event extends CI_Controller
  {
      public function __construct() {
           parent::__construct();
           $this->load->database();
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
