/*
############################################################################################
####  Name:             go_callmenu_main.js                                             ####
####  Type:             ci js for script                                                ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
*/

$(function(){

   if(window.reposition !== undefined){
       reposition([$(".callmenu-wizard")]);
   }

   $("#callmenuadd").click(function(){
       if(window.wizard !== undefined){
            wizard($(".callmenu-wizard"));
       }
   });

   $(".overlay-closer").click(function(){
       if(window.closer !== undefined){
            if($(".digit-display").css("display") === "block"){
                $(".digit-display").hide('slide',{direction:'left'},900); 
                $(".callmenu-wizard").animate({width:300});
            }
            $(".pdigits").css("background-color","white");
            $("#menuid").val('');
            $("#menuname").val('');
            $("#custom_dialplan").val('');
            $("#route").val($("#route option:first").val());
            $("#enter_filename").val('');
            $("#id_number_filename").val('');
            $("#confirm_filename").val('');
            $("#hangup_audio").val('');
            $("#extension").val('');
            $("#diventer_filename").text('');
            $("#divid_number_filename").text('');
            $("#divconfirm_filename").text('');
            $("#divhangup_audio").text('');
            $("#divvoicemail_box").text('');
            $("#route").parent().siblings().each(function(){
                 $(this).hide();
            });
           setTimeout('closer($(".callmenu-wizard"));',900);
       }
   });

   $("#route").change(function(){
        $(this).parent().siblings().each(function(){
             $(this).hide();
        });
        switch($(this).val()){
             case '1':
                   $(".callmenu").fadeIn(900);
             break;
             case '2':
                   $(".ingroup").fadeIn(900);
             break;
             case '3':
                   $(".did").fadeIn(900);
             break;
             case '4':
                   $(".hangup").fadeIn(900);
             break;
             case '5':
                   $(".extension").fadeIn(900);
             break;
             case '6':
                    $('.phone').fadeIn(900);
             break;
             case '7':
                   $(".voicemail").fadeIn(900);
             break;
             case '8':
                   $(".agi").fadeIn(900);
             break;
        }
   });

   // save route:phone
   $("#save-digit").click(function(){
        var $datas = {};
        var pdigit = 0;
        var $jsonData = '';
        
        $(".pdigits").each(function()
        {
            if ($(this).css("background-color") == "rgb(255, 0, 0)")
            {
                pdigit = $(this).val();
                console.log(pdigit);
            }
        });
        switch($("#route").val()){
            case '1':
                $datas['callmenu']=$('#callmenu').val();
            break;
            case '2':
                $datas['group_id']=$('#group_id').val();
                $datas['handle_method']=$('#handle_method').val();
                $datas['search_method']=$('#search_method').val();
                $datas['campaign_id']=$('#campaign_id').val();
                $datas['phone_code']=$('#phone_code').val();
                $datas['list_id']=$('#list_id').val();
                $datas['enter_filename']=$('#enter_filename').val();
                $datas['id_number_filename']=$('#id_number_filename').val();
                $datas['confirm_filename']=$('#confirm_filename').val();
                $datas['validate_digits']=$('#validate_digits').val();
            break;
            case '3':
            break;
            case '4':
            break;
            case '5':
            break;
            case '6':
                $datas['phone']=$("#phones").val();
            break;
            case '7':
            break;
            case '8':
            break;
        }
        
        
        $(".phone-entry").children().each(function(){
            //console.log($(this).hasClass('entry-elem'));
        });
        
        $datas['route']=$("#route").find(":selected").text().toLowerCase();
        $jsonData = JSON.stringify($datas);
        console.log($jsonData);
   });

});

function digitdisplay(obj){

    $(obj).css("background-color","red");
    $(obj).siblings().each(function(){
        $(this).css("background-color","white");
    });
    if($(".digit-display").css("display")==="block"){
        $(".digit-display").hide('slide',{direction:'left'},600); 
    }
    $(".callmenu-wizard").animate({width:750});
    $(".digit-display").show('slide',{direction:'left'},900); 

    $("#enter_filename").val('');
    $("#id_number_filename").val('');
    $("#confirm_filename").val('');
    $("#hangup_audio").val('');
    $("#extension").val('');
    $("#route").val($("#route option:first").val());
    $("#diventer_filename").text('');
    $("#divid_number_filename").text('');
    $("#divconfirm_filename").text('');
    $("#divhangup_audio").text('');
    $("#divvoicemail_box").text('');
    $("#route").parent().siblings().each(function(){
         $(this).hide();
    });
}
