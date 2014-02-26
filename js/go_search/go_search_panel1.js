/*
############################################################################################
####  Name:             go_user_panel1_ce.js                                            ####
####  Type:             ci js for users panel1 exclusively                              ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
*/

$(function(){  

    $(".quick-action-set").delegate('a','click',function(){
         var $theForm = $(this).parent().siblings('form');
         $($theForm).validate({
              submitHandler: function(form){
                  if(this.valid()){
                        $.post(
                               protocol+"//"+host+basepath+"/index.php/go_user_ce/updateuser/"+$('#vicidial_user_id-'+$theForm.attr('id').replace('form-',"")).val()+"/"+$("#users_id-"+$theForm.attr('id').replace('form-',"")).val(),
                               $(form).serialize(),
                               function (data){
                                    alert(data);
                                    if(data.indexOf('Error') === -1){
                                         $("#"+$theForm.attr('id').replace('form-',"")).children("div:nth-child(2)").empty();
                                         $("#"+$theForm.attr('id').replace('form-',"")).children("div:nth-child(2)").append($("#"+$theForm.attr('id').replace('form-',"")+"-full_name").val());
                                         $("#"+$theForm.attr('id').replace('form-',"")).children("div:nth-child(3)").empty();
                                         if($("#active-"+$theForm.attr('id').replace('form-',"")).val() == 'Y'){
                                            $active = '<span class="active">Active</span>';
                                         }else{
                                            $active = "<span class='inactive'>Inactive</span>";
                                         }
                                         $("#"+$theForm.attr('id').replace('form-',"")).children("div:nth-child(3)").append($active);
                                    }
                               }
                       );
                  }
              }
         });

         $theForm.children().find("input[id^='"+$theForm.attr('id').replace('form-',"")+"']").each(function(){
             $(this).rules("add",{required:true,minlength:2,messages:{required:"* req",minlength:"Atlease 2 char"}});
         });
         $theForm.submit();
    });

});

// calling wizard part 
$(function(){
    $("#call-user-wizard").click(function(){
        $(".overlay").show().fadeIn('slow');
        $('.wizard-box').animate({opacity:1},3000);
        var $position = $('.wizard-box').offset();
        $('.add-close').animate({opacity:1},3000).offset({top:($position.top - 10),left:(($position.left + $('.wizard-box').width() - 5))});
    
        var $form = $('.wizard-box').children('form');
        var $firstChild = $($form).children('div:nth-child(2)');
        if($firstChild.children().is('select')===true){
             var group = $firstChild.children().val();
             $.each(go_accounts,function(){
                  if(this.account_num === group){
                       $('#comp_name').append(this.company);
                       $('#count').append(this.num_seats);
                       $('#hidcount').val(this.num_seats);
                  }
             });
        }
    });

});

$(function(){
    $('.add-close').click(function(){
        $('.wizard-box').animate({opacity:0});
        $('.add-close').animate({opacity:0});
        $('.overlay').hide().fadeOut('slow');
        resetwizard();
    });

    $('.add-close-modify').click(function(){
        $('.wizard-box-modify').animate({opacity:0});
        $('.add-close-modify').animate({opacity:0});
        $('.overlay-modify').hide().fadeOut('slow'); 
    });

    $('.add-close-info').click(function(){
        $('.wizard-box-info').animate({opacity:0});
        $('.add-close-info').animate({opacity:0});
        $('.overlay-info').hide().fadeOut('slow'); 
    });
});

function resetwizard(){

    var $content = $('.wizard-content');
    if($consolidated.length > 0){
        $submit = $.extend({},$consolidated);
        $.post(
               protocol+'//'+host+basepath+'/index.php/go_user_ce/resetwizard',
               $submit,
               function(data){
                   if(data.indexOf("Error") !== -1){
                       alert(data); return false;
                   }
                   var $result = JSON.parse(data);
                   $('.wizard-box').children('div').each(function(){
                        switch($(this).attr("class")){
                            case 'wizard-breadcrumb':
                                  $(this).find("img").attr("src",$result.display.breadcrumb);
                            break;
                            case 'wizard-content':
                                  $(this).children().each(function(){
                                       if($(this).hasClass('wizard-content-left')){
                                            $(this).find("img").attr("src",$result.display.content.left)
                                       }else{
                                            $(this).empty();
                                            $(this).append($result.display.content.right);
                                       }
                                  });
                            break;
                            case 'wizard-action':
                                  $(this).empty();
                                  $(this).append($result.display.action);
                            break;
                        }
                   });
                   $consolidated = [];
               }
        );
    }
}

function autogen(){
    var $content = $('.wizard-content');
    var $form = $($content).find("form");
    $($form).validate({
       submitHandler : function(form){
           if(this.valid()){
               $.post(
                      protocol+'//'+host+basepath+'/index.php/go_user_ce/autogenuser',
                      $form.serialize(),
                      function(data){
                          console.log(data);
                          if(data.indexOf('Error') !== -1){
                              alert(data);
                          } else {
                              alert(data);
                              location.reload();
                          }
                      }
               );
           }
       }
    });

    $($form).children("div.boxrightside").each(function(){
         $(this).children().each(function(){
              if(($(this).is("span") === false) && ($(this).is("select")===false)){
                   if($(this).attr("id") !== undefined){
                        $(this).rules("add",{required:true,minlength:2,messages:{required:"* req",minlength:"atleast 2 char"}});
                   }
              }
         });
    });

    $($form).submit();
}


function accountinfo(){

    //$('#accountNum').change(function (){ 
            var account = $("#accountNum").val();
            $.post(
                   protocol+"//"+host+basepath+"/index.php/go_user_ce/getaccountinfo/"+account,
                   $("#userform").serialize(),
                   function (data){
                        var infos = JSON.parse(data);
                        $('#comp_name').empty();
                        $('#comp_name').append(infos[0].company);
                        console.log($("#comp_name").siblings());
                        $("#comp_name").siblings().val(infos[0].company);
                        $('#count').empty();
                        $('#count').append(infos[0].num_seats);
                        $('#hidcount').val(infos[0].num_seats);
                   }
            );
    //});

}

// for emergency logout
$(function(){
   // get our emergency element(anchor)
   try{ // just a checker if its alive or not nothing fancy
       $('.emergency').click(function(){ 
           // magic begins here first things first the user
           var $user = $activeElement;
           $.post(
                  protocol+'//'+host+basepath+'/index.php/go_user_ce/emergencylogout',
                  {user:$user},
                  function(data){
                      alert(data);
                      if(data.indexOf('complete') !== -1){
                          location.reload();
                      }
                  }
           ); 
       });
   }catch(err){
      alert(err);
   }
});


$(document).ready(function(){
   try{
      datesearch();
   }catch(err){}
});

function datesearch(){

   try{
       var $daterange = $('#widgetField').children("span");
       var $dates = $daterange.text().split(' to ');
       var $user = $daterange.attr('id').replace('user-stat-',"");

       // agent talk time
       $.post(
               protocol+'//'+host+basepath+'/index.php/go_user_ce/agentTalkTimeStatus',
               {dates:$dates,user:$user},
               function(data){
                   $('.time-stat-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+basepath+'/img/loading.gif" /><br/></div>');
                   $('.time-stat-content').delay(200).empty().append(data); 
               }
       );

/*
       // leadsearch
       $.post(
               protocol+'//'+host+basepath+'/index.php/go_user_ce/leadsearchThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.leadsearch-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+basepath+'/img/loading.gif" /><br/></div>');
                   $('.leadsearch-thistime-content').delay(2000).empty();
                   $.each($.makeArray($(data)),function(){
                       if($(this).is("div")){
                           $.each($.makeArray($(this).children("div.cols")),function(){
                                if($(this).hasClass('elipsis')){
                                    var $elipsisValue = $(this).text();
                                    $(this).empty();
                                    $(this).text($elipsisValue.substring(0,40)+"...");
                                    $('.leadsearch-thistime-content').append("<div class='realValue bubblemsg'><span class='user-cornerall spanbubble'>"+$elipsisValue+"</span></div>");
                                }
                           });
                           $('.leadsearch-thistime-content').append($(this)); 
                           $('.leadsearch-thistime-content').append("<br class='spacer'/>"); 
                       }
                   });
               }
       );

       // agent activity
       $.post(
               protocol+'//'+host+basepath+'/index.php/go_user_ce/agentActivity',
               {dates:$dates,user:$user},
               function(data){
                   $('.agent-activity-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+basepath+'/img/loading.gif" /><br/></div>');
                   $('.agent-activity-content').delay(2000).empty().append(data); 
               }
       );

       // agent login logout 
       $.post(
               protocol+'//'+host+basepath+'/index.php/go_user_ce/agentLoginLogoutTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.time-loginlogout-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+basepath+'/img/loading.gif" /><br/></div>');
                   $('.time-loginlogout-content').delay(2000).empty().append(data); 
               }
       );


       // outbound
       $.post(
               protocol+'//'+host+basepath+'/index.php/go_user_ce/outboundThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.outbound-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+basepath+'/img/loading.gif" /><br/></div>');
                   $('.outbound-thistime-content').delay(2000).empty().append(data); 
               }
       );

       // inbound
       $.post(
               protocol+'//'+host+basepath+'/index.php/go_user_ce/inboundThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.inbound-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+basepath+'/img/loading.gif" /><br/></div>');
                   $('.inbound-thistime-content').delay(2000).empty().append(data); 
               }
       );

       // recording
       $.post(
               protocol+'//'+host+basepath+'/index.php/go_user_ce/recordingThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.recording-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+basepath+'/img/loading.gif" /><br/></div>');
                   $('.recording-thistime-content').delay(2000).empty();
                   // recreating result making it object
                   $.each($.makeArray($(data)),function(){
                       if($(this).is('div')){
                           $.each($.makeArray($(this).children("div.cols")),function(){
                                if($(this).hasClass('elipsis')){
                                    var $elipsisChild = $(this).children();
                                    if($elipsisChild.is("a") === false){ 
                                        var $elipsisValue = $(this).text();
                                        $(this).empty(); // cleaning 
                                        $(this).text($elipsisValue.substring(0,20)+"...");// cut and paste the text
                                        $('.recording-thistime-content').append("<div class='realValue bubblemsg'><span class='user-cornerall spanbubble'>"+$elipsisValue+"</span></div>"); // adding real value of elipsis
                                    }else{
                                        var $elipsisValue = $(this).children("a").text();
                                        var $anchor = $(this).children("a");
                                        $(this).empty();
                                        $(this).append("<a href='"+$anchor.attr('href')+"'>"+$elipsisValue.substring(0,20)+"...</a>");
                                    }
                                }
                           });
                           $('.recording-thistime-content').append($(this)); 
                           $('.recording-thistime-content').append("<br class='spacer'/>"); 
                       }
                   });
               }
       );


       // manual Outbound
       $.post(
               protocol+'//'+host+basepath+'/index.php/go_user_ce/manualoutboundThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.manualoutbound-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+basepath+'/img/loading.gif" /><br/></div>');
                   $('.manualoutbound-thistime-content').delay(2000).empty().append(data); 
               }
       );
   */
      
   }catch(err){
   }
   
}

var $consolidated = [];
function next(){

   var $wizardbox = $(".wizard-box");
   // get the wizard-content elements to submit
   var $contentForm = $($wizardbox).find("form");

   // form manipulation
   $($contentForm).validate({
       submitHandler : function(form){
            if(this.valid()){
                 $.post(
                         protocol+'//'+host+basepath+'/index.php/go_user_ce/userwizard',
                         $(form).serialize(),
                         function(data){
                             if((data.indexOf('Error') !== -1) || (data.indexOf('Success') !== -1)){
                                 alert(data);
                                 return false;
                             }
                             try{
                                 var $result = JSON.parse(data);
                                 var $layout = $result.display;
                                 $($wizardbox).children("div").each(function(){
                                       switch($(this).attr("class")){
                                            case 'wizard-breadcrumb':
                                                    $(this).find("img").attr("src",$layout.breadcrumb);
                                            break;
                                            case 'wizard-content':
                                                    if($(this).children().length > 0){
                                                        $(this).children().each(function(){
                                                              if($(this).hasClass("wizard-content-left")){
                                                                  $(this).find("img").attr("src",$layout.content.left);
                                                              }else{
                                                                  $(this).empty();
                                                                  $(this).append($layout.content.right);
                                                              }
                                                        }); 
                                                    }
                                            break;
                                            case 'wizard-action':
                                                    $(this).empty();
                                                    $(this).append($layout.action);
                                            break;
                                       }
                                 });
                                 $consolidated[$consolidated.length] = $result.prev_step;
                             }catch(err){console.log(err);}
                         }
                 );
            }
       }
   });

   $($contentForm).submit();
}
