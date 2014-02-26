<?php

############################################################################################
####  Name:             go_audiostore.php                                               ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();

?>
<script>
$(document).ready(function() 
    { 
        $("#filestable").tablesorter({sortList:[[0,0]], headers: { 6: { sorter: false}, 7: {sorter: false} }});
    } 
); 
</script>

<script>
	$(document).ready(function(){
	$(".toolTip").tipTip();
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
						
			
    });  
  
		
</script>
<script>
$(document).ready(function() 
    { 
        $("#audiolisttable").tablesorter(); 
        
	
	$("#showAllLists").click(function()
	{
	    location.href = '<?=base_url() ?>audiostore';
	});
	
	$("#search_list_button").click(function()
	{
	    var search = $("#search_list").val();
	    if (search.length > 2) {
		$('#showAllLists').show();
		$('#search_submit').submit();
	    } else {
		alert("Please enter at least 3 characters to search.");
	    }
	});
	
	$('#search_list').bind("keydown keypress", function(event)
	{
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
		if ((event.which > 32 && event.which < 48) || (event.which == 32 && $(this).attr('id') != "statusName") || (event.which > 57 && event.which < 65)
		    || (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
		    return false;
	    }
	    //console.log(event.type + " -- " + event.altKey + " -- " + event.which);
	    if (event.which == 13 && event.type == "keydown") {
		var search = $("#search_list").val();
		if (search.length > 2) {
		    $('#showAllLists').show();
		    $('#search_submit').submit();
		} else {
		    alert("Please enter at least 3 characters to search.");
		}
	    }
	});
	
	if (<?=strlen($search) ?> > 0) {
	    $('#search_list').val('<?=$search ?>');
	    $('#showAllLists').show();
	}
    } 
); 
</script>

<!--<script type="text/javascript" src="../../../js/go_ingroup/jscolor.js"></script>-->

<!--  Javascript section -->
<script language="javascript">


	function getselval() {
    		var account_num = document.getElementById('campaign_id').value;
		//alert(account_num);
	}

	function showaddlist(listid) {
		document.getElementById('addlist').style.display='block';
		document.getElementById('showlist').style.display='none';
		document.getElementById('list_id').value = listid;
	}


	function showRow() {
    		var autoGen = document.getElementById('auto_gen');
    		var selectCamp_old = document.getElementById('campaign_list_hidden');
    		var account_num = document.getElementById('account_num');

    		if (autoGen.checked) {
        		selectCamp_old.innerHTML = document.getElementById('campaign_list').innerHTML;
        		document.getElementById('account_numTD').style.display = 'table-row';
        		document.getElementById('list_id').readOnly = true;
        		document.getElementById('list_id').value = '';
        		document.getElementById('list_name').value = '';
        		document.getElementById('list_description').value = '';
    		} else {
        		document.getElementById('account_numTD').style.display = 'none';
       	 		document.getElementById('list_id').readOnly = false;
        		document.getElementById('list_id').value = '';
        		document.getElementById('list_name').value = '';
        		document.getElementById('list_description').value = '';
        		document.getElementById('campaign_list').innerHTML = selectCamp_old.innerHTML;
        		account_num.selectedIndex = 0;
    		}
	}


	function genListID(accnt) {
    		var account_num = document.getElementById('account_num');
    		var cntX=0;


    		if (accnt>0) {
        		var autoListID = account_num.options[accnt].value;
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
                			var cnt=returnArray[0];
                			var camp_list=returnArray[1].split(",");
                			var camp_name=returnArray[2].split(",");
                			var i=0;
                			cnt++;
                		
	
					var selectCamp = '<select name=campaign_ids id=campaign_ids>';
  	             				for (i=0;i<camp_list.length;i++) {
                    					selectCamp = selectCamp + '<option value="'+camp_list[i]+'">'+camp_list[i]+' - '+camp_name[i]+'</option>';
                				}
                				selectCamp = selectCamp + '</select>';
                		
					if (cnt < 1000000) {
                    				cntX="1" + cnt;
                			}
                			if (cnt < 100000) {
                    				cntX="10" + cnt;
                			}
                			if (cnt < 10000) {
                    				cntX="100" + cnt;
                			}
                			if (cnt < 1000) {
                    				cntX="1000" + cnt;
                			}
                			if (cnt < 100) {
                    				cntX="10000" + cnt;
                			}
                			if (cnt < 10) {
                    				cntX="100000" + cnt;
                			}
                			
							var currentTime = new Date();
							var month = currentTime.getMonth() + 1;
							var day = currentTime.getDate();
							var year = currentTime.getFullYear();
							var comdate = month+'-'+day+'-'+year;

							document.getElementById('list_name').value = autoListID + ' ListID ' + cntX;
                			document.getElementById('list_description').value = 'Auto-Generated ListID - '+comdate;
                			document.getElementById('list_id').value = autoListID.substr(0,5) + cntX;
                			document.getElementById('campaign_list').innerHTML = selectCamp;
                		}	
            		}
        		xmlhttp.open("GET","go_list/go_list.php?stage=addLIST&accnt="+autoListID,true);
        		//xmlhttp.open("GET",window.location.pathName+"?stage=addLIST&accnt="+autoListID,true);
        		xmlhttp.send();
    		} else {
        		var selectCamp_old = document.getElementById('campaign_list_hidden');
        		document.getElementById('list_id').value = '';
        		document.getElementById('list_name').value = '';
        		document.getElementById('list_description').value = '';
        		document.getElementById('campaign_list').innerHTML = selectCamp_old.innerHTML;
    		}
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
	

	function postval(groupid) {

		document.getElementById('showval').value=groupid;
		document.getElementById('showvaledit').value=groupid;

		var items = $('#showlistview').serialize();
			$.post("<?=$base?>index.php/go_ingroup/editview", { items: items, action: "editlist" },
				 function(data){

                				var datas = data.split("##");

                				document.getElementById('agentrankvalue').innerHTML=datas[102];
                				
                				var i=0;
                				var count_listid = datas.length;
				
                				for (i=0;i<count_listid;i++) {

                						if(datas[i]=="") {
												datas[i]=" ";
 											}
 											
											document.getElementById('egroup_id').innerHTML=datas[0];
											document.getElementById('egroup_name').value=datas[1];
											document.getElementById('egroup_color').value=datas[2];
											document.getElementById('escript_id').value=datas[8];
											document.getElementById('eactive').value=datas[3];
											document.getElementById('eweb_form_address').value=datas[4];
											document.getElementById('enext_agent_call').value=datas[6];
											document.getElementById('equeue_priority').value=datas[29];
											document.getElementById('eon_hook_ring_time').value=datas[101];
											document.getElementById('efronter_display').value=datas[7];
											document.getElementById('eget_call_launch').value=datas[9];
											document.getElementById('exferconf_a_dtmf').value=datas[10];
											document.getElementById('exferconf_a_number').value=datas[11];
											document.getElementById('exferconf_b_dtmf').value=datas[12];
											document.getElementById('exferconf_b_number').value=datas[13];
											document.getElementById('exferconf_c_number').value=datas[65];
											document.getElementById('exferconf_d_number').value=datas[66];
											document.getElementById('exferconf_e_number').value=datas[67];
											document.getElementById('etimer_action').value=datas[60];
											document.getElementById('etimer_action_message').value=datas[61];
											document.getElementById('etimer_action_seconds').value=datas[62];
											document.getElementById('etimer_action_destination').value=datas[95];
											document.getElementById('edrop_call_seconds').value=datas[14];
											document.getElementById('edrop_action').value=datas[15];
											document.getElementById('edrop_exten').value=datas[16];
											document.getElementById('evoicemail_ext').value=datas[5];
											document.getElementById('eafter_hours_action').value=datas[18];
											document.getElementById('eignore_list_script_override').value=datas[68]; 
											document.getElementById('after_hours_message_filename').value=datas[19];  
											document.getElementById('eafter_hours_exten').value=datas[20]; 
											document.getElementById('after_hours_voicemail').value=datas[21];  
											document.getElementById('eno_agent_no_queue').value=datas[56]; 
											document.getElementById('no_agent_action').value=datas[57]; 
											document.getElementById('welcome_message_filename').value=datas[22];  
											document.getElementById('eplay_welcome_message').value=datas[52];
											document.getElementById('moh_context').value=datas[23];      
											document.getElementById('onhold_prompt_filename').value=datas[24];
											document.getElementById('prompt_interval').value=datas[25];
											document.getElementById('onhold_prompt_no_block').value=datas[75];
											document.getElementById('onhold_prompt_seconds').value=datas[76];
											document.getElementById('play_place_in_line').value=datas[41];
											document.getElementById('play_estimate_hold_time').value=datas[42];
											document.getElementById('calculate_estimated_hold_seconds').value=datas[96];
											document.getElementById('eht_minimum_prompt_filename').value=datas[98];
											document.getElementById('eht_minimum_prompt_no_block').value=datas[99];
											document.getElementById('eht_minimum_prompt_seconds').value=datas[100];
											document.getElementById('wait_time_option').value=datas[82];
											document.getElementById('wait_time_second_option').value=datas[83];
											document.getElementById('wait_time_third_option').value=datas[84];
											document.getElementById('wait_time_option_seconds').value=datas[85];
											document.getElementById('wait_time_option_exten').value=datas[86];
											document.getElementById('wait_time_option_voicemail').value=datas[87];
											document.getElementById('wait_time_option_press_filename').value=datas[92];
											document.getElementById('wait_time_option_no_block').value=datas[93];
											document.getElementById('wait_time_option_prompt_seconds').value=datas[94];
											document.getElementById('wait_time_option_callback_filename').value=datas[90];
											document.getElementById('wait_time_option_callback_list_id').value=datas[91];
											document.getElementById('wait_hold_option_priority').value=datas[81];
											document.getElementById('hold_time_option').value=datas[43];
											document.getElementById('hold_time_second_option').value=datas[79];
											document.getElementById('hold_time_option_seconds').value = datas[44];
											document.getElementById('hold_time_option_minimum').value = datas[72];
											document.getElementById('hold_time_option_exten').value=datas[45];
											document.getElementById('hold_time_option_voicemail').value=datas[46];
											document.getElementById('hold_time_option_press_filename').value=datas[73];
											document.getElementById('hold_time_option_no_block').value=datas[77];
											document.getElementById('hold_time_option_prompt_seconds').value=datas[78];
											document.getElementById('hold_time_option_callback_filename').value=datas[48];
											document.getElementById('hold_time_option_callback_list_id').value=datas[49];
											document.getElementById('agent_alert_exten').value=datas[26];
											document.getElementById('agent_alert_delay').value=datas[27];
											document.getElementById('no_delay_call_route').value=datas[51];
											document.getElementById('ingroup_recording_override').value=datas[31];
											document.getElementById('ingroup_rec_filename').value =datas[32];
											document.getElementById('answer_sec_pct_rt_stat_one').value=datas[53];
											document.getElementById('answer_sec_pct_rt_stat_two').value=datas[54];
											document.getElementById('start_call_url').value=datas[63];
											document.getElementById('dispo_call_url').value=datas[64];
											document.getElementById('add_lead_url').value=datas[97];
											document.getElementById('extension_appended_cidname').value = datas[69];
											document.getElementById('uniqueid_status_display').value = datas[70];
											document.getElementById('uniqueid_status_prefix').value = datas[71];
 											document.getElementById('edrop_inbound_group').value=datas[30];
 											document.getElementById('ecall_time_id').value=datas[17];
											document.getElementById('afterhours_xfer_group').value=datas[33];
											document.getElementById('wait_time_option_callmenu').value=datas[89];
											document.getElementById('wait_time_option_xfer_group').value=datas[88];
											document.getElementById('hold_time_option_callmenu').value=datas[74];
											document.getElementById('hold_time_option_xfer_group').value=datas[47];
											document.getElementById('default_xfer_group').value=datas[28];
											document.getElementById('hold_recall_xfer_group').value=datas[50];
											document.getElementById('default_group_alias').value=datas[55];
 									} 
 											
 								  $('#overlay').fadeIn('fast',function(){
 								  		$('#box').show('fast');
	                  			$('#box').animate({'top':'70px'},500);
			             			});
	             				 
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	                  					
	              					});
								 	});		
						 });	
	}
	
	function editpost(groupID) {

			document.getElementById('showval').value=groupID;
			document.getElementById('showvaledit').value=groupID;

			var itemsumit = $('#edit_go_listfrm').serialize();
				$.post("<?=$base?>index.php/go_ingroup/editsubmit", { itemsumit: itemsumit, action: "editlistfinal" },
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
			
				var confirmmessage=confirm("Confirm to delete "+listID+"?");
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
	
   function addlistoverlay() {

	 $('#overlay').fadeIn('fast',function(){
	                  			$('#boxaddlist').animate({'top':'70px'},500);
		             			});
		
	              					/*$('#boxaddlist').animate({'top':'-550px'},500,function(){
	                  			$('#overlay').fadeOut('fast');
	              					});*/
		
	
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
  
  function launch_chooser(fieldname,stage,vposition,defvalue) {
                
		$.post("<?=$base?>index.php/go_audiostore/chooser", { action: "sounds_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
		function(data){
/*var datas = data.split("--");
document.getElementById("tdfilename").innerHTML=datas[0]+"<br>";
document.getElementById("tddate").innerHTML=datas[1]+"<br>";
document.getElementById("tdsize").innerHTML=datas[2]+"<br>";*/
	document.getElementById("divsoundlists").innerHTML=data;
	    				
		});	
	}

 	function ahmf(idval) {
		document.getElementById("after_hours_message_filename").value=idval;
 	}
 	
 	function wmf(idval) {
 		document.getElementById("welcome_message_filename").value=idval;
 	}
 	
 	function opf(idval) {
 		document.getElementById("onhold_prompt_filename").value=idval;
 	}
 	
 	function empf(idval) {
 		document.getElementById("eht_minimum_prompt_filename").value=idval;
 	}
 	
 	function wtopf(idval) {
 		document.getElementById("wait_time_option_press_filename").value=idval;
	}
	
 	function wtocf(idval) {
 		document.getElementById("wait_time_option_callback_filename").value=idval;
	}

	function htopf(idval) {
 		document.getElementById("hold_time_option_press_filename").value=idval;
	}
	
	function htocf(idval) {
 		document.getElementById("hold_time_option_callback_filename").value=idval;
	}
	
	function aae(idval) {
 		document.getElementById("agent_alert_exten").value=idval;
	}
	
	function launch_vm_chooser(fieldname,stage,vposition,defvalue) {

	    $.post("<?=$base?>index.php/go_ingroup/chooser", { action: "vm_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
		function(data){
			
			if(fieldname=="evoicemail_ext"){
	 			document.getElementById("divvoicemail_ext").innerHTML=data;
			} 
			
			if(fieldname=="after_hours_voicemail"){
	 			document.getElementById("divafter_hours_voicemail").innerHTML=data;
			} 
			      
			if(fieldname=="wait_time_option_voicemail"){
	 			document.getElementById("divwait_time_option_voicemail").innerHTML=data;
			} 
			
			if(fieldname=="hold_time_option_voicemail"){
	 			document.getElementById("divhold_time_option_voicemail").innerHTML=data;
			} 
	    				
		});	
	}

	function voiceexten(idval) {
		document.getElementById("evoicemail_ext").value=idval;
	}
	
	function ahv(idval) {
		document.getElementById("after_hours_voicemail").value=idval;
	}
	
	function wtov(idval) {
		document.getElementById("wait_time_option_voicemail").value=idval;
	}
	
	function htov(idval) {
		document.getElementById("hold_time_option_voicemail").value=idval;
	}
	
	
	function launch_moh_chooser(fieldname,stage,vposition,defvalue) {
                
			$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "moh_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
			function(data){
				
				if(fieldname=="moh_context"){
		 			document.getElementById("divmoh_context").innerHTML=data;
				} 
		    				
			});	

	}

	function mohc(idval) {
		document.getElementById("moh_context").value=idval;
	}
	
	function checkdatas(groupID) {
		
		var itemdatas = $('#agentrankform').serialize();
		$.post("<?=$base?>index.php/go_ingroup/checkagentrank", { itemrank: itemdatas, action: "getcheckagentrank", idgroup: groupID  },
			function(data){
				//alert(data);			
				/*var datas = data.split(":");
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
 								}*/
							 		
				});	
				
				

	}
	
/*	function  test(musicsrc) {
		
	var musicsrc="http://192.168.100.112/sounds/6889980794_message4.wav"
if (navigator.appName=="Microsoft Internet Explorer")
document.write('<bgsound src='+'"'+musicsrc+'"'+' loop="infinite">')
else
document.write('<embed src=\"'+musicsrc+'\" hidden="true" border="0" width="20" height="20" autostart="false" loop="true">')

}*/
	


</script>
<!-- end Javascript section -->

		<!-- CSS section -->
		<style type="text/css">
	
                 body{height:90%;}
	
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
	            background:transparent url(../../../img/images/go_list/overlay.png) repeat top left;
	            position:fixed;
	            top:0px;
	            bottom:0px;
	            left:0px;
	            right:0px;
	            z-index:100;
	        }
	        .box{
	            position:fixed;
	            top:-550px;
/*	            top:-200px;*/
	            left: 15%;
	            right: 30%;
	            background-color: #EFFBEF;
	            color:#7F7F7F;
	            padding:20px;
	          /*  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;*/
	            z-index:101;
	            width: 70%;
	            
	        }
	        .boxaddlist{
	             position:fixed;
	            top:-550px;
/*	            top:-200px;*/
	            left:20%;
	            right:30%;
	            background-color: #EFFBEF;
	            color:#7F7F7F;
	            padding:20px;
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
	        
	        a.boxclose{
	            float:right;
	            width:26px;
	            height:26px;
	            /*background:transparent url(../../../img/images/go_list/cancel.png) repeat top left;*/
	            background:transparent url(<? echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	            margin-top:-30px;
	            margin-right:-30px;
	            cursor:pointer;
	        }
	        
			.nowrap { 
			   background: white;
				font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;
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
				font-weight: bold;
				color: black;
				font-size: 15px;	
			}
			
			.modify-value {
				color: black;	
			}
			.lblback {
				background:#E0F8E0;
			}
			
			.tableedit {
				border-top: 1px double; rgb(208,208,208);
			}
			
			
			.tableeditingroup {
				border-top: 1px double; rgb(208,208,208);
			}

			.tablenodouble {
				border-top: 0px double; rgb(208,208,208);
			}

			td {
			}
			.uploadborder {
				border:  1px solid #afe14c;
				margin: 10px 0;
				padding: 20px 10px;
			}
			.thheader {
				/*background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/bg.gif);*/
        		background-repeat: no-repeat;
        		background-position: center right;
        		cursor: pointer;
			font-family: Verdana,Arial,Helvetica,sans-serif; 
			font-size: 13px; 
			font-stretch: normal;		
			
			}  
			.tr1 td{ background:#E0F8E0;  color:#000;  border-top: 1px dashed rgb(208,208,208); }
			.tr2 td{ background:#EFFBEF; color:#000;  border-top: 1px dashed rgb(208,208,208); }
			.tredit td{ background:#EFFBEF; color:#000;  border-bottom: 1px dashed rgb(208,208,208); }   
			
			A:link {text-decoration: none; color: black;}
			A:visited {text-decoration: none; color: black;}
			A:active {text-decoration: none; color: black;}
			A:hover {text-decoration: underline overline; color: red;}

table.tablesorter .even {
         background-color: #EFFBEF;
}
table.tablesorter .odd {
         background-color: #E0F8E0;
}
 

#showAllLists {
	color: #F00;
	font-size: 10px;
	cursor: pointer;
}

		</style>
		<!-- end CSS section -->

<!-- begin body -->
<!--<body onload="launch_chooser('sample_prompt','date',30);">-->
<body>

<div id='outbody' class="wrap">
    <div id="icon-voicefile" class="icon32"></div>
    <div style="float: right;margin-top:15px;margin-right:25px;"><?=form_open(base_url().'audiostore',array('id'=>'search_submit')) ?><span id="showAllLists" style="display: none">[Clear Search]</span>&nbsp;<?=form_input('search_list',null,'id="search_list" size="40" maxlength="100" placeholder="Search '.$bannertitle.' (case sensitive)"') ?>&nbsp;<img src="<?=base_url()."img/spotlight-black.png"; ?>" id="search_list_button" style="cursor: pointer;" /><?=form_close() ?></div>
    <h2 style=""><?=$bannertitle ?></h2>
    
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">

    	    <!-- start box -->
            <div class="postbox-container" style="width:100%;">
                <div class="meta-box-sortables ui-sortables">
                    <!-- List holder-->
                    <div class="postbox" style="width: 99%; min-width: 1200px;">
                        <div class="">
                            
                        </div>
                        <div class="hndle" >
                            <span style="">&nbsp;<!--Voice Files--></span>
                        
                       <!-- <span style="margin-left: 73%;">
								<a id="activator" class="activator"  onClick="addlistoverlay();" style="text-decoration: none;" title="CREATE INGROUP"><b>Add New In-group</b>  <img src="http://192.168.100.112/img/cross.png" style="height:14px; vertical-align:middle"></a></span>-->
                        </div>
                        <div class="inside">

<div id="tabs" class=""  style="border: none;">


	<div id="tabs-1">

				<!-- Ingroup TAB -->
				<div id="showlist" style="display: block;">
			

<?php if ($permissions->voicefile_upload!='N') { ?>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="stage" value="upload">
<!--<input type="hidden" name="sample_prompt" id=sample_prompt value="">-->

<table align=center width="100%" border="0" cellpadding="5" cellspacing="0" >
<tr><td align="center" colspan="2">We STRONGLY recommend uploading only 16bit Mono 8k PCM WAV audio files(.wav)</td></tr>
  <tr>
        <td colspan="2" align="center"><B>Voice File to Upload:</B>
        <input type="file" name="audiofile" value="" accept="audio/*"><input type="submit" name="submit" style="cursor:pointer;" value="UPLOAD"></td>
  </tr>
  <tr>
        <td align="center" colspan="2"></td>
  </tr>
  <tr><td align=center colspan="2"> <?=$uploadfail?> </td><td align=right></td></tr>
  
 
</table>
</form>
<?php } ?>

<br>

<table id="filestable" class="tablesorter" border="0" cellpadding="1" cellspacing="2" width="100%" bgcolor="white" >
<thead>
<tr align="left" class="nowrap">
	<th class="thheader" align="center">No</th>
        <th class="thheader" align="center"><b>FILENAME</b></th>
        <th class="thheader" align="center"><b>DATE</b></th>
        <th class="thheader" align="center"><b>SIZE</b></th>
        <th class="thheader" align="center"><b>PLAY</b></th>
</tr>
</thead>
<?php
echo $voicefilestable;
?>
</table> 

<div id="divsoundlists"></div>
 
					</div>
				<!-- end view -->
				
				
				<div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="boxaddlist" id="boxaddlist">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closemeadd();"></a>
				<!-- start add -->
				
				
			<!--	<div id="addlist" style="display: block;">
					<form  method="POST" id="go_listfrm" name="go_listfrm">
				   	<input type="hidden" id="selectval" name="selectval" value="">
				   	
				   <div align="left" class="title-header" style="font-family: Verdana;">Create new In-Group</div>
					
					
			
					</form>
					</div>-->
				</div>
				<!-- end add -->
				
				
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
					<div align="left" class="title-header"> Modify In-Group: <label id="egroup_id"></label></div>
					
					<div align="left">					
					<!--<label class="modify-value">Change Date:</label>-->
					<!--<table width="100%" class="tableedit">
						<tr><td align="left"><div id="cdates"></div></td><td align="right"><div id="lcdates"></div></td></tr>
						<tr><td>Group ID: </td><td align=left><b></b></td></tr>
					</table>
					-->
					
					</div>
					<div style="width: auto; height:500px; overflow:scroll;">
					<?php
					
					## callmenu pulldown
					foreach($callmenupulldown as $callmenupulldownInfo){
						$menu_id = $callmenupulldownInfo->menu_id;
						$menu_name = $callmenupulldownInfo->menu_name;
						
						$Xmenuslist .= "<option ";
						$Xmenuslist .= "value=\"$menu_id\">$menu_id - $menu_name</option>\n";
					}			
					
						if ($Xmenus_selected < 1) 
						{
							$Xmenuslist .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";
						}
					
					## ingroup pulldown
					$Xgroups_menu='';
					$Xgroups_selected=0;
					$Dgroups_menu='';
					$Dgroups_selected=0;
					$Agroups_menu='';
					$Agroups_selected=0;
					$Hgroups_menu='';
					$Hgroups_selected=0;
					$Tgroups_menu='';
					$Tgroups_selected=0;
												
						  foreach($ingrouppulldown as $ingrouppulldownInfo){
						  			$group_id = $ingrouppulldownInfo->group_id;
						  			$group_name = $ingrouppulldownInfo->group_name;
						  			
									$Xgroups_menu .= "<option ";
									$Dgroups_menu .= "<option ";
									$Agroups_menu .= "<option ";
									$Tgroups_menu .= "<option ";
									$Hgroups_menu .= "<option ";
									
									if ($default_xfer_group == "$group_id") 
										{
										$Xgroups_menu .= "SELECTED ";
										$Xgroups_selected++;
										}
									if ($drop_inbound_group == "$group_id") 
										{
										$Dgroups_menu .= "SELECTED ";
										$Dgroups_selected++;
										}
									if ($afterhours_xfer_group == "$group_id") 
										{
										$Agroups_menu .= "SELECTED ";
										$Agroups_selected++;
										}
									if ($hold_time_option_xfer_group == "$group_id") 
										{
										$Tgroups_menu .= "SELECTED ";
										$Tgroups_selected++;
										}
									if ($hold_recall_xfer_group == "$group_id") 
										{
										$Hgroups_menu .= "SELECTED ";
										$Hgroups_selected++;
										}
									$Xgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
									$Dgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
									/*if ($group_id!=$group_id)
										{
*/										$Agroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
										$Tgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
										$Hgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
	//									}
						  			
						  } // end foreach
					
					  		if ($Xgroups_selected < 1) 
								{$Xgroups_menu .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";}
							else 
								{$Xgroups_menu .= "<option value=\"---NONE---\">---NONE---</option>\n";}
							if ($Dgroups_selected < 1) 
								{$Dgroups_menu .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";}
							else 
								{$Dgroups_menu .= "<option value=\"---NONE---\">---NONE---</option>\n";}
							if ($Agroups_selected < 1) 
								{$Agroups_menu .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";}
							else 
								{$Agroups_menu .= "<option value=\"---NONE---\">---NONE---</option>\n";}
							if ($Tgroups_selected < 1) 
								{$Tgroups_menu .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";}
							else 
								{$Tgroups_menu .= "<option value=\"---NONE---\">---NONE---</option>\n";}
							if ($Hgroups_selected < 1) 
								{$Hgroups_menu .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";}
							else 
								{$Hgroups_menu .= "<option value=\"---NONE---\">---NONE---</option>\n";}
					
		                echo "<center><TABLE width=100% class=\"tableeditingroup\">\n";

		                echo "<tr><td>Description: </td><td align=left><input type=text name=group_name id=egroup_name size=30 maxlength=30></td></tr>\n";

		                echo "<tr><td>Color: </td><td align=left id=\"group_color_td\"><input class=color type=text name=group_color id=egroup_color size=7 maxlength=7></td></tr>\n";

		                echo "<tr><td>Active: </td><td align=left><select size=1 name=active id=eactive><option>Y</option><option>N</option></select></td></tr>\n";

		                echo "<tr><td>Web Form: </td><td align=left><input type=text id=eweb_form_address name=web_form_address size=70 maxlength=500></td></tr>\n";

		                echo "<tr><td>Next Agent Call: </td><td align=left><select size=1 name=next_agent_call id=enext_agent_call><option >random</option><option>oldest_call_start</option><option>oldest_call_finish</option><option>overall_user_level</option><option>inbound_group_rank</option><option>campaign_rank</option><option>fewest_calls</option><option>fewest_calls_campaign</option><option>longest_wait_time</option><option>ring_all</option></select></td></tr>\n";
		
		                echo "<tr><td>Queue Priority: </td><td align=left><select size=1 name=queue_priority id=equeue_priority>\n";
		                $n=99;
		                while ($n>=-99) {
		                        $dtl = 'Even';
		                        if ($n<0) {$dtl = 'Lower';}
		                        if ($n>0) {$dtl = 'Higher';}
		                        if ($n == $queue_priority)
		                                {echo "<option SELECTED value=\"$n\">$n - $dtl</option>\n";}
		                        else
		                                {echo "<option value=\"$n\">$n - $dtl</option>\n";}
		                        $n--;
		                }
		                echo "</select> </td></tr>\n";
		                
		                echo "<tr><td>On-Hook Ring Time: </td><td align=left><input type=text name=on_hook_ring_time id=eon_hook_ring_time size=5 maxlength=4 ></td></tr>\n";
		
		                echo "<tr><td>Fronter Display: </td><td align=left><select size=1 name=fronter_display id=efronter_display><option>Y</option><option>N</option></select></td></tr>\n";
		                
		                echo "<tr><td>Script: </td><td align=left>";
		                		$scripts_listS="<option value=\"\">NONE</option>\n";
													foreach($scriptlists as $scriptlistsInfo){
														$script_id = $scriptlistsInfo->script_id;
														$script_name = $scriptlistsInfo->script_name;
														$scripts_listS .= "<option value=\"$script_id\">$script_id - $script_name</option>\n";
														$scriptname_listS["$script_id"] = "$script_name";
														//echo '<option value="'.$script_id.'">'.$script_id.'---'.$script_name.'</option>';
													}
						   echo "<select size=\"1\" name=\"ingroup_script\" id=\"escript_id\">";
						   echo "$scripts_listS";
						   echo "</select></td></tr>";
						  	
						   echo "<tr><td>Ignore List Script Override: </td><td align=left><select size=1 name=ignore_list_script_override id=eignore_list_script_override><option>Y</option><option>N</option></select></td></tr>\n";
		
						   echo "<tr><td>Get Call Launch: </td><td> <select name=get_call_launch id=eget_call_launch><option selected=\"\">NONE</option><option>SCRIPT</option>					<option>WEBFORM</option><option>FORM</option></select></td></tr>";
		
		                echo "<tr><td>Transfer-Conf DTMF 1: </td><td align=left><input type=text name=xferconf_a_dtmf id=exferconf_a_dtmf size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr><td>Transfer-Conf Number 1: </td><td align=left><input type=text name=xferconf_a_number id=exferconf_a_number size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr><td>Transfer-Conf DTMF 2: </td><td align=left><input type=text name=xferconf_b_dtmf id=exferconf_b_dtmf size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr><td>Transfer-Conf Number 2: </td><td align=left><input type=text name=xferconf_b_number id=exferconf_b_number size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr><td>Transfer-Conf Number 3: </td><td align=left><input type=text name=xferconf_c_number id=exferconf_c_number size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr><td>Transfer-Conf Number 4: </td><td align=left><input type=text name=xferconf_d_number id=exferconf_d_number size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr><td>Transfer-Conf Number 5: </td><td align=left><input type=text name=xferconf_e_number id=exferconf_e_number size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr><td>Timer Action: </td><td align=left><select size=1 name=timer_action id=etimer_action><option selected>NONE</option><option>D1_DIAL</option><option>D2_DIAL</option><option>D3_DIAL</option><option>D4_DIAL</option><option>D5_DIAL</option><option>MESSAGE_ONLY</option><option>WEBFORM</option><option>HANGUP</option><option>CALLMENU</option><option>EXTENSION</option><option>IN_GROUP</option></select></td></tr>\n";
		
		                echo "<tr><td>Timer Action Message: </td><td align=left><input type=text name=timer_action_message id=etimer_action_message size=50 maxlength=255></td></tr>\n";
		
		                echo "<tr><td>Timer Action Seconds: </td><td align=left><input type=text name=timer_action_seconds id=etimer_action_seconds size=10 maxlength=10></td></tr>\n";
		
		                echo "<tr><td>Timer Action Destination: </td><td align=left><input type=text name=timer_action_destination id=etimer_action_destination size=25 maxlength=30></td></tr>\n";
		
		                echo "<tr><td>Drop Call Seconds: </td><td align=left><input type=text name=drop_call_seconds id=edrop_call_seconds size=5 maxlength=4></td></tr>\n";
		
		                echo "<tr><td>Drop Action: </td><td align=left><select size=1 name=drop_action id=edrop_action><option>HANGUP</option><option>MESSAGE</option><option>VOICEMAIL</option><option>IN_GROUP</option></select></td></tr>\n";
		
		                echo "<tr><td>Drop Exten: </td><td align=left><input type=text name=drop_exten id=edrop_exten size=10 maxlength=20></td></tr>\n";
				
		                echo "<tr><td>Voicemail: </td><td align=left><input type=text name=voicemail_ext id=evoicemail_ext size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('evoicemail_ext','vm',500,document.getElementById('evoicemail_ext').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</a><div id=\"divvoicemail_ext\"></div></td></tr>\n";
		
		                echo "<tr bgcolor=#99FFCC><td>Drop Transfer Group: </td><td align=left><select size=1 name=drop_inbound_group id=edrop_inbound_group>";
		                echo "$Dgroups_menu";
		                echo "</select></td></tr>\n";
		
		
		                echo "<tr><td>Call Time: </td><td align=left><select size=1 name=call_time_id id=ecall_time_id>\n";

		                foreach($calltimespulldown as $calltimespulldownInfo){
			                	$call_time_id = $calltimespulldownInfo->call_time_id;
			                	$call_time_name = $calltimespulldownInfo->call_time_name;
		         				$selected_time="selected";
                				$call_times_list .= "<option value=\"$call_time_id\" $selected_time>$call_time_id - $call_time_name</option>\n";

                		  }
                		  
		                echo "$call_times_list";
		                echo "<option selected value=\"$call_time_id\">$call_time_id - $call_timename_list[$call_time_id]</option>\n";
		                echo "</select></td></tr>\n";
		
		                echo "<tr><td>After Hours Action: </td><td align=left><select size=1 name=after_hours_action id=eafter_hours_action><option>HANGUP</option><option>MESSAGE</option><option>EXTENSION</option><option>VOICEMAIL</option><option>IN_GROUP</option></select></td></tr>\n";
		
		                echo "<tr><td>After Hours Message Filename: </td><td align=left><input type=text name=after_hours_message_filename id=after_hours_message_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('after_hours_message_filename','date',600,document.getElementById('after_hours_message_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a><div id=\"divafter_hours_message_filename\"></div> </td></tr>\n";
		
		                echo "<t><td>After Hours Extension: </td><td align=left><input type=text name=after_hours_exten id=eafter_hours_exten size=10 maxlength=20></td></tr>\n";
		
		                echo "<tr><td>After Hours Voicemail: </td><td align=left><input type=text name=after_hours_voicemail id=after_hours_voicemail size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('after_hours_voicemail','vm',700,document.getElementById('after_hours_voicemail').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</font></a><div id=\"divafter_hours_voicemail\"></div></td></tr>\n";
		
		                echo "<tr><td>After Hours Transfer Group: </td><td align=left><select size=1 name=afterhours_xfer_group id=afterhours_xfer_group>";
		                echo "<option value=\"\">--NONE--</option>\n";
		                echo "$Agroups_menu";
		                echo "</select></td></tr>\n";
		
		                echo "<tr><td>No Agents No Queueing: </td><td align=left><select size=1 name=no_agent_no_queue id=eno_agent_no_queue><option>Y</option><option>N</option><option>NO_PAUSED</option></select></td></tr>\n";
		
		                echo "<tr><td>No Agent No Queue Action: </td><td align=left><select size=1 name=no_agent_action id=no_agent_action onChange=\"dynamic_call_action('no_agent_action','$no_agent_action','$no_agent_action_value','600');\"><option>CALLMENU</option><option>INGROUP</option><option>DID</option><option>MESSAGE</option><option>EXTENSION</option><option>VOICEMAIL</option></select>\n";				
						
							echo "<tr><td>Welcome Message Filename: </td><td align=left><input type=text name=welcome_message_filename id=welcome_message_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('welcome_message_filename','date',800,document.getElementById('welcome_message_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divwelcome_message_filename\"></div> </td></tr>\n";
					
							echo "<tr><td>Play Welcome Message: </td><td align=left><select size=1 name=play_welcome_message id=eplay_welcome_message><option>ALWAYS</option><option>NEVER</option><option>IF_WAIT_ONLY</option><option>YES_UNLESS_NODELAY</option></select></td></tr>\n";
					
							echo "<tr><td>Music On Hold Context: </td><td align=left><input type=text name=moh_context id=moh_context size=30 maxlength=50> <a href=\"javascript:launch_moh_chooser('moh_context','moh',800,document.getElementById('moh_context').value);\"><FONT color=\"blue\">[ Moh Chooser ]</font></a> <div id=\"divmoh_context\"></div></td></tr>\n";
					
							echo "<tr><td>On Hold Prompt Filename: </td><td align=left><input type=text name=onhold_prompt_filename id=onhold_prompt_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('onhold_prompt_filename','date',800,document.getElementById('onhold_prompt_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divonhold_prompt_filename\"></div></td></tr>\n";
					
							echo "<tr><td>On Hold Prompt Interval: </td><td align=left><input type=text name=prompt_interval id=prompt_interval size=5 maxlength=5></td></tr>\n";
					
							echo "<tr><td>On Hold Prompt No Block: </td><td align=left><select size=1 name=onhold_prompt_no_block id=onhold_prompt_no_block><option>N</option><option>Y</option></select></td></tr>\n";
					
							echo "<tr><td>On Hold Prompt Seconds: </td><td align=left><input type=text name=onhold_prompt_seconds id=onhold_prompt_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr><td>Play Place in Line: </td><td align=left><select size=1 name=play_place_in_line id=play_place_in_line><option>Y</option><option>N</option></select></td></tr>\n";
					
							echo "<tr><td>Play Estimated Hold Time: </td><td align=left><select size=1 name=play_estimate_hold_time id=play_estimate_hold_time><option>Y</option><option>N</option></select></td></tr>\n";
					
							echo "<tr><td>Calculate Estimated Hold Seconds: </td><td align=left><input type=text name=calculate_estimated_hold_seconds id=calculate_estimated_hold_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr><td>Estimated Hold Time Minimum Filename: </td><td align=left><input type=text name=eht_minimum_prompt_filename id=eht_minimum_prompt_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('eht_minimum_prompt_filename','date',800,document.getElementById('eht_minimum_prompt_filename').value);\"><FONT color=\"blue\"> [ Audio Chooser ]</font></a> <div id=\"diveht_minimum_prompt_filename\"></div> </td></tr>\n";
					
							echo "<tr><td>Estimated Hold Time Minimum Prompt No Block: </td><td align=left><select size=1 name=eht_minimum_prompt_no_block id=eht_minimum_prompt_no_block><option>N</option><option>Y</option></select></td></tr>\n";
					
							echo "<tr><td>Estimated Hold Time Minimum Prompt Seconds: </td><td align=left><input type=text name=eht_minimum_prompt_seconds id=eht_minimum_prompt_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr><td>Wait Time Option: </td><td align=left><select size=1 name=wait_time_option id=wait_time_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr><td>Wait Time Second Option: </td><td align=left><select size=1 name=wait_time_second_option id=wait_time_second_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr><td>Wait Time Third Option: </td><td align=left><select size=1 name=wait_time_third_option id=wait_time_third_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr><td>Wait Time Option Seconds: </td><td align=left><input type=text name=wait_time_option_seconds id=wait_time_option_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr><td>Wait Time Option Extension: </td><td align=left><input type=text name=wait_time_option_exten id=wait_time_option_exten size=20 maxlength=20></td></tr>\n";
					
							echo "<tr><td>Wait Time Option Callmenu: </td><td align=left><select size=1 name=wait_time_option_callmenu id=wait_time_option_callmenu>\n";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Xmenuslist";
							echo "</select></td></tr>\n";
					
							echo "<tr><td>Wait Time Option Voicemail: </td><td align=left><input type=text name=wait_time_option_voicemail id=wait_time_option_voicemail size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('wait_time_option_voicemail','vm',1100,document.getElementById('wait_time_option_voicemail').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</font></a><div id=\"divwait_time_option_voicemail\"></div> </td></tr>\n";
					
							echo "<tr><td>Wait Time Option Transfer In-Group: </td><td align=left><select size=1 name=wait_time_option_xfer_group id=wait_time_option_xfer_group>";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Tgroups_menu";
							echo "</select></td></tr>\n";
					
							echo "<tr><td>Wait Time Option Press Filename: </td><td align=left><input type=text name=wait_time_option_press_filename id=wait_time_option_press_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('wait_time_option_press_filename','date',1200,document.getElementById('wait_time_option_press_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divwait_time_option_press_filename\"></div> </td></tr>\n";
					
							echo "<tr><td>Wait Time Option Press No Block: </td><td align=left><select size=1 name=wait_time_option_no_block id=wait_time_option_no_block><option>N</option><option>Y</option></select></td></tr>\n";
					
							echo "<tr><td>Wait Time Option Press Filename Seconds: </td><td align=left><input type=text name=wait_time_option_prompt_seconds id=wait_time_option_prompt_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr><td>Wait Time Option After Press Filename: </td><td align=left><input type=text name=wait_time_option_callback_filename id=wait_time_option_callback_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('wait_time_option_callback_filename','date',1300,document.getElementById('wait_time_option_callback_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divwait_time_option_callback_filename\"></div></td></tr>\n";
					
							echo "<tr><td>Wait Time Option Callback List ID: </td><td align=left><input type=text name=wait_time_option_callback_list_id id=wait_time_option_callback_list_id size=14 maxlength=14></td></tr>\n";
					
							echo "<tr><td>Wait Hold Option Priority: </td><td align=left><select size=1 name=wait_hold_option_priority id=wait_hold_option_priority><option>WAIT</option><option>BOTH</option></select></td></tr>\n";
					
							echo "<tr><td>Estimated Hold Time Option: </td><td align=left><select size=1 name=hold_time_option id=hold_time_option><option>NONE</option><option>EXTENSION</option><option>CALL_MENU</option><option>VOICEMAIL</option><option>IN_GROUP</option><option>CALLERID_CALLBACK</option><option>DROP_ACTION</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr><td>Hold Time Second Option: </td><td align=left><select size=1 name=hold_time_second_option id=hold_time_second_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr><td>Hold Time Third Option: </td><td align=left><select size=1 name=hold_time_third_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr><td>Hold Time Option Seconds: </td><td align=left><input type=text name=hold_time_option_seconds id=hold_time_option_seconds size=5 maxlength=5></td></tr>\n";

							echo "<tr><td>Hold Time Option Minimum: </td><td align=left><input type=text name=hold_time_option_minimum id=hold_time_option_minimum size=5 maxlength=5></td></tr>\n";
					
							echo "<tr><td>Hold Time Option Extension: </td><td align=left><input type=text name=hold_time_option_exten id=hold_time_option_exten size=20 maxlength=20></td></tr>\n";
					
							echo "<tr><td>Hold Time Option Callmenu: </td><td align=left><select size=1 name=hold_time_option_callmenu id=hold_time_option_callmenu>\n";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Xmenuslist";
							echo "</select></td></tr>\n";
					
							echo "<tr><td>Hold Time Option Voicemail: </td><td align=left><input type=text name=hold_time_option_voicemail id=hold_time_option_voicemail size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('hold_time_option_voicemail','vm',1100,document.getElementById('hold_time_option_voicemail').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</font></a><div id=\"divhold_time_option_voicemail\"></div> </td></tr>\n";
					
							echo "<tr><td>Hold Time Option Transfer In-Group: </td><td align=left><select size=1 name=hold_time_option_xfer_group id=hold_time_option_xfer_group>";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Tgroups_menu";
							echo "</select></td></tr>\n";
					
							echo "<tr><td>Hold Time Option Press Filename: </td><td align=left><input type=text name=hold_time_option_press_filename id=hold_time_option_press_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('hold_time_option_press_filename','date',1200,document.getElementById('hold_time_option_press_filename').value);\"><FONT color=\"blue\"><FONT color=\"blue\">[ Audio Chooser]</font></a> <div id=\"divhold_time_option_press_filename\"></div></td></tr>\n";
					
							echo "<tr><td>Hold Time Option Press No Block: </td><td align=left><select size=1 name=hold_time_option_no_block id=hold_time_option_no_block><option>N</option><option>Y</option></select></td></tr>\n";
					
							echo "<tr><td>Hold Time Option Press Filename Seconds: </td><td align=left><input type=text name=hold_time_option_prompt_seconds id=hold_time_option_prompt_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr><td>Hold Time Option After Press Filename: </td><td align=left><input type=text name=hold_time_option_callback_filename id=hold_time_option_callback_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('hold_time_option_callback_filename','date',1300,document.getElementById('hold_time_option_callback_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a><div id=\"divhold_time_option_callback_filename\"></div> </td></tr>\n";
					
							echo "<tr><td>Hold Time Option Callback List ID: </td><td align=left><input type=text name=hold_time_option_callback_list_id id=hold_time_option_callback_list_id size=14 maxlength=14></td></tr>\n";
					
							echo "<tr><td>Agent Alert Filename: </td><td align=left><input type=text name=agent_alert_exten id=agent_alert_exten size=30 maxlength=100> <a href=\"javascript:launch_chooser('agent_alert_exten','date',1500,document.getElementById('agent_alert_exten').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divagent_alert_exten\"></div></td></tr>\n";
					
							echo "<tr><td>Agent Alert Delay: </td><td align=left><input type=text name=agent_alert_delay id=agent_alert_delay size=6 maxlength=6></td></tr>\n";
					
							echo "<tr><td>Default Transfer Group: </td><td align=left><select size=1 name=default_xfer_group id=default_xfer_group>";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Xgroups_menu";
							echo "</select></td></tr>\n";
						
							echo "<tr><td align=left>Default Group Alias: </td><td align=left><select size=1 name=default_group_alias id=default_group_alias>";
							## group alias pulldown
							foreach($groupaliaspulldown as $groupaliaspulldownInfo) {
								$group_alias_id = $groupaliaspulldownInfo->group_alias_id;
								$group_alias_name = $groupaliaspulldownInfo->group_alias_name;
						
								$group_alias_menu .= "value=\"$group_alias_id\">$group_alias_id - $group_alias_name</option>\n";
							}
					
							echo "<option value=\"\">--NONE--</option>";
							echo "$group_alias_menu";
							echo "</select></td></tr>\n";
							
							echo "<tr><td>Hold Recall Transfer In-Group: </td><td align=left><select size=1 name=hold_recall_xfer_group id=hold_recall_xfer_group>";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Hgroups_menu";
							echo "</select></td></tr>\n";
					
							echo "<tr><td>No Delay Call Route: </td><td align=left><select size=1 name=no_delay_call_route id=no_delay_call_route><option>Y</option><option>N</option></select></td></tr>\n";
					
							echo "<tr><td>In-Group Recording Override: </td><td align=left><select size=1 name=ingroup_recording_override id=ingroup_recording_override><option>DISABLED</option><option>NEVER</option><option>ONDEMAND</option><option>ALLCALLS</option><option>ALLFORCE</option></select></td></tr>\n";
					
							echo "<tr><td>In-Group Recording Filename: </td><td align=left><input type=text name=ingroup_rec_filename id=ingroup_rec_filename size=50 maxlength=50></td></tr>\n";
							
							echo "<tr><td>Stats Percent of Calls Answered Within X seconds 1: </td><td align=left><input type=text name=answer_sec_pct_rt_stat_one id=answer_sec_pct_rt_stat_one size=5 maxlength=5 ></td></tr>\n";
					
							echo "<tr><td>Stats Percent of Calls Answered Within X seconds 2: </td><td align=left><input type=text name=answer_sec_pct_rt_stat_two id=answer_sec_pct_rt_stat_two size=5 maxlength=5></td></tr>\n";
					
							echo "<tr><td>Start Call URL: </td><td align=left><input type=text name=start_call_url id=start_call_url size=70 maxlength=2000 ></td></tr>\n";
					
							echo "<tr><td>Dispo Call URL: </td><td align=left><input type=text name=dispo_call_url id=dispo_call_url size=70 maxlength=2000 ></td></tr>\n";
					
							echo "<tr><td>Add Lead URL: </td><td align=left><input type=text name=add_lead_url id=add_lead_url size=70 maxlength=2000></td></tr>\n";
					
							echo "<tr><td>Extension Append CID: </td><td align=left><select size=1 name=extension_appended_cidname id=extension_appended_cidname><option>Y</option><option>N</option></select></td></tr>\n";
					
							echo "<tr><td>Uniqueid Status Display: </td><td align=left><select size=1 name=uniqueid_status_display id=uniqueid_status_display><option>DISABLED</option><option>ENABLED</option><option>ENABLED_PREFIX</option><option>ENABLED_PRESERVE</option></select></td></tr>\n";
					
							echo "<tr><td>Uniqueid Status Prefix: </td><td align=left><input type=text name=uniqueid_status_prefix id=uniqueid_status_prefix size=10 maxlength=50></td></tr>\n";
							
							
				?>
				<tr>					
				  <td colspan="2" align="center"><input type="button" name="editSUBMIT" value="MODIFY" onclick="editpost(document.getElementById('showvaledit').value);">
				</tr>
				</table>
				</form>
				<br><br>
				
				<form  method="POST" id="agentrankform" name="agentrankform">
				<?php
					echo "<center>\n";
					echo "<br><b><font color=black>AGENT RANKS FOR THIS INBOUND GROUP:</b></font><br>\n";
					
					echo "<TABLE width=\"60%\" cellspacing=3 class=\"tableeditingroup\" id=\"agentrankvalue\">\n";										
				
					echo "</TABLE></center>";
				?>
				</form>
								
				</div>
				</center>
				
				</div>	
				<!-- end edit -->
	</div> <!-- end LISTs -->
	
	
	
	
	
	
	
	
	<!-- CUSTOM FIELDS -->
<!--	<div id="tabs-2">
	<div id="showlist" style="display: block;">
	<br><br>
			<table width="100%" class="tableedit" cellspacing="1" cellpadding="1" border="0" style="margin-left:auto; margin-right:auto; width:95%; border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
				
				<tr align="center" class="nowrap">
					<TD><b>LIST ID</b></TD>
					<TD><b>DESCRIPTION</b></TD>
					<TD><b>STATUS</b></TD>
					<TD><b>CAMPAIGN ASSIGNED</b></TD>
					<TD><b>CUSTOM FIELDS</b></TD>
					<TD colspan="2" align="center"><b>MODIFY</b></TD>
				</tr>
				
				<?php
					echo $clist;
				?>
				
			</table>
		</div>
	
	</div>--><!-- end tab2 -->



	<!-- tab3 -->
	<!-- upload leads -->
<!--	<div id="tabs-3">
				<div id="uploadlist" style="display: block;" align="left">
				<form action="go_list" name="uploadform" method="post" onSubmit="ParseFileName()" enctype="multipart/form-data">
				<input type="hidden" name="leadsload" id="leadsloadok" value="ok">
				<input type="hidden" name="tabvalsel" id="tabvalsel" value="<?=$tabvalsel?>">
				<input type=hidden name='leadfile_name' id="leadfile_name" value="<?php echo $leadfile_name ?>">
				<b>Load Leads</b>
				<table class="tableedit" width="100%">
					<tr><td colspan="2">&nbsp;&nbsp;</td></tr>
				</table>
				
				<center>
				<table class="tablenodouble" width="80%">
					<tr>
						<td colspan="2">&nbsp;&nbsp;</td>
					</tr>
		  			<tr>
						<td><label class="modify-value">Load leads from this file:</label></td>
						<td><input type="file" name="leadfile" id="leadfile" value="<?php echo $leadfile ?>"></td>
		  			</tr>
					<tr>
						<td><label class="modify-value">List ID Override:</label></td>
						<td>
							<select name="list_id_override">
								<option value='in_file' selected='yes'>Load from Lead File</option>
								<?php
									foreach($lists as $listsInfo){
											$load_list_id = $listsInfo->list_id;
											$load_list_name = $listsInfo->list_name;
											echo '<option value="'.$load_list_id.'">'.$load_list_id.'---'.$load_list_name.'</option>';	 
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td><label class="modify-value">Phone Code Override: </label></td>
						<td><font face="arial, helvetica" size="1">
								<select name="phone_code_override">
                        	<option value='in_file' selected>Load from Lead File</option>
                        	<?php
                        		foreach($phonedoces as $listcodes) {
                        			$country_code = $listcodes->country_code;
                        			$country = $listcodes->country;
                        			echo '<option value="'.$country_code.'">'.$country_code.'---'.$country.'</option>';
										}
                        	?>
                        </select>
                        </td>
                </tr>
                <tr>
						<td><label class="modify-value">Lead Duplicate Check: </label></td>
						<td><font face="arial, helvetica" size="1">
							<select size="1" name="dupcheck">
								<option value="NONE">NO DUPLICATE CHECK</option>
								<option value="DUPLIST">CHECK FOR DUPLICATES BY PHONE IN LIST ID*</option>
								<option value="DUPCAMP">CHECK FOR DUPLICATES BY PHONE IN ALL CAMPAIGN LISTS</option>
								<option value="DUPSYS">CHECK FOR DUPLICATES BY PHONE IN ENTIRE SYSTEM</option>
								<option value="DUPTITLEALTPHONELIST">CHECK FOR DUPLICATES BY TITLE/ALT-PHONE IN LIST ID</option>
								<option value="DUPTITLEALTPHONESYS">CHECK FOR DUPLICATES BY TITLE/ALT-PHONE IN ENTIRE SYSTEM</option>
							</select>
						</td>
		  			 </tr>
		  			 <tr>
		  			 <td><label class="modify-value">Lead Time Zone Lookup: </label></td>
						<td><font face="arial, helvetica" size="1">
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
					 			<input type="submit" value="SUBMIT" name="submit_file" id="submit_file">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					 			<input type="button" onClick="javascript:document.location='go_list/'" value="START OVER" name='reload_page'>
					 		</center>
					 	</td>
					 	
					 </tr>
					 </form>
					 <?php
					 if($fields!=null) {
					 ?>
					<form action="go_list" name="uploadform" method="post" onSubmit="ParseFileName()" enctype="multipart/form-data">
					<input type="hidden" name="leadsload" value="okfinal">
					<input type="hidden" name="lead_file" id="lead_file" value="<?=$lead_file?>">
					<input type="hidden" name="leadfile" id="leadfile" value="<?=$leadfile?>">
					<input type="hidden" name="list_id_override" value="<?=$list_id_override?>">
					<input type="hidden" name="phone_code_override" value="<?=$phone_code_override?>">
					<input type="hidden" name="dupcheck" value="<?=$dupcheck?>">
					<input type="hidden" name="leadfile_name" id="leadfile_name" value="<?=$leadfile_name?>">
					

					 <tr>
					 	<td colspan="2">
					 			<tr bgcolor="#efefef">
					 			<td align="center">GoAutoDial Fields</td>
					 			<td align="center">CSV Fields</td>
					 			</tr>
					 			<?php	
					 			
									$noview = array("lead_id","entry_date","modify_date","status","user","list_id","gmt_offset_now","called_since_last_reset","called_count","last_local_call_time","entry_list_id");					 				
					 				
					 				foreach ($fields as $field) {
					 					
					 					if(in_array("$field", $noview)) {
											echo "";					 						
					 					} else {
								
											echo "  <tr bgcolor=#efefef>\r\n";
											echo "    <td align=right><font class=standard>".strtoupper(eregi_replace("_", " ", $field)).": </font></td>\r\n";
											echo "    <td align=center><select name='".$field."_field'>\r\n";
											echo "     <option value='-1'>(none)</option>\r\n";

											for ($j=0; $j<count($fieldrow); $j++) {
												eregi_replace("\"", "", $fieldrow[$j]);
												echo "     <option value='$j'>\"$fieldrow[$j]\"</option>\r\n";
											}
									
											echo "    </select></td>\r\n";
											echo "  </tr>\r\n";
					
										}
									} // end for
								
					 			?>		
					 	</td>
					 </tr>
					 
					 <tr>
				    	<td colspan="2">
				    		<input type="submit" name="OK_to_process" value="SUBMIT">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				    		<input type="button" onClick="javascript:document.location='go_list/'" value="BACK" name="reload_page">
						</td>
					 <?php
					 	}
					 ?>
					 </tr>
					 </form>
		  		</table>
		  		</center>
				</div>
	</div>
-->	
	<!-- end tab3 -->
				

									<div style="display: none;" class="demo-description">
										<p>Click tabs to swap between content that is broken into logical sections.</p>
									</div><!-- End demo-description -->							
				
                            <div class="container">
                               <div class="clear"></div>
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
