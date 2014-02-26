<style>
   .forms-labels{float:left;position:relative;vertical-align:middle;padding:5px;}
   .forms-vals{float:left;}
</style>
<?php

   echo form_open();
        echo "<div class='forms-labels'>$label</div>"; # use in call menu,did,phone
        echo "<div class='forms-vals'>".form_dropdown("option_route_value",array(),null,array("id"=>"option_route_value"))."</div>";
   echo form_close();

   echo "<br class='clear'/>";

   echo form_open();
        echo "<table>";
                echo "<tr>";
                     echo "<td>In-Group</td>";
                     echo "<td>".form_dropdown("option_route_value",array(),null,array("id"=>"option_route_value"))."</td>";
                     echo "<td>Handle Method</td>";
                     echo "<td>".form_dropdown("IGhandle_method",array(),null,array("id"=>"IGhandle_method"))."</td>";
                echo "</tr>";
                echo "<tr>";
                     echo "<td>Search Method</td>";
                     echo "<td>".form_dropdown("IGsearch_method",array(),null,array("id"=>"IGsearch_method"))."</td>";
                     echo "<td>List ID</td>";
                     echo "<td>".form_input("IGlist_id",null,array("id"=>"IGlist_id"))."</td>";
                echo "</tr>";
                echo "<tr>";
                     echo "<td>Campaign ID</td>";
                     echo "<td>".form_dropdown("IGcampaign_id",array(),null,array("id"=>"IGcampaign_id"))."</td>";
                     echo "<td>Phone Code</td>";
                     echo "<td>".form_input("IGphone_code",null,array("id"=>"IGphone_code"))."</td>";
                echo "</tr>";
                echo "<tr>";
                     echo "<td colspan='2'>VID Enter Filename</td>";
                     echo "<td colspan='2'>".form_input("IGvid_enter_filename",null,array("id"=>"IGvid_enter_filename"))."<a>audio chooser</a></td>";
                echo "</tr>";
                echo "<tr>";
                     echo "<td colspan='2'>VID ID Number Filename</td>";
                     echo "<td colspan='2'>".form_input("IGvid_id_number_filename",null,array("id"=>"IGvid_id_number_filename"))."<a>audio chooser</a></td>";
                echo "</tr>";
                echo "<tr>";
                     echo "<td colspan='2'>VID Confirm Filename</td>";
                     echo "<td colspan='2'>".form_input("IGvid_confirm_filename",null,array("id"=>"IGvid_confirm_filename"))."<a>audio chooser</a></td>";
                echo "</tr>";
                echo "<tr>";
                     echo "<td colspan='2'>VID Digits</td>";
                     echo "<td colspan='2'>".form_input("IGvid_validate_digits",null,array("id"=>"IGvid_validate_digits"))."</td>";
                echo "</tr>";
        echo "</table>";
   echo form_close();

   echo form_open();
        echo "<table>";
                echo "<tr>";
                     echo "<td>$label</td>"; # Hang up, extension , voicemail, agi
                     echo "<td>".form_input("IGvid_validate_digits",null,array("id"=>"IGvid_validate_digits"))."</td>";
                     # if(){ # condition for displaying extension context
                         #echo "<td>".form_input("option_route_value_context",null,array("id"=>"option_route_value_context"))."</td>";
                     #}
                echo "</tr>";
        echo "</table>";
   echo form_close();

?>
