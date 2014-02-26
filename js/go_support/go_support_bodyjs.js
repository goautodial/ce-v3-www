/*
############################################################################################
####  Name:             go_support_bodyjs.js                                            ####
####  Type:             ci support bodyjs jquery used in the ci                         ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
*/

$(function () {
  //  CKEDITOR.replace('description');

    $('input[class|="callnote"]').each(function (index) {

         $(this).click(function () {
             var $id = $(this).attr('id');
             var $addnote = $id.replace("addnote","note");

             $("#"+$addnote).show();
             $(this).hide();

         });

    });


    $('input[class|="add-note"]').each(function (index){
        $(this).click( function (){
 
            var $id = $(this).attr('id');
            var $rcvID = $id.replace("add-note-","");
            var $note = $("#client-note_"+$rcvID).val(); 

            $(".support-wizard-content").append("<div class='processing'><img src='"+$_protocol+'//'+$_host+$_base_path+'/img/goloading.gif'+"'></div>"); 
            $.post(
                      protocol+"//"+host+$_base_path+"/index.php/go_support_ce/modifyticket/",
                      {id : $rcvID , note : $("#client-note_"+$rcvID).val(), requesterid : $("#requester-id-"+$rcvID).val() },
                      function (data){
                          $("#table_submitted_tickets").empty().html('<img src="'+protocol+'//'+host+$_base_path+'/img/loading.gif" />');
                          $('#table_submitted_tickets').fadeOut("slow").load(protocol+'//'+host+$_base_path+'/index.php/go_support_ce/go_curl_view_tickets/'+freshdeskaccount).fadeIn("slow");
                          $(".processing").remove();
                          alert(data);
                      }
                  );

        });
    });


    /*
     * close ticket not yet implemented
     */
    $('input[class|="close-ticket"]').each(function (index){
         $(this).click(function (){
            alert('close ticket');
         });
    });

});

$(function (){

    setInterval(
       function (){
           updateconversation();
       },600000
    );

});


function updateconversation(){

    $('div[class|="conversation-container"]').each(function (index){
        var $id = $(this).attr('id');
        var $displayid = $id.replace("conv-container-","");
	$("#"+$id).empty().html('<img src="'+protocol+'//'+host+$_base_path+'/img/loading.gif" align="center"/>');
        $("#"+$id).fadeOut("slow").load(protocol+'//'+host+$_base_path+'/index.php/go_support_ce/updateconversation/'+freshdeskaccount,{displayid:$displayid}).fadeIn("slow");
    });
}

$(function(){

    try {

        if(window.paginate) {

            paginate($("#tickets"),$(".pager-perpage > select").val());

        }

    } catch(err) {

       console.log(err);

    }
});

$(function(){

    $obj = $("#tickets");

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
