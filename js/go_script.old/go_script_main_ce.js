/*
############################################################################################
####  Name:             go_script_main_ce.js                                            ####
####  Type:             ci js for script                                                ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
*/


var $active_elem = null;


// script add
$(function(){




     $('#script-add').click(function(){

           if(window.reposition !== undefined){

                var $obj = [$(".script-add-wizard-container")];

                reposition($obj);

           }

           wizard($(".script-add-wizard-container"));


     });

     $(".overlay-closer").click(function(){

         $(".processing").remove(); 
         var $boxes = [$(".script-add-wizard-container"),$(".script-preview-container"),$(".edit-form")];
         $.each($boxes,function(){
    
              if($(this).css("top") !== "-3000"){

                   if(window.closer !== undefined){
                       closer($(this));
                       if($consolidated.length > 0){
                          back("<a rel='1'>back</a>");
                          $(".processing").remove();
                       }
                   }

              }

         });

     });


});


$(function(){

    $('[id^="preview"]').click(function(){
         var $editform = $(this).parent().parent().parent();
         var $position = $($editform).position();
         var $scriptinfo = $(this).parent().siblings('.scripts-values');
         var $infovalues = [];
         var $elems = $("#modify-script").serializeArray();

         $.each($scriptinfo,function(){
             if($(this).children().length > 0){
                 var $currentElem = $(this).children().attr("id");
                 $.each($elems,function(index){
                     if($elems[index].name === $currentElem){
                         $infovalues[$currentElem] = $elems[index].value;
                     }
                 });
             }else{
                 $infovalues[$(this).attr("id")] = $(this).text();
             }
         });

         $scriptinfo = $.extend({},$infovalues,{script_text:encodeURIComponent($elems[4].value)});
         $.post(
                $_protocol+'//'+$_host+$_base_path+'/index.php/go_script_ce/previewscript',
                $scriptinfo,
                function(data){
                   var $receivedata = JSON.parse(data);
                   $('#script-preview-id').empty();
                   $('#script-preview-title').empty();
                   $('.script-preview-script').empty();
                   $('#script-preview-id').append("Preview script: "+$receivedata.script_id);
                   $('#script-preview-title').append($receivedata.script_name);
                   $('.script-preview-script').append($receivedata.display);
                   //var $preview = $('.script-preview-container').clone(true);
                   var $preview = $('.script-preview-container');
                   $($preview).height($($editform).outerHeight());
                   $($preview).offset({top:-3000,left:$position.left}).show().animate({top:70},900);
                   
                }
         );

    });

    $('.script-preview-close').click(function(){
         //$(this).parent().slideToggle(900).delay(1000);
         //$(this).parent().remove();
         $(this).parent().animate({top:-3000},2000);
    });

});

// update database
$(function(){

    $('#script-update').click(function(){
          /**/
          $('form[id="modify-script"]').validate({
               submitHandler: function(){
                    $(".edit-form").append("<div class='processing'><img src='"+$_protocol+'//'+$_host+$_base_path+'/img/goloading.gif'+"'></div>"); 
                    var $fields = [];
                    $('[id^="modify-script"]').find("div.scripts-values").each(function(){
                         if($(this).children().size() === 0){
                             $fields[$(this).attr("id")] = $(this).text();
                         } else {

                              $(this).children().each(function(){

                                  if($(this).is("a")===false && $(this).attr("id") !== "selectField"){
                                      if($(this).is('textarea') === false){
                                          $fields[$(this).attr("id")] = $(this).val();
                                      } else {
                                          $fields[$(this).attr("id")] = encodeURIComponent($(this).val());
                                      }
                                  }

                              });

                         }
                    });
                    var $finalfields = $.extend({},$fields,{});
                    $.post(
                           $_protocol+'//'+$_host+$_base_path+'/index.php/go_script_ce/modifyscript',
                           $finalfields,
                           function(data){
                               $(".elem-tbl-container").find("div[id='"+$finalfields.script_id+"'] > div:nth-child(2)").empty();
                               $(".elem-tbl-container").find("div[id='"+$finalfields.script_id+"'] > div:nth-child(2)").append($finalfields.script_name);
                               $(".elem-tbl-container").find("div[id='"+$finalfields.script_id+"'] > div:nth-child(4)").empty();
                               $(".elem-tbl-container").find("div[id='"+$finalfields.script_id+"'] > div:nth-child(4)").append(($finalfields.active==="Y"?"<span class='active'>Active</span>":"<span class='inactive'>Inactive</span>"));
                               $(".processing").remove();
                               alert(data);
                           }
                    );
               }
          });

          $('[id^="modify-script"]').children("div.scripts-values").each(function(){
                if($(this).children().size() > 0){
                    if($(this).children().size() === 1 && $(this).children().attr("id") !== "active" && $(this).children().attr('id') !== 'script_comments' && $(this).children().attr("id") !== "selectField"){
                        $(this).children().rules("add",{required:true,minlength:2,messages:{required:"* req",minlength:"atleast 2 char"} });
                    }
                    if($(this).children().size() === 4){
                         $(this).children("textarea").rules("add",{required:true,messages:{required:"* req"}});
                    }
                }
          });
 
          $("form[id^='modify-script']").submit();
    });

});

function passphp(){

    try{
       //return $active_elem;
       return $active_elem;
    }catch(err){
       console.log(err);
    }

}

$(function(){

    $('#script-tab').tabs({
       ajaxOptions:{
             data: {script_id:passphp},
             type: "POST",
             cache: false
       },
       show: function (){ // disable the advance tab?
            try{
                $(this).tabs("option","disabled",[1,2]);
            }catch(err){}
       },
       fx: {opacity : 'toggle'},
       disabled: [1],
       cache: false
    });

});
