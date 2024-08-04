<?php

/**
 * use for connection
 */
class Connection extends CI_Controller
{

    function __construct()
    {
      parent::__construct();
      $this->load->database();
    }


    public function VillageSelectionList()
    {

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
         //query
         $sql='SELECT * FROM (SELECT *,(
                                    (
                                    (
                                    ACOS( SIN( ( ? *PI( ) /180 ) ) *SIN( (
                                    latitude  *PI( ) /180 ) ) + COS( ( ? *PI( ) /180 ) ) *COS( (
                                    latitude  *PI( ) /180 )
                                    ) *COS( (
                                    ( ?  - longitude  ) *PI( ) /180 )
                                    )
                                    )
                                    ) *180 / PI( )
                                    ) *60 * 1.1515 * 1.609344
                                    ) AS distance
                                      FROM village
                                    )village
                                    WHERE distance < 60
                                    ORDER BY distance ASC';

                                    //quert to get village list
                                    $query=  $this->db->query($sql,array($json->latitude,$json->latitude,$json->longitude));
                                    //final result
                                    $villageList=$query->result();
                                    //show response
                                    if(!empty($villageList)){

                                        //create rerspose
                                        $response = array("status" => 1, "message" => "success","VillageList"=>$villageList);

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

    public function MyConnection()
    {

      //get data from url
      $user_id=  $this->uri->segment('3');
      //quert to get village data
      $sql="SELECT * FROM connection WHERE user_id=?";
      $query=  $this->db->query($sql,array($user_id));
      //final result
      $connectionList=$query->result();

      $FinalConnectionArray=array();

      foreach ($connectionList as $connection) {

        //quert to get village data
        $sql="SELECT * FROM village WHERE id=?";
        $query=  $this->db->query($sql,array($connection->village_id));
        //final result
         $village=$query->row();

         if ($village!=null) {
           //array for events
           $conList['connection_id']=$connection->id;
           $conList['user_id']=$connection->user_id;
           $conList['village_id']=$connection->village_id;
           $conList['state_id']=$village->state_id;
           $conList['district_id']=$village->district_id;
           $conList['taluka_id']=$village->taluka_id;
           $conList['english']=$village->english;
           $conList['hindi']=$village->hindi;
           $conList['marathi']=$village->marathi;
           $conList['latitude']=$village->latitude;
           $conList['longitude']=$village->longitude;

           $FinalConnectionArray[]=$conList;
         }

     }

       //show response
       if(!empty($FinalConnectionArray)){

           //create rerspose
           $response = array("status" => 1, "message" => "success","MyConnection"=>$FinalConnectionArray);

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
