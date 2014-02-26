/*
########################################################################################################
####  Name:             	go_user_panel1_ce.js                                                ####
####  Type:             	ci js for users panel1 exclusively - administrator                  ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Franco Hora					            	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
*/

$(function(){  

    $(".quick-action-set").delegate('a','click',function(){
         var $theForm = $(this).parent().siblings('form');
         $($theForm).validate({
              submitHandler: function(form){
                  if(this.valid()){
                        $(".wizard-box-modify").append("<div class='processing'><img src='"+protocol+'//'+host+'/img/goloading.gif'+"'></div>"); 

                        var $raw = [];
                        $(".boxrightside-modify").children().each(function(){
                             if($(this).is('input[type="checkbox"]') === true){
                                  $raw[$(this).attr("id")] = ($(this).prop("checked") === true ? 1 : 0 );
                             }else{ 
                                  $raw[$(this).attr("id")] = $(this).val();
                             }
                        });
                      
                        $raw = $.extend({},$raw); 
                        $.post(
                               protocol+"//"+host+"/index.php/go_user_ce/updateuser/"+$('#vicidial_user_id-'+$theForm.attr('id').replace('form-',"")).val()+"/"+$("#users_id-"+$theForm.attr('id').replace('form-',"")).val(),
                               $raw,
                               function (data){
                                    $(".processing").remove();
                                    if(data.indexOf('Error') === -1){
                                         /*
                                         $("#"+$theForm.attr('id').replace('form-',"")).children("td:nth-child(2)").empty().text($("#"+$theForm.attr('id').replace('form-',"")+"-full_name").val());
                                         if($("#active-"+$theForm.attr('id').replace('form-',"")).val() == 'Y'){
                                            $active = '<span class="active">Active</span>';
                                         }else{
                                            $active = "<span class='inactive'>Inactive</span>";
                                         }
                                         $("#"+$theForm.attr('id').replace('form-',"")).children("td:nth-child(5)").empty().append($active);
                                         $("#"+$theForm.attr('id').replace('form-',"")).children("td:nth-child(4)").empty().append($("#user_group-"+$theForm.attr('id').replace('form-',"")).val());
                                         */
                                         alert(data);
                                         //location.reload();
                                         window.location.href = protocol+"//"+host+"/users";
                                    }
                                    
                               }
                       );
                  }
              }
         });

         $.validator.addMethod('letterOnly',
                          function(value,elem){
                              return value.match(new RegExp("^[a-zA-Z0-9]+$"));
                              //return this.optional(elem) || /[a-zA-Z0-9]/.test(value);
                          },
                          "No character(s)");

         $theForm.children().find("input[id^='"+$theForm.attr('id').replace('form-',"")+"']").each(function(){
              if($(this).attr("id").indexOf("pass") === -1){
                 $(this).rules("add",{required:true,minlength:2,messages:{required:"* req",minlength:"Atlease 2 char"}});
              }else{
                 $(this).rules("add",{required:true,minlength:2,messages:{required:"* req",minlength:"Atlease 2 char"},letterOnly:true});
              }
         });
         $theForm.submit();
    });

    $("#advance-toggle").click(function(){

        $.post(
               protocol+"//"+host+"/index.php/go_user_ce/collectuserinfo/"+$(this).attr("rel"),
               function(data){

                     var $checkThisout = $("div.boxleftside-modify")[9];

                     if($($checkThisout).css("display") === "block"){
                          $("div.boxleftside-modify").siblings("div.boxleftside-modify").each(function(indx,vals){
                               if(indx > 8){
                                   $(vals).hide();
                                   var $partner = $("div.boxrightside-modify")[indx];
                                   $($partner).hide();
                               }
                          });
                          $("#advance-toggle").empty().text("Advance [ + ]");
                     }else{
                          $("div.boxleftside-modify").siblings("div.boxleftside-modify").each(function(indx,vals){
                               if(indx > 8){
                                   $(vals).show();
                                   var $partner = $("div.boxrightside-modify")[indx];
                                   $($partner).show();
                               }
                          });
                          $("#advance-toggle").empty().text("Basic [ - ]");
                          if(parseInt(data[0].user_level) < 7){
                              $(".Admin").hide();
                          }
                     }

               }
        );


   });

   $("div.boxleftside-modify").siblings("div.boxleftside-modify").each(function(indx,vals){
          if(indx > 8){
             $(vals).hide();
             var $partner = $("div.boxrightside-modify")[indx];
             $($partner).hide();
          }
   });

});

// calling wizard part 
$(function(){
    $("#call-user-wizard").click(function(){
        $.post(
               protocol+"//"+host+"/index.php/go_user_ce/CheckActionIfAllowed/create",
               function(data){

                   if(data.indexOf("Error") !== -1){
                        alert(data); return false;
                   }

                   $(".overlay").show().fadeIn('slow');
                   $('.overlay-type').offset({top:-3000}).css("margin-left","auto").css("margin-right","auto").animate({"top":"70px"},900).delay(2000);
    
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

               }
        );

    });

});

function showtype(){
   $(".overlay-type").animate({top:-3000});
   if($("#wizard_type").val() === "add"){
       $('.wizard-box').offset({top: -3000 }).css("margin-left","auto").css("margin-right","auto").animate({"top":"70px"},900).delay(2000);
   }else{
       $(".wizard-copy").css('margin','auto').animate({top:70});
   }
}

function cancelWizard($obj){
         $($obj).parent().parent().animate({top:-3000});
         $(".overlay-type").animate({top:70});
}

$(function(){

    $('.add-close').click(function(){
        $('.wizard-box').animate({"top":"-3000"});
        $('.add-close').animate({"top":"-3000"});
        setTimeout("$('.overlay').fadeOut(1000)",1000);
        setTimeout("resetwizard();",1000);
    });

    $('.type-close').click(function(){
        $('.overlay-type').animate({"top":"-3000"});
        setTimeout("$('.overlay').fadeOut(1000)",1000);
    });

    $('.add-close-modify').click(function(){
        $('.wizard-box-modify').animate({top:"-3000"});
        $('.add-close-modify').animate({top:"-300"});
        $("div.boxleftside-modify").siblings("div.boxleftside-modify").each(function(indx,vals){
                if(indx > 8){
                    $(vals).hide();
                    var $partner = $("div.boxrightside-modify")[indx];
                    $($partner).hide();
                }
        });
        $("#advance-toggle").empty().append("Advance [ + ]");
        setTimeout("$('.overlay-modify').fadeOut(1000);",1000);
    });

    $('.add-close-info').click(function(){
        $(".time-stat-content").empty(); 
        $(".time-loginlogout-content").empty(); 
        $(".outbound-thistime-content").empty(); 
        $(".inbound-thistime-content").empty(); 
        $(".agent-activity-content").empty(); 
        $(".recording-thistime-content").empty(); 
        $(".manualoutbound-thistime-content").empty(); 
        $(".leadsearch-thistime-content").empty(); 
        $('.wizard-box-info').animate({top:"-3000"});
        $('.add-close-info').animate({top:"-3000"});
        setTimeout("$('.overlay-info').fadeOut(1000);",1000);
    });

    $('.copy-close').click(function(){
          $('.overlay').fadeOut('fast');
          $(".wizard-copy").animate({top:-3000});
    });
});

function resetwizard(){

    var $content = $('.wizard-content');
    if($consolidated.length > 0){
        $submit = $.extend({},$consolidated);
        $(".wizard-content").append("<div class='processing'><img src='"+protocol+'//'+host+'/img/goloading.gif'+"'></div>"); 
        $.post(
               protocol+'//'+host+'/index.php/go_user_ce/resetwizard',
               $submit,
               function(data){
                   if(data.indexOf("Error") !== -1){
                       $(".processing").remove();
                       alert(data); return false;
                   }
                   var $result = JSON.parse(data);
                   $('.wizard-box').children('div').each(function(){
                        switch($(this).attr("class")){
                            case 'wizard-breadcrumb':
                                  $(this).find("img").attr("src",$result.display.breadcrumb);
                            break;
                            case 'wizard-content':
                                  $(".processing").remove();
                                  $(this).children("div").each(function(){
                                       if($(this).hasClass('wizard-content-left')){
                                            $(this).find("img").attr("src",$result.display.content.left)
                                       }else{
                                            $(this).empty().append($result.display.content.right);
                                       }
                                  });
                            break;
                            case 'wizard-action':
                                  $(this).empty().append($result.display.action);
                            break;
                        }
                   });
                   if($('.wizard-box').css("position")==="absolute"){
                       $(".wizard-box").css("position","fixed");
                   }
                   $consolidated = [];
               }
        );
    }
}

function generatePhone() {
     $(".start_phone_exten").val('');
     if ($('select[name="generate_phone"] option:selected').val() > 0) {
         $(".generate_phone_class").show();
     } else {
         $(".generate_phone_class").hide();
     }
}

function digitsOnly(event) {
     //console.log(event);
     if( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
       // Allow: Ctrl+A
      (event.keyCode == 65 && event.ctrlKey === true) || 
       // Allow: Ctrl+X
      (event.keyCode == 88 && event.ctrlKey === true) || 
       // Allow: Ctrl+C
      (event.keyCode == 67 && event.ctrlKey === true) || 
       // Allow: Ctrl+V
      (event.keyCode == 86 && event.ctrlKey === true) || 
       // Allow: Ctrl+Z
      (event.keyCode == 90 && event.ctrlKey === true) || 
       // Allow: Ctrl+Y
      (event.keyCode == 89 && event.ctrlKey === true) || 
       // Allow: home, end, left, right
      (event.keyCode >= 35 && event.keyCode <= 39)) {
           // let it happen, don't do anything
           return true;
     }else{
          if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
              event.preventDefault(); 
          }
     }
}

function checkPhoneIfExist() {
     var phoneExten = $('.start_phone_exten').val();
     var seat = $('.txtSeats option:selected').val();
     $.post(
          protocol+'//'+host+'/index.php/go_user_ce/checkphone',
          {phone: phoneExten, seat: seat},
          function(data){
               $('.eloading').html(data);
          });
}

function autogen(){
    var $content = $('.wizard-content');
    var $form = $($content).find("form#wizard-config");
    $($form).validate({
       submitHandler : function(form){
           if(this.valid()){
               $(".wizard-content").append("<div class='processing'><img src='"+protocol+'//'+host+'/img/goloading.gif'+"'></div>"); 
               $.post(
                      protocol+'//'+host+'/index.php/go_user_ce/autogenuser',
                      $form.serialize(),
                      function(data){
                       
                          $(".processing").remove();
                          if(data.indexOf('Error') !== -1){
                              alert(data);
                          } else {
                              alert(data);
                              //location.reload();
                              window.location.href = protocol+"//"+host+"/users";
                          }
                      }
               );
           }
       }
    });

    $.validator.addMethod('duplicate',
                         function(value,elem){

                                return responder(value);
                         },
                         "Not Available");
    $.validator.addMethod('noSpace',
                          function(value,elem){
                               return value.indexOf(" ") < 0 && value != "";
                          },
                          "No space(s)"
                          );
    $.validator.addMethod('letterOnly',
                          function(value,elem){
                              return value.match(new RegExp("^[a-zA-Z0-9]+$"));
                              //return this.optional(elem) || /[a-zA-Z0-9]/.test(value);
                          },
                          "No character(s)");
    $.validator.addMethod('reserveWord',
                          function(value,elem){
                             var $test = ""; 
                             if($(elem).attr("id").indexOf("full_name")){
                                 $test = value.match(new RegExp("Survey"));
                             }
                             return !Boolean($test);
                          },
                          "Not Available");

    $($form).children("div.boxrightside").each(function(){
         if($(this).css("display") === "block"){
            $(this).children().each(function(){
              if(($(this).is("span") === false) && ($(this).is("select")===false)){
                   if($(this).attr("id") !== undefined){
                        if($(this).attr("id").indexOf("user") !== -1){
                            $(this).rules("add",{required:true,minlength:2,messages:{required:"* Required",minlength:"atleast 2 char"},duplicate:true,noSpace: true});
                        } else if($(this).attr("id").indexOf("pass") !== -1){
                            $(this).rules("add",{required:true,minlength:2,messages:{required:"* Required",minlength:"atleast 2 char"},letterOnly:true});
                        }else {
                            $(this).rules("add",{required:true,minlength:2,messages:{required:"* Required",minlength:"atleast 2 char"},reserveWord:true});
                        }
                   }
              }
            });
         }
    });

    $($form).submit();
}

function responder(value,elem){
      var $respond = '';
      $.ajax({
            type:"POST",
            url:protocol+'//'+host+'/index.php/go_user_ce/duplicate',
            cache:false,
            async:false,
            data: {user:value},
            success: function(result){
                $respond = result;
            }
      });

      return Boolean($respond);
}

function accountinfo(){

    //$('#accountNum').change(function (){ 
            var account = $("#accountNum").val();
            $.post(
                   protocol+"//"+host+"/index.php/go_user_ce/getaccountinfo/"+account,
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
                  protocol+'//'+host+'/index.php/go_user_ce/emergencylogout',
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

   $("#selectDateOK").click(function(){

        datesearch();

   });

   // user stat information
   $("#agentloginlogout").click(function(){
       $(".agent-loginlogout-time").animate({top:60,left:"auto"});
       pagination($(".time-loginlogout-content"),$(".agent-loginlogout-pager").find(".pager-perpage > select").val(),"agent-loginlogout-pager");
   });

   $("#loginlogout-nextpage").click(function(){
        $login_outperpage = $(".agent-loginlogout-pager > span.pager-perpage > select").val();
        if($login_outperpage !== "all"){
            $login_outcurrpage = $(".agent-loginlogout-pager > span.pager-paginater > input");
            $login_outnext = parseInt($($login_outcurrpage).val()) + parseInt(1);
            nextpager($(".time-loginlogout-content"),$login_outnext,"agent-loginlogout-pager");
            $cols = [4];
            totalperpage("time-loginlogout-content",$cols,"agent-loginlogout-pager",$login_outnext);
        }
   });

   $("#loginlogout-firstpage").click(function(){
        $outbndperpage = $(".agent-loginlogout-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
             firstpager($(".time-loginlogout-content"),"agent-loginlogout-pager");
             $cols = [4];
             totalperpage("time-loginlogout-content",$cols,"agent-loginlogout-pager",1);
        }
   });

   $("#loginlogout-prevpage").click(function(){
        $outbndperpage = $(".agent-loginlogout-pager > span.pager-perpage > select").val();
             if($outbndperpage !== "all"){
                 $outbndcurrpage = $(".agent-loginlogout-pager > span.pager-paginater > input");
                 $next = parseInt($($outbndcurrpage).val()) - parseInt(1);
                 backpager($(".time-loginlogout-content"),$next,"agent-loginlogout-pager");
                 $cols = [4];
                 totalperpage("time-loginlogout-content",$cols,"agent-loginlogout-pager",$next);
             }
   });

   $("#loginlogout-lastpage").click(function(){
        $login_outperpage = $(".agent-loginlogout-pager > span.pager-perpage > select").val();
        if($login_outperpage !== "all"){
            lastpager($(".time-loginlogout-content"),$totalpages["agent-loginlogout-pager"],"agent-loginlogout-pager");
            $cols = [4];
            totalperpage("time-loginlogout-content",$cols,"agent-loginlogout-pager",$totalpages["agent-loginlogout-pager"]);
        }
   });

   $(".agent-loginlogout-pager > span.pager-perpage > select").change(function(){
       pagination($(".time-loginlogout-content"),$(".agent-loginlogout-pager").find(".pager-perpage > select").val(),"agent-loginlogout-pager");
       $cols = [4];
       totalperpage("time-loginlogout-content",$cols,"agent-loginlogout-pager",$totalpages["agent-loginlogout-pager"]);
   });
   // End of Agent login/logout

   $("#outboundthistime").click(function(){
       $(".agent-outbound-thistime").animate({top:60,left:"auto"});
       pagination($(".outbound-thistime-content"),$(".outbound-thistime-pager").find(".pager-perpage > select").val(),"outbound-thistime-pager");
   });

   $("#outbound-nextpage").click(function(){
        $outbndperpage = $(".outbound-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            $outbndcurrpage = $(".outbound-thistime-pager > span.pager-paginater > input");
            $outbndnext = parseInt($($outbndcurrpage).val()) + parseInt(1);
            nextpager($(".outbound-thistime-content"),$outbndnext,"outbound-thistime-pager");
        }
   });

   $("#outbound-firstpage").click(function(){
        $outbndperpage = $(".outbound-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
             firstpager($(".outbound-thistime-content"),"outbound-thistime-pager");
        }
   });

   $("#outbound-prevpage").click(function(){
        $outbndperpage = $(".outbound-thistime-pager > span.pager-perpage > select").val();
             if($outbndperpage !== "all"){
                 $outbndcurrpage = $(".outbound-thistime-pager > span.pager-paginater > input");
                 $next = parseInt($($outbndcurrpage).val()) - parseInt(1);
                 backpager($(".outbound-thistime-content"),$next,"outbound-thistime-pager");
             }
   });

   $("#outbound-lastpage").click(function(){
        $outbndperpage = $(".outbound-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            lastpager($(".outbound-thistime-content"),$totalpages["outbound-thistime-pager"],"outbound-thistime-pager");
        }
   });

   $(".outbound-thistime-pager > span.pager-perpage > select").change(function(){
       pagination($(".outbound-thistime-content"),$(".outbound-thistime-pager").find(".pager-perpage > select").val(),"outbound-thistime-pager");
   });
   // End of Outbound 

   $("#inboundthistime").click(function(){
       $(".agent-inbound-thistime").animate({top:60,left:"auto"});
       pagination($(".outbound-thistime-content"),$(".outbound-thistime-pager").find(".pager-perpage > select").val(),"outbound-thistime-pager");
   });

   $("#inbound-nextpage").click(function(){
        $outbndperpage = $(".inbound-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            $outbndcurrpage = $(".inbound-thistime-pager > span.pager-paginater > input");
            $outbndnext = parseInt($($outbndcurrpage).val()) + parseInt(1);
            nextpager($(".inbound-thistime-content"),$outbndnext,"inbound-thistime-pager");
            $cols = [1,3];
            totalperpage("inbound-thistime-content",$cols,"inbound-thistime-pager",$outbndnext);
        }
   });

   $("#inbound-firstpage").click(function(){
        $outbndperpage = $(".inbound-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
             firstpager($(".inbound-thistime-content"),"inbound-thistime-pager");
             $cols = [1,3];
             totalperpage("inbound-thistime-content",$cols,"inbound-thistime-pager",1);
        }
   });

   $("#inbound-prevpage").click(function(){
        $outbndperpage = $(".inbound-thistime-pager > span.pager-perpage > select").val();
             if($outbndperpage !== "all"){
                 $outbndcurrpage = $(".inbound-thistime-pager > span.pager-paginater > input");
                 $next = parseInt($($outbndcurrpage).val()) - parseInt(1);
                 backpager($(".inbound-thistime-content"),$next,"inbound-thistime-pager");
                 $cols = [1,3];
                 totalperpage("inbound-thistime-content",$cols,"inbound-thistime-pager",$next);
             }
   });

   $("#inbound-lastpage").click(function(){
        $outbndperpage = $(".inbound-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            lastpager($(".inbound-thistime-content"),$totalpages["inbound-thistime-pager"],"inbound-thistime-pager");
             $cols = [1,3];
             totalperpage("inbound-thistime-content",$cols,"inbound-thistime-pager",$totalpages["inbound-thistime-pager"]);
        }
   });

   $(".inbound-thistime-pager > span.pager-perpage > select").change(function(){
       pagination($(".inbound-thistime-content"),$(".inbound-thistime-pager").find(".pager-perpage > select").val(),"inbound-thistime-pager");
       $cols = [1,3];
       totalperpage("inbound-thistime-content",$cols,"inbound-thistime-pager",1);
   });
   // End of Inbound

   $("#agentactivity").click(function(){
       $(".agent-activity-thistime").animate({top:60,left:"auto"});
       pagination($(".agent-activity-content"),$(".agent-activity-pager").find(".pager-perpage > select").val(),"agent-activity-pager");
   });

   $("#agentactivity-nextpage").click(function(){
        $outbndperpage = $(".agent-activity-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            $outbndcurrpage = $(".agent-activity-pager > span.pager-paginater > input");
            $outbndnext = parseInt($($outbndcurrpage).val()) + parseInt(1);
            nextpager($(".agent-activity-content"),$outbndnext,"agent-activity-pager");
            $cols = [1,2,3,4,5,6];
            totalperpage("agent-activity-content",$cols,"agent-activity-pager",$outbndnext);
        }
   });

   $("#agentactivity-firstpage").click(function(){
        $outbndperpage = $(".agent-activity-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
             firstpager($(".agent-activity-content"),"agent-activity-pager");
             $cols = [1,2,3,4,5,6];
             totalperpage("agent-activity-content",$cols,"agent-activity-pager",1);
        }
   });

   $("#agentactivity-prevpage").click(function(){
        $outbndperpage = $(".agent-activity-pager > span.pager-perpage > select").val();
             if($outbndperpage !== "all"){
                 $outbndcurrpage = $(".agent-activity-pager > span.pager-paginater > input");
                 $next = parseInt($($outbndcurrpage).val()) - parseInt(1);
                 backpager($(".agent-activity-content"),$next,"agent-activity-pager");
                 $cols = [1,2,3,4,5,6];
                 totalperpage("agent-activity-content",$cols,"agent-activity-pager",$next);
             }
   });

   $("#agentactivity-lastpage").click(function(){
        $outbndperpage = $(".agent-activity-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            lastpager($(".agent-activity-content"),$totalpages["agent-activity-pager"],"agent-activity-pager");
            $cols = [1,2,3,4,5,6];
            totalperpage("agent-activity-content",$cols,"agent-activity-pager",$totalpages["agent-activity-pager"]);
        }
   });

   $(".agent-activity-pager > span.pager-perpage > select").change(function(){
       pagination($(".agent-activity-content"),$(".agent-activity-pager").find(".pager-perpage > select").val(),"agent-activity-pager");
       $cols = [1,2,3,4,5,6];
       totalperpage("agent-activity-content",$cols,"agent-activity-pager",1);
   });
   // End of agent activity

   $("#recordingthistime").click(function(){
       $(".agent-recording-thistime").animate({top:60,left:"auto"});
       pagination($(".record-thistime-content"),$(".recording-thistime-pager").find(".pager-perpage > select").val(),"recording-thistime-pager");
   });

   $("#recording-nextpage").click(function(){
        $outbndperpage = $(".recording-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            $outbndcurrpage = $(".recording-thistime-pager > span.pager-paginater > input");
            $outbndnext = parseInt($($outbndcurrpage).val()) + parseInt(1);
            nextpager($(".recording-thistime-content"),$outbndnext,"recording-thistime-pager");
        }
   });

   $("#recording-firstpage").click(function(){
        $outbndperpage = $(".recording-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
             firstpager($(".recording-thistime-content"),"recording-thistime-pager");
        }
   });

   $("#recording-prevpage").click(function(){
        $outbndperpage = $(".recording-thistime-pager > span.pager-perpage > select").val();
             if($outbndperpage !== "all"){
                 $outbndcurrpage = $(".recording-thistime-pager > span.pager-paginater > input");
                 $next = parseInt($($outbndcurrpage).val()) - parseInt(1);
                 backpager($(".recording-thistime-content"),$next,"recording-thistime-pager");
             }
   });

   $("#recording-lastpage").click(function(){
        $outbndperpage = $(".recording-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            lastpager($(".recording-thistime-content"),$totalpages["recording-thistime-pager"],"recording-thistime-pager");
        }
   });

   $(".recording-thistime-pager > span.pager-perpage > select").change(function(){
       pagination($(".recording-thistime-content"),$(".recording-thistime-pager").find(".pager-perpage > select").val(),"recording-thistime-pager");
   });
   // End of recording

   $("#manualoutbound").click(function(){
       $(".agent-manualoutbound-thistime").animate({top:60,left:"auto"});
       pagination($(".manualoutound-thistime-content"),$(".manualoutbound-thistime-pager").find(".pager-perpage > select").val(),"manualoutbound-thistime-pager");
   });

   $("#manualoutbound-nextpage").click(function(){
        $outbndperpage = $(".manualoutbound-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            $outbndcurrpage = $(".manualoutbound-thistime-pager > span.pager-paginater > input");
            $outbndnext = parseInt($($outbndcurrpage).val()) + parseInt(1);
            nextpager($(".manualoutbound-thistime-content"),$outbndnext,"manualoutbound-thistime-pager");
        }
   });

   $("#manualoutbound-firstpage").click(function(){
        $outbndperpage = $(".manualoutbound-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
             firstpager($(".manualoutbound-thistime-content"),"manualoutbound-thistime-pager");
        }
   });

   $("#manualoutbound-prevpage").click(function(){
        $outbndperpage = $(".manualoutbound-thistime-pager > span.pager-perpage > select").val();
             if($outbndperpage !== "all"){
                 $outbndcurrpage = $(".manualoutbound-thistime-pager > span.pager-paginater > input");
                 $next = parseInt($($outbndcurrpage).val()) - parseInt(1);
                 backpager($(".manualoutbound-thistime-content"),$next,"manualoutbound-thistime-pager");
             }
   });

   $("#manualoutbound-lastpage").click(function(){
        $outbndperpage = $(".manualoutbound-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            lastpager($(".manualoutbound-thistime-content"),$totalpages["manualoutbound-thistime-pager"],"manualoutbound-thistime-pager");
        }
   });

   $(".manualoutbound-thistime-pager > span.pager-perpage > select").change(function(){
       pagination($(".manualoutbound-thistime-content"),$(".manualoutbound-thistime-pager").find(".pager-perpage > select").val(),"manualoutbound-thistime-pager");
   });
   // End of manual outbound

   $("#leadsearchthistime").click(function(){
       $(".agent-leadsearch-thistime").animate({top:60,left:"auto"});
       pagination($(".leadsearch-thistime-content"),$(".leadsearch-thistime-pager").find(".pager-perpage > select").val(),"leadsearch-thistime-pager");
   });

   $("#leadsearch-nextpage").click(function(){
        $outbndperpage = $(".leadsearch-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            $outbndcurrpage = $(".leadsearch-thistime-pager > span.pager-paginater > input");
            $outbndnext = parseInt($($outbndcurrpage).val()) + parseInt(1);
            nextpager($(".leadsearch-thistime-content"),$outbndnext,"leadsearch-thistime-pager");
        }
   });

   $("#leadsearch-firstpage").click(function(){
        $outbndperpage = $(".leadsearch-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
             firstpager($(".leadsearch-thistime-content"),"leadsearch-thistime-pager");
        }
   });

   $("#leadsearch-thistime-prevpage").click(function(){
        $outbndperpage = $(".leadsearch-thistime-pager > span.pager-perpage > select").val();
             if($outbndperpage !== "all"){
                 $outbndcurrpage = $(".leadsearch-thistime-pager > span.pager-paginater > input");
                 $next = parseInt($($outbndcurrpage).val()) - parseInt(1);
                 backpager($(".leadsearch-thistime-content"),$next,"leadsearch-thistime-pager");
             }
   });

   $("#leadsearch-lastpage").click(function(){
        $outbndperpage = $(".leadsearch-thistime-pager > span.pager-perpage > select").val();
        if($outbndperpage !== "all"){
            lastpager($(".leadsearch-thistime-content"),$totalpages["leadsearch-thistime-pager"],"leadsearch-thistime-pager");
        }
   });

   $(".leadsearch-thistime-pager > span.pager-perpage > select").change(function(){
       pagination($(".leadsearch-thistime-content"),$(".leadsearch-thistime-pager").find(".pager-perpage > select").val(),"leadsearch-thistime-pager");
   });
   // End of leadsearch

   $(".userstat-closer").click(function(){
        $(this).parent().animate({top:"-3000px"});
        $(".overlay-leadinfo").fadeOut();
   });

});


$(document).ready(function(){
   try{
     //datesearch();
   }catch(err){}
});

function datesearch(){

   try{
       var $daterange = $('#widgetDate').children("span");
       var $dates = $daterange.text().split(' to ');
       var $user = $daterange.attr('id').replace('user-stat-',"");

       // agent talk time
       $.post(
               protocol+'//'+host+'/index.php/go_user_ce/agentTalkTimeStatus',
               {dates:$dates,user:$user},
               function(data){
                   $('.time-stat-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.time-stat-content').delay(200).empty().append(data); 
               }
       );

       // agent login logout 
       $.post(
               protocol+'//'+host+'/index.php/go_user_ce/agentLoginLogoutTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.time-loginlogout-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.time-loginlogout-content').delay(2000).empty().append(data); 
                   $accum = $('.time-loginlogout-content').children().size();
                   if($accum !== 0){
                       setTimeout('pagination($(".time-loginlogout-content"),$(".agent-loginlogout-pager").find(".pager-perpage > select").val(),"agent-loginlogout-pager");',2500);
                   } else {
                       $(".time-loginlogout-pager > span").hide();
                   }
                   $cols = [4];
                   totalperpage("time-loginlogout-content",$cols,"agent-loginlogout-pager",1);

               }
       );

       // outbound
       $.post(
               protocol+'//'+host+'/index.php/go_user_ce/outboundThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.outbound-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.outbound-thistime-content').delay(1000).empty().append(data); 
                   $accum = $('.outbound-thistime-content').children().size();
                   if($accum !== 0){
                       setTimeout('pagination($(".outbound-thistime-content"),$(".outbound-thistime-pager").find(".pager-perpage > select").val(),"outbound-thistime-pager");',2500);
                   } else {
                       $(".outbound-thistime-pager > span").hide();
                   }
               }
       );


       // leadsearch
       $.post(
               protocol+'//'+host+'/index.php/go_user_ce/leadsearchThisTime',
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
                                    //$('.leadsearch-thistime-content').append("<div class='realValue bubblemsg'><span class='user-cornerall spanbubble'>"+$elipsisValue+"</span></div>");
                                }
                           });
                           $('.leadsearch-thistime-content').append($(this)); 
                           $('.leadsearch-thistime-content').append("<br class='spacer'/>"); 
                       }
                   });
                   $(".toolTip").tipTip();
                   $accum = $('.leadsearch-thistime-content').children().size();
                   if($accum !== 0){
                       setTimeout('pagination($(".leadsearch-thistime-content"),$(".leadsearch-thistime-pager").find(".pager-perpage > select").val(),"leadsearch-thistime-pager");',2500);
                   } else {
                       $(".leadsearch-thistime-pager > span").hide();
                   }
               }
       );

       // agent activity
       $.post(
               protocol+'//'+host+'/index.php/go_user_ce/agentActivity',
               {dates:$dates,user:$user},
               function(data){
                   $('.agent-activity-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.agent-activity-content').delay(2000).empty().append(data); 
                   $accum = $('.agent-activity-content').children().size();
                   if($accum !== 0){
                       setTimeout('pagination($(".agent-activity-content"),$(".agent-activity-pager").find(".pager-perpage > select").val(),"agent-activity-pager");',2500);
                   } else {
                       $(".agent-activity-pager > span").hide();
                   }
                   $cols = [1,2,3,4,5,6];
                   totalperpage("agent-activity-content",$cols,"agent-activity-pager",1);
               }
       );






       // inbound
       $.post(
               protocol+'//'+host+'/index.php/go_user_ce/inboundThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.inbound-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.inbound-thistime-content').delay(2000).empty().append(data); 
                   $accum = $('.inbound-thistime-content').children().size();
                   if($accum !== 0){
                       setTimeout('pagination($(".inbound-thistime-content"),$(".inbound-thistime-pager").find(".pager-perpage > select").val(),"inbound-thistime-pager");',2500);
                   } else {
                       $(".inbound-thistime-pager > span").hide();
                   }
                   $cols = [1,6];
                   totalperpage("inbound-thistime-content",$cols,"inbound-thistime-pager",1);
               }
       );

       // recording
       $.post(
               protocol+'//'+host+'/index.php/go_user_ce/recordingThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.recording-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.recording-thistime-content').delay(2000).empty().append(data);
                   // recreating result making it object
                   /*$.each($.makeArray($(data)),function(){
                       if($(this).is('div')){
                           $.each($.makeArray($(this).children("div.cols")),function(){
                                if($(this).hasClass('elipsis')){
                                    var $elipsisChild = $(this).children();
                                    if($elipsisChild.is("a") === false){ 
                                        var $elipsisValue = $(this).text();
                                        $(this).empty(); // cleaning 
                                        $(this).text($elipsisValue.substring(0,20)+"...");// cut and paste the text
                                        //$('.recording-thistime-content').append("<div class='realValue bubblemsg'><span class='user-cornerall spanbubble'>"+$elipsisValue+"</span></div>"); // adding real value of elipsis
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
                   });*/
                   $(".toolTip").tipTip();
                   $accum = $('.recording-thistime-content').children().size();
                   if($accum !== 0){
                       setTimeout('pagination($(".recording-thistime-content"),$(".recording-thistime-pager").find(".pager-perpage > select").val(),"recording-thistime-pager");',2500);
                   } else {
                       $(".recording-thistime-pager > span").hide();
                   }
               }
       );


       // manual Outbound
       $.post(
               protocol+'//'+host+'/index.php/go_user_ce/manualoutboundThisTime',
               {dates:$dates,user:$user},
               function(data){
                   $('.manualoutbound-thistime-content').append('<div class="intermission"><img align="center" src="'+protocol+'//'+host+'/img/loading.gif" /><br/></div>');
                   $('.manualoutbound-thistime-content').delay(2000).empty().append(data); 
                   $accum = $('.manualoutbound-thistime-content').children().size();
                   if($accum !== 0){
                       setTimeout('pagination($(".manualoutbound-thistime-content"),$(".manualoutbound-thistime-pager").find(".pager-perpage > select").val(),"manualoutbound-thistime-pager");',2500);
                   } else {
                       $(".manualoutbound-thistime-pager > span").hide();
                   }
               }
       );
   
      
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
                 $(".wizard-content").append("<div class='processing'><img src='"+protocol+'//'+host+'/img/goloading.gif'+"'></div>"); 
                 $.post(
                         //protocol+'//'+host+'/index.php/go_user_ce/userwizard',
                         protocol+'//'+host+'/index.php/go_user_ce/userwizard',
                         $(form).serialize(),
                         function(data){


                             if((data.indexOf('Error') !== -1) || (data.indexOf('Success') !== -1)){
                                 $(".processing").remove();
                                 alert(data);
                                 if(data.indexOf('Error') !== -1){
                                     return false;
                                 } else if (data.indexOf('Success') !== -1) {
                                     location.reload();
                                 }
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
                                                    $(".processing").remove();
                                                    if($(this).children().length > 0){
                                                        $(this).children("div").each(function(){
                                                              if($(this).hasClass("wizard-content-left")){
                                                                  $(this).find("img").attr("src",$layout.content.left);
                                                              }else{
                                                                  $(this).empty().append($layout.content.right);
                                                              }
                                                        });
                                                    }
                                            break;
                                            case 'wizard-action':
                                                    $(this).empty().append($layout.action);
                                            break;
                                       }
                                 });
                                 $consolidated[$consolidated.length] = $result.prev_step;
                                 var $container = $(".wizard-box").outerWidth(true) + 70;
                                 if($container > $(window).height()){
                                     $(".wizard-box").css("position","absolute");
                                 }
                             }catch(err){console.log(err);}
                         }
                 );
            }
       }
   });

   $($contentForm).submit();
}


function leadinfo(leadelem){

    $.post(
           protocol+'//'+host+'/index.php/go_search_ce/leadinfo',
           {leadid:leadelem},
           function(data){

               if(data.indexOf("Error") === -1){
                   var $result  = JSON.parse(data);
                   $(".lead_id").empty().append($result[0].lead_id);
                   $(".list_id").empty().append($result[0].list_id);
                   $(".address1").empty().append(($result[0].address1 === "")?'&nbsp;': $result[0].address1);
                   $(".phone_code").empty().append(($result[0].phone_code === "")?'&nbsp;': $result[0].phone_code);
                   $(".phone_number").empty().append(($result[0].phone_number === "")?'&nbsp;': $result[0].phone_number);
                   $(".city").empty().append(($result[0].city === "")?'&nbsp;': $result[0].city);
                   $(".state").empty().append(($result[0].state === "")?'&nbsp;': $result[0].state);
                   $(".postal_code").empty().append(($result[0].postal_code === "")?'&nbsp;': $result[0].postal_code);
                   $(".comment").empty().append(($result[0].comment === "") ? '&nbsp;' : $result[0].comment);
                   $(".overlay-leadinfo").fadeIn();
                   $(".leadinfo").animate({top:60});
               } else {
                     alert("Error: No Lead info!");
               }

           }
    );

}

$(function(){

    $(".copy-user").click(function(){
          $('.overlay-info').fadeIn('fast');
          $(".wizard-copy").css('margin','auto').animate({top:70});
    });

    $("#copy-proceed").click(function(){


        $("#copy-form").validate({
             rules: {
                    user : {required : true,duplicate : true,noSpace : true},
                    pass : {required : true,letterOnly : true,minlength: 2 }
             },
             messages : {
                    user : {required : "* Required"},
                    pass : {required : "* Required",minlength:"Atleast 2 character"}
             },
             submitHandler : function(form) {

                    $.post(
                             protocol+"//"+host+"/index.php/go_user_ce/copyuser/", 
                             $('#copy-form').serialize(),
                             function(data){
                                   alert(data);
                                   //$(form).find("input#user").val("");
                                   $(form).find("input#pass").val("");
                                   $(form).find("input#full_name").val("");
                                   setTimeout("location.reload();",500);
                             }
                      );

             }
        });

        $.validator.addMethod('duplicate',
                         function(value,elem){

                                return responder(value);
                         },
                         "Entry exists");
         $.validator.addMethod('letterOnly',
                          function(value,elem){
                              return value.match(new RegExp("^[a-zA-Z0-9]+$"));
                          },
                          "No character(s)");
         $.validator.addMethod('noSpace',
                          function(value,elem){
                               return value.indexOf(" ") < 0 && value != "";
                          },
                          "No space(s)"
                          );

        $("#copy-form").submit();



    });




});


function formatuser($obj){

    if($($obj).prop('checked') === false){
       $($obj).siblings("input[type='text']").removeAttr("readonly");
    }else{
       $($obj).siblings("input[type='text']").attr("readonly",'true');
    }

}


$(function(){
 
   $("#user-permission").click(function(){
        $(".user-permission").slideToggle(function(){
              if($(this).css("display") === "block"){
                  $("#user-permission").empty().append("Basic [ - ]");
              }else{
                  $("#user-permission").empty().append("Advance [ + ]");
              }
        });
   });

});
