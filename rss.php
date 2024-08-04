 <?php

 $url = "https://www.loksatta.com/timuktvimukt/feed/";

$content = file_get_contents($url);
    $x = new SimpleXmlElement($content);
     
    echo "<ul>";
     
    foreach($x->channel->item as $entry) {
        echo "<li><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
    }
    echo "</ul>";

 ?>