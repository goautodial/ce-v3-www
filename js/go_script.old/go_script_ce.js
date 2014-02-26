/*
############################################################################################
####  Name:             go_script_ce.js                                                 ####
####  Type:             ci js for script                                                ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
*/


$(function(){

    var $info = null;

    $(".cols-action-container").delegate("a","click",function(){

        if($(this).attr("id") === undefined){

             // if no definded then nothing to do kill here
             return false;

        }

        var $id = getId($(this));

        if($(this).attr("id").indexOf("update") > 0){

            // collects info here
            $.post(
                $_protocol+'//'+$_host+$_base_path+'/index.php/go_script_ce/getscriptinfo',
                {scriptid:$id.replace('-update','')},
                function(data){

                    $result = $.makeArray(JSON.parse(data));
                    $.each($.makeArray($(".edit-form").children().children('div')),function(){

                         var $currElem = $(this);
                         var $divfield = $($currElem).attr('id');

                         if($(this).hasClass('scripts-values')){

                              if($(this).children().length > 0){

                                   var $elemChilds = $(this).children();
                                   $.each($elemChilds,function(){
                                        var $elemChildsId = $(this).attr("id");
                                        if($result[0].hasOwnProperty($elemChildsId)){
                                             $(this).val($result[0][$elemChildsId]);
                                             $(this).attr("id",$(this).attr("id"));
                                             $(this).attr("name",$(this).attr("name"));
                                        }else{
                                             if($(this).is('a#preview')){
                                                  $(this).attr('id',"preview-"+$result[0].script_id);
                                             }
                                        }
                                   });

                              }else{

                                   if($(this).attr("id") === $divfield){
                                       $(this).empty().append($result[0][$divfield]);
                                       $(this).attr("id",$(this).attr("id"));
                                   }

                              }
                         }
                    });
                }
            );

            // reposition closer and box
            var $obj = [$(".edit-form")];
            reposition($obj);

            setTimeout('wizard($(".edit-form"));',1000);
            
        } else if($(this).attr("id").indexOf("delete") > 0) {

            if(confirm("You really want to delete this?")){
                var $scriptid = $id.replace('-delete','');
                $.post(
                   $_protocol+'//'+$_host+$_base_path+'/index.php/go_script_ce/deletescript',
                   {scriptid:$scriptid},
                   function(data){
                       alert(data);
                       if(data.indexOf('Error') === -1){
                           $('#'+$scriptid).remove();
                           paginate($(".elem-tbl-container"),$(".pager-perpage > select").val());
                       }
                   }
                );
            }

        }

    });

});

// get element id
function getId(obj){
    var $id = $(obj).attr("id");
    return $id;
}

// update textarea
function updatetextarea(obj){
     var $openTag = "--A--";
     var $closeTag = "--B--";
     var $textarea = $(obj).siblings('textarea');
     var $caretStartPos = $($textarea).prop("selectionStart");
     var $caretEndPos = $($textarea).prop("selectionEnd");
     $($textarea).val(
            $($textarea).val().substring(0,$caretStartPos)+
            $openTag+
            $(obj).siblings("select").val()+
            $closeTag+
            $($textarea).val().substring($caretEndPos,$($textarea).val().length)
     );

}

/*$(function(){

   $('.scripts').draggable({
         revert:'invalid',
         zIndex:2,
         opacity: 0.70,
         start: function (event,ui){
              $(this).data('beginningPos',ui.originalPosition);
         }
   });
   $('.trash').droppable({
              drop : function (event, ui){
                   if(!confirm("You want to delete this item?")){
                      $(ui.draggable).animate($(ui.draggable).data('beginningPos'),900);
                   }else{
                      var $elem = ui.draggable;
                      $.post(
                              $_protocol+'//'+$_host+'/index.php/go_script_ce/deletescript',
                              {scriptid:$($elem).attr('id')},
                              function(data){
                                  alert(data);
                                  if(data.indexOf("Error") === -1){
                                       $($elem).remove();
                                  }else{
                                       $(ui.draggable).animate($(ui.draggable).data('beginningPos'),900);
                                  }
                              }
                      );
                   }
              },
              hoverClass: "trash-over",
              tolerance: "pointer"
   });

});*/




var $consolidated  = [];
function next($obj){

         var $step = $($obj).attr('rel');
         var $form = $($obj).parent().siblings().children('div.scripts-values').children().find('form');

         $($form).validate({
              submitHandler: function(form){
                  if($(this).valid){
                       $(".script-wizard-content").append("<div class='processing'><img src='"+$_protocol+'//'+$_host+$_base_path+'/img/goloading.gif'+"'></div>"); 
                       var $formelems = $(form).children('div.scripts-values').children();
                       var $elem = [];
                       var $add_detail = null;
                       var $objs = null;
                       $.each($.makeArray($formelems),function(){
                           if($(this).attr('id') !== undefined){
                               $elem[$(this).attr('name')] = $(this).val();
                           }else if($(this).is('input')){
                               $elem[$(this).attr('name')] = $(this).val();
                           }
                       });
                       if($elem.hasOwnProperty('script_type')){
                          $submits = $.extend({},$elem,{step:$step});
                       }else{
                           if($consolidated.length > 0){
                                if($consolidated[1].script_type === "default"){
                                      $submits = $.extend({
                                                     step:$step,
                                                     script_type:$consolidated[$consolidated.length - 1].script_type
                                                    },
                                                    $elem);
                                } else {
                                     var $obj_cons = $.extend({},$consolidated[$consolidated.length]);
                                     $.each($obj_cons,function(indx,val){
                                         $submits = $.extend({},val);
                                     });
                                     $submits.step = $step;
                                     $submits = $.extend({},$submits,$elem);
                                }
                           }
                       }

                       $.post(
                            $_protocol+'//'+$_host+$_base_path+'/index.php/go_script_ce/scriptaddwizard',
                            $submits,
                            function(data,xhr) {
                                 if(data.indexOf('Error') === -1){
                                     if(data.indexOf('Success') === -1){
                                          result = JSON.parse(data);
                                          // redisplay the form
                                          $('.script-wizard-steps').children().each(function(){
                                               if($(this).hasClass('script-wizard-breadcrumb')){
                                                    $(this).children('img').attr('src',result.display.breadcrumb.script_wizard_breadcrumb);
                                               }else if($(this).hasClass('script-wizard-content')){
                                                    $(".processing").remove();
                                                    $(this).children().each(function(){
                                                         if($(this).hasClass('scripts-labels')){
                                                              $(this).children('img').attr('src',result.display.content.wizard_step);
                                                         }else{
                                                              $(this).empty().append(result.display.content.wizard_form_elems);
                                                         }
                                                    });
                                               }else{
                                                    $(this).empty().append(result.display.action.actions);
                                               }
                                          });
                                          $consolidated[result.prev_step.step] = result.prev_step;
                                     }else{

                                        switch($step){

                                              case "Later":
                                              case "4":

                                                   $(".processing").remove();
                                                   alert(data);
                                                   location.reload();
    
                                              break;

                                              case "Now":

                                                   $active_elem  = $submits.script_id;
                                                   $("#script-tab").tabs("option","disabled",[]);
                                                   $('#script-tab').tabs("url",1,$_protocol+"//"+$_host+$_base_path+"/index.php/go_script_ce/scriptadvance");
                                                   $('#script-tab').tabs("select",1);
                                                   $(".processing").remove();
                                                   closer($(".script-add-wizard-container"));  

                                              break;

                                        }

                                     }
                                 }else{
                                     $(".processing").remove();
                                     alert(data);
                                 }
                            }
                       );
                  }
              }
         });

         $.each($($form).children('div.scripts-values').children(),function(){
              $elemId = $(this).attr('id');
              if($elemId !== undefined ){
                  if($(this).is('select')===false){
                      $(this).rules("add",{required: true,minlength:2,messages:{required:"*req",minlength:"2 char"}});
                  }else{
                     if($(this).is("select")){
                         if($(this).attr("id") === "campaign_id"){
                             $(this).rules("add",{required: true,messages:{required:"please create camapaign"}});
                         }
                     }
                  }
              }
         });

         $($form).submit();
}


function back($obj){

   var $postvar=null;
   var $rel = $($obj).attr('rel');
   var $theArray = $.extend({},$consolidated);
   $.each($theArray,function(){
       if(this.step !== undefined){
           if(this.step == $rel){
                $postvar = this;
                $consolidated.splice($.inArray($postvar,$consolidated),this.step);
           }
       }
   });
   $(".script-wizard-content").append("<div class='processing'><img src='"+$_protocol+'//'+$_host+$_base_path+'/img/goloading.gif'+"'></div>"); 
   $.post(
         $_protocol+'//'+$_host+$_base_path+'/index.php/go_script_ce/scriptaddwizard',
         $postvar,
         function(data){
              var result = JSON.parse(data);
              $('.script-wizard-steps').children().each(function(){
                      if($(this).hasClass('script-wizard-breadcrumb')){
                             $(this).children('img').attr('src',result.display.breadcrumb.script_wizard_breadcrumb);
                      }else if($(this).hasClass('script-wizard-content')){
                             $(this).children().each(function(){
                                   if($(this).hasClass('scripts-labels')){
                                          $(this).children('img').attr('src',result.display.content.wizard_step);
                                   }else{
                                          $(".processing").remove();
                                          $(this).empty().append(result.display.content.wizard_form_elems);
                                   }
                             });
                      }else{
                              $(this).empty().append(result.display.action.actions);
                      }
              });
         }  
   ); 

}


$(function(){

    try {

        if(window.paginate) {

            paginate($(".elem-tbl-container"),$(".pager-perpage > select").val());

        }

    } catch(err) {

       console.log(err);

    }
});

$(function(){

    $obj = $(".elem-tbl-container");

    $(".pager-perpage > select").change(function(){
         paginate($obj,$(this).val());
    });

    // first page
    $("#pager-firstpage").click(function(){
             if($perpage !== "all"){
                 firstpage($obj);
             }
    });

    // back page
    $("#pager-prevpage").click(function(){
             if($perpage !== "all"){
                 $curr_page = $(".pager-paginater > input");
                 $next = parseInt($($curr_page).val()) - parseInt(1);
                 backpage($obj,$next);
             }
    });

    // next page
    $("#pager-nextpage").click(function(){
             if($perpage !== "all"){
                 $curr_page = $(".pager-paginater > input");
                 $next = parseInt($($curr_page).val()) + parseInt(1);
                 nextpage($obj,$next);
             }
    });

    // last page
    $("#pager-lastpage").click(function(){
            if($perpage !== "all"){
               lastpage($obj,$total_page);
            }
    });

});

$(function(){

   $(".scripts-action").delegate("a","click",function(){

       var $id = getId($(this));
       if($(this).attr("id").indexOf("default") > 0){
            // collects info here
            $.post(
                $_protocol+'//'+$_host+$_base_path+'/index.php/go_script_ce/getscriptinfo',
                {scriptid:$id.replace('-default','')},
                function(data){
                    $result = $.makeArray(JSON.parse(data));
                    $.each($.makeArray($(".edit-form").children().children('div')),function(){
                         var $currElem = $(this);
                         var $divfield = $($currElem).attr('id');
                         if($(this).hasClass('scripts-values')){
                              if($(this).children().length > 0){
                                   var $elemChilds = $(this).children();
                                   $.each($elemChilds,function(){
                                        var $elemChildsId = $(this).attr("id");
                                        if($result[0].hasOwnProperty($elemChildsId)){
                                             $(this).val($result[0][$elemChildsId]);
                                             // after setting values add prefix "copy-" for saving purposes
                                             $(this).attr("id",$(this).attr("id"));
                                             $(this).attr("name",$(this).attr("name"));
                                        }else{
                                             if($(this).is('a#preview')){
                                                  $(this).attr('id',"preview-"+$result[0].script_id);
                                             }
                                        }
                                   });
                              }else{
                                   if($(this).attr("id") === $divfield){
                                       $(this).empty().append($result[0][$divfield]);
                                       $(this).attr("id",$(this).attr("id"));
                                   }
                              }
                         }
                    });
                    // reposition closer and box
                    var $obj = [$(".edit-form")];
                    reposition($obj);

                    setTimeout('wizard($(".edit-form"));',1000);

                }
            );

       } else if($(this).attr("id").indexOf("advance") > 0){
         
           $id = getId(this);
           $active_elem = $id.replace("-advance","");
           $("#script-tab").tabs("option","disabled",[]);
           $('#script-tab').tabs("url",1,$_protocol+"//"+$_host+$_base_path+"/index.php/go_script_ce/scriptadvance");
           $('#script-tab').tabs("select",1);

       }

   });

});


$(function(){

    $(".sort").click(function(){

        var $col = $(this).index();
        var $order = null;
        if($(this).has("desc")){
   
             $order = "desc";

        } else {

             $order = "asc";

        }

        sortrows($(".elem-tbl-container"),$col,$order);
        $(this).toggleClass("desc").siblings().toggleClass("desc");

    });

    $(".toolTip").tipTip();

});

$(function(){

    if($('.elem-tbl-container').children().size() === 0){

           if(window.reposition !== undefined){

                var $obj = [$(".script-add-wizard-container")];

                reposition($obj);

           }

           wizard($(".script-add-wizard-container"));

    }

});

$(function(){

    $("#batch-process").click(function(){

         if($(this).prop("checked") === true){
             
             $("input.batch").each(function(){
                  $(this).attr("checked",true);
             });

         } else {

             $("input.batch").each(function(){
                  $(this).attr("checked",false);
             });

         }

    });

    $(".elem-cols-action-lbl").delegate("img","click",function(){
         var $position = $(this).offset();
         $(".batch-actions").slideToggle().offset({top:($position.top + 15),left:($position.left - $(".batch-actions").width())});
    });

    $(".batch-actions").delegate("a","click",function(){
          var $anchor_id  =  $(this).attr("id").replace("-selected","");
          var $action = "";
          var $batch = [];
          switch($anchor_id){
               case "active":
                     $action = "Y";
               break;
               case "deactivate":
                     $action = "N";
               break;
               case "delete":
                     $action = "D";
               break;
          }

          $("input.batch").each(function(){
               if($(this).prop("checked") === true){
                   $batch[$batch.length] = $(this).attr("id").replace("check-","");
               }
          });

          if($batch.length > 0){
              var $datas = {action:$action,ids:$batch};
              $.post(
                     $_protocol+"//"+$_host+$_base_path+"/index.php/go_script_ce/batchprocess",
                     $datas,
                     function(data){
                          if(data.indexOf("Success") !== -1){
                              alert("Success: Batch process complete");
                              location.reload();
                          } else {
                              alert("Error: Something went wrong please contact our support team");
                          }
                     }
              );
          }
    });

});
