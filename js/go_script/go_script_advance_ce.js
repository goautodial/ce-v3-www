/*
########################################################################################################
####  Name:             	go_script_advance_ce.js                                             ####
####  Type:                     script advance js - administrator                                   ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Franco Hora					            	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
*/

$(function(){

   $(".script-advance-tab-container").delegate("div","click",function(){

       if($(this).hasClass('script-advance-selected') === false){

           var $clicked_elem = $(this);
 
           // hide and deselect the other tab 
           $(this).toggleClass('script-advance-selected');
           $(this).siblings().each(function(){
                if($(this).hasClass("script-advance-selected")){
                    $(this).toggleClass('script-advance-selected');
                    switch($(this).attr('id')){
                         case "settings":
                                $(".script-advance-settings").hide();
                         break;
                         case "modify":
                                $(".script-advance-modify_question").hide();
                         break;
                         case "add":
                                $(".script-advance-add_question").hide();
                         break;
                         case "vicidial":
                                $(".script-vicidial-config").hide();
                         break;
                    }
                }
           });

           // display the div with 
           $(".script-advance-container").find("div[class*='"+$($clicked_elem).attr('id')+"']").each(function(){
                  $(this).show();
           });
            

       }

   });


});

$(function(){

    $('.script-advance-row').click(function(){
        
         var $click = $(this);
         $(".script-advance-modify_question > div.script-advance-value").children().each(function(){

               switch($(this).attr('name')){

                     case 'title':

                            $(this).val($($click).find("div:nth-child(2)").text());

                     break;
 
                     case 'question':

                            $(this).val($($click).find("div:nth-child(3)").attr("rel"));

                     break;

                     case 'mandatory':

                           if($(this).attr("value") === $($click).find("div:nth-child(5)").text()){
                               $(this).attr("checked",true);
                           }

                     break;

                     case 'qid':

                          $(this).val($($click).find("div:nth-child(1)").text());

                     break;

               }

         });

    });

});


$(function(){

    $("#save-button").click(function(){

         var $elems = [];
         var $selected = $(".script-advance-tab-container").find("div.script-advance-selected");
         $(".script-advance-panel").find("div[class*='"+$($selected).attr("id")+"']").children(".script-advance-value").each(function(){

              if($(this).children().is("a,img") === false ){

                  var $the_child = $(this).children();
                  if($($the_child).attr("name") !== undefined){
                  
                      if($the_child.length < 2){
                          $elems[$($the_child).attr("name")] = $($the_child).val(); 
                      } else {
                          $($the_child).each(function(){

                                if($(this).prop("checked")){

                                    $elems[$(this).attr("name")] = $(this).val();

                                } else {

                                    $elems[$(this).attr("name")] = $(this).val();

                                }

                          });
                      } 

                  }else if($($the_child).is("div.script_text")){
                      $elems[$($the_child).children("textarea").attr("name")] = encodeURIComponent($($the_child).children("textarea").text());
                  }

              }

         });

         var $submit = $.extend({selected:$($selected).attr("id")},$elems);


         if($submit.selected !== "vicidial"){
             $.post(
                $_protocol+"//"+$_host+"/index.php/go_script_ce/saveconfig",
                $submit,
                function(data){

                     if(data.indexOf("Error") !== -1){
                         alert(data);
                         return false;
                     }
                     if(data.indexOf("Success") !== -1){
                        switch($($selected).attr('id')){
                             case "settings":
                                       if($submit.active === "Y"){
                                           $active = true;
                                       } else {
                                           $active = false;
                                       }
                                       $('div[class*="settings"] > div.script-advance-value').children().each(function(){
                                         if($(this).attr("name") !== "active"){
                                             if($(this).is("img,a") === false){
                                                  if($(this).is("input")){
                                                        if($(this).attr("type") !== "hidden"){
                                                             $(this).attr("disabled",$active);
                                                        }
                                                  } else {
                                                       $(this).attr("disabled",$active);
                                                  }
                                             }
                                         }
                                       });
                                       alert(data);
                             break;
                             case "modify":

                                    var $question = ($submit.question.length < 15 ? $submit.question : $submit.question.substr(0,15) + "..." );

                                    $("#"+$submit.qid+" > div:nth-child(2)").empty();
                                    $("#"+$submit.qid+" > div:nth-child(2)").append($submit.title);
                                    $("#"+$submit.qid+" > div:nth-child(3)").empty();
                                    $("#"+$submit.qid+" > div:nth-child(3)").append($question);
                                    $("#"+$submit.qid+" > div:nth-child(3)").attr("title",$submit.question);
                                    $("#"+$submit.qid+" > div:nth-child(3)").attr("rel",$submit.question);
                                    $("#"+$submit.qid+" > div:nth-child(4)").empty();
                                    $("#"+$submit.qid+" > div:nth-child(4)").append($submit.type);
                                    alert(data);
                                    $(".toolTip").tipTip();
                             break;
                             case "add" :

                                    var $question = ($submit.question.length < 15 ? $submit.question : $submit.question.substr(0,15) + "..." );
                                    $newQ = JSON.parse(data);
                                    $(".script-advance-left > div.script-advance-modify_question").append("<div id='"+$newQ.qid+"' class='script-advance-row'>"+
                                                                                     "<div class='script-advance-col'>"+$newQ.qid+"</div>"+
                                                                                     "<div class='script-advance-col'>"+$submit.title+"</div>"+
                                                                                     "<div class='script-advance-col toolTip' title='"+$submit.question+"' rel='"+$submit.question+"'>"+$question+"</div>"+
                                                                                     "<div class='script-advance-col'>"+$submit.type+"</div>"+
                                                                                     "<div class='script-advance-col'>"+$submit.mandatory+"</div>"+
                                                                                "</div>");
                                     $(document).delegate("div#"+$newQ.qid,"click",function(){
                                             var $click = $(this);
                                             $(".script-advance-modify_question > div.script-advance-value").children().each(function(){
                                             switch($(this).attr('name')){
                                               case 'title':
                                                     $(this).val($($click).find("div:nth-child(2)").text());
                                               break;
                                               case 'question':
                                                     $(this).val($($click).find("div:nth-child(3)").attr("rel"));
                                               break;
                                               case 'mandatory':
                                                     if($(this).attr("value") === $($click).find("div:nth-child(5)").text()){
                                                           $(this).attr("checked",true);
                                                     }
                                               break;
                                               case 'qid':
                                                      $(this).val($($click).find("div:nth-child(1)").text());
                                               break;
                                             }
                                         });
                                    });
                                    alert($newQ.msg);
                                    $(".toolTip").tipTip();
                                    $.
                             break;
                        } 

                     } 
                }
             );
         }else{
            
             delete $submit.selected;
             $.post(
                           $_protocol+'//'+$_host+'/index.php/go_script_ce/modifyscript',
                           $submit,
                           function(data){
                               alert(data);
                               location.reload();
                           }
             );
         }

    });

});


