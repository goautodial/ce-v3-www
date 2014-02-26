 /*
########################################################################################################
####  Name:             	go_user_body_ce.js                                                  ####
####  Type:             	ci js for user - administrator                                      ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Franco Hora					            	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
*/


$(function(){

    if($(".user-tbl-container").children().size() === 1){

        $(".overlay").show().fadeIn('slow');
        $('.wizard-box').offset({top: -3000 }).css("margin-left","auto").css("margin-right","auto").animate({"top":"70px"},900).delay(2000);
    
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

});
