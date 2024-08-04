<?php

/**
 *
 */
class VillageDirectory extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getVillageDirectory(){

                  //get data from url
                  $village_id=  $this->uri->segment('3');
                  //quert to get directory data
                  $sql="SELECT * FROM directory WHERE village_id=?";
                  $query=  $this->db->query($sql,array($village_id));
                  //final result
                  $directory=$query->result();

                      //show response
                      if(!empty($directory)){

                          //create rerspose
                          $response = array("status" => 1, "message" => "success","directoryList"=>$directory);

                          //return data in json format
                          echo json_encode($response);

                      }else{

                          //create rerspose
                          $response = array("status" => -1, "message" => "No data Available !");

                          //return data in json format
                          echo json_encode($response);

                    }


            }
  
  public function addDirectoryContact(){

        //init model
         $this->load->model('Directory_Model');

         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-1,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);

        }else {

          //extract data from json
          $json = json_decode($jsonText);

          if ($json->business == "" || $json->business == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field business is missing or empty");
              echo json_encode($response);

          }else if ($json->b_name == "" || $json->b_name == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field name is missing or empty");
            echo json_encode($response);

          }else if ($json->b_description == "" || $json->b_description == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field  description is missing or empty");
            echo json_encode($response);

          }else if ($json->mobile == "" || $json->mobile == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field mobile is missing or empty");
            echo json_encode($response);

          }else if ($json->avatar == "" || $json->avatar == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field avatar is missing or empty");
            echo json_encode($response);

          }else {
              
            $data = array(
                 'user_id' => $json->user_id,
                 'village_id' => $json->village_id,
                 'village_boy_id' => $json->village_boy_id,
                 'business' => $json->business,
                 'b_name' => $json->b_name,
                 'b_description' => $json->b_description,
                 'mobile' => $json->mobile,
                 'avatar' => $json->avatar
            );


            //query to get data to check duplicate event
            $sql = "SELECT * FROM directory WHERE user_id= ? AND village_id=?";
            $query=$this->db->query($sql, array($json->user_id,$json->village_id));
            $row = $query->row();

            if($row==NULL){ 

               if($json->api_call == 1){
                   $insertId=$this->Directory_Model->insert_dir($data);
                   if($insertId>0){
                        $response = array("status" => 1, "message" => "success");
                        echo json_encode($response);
                     }else {
    
                       //create rerspose
                       $response = array("status" => -1, "message" => "false");
    
                       //return data in json format
                       echo json_encode($response);
                     }
               }
               
               if($json->api_call == 2){
                   $result = $this->Directory_Model->update_dir($data,$json->user_id);
                   if($result){
                        $response = array("status" => 1, "message" => "success");
                        echo json_encode($response);

                     }else {
    
                       //create rerspose
                       $response = array("status" => -1, "message" => "false");
    
                       //return data in json format
                       echo json_encode($response);
                     }
               }
               

          }else {

              $result = $this->Directory_Model->update_dir($data,$json->user_id);
                   if($result){
                        $response = array("status" => 1, "message" => "success");
                        echo json_encode($response);

                     }else {
    
                       //create rerspose
                       $response = array("status" => -1, "message" => "false");
    
                       //return data in json format
                       echo json_encode($response);
                     }

            }

           }
      }
  }
  
  public function getMyData(){

                  //get data from url
                  $user_id=  $this->uri->segment('3');
                  //quert to get directory data
                  $sql="SELECT * FROM directory WHERE user_id=?";
                  $query=  $this->db->query($sql,array($user_id));
                  //final result
                  $directory=$query->row();

                      //show response
                      if(!empty($directory)){

                          //create rerspose
                          $response = array("status" => 1, "message" => "success","directory"=>$directory);

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
