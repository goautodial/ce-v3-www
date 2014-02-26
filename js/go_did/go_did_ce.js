/*
############################################################################################
####  Name:             go_did_main_ce.js                                               ####
####  Type:             ci js for script                                                ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
*/

$(".toolTip").tipTip();


$(function(){

    $(".cols-action").delegate("a","click",function(){

          var $id = $(this).attr("id");
          if($id.indexOf("update") !== -1){

               $.post(
                      //$_protocol+$_host+$_base_path+"/index.php/go_did_ce/collectinfo",
                      "go_did_ce/collectinfo",
                      {did_id:$id.replace("-update","")},
                      function(data){
                          if(data.indexOf("Error") === -1){

                                  showModify();

                          } else {

                              alert(data);

                          }
                      }
               );

          }

    });


});


function showModify(){

     wizard($(".edit-form"),2,100,20);
    
}
