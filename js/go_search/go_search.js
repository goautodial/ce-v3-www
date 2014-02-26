/*
############################################################################################
####  Name:             go_search.js                                                      ####
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

/*$(document).ready(function() {

    $(".allowed-access").draggable({
                appendTo: 'div',
                revert: 'invalid',
                zIndex: 9999
    });
    $( "#droppable" ).droppable({
            drop: function( event, ui ) {
                        $( this ).addClass( "ui-state-highlight" )
		  },
            out: function ( event, ui ){
             
                 }
    });
});

/*$(function (){

    $.validator.addMethod("allowChar",
                           function(value, element){
                                  return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
                           },
                          "invalid"
    );

    $.validator.addMethod("passAllowChar",
                           function(value, element){
                                  return this.optional(element) || /^[a-z0-9\_\'\-\@\#\$\%\^\&\*\!\.\,\"\(\)]+$/i.test(value);
                           },
                          "invalid"
    );


    $("#userform").validate({
            debug: true,
            rules: {
                  user: {
                         required : true,
                         minlength: 2,
                         allowChar: true
                        },
                  pass: {
                         required : true,
                         minlength: 2,
                         passAllowChar: true
                        },
                  user_group: {
                         required: true
                        },
                  accountNum: {
                         required: true
                  }
            },
            messages: {
                  user : {
                          required : "* req",
                          minlength: "2 char"
                         },
                  pass : {
                          required : "* req",
                          minlength: "2 char"
                         },
                  user_group: {
                          required : "* req"
                         },
                  accountNum: {
                          required : "* req"
                  }
            },
            submitHandler: function(form) {
                     $.post(
                            "https://"+window.location.host+"/index.php/go_search/autogenuser/"+account,
                            $("#userform").serialize(),
                            function (data){
                                // update add user widget
                                var $results = $(data);
                                $('#returncount').empty();
                                $('#returncount').prepend($results.filter('#totalseats').text());
                                $('#hidcount').val($results.filter('#totalseats').text());
                                // update userlist
                                $('#userlist').append('tangina this');
                                alert($results.filter('#msg').text());
                            }
                     );
            }
    });

});
*/
$(function (){

    $('#accountNum').change(function (){ 
            var account = $("#accountNum").val();
            $.post(
                   "https://"+window.location.host+"/index.php/go_search/getaccountinfo/"+account,
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


$(function (){

    $('#tab-nav').tabs({
        load: function(event, ui) {
            $(ui.panel).delegate('a', 'click', function(event) {
                $(ui.panel).load(this.href);
                event.preventDefault();
            });
        },
        fx: {opacity : 'toggle'}
    });
});

$(document).ready(function (){

    $('div.group-toggle').click(function (){
        var $id = $(this).attr("id");
        var $group = $id.replace('toggle-',"");

        $("#"+$group).slideToggle("fast",function(){
              $(this).parent().toggleClass("closed");
        });
    });

});

$(document).ready(function (){

    $('div.ungroup-toggle').click(function (){
        var $id = $(this).attr("id");
        var $group = $id.replace('toggle','quickedit');
        var $userid = $id.replace('ungroup-toggle-',"");

        $("#"+$group).slideToggle("fast",function(){
              $(this).parent().toggleClass("closed");
              /*$.get(
                     protocol+"//"+host+"/index.php/go_search/collectuserinfo/"+$("#vicidial_user_id-"+$userid).val(),
                     function(data) { console.log(data) },
                     "jsonp" 
                   );*/
              $.getJSON(
                       protocol+"//"+host+"/index.php/go_search/collectuserinfo/"+$("#vicidial_user_id-"+$userid).val(),
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
                      url: protocol+"//"+host+"/index.php/go_search/collectuserinfo/"+$("#vicidial_user_id-"+$userid).val(),
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
            $('.inside').load(protocol+"//"+host+"/index.php/go_search/modifyuser/",{userid:$id});
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
                                    protocol+"//"+host+"/index.php/go_search/updateuser/"+$('#vicidial_user_id-'+$group).val()+"/"+$("#users_id-"+$group).val(),
                                    $("#form-"+$group).serialize(),
                                    function (data){
                                        $('#ungroup-'+$group).find('h3').text('('+$group+') '+$('#'+$group+"-full_name").val()); 
                                        alert(data);
                                    }
                             );
                             return false;
                       }
                   }
             });

             $('input[id^="'+$group+'"]').each(function (){
                   $(this).rules("add",{required: true,minlength: 2,messages: {required:"* req"} });
             });
             $("#form-"+$group).submit();
        }else if($action.toLowerCase() == 'delete'){
            $.post(
                  protocol+"//"+host+"/index.php/go_search/deleteuser/"+$('#vicidial_user_id-'+$group).val(),
                  function (data){
                        $("#active-"+$group+" option[value='N']").attr('selected','selected');
                        alert(data);
                  }
             );
            return false;
        }else if($action.toLowerCase() == 'advance'){
            $('.inside').load(protocol+"//"+host+"/index.php/go_search/advancemodifyuser/",{userid:$group});
            return false;
        }

    });

});


$(function (){

    $('.groupsuser').delegate('a','click',function (){
         var $id = $(this).attr("id");
         var $group = $id.replace("hierarchy-","");
         $('.inside').load(protocol+"//"+host+"/index.php/go_search/advancemodifyuser/",{userid:$group});
    }); 

});

$(function (){
    $('.groups').delegate('h3','click',function(){
         var $id = $(this).parent().attr("id");
         var $group = $id.replace("hierarchy-","");
         var $group = $group.replace("groups-","");
         if($id != 'groups-SUPTLIT' && $id != 'groups-AGENTS' ){
             $('.inside').load(protocol+"//"+host+"/index.php/go_search/advancemodifyuser/",{userid:$group});
         }
    });
});

$(function (){
        $(".adv-enable").click(function(){
                var parent = $(this).parents('.switch');
                $('.bsc-enable',parent).removeClass('selected');
                $(this).addClass('selected');
        });
        $(".bsc-enable").click(function(){
                var parent = $(this).parents('.switch');
                $('.adv-enable',parent).removeClass('selected');
                $(this).addClass('selected');
        });
});

// set scrollbar for our access and groups
$(document).ready(function (){
   $(".adv-user-permissions").tinyscrollbar({sizethumb:'auto'});
   $(".adv-user-groups").tinyscrollbar({sizethumb:'auto'});
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
                                                       protocol+'//'+host+'/index.php/go_search/deleteitem/',
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
              $(".adv-user-groups").tinyscrollbar_update('relative');
          });
     });

});


$(function (){
   $(".adv-settings-actions").delegate('a','click',function(){
       var $elementid = $(this).attr('id');
       var $parentid =  $(this).parent().attr("id");
       var $currentVuserid = $parentid.replace("current-user-","");
       if($elementid == "save-group"){
          // loop on all user-groups children which is our groups
          $("#user-groups").children().each(function($index,$value){            
             // if inactive is not present then you are the active element
             if($(this).hasClass('inactive') === false){ 
                  // meaning we are creating but actually we are updating our newly created group
                  if($(this).hasClass('new-user-groups') === true){ 
                     // check if it is div if so proceed in saving 
                     if($(this).is('div') === true){ 
                         var $formObj = $(this).children().children();
                         if($formObj.is('form') === true){
                             // get the new created id
                             var $formid = $formObj.attr('id');
                             var $newgroupid = $formid.replace("new-group-","");
                             $("#"+$formid).validate({
                                  submitHandler:function (form){
                                      // get all elements from form
                                      var $elementsvalue = [];
                                      $("#"+$formid).children().children().each(function (){
                                          var $name = $(this).attr("name");
                                          if($(this).is('input')){
                                               $elementsvalue[$name]=$(this).val();
                                          }else if($(this).is('select')){
                                               $elementsvalue[$name]= $(this).val();
                                          }
                                      });
                                      // create an array of access id
                                      var $access = [];
                                      $('#new-allowed-access').children().each(function(){
                                          $accessid = $(this).attr('id');
                                          $access[$access.length] = $accessid.replace("access-","");
                                      });
                                      var $finalarray = $.extend({},$elementsvalue,{useraccess_id:$access});
                                      $.post(
                                               protocol+'//'+host+"/index.php/go_search/saveusergroup/"+$currentVuserid+"/"+$newgroupid,
                                               $finalarray,
                                               function (data){
                                                    alert(data);
                                                    // get new id to change its attrib
                                                    var $newid = $('.new-user-groups').attr('id');
                                                    var $newaccess = $('#new-allowed-access').children();
                                                    var $groupaction = $('#'+$newid+' .group-action').clone(true);
 
                                                    // try get the first child and clone it
                                                    var $newgroup = $(".user-groups:nth-child(1)").clone(true);
                                                    $newgroup.attr('id',function(){return $newid;});
                                                    $newgroup.attr('class','user-groups');
                                                    $newgroup.children('h3').empty();
                                                    $newgroup.children('h3').append($('#'+$newid).children('.new-allowed-access').children('form[id^="new-group"]').children('.rightcol').children('input').val());
                                                    $newgroup.children('.adv-allowed-access').empty();
                                                    // change newaccess class
                                                    $.each($.makeArray($newaccess),function(){
                                                        $newgroup.children('.adv-allowed-access').append($(this).attr('class','allowed-access user-cornerall'));
                                                    });
                                                    //$newgroup.children('.adv-allowed-access').append($newaccess);
                                                    $newgroup.children('.adv-allowed-access').append('<br class="clear"/>');
                                                    $newgroup.children('.adv-allowed-access').append($groupaction);
                                                    $newgroup.children('.adv-allowed-access').attr('style',"display:block;");
                                                    $('#user-groups').append($newgroup);

                                                    // detach the new configuration
                                                    $(".new-user-groups").detach();
                                                    // update the scrollbar
                                                    $(".adv-user-groups").tinyscrollbar_update('relative');
                                                    $(".adv-user-permissions").tinyscrollbar_update('relative');
                                                }
                                      );
                                  }
                             });
                             $("#"+$formObj.attr('id')).children().each(function (){
                                   if($(this).children().is('input')){
                                       $(this).children().rules("add",{required: true,minlength: 2,messages: {required:"* req",minlength: "atleast 2 char"} });
                                   }
                             });
                             $("#"+$formObj.attr('id')).submit();
                         }
                     }
                  }else{
                     if($(this).is('div') === true){ // check if it is div if so proceed in saving 
                        // get the groupid
                        $groupid = $(this).attr('id');
                        $groupid = $groupid.substring($groupid.indexOf('groups-')+7,$groupid.indexOf('--')); 
                        // clone the user access data
                        var $cloned = $('#'+$(this).attr('id')+' div.adv-allowed-access').children('div').clone();
                        // create an array of access id
                        var $access = [];
                        $.each($.makeArray($cloned),function(){
                            if($(this).is('div') && $(this).hasClass("group-action") === false){
                               $accessid = $(this).attr('id');
                               $access[$access.length] = $accessid.replace("access-","");
                            }
                         });
                         $.post(
                             protocol+'//'+host+"/index.php/go_search/saveusergroup/"+$currentVuserid+"/"+$groupid,
                             {useraccess_id:$access},
                             function(data){
                                alert(data);
                             }
                         );
                     }
                  }
                  // set to null after saving 
                  $newHgroup = null;
             } // end no active element present nothing to do

          });
       }else if($elementid == "create-group"){

               try{ // detach the previous new group
                  // check if we are in create if so delete the group 
                  $created = $('#user-groups').find('div[class="new-user-groups"]');
                  $('#'+$created.attr('id')).detach();
                  var $newid = $created.attr('id');
                  // remove from database
                  $.post(
                         protocol+'//'+host+'/index.php/go_search/deleteitem/',
                         {groupid:$newid.substring($newid.indexOf('groups-')+7,$newid.indexOf('--'))},
                         function (){
                             //update scrollbar 
                             $(".adv-user-groups").tinyscrollbar_update('bottom');
                         }
                  );

               }catch(err){ // set active group to inactive
                  // meaning you have not created a new group yet
                  // and we have an active element so if we want to create a new group let's close the active one
                  var $groupsObj = $(this).parent().parent().find('#user-groups').children('div');
                  $.each($.makeArray($groupsObj),function(){
                      if($(this).hasClass('inactive') === false){
                           $(this).attr('class','user-groups inactive');
                           $(this).children('div').attr('style','block:none;');
                           $('.adv-user-groups').tinyscrollbar_update('bottom');
                      }
                  });
               }
               $.post(
                   protocol+'//'+host+"/index.php/go_search/createusergroup",
                   {currentuser:$currentVuserid},
                   function (data){
                        $("#user-groups").append(data);
                        $newelementid = $('.new-user-groups').attr('id');
                        // update scrollbar
                        $('.adv-user-groups').tinyscrollbar_update('bottom');
                        // ------- set value for $newgroup 
                        $newHgroup=$newelementid; 
                   }
               );

               // ----------- END initial create ------------
       }else if($elementid == "add-to-group"){
           // check active children element 
           var $theObj = $(this);
           var $groupsElems = $theObj.parent().parent().children('.adv-user-groups').children('.adv-settings-container').children('#user-groups').children(); 
           $.each($.makeArray($groupsElems),function(){
               $groupid = $(this).attr('id');
               if($(this).is('div')){
                   if($(this).hasClass('inactive') === false){
                       var $currentid = $theObj.parent().attr('id');
                       if(confirm("Do you want to Add this user to the active group?")){ 
                            $.post(
                                  protocol+'//'+host+'/index.php/go_search/updategousers/'+$currentid.substring($currentid.indexOf("-user-")+6),
                                  {groupaccess_id:$groupid.substring($groupid.indexOf("groups-")+7,$groupid.indexOf('--'))},
                                  function(data){
                                     alert(data);
                                     // update scrollbar
                                     $('.adv-user-groups').tinyscrollbar_update('relative');
                                  }
                            );
                            return false; // stop the loop in objects to save time
                       }
                   } 
               }
           });
       }else{
           alert('disable this group');
       }
   });
});

$(function(){
    $('#user-groups').delegate('a','click',function(){
         if($(this).text() == "Add access"){ // action are in add
             // determine where to put tha accesses
             var $activeObj = null;
             var $checkHere = null;
             $activeElem = $(this).parent().parent();
             if($activeElem.is('div')){
                 $activeObj = '.adv-allowed-access';
             }else{
                 $activeObj = '.new-access';
             }
             // set arrays for the two different settings
             var $selectednewaccess = [];
             var $allnewaccess = [];
             $("#user-permissions").children().each(function(index,value){
                 if($(this).hasClass('highlight')){
                    if($($activeElem).find("div[id='"+$(this).attr('id')+"']").length === 0){
                        $selectednewaccess[$selectednewaccess.length] = $(this).clone(true);
                    }
                 }
                 if($($activeElem).find("div[id='"+$(this).attr('id')+"']").length === 0){
                     $allnewaccess[$allnewaccess.length] = $(this).clone(true);
                 }
                 
             });

             // if empty selectedaccesses or access with hightlight
             if($selectednewaccess.length === 0){
                 // no highlighted in access so get all access
                 $.each($allnewaccess,function(){ 
                     $($activeObj).prepend($(this));
                });
             }else{ 
                 // get all and append all highlighted access
                 $.each($selectednewaccess,function(){
                     $($activeObj).prepend($(this).toggleClass('highlight'));
                     $('#user-permissions').children('#'+$(this).attr('id')).toggleClass('highlight');
                 });
             }
             // update scrollbar
             $(".adv-user-groups").tinyscrollbar_update('relative');
         }else{// now actions are in remove 

             // get parents to check if form or div
             var $parentobj = $(this).parent().parent();   
             // if form you are in add state 
             if($('#'+$parentobj.attr('id')).is('form')){
                 $('#new-allowed-access').children().each(function(index, value){
                     if($(this).hasClass('highlight')){
                         $(this).detach();
                     }
                 });
             }else{ // you are in edit mode you are in div
                 $parentobj.children().each(function(index,value){
                     if($(this).hasClass('highlight')){
                         $(this).detach();    
                     }
                 });
             }
             // update scrollbar
             $(".adv-user-groups").tinyscrollbar_update('bottom');
         }

    });
});

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


// save the advance data
$(function(){

    $('.adv-userinfo-action').children('a').click(function(){
        if($(this).text() === "Save User"){

              // we must force them to save the new created new group before we proceed
              try{
                  if($newHgroup.length > 0){
                      alert("Kindly save previous process");
                      return false;
                   }
              }catch(err){
              }

              // get the element id which has our vicidial_users.users_id
              var $elementid = $(this).parent().attr('id');
              // clone and get the children of user settings which would be go_users.groupaccess_id value
              var $groupaccessObj = $('div.adv-user-settings').find('#user-groups').children('div').clone();
              // we have to get the go_users.users_id or the vicidial_users.user before setting rules
              var $advanceformuserid = $(this).parent().parent().parent().siblings('h3').text();
              // to get go_users.groupaccess_id value search children in $groupaccess to it lets loop the object again hay buhay
              var $groupaccessid = null;
              var $access = [];
              $.each($.makeArray($groupaccessObj),function(){
                  if($(this).hasClass('inactive') === false){
                        // we found the active element now let save the data
                        $groupaccessid = $(this).attr('id');
                        // we have to collect all our access to update our vicidial_user table
                        //var $ouraccess = $("#"+$groupaccessid).children("div").children().children('div.new-access').children('div').clone();
                        var $ouraccess = $("#"+$groupaccessid).children("div").children("div[id^='access']").clone();
                        $.each($.makeArray($ouraccess),function(){
                            $access[$access.length] = $(this).attr('id').replace("access-","");
                        });
                  }
              }); 
              var $input = $("form[id^='adv-userinfo-']").children().children();
              // get all form elements and put in an array
              var $createObjforData = [];
              $.each($.makeArray($input),function(){
                  if($(this).is('a')  === false){
                      $createObjforData[$(this).attr('name')] = $(this).val();
                  }
              });


              // we have created our raw data wahahahahahaha
              var $finaldata = $.extend({},$createObjforData,{groupaccess_id:$groupaccessid.substring($groupaccessid.indexOf('groups-')+7,$groupaccessid.indexOf('--')), users_id:$elementid.substring($elementid.indexOf('action-')+7),permission:$access});
              // validate our form
              $("form[id^='adv-userinfo-']").validate({
                      submitHandler: function(){
                             $.post(
                                    protocol+"//"+host+"/index.php/go_search/updateuser/"+$elementid.substring($elementid.indexOf('action-')+7)+"/"+$advanceformuserid,
                                    $finaldata,
                                    function (data){
                                        alert(data);
                                        // reset to null again
                                        $newgroup = null;
                                        return false;
                                    }
                             );
                      }
              });

              // now we set rules to validate
              $('input[id^="'+$advanceformuserid+'"]').each(function (){
                    $(this).rules("add",{required: true,minlength: 2,messages: {required:"* req"} });
              });
              $("form[id^='adv-userinfo-']").submit();
        }else{ // you click cancel so ito ang para sayo
            alert('icacancel mo pare?');
        }
    });

});
