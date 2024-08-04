<?php

/**
 * To access events from user selected villages
 */
class AllVillage extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getAllVillageData()
  {

    //get data from url
    $user_id=  $this->uri->segment('3');
    //quert to get village data
    $sql="SELECT * FROM connection WHERE user_id=?";
    $query=  $this->db->query($sql,array($user_id));
    //final result
    $connection=$query->result();

    // echo json_encode($connection);

    $FinalEventArray=array();
    $FinalNewsArray=array();

    foreach ($connection as $villages) {

      $village_id=$villages->village_id;

     //quert to get village name list
     $sql="SELECT * FROM village WHERE id=?";
     $query=  $this->db->query($sql,array($village_id));
     $village=$query->row();

      //quert to get village data
      $sql="SELECT * FROM event
      WHERE village_id=?
      AND status=1
      AND event_date>=DATE(NOW()) GROUP by event_date ASC";
      $query=  $this->db->query($sql,array($village_id));
      //final result
       $events=$query->result();

       if ($events!=null) {
           
           foreach ($events as $event) {
               
                //array for events
                 $eventList['id']=$event->id;
                 $eventList['user_id']=$event->user_id;
                 $eventList['village_id']=$event->village_id;
                 $eventList['status']=$event->status;
                 $eventList['village_boy_id']=$event->village_boy_id;
                 $eventList['created_at']=$event->created_at;
                 $eventList['event_date']=$event->event_date;
                 $eventList['event_date_ms']=$event->event_date_ms;
                 $eventList['latitude']=$event->latitude;
                 $eventList['longitude']=$event->longitude;
                 $eventList['address']=$event->address;
                 $eventList['contact_no']=$event->contact_no;
                 $eventList['title']=$event->title;
                 $eventList['subtitle']=$event->subtitle;
                 $eventList['family']=$event->family;
                 $eventList['muhurt']=$event->muhurt;
                 $eventList['note']=$event->note;
                 $eventList['description']=$event->description;
                 $eventList['photo']=$event->photo;
                 $eventList['english']=$village->english;
                 $eventList['hindi']=$village->hindi;
                 $eventList['marathi']=$village->marathi;
                 $FinalEventArray[]=$eventList;
               
           }
       }

      //quert to get news data
      $sql="SELECT vb_news.*,news_media.photo,village.*
      FROM vb_news,village
      JOIN news_media
      WHERE vb_news.id=news_media.news_id
      AND news_media.type=0
      AND village_id=?
      AND status=1
      GROUP by news_date ASC";
      $query=  $this->db->query($sql,array($village_id));
      //final result
      $news=$query->row();

      if ($news!=null) {
        //array for events
        $NewsList['id']=$news->id;
        $NewsList['village_id']=$news->village_id;
        $NewsList['village_boy_id']=$news->village_boy_id;
        $NewsList['news_type']=$news->news_type;
        $NewsList['created_at']=$news->created_at;
        $NewsList['news_date']=$news->news_date;
        $NewsList['news_date_ms']=$news->news_date_ms;
        $NewsList['title']=$news->title;
        $NewsList['description']=$news->description;
        $NewsList['photo']=$news->photo;
        $NewsList['english']=$village->english;
        $NewsList['hindi']=$village->hindi;
        $NewsList['marathi']=$village->marathi;
        $FinalNewsArray[]=$NewsList;
      }

 }

     //show response
     if(!empty($FinalEventArray) || !empty($FinalNewsArray)){

         //create rerspose
         $response = array("status" => 1, "message" => "success","AllVillageEvent"=>$FinalEventArray,
         "AllVillageNews"=>$FinalNewsArray);

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
