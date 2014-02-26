<?php

     $seg4 = $this->uri->segment(4);
     header("Cache-Control: public");
     if($seg4 === "rec"){
         header("Content-Type: application/mp3");
     } else {
         header("Content-Type: application/octet-stream");
     }
     header("Content-disposition: attachment; filename=\"$url\"");
     header("Content-Type: application/force-download; filename=\"$url\"");
     header("Content-Transfer-Encoding:binary");

     if(!empty($seg4) && $seg4 == "rec"){
         readfile($url);
         exit;
     } else {
         $csv=preg_replace('/ +\"/', '"', $csv);
         $csv=preg_replace('/\" +/', '"', $csv);
         echo $csv;
         exit;
     }

?>
