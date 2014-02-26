<script type="text/javascript" src="<?=base_url()?>js/go_common_ce.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/go_callmenu/go_callmenu_main.js"></script>
<div class="overlay"></div>


<table id="callmenu_list" class="elem-tbl">
   <tr class="elem-tbl-hdr">
       <td><strong>Menu Id</strong></td>
       <td><strong>Name</strong></td>
       <td><strong>Prompt</strong></td>
       <td><strong>Timeout</strong></td>
       <td><strong>Options</strong></td>
   </tr>
   <?php
         $ctr=0;
         if(count($result) > 0){
              foreach($result as $details){
                 echo "<tr class='".((($ctr%2)==0)?'elem-tbl-rows-even':'elem-tbl-rows-odd')."'>";
                       echo "<td>$details->menu_id</td>";
                       echo "<td>$details->menu_name</td>";
                       echo "<td>$details->menu_prompt</td>";
                       echo "<td>$details->menu_timeout</td>";
                 echo "</tr>";
                 $ctr++;
              }
         }
   ?>
</table>
