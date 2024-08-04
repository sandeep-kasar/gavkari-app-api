<?php

class Event extends CI_Controller
{
    public function __construct() { 
         parent::__construct(); 
         $this->load->database(); 
      } 
    
    public function  index(){
        
        //get json data from url
        $jsonText = file_get_contents('php://input');
        
       

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-1,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);
        }

        //extract data from json
        $json = json_decode($jsonText);
        $e_latitude=$json->latitude;
        $e_longitude=$json->longitude;
        $cat_array=$json->category;
        $distance=$json->distance;
        $country=$json->country;
        $type=$json->type;
       
        
         if($e_latitude==0 || $e_longitude==0 || $e_latitude==NULL || $e_longitude==NULL){ //if location is off
            
             //query to get data
             $sql ='SELECT * FROM event WHERE e_start_date>DATE(NOW()) AND e_country=? ORDER BY e_start_date ASC LIMIT 20';
             $query=$this->db->query($sql, array($country));
            
            if (empty($query->result())){//empty result
                
                //create rerspose
                $response = array("status" => -5, "message" => "false", "events" => $query->result());
                
                //return data in json format
                echo json_encode($response);
                
            }  else {
                
                //create rerspose
                $response = array("status" => 1, "message" => "success", "events" => $query->result());
                
                //return data in json format
                echo json_encode($response);
            }
            
            
            
        } else {//if location is on

            //init array
             $finalArray = array();
             //init distance
             $distance=0;
            
             //if distance is not given then set defautl distance as 15km
             if($distance==0){
               $distance=15;
             }  
             
             //select event by type
             //query
             $sql='SELECT * FROM (SELECT *,(
                                        (
                                        (
                                        ACOS( SIN( ( '.$e_latitude.' *PI( ) /180 ) ) *SIN( (
                                        e_latitude  *PI( ) /180 ) ) + COS( ( '.$e_latitude.' *PI( ) /180 ) ) *COS( (
                                        e_latitude  *PI( ) /180 )
                                        ) *COS( (
                                        ( '.$e_longitude.'  - e_longitude  ) *PI( ) /180 )
                                        )
                                        )
                                        ) *180 / PI( )
                                        ) *60 * 1.1515 * 1.609344
                                        ) AS distance
                                          FROM event
                                        )event
                                        WHERE distance < '.$distance.'';
            
             if($type==0){
                 
                 //get  event data as per list of categories
                foreach ($cat_array as $number) {

                    $category=$number->number;

                     //query to get data
                    $query = $this->db->query(''.$sql.' AND c_id='.$category.' AND e_start_date>DATE(NOW()) ORDER BY e_start_date ASC LIMIT 20');                

                    foreach ($query->result() as $row)
                    {
                            //We add the result array to the $finalArray
                             $finalArray[] = $row;
                    }


                }
                  
             }elseif ($type==1) {
                 
                  //query to get event by most viewed one
                $sqlQuery = ''.$sql.' AND e_start_date>DATE(NOW()) ORDER BY e_views DESC LIMIT 20';
                $query=$this->db->query($sqlQuery);
                
                foreach ($query->result() as $rowData){
                    
                    //get  event data as per list of categories
                foreach ($cat_array as $number) {

                    $category=$number->number;

                     //query to get data
                     $sql='SELECT * FROM event WHERE e_start_date>DATE(NOW()) AND e_id=?  AND c_id='.$category.'  ORDER BY e_start_date ASC LIMIT 20';
                     $query=$this->db->query($sql, array($rowData->e_id));              

                        foreach ($query->result() as $row)
                        {
                                //We add the result array to the $finalArray
                                 $finalArray[] = $row;
                                
                        }

                        
                }
             }
                 
                 
                
            }elseif ($type==2) {
                
                //query to get event by most interested one
                $sql = "select event_id, count(event_id) c from interestee group by event_id order by c desc LIMIT 20";
                $query=$this->db->query($sql);
                
                foreach ($query->result() as $rowData){
                    
                    //get  event data as per list of categories
                    foreach ($cat_array as $number) {

                        $category=$number->number;

                         //query to get data
                        $sql='SELECT * FROM event WHERE e_start_date>DATE(NOW()) AND e_id=?  AND c_id='.$category.'  ORDER BY e_start_date ASC LIMIT 20';
                        $query=$this->db->query($sql, array($rowData->event_id));              

                        foreach ($query->result() as $row)
                        {
                                //We add the result array to the $finalArray
                                 $finalArray[] = $row;
                        }


                    }
                    
                }
                
                 
            }elseif ($type==3) {
                
                //query to get event by most rated one
                $sql = "select e_id, count(e_id) c from rating group by e_id order by c desc";
                $query=$this->db->query($sql);
                
                foreach ($query->result() as $rowData){
                    
                    //get  event data as per list of categories
                    foreach ($cat_array as $number) {

                        $category=$number->number;

                         //query to get data
                        $sql='SELECT * FROM event WHERE e_start_date>DATE(NOW()) AND e_id=?  AND c_id='.$category.' ORDER BY e_start_date ASC LIMIT 20';
                        $query=$this->db->query($sql, array($rowData->e_id));

                        //(select e_id, count(e_id) c from interestee group by e_id order by c desc)                

                        foreach ($query->result() as $row)
                        {
                                //We add the result array to the $finalArray
                                 $finalArray[] = $row;
                        }


                    }
                    
                }
                
                 
            }  else {
                
                //get  event data as per list of categories
                foreach ($cat_array as $number) {

                    $category=$number->number;

                     //query to get data
                    $query = $this->db->query($sql.'AND e_start_date>DATE(NOW()) ORDER BY e_start_date ASC LIMIT 20');                

                    foreach ($query->result() as $row)
                    {
                            //We add the result array to the $finalArray
                             $finalArray[] = $row;
                    }


                }
                
            }
             

            
            
            
                if (empty($finalArray)){//empty result

                    //create rerspose
                    $response = array("status" => -5, "message" => "false", "events" => $finalArray);

                    //return data in json format
                    echo json_encode($response);

                }  else {

                    //create rerspose
                    $response = array("status" => 1, "message" => "success", "events" => $finalArray);

                    //return data in json format
                    echo json_encode($response);
                }
           
        }
        

    }
    
    public function get_event_detail(){
        
        
        
         //init model
         $this->load->model('Event_Model');
         
         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-1,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);
        }

        //extract data from json
        $json = json_decode($jsonText);
        $user_id=$json->ur_id;
        
         if ($json->event_id == "" || $json->event_id == NULL) {
             
            $response = array("status"=>-2,"message"=>"Required field event id is missing or empty");
            echo json_encode($response);
            
        } else if ($json->ur_id == "" || $json->ur_id == NULL) {
            
            $response = array("status"=>-2,"message"=>"Required field user id is missing or empty");
            echo json_encode($response);
            
        } else {
            
             //init finalarray
             $finalArray = array();
             //init array
             $RequiredArray=[];
             
             //query to get an event details
            $sql = "SELECT * FROM event WHERE e_id =? ";
            $query=$this->db->query($sql, array($json->event_id));
            $row = $query->row();
            
            //query to get an event details
            $sql = "SELECT * FROM event_images WHERE event_id =? ";
            $query=$this->db->query($sql, array($json->event_id));
            $eventImages= $query->result();
            
            //add data in array
            $ReuiredArray['e_id'] =$row->e_id;
            $ReuiredArray['c_id'] =$row->c_id;
            $ReuiredArray['ur_id'] =$row->ur_id;
            $ReuiredArray['e_views'] =$row->e_views;
            $ReuiredArray['e_discount_code'] =$row->e_discount_code;
            $ReuiredArray['e_discount_amount'] =$row->e_discount_amount;
            $ReuiredArray['e_latitude'] =$row->e_latitude;
            $ReuiredArray['e_longitude'] =$row->e_longitude;
            $ReuiredArray['e_city'] =$row->e_city;
            $ReuiredArray['e_start_date'] =$row->e_start_date;
            $ReuiredArray['e_end_date'] =$row->e_end_date;
            $ReuiredArray['e_created_at'] =$row->e_created_at;
            $ReuiredArray['e_name'] =$row->e_name;
            $ReuiredArray['e_place'] =$row->e_place;
            $ReuiredArray['e_address'] =$row->e_address;
            $ReuiredArray['e_book_link'] =$row->e_book_link;
            $ReuiredArray['e_description'] =$row->e_description;
            
            //query to get average rating and count of rate of a event
            $sql = "SELECT AVG(r_point)AS average, count(r_id)AS ratecount FROM rating WHERE e_id =? ";
            $query=$this->db->query($sql, array($json->event_id));
            $row = $query->row();
            
            if(($row->average)==NULL){
                //add data in array
               $ReuiredArray['average'] =0; 
            }  else {
                //add data in array
               $ReuiredArray['average'] = round($row->average); 
            }
            
            //add rate count
            $ReuiredArray['ratecount'] = $row->ratecount;
           
            
            //query to get average rating of a event
            $sql = "SELECT r_id FROM rating WHERE e_id =? and ur_id=?";
            $query=$this->db->query($sql, array($json->event_id,$json->ur_id));
            $r_count = $query->num_rows();
            
             //add data in array
            //0=not rated yet, 1=rated before
            $ReuiredArray['isLeaveReview'] = $r_count;
            
            //query to check user interest for the event
            $sql = "SELECT i_id FROM interestee WHERE event_id =? and user_id=?";
            $query=$this->db->query($sql, array($json->event_id,$json->ur_id));
            $i_count = $query->num_rows();
            
             //add data in array
            //0=not intrested yet, 1=shown interest before
            $ReuiredArray['isInterested'] = $i_count;
            
            
            //query to check user has shared the event or not
            $sql = "SELECT * FROM event_share WHERE e_id =? and ur_id=?";
            $query=$this->db->query($sql, array($json->event_id,$json->ur_id));
            $s_count = $query->num_rows();
             
             if( $s_count>0){ 
                 //if user has shared event
                 foreach ($query->result() as $row)
                    {
                        if(($row->share_type)==1){
                            //add data in array
                            $ReuiredArray['isShared'] =1;
                            $ReuiredArray['facebook'] = $row->share_type;
                        }elseif (($row->share_type)==2) {
                            //add data in array
                            $ReuiredArray['isShared'] =1;
                            $ReuiredArray['twwiter'] = $row->share_type;
                        }elseif (($row->share_type)==3) {
                            //add data in array
                            $ReuiredArray['isShared'] =1;
                            $ReuiredArray['instagram'] = $row->share_type;
                        }  else {
                            //add data in array
                            $ReuiredArray['isShared'] =0;
                            $ReuiredArray['facebook'] =0;
                            $ReuiredArray['twwiter'] =0;
                            $ReuiredArray['instagram'] =0;
                        }


                    }
             }  else {
                 //user did not shared the event
                 //add data in array
                $ReuiredArray['isShared'] =0;
                $ReuiredArray['facebook'] =0;
                $ReuiredArray['twwiter'] =0;
                $ReuiredArray['instagram'] =0;
             }
            
           if (empty($ReuiredArray)){//empty result

                    //create rerspose
                    $response = array("status" => -5, "message" => "false", "eventDetail" => $ReuiredArray, "eventImages" => $eventImages);

                    //return data in json format
                    echo json_encode($response);

                }  else {

                    //create rerspose
                    $response = array("status" => 1, "message" => "success", "eventDetail" => $ReuiredArray,"eventImages" => $eventImages);

                    //return data in json format
                    echo json_encode($response);
                }
            
            
        }
        
    }
    
    public function get_own_event_detail(){
         
         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-2,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);
        }

        //extract data from json
        $json = json_decode($jsonText);
        $user_id=$json->ur_id;
        $e_id=$json->e_id;
        
         if ($e_id == "" || $e_id == NULL) {
            
            $response["message"] = 'Required field event id is missing or empty';
            echo json_encode($response);
            
        } else if ($user_id == "" || $user_id == NULL) {
            
            $response["message"] = 'Required field user id is missing or empty';
            echo json_encode($response);
            
        } else {
            
             //init finalarray
             $finalArray = array();
            //init array
            $RequiredArray=[];
             
             
             //query to get average rating and count of rate of a event
            $sql = "SELECT AVG(r_point)AS average, count(r_id)AS ratecount FROM rating WHERE e_id =? ";
            $query=$this->db->query($sql, array($e_id));
            $row = $query->row();
            
            if(($row->average)==NULL){
                //add data in array
               $ReuiredArray['average'] =0; 
            }  else {
                //add data in array
               $ReuiredArray['average'] = round($row->average); 
            }
            
            //add rate count
            $ReuiredArray['ratecount'] = $row->ratecount;
            
            //query to check no of users are interested for event
            $sql = "SELECT count(i_id)AS inerestcount FROM interestee WHERE event_id =? ";
            $query=$this->db->query($sql, array($e_id));
            $row = $query->row();
            
            //add data in array
            $ReuiredArray['ineresteecount'] = $row->inerestcount;
            
            
            //query to check no of users are interested for event
            $sql = "SELECT count(es_id)AS sharecount FROM event_share WHERE e_id =? ";
            $query=$this->db->query($sql, array($e_id));
            $row = $query->row();
            
            //add data in array
            $ReuiredArray['sharecount'] = $row->sharecount;
            
            
            //query to get all review of event with user
            $sql = "SELECT event.*, user.*
                    FROM event 
                    INNER JOIN rating 
                        on event.e_id = rating.e_id
                    INNER JOIN user 
                        on rating.ur_id = user.ur_id 
                        WHERE event.e_id=?";
            $query=$this->db->query($sql, array($e_id));
            $ratingList = $query->result();
            
           if (empty($ReuiredArray)){//empty result

                    //create rerspose
                    $response = array("status" => -1, "message" => "false", "event" => $ReuiredArray, "ratings" => $ratingList);

                    //return data in json format
                    echo json_encode($response);

                }  else {

                    //create rerspose
                    $response = array("status" => 1, "message" => "success", "event" => $ReuiredArray, "ratings" => $ratingList);

                    //return data in json format
                    echo json_encode($response);
                }
            
            
        }
        
    }
    
    
}