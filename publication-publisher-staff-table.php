<?php

//connect to mysql db
    $con = mysql_connect("localhost","root","qatar123") or die('Could not connect: ' . mysql_error());

//connect to the employee database
    mysql_select_db("qcri_kpi", $con);

// copy file content into a string var
    $json_file = file_get_contents('stats-hci.json');

// convert the string to a json object
    $jfo = json_decode($json_file);

// read the title value
  $Ename = $jfo->name;
  $keywords=$jfo->keywords[0];
  $affiliation=$jfo->affiliation;
  $Elink = $jfo->url;

//insert all Publication title,citation,year and link into publications(Table)
    $posts = $jfo->PublicationArray;
    foreach ($posts as $post) {
      $sql = "INSERT INTO `publications` (`publication_id`, `title`, `group`, `joint_groups`, `year`, `publisher_id`, `citation_count`, `pages_count`, `link`, `status`)
      VALUES (NULL, '$post->Title', '3', 'NULL', '$post->Year', '10', '$post->CitedBy', '1', '$Elink', '00')";

      if(!mysql_query($sql,$con))
      {
          die('Error : ' . mysql_error());
      }
    }

//insert publisher Name,Acronym into publisher(Table)
    $posts = $jfo->PublicationArray;
    foreach ($posts as $post) {
      $sql = "INSERT INTO `publishers` (`publisher_id`, `name`, `acronym`, `field`, `category`, `type`)
      VALUES (NULL, '$post->Venue', '', 'Hello', '0', '0')";

      if(!mysql_query($sql,$con))
      {
          die('Error : ' . mysql_error());
      }
    }

//insert all citation-Coount and h-index into staff_members(Table)
      $citation = $jfo->stats[0]->citations;
      $hindex = $jfo->stats[0]->hindex;

      $sql = "UPDATE `staff_members` SET `citation_count` = '$citation', `h_index` = '$hindex' WHERE `staff_members`.`name` = $Ename";
      if(!mysql_query($sql,$con))
          {
              die('Error : ' . mysql_error());
          }
?>
