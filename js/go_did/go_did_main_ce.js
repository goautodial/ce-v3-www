/*
############################################################################################
####  Name:             go_did_main_ce.js                                               ####
####  Type:             ci js for script                                                ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
*/

$(function(){

    $('#did-tab').tabs({
       ajaxOptions:{
             data: {script_id:'tst'},
             type: "POST",
             cache: false
       },
       show: function (){ // disable the advance tab?
            try{
                $(this).tabs("option","disabled",[]);
            }catch(err){}
       },
       fx: {opacity : 'toggle'},
       disabled: [1],
       cache: false
    });

});

$(function(){

    $("#did-add").click(function(){

        wizard($(".did-add-wizard-container"),5,300,20) ;

    });

});

$(function(){

   $("#step-next").click(function(){

        $.validator.addMethod("allowToDID",
            function(value, element){
                   return this.optional(element) || /^[0-9]+$/i.test(value);
             },
             "Invalid DID Extension"
        );


         $("#did-add-wizard").validate({

               rules: {
                       did_pattern:{ required:true,
                                     allowToDID:true,
                                     minlength:7,
                                     maxlength:12
                                   }
               },
               submitHandler: function(form){
  
                    if($(this).valid){

                        $.post(
                               $_protocol+"//"+$_host+$_base_path+"/index.php/go_did_ce/newdid",
                               $("#did-add-wizard").serialize(),
                               function (data){
                               
                                   alert(data);
                                   if(data.indexOf("Error") === -1){
                                       location.reload();
                                   }

                               }
                        );
 
                    }

               }

         });

         $("#did-add-wizard").submit(); 


   });


});
