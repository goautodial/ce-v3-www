<script src="../../../js/go_search/go_search.js"></script>
<?Php
        foreach($users as $usersInfo){
                     echo '<div id="'.$usersInfo->user_id.'" class="draggable">'.$usersInfo->user.'</div>';
        }
        /*foreach($usergroupss as $usersgroup){
                     echo '<div id="'.$usersgroup->user_id.'" class="draggable">'.$usersInfo->user.'</div>';
        }*/
?>
