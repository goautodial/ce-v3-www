<?php
############################################################################################
####  Name:             go_list.php                                                     ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  License:          AGPLv2                                                          ####
############################################################################################

$base = base_url();
echo $lead_file;
?>
<script>
	$(document).ready(function(){

				var $tabs = $('#tabs').tabs();
				var $tabvalsel = $('#tabvalsel').val();				
				
				$( "#tabs" ).tabs();
			
				/* tabs navigation */				
				if($tabvalsel=="tabloadleads") {
			                $tabs.tabs('select', 2);
				}	

				if($tabvalsel=="customleads")	{
			        	$tabs.tabs('select', 1);						
				}
				
				$('#list_id').bind("keydown keypress", function(event)
				{
				 //console.log(event.type + " -- " + event.altKey + " -- " + event.which);
				 if (event.type == "keydown") {
				  // For normal key press
				  if (event.keyCode == 32 || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				   || event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				   || event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				   return false;
				  
				  if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				   return false;
				  
				  if (!event.shiftKey && event.keyCode == 173)
				   return false;
				 } else {
				  // For ASCII Key Codes
				  if ((event.which > 31 && event.which < 48) || (event.which > 57 ))
				   return false;
				 }
				 
				 $(this).css('border','solid 1px #999');
				});
				
				
				
                                   $("#list_id").keydown(function(event) {
                                   if(event.shiftKey)
                                        event.preventDefault();
                                   if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9) {
                                   }
                                   else {
                                        if (event.keyCode < 95) {
                                          if (event.keyCode < 48 || event.keyCode > 57) {
                                                event.preventDefault();
                                          }
                                        }
                                        else {
                                              if (event.keyCode < 96 || event.keyCode > 105) {
                                                  event.preventDefault();
                                              }
                                        }
                                      }
                                   });

				$("#field_label").keydown(function(event) {
    					if (event.keyCode == 32) {
        				event.preventDefault();
    				}
				
});
				
				$("#field_options").keydown(function(event) {
    					if (event.keyCode == 32) {
        				event.preventDefault();
    				}
				});

		
		$("#advancedFieldLink").click(function()
		{
			if ($(".advancedFields").is(':hidden'))
			{
				$(".advancedFields").show();
				$(this).html("[ - HIDE ADVANCE FIELDS ]");
			} else {
				$(".advancedFields").hide();
				$(this).html("[ + SHOW ADVANCE FIELDS ]");
			}
		});
    });  
</script>


<script>
$(document).ready(function() 
    { 
        $("#listtableresult").tablesorter({sortList:[[0,0]], headers: { 6: { sorter: false}, 7: {sorter: false} }});
        $("#cumstomtable").tablesorter({sortList:[[0,0]], headers: { 6: { sorter: false}, 7: {sorter: false} }});
    } 
); 
</script>

<!--  Javascript section -->
<script language="javascript">


	function checklistadd() {
		var camp_iddetail = document["go_listfrm"]["campaign_id"].value;
		
		if (camp_iddetail=="") {
			alert("Campaign is empty."); return false;
		} else {
			document['go_listfrm'].submit();	
		}
		
	}

	function getselval() {
    		var account_num = document.getElementById('campaign_id').value;
	}

	function showaddlist(listid) {
		document.getElementById('addlist').style.display='block';
		document.getElementById('showlist').style.display='none';
		document.getElementById('list_id').value = listid;
	}


	function showRow() {
    		var autoGen = document.getElementById('auto_gen');

    		if (autoGen.checked) {
        		document.getElementById('list_id').readOnly = true;
        		document.getElementById('list_id').value = '';
        		document.getElementById('list_name').value = '';
        		document.getElementById('list_description').value = '';
        		document.getElementById('autogenlabel').innerHTML= "<font color='red' size='1'>(auto generated) </font>";
										genListID();
    		} else {
      	 		document.getElementById('list_id').readOnly = false;
        		document.getElementById('list_id').value = '';
        		document.getElementById('list_name').value = '';
        		document.getElementById('list_description').value = '';
        		document.getElementById('autogenlabel').innerHTML= "<font color='red' size='1'>(numeric only) </font>";
    		}
	}


	function genListID() {
		document.getElementById('list_id').readOnly = true;
    		var account_num = document.getElementById('account_num');
    		var cntX=0;
		    		

        		var autoListID;
        			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            				xmlhttp=new XMLHttpRequest();
            			} else {// code for IE6, IE5
            				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        			}
        	
			xmlhttp.onreadystatechange=function()
            		{
            			if (xmlhttp.readyState==4 && xmlhttp.status==200)
                		{
                			var returnString=xmlhttp.responseText;
                			var returnArray=returnString.split("\n");
					var cnt=returnArray[1];
                			var camp_list=returnArray[2].split(",");
                			var camp_name=returnArray[3].split(",");
					var accnt_num = returnArray[3];
                			var i=0;
                			
                			cntX=accnt_num;

					
                			
							var currentTime = new Date();
							var month = currentTime.getMonth() + 1;
							var day = currentTime.getDate();
							var year = currentTime.getFullYear();
							var comdate = month+'-'+day+'-'+year;
							
							document.getElementById('list_name').value = 'ListID ' + cntX;
							document.getElementById('list_description').value = 'Auto-Generated ListID - '+comdate;
							document.getElementById('list_id').value = cntX;
									

					

                		}	
            		}
        		//xmlhttp.open("GET","go_list/go_list.php?stage=addLIST&accnt="+autoListID,true);
			xmlhttp.open("GET","",true);
        		xmlhttp.send();
	} //end function
	
	function ParseFileName() {
			
	if (!document.uploadform.OK_to_process) 
		{	
		
		var filename = document.getElementById("leadfile").value;

		var endstr = filename.lastIndexOf('');
			
		if (endstr>-1) 
			{
			endstr++;
			document.getElementById('leadfile_name').value=filename;
			}
		}
	}

	function ShowProgress(good, bad, total, dup, post) {
		parent.lead_count.document.open();
		parent.lead_count.document.write('<html><body><table border=0 width=200 cellpadding=10 cellspacing=0 align=center valign=top><tr bgcolor="#000000"><th colspan=2><font face="arial, helvetica" size=3 color=white>Current file status:</font></th></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B>Good:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+good+'</B></font></td></tr><tr bgcolor="#990000"><td align=right><font face="arial, helvetica" size=2 color=white><B>Bad:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+bad+'</B></font></td></tr><tr bgcolor="#000099"><td align=right><font face="arial, helvetica" size=2 color=white><B>Total:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+total+'</B></font></td></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B> &nbsp; </B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B> &nbsp; </B></font></td></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B>Duplicate:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+dup+'</B></font></td></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B>Postal Match:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+post+'</B></font></td></tr></table><body></html>');
		parent.lead_count.document.close();
	}

	function copyview() {
                $('#overlaycopy').fadeIn('fast',function(){
                        $('#boxcopy').animate({'top':'70px'},500);
                });
                                         
                $('#boxclosecopy').click(function(){
                        $('#boxcopy').animate({'top':'-550px'},500,function(){
                         $('#overlaycopy').fadeOut('fast');
                        });
                });
                
                $('#btnclose').click(function(){
                        $('#boxcopy').animate({'top':'-550px'},500,function(){
                          $('#overlaycopy').fadeOut('fast');
                       });
                });     

	}	

	function viewadd() {

<?php
                             $permissions = $this->commonhelper->getPermissions("customfields",$this->session->userdata("user_group"));
                             if($permissions->customfields_create == "N"){
                                echo("alert('You don\'t have permission to create this record(s)');");
                                echo "return false;";
                             }
                ?>
																document.getElementById('hide_listid').value= "";
                document.getElementById('field_label').value= "";
                document.getElementById('field_rank').value= "";
                document.getElementById('field_order').value= "";
                document.getElementById('field_name').value= "";
                document.getElementById('name_position').value= "";
                document.getElementById('field_description').value= "";
                document.getElementById('field_type').value= "";
                document.getElementById('field_options').value= "";
                document.getElementById('multi_position').value= "";
                document.getElementById('field_size').value= "";
                document.getElementById('field_max').value= "";
                document.getElementById('field_default').value= "";
                document.getElementById('field_required').value= "";

                $('#overlayadd').fadeIn('fast',function(){
                        $('#boxaddcustom').animate({'top':'-50px'},500);
                });
                                         
                $('#boxclose').click(function(){
                        $('#boxaddcustom').animate({'top':'-3000px'},500,function(){
                                $('#overlayadd').fadeOut('fast');
                                
                        });
                        $('#spanaddcustom').show("fast");
						 																	$('#spancopycustom').hide("fast");
                });
                
                $('#btnclose').click(function(){
                        $('#boxaddcustom').animate({'top':'-3000px'},500,function(){
                                $('#overlayadd').fadeOut('fast');
                        });
                });     

	}


	function customviews(listid) {
		

			$.post("<?=$base?>index.php/go_list/editcustomview", { action: "customview", list_id: listid   },
				 function(data){
                          
                				var datas = data.split("||");
                				var i=0;
                				var count_listid = datas.length;
                				
                				document.getElementById('viewme').innerHTML = data;

 								$('#overlayview').fadeIn('fast',function(){
 								  	$('#boxview').show();
 								  	$('#overlayview').show();
	                  			$('#boxview').animate({'top':'70px'},500);
		             			});
	             				 
	             				$('#boxcloseview').click(function(){
	              					$('#boxview').animate({'top':'-550px'},500,function(){
			                  			$('#overlayview').fadeOut('fast');
			                  			$('#boxview').hide();
			                  			$('#overlayview').hide();
	              					});
								});
								 	
								$('#btncloseview').click(function(){
	              					$('#boxview').animate({'top':'-550px'},500,function(){
	                  					$('#overlayview').fadeOut('fast');
	              					});
								});	
				});	
	}	
	

	function addsubmit() {
                
		var hide_listid = document.getElementById('hide_listid').value;
                var field_label = document.getElementById('field_label').value;
                var field_rank = document.getElementById('field_rank').value;
                var field_order = document.getElementById('field_order').value;
                var field_name = document.getElementById('field_name').value;
                var name_position = document.getElementById('name_position').value;
                var field_description = document.getElementById('field_description').value;
                var field_type = document.getElementById('field_type').value;
                var field_options = document.getElementById('field_options').value;
                var multi_position = document.getElementById('multi_position').value;
                var field_size = document.getElementById('field_size').value;
                var field_max = document.getElementById('field_max').value;
                var field_default = document.getElementById('field_default').value;
                var field_required = document.getElementById('field_required').value;
              //var field_options = $("#field_options").val().replace("\n", "");
	      
  if(hide_listid == "") {
  	alert('List I.D. is a required field.');
			return false;
  }
  if(hide_listid == "copycustomselect") {
  	alert('Choose List I.D.');
			return false;
  }
		if(field_label == "") {
			alert('Label is a required field.');
			return false;
		}
		if(field_name == "") {
			alert('Name is a required field.');
			return false;
		}
		if(field_rank == "") {
			alert('Rank is a required field.');
			return false;
		}
		if(field_order == "") {
			alert('Order is a required field.');
			return false;
		}
		if(field_max == "") {
                        alert('Field max is a required field.');
                        return false;
                }
		if((field_size == "") || (field_size < 1)) {
			alert('Field size is a required field.');
			return false;
		}
		
 
		 $.post("<?=$base?>index.php/go_list/editcustomview", { action: "customadd", listid: hide_listid, field_label: field_label, field_rank: field_rank, field_order: field_order, field_name: field_name, name_position: name_position, field_description: field_description, field_type: field_type, field_options: field_options, multi_position: multi_position, field_size: field_size, field_max: field_max, field_default: field_default, field_required: field_required },
                                         
                                 function(data){
					      alert("SUCCESS: Custom Field Added");
                                              location.reload();
                                 });
		
	}

	function copysubmit() {
		var to_list_id = document.getElementById('to_list_id').value;
                var source_list_id = document.getElementById('source_list_id').value;
                var copy_option = document.getElementById('copy_option').value;
	
                 $.post("<?=$base?>index.php/go_list/copycustomview", { action: "copycustomlist", source_list_id: source_list_id, to_list_id: to_list_id, copy_option: copy_option },
                                         
                                 function(data){
                                                alert("SUCCESS: "+copy_option+ " Custom Field");
                                                //alert(data);
                                                location.reload();
                                 }); 

	
	}

	function selectlistid() {

		var select_id = document.getElementById("hide_listid");
		var listID = select_id.options[select_id.selectedIndex].value;

		document.getElementById('copyselectlist').value= "";
		if(listID=="copycustomselect") {
						 $('#spanaddcustom').hide("fast");
						 $('#spancopycustom').show("fast");
		}else{		
			$('#countsd').load('<? echo $base; ?>index.php/go_list/oncheangeselect/'+listID);
		}
		

	}
	
	function selectlistidcopy() {
				
		var select_id2 = document.getElementById("copyselectlist");
		var listID2 = select_id2.options[select_id2.selectedIndex].value;
		document.getElementById('hide_listid').value= "";
		
				if(listID2=="createcustomselect") {
							$('#spancopycustom').hide("fast");
							$('#spanaddcustom').show("fast");
							$('#tbloverlaycopy').css("width", "112%");
							
		}
	}

        function selectlistidagain(again_listid) {
                var listID = again_listid;
                $('#countsd').load('<? echo $base; ?>index.php/go_list/oncheangeselect/'+listID);
        }	

	function postval(listID) {

   <?php
                             $permissions = $this->commonhelper->getPermissions("list",$this->session->userdata("user_group"));
                             if($permissions->list_update == "N"){
                                echo("alert('You don\'t have permission to update this record(s)');");
                                echo "return false;";
                             }
  ?>

		document.getElementById('showval').value=listID;
		document.getElementById('showvaledit').value=listID;

		var items = $('#showlistview').serialize();
			$.post("<?=$base?>index.php/go_list/editview", { items: items, action: "editlist" },
				 function(data){
				 	
                				var datas = data.split("--");
                				var data_statuses = data.split("##");
                				var i=0;
                				var j=0;
                				var count_listid = datas.length;
                				var count_status = data_statuses.length;
 						var stats = data_statuses[1];
						
						for (i=0;i<count_status;i++) {
							document.getElementById('stats').innerHTML=data_statuses[i];
						}
		
						for (i=0;i<count_listid;i++) {
                						if(datas[i]=="") {
												datas[i]=" ";
 											}
											 											
 										document.getElementById('listname_edit').value=datas[2];
 										document.getElementById('listdesc_edit').value=datas[5];
 										document.getElementById('campid_edit').value=datas[3];
 										document.getElementById('reslist_edit').value=datas[8];
 										document.getElementById('act_edit').value=datas[4];
 										document.getElementById('restime_edit').value=datas[8];
 										document.getElementById('agcscp_edit').value=datas[9];
 										document.getElementById('campcidover_edit').value=datas[10];
 										document.getElementById('drpinbovr_edit').value=datas[12];
 										document.getElementById('wbfrmadd_edit').value=datas[18];
 										document.getElementById('xfer1').value=datas[13];
 										document.getElementById('xfer2').value=datas[14];
 										document.getElementById('xfer3').value=datas[15];
 										document.getElementById('xfer4').value=datas[16];
 										document.getElementById('xfer5').value=datas[17];
										document.getElementById('cdates').innerHTML= "<i>Change date: "+datas[6]+"</i>";
										document.getElementById('lcdates').innerHTML= "<i>Last call date: "+datas[7]+"</i>";
										document.getElementById('listid_edit').innerHTML= "<b>Modify List I.D. "+datas[1]+"</b>";
										//document.getElementById('oldcampaignid').innerHTML=datas[3];
										
										//document.getElementById('showval').innerHTML= datas[1];
										
 									} 
 											
 								  $('#overlay').fadeIn('fast',function(){
 								  		$('#box').show('fast');
	                  			$('#box').animate({'top':'-70px'},500);
			             			});
	             				 
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	                  					
	              					});
								 	});		
						 });	
	}
	
	function editpost(listID) {

			document.getElementById('showval').value=listID;
			document.getElementById('showvaledit').value=listID;

			var itemsumit = $('#edit_go_listfrm').serialize();
				$.post("<?=$base?>index.php/go_list/editsubmit", { itemsumit: itemsumit, action: "editlistfinal" },
				function(data){
                			
	             				var datas = data.split(":");
                				var i=0;
                				var count_listid = datas.length;
                				
                				for (i=0;i<count_listid;i++) {

                						if(datas[i]=="") {
												datas[i]=" ";
 										}
 								}
 								
 								if(datas[0]=="SUCCESS") {
 									alert(data);
	             					location.reload();
 								}
 								
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								});	
							 		
				});	
	}
	
	function deletepost(listID) {
		
   <?php
                             $permissions = $this->commonhelper->getPermissions("list",$this->session->userdata("user_group"));
                             if($permissions->list_delete == "N"){
                                echo("alert('You don\'t have permission to delete this record(s)');");
                                echo "return false;";
                             }
  ?>
	
				var confirmmessage=confirm("Confirm to delete the List "+listID+" and all of its leads?");
				if (confirmmessage==true){
					
					
					$.post("<?=$base?>index.php/go_list/deletesubmit", { listid_delete: listID, action: "deletelist" },
					function(data){
                			
	             				var datas = data.split(":");
                				var i=0;
                				var count_listid = datas.length;

 								if(datas[0]=="SUCCESS") {
 									alert(listID+" successfully deleted");
	             					location.reload();
 								}
 								
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								});	
							 		
					});	
				} 
				
	}
	
	
	function deleteselectedlists(listID,actionmo) {

		var i=0;
		var count_listid = (listID.length);
		
		document.getElementById('loadingslist').innerHTML= "<img src=\"<? echo $base; ?>img/goloading.gif\" />";

			for (i=0;i<count_listid;i++) {
			 		$.post("<?=$base?>index.php/go_list/deletesubmit", { listid_delete: listID[i], action: actionmo },
						function(data){
							alert("List ID successfully deleted.");	
						});
			}
			
			if(count_listid == i) {
				setTimeout(alertBlah,i+"000");
			}
				
	}
	
	function alertBlah() {
		var url =window.location.href;
		var counturl = url.split("#");
		var countedurl = counturl.length;
		
		if(countedurl == 1){
			 $(location).attr('href', url);
		} else {
			$(location).attr('href', counturl[0]);
		}
	}

	var protocol = window.location.protocol;
        var host = window.location.host;
        var path_string = window.location.pathname.substr(1);
        var basepath = path_string.split("/")[0];

        //override value 0f basepath
        if(basepath === "index.php"){
            basepath = "";
        }else{
            basepath = "/"+basepath;
        }
	
	function viewpost(listID) {

		document.getElementById('showval').value=listID;
		document.getElementById('showvaledit').value=listID;

		var items = $('#showlistview').serialize();
			$.post("<?=$base?>index.php/go_list/editview", { items: items, action: "editlist" },
				 function(data){
				 	
                				var datas = data.split("--");
                				var i=0;
                				var count_listid = datas.length;
                				
                				for (i=0;i<count_listid;i++) {
                						if(datas[i]=="") {
												datas[i]=" ";
 											}
 										document.getElementById('viewlistid').innerHTML=datas[1];
										document.getElementById('viewlistdesc').innerHTML=datas[5];
										document.getElementById('viewliststatus').innerHTML=datas[4];
										document.getElementById('viewlistcalldate').innerHTML=datas[7];
                                                                                $("#download").attr("href",protocol+"//"+host+"/index.php/go_list/download/"+datas[1]);
 		                                  } 
 											
 								  $('#overlay').fadeIn('fast',function(){
 								  		$('#boxviewlist').show('fast');
	                  			$('#boxviewlist').animate({'top':'70px'},500);
			             			});
	             				 
	             				$('#boxclose').click(function(){
	              					$('#boxviewlist').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	                  					
	              					});
								 	});		
						 });	
	}
	
   function addlistoverlay() {
   <?php
                             $permissions = $this->commonhelper->getPermissions("list",$this->session->userdata("user_group"));
                             if($permissions->list_create == "N"){
                                echo("alert('You don\'t have permission to create this record(s)');");
                                echo "return false;";
                             }
  ?>	
	 $('#overlay').fadeIn('fast',function(){
	                  			$('#boxaddlist').animate({'top':'70px'},500);
		             			});
  }

  function closeme() {
                    $('#box').animate({'top':'-550px'},500,function(){
	                  			$('#overlay').fadeOut('fast');
	                  			$('#box').hide('fast');
	                  			});
  }

  function closemeadd() {
	
     $('#boxaddlist').animate({'top':'-550px'},500,function(){
	                  			$('#overlay').fadeOut('fast');
	                  						
	              					});
  }

  function closemeview() {
	
     $('#boxviewlist').animate({'top':'-550px'},500,function(){
	                  			$('#overlay').fadeOut('fast');
	                  						
	              					});
  }
  
	

	
function delDNC(dnc_num)
{
	var what = confirm('Are you sure you want to delete the selected DNC number?');
	if (what)
	{
		var submit_msg = '';
		$.post('<? echo $base; ?>index.php/go_dnc_ce/go_delete_dnc_number/'+dnc_num, function(data)
		{
			var pnum = dnc_num.split('-');
			$("#dnc_placeholder").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
			$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/1/');

			if (data)
			{
				submit_msg = 'deleted';
			} else {
				submit_msg = 'not deleted';
			}
			alert('Phone number '+pnum[0]+' '+submit_msg+' from the DNC list.');
		});
	}
}
</script>

<script>
$(document).ready(function() 
    { 
        //$('#listtableresult').tablePagination();
        //$('#cumstomtable').tablePagination();
		
		//DNC START
		$('#search_dnc').val('');
		//$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		//$("#dnc_placeholder").load("<?php echo $base;?>index.php/go_dnc_ce/go_search_dnc/1/");
		
		$('.tab').click(function()
		{

			if ($(this).attr('id') == 'atab5')
			{
				$('#activator').hide();
				$('#submit_dnc').show();
                                $('#newcustomfield').hide();
				$('#singrp').hide();
				$('#searchDNC').show();
			} else {
				$('#activator').show();
                                $('#newcustomfield').show();
				$('#submit_dnc').hide();
				$('#singrp').show();
				$('#searchDNC').hide();
			}
			
			if ($(this).attr('id') == 'atab3')
			{
			        $('#singrp').hide();
                        }

                        if ($(this).attr('id') == 'atab2')
                        {
                               $('#activator').hide();
                               $('#submit_dnc').hide();
                               $('#newcustomfield').show();
			       $('#typeofsearch').val('custom');
                        } else {
                               $('#newcustomfield').hide();
			       $('#typeofsearch').val('lists');
			}


		});
		
		$('li.go_dnc_submenu').hover(function()
		{
			$(this).css('background-color','#ccc');
		},function()
		{
			$(this).css('background-color','#fff');
		});
	
		$('li.go_dnc_submenu').click(function () {
			var selectedNumber = [];
			$('input:checkbox[id="delDNC[]"]:checked').each(function()
			{
				selectedNumber.push($(this).val());
			});
	
			$('#go_dnc_menu').slideUp('fast');
			$('#go_dnc_menu').hide();
			toggleAction = $('#go_dnc_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedNumber.length<1)
			{
				alert('Please select a Phone Number.');
			}
			else
			{
				var s = '';
				if (selectedNumber.length>1)
					s = 's';
	
				if (action == 'delete')
				{
					var what = confirm('Are you sure you want to delete the selected Phone Number'+s+'?');
				if (what)
				{
					$.post('<? echo $base; ?>index.php/go_dnc_ce/go_delete_mass_dnc_number/'+selectedNumber+'/', function(data)
					{
						$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
						$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/');

						if (data)
						{
							submit_msg = 'deleted';
						} else {
							submit_msg = 'not deleted';
						}
						alert('Selected phone number(s) '+submit_msg+' from the DNC list.');
					});
				}
				}
	// 			else
	// 			{
	// 				$.post('<? echo $base; ?>index.php/go_dnc_ce/go_delete_dnc_number/'+selectedNumber+'/');
	// 			}
			}
		});
	
		// Pagination
		$('#DNCTable').tablePagination({rowsPerPage: 15, optionsForRows: [15,25,50,100,"ALL"]});
	
		// Table Sorter
		$("#DNCTable").tablesorter({sortList:[[1,0],[0,0]], headers: { 2: { sorter: false}, 3: {sorter: false} }, widgets: ['zebra']});
	
	$("#showAllDNCLists").click(function()
	{
		$(this).hide();
		$("#search_dnc").val('');
                $("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
                $('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/1/');
	});
	
	$("#submit_search_dnc").click(function()
	{
		var number = $("#search_dnc").val();
		number = number.replace(/\s/g,"%20");
		if (number.length > 2) {
		        $('#showAllDNCLists').show();
			$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/1/'+number);
		} else {
			alert("Please enter at least 3 characters to search.");
		}
	});
	
	$('#search_dnc').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			if (event.keyCode == 32 || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			//if (!event.shiftKey && event.keyCode == 173)
			//	return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 31 && event.which < 45) || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
		if (event.which == 13 && event.type == "keydown") {
			var number = $("#search_dnc").val();
			number = number.replace(/\s/g,"%20");
			if (number.length > 2) {
			        $('#showAllDNCLists').show();
				$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			        $('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/1/'+number);
			} else {
				alert("Please enter at least 3 characters to search.");
			}
		}
	});

		$('#submit_dnc').click(function()
		{
			$('#overlayDNC').fadeIn('fast');
			$('#boxDNC').css({'width': '600px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
			$('#boxDNC').animate({
				top: "-70px"
			}, 500);
	
			$("#overlayContentDNC").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#overlayContentDNC').fadeOut("slow").load('<? echo $base; ?>index.php/go_dnc_ce/go_submit_dnc/').fadeIn("slow");
		});

		$('#closeboxDNC').click(function()
		{
			$('#boxDNC').animate({'top':'-2550px'},500);
			$('#overlayDNC').fadeOut('slow');
			$('#campaign_idDNC').val('INTERNAL');
			$('#phone_numbers').val('');
		});
		//DNC END

        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');

                $('#uploadform').ajaxForm({
                beforeSend: function() {
                        status.empty();
                        var percentVal = '0%';
                        bar.width(percentVal);
                        percent.html(percentVal);
                },
                uploadProgress: function(event, position, total, percentComplete) {
                        var percentVal = percentComplete + '%';
                        bar.width(percentVal);
                        percent.html(percentVal);
                },
                complete: function(xhr) {
                        document.forms["uploadform"].submit();

                }
                
           
        });

        $('#selectAll').click(function()
        {
                if ($(this).is(':checked'))
                {
                        $('input:checkbox[id="delCampaign[]"]').each(function()
                        {
                                $(this).attr('checked',true);
                        });
                }
                else
                {
                        $('input:checkbox[id="delCampaign[]"]').each(function()
                        {
                                $(this).removeAttr('checked');
                        });
                }
        });
        
                $(document).mouseup(function (e)
        {
                var content = $('#go_action_menu, #go_status_menu, #go_camp_status_menu');
                if (content.has(e.target).length === 0 && (e.target.id != 'selectAction' && e.target.id != 'selectStatusAction'))
                {
                        content.slideUp('fast');
                        content.hide();
                        toggleAction = $('#go_action_menu').css('display');
                        toggleStatus = $('#go_status_menu').css('display');
                        toggleCampStatus = $('#go_camp_status_menu').css('display');
                }
        });
        
        
		  var toggleAction = $('#go_action_menu').css('display');
        $('#selectAction').click(function()
        {
                if (toggleAction == 'none')
                {
                        var position = $(this).offset();
                        $('#go_action_menu').css('left',position.left-110);
                        $('#go_action_menu').css('top',position.top-120);
                        $('#go_action_menu').slideDown('fast');
                        toggleAction = $('#go_action_menu').css('display');
                }
                else
                {
                        $('#go_action_menu').slideUp('fast');
                        $('#go_action_menu').hide();
                        toggleAction = $('#go_action_menu').css('display');
                }
        });

		  $('li.go_action_submenu,li.go_status_submenu,li.go_camp_status_submenu').hover(function()
        {
                $(this).css('background-color','#ccc');
        },function()
        {
                $(this).css('background-color','#fff');
        });
        
                   $('#selectAll').click(function()
        {
                if ($(this).is(':checked'))
                {
                        $('input:checkbox[id="delselectlist[]"]').each(function()
                        {
                                $(this).attr('checked',true);
                        });
                }
                else
                {
                        $('input:checkbox[id="delselectlist[]"]').each(function()
                        {
                                $(this).removeAttr('checked');
                        });
                }
        });
        
        
               $('li.go_action_submenu').click(function () {
                var selectedlists = [];
                $('input:checkbox[id="delselectlist[]"]:checked').each(function()
                {
                        selectedlists.push($(this).val());
                });
                
                $('#go_action_menu').slideUp('fast');
                $('#go_action_menu').hide();
                toggleAction = $('#go_action_menu').css('display');

                var action = $(this).attr('id');
                if (selectedlists.length<1)
                {
			alert("Please select a List.");
                        /* new $.msgbox({
                                type: 'alert',
                                showClose: false,
                                content: 'Please select a List.'
                        }).show(); */
                }
                else
                {
                        var s = '';
                        if (selectedlists.length>1)
                                s = 's';
                        
                        
                        if (action == 'delete')
                        {
<?php
                             $permissions = $this->commonhelper->getPermissions("list",$this->session->userdata("user_group"));
                             if($permissions->list_delete == "N"){
                                echo("alert('You don\'t have permission to delete this record(s)');");
                                echo "return false;";
                             }
                ?>

                                /* new $.msgbox({
                                        type: 'confirm',
                                        showClose: false,
                                        content: 'Are you sure you want to delete the selected List'+s+'?',
                                        onClose: function()
                                        {
                                                if (this.getValue())
                                                {
                                                       deleteselectedlists(selectedlists,"deletelist");
                                                }
                                        }
                                }).show(); */
				var r=confirm("Are you sure you want to delete the selected List"+s);
				if (r==true) {
					deleteselectedlists(selectedlists,"deletelist");
				} else  {
					
				}
                        }
                        else if(action == 'deactivate')
                        {
                        	 deleteselectedlists(selectedlists,"deactivatelist");
                        }
                        else if(action == 'activate')
                        {
                        	 deleteselectedlists(selectedlists,"activatelist");
                        }
                }
        });

	$('#submit_search_list').click(function()
	{
	        var search = $('#search_list').val();
	        if (search.length > 2) {
		        $("#go_search_list").submit();
		} else {
		        alert("Please enter at least 3 characters to search.");
		}
	});
	
	$('#search_list').bind("keydown keypress", function(event)
	{
		if (event.type == "keydown") {
			// For normal key press
			if (event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 48) || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.which == 13 && event.type == "keydown") {
			var search = $("#search_list").val();
			if (search.length > 2) {
		                $("#go_search_list").submit();
			} else {
				alert("Please enter at least 3 characters to search.");
			}
		}
	});
	
	$("#go_search_list").submit(function(e){
                var search = $("#search_list").val();
                if (search.length < 3) {
			return false;
                }
        });
	
	$('#showAllLists').click(function()
        {
                window.location.href = '<?=base_url() ?>go_list';
        });
	
	if ('<?=$search ?>'.length > 0) {
                $('#showAllLists').show();
	} else {
                $('#showAllLists').hide();
	}
        
    } 
); 
</script>
<script type="text/javascript" >

function checkmes(){
	var leadfile = document.getElementById('leadfile').value;	
	var leadfile2 = document.getElementById('leadfile').value;	
	
	var lead_file = $('#leadfile').val();
                var valid_extensions = /(\.xls|\.xlsx|\.csv|\.ods|\.sxc)$/i;
                
                if (lead_file.length < 1)
                {
                        alert('Please include a lead file.');
                        return false;
                }
                else
                {
                        if (valid_extensions.test(lead_file))
                        {
                                $('.progressBar').show();
                                $('#uploadleads').submit();
                                $('#box').css('position','absolute');
                        }
                        else
                        {
                                alert('Uploaded file is invalid: '+lead_file+'<br /><br />File must be in Excel format (xls, xlsx) or in Comma Separated Values (csv).');
                                return false;
                        }
                }

	
}

function uploadimg() {

document.getElementById('loadings').innerHTML= "<img src=\"<? echo $base; ?>img/goloading.gif\" />";
	
}

</script>
<!-- end Javascript section -->

		<!-- CSS section -->
<link href="<?=base_url()?>css/go_common_ce.css" rel="stylesheet" type="text/css">
<style type="text/css">
			
			a.back{
	            width:256px;
	            height:73px;
	            /*position:fixed;*/
	            bottom:15px;
	            right:15px;
/*	            background:#fff url(codrops_back.png) no-repeat top left;*/
	            z-index:1;
	            cursor:pointer;
	        }
	        a.activator{
	            width:153px;
	            height:150px;
	          /*  position:absolute;
	            top:0px;
	            left:0px;
/*	            background:#fff url(clickme.png) no-repeat top left;*/
	            z-index:1;
	            cursor:pointer;
			font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;            
	        }
	        /* Style for overlay and box */
	        .overlay{
	            background:transparent url(../../img/images/go_list/overlay.png) repeat top left;
	            position:fixed;
	            top:0px;
	            bottom:0px;
	            left:0px;
	            right:0px;
	            z-index:100;
	        }

		.overlayadd{
                    background:transparent url(../../../img/images/go_list/overlay.png) repeat top left;
                    position:fixed;
                    top:0px; 
                    bottom:0px;
                    left:0px; 
                    right:0px;
                    z-index:100;
                }   
	        .box{
	            /*position:fixed;*/
	            position:absolute;
	            top:-650px;
/*	            top:-200px;*/
	            left:20%;
	            right:30%;
	            background-color: white;
	            color:#7F7F7F;
	            padding:20px;
				display: none;
	          /*  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;*/
	            z-index:101;
	            width: 50%;
	            
	        }

                .boxcopylist{
                     position:fixed;
                    top:-550px;
/*                  top:-200px;*/
                    left:20%;
                    right:30%;
                    background-color: white;
                    color:#7F7F7F;
                    padding: 20px 20px 5px 20px;
                  /*  border:2px solid #ccc;
                    -moz-border-radius: 20px;
                    -webkit-border-radius:20px;
                    -khtml-border-radius:20px;
                    -moz-box-shadow: 0 1px 5px #333;
                    -webkit-box-shadow: 0 1px 5px #333;*/

                    -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
                    /*background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;*/
                    z-index:101;
                    width: 50%;
                        }

	        .boxaddlist{
	             position:absolute;
	            top:-3000px;
/*	            top:-200px;*/
	            left:18%;
	            right:25%;
	            background-color: white;
	            color:#7F7F7F;
	            padding: 20px 20px 5px 20px;
	          /*  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;*/
	            z-index:101;
	            width: 55%;
	        	}
	        	
	        	.boxviewlist{
	             position:absolute;
	            top:-550px;
/*	            top:-200px;*/
	            left:30%;
	            right:30%;
	            background-color: white;
	            color:#7F7F7F;
	            padding:20px;
	          	/*  
	          	border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*
	            background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;			*/
	            z-index:101;
	            width: 40%;
	        	}
	        	
	        .box h1{
	            border-bottom: 1px dashed #7F7F7F;
	            margin:-20px -20px 0px -20px;
	            padding:50px;
	            background-color:#FFEFEF;
	            color:#EF7777;
	            -moz-border-radius:20px 20px 0px 0px;
	            -webkit-border-top-left-radius: 20px;
	            -webkit-border-top-right-radius: 20px;
	            -khtml-border-top-left-radius: 20px;
	            -khtml-border-top-right-radius: 20px;
	        }

                .boxview{
                    position: absolute;
                    top:-550px;
/*                  top:-200px;*/
                    left:10%;
                    right:10%;
/*                  background-color:#fff;
                    color:#7F7F7F;*/
                    background-color: white;
                    color:#7F7F7F;
                    padding:20px;
/*                  border:2px solid #ccc;
                    -moz-border-radius: 20px;
                    -webkit-border-radius:20px;
                    -khtml-border-radius:20px;
                    -moz-box-shadow: 0 1px 5px #333;
                    -webkit-box-shadow: 0 1px 5px #333;*/
                     -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
                    z-index:101;
                }

	        
	        a.boxclose{
	            float:right;
	            width:26px;
	            height:26px;
	            background:transparent url(<? echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	            margin-top:-30px;
	            margin-right:-30px;
	            cursor:pointer;
	        }
	        
			.nowrap { 
			   background: white;
 			   font-size: 12px;
			}
			table {
			    /*border-top: 1px dashed rgb(208,208,208);*/
			}
			/*td {
			    font-size : 10px;
			}*/

			.tabnoborder {
				border: none;
			}
			
			.title-header {
				color: #333;
				font-size: 16px;	
			}
			
			.modify-value {
				font-weight: bold;
				color: #7f7f7f;
			}
			.lblback {
				background:#E0F8E0;
			}
			
			.tableedit {
				border-top: 0px double; rgb(208,208,208);
			}
			

			.tablenodouble {
				border-top: 0px double; rgb(208,208,208);
			}
			
			td {
			font-family: Verdana,Arial,Helvetica,sans-serif; 
			font-size: 13px; 
			font-stretch: normal;		
			}
			
			.thheader {
        		background-repeat: no-repeat;
        		background-position: center right;
        		cursor: pointer;
			font-family: Verdana,Arial,Helvetica,sans-serif; 
			font-size: 13px; 
			font-stretch: normal;		
			
			}     
			
			.progress { position:relative; width:200px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
			.bar { background-color: #B4F5B4; width:0%; height:15px; border-radius: 3px; }
			.percent { position:absolute; display:inline-block; top:2px; left:45%; font-size:10px; }
  


			
			.tr1 td{ background:#E0F8E0; color:#000; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal; border-top: 1px dashed rgb(208,208,208); }
			.tr2 td{ background:#EFFBEF; color:#000; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;  border-top: 1px dashed rgb(208,208,208); }
			.tredit td{ background:#EFFBEF; color:#000; font-family: font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal; border-bottom: 1px dashed rgb(208,208,208); }   
			
/*			A:link {text-decoration: none; color: black;}
			A:visited {text-decoration: none; color: black;}
			A:active {text-decoration: none; color: black;}
			A:hover {text-decoration: underline overline; }
*/		
			A#searchcallhistory:link {text-decoration: none; color: black;}
			A#searchcallhistory:visited {text-decoration: none; color: black;}
			A#searchcallhistory:active {text-decoration: none; color: black;}
			A#searchcallhistory:hover {text-decoration: none; font-weight:bold;}
			
			A#listidlink:link {text-decoration: none; color: black;}
			A#listidlink:visited {text-decoration: none; color: black;}
			A#listidlink:active {text-decoration: none; color: black;}
			A#listidlink:hover {text-decoration: none; color: red}

			A#activator:link {text-decoration: none; color: #555555;}
			A#activator:visited {text-decoration: none; color: #555555;}
			A#activator:active {text-decoration: none; color: #555555;}
			A#activator:hover {text-decoration: none; color: #555555}

			A#newcustomfield:link {text-decoration: none; color: #555555;}
			A#newcustomfield:visited {text-decoration: none; color: #555555;}
			A#newcustomfield:active {text-decoration: none; color: #555555;}
			A#newcustomfield:hover {text-decoration: none; color: #555555}
			
			A#submit_dnc:link {text-decoration: none; color: #555555;}
			A#submit_dnc:visited {text-decoration: none; color: #555555;}
			A#submit_dnc:active {text-decoration: none; color: #555555;}
			A#submit_dnc:hover {text-decoration: none; color: #555555}
						
			
/*			.go_action_submenu, .go_status_submenu, .go_camp_status_submenu {
	        padding: 3px 10px 3px 5px;
	        margin: 0px;
			}

	
			#selectAction, #selectStatusAction, #selectCampStatusAction {
	        -webkit-touch-callout: none;
	        -webkit-user-select: none;
	        -khtml-user-select: none;
	        -moz-user-select: none;
	        -ms-user-select: none;
	        user-select: none;
        
			}*/
			
			.go_action_menu{
        z-index:999;
        position:absolute;
        top:188px;
        border:#CCC 1px solid;
        background-color:#FFF;
        display:none;
        cursor:pointer;
}

#go_action_menu ul, #go_status_menu ul, #go_camp_status_menu ul{
        list-style-type:none;
        padding: 1px;
        margin: 0px;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
}

#selectAction, #selectStatusAction, #selectCampStatusAction {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
}

.go_action_submenu, .go_status_submenu, .go_camp_status_submenu{
        padding: 3px 10px 3px 5px;
        margin: 0px;
}


			
			.button:hover{
	font-weight:bold;
}

img.desaturate{
    filter: grayscale(100%); /* Current draft standard */
    -webkit-filter: grayscale(100%); /* New WebKit */
    -moz-filter: grayscale(100%);
    -ms-filter: grayscale(100%);
    -o-filter: grayscale(100%); /* Not yet supported in Gecko, Opera or IE */
    filter: url(<?php echo $base;?>img/resources.svg#desaturate); /* Gecko */
    filter: gray; /* IE */
    -webkit-filter: grayscale(1); /* Old WebKit */
}

#overlayDNC{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:100;
}

#boxDNC{
	position:absolute;
	top:-2550px;
	left:14%;
	right:14%;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:101;
}

#closeboxDNC{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}

.go_dnc_menu{
	z-index:999;
	position:absolute;
	top:188px;
	border:#CCC 1px solid;
	background-color:#FFF;
	display:none;
	cursor:pointer;
}

#go_dnc_menu ul{
	list-style-type:none;
	padding: 1px;
	margin: 0px;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.go_dnc_submenu{
	padding: 3px 10px 3px 5px;
	margin: 0px;
}

table.tablesorter .even {
	background-color: #EFFBEF;
}
table.tablesorter .odd {
	background-color: #E0F8E0;
}

.listdownload{cursor:pointer;text-align: right;}
.listdownload a{color:#7A9E22;}
.listdownload a:hover {font-weight:bold;}

.listinfo{
   width:100%;
}

.listinfolabel{width:30%;}

.advancedFields{
	display:none;
}

#advancedFieldLink{
	font-size:10px;
	color:#7A9E22;
	font-weight:bold;
	cursor:pointer;
}

#showAllLists, #showAllDNCLists {
	color: #F00;
	font-size: 10px;
	cursor: pointer;
}
		</style>
		<!-- end CSS section -->
<?php

$countthis = count($lists);
if($countthis > 0){
echo "<body onload='genListID()'>";
} else {	
?>	
<body onload='addlistoverlay(); genListID()'>
<?php
}
?>

<!-- begin body -->
<div id='outbody' class="wrap">
    <div id="icon-list" class="icon32"></div>
    <h2 style="font-family: Verdana,Arial,Helvetica,sans-serif; ">Lists</h2>
    <!-- search -->
    <div id="singrp" align="right" style="position: absolute; float: left; width: 94%; margin-top: -35px; display: block;">
	<form  method="POST" id="go_search_list" name="go_search_list">
                <span id="showAllLists" style="display: none">[Clear Search]</span>
		<input type="hidden" id="action" name="action" value="action_search_list">
		<input type="hidden" id="typeofsearch" name="typeofsearch" value="lists">
		<input type="text" value="<?=$search ?>" name="search_list" id="search_list" size="20" maxlength="100" placeholder="Search Lists">
		&nbsp;<img src="<?=base_url()."img/spotlight-black.png"; ?>" id="submit_search_list" style="cursor: pointer;" />
	</form>
    </div>
    <div id="searchDNC" align="right" style="position: absolute; float: left; width: 94%; margin-top: -35px;display:none;"><span id="showAllDNCLists" style="display: none">[Clear Search]</span> <input type="text" id="search_dnc" placeholder="Search DNC Numbers" size="20" maxlength="100" /> <img src="<?=base_url()."img/spotlight-black.png"; ?>" id="submit_search_dnc" style="cursor: pointer;" /></div>
    <!-- search -->
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">

    	    <!-- start box -->
            <div class="postbox-container" style="width: 99%; min-width: 1200px;">
                <div class="meta-box-sortables ui-sortables">
                    <!-- List holder-->
                    <div class="postbox" >
                        <div>
                            
   <span><a id="activator" class="rightdiv toolTip" style="text-decoration: none; cursor:pointer; font-family: Verdana,Arial,Helvetica,sans-serif;" onClick="addlistoverlay();" title="Create New List"><b>Create New List</b>  </a></span>

			<!--<span><a id="copycustomfield"  class="rightdiv toolTip" style="text-decoration: none; cursor:pointer;display: none; font-family: Verdana,Arial,Helvetica,sans-serif;" onClick="copyview();" title="Copy Custom Field" style="margin-left: 0px; font-family: Verdana,Arial,Helvetica,sans-serif;">Copy Custom Field</a></span> <span><a id="pipelink" class="rightdiv toolTip" style="text-decoration: none; cursor:pointer;display: none; ">|</a></span>-->
			
			<span><a id="newcustomfield"  class="rightdiv toolTip" style="text-decoration: none; cursor:pointer;display: none;font-family: Verdana,Arial,Helvetica,sans-serif; " onClick="viewadd();" title="Create New Field" style="margin-left: 0px;">Create Custom Field</a></span>
			<span>				
                        <a id="submit_dnc" class="rightdiv toolTip" style="text-decoration: none; cursor:pointer;display: none;font-family: Verdana,Arial,Helvetica,sans-serif;" title="Add/Delete DNC Numbers"><b>Add/Delete DNC Numbers</b>  </a>
			</span>
						
                        </div>
                        <h3 class="hndle" style="height:13px" onclick="return false;">
                                   <!-- <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;">List Listings</span> -->
	                    	</h3>
                        <div class="inside inside-tab">

<div id="tabs" class="tab-container" style="border: none;">
<ul style="background: transparent; border: none;">
		<li><a href="#tabs-1" id="atab1" title="Show Lists" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;">Show Lists</a></li>
		<li><a href="#tabs-2" id="atab2" title="Custom Fields" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;">Custom Fields</a></li>
		<li><a href="#tabs-3" id="atab3" title="Load Leads" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;">Load Leads</a></li>
		<li><a href="#tabs-5" id="atab5" title="DNC Numbers" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;">DNC Numbers</a></li>
		<!--<li><a href="#tabs-6" id="atab6" title="Custom Fields Settings" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;">Custom Fields Settings</a></li>-->
	</ul>
	

                <div class="overlay" id="overlaycopy" style="display:none;"></div>
                                <div class="boxcopylist" id="boxcopy">
                                        <a class="boxclose" id="boxclosecopy"></a>
                                	<div id="small_step_number" style="float:right; margin-top: -5px;">
                                        	<img src="<?=$base?>img/step1-nav-small.png">
                                	</div>
                                	<div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
                                        	<font color="#333" style="font-size:16px;"><b>Custom Field Wizard » Copy Custom Field</b></font>
                                	</div>

<!--                                        <form method="POST" name="formcopyfields" id="formcopyfields">
        				<input type=hidden name=action value=COPY_FIELDS_SUBMIT>
				
                                        <br>
                                                <table class="tableedit" width="100%" id="tbloverlaycopy">
                                                <tr>
                                                <td valign="top" style="width:20%">
                                                                        <div id="step_number" style="padding:0px 10px 0px 30px;">
                                                                <img src="<?=$base?>img/step1-trans.png">
                                                                </div>
                                                </td>
                                                <td style="padding-left:50px;" valign="top" colspan="2">
        						<table class="tablenodouble" width="100%">
								<tr>
									<td>
        									<label class="modify-value">Copy Fields to Another List</label>
        								</td>
									<td>
        								<select name="source_list_id" id="source_list_id">
									<?php
										foreach($lists as $listsInfo){
											echo "<option value='$listsInfo->list_id'>".$listsInfo->list_id." - ".$listsInfo->list_name."</option>";
										}
									?>
        								</select>
									</td>
								</tr>
								<tr>
									<td>
        									<label class="modify-value">List ID to Copy Fields From:</label>
									</td>
									<td>
        								<select name="to_list_id" id="to_list_id">
									<?php
										foreach($lists as $listsInfo){
											echo "<option value='$listsInfo->list_id'>".$listsInfo->list_id." - ".$listsInfo->list_name."</option>";
										}
									?>
        								</select>
									</td>
								</tr>
								<tr>
									<td>
										<label class="modify-value">Copy Option:</label>
									</td>
									<td>
									<select name="copy_option" id="copy_option">
        								<option selected>APPEND</option>
        								<option>UPDATE</option>
        								<option>REPLACE</option>
        								</select>
									</td>
								</tr>
							</table>
						</td>
						</tr>
                                        	<tr>
                                                <td align="right" colspan="9">
                                                <div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
                                                    <a id="searchcallhistory" style="cursor: pointer;" onclick="copysubmit();"><font color="#7A9E22">Submit</font></a>
                                                </div>

                                                </td>
                                        	</tr>
                                        	</table>
					</form>-->

                                </div>

                <div class="overlay" id="overlayview" style="display:none;"></div>
                                <div class="boxview" id="boxview">
                                        <center>
                                                        <a class="boxclose" id="boxcloseview"></a>
                                                        <span id="viewme"></span>
                                        </center>
                                </div>
	
		<div class="overlay" id="overlayadd" style="display:none;"></div>
                                <div class="boxaddlist" id="boxaddcustom">

                                <a class="boxclose" id="boxclose"></a>
                                <div id="small_step_number" style="float:right; margin-top: -5px;">
                                        <img src="<?=$base?>img/step1-nav-small.png">
                                </div>
                                <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
                                        <font color="#333" style="font-size:16px;"><b>Custom Field Wizard » Create / Copy Custom Field</b></font>

                                </div>

<!-- copy custom -->
									<span id="spancopycustom" style="display: none;">

                                 <form method="POST" name="formcopyfields" id="formcopyfields">
        																									<input type=hidden name=action value=COPY_FIELDS_SUBMIT>
				
                                        <br>
                                                <table class="tableedit" width="100%" id="tbloverlaycopy">
                                                <tr>
                                                <td valign="top" style="width:20%">
                                                                        <div id="step_number" style="padding:0px 10px 0px 30px;">
                                                                <img src="<?=$base?>img/step1-trans.png">
                                                                </div>
                                                </td>
                                                <td style="padding-left:50px;" valign="top" colspan="2">
        																																								
        																																										<table class="tablenodouble" width="100%">
        						   																																			<tr><td> <label class="modify-value">Process: </label></td>
							    																																													<td> 
								                                                     <select name="copyselectlist" id="copyselectlist" Onchange="selectlistidcopy();">
									                                                      <option value="" selected>Copy Custom Field</option>
									                                                      <option value="createcustomselect">Create Custom Field</option>
																																																													</select> 		
							    																																													</td>
							    																																									</tr>
																																																				<tr>
																																																								<td><label class="modify-value">List ID to Copy Fields From:</label></td>
																																																								<td>
        																																																				<select name="source_list_id" id="source_list_id">
																																																												<?php
																																																												foreach($dropactivecustom as $droplistsInfo){
										                                                  	echo "<option value='$droplistsInfo->listids'>".$droplistsInfo->listids." - ".$droplistsInfo->list_name."</option>";
																																																												}
																																																												?>
        																																																				</select>
																																																								</td>
																																																				</tr>
																																																				<tr>
																																																								<td>
        																																																				<label class="modify-value">Copy Fields to Another List:</label>
																																																								</td>
																																																							<td>
        								<select name="to_list_id" id="to_list_id">
									<?php
										foreach($listIDs as $listsInfo){
											echo "<option value='$listsInfo->list_id'>".$listsInfo->list_id." - ".$listsInfo->list_name."</option>";
										}
									?>
        								</select>
									</td>
								</tr>
								<tr>
									<td>
										<label class="modify-value">Copy Option:</label>
									</td>
									<td>
									<select name="copy_option" id="copy_option">
        								<option selected>APPEND</option>
        								<option>UPDATE</option>
        								<option>REPLACE</option>
        								</select>
									</td>
								</tr>
							</table>
						</td>
						</tr>
                                        	<tr>
                                                <td align="right" colspan="20">
                                                <div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
                                                    <a id="searchcallhistory" style="cursor: pointer;" onclick="copysubmit();"><font color="#7A9E22">Submit</font></a>
                                                </div>

                                                </td>
                                        	</tr>
                                        	</table>
					</form>
					</span>


<!-- end copy custom -->





     
     
     
                             
                                <span id="spanaddcustom" style="display: block;">

                                                
						<br>
                                        	<table class="tableedit" width="100%" id="tblboxaddcustom">
                                        	<tr>
                                                <td valign="top" style="width:20%">
                                                                        <div id="step_number" style="padding:0px 10px 0px 30px;">
                                                                <img src="<?=$base?>img/step1-trans.png">
                                                                </div>
                                                </td>
                                                <td style="padding-left:50px;" valign="top" colspan="2">

                                                <form method="POST" name="formfields" id="formfields">
                                                <input type="hidden" name="fakeselectlistidname" id="fakename">
                                                <input type="hidden" name="field_id" id="field_id">
                                                <table class="tablenodouble" width="100%">
                                                        <tr><td> <label class="modify-value">List I.D.:</label></td>
							    <td> 
								<select name="hide_listid" id="hide_listid" Onchange="selectlistid();">
								 <option value="" selected></option>
									<option value="copycustomselect">Copy Custom Field</option>
									<?php
									foreach($listIDs as $listsInfo){
										echo "<option value='$listsInfo->list_id'>".$listsInfo->list_id." - ".$listsInfo->list_name." </option>";				
									}
									?>
								</select> 		
							    </td></tr>
                                                        <tr><td> <label class="modify-value">Labels:</label></td><td> <input type="text" name="field_label" id="field_label"> </td></tr>
                                                        <tr><td align="left">
                                                                        <label class="modify-value">Rank:</label>
														</td><td align="left">
									<span id="countsd">
										<select name='field_rank' id='field_rank'>
											<option value="1">1</option>
										</select> 
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                								<label class='modify-value'>Order:</label>
                								<select name='field_order' id='field_order'>
											<option value="1">1</option>
										</select>
									</span>	
                                                        </td></tr>
                                                        <tr><td> <label class="modify-value">Name:</label></td><td> <input type="text" name="field_name" id="field_name"></td></tr>
                                                        <tr><td> <label class="modify-value">Position:</label> </td><td><select name="name_position" id="name_position">
                                                                                                                <option value="LEFT">LEFT</option>
                                                                                                                <option value="TOP">TOP</option>
                                                                                                        </select>
                                                        </td></tr>
                                                        <tr><td> <label class="modify-value">Description:</label> </td><td><input type="text" name="field_description" id="field_description"> </td></tr>
                                                        <tr><td> <label class="modify-value">Type</label></td><td> <select name="field_type" id="field_type">
                                                                                <option value="TEXT">TEXT</option>
                                                                                <option value="AREA">AREA</option>
                                                                                <option value="SELECT">SELECT</option>
                                                                                <option value="MULTI">MULTI</option>
                                                                                <option value="RADIO">RADIO</option>
                                                                                <option value="CHECKBOX">CHECKBOX</option>
                                                                                <option value="DATE">DATE</option>
                                                                                <option value="TIME">TIME</option>
                                                                                <option value="DISPLAY">DISPLAY</option>
                                                                                <option value="SCRIPT">SCRIPT</option>
                                                                                </select>
                                                        </td></tr>
                                                        <tr><td> <label class="modify-value">Options:</label></td><td> <textarea name="field_options" id="field_options" style="resize: none;" ROWS="2" COLS="40"></textarea>
                                                        </td></tr>
                                                        <tr><td> <label class="modify-value">Option Position:</label> </td><td><select name="multi_position" id="multi_position">
                                                                                <option value="HORIZONTAL">HORIZONTAL</option>
                                                                                <option value="VERTICAL">VERTICAL</option>
                                                                                </select>
                                                        </td></tr>
                                                        <tr><td><label class="modify-value">Field Size:</label></td><td> <input type="text" name="field_size" id="field_size"> </td></tr>
                                                        <tr><td><label class="modify-value">Field Max:</label></td><td> <input type="text" name="field_max" id="field_max"> </td></tr>
                                                        <tr><td><label class="modify-value">Field Default:</label></td><td> <input type="text" name="field_default" id="field_default"> </td></tr>
                                                        <tr><td><label class="modify-value">Field Required:</label></td><td> <select name="field_required" id="field_required">
                                                                                        <option value="Y">YES</option>
                                                                                        <option value="N">NO</option>
                                                                                </select>
                                                        </td></tr>
                                                        <!--<tr><td align="center" colspan="2">

                                                                <span id="btnsub"></span>
                                                                <input type="button" name="btnclose" id="btnclose" class="btnclose" value="close">
                                                        </td></tr>-->
                                        </table>
                                        </tr>
                                        <tr>
                                                <td align="right" colspan="9">
                                                <div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
                                                    <!--<a id="searchcallhistory" style="cursor: pointer;" onclick="document['formfields'].submit()"><font color="#7A9E22">Submit</font></a>-->
                                                    <a id="searchcallhistory" style="cursor: pointer;" onclick="addsubmit();"><font color="#7A9E22">Submit</font></a>
                                                </div>

                                                </td>
                                        </tr>



                                        </table>

</form></span>
                                </div>	

<div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="boxaddlist" id="boxaddlist">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closemeadd();"></a>
				<!-- start add -->
				<div id="small_step_number" style="float:right; margin-top: -5px;">
					<img src="<?=$base?>img/step1-nav-small.png">
				</div>
				<div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
					<font color="#333" style="font-size:16px;"><b>List Wizard » Create New List</b></font>
					
				</div>
				
				<div id="addlist" style="display: block;">
					<form  method="POST" id="go_listfrm" name="go_listfrm">
				   	<input type="hidden" id="selectval" name="selectval" value="">
				   	<input type="hidden" id="addSUBMIT" name="addSUBMIT" value="addSUBMIT">
				   	
				   	
				  <!-- <div align="left" class="title-header">Create new list</div>-->
					<br>
					<table class="tableedit" width="100%">
					<tr>
						<td valign="top" style="width:20%">
									<div id="step_number" style="padding:0px 10px 0px 30px;">
								<img src="<?=$base?>img/step1-trans.png">
								</div>
						</td>
						<td style="padding-left:50px;" valign="top" colspan="2">
							<table width="100%">
								
								<tr style="display:none">
									<td align="right"><label class="modify-value">Auto Generate:&nbsp;&nbsp;&nbsp;</label></td>
									<td><input type="checkbox" id="auto_gen" name="auto_gen" onclick="showRow();" checked="checked"></td>
								</tr>
	                  					<tr>
									<td align="right"><label class="modify-value">List ID:&nbsp;&nbsp;&nbsp;</label> </td>
									<td><input type="text" name="list_id" id="list_id" size="12" maxlength="7">
									<label id="autogenlabel"><font size="1" color="red">(numeric only)</font></label> </td>
								</tr>
								<tr>
									<td align="right"><label class="modify-value">List Name:&nbsp;&nbsp;&nbsp;</label> </td>
									<td><input type="text" name="list_name" id="list_name" size="30" maxlength="22">
									<font color="red" size="1" style="display: none;">(alphanumeric only)</font></td>
								</tr>
								<tr>
									<td align="right"><label class="modify-value">List Description:&nbsp;&nbsp;&nbsp;</label> </td>
									<td><input type="text" name="list_description" id="list_description" size="30" maxlength="255">
									<font color="red" size="1" style="display: none;">(alphanumeric only)</font></td>
								</tr>
								<tr>
									<td align="right"><label class="modify-value">Campaign:&nbsp;&nbsp;&nbsp;</label> </td>
									<td><span id="campaign_list">
											<select name="campaign_id" id="campaign_id" style="width:300px;">
									        
									<?php
		   		               		foreach($campaigns as $campaignInfo){
											$cid = $campaignInfo->campaign_id;
											$cname = $campaignInfo->campaign_name;
		                        		echo '<option value="'.$cid.'">'.$cid.'-'.$cname.'</option>';
										}
									?>
											</select></span>
											<span id="campaign_list_hidden" style="display:none"></span>
											</td>
								</tr>
			                	<tr>
									<td align="right"><label class="modify-value">Active:&nbsp;&nbsp;&nbsp;</label> </td>
									<td><select size="1" name="active"><option>Y</option><option>N</option></select></td>
								</tr>
								<tr><td colspan="2">&nbsp;</td></tr>
		                	</table>
						</td>
					</tr>
					<tr>
						<td align="right" colspan="9">
						<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
						<!--<a id="searchcallhistory" style="cursor: pointer;" onclick="document['go_listfrm'].submit()"><font color="#7A9E22">Submit</font></a>-->
						<a id="searchcallhistory" style="cursor: pointer;" onclick="checklistadd();"><font color="#7A9E22">Submit</font></a>		
						</div>		
											
						</td>			  
					</tr>
					
					
                  
					</table>	
					</form>
					</div>
				</div>


	<!-- edit -->
                                <div class="overlay" id="overlay" style="display:none;"></div>

                                <div class="box" id="box">
                                <center>

                                <a class="boxclose" id="boxclose" onclick="closeme();"></a>

                                <form  method="POST" id="edit_go_listfrm" name="edit_go_listfrm">
                                        <input type="hidden" name="editlist" value="editlist">
                                        <input type="hidden" name="editval" id="editval">
                                        <input type="hidden" name="showvaledit" id="showvaledit" value="">
                                        <!--<input type="hidden" name="oldcampaignid" id="oldcampaignid" value="">-->


                                        <div id="listid_edit" align="left" class="title-header"> </div>
                                        <div align="left">
                                        <!--<label class="modify-value">Change Date:</label>-->
                                        <table width="100%">
                                                <tr><td align="left"><div id="cdates"></div></td><td align="right"><div id="lcdates"></div></td></tr>
                                        </table>


                                        </div>

                                        <table class="tableedit">
                                                        <tr>
                                                                <td><br><label class="">Name:</label><div id="simula"></div> </td>
                                                                <td><input type="text" name="list_name" id="listname_edit" size="30" maxlength="30">
								<font color="red" size="1" style="display: none;">(alphanumeric only)</font></td>
                                                        </tr>
                                                        <tr>
                                                                <td><label class="">Description:</label></td>
                                                                <td><input type="text" name="list_description" id="listdesc_edit" size="30" maxlength="22">
								<font color="red" size="1" style="display: none;">(alphanumeric only)</font></td>
                                                        </tr>
                                                        <tr>
                                                                <td><label class="">Campaign:</label></td>
                                                                <td>
                                                                        <select size="1" name="campaign_id" id="campid_edit" style="width:300px;">
                                                                                <option>--- Select Campaign ---</option>
                                                                                <?php
                                                        foreach($campaigns as $campaignInfo){
                                                                                                $cid = $campaignInfo->campaign_id;
                                                                                                $cname = $campaignInfo->campaign_name;
                                                                echo '<option value="'.$cid.'">'.$cname.'</option>';
                                                                                }
                                                                                ?>
									</select>
                                                                </td>
                                                        </tr>

                                                        <tr>
                                                                <td><label class="">Reset Times:</label> </td>
                                                                <td><input type="text" name="reset_time" id="restime_edit" size="30" maxlength="100"></td>
                                                        </tr>
                                                        <!--<tr>
                                                                <td><label class="modify-value">Change Date:</label> 
                                                                </td>
                                                                <td><div id="cdates"></div></td>
                                                        </tr>
                                                        <tr>
                                                                <td>
                                                        <label class="modify-value">Last Call Date: </label>
                                                        </td>
                                                        <td><div id="lcdates"></div></td>
                                                        </tr>-->
                                                        <tr>
                                                                <td><label class="">Reset Lead-Called-Status:</label></td>
                                                                <td>
                                                                        <select size="1" name="reset_list" id="reslist_edit">
                                                                                <option value="N">N</option>
                                                                                <option value="Y">Y</option>
                                                                        </select>
                                                                &nbsp;&nbsp;&nbsp;
                                                                 <label class="">Active:</label>
                                                                        <select size="1" name="active" id="act_edit">
                                                                                <option value="Y">Y</option>
                                                                                <option value="N">N</option>
                                                                        </select>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td><label class="">Agent Script Override:</label> </td>
                                                                <td>
                                                                        <select size="1" name="agent_script_override" id="agcscp_edit">
                                                                                <?php
                                                                                         if($eagent_script_override!=null) {
                                                                                ?>
                                                                                         <option value="<?=$eagent_script_override?>"><?=$eagent_script_override?></option>                        
                                                                                <?
                                                                                         } else {
                                                                                ?>
											<option selected value=""> - </option>
                                                                                <?php
                                                                                         }
                                                                                ?>
                                                                                <option value="">NONE - INACTIVE</option>
                                                                        </select>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td><label class=""><label class="">Campaign CID Override:</label> </td>
                                                                <td><input type="text" name="campaign_cid_override" id="campcidover_edit" size="20" maxlength="20"></td>
                                                        </tr>
                                                        <!-- <tr>
                                                                <td>Answering Machine Message Override: </td>
                                                                <td><input type="text" name="am_message_exten_override" id="am_message_exten_override" size="50" maxlength="100" value="<?=$eam_message_exten_override?>"></td>
                                                        </tr> -->
                                                        <tr>
                                                                <td><label class=""><label class="">Drop Inbound Group Override:</label> </td>
                                                                <td>
                                                                        <select size="1" name="drop_inbound_group_override" id="drpinbovr_edit">
                                                                        <?php
                                                                                if($edrop_inbound_group_override!=null) {
                                                                        ?>
                                                                                <option value="<?=$edrop_inbound_group_override?>"><?=$edrop_inbound_group_override?></option>
                                                                        <?
                                                                                }
                                                                        ?>
                                                                                <option value="">NONE</option>
                                                                        </select>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td><label class="">Web Form:</label> </td>
                                                                <td><input type="text" name="web_form_address" id="wbfrmadd_edit" size="50" maxlength="1055"></td>
                                                        </tr>
                                                        <tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
                                                        <tr>
                                                                <td colspan="2" align="center"> <br><label class="">Transfer-Conf Number Override</label> </td>
                                                        </tr>
                                                        <tr>
                                                                <td colspan="2">
                                                                <label class="">Number 1:</label> <input type="text" name="xferconf_a_number" id="xfer1" size="20" maxlength="50">
                                                                <label class="">Number 4:</label> <input type="text" name="xferconf_d_number" id="xfer4" size="20" maxlength="50">
                                                                <br>
                                                                <label class="">Number 2:</label> <input type="text" name="xferconf_b_number" id="xfer2" size="20" maxlength="50">
								<label class="">Number 5:</label> <input type="text" name="xferconf_e_number" id="xfer5" size="20" maxlength="50">
                                                                <br>
                                                                <label class="">Number 3:</label> <input type="text" name="xferconf_c_number" id="xfer3" size="20" maxlength="50">
                                                                </td>
                                                        </tr>
                                                        <tr><td colspan="2">&nbsp;</td></tr>
                                                        <tr><td colspan="2">&nbsp;</td></tr>
                                                        <tr>
                                                                <td colspan="2" align="center"><b><?php
                                                                echo "<a id=\"clickadvanceplus\" style=\"cursor: pointer;\" onclick=\"$('#statusid').css('display', 'block'); $('#clickadvanceplus').css('display', 'none'); $('#clickadvanceminus').css('display', 'block');  \" title=\"Click to view reports\">[ + ] STATUSES WITHIN THIS LIST</a><a id=\"clickadvanceminus\" style=\"cursor: pointer; display: none;\" onclick=\"$('#statusid').css('display', 'none'); $('#clickadvanceplus').css('display', 'block'); $('#clickadvanceminus').css('display', 'none');\" title=\"Click to view reports\">[ - ] STATUSES WITHIN THIS LIST</a>";
?></b></td>
                                                        </tr>
                                                        <tr>
                                                                <td colspan="2" align="center">
                                                                        <div id="stats"></div>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td align="center" colspan="2"><br>
                                                                <input type="button" name="editSUBMIT" class="button" style="cursor:pointer;border:0px;color:#7A9E22;" value="MODIFY" onclick="editpost(document.getElementById('showvaledit').value);">
<!--                                                            <input type="submit" name="editSUBMIT" value="MODIFY">                                                          -->
                                                                </td>

                                                        </tr>
                                                        <tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
                                        </table>
                                </form>
                                </center>
                                </div>
                                <!-- end edit -->



	<div id="tabs-1">

				<!-- LISTs TAB -->
				<div id="showlist" style="display: block;">
				
				<form name="showlistview" id="showlistview">
				<input type="hidden" name="showval" id="showval">
				
				<table id="listtableresult" class="tablesorter" width="100%" class="" cellspacing="0" cellpadding="0" border="0" style="margin-left:auto; margin-right:auto; width:100%;margin-top:3px;" > 
					<thead>
					<tr align="left" class="nowrap">
						<th class="thheader" style="padding-bottom:-1px;">&nbsp;&nbsp;<b>LIST ID</b> </th>
						<th colspan="" class="thheader" style="padding-bottom:-1px;"><b>NAME</b> </th>
						<th class="thheader" align="left" style="padding-bottom:-1px;"><b>STATUS</b> </th>
						<th class="thheader" style="padding-bottom:-1px;"><b>LAST CALL DATE</b> </th>
						<th class="thheader" style="padding-bottom:-1px;"><b>LEADS COUNT</b> </th>
						<th class="thheader" style="padding-bottom:-1px;"><b>CAMPAIGN</b> </th>
						<th colspan="3" class="thheader" style="width:7%;white-space: nowrap;padding-bottom:-1px;" align="right">
						<span style="cursor:pointer;" id="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span>



</th>
						<th align="center" width="26px" style="padding-bottom:-1px;">
									<!--<input id="sellist" value="<?=$ingroupInfo->group_id;?>" type="checkbox">-->	
									<input type="checkbox" id="selectAll" />							 
						</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if($permissions->list_read == "N"){
                                                echo("<tr class='tr2'><td colspan='9'>You don't have permission to view this record(s)</td></tr>");
                                                $countthis = 0;
                                                $justpermission = true;
                                        }	
                  
                  if($countthis > 0){
                  								
					   foreach($lists as $listsInfo){
					?>   		         	
							 <tr align="left" class="tr<?php echo alternator('1', '2') ?>">
								 <td align="left" style="padding-bottom:-1px;">&nbsp;
								 <!--<div class="rightdiv toolTip" title="MODIFY <?=$listsInfo->list_id?>">-->
								<!-- <a id="listidlink" class="activator"  onClick="postval('<? echo $listsInfo->list_id; ?>');"><? echo $listsInfo->list_id; ?></a>-->
								<a id="listidlink" class="leftDiv toolTip" style="cursor:pointer;"  title="MODIFY <?=$listsInfo->list_id?>"  onClick="postval('<? echo $listsInfo->list_id; ?>');"><? echo $listsInfo->list_id; ?></a>
						
								</td>
								
								 <td colspan="" style="padding-bottom:-1px;">
								 								 
								 <?
								 	
									if($listsInfo->list_name == "") {
										echo "&nbsp;";
									} else {
										echo ucwords(strtolower($listsInfo->list_name));
									}
								 ?>
								 </td>
								 <td align="left" style="padding-bottom:-1px;">
								 <?php
								 	if($listsInfo->active=="Y") {
								 		echo "<b><font color=green>ACTIVE</font></b>";
								 	} else {
								 		echo "<b><font color=red>INACTIVE</font></b>";	
								 	}
								 	
								 ?>
								 </td>
								 <td align="left" style="padding-bottom:-1px;">	
								<?
								 		#echo $listsInfo->list_lastcalldate.'&nbsp;'; 
										echo str_replace("-","&#150;",$listsInfo->list_lastcalldate)."&nbsp;";
								?>
								</td>
				
								 <td align="left" style="padding-bottom:-1px;"><font color="RED"><b><? echo $listsInfo->tally; ?></b></font></td>
								 <td align="left" style="padding-bottom:-1px;"><? echo $listsInfo->campaign_id."&nbsp;"; ?></td>
  								 <td align="right" style="padding-bottom:-1px;">
  								
<img src="<?=$base?>img/edit.png" onclick="postval('<? echo $listsInfo->list_id; ?>');"  class="rightdiv toolTip" style="cursor:pointer;width:14px; padding: 3px;" title="MODIFY <?=$listsInfo->list_id?>"  />
								
  								 </td>
  								 <td align="left" style="padding-bottom:-1px;">
								 <?php
									if($listsInfo->list_id == 998 || $listsInfo->list_id == 999 || $listsInfo->list_id == 101) {
								 ?>
								 
  								 <div class="rightdiv toolTip" title="Cannot delete <?=$listsInfo->list_id?>" style="padding:3px;">
						 			 <img src="<?=$base?>img/delete_grayed.png" style="cursor:pointer;width:12px;"  /> 
						 		 </div>
								<?php
									} else {
								?>
  								 <div class="rightdiv toolTip" title="DELETE <?=$listsInfo->list_id?>" style="padding:3px;">
						 			 <img src="<?=$base?>img/delete.png" onclick="deletepost('<? echo $listsInfo->list_id; ?>');" style="cursor:pointer;width:12px;"  /> 
						 		 </div>
								
								<?php
								}
								?>
  								 <div class="rightdiv toolTip" title="Download <?=$listsInfo->list_id?>" style="padding:3px;">
						 			 <a href="<?=$base?>index.php/go_list/download/<?=$listsInfo->list_id?>"><img src="<?=$base?>img/download.png" style="cursor:pointer;width:12px;"  /> </a>
						 		 </div>
								 </td>
  								 <td align="center" style="padding-bottom:-1px;">
  								<div class="rightdiv toolTip" title="VIEW INFO FOR LIST <?=$listsInfo->list_id?>" style="padding: 3px;">
									<img style="cursor:pointer;width:12px;" src="<?=$base?>img/status_display_i.png" onclick="viewpost('<? echo $listsInfo->list_id; ?>');" style="cursor:pointer;width:14px;">
									</div>
								 </td>
								 <td align="center" width="26px" style="margin-top:-1px;padding-bottom:-1px;">
								 <?php
									if($listsInfo->list_id == 998 || $listsInfo->list_id == 999 || $listsInfo->list_id == 101) {
								 ?>
									<input type="checkbox" id="cannotdelete[]" disabled/>
								<?php
									} else {
								?>
									<input type="checkbox" id="delselectlist[]" value="<?=$listsInfo->list_id;?>" />
								<?php
								}
								?>
								 </td>
							 </tr>
							<?php
							$i++;
							}
							} else {
								if(!$justpermission){
	
							echo "<td colspan=\"7\" align=\"center\" style=\"background-color: #EFEFEF;\"><font color=\"red\"><b>No record(s) found!</b></font></td>";

								}
							}
							
							?>
							
			</tbody>
				</table><br>
				<?php
				if (strlen($pagelinks["lists"]) > 0 || !is_null($pagelinks["lists"])) {
					echo "<div style='float:left;'>".$pagelinks["lists"]."</div>";
				}
			        echo "<div style='float:right;'>".$pageinfo["lists"]."</div>";
				?>
				<table id="listloadinggif" class="" width="100%" class="" cellspacing="0" cellpadding="0" border="0" style="margin-left:auto; margin-right:auto; width:100%;" > 
					<tr><td colspan="7" align="center"><div id="loadingslist"></div></td></tr>				
				</table>
				</form>
				</div>
				<!-- end view -->
				
				<!-- end add -->
				
				
				<!-- edit -->
				<!-- <div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="box" id="box">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closeme();"></a>
	
				<form  method="POST" id="edit_go_listfrm" name="edit_go_listfrm">
					<input type="hidden" name="editlist" value="editlist">
					<input type="hidden" name="editval" id="editval">
					<input type="hidden" name="showvaledit" id="showvaledit" value="">
		
					
					<div id="listid_edit" align="left" class="title-header"> </div>
					<div align="left">					
					<table width="100%">
						<tr><td align="left"><div id="cdates"></div></td><td align="right"><div id="lcdates"></div></td></tr>
					</table>
					
					
					</div>
					
					<table class="tableedit">
							<tr>
								<td><br><label class="modify-value">Name:</label><div id="simula"></div> </td>
								<td><input type="text" name="list_name" id="listname_edit" size="30" maxlength="30"></td>
							</tr>
							<tr>
								<td><label class="modify-value">Description:</label></td>
								<td><input type="text" name="list_description" id="listdesc_edit" size="30" maxlength="255"></td>
							</tr>
							<tr>
								<td><label class="modify-value">Campaign:</label></td>
								<td>
									<select size="1" name="campaign_id" id="campid_edit">
										<option>--- Select Campaign ---</option>
										<?php
   		               			/*	foreach($campaigns as $campaignInfo){
												$cid = $campaignInfo->campaign_id;
												$cname = $campaignInfo->campaign_name;
                        					echo '<option value="'.$cid.'">'.$cname.'</option>';
										} */
										?>
									</select>
								</td>
							</tr>
							
							<tr>
								<td><label class="modify-value">Reset Times:</label> </td>
								<td><input type="text" name="reset_time" id="restime_edit" size="30" maxlength="100"></td>
							</tr>
							<tr>
								<td><label class="modify-value">Reset Lead-Called-Status:</label></td>
								<td>
									<select size="1" name="reset_list" id="reslist_edit">
										<option value="N">N</option>
										<option value="Y">Y</option>
									</select> 
								&nbsp;&nbsp;&nbsp;
								 <label class="modify-value">Active:</label>
									<select size="1" name="active" id="act_edit">
										<option value="Y">Y</option>
										<option value="N">N</option>
									</select>
								</td>
							</tr>
							<tr>
								<td><label class="modify-value">Agent Script Override:</label> </td>
								<td>
									<select size="1" name="agent_script_override" id="agcscp_edit">
										<?php
											 if($eagent_script_override!=null) {
										?>
											 <option value="<?=$eagent_script_override?>"><?=$eagent_script_override?></option>										 
										<?											 
											 } else {
										?>
											 <option selected value=""> - </option>
										<?php	 	
											 }
										?>
										<option value="">NONE - INACTIVE</option>
									</select>
								</td>
							</tr>
							<tr>
								<td><label class="modify-value"><label class="modify-value">Campaign CID Override:</label> </td>
								<td><input type="text" name="campaign_cid_override" id="campcidover_edit" size="20" maxlength="20"></td>
							</tr>
							<tr>
								<td><label class="modify-value"><label class="modify-value">Drop Inbound Group Override:</label> </td>
								<td>
									<select size="1" name="drop_inbound_group_override" id="drpinbovr_edit">
									<?php
										if($edrop_inbound_group_override!=null) {
									?>
										<option value="<?=$edrop_inbound_group_override?>"><?=$edrop_inbound_group_override?></option>
									<?
										}
									?>
										<option value="">NONE</option>
									</select>			
								</td>
							</tr>
							<tr>
								<td><label class="modify-value">Web Form:</label> </td>
								<td><input type="text" name="web_form_address" id="wbfrmadd_edit" size="50" maxlength="1055"></td>
							</tr>
							<tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
							<tr>
								<td colspan="2" align="center"> <br><label class="modify-value">Transfer-Conf Number Override</label> </td>
							</tr>
							<tr>
								<td colspan="2">
								<label class="modify-value">Number 1:</label> <input type="text" name="xferconf_a_number" id="xfer1" size="20" maxlength="50">
								<label class="modify-value">Number 4:</label> <input type="text" name="xferconf_d_number" id="xfer4" size="20" maxlength="50">
								<br>
								<label class="modify-value">Number 2:</label> <input type="text" name="xferconf_b_number" id="xfer2" size="20" maxlength="50">
								<label class="modify-value">Number 5:</label> <input type="text" name="xferconf_e_number" id="xfer5" size="20" maxlength="50">
								<br>
								<label class="modify-value">Number 3:</label> <input type="text" name="xferconf_c_number" id="xfer3" size="20" maxlength="50">
								</td>
							</tr>
							<tr><td colspan="2">&nbsp;</td></tr>
							<tr><td colspan="2">&nbsp;</td></tr>
							<tr>
								<td colspan="2" align="center"><b><?php
?></b></td>
							</tr>			
							<tr>
								<td colspan="2" align="center">
									<label id="stats"></label>
								</td>
							</tr>			
							<tr>
								<td align="center" colspan="2"><br>
								<input type="button" name="editSUBMIT" class="button" style="cursor:pointer;border:0px;color:#7A9E22;" value="MODIFY" onclick="editpost(document.getElementById('showvaledit').value);">
								</td>
								
							</tr>
							<tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
					</table>				
				</form>
				</center>
				</div>-->	
				<!-- end edit -->
				
			    <!-- view edit -->
			    <div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="boxviewlist" id="boxviewlist">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closemeview();"></a>
 				
 				<table summary="" class="listinfo">
					<tr>
					<td class="listinfolabel">
						<b>List I.D.: </b>					
					</td>
					<td>
						<div id="viewlistid" align="left"> </div>
					</td>
					</tr>
					<tr>
					<td class="listinfolabel">
						<b>Description: </b>					
					</td>
					<td>
						<div id="viewlistdesc" align="left"> </div>
					</td>
					</tr>
					<tr>					
					<td class="listinfolabel">
						<b>Status: </b>					
					</td>
					<td>
						<div id="viewliststatus" align="left"> </div>
					</td>					
					</tr>
					<tr>
					<td class="listinfolabel">
						<b>Last call date: </b>					
					</td>
					<td>
						<div id="viewlistcalldate" align="left"> </div>
					</td>
					
					</tr>
                                        <tr>
                                            <td colspan="2" class="listdownload" ><a id="download">Download</a></td>
                                        </tr>
				</table>			
	 				
			    </center>
			    </div>
			    <!-- end view edit -->						
				
				
				
				
	</div> <!-- end LISTs -->
	
	
	
	
	
	
	
	
	<!-- CUSTOM FIELDS -->
	<div id="tabs-2">
	<div id="showlist" style="display: block;">
	
			<table id="cumstomtable" class="tablesorter" width="100%" class="" cellspacing="0" cellpadding="0" border="0" style="margin-left:auto; margin-right:auto; width:100%; margin-top: 3px;" > 
				<thead>
				<tr align="left" class="nowrap">
					<th class="thheader">&nbsp;&nbsp;<b>LIST ID</b> </th>
					<th class="thheader"><b>DESCRIPTION</b></th>
					<th class="thheader"><b>STATUS</b> </th>
					<th class="thheader"><b>CAMPAIGN ASSIGNED</b> </th>
					<th class="thheader"><b>CUSTOM FIELDS</b> </th>
					<th class="thheader"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> </th>
					
					<th colspan="3" class="thheader" style="width:8%;" align="right">
                                            <span style="cursor:pointer;" id="customselectAction">&nbsp;MODIFY&nbsp;</span>
					</th>
                                        <th align="center" width="35px">
                                           <input type="checkbox" id="customselectallf" disabled/>
                                        </th>
				</tr>
				</thead>
				<?php
					if (strlen($clist) > 0)
					{
						echo $clist;
					} else {
						 echo "<tr><td colspan=\"7\" align=\"center\" style=\"background-color: #EFEFEF;\"><font color=\"red\"><b>No custom fields created.</b></font></td></tr>";

#						echo "<tr><td colspan='6' style='font-weight:bold;color:#f00;text-align:center;padding-top:10px;'>No custom fields created.</td></tr>";
					}
				?>
			</table>
			<?php
			if (strlen($pagelinks["custom"]) > 0 || !is_null($pagelinks["custom"])) {
				echo "<div style='float:left;padding-top:10px;'>".$pagelinks["custom"]."</div>";
			}
			echo "<div style='float:right;padding-top:10px;'>".$pageinfo["custom"]."</div>";
			?>
		</div>
	
	</div><!-- end tab2 -->



	<!-- tab3 -->
	<div id="tabs-3">
				<!-- upload leads -->
				<div id="uploadlist" style="display: block;" align="left">
				<form action="go_list" name="uploadform" id="uploadform" method="post" onSubmit="ParseFileName()" enctype="multipart/form-data">
				<input type="hidden" name="leadsload" id="leadsloadok" value="ok">
				<input type="hidden" name="tabvalsel" id="tabvalsel" value="<?=$tabvalsel?>">
				<input type="hidden" name="leadfile_name" id="leadfile_name" value="<?=$leadfile_name?>">
				<!--<b>Load Leads</b>
				<table class="tableedit" width="100%">
					<tr><td colspan="2">&nbsp;&nbsp;</td></tr>
				</table>
				-->
				<center>
				<table class="tablenodouble" width="50%">
				<?php
						$permissions = $this->commonhelper->getPermissions("loadleads",$this->session->userdata("user_group"));
						if($permissions->loadleads_read == "N"){
                                                   echo("<tr><td colspan='9'>You don't have permission to view this record(s)</td></tr>");
                                                   $countthis = 0;
                                                   $justpermission = true;
						   exit;
                                                 } 

						
				?>	
			
					<tr>
						<td colspan="2">&nbsp;&nbsp;</td>
					</tr>
		  			<tr>
						<td align="right"><label class="modify-value">Leads file:</label></td>
						<td><input type="file" name="leadfile" id="leadfile" value="<?php echo $leadfile ?>">
						<div class="progress">
                            <div class="bar"></div >
                            <div class="percent">0%</div >
                        </div>
                        <div id="customsd"></div>
					</td>
		  			</tr>
					<tr>
						<td align="right"><label class="modify-value">List ID:</label></td>
						<td>
							<select name="list_id_override">
								<?php
									foreach($listIDs as $listsInfo){
											$load_list_id = $listsInfo->list_id;
											$load_list_name = $listsInfo->list_name;
											echo '<option value="'.$load_list_id.'">'.$load_list_id.'---'.$load_list_name.'</option>';	 
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"><label class="modify-value">Phone Code: </label></td>
						<td>
								<select name="phone_code_override">
                        	<option value='in_file'>Load from Lead File</option>
                        	<?php
						//echo '<option value="1" selected>1---USA</option>';
                        		foreach($phonedoces as $listcodes) {
 									$selected = '';
									$country_code = $listcodes->country_code;
                        			$country = $listcodes->country;
									if ($country=="USA")
										$selected='selected="selected"';
                        			echo '<option value="'.$country_code.'" '.$selected.'>'.$country_code.'---'.$country.'</option>';
								}
                        	?>
                        </select><br>
			 <font size="1" color="red">*If you select Load from Lead Files, be sure to check your phone code from your file.</font>
                        </td>
                </tr>
                <!--<tr>
						<td><B><font face="arial, helvetica" size="2">File layout to use:</font></B></td>
						<td><font face="arial, helvetica" size="2"><input type="radio" name="file_layout" value="standard">Standard Format&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio name="file_layout" value="custom" checked>Custom layout</td>
                </tr>-->
                <tr>
						<td align="right"><label class="modify-value">Duplicate Check: </label></td>
						<td>
							<select size="1" name="dupcheck">
								<option value="NONE">NO DUPLICATE CHECK</option>
								<option value="DUPLIST">CHECK FOR DUPLICATES BY PHONE IN LIST ID*</option>
								<option value="DUPCAMP">CHECK FOR DUPLICATES BY PHONE IN ALL CAMPAIGN LISTS</option>
								<!-- <option value="DUPSYS">CHECK FOR DUPLICATES BY PHONE IN ENTIRE SYSTEM</option>
								<option value="DUPTITLEALTPHONELIST">CHECK FOR DUPLICATES BY TITLE/ALT-PHONE IN LIST ID</option>
								<option value="DUPTITLEALTPHONESYS">CHECK FOR DUPLICATES BY TITLE/ALT-PHONE IN ENTIRE SYSTEM</option>
 -->
							</select>
						</td>
		  			 </tr>
		  			 <tr>
		  			 <td align="right"><label class="modify-value">Time Zone: </label></td>
						<td>
							<select size="1" name="postalgmt">
								<option value="AREA" selected>COUNTRY CODE AND AREA CODE ONLY</option>
								<option value="POSTAL">POSTAL CODE FIRST</option>
								<option value="TZCODE">OWNER TIME ZONE CODE FIRST</option>
							</select>
						</td>
					 </tr>
					 <tr>
					 	<tr><td colspan="2">&nbsp;&nbsp;</td></tr>
					 	<td colspan="2">
					 		<center>
					 			<input type="submit" value="UPLOAD LEADS" name="submit_file" id="submit_file" style="cursor:pointer;" onclick="return checkmes();">
					 			<!--<input type="button" onClick="javascript:document.location='go_list/#tabs-3'" value="START OVER" name='reload_page'>-->
					 		</center>
					 	</td>
					 	
					 </tr>
					 </form>
					 <?php
					 if($fields!=null) {
					 ?>
					<form action="go_list" name="uploadform2" id="uploadform2" method="post" onSubmit="ParseFileName()" enctype="multipart/form-data">
					<input type="hidden" name="leadsload" value="okfinal">
					<input type="hidden" name="lead_file" id="lead_file" value="<?=$lead_file?>">
					<input type="hidden" name="leadfile" id="leadfile" value="<?=$leadfile?>">
					<input type="hidden" name="list_id_override" value="<?=$list_id_override?>">
					<input type="hidden" name="phone_code_override" value="<?=$phone_code_override?>">
					<input type="hidden" name="dupcheck" value="<?=$dupcheck?>">
					<input type="hidden" name="leadfile_name" id="leadfile_name" value="<?=$leadfile_name?>">
					<input type="hidden" name="superfinal" id="superfinal">
					

					 <tr>
					 	
					 	<td colspan="2" align="center">
					 			
					 			<br><br><br><br>
					 			<table>
					 			<tr bgcolor="#efefef">
					 			<td align="center" colspan="2"><b>Processing <?=$delim_name ?> file...<br>

LIST ID FOR THIS FILE: <?=$list_id_override?><br>

COUNTRY CODE FOR THIS FILE: <?=$phone_code_override?></b><br><br><br></td>
					 			</tr>
					 			<?php	
					 			
									//$noview = array("security_phrase","date_of_birth","gender","country_code","phone_code","owner","rank","address3","address2","title","source_id","vendor_lead_code","lead_id","entry_date","modify_date","status","user","list_id","gmt_offset_now","called_since_last_reset","called_count","last_local_call_time","entry_list_id");
									$noview = array("phone_code","lead_id","entry_date","modify_date","status","user","list_id","gmt_offset_now","called_since_last_reset","called_count","last_local_call_time","entry_list_id");					 				
									$hiddenfields = array("security_phrase","date_of_birth","gender","country_code","vendor_lead_code","title","owner","rank","address3","address2","source_id");
									//$noview = array("security_phrase","alt_phone","date_of_birth","gender","country_code","phone_code","owner","rank","address3","address2","title","source_id","vendor_lead_code","lead_id","entry_date","modify_date","status","user","list_id","gmt_offset_now","called_since_last_reset","called_count","last_local_call_time","entry_list_id");					 				
					 				
					 				foreach ($fields as $field) {
					 					
					 					if(in_array("$field", $noview)) {
											echo "";					 						
					 					} else {
											if (in_array("$field", $hiddenfields))
											{
												$hiddenclass = 'class="advancedFields"';
											} else {
												$hiddenclass = '';
											}
										
											echo "  <tr bgcolor=#efefef $hiddenclass>\r\n";
											echo "    <td align=right><b><font class=standard>".strtoupper(eregi_replace("_", " ", $field)).": </font></b></td>\r\n";
											echo "    <td align=left><select name='".$field."_field'>\r\n";
											echo "     <option value='-1'>(none)</option>\r\n";

											for ($j=0; $j<count($fieldrow); $j++) {
												eregi_replace("\"", "", $fieldrow[$j]);
												echo "     <option value='$j'>\"$fieldrow[$j]\"</option>\r\n";
											}
									
											echo "  </select></td>\r\n";
											echo "  </tr>\r\n";
					
										}
									} // end for
								
					 			?>
								<tr>
									<td colspan="2" style="padding-top:20px;text-align:center;"><span id="advancedFieldLink">[ + SHOW ADVANCE FIELDS ]</span></td>
								</tr>
								<tr>
									<td colspan="2" style="padding-top:15px;text-align:center;white-space:nowrap;font-size:10px;color:red;">*Custom fields will show here if you have enabled it on the list id you provided.</td>
								</tr>
					 		</table>
					 	</td>
					 </tr>
					 <tr>
						<td colspan="4" align="center">	
							<div id="loadings"></div>					 
					 	</td>
					 </tr>
					 
					 <tr>
				    	<td colspan="4" align="center">
				    		<br><br><br>
				    		<input type="submit" name="OK_to_process" value="OK TO PROCESS" onclick="uploadimg();">
				    		<!--<input type="button" onClick="javascript:document.location='go_list/#tabs-3'" value="BACK" name="reload_page">-->
				    		
						</td>
					 <?php
					 	}
					 ?>
					 </tr>
					 </form>
		  		</table>
		  		</center>
				</div>
	</div><!-- end tab3 -->

	       <!-- COPY CUSTOM FIELDS -->
        <div id="tabs-4">
        <div id="copylist" style="display: none;">
			<form name="copyfields" id="copyfields" method="POST">
                        <table id="cumstomtablec" class="tablesorter" cellspacing="0" cellpadding="0" border="0" style="margin-left:auto; margin-right:auto; ">
                                <thead>
		                        <tr align="center" class="nowrap">
                                        	<td class="thheader" align="center" colspan="2">&nbsp;&nbsp;</td>
					</tr>
                                	<tr align="center" class="nowrap">
                                        	<td class="thheader" align="right"><b>FROM: &nbsp;&nbsp;&nbsp;&nbsp; </b></td>
						<td>	
						<select id="dropsource_list_id" name="dropsource_list_id">
						<?php
			  				foreach($dropcopylist as $dropcopylistInfo){
                                  				$copy_list_id = $dropcopylistInfo->list_id;
                                  				$copy_list_name = $dropcopylistInfo->list_name;
                                  				echo '<option value="'.$copy_list_id.'">'.$copy_list_id.'---'.$copy_list_name.'</option>';
                          				}
						?>
						</select>
						</td>
                                	</tr>
                                	<tr align="center" class="nowrap">
                                        	<td class="thheader" align="right"><b>TO: &nbsp;&nbsp;&nbsp;&nbsp; </b></td>
						<td>	
						<select id="dropcopy_list_id" name="dropcopy_list_id">
						<?php
			  				foreach($dropcopylist as $dropcopylistInfo){
                                  				$copy_list_id = $dropcopylistInfo->list_id;
                                  				$copy_list_name = $dropcopylistInfo->list_name;
                                  				echo '<option value="'.$copy_list_id.'">'.$copy_list_id.'---'.$copy_list_name.'</option>';
                          				}
						?>
						</select>
						</td>
                                	</tr>
		                        <tr align="center" class="nowrap">
                                        	<td class="thheader" align="right"><b>OPTION: &nbsp;&nbsp;&nbsp;&nbsp; </b></td>
						<td align="left">
						<select id="copy_option" name="copy_option">
							<option value="APPEND">APPEND</option>
							<option value="UPDATE">UPDATE</option>
							<option value="REPLACE">REPLACE</option>
						</select>	
						</td>	
					</tr>
		                        <tr align="center" class="nowrap">
                                        	<td class="thheader" align="center" colspan="2">&nbsp;&nbsp;</td>
					</tr>
		                        <tr align="center" class="nowrap">
                                        	<td class="thheader" align="center" colspan="2"><input type="submit" name="COPY_SUBMIT" id="COPY_SUBMIT" value="SUBMIT"></td>
					</tr>
                                </thead>
                                <tbody>
                                </tbody>
                        </table>
			</form>
                </div>

        </div><!-- end tab4 -->
		
		
	<!-- DNC NUMBERS -->
        <div id="tabs-5">
			<br style="font-size:8px;" />
			<div class="table_dnc" style="margin-top:-15px;">
				<div id="dnc_placeholder">
					<br />
					<p style="text-align:center;font-weight:bold;color:#f00;">Type the number at the top right search box.</p>
				</div>
			</div>
		
			<!-- Overlay1 -->
			<div id="overlayDNC" style="display:none;"></div>
			<div id="boxDNC">
			<a id="closeboxDNC" class="toolTip" title="CLOSE"></a>
			<div id="overlayContentDNC"></div>
			</div>

			<!-- Action Menu -->
			<div id='go_dnc_menu' class='go_dnc_menu'>
			<ul>
			<li class="go_dnc_submenu" title="Delete Selected" id="delete">Delete Selected</li>
			</ul>
			</div>
        </div>
	<!-- end tab5 -->
	
	<div id="tabs-6">
		
	</div>

				

									<div style="display: none;" class="demo-description">
										<p>Click tabs to swap between content that is broken into logical sections.</p>
									</div><!-- End demo-description -->							
				
                            <div class="container">
                               <div class="clear"></div>
                            </div>
                            						<div id='go_action_menu' class='go_action_menu'>
<ul>
<li class="go_action_submenu" title="Activate Selected" id="activate">Activate Selected</li>
<li class="go_action_submenu" title="Deactivate Selected" id="deactivate">Deactivate Selected</li>
<li class="go_action_submenu" title="Delete Selected" id="delete">Delete Selected</li>
</ul>
</div>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
</div>
</div> <!-- wpwrap -->
</div>
<!-- end body -->
