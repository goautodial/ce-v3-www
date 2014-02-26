<?
############################################################################################
####  Name:             conversation.php                                                ####
####  Type:             ci views                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <franco@goautodial.com>           ####
####  License:          AGPLv2                                                          ####
############################################################################################


foreach($xml as $level1child){

    if(array_key_exists('helpdesk-note',$level1child)){
        foreach($level1child as $helpdesknotes){
            foreach($helpdesknotes as $conversation){
          #echo '<pre>';var_dump($helpdesknotes[0]['user-info']['name']);die('</pre>');
?>
             <div class="conversation <?=$goautodial?>" >
                 <div class="responder "><?=$conversation['user-info']['name']?></div>
                 <div class="response ">
                     <?php echo $conversation['body-html']?>
                 </div>
             </div>
<?
            } # end of conversation array
        } # end of notes
    } # end of "if helpdesk-note is present"
} # end of xml loop
?>
