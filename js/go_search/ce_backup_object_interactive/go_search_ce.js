/*
############################################################################################
####  Name:             go_search_ce.js                                                   ####
####  Type:             ci js for users                                                 ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
*/

var protocol = window.location.protocol;
var host = window.location.host;
// to check if create group is in action
var $newHgroup=null;
var $activeElement = null;
$(function (){

    $('#accountNum').change(function (){ 
            var account = $("#accountNum").val();
            $.post(
                   protocol+"//"+host+"/index.php/go_search_ce/getaccountinfo/"+account,
                   $("#userform").serialize(),
                   function (data){
                        var infos = $(data);
                        // update add new user widget
                        $('#returncompany').empty();
                        $('#returncount').empty();
                        $('#returncompany').prepend(infos.filter("#company").text());
                        $('#returncount').prepend(infos.filter("#num_seats").text());
                        $("#hidcompany").val(infos.filter("#company").text());
                        $("#hidcount").val(infos.filter("#num_seats").text());
                   }
            );
    });

});

function passtophp (){
  return $activeElement;
}
$(function (){

    $('#tab-nav').tabs({

        ajaxOptions:{
              data:{userid:passtophp},
              type: "POST",
              cache: false
        },
        show: function (){ // disable the advance tab?
            try{
                //$(this).tabs({disabled:[1]});
                $(this).tabs("option","disabled",[1]);
                $activeElement = null; 
            }catch(err){}
        },
        fx: {opacity : 'toggle'},
        disabled : [1],
        cache : false
    });


});

$(function (){

    $('div.group-toggle').click(function (){
        var $id = $(this).attr("id");
        var $group = $id.replace('toggle-',"");

        $("#"+$group).slideToggle("fast",function(){
              $(this).parent().toggleClass("closed");
        });
    });

});

$(function (){

    $('div.ungroup-toggle').click(function (){
        var $id = $(this).attr("id");
        var $group = $id.replace('toggle','quickedit');
        var $userid = $id.replace('ungroup-toggle-',"");

        $("#"+$group).slideToggle("fast",function(){
              $(this).parent().toggleClass(function(){
                   if($(this).hasClass('closed') == false){
                       //$("#tab-nav").tabs({disabled:[1]});
                       $("#tab-nav").tabs("option","disabled",[1]);
                       return "closed";
                   }else{
                       //put to global variable
                       $activeElement = $(this).attr("id");
                       // close other div
                       $(this).siblings().each(function(){
                           if($(this).hasClass('closed') === false){
                                $(this).toggleClass('closed');
                                $(this).children('div[id^="ungroup-quickedit-"]').hide();
                           }
                       });
                 

                       //$("#tab-nav").tabs({disabled:[]});
                       $("#tab-nav").tabs("option","disabled",[]);
                       return "closed";
                   }
              });
              /*$.get(
                     protocol+"//"+host+"/index.php/go_search_ce/collectuserinfo/"+$("#vicidial_user_id-"+$userid).val(),
                     function(data) { console.log(data) },
                     "jsonp" 
                   );*/
              $.getJSON(
                       protocol+"//"+host+"/index.php/go_search_ce/collectuserinfo/"+$("#vicidial_user_id-"+$userid).val(),
                       function (data){
                            //console.log(data);
                           $("#"+$userid+"-pass").val(data[0].pass);
                           $("#"+$userid+"-full_name").val(data[0].full_name);
                           $("#phone_login-"+$userid).val(data[0].phone_login);
                           $("#phone_pass-"+$userid).val(data[0].phone_pass);
                           $("#active-"+$userid+" option[value='"+data[0].active+"']").attr('selected','selected');
                       }
              );

              /*$.ajax({
                      type: "GET",
                      url: protocol+"//"+host+"/index.php/go_search_ce/collectuserinfo/"+$("#vicidial_user_id-"+$userid).val(),
                      async: false,
                      datatype : 'jsonp',
                      success: 
              });*/
           //--------------------

        });
    });

});



$(document).ready(function (){

    $('div[class|="draggable"]').each(function (index){
        var $id = $(this).attr("id");
        $("#"+$id).click(function(){
            $('.inside').load(protocol+"//"+host+"/index.php/go_search_ce/modifyuser/",{userid:$id});
        });
    });

});


$(document).ready(function (){
    $('div[class|="groupsuser"]').each(function (index){
         var $id = $(this).attr("id");
         $("#"+$id).jstree({
                             "themes" : {"theme": "default", "icons": false},
                             "destroy" : {"set_focus": false},
                             "plugins":["themes","html_data"]
                           });
    });
});


$(function (){

    $(".quick-action-set").delegate('a','click',function(event){
   
        event.preventDefault();
        var $id = $(this).attr("id");
        var $action = $("#"+$id).text();
        var $group = $id.replace("actions-"+$action.toLowerCase()+"-","");
 
        if($action.toLowerCase() == 'update'){
            $("#form-"+$group).validate({
                   submitHandler: function(form){
                       if($(this).valid){
                             $.post(
                                    protocol+"//"+host+"/index.php/go_search_ce/updateuser/"+$('#vicidial_user_id-'+$group).val()+"/"+$("#users_id-"+$group).val(),
                                    $("#form-"+$group).serialize(),
                                    function (data){
                                        $('#ungroup-'+$group).find('h3').text('('+$group+') '+$('#'+$group+"-full_name").val()); 
                                        alert(data);
                                    }
                             );
                       }
                   }
             });

             $("#form-"+$group).children().find('input[id^="'+$group+'"]').each(function (){
                   $(this).rules("add",{required: true,minlength: 2,messages: {required:"* req",minlength:"atleast 2 char"} });
             });
             $("#form-"+$group).submit();
        }else if($action.toLowerCase() == 'delete'){
            if(confirm("Do you really want to disable this agent?")){
                 $.post(
                        protocol+"//"+host+"/index.php/go_search_ce/deleteuser/"+$('#vicidial_user_id-'+$group).val()+"/"+$("#users_id-"+$group).val(),
                        $("#form-"+$group).serialize(),
                        function (data){
                              $("#active-"+$group+" option[value='N']").attr('selected','selected');
                              alert(data);
                        }
                 );
            return false;
            }
        }else if($action.toLowerCase() == 'status'){
            try{
                 $('#tab-nav').tabs("url",1,"userstatus"); 
                 $('#tab-nav').tabs("select",1);
             }catch(err){
                console.log(err);
             }
        }

    });

});


$(function (){

    $('.groupsuser').delegate('a','click',function (){
         var $id = $(this).attr("id");
         var $group = $id.replace("hierarchy-","");
         $('.inside').load(protocol+"//"+host+"/index.php/go_search_ce/advancemodifyuser/",{userid:$group});
    }); 

});

$(function (){
    $('.groups').delegate('h3','click',function(){
         var $id = $(this).parent().attr("id");
         var $group = $id.replace("hierarchy-","");
         var $group = $group.replace("groups-","");
         if($id != 'groups-SUPTLIT' && $id != 'groups-AGENTS' ){
             $('.inside').load(protocol+"//"+host+"/index.php/go_search_ce/advancemodifyuser/",{userid:$group});
         }
    });
});




// function to display all useraccess
$(function() {

     $('.user-groups').delegate('h3','click',function (){
          var $id = $(this).parent().attr("id");  
          var $ids = $id.replace("user-groups-","");
          var $groupid = $ids.substr(0,$ids.indexOf("-"));
          var $group_ownerid = $ids.substr($ids.indexOf("--")+1);
         
          //display the useraccess
          $(this).siblings(".adv-allowed-access").slideToggle("fast",function(){
              $(this).parent().toggleClass(
                                     function(){
                                        if($(this).hasClass('inactive') !== false){
                                            try{
                                                // check if we are in create if so delete the group 
                                                $created = $('#user-groups').find('div[class="new-user-groups"]');
                                                $('#'+$created.attr('id')).detach();
                                                var $newid = $created.attr('id');
                                                // remove from database
                                                $.post(
                                                       protocol+'//'+host+'/index.php/go_search_ce/deleteitem/',
                                                       {groupid:$newid.substring($newid.indexOf('groups-')+7,$newid.indexOf('--'))}
                                                );
                                            }catch(err){}
                                            $(this).siblings().each(function(){
                                                if($(this).hasClass('inactive') === false){
                                                    // this is the siblings object put inactive so we can have only one active elements
                                                   $(this).toggleClass('inactive'); 
                                                   $(this).children('div').attr('style','block:none;');

                                                }
                                            });

                                            return "inactive";
                                        }else{
                                            return 'inactive';
                                        }
                                     }
                               );
              //$(".adv-user-groups").tinyscrollbar_update('relative');
          });
     });

});


$(function (){
   $(".adv-settings-actions").delegate('a','click',function(){
       console.log($(this)); 
       alert('unfinished business');
   });
});

// for admin select or deselect
$(function(){
    $("div[id^='access']").click(function(){ 
         //$(this).toggleClass("highlight");
         $(this).toggleClass(function(){
             if($(this).hasClass('highlight') === true){
                  $(this).children('p').attr('style','display:none;');
                  return 'highlight';
             }else{
                  $(this).children('p').attr('style','display:block;');
                  return 'highlight';
             }
         });
    });
});


// for emergency logout
$(function(){
   // get our emergency element(anchor)
   try{ // just a checker if its alive or not nothing fancy
       $('.emergency').click(function(){ 
           // magic begins here first things first the user
           var $user = $(this).parent().attr('id');
           $.post(
                  protocol+'//'+host+'/index.php/go_search_ce/emergencylogout',
                  {user:$user},
                  function(data){
                      alert(data);
                      location.reload();
                  }
           ); 
       });
   }catch(err){
      alert(err);
   }
});


// calling wizard part 
$(function(){
    $("#call-user-wizard").click(function(){
        $(".overlay").show().fadeIn('slow');
        $('.wizard-box').animate({opacity:1},3000);
        $('.add-close').animate({opacity:1},3000);
    });
});

$(function(){
    $('.add-close').click(function(){
        $('.overlay').hide().fadeOut('slow'); 
        $('.wizard-box').animate({opacity:0});
        $('.add-close').animate({opacity:0});

    });
});

$(document).ready(function(){
   try{
      datesearch();
   }catch(err){}
});

/*
 * bubble message
 */
$(function(){
    try{
         $("div[class$='content']").delegate("div.cols","mouseover",function(){
              var $edge = $(this).width();
              if($(this).hasClass('bubble')){
                  var $position = $(this).offset();
                  var $elemRowIndex = $(this).parent().index(); // row
                  var $currentObj = $(this).parent().parent();
                  var $callThis = ":nth-child("+$elemRowIndex+")";
                  var $monitorWidth = $(window).width();
                  var $displayThis = $("."+$currentObj.attr("class")).children("div"+$callThis);

                  $($displayThis).show();
                  //initial position
                  $($displayThis).offset({top:$position.top,left:$position.left+($edge-30)});

                  //for repositioning y adjustments
                  var $elemWidth = $($displayThis).width(); 
                  $position = $($displayThis).offset();
                  var $lampasChecker = $position.left + $elemWidth;
                  if($lampasChecker > $monitorWidth){
                      $($displayThis).offset({top:$position.top,left:$position.left-((Math.round($lampasChecker-$monitorWidth))+7)});
                  }

                  //for position x adjustments
                  var $elemHeight = $($displayThis).outerHeight();
                  $position = $($displayThis).offset();
                  //repositioning
                  $($displayThis).offset({top:($position.top-$elemHeight),left:$position.left});
              }
         }).mouseout(function(){
              $(this).children('div.realValue').hide();
         });
    }catch(err){
    }
});


function datesearch(){

   try{
       var $daterange = $('#widgetField').children("span");
       var $dates = $daterange.text().split(' to ');
       var $user = $daterange.attr('id').replace('user-stat-',"");

       // leadsearch
       $.post(
               protocol+'//'+host+'/index.php/go_search_ce/leadsearchThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.leadsearch-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
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

       // agent talk time
       $.post(
               protocol+'//'+host+'/index.php/go_search_ce/agentTalkTimeStatus',
               {dates:$dates,user:$user},
               function(data){
                   $('.time-stat-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.time-stat-content').delay(200).empty().append(data); 
               }
       );

       

       // agent activity
       $.post(
               protocol+'//'+host+'/index.php/go_search_ce/agentActivity',
               {dates:$dates,user:$user},
               function(data){
                   $('.agent-activity-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.agent-activity-content').delay(2000).empty().append(data); 
               }
       );

       // agent login logout 
       $.post(
               protocol+'//'+host+'/index.php/go_search_ce/agentLoginLogoutTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.time-loginlogout-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.time-loginlogout-content').delay(2000).empty().append(data); 
               }
       );


       // outbound
       $.post(
               protocol+'//'+host+'/index.php/go_search_ce/outboundThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.outbound-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.outbound-thistime-content').delay(2000).empty().append(data); 
               }
       );

       // inbound
       $.post(
               protocol+'//'+host+'/index.php/go_search_ce/inboundThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.inbound-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.inbound-thistime-content').delay(2000).empty().append(data); 
               }
       );

       // recording
       $.post(
               protocol+'//'+host+'/index.php/go_search_ce/recordingThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.recording-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
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
               protocol+'//'+host+'/index.php/go_search_ce/manualoutboundThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.manualoutbound-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.manualoutbound-thistime-content').delay(2000).empty().append(data); 
               }
       );

      
   }catch(err){
   }
   
}

$(function(){

   $("#user-search").autocomplete({
          source:function(request,response){
               $.post(
                      protocol+'//'+host+'/index.php/go_search_ce/usersearch',
                      request,
                      function (data){
                         $('#user-container').children('div').fadeOut('slow');
                         var $displays = $.makeArray(JSON.parse(data));
                         response($displays);
                         $("#user-container").children('div').each(function(){
                              var $showThis = $(this);
                              $.each($.makeArray($displays),function(){
                                  if(this.value === $showThis.attr('id') ){
                                       $showThis.fadeIn('slow');
                                  }else{
                                     var $hasThis = this.label.indexOf($("#user-search").val());
                                     if($hasThis > 0){
                                         $showThis.fadeIn('slow');
                                     }
                                  }
                              });
                         });
                      }
               );
          }
   });

});


$(function(){

   $("#searchcall").click(function(){
               $.post(
                      protocol+'//'+host+'/index.php/go_search_ce/usersearch',
                      {term:$('#user-search').val()},
                      function (data){
                         $('#user-container').children('div').fadeOut('slow');
                         var $displays = $.makeArray(JSON.parse(data));
                         $("#user-container").children('div').each(function(){
                              var $showThis = $(this);
                              $.each($.makeArray($displays),function(){
                                  if($('#user-search').val() === $showThis.attr('id') ){
                                       $showThis.fadeIn('slow');
                                  }else{
                                      if($('#user-search').val() === ""){ // if empty display all element
                                          $showThis.fadeIn('slow');
                                      }
                                  }
                              });
                         });
                      }
               );
   });

});
