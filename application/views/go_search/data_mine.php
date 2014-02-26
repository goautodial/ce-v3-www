<?Php

    foreach($result->result() as $result_row){
         $row = get_object_vars($result_row);
         foreach($row as $col => $val){
             echo "<div style='float:left'>$col</div>";
             echo "<div style='float:left'>$val</div><br/>";
         }
    }


?>
