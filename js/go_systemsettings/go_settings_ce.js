$(function(){

    $("#updatesettings").click(function(){
             $.post(
                   $_protocol+'//'+$_host+'/index.php/go_systemsettings_ce/updatesettings',
                   $("#system-settings").serialize(),
                   function(data){
                        alert(data);
                        
                        location.reload();
                   }
             );

    });

});
