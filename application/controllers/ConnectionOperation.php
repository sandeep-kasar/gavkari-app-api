<?php

/**
 *
 */
class ConnectionOperation extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function addConnection()
  {
     //init model
     $this->load->model("Connection_Model");

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
       
       $sql="SELECT * from user WHERE id=? and village_id=?";
       $query=$this->db->query($sql,array($json->user_id,$json->village_id));
       $row=$query->row();
       if ($row==null){
           
             $village = array(
                         'user_id' => $json->user_id,
                          'village_id' => $json->village_id
                          );

              $sql="SELECT * from connection WHERE user_id=? and village_id=?";
              $query=$this->db->query($sql,array($json->user_id,$json->village_id));
              $row=$query->row();
        
              if ($row==null) {
        
                $sql="SELECT count(id) as vlg_count from connection WHERE user_id=?";
                $query=$this->db->query($sql,array($json->user_id));
                $row=$query->row();
        
                if ($row->vlg_count>5) {
                  $permission="Not allowed";
                  $result=TRUE;
                  $connection_id=0;
                }else {
                  $connection_id=$this->Connection_Model->insert_village($village);
                  if($connection_id>0){
                    $result=TRUE;
                  }
                  $permission="Allowed";
                }
        
                $this->response($result,$connection_id,$row->vlg_count,$permission);
              }else {
                //create rerspose
                  $response = array("status" => -4, "message" => "village already present");
                //return data in json format
                echo json_encode($response);
              }
           
       }else{
          $response = array("status" => -4, "message" => "You cant add your own village");
          echo json_encode($response); 
       }
     }

  }

  public function response($result,$connectionId,$connectionCount,$permission){
      //show response
      if($result){

          //create rerspose
          $response = array("status" => 1, "message" => "success","connectionId"=>$connectionId,
          "connection"=>$connectionCount,"permission" => $permission);

          //return data in json format
          echo json_encode($response);
      }else{

          //create rerspose
          $response = array("status" => -1, "message" => "false");
          //return data in json format
          echo json_encode($response);


      }

  }


  public function removeConnection()
  {

    //init model
    $this->load->model("Connection_Model");

    //get data from url
    $connection_id=  $this->uri->segment('3');

    $result=$this->Connection_Model->delete_village($connection_id);

    //show response
    if($result){

        //create rerspose
        $response = array("status" => 1, "message" => "deleted");

        //return data in json format
        echo json_encode($response);
    }else{

        //create rerspose
        $response = array("status" => -1, "message" => "false");
        //return data in json format
        echo json_encode($response);

    }

  }







}




 ?>
