<?php

class NewsOperation extends CI_Controller{

    function __construct() {
         parent::__construct();
         $this->load->database();
      }

      public function createNews(){

        //init model
         $this->load->model('News_Model');

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

          if ($json->title == "" || $json->title == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field title is missing or empty");
              echo json_encode($response);

          }else if ($json->description == "" || $json->description == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field description is missing or empty");
              echo json_encode($response);

          }else if ($json->news_date == "" || $json->news_date == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field news_date is missing or empty");
            echo json_encode($response);

          }else if ($json->news_type == "" || $json->news_type == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field type of event is missing or empty");
            echo json_encode($response);

          }else if (empty($json->news_media)) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required news_media is missing or empty");
            echo json_encode($response);

          } else {

            $data = array(
                 'user_id' => $json->user_id,
                 'village_id' => $json->village_id,
                 'village_boy_id' => $json->village_boy_id,
                 'news_type' => $json->news_type,
                 'news_date' => $json->news_date,
                 'news_date_ms' => $json->news_date_ms,
                 'title' => $json->title,
                 'photo' => $json->photo,
                 'description' => $json->description
            );


            //query to get data to check duplicate news
            $sql = "SELECT * FROM vb_news WHERE village_boy_id= ? AND village_id=? AND news_date=?";
            $query=$this->db->query($sql, array($json->village_boy_id,$json->village_id,$json->news_date));
            $row = $query->row();

            if($row==NULL){ //first time news creation

                //insert in event table
               $newsId=$this->News_Model->insert_news($data);


               if($newsId>0){

                      //insert media
                       foreach ($json->news_media as $image) {

                         $imagesUrls = array(
                                                        'news_id'=>$newsId,
                                                        'photo' =>$image->photo
                                                       );

                         $result=$this->News_Model->insert_media($imagesUrls);

                       }

                       //show response
                       $this->response($result);


                 }else {

                   //create rerspose
                   $response = array("status" => -1, "message" => "false");

                   //return data in json format
                   echo json_encode($response);
                 }


          }else {

              //create rerspose
              $response = array("status" => -3, "message" => "News already present !");

              //return data in json format
              echo json_encode($response);

            }

           }
      }
  }


  public function response($result){
      //show response
      if($result){

          //create rerspose
          $response = array("status" => 1, "message" => "success");

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
