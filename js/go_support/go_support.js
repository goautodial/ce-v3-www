/*
############################################################################################
####  Name:             go_support.js                                                 ####
####  Type:             ci common jquery used in the ci                                 ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
*/
var protocol = window.location.protocol;
var host = window.location.host;
var $_path_string = window.location.pathname.substr(1);
var $_base_path = $_path_string.split("/")[0];

// override value of basepath
if($_base_path === "index.php"){
   $_base_path = "";
}else{
   //$_base_path = "/"+$_base_path;
   $_base_path = "";
}


$(function () {
  //  CKEDITOR.replace('description');
});



$(document).ready(function (){

    $("#step-next").click(function(){

        $.validator.addMethod("allowToAccount",
            function(value, element){
                   return this.optional(element) || /^[0-9]+$/i.test(value);
             },
             " Invalid Acct"
        );



        $("#newticket").validate({

             submitHandler: function(){

                if($(this).valid){
                    $(".support-wizard-content").append("<div class='processing'><img src='"+$_protocol+'//'+$_host+$_base_path+'/img/goloading.gif'+"'></div>"); 
                    $.post(
                      protocol+"//"+host+$_base_path+"/index.php/go_support_ce/newticket",
                      $("#newticket").serialize(),
                      function (data){
                          if(window.closer !== undefined){
                               closer($(".support-wizard-container"));
                               $("#table_submitted_tickets").empty().html('<img src="'+protocol+'//'+host+$_base_path+'/img/loading.gif" />');
                               $('#table_submitted_tickets').fadeOut("slow").load(protocol+'//'+host+$_base_path+'/index.php/go_support_ce/go_curl_view_tickets/'+freshdeskaccount).fadeIn("slow");
                               clearall();
                          }
                          alert(data);
                          //setTimeout("alert('"+data+"');",2000);
                          $(".processing").remove();
                      }
                    );
                }
             }
        });


        $.each($("#newticket").children('div.support-values').children(),function(){
             var $elemId = $(this).attr("id");
             if($elemId !== undefined && ($elemId !== "attachment" && $elemId !== "attachment-queue")){

                 switch($elemId){

                    case "accountNum":
                          $(this).rules("add",{required:true,minlength:8,allowToAccount:true,
                                               messages:{required:"* req",minlength:"* minimum of 8 digits"} });
                    break;

                    case "accntemail":
                          $(this).rules("add",{required:true,email:true, 
                                               messages:{required:"* req",email:"email format"}});
                    break;
                    default:
                          $(this).rules("add",{required:true,messages:{required:"* req"}});
                    break;
                 }

             }
        });

        $("#newticket").submit();

    });



});

function slideonlyone(thisone) {

     $('div[name|="desc"]').each(function(index) {
          if ($(this).attr("id") == thisone) {
               $(this).slideDown(200);
          }
          else {
               var $hidethis = $(this).attr('id');
               $("#note_"+$hidethis).hide();
               $("#addnote_"+$hidethis).show();
               $(this).slideUp(600);
          }
     });
}

function clearall(){
   $('input[class|="valid"]').each(function(index){
       if($(this).hasClass("escape") === false){
            $(this).val("");
       }
   });
   $('#description').val("");
   $('#group').val("");
} 

function filterticket(email,type){

       $("#table_submitted_tickets").empty().html('<img src="'+protocol+'//'+host+$_base_path+'/img/loading.gif" />');
       $('#table_submitted_tickets').fadeOut("slow").load(protocol+'//'+host+$_base_path+'/index.php/go_support_ce/go_curl_view_tickets/'+freshdeskaccount,{statustype:type}).fadeIn("slow");

}

$(function(){

    if(window.reposition !== undefined){

         var $objs = [$(".support-wizard-container")];
 
         reposition($objs);

    }

    if(window.wizard !== undefined){
 
         $("#support-add").click(function(){

             wizard($(".support-wizard-container"));

         });

    }

    if(window.closer !== undefined){

        $(".overlay-closer").click(function(){

            closer($(".support-wizard-container"));

        });

    } 

    /*$(".support-wizard-container").offset({left:((Math.ceil($(window).width()) / 2) - (Math.ceil($(".support-wizard-container").outerWidth(true)) / 2))});
    $(".overlay-closer2").offset({top:-3000,left:((Math.ceil($(window).width()) / 2) + (Math.ceil($(".support-wizard-container").outerWidth(true)) / 2) - 10)});
    $("#support-add").click(function(){

        $(".overlay").fadeIn(900);
        $(".overlay-closer2").animate({top:60},900);
        $(".support-wizard-container").animate({top:70},900); 

    });

    $(".overlay-closer2").click(function(){
        $(".overlay").fadeOut(900);
        $(".overlay-closer2").animate({top:-3000},900);
        $(".support-wizard-container").animate({top:-3000},900); 

    });*/

});
