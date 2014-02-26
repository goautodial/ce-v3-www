$(document).ready(function() {
	
	// Expand Panel
	$("#quick-support-open").click(function(){
                $("#subject").slideDown("slow").delay(300);
                $("#description").slideDown("slow").delay(300);
                $("#subjectlabel").slideDown("slow").delay(300);
                $("#descriptionlabel").slideDown("slow").delay(300);
                $("#quick-button").slideDown("slow").delay(300);
		$("div#quick-support-detail").slideDown("slow");
	});	
	
	// Collapse Panel
	$("#quick-support-close").click(function(){
                $("#subject").slideUp("fast");
                $("#description").slideUp("fast");
                $("#subjectlabel").slideUp("fast");
                $("#descriptionlabel").slideUp("fast");
                $("#quick-button").slideUp("fast");
		$("div#quick-support-detail").slideUp("slow");	
	});		
	
	$("#bullhorn a").click(function () {
		$("#bullhorn a").toggle();
	});		
		
});

$(function () {

    $('#quicksupport').validate({
         rules: {
              quickdescription: {required: true}
         }
    });


});
