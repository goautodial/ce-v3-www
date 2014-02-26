<?php
############################################################################################
####  Name:             go_list.php                                                     ####
####  Type: 		       ci views 															      ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Jerico James Milo <james@goautodial.com>      #### 
####  License:          AGPLv2                                                          ####
############################################################################################

$base = base_url();

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
						
    });  
  
		
</script>

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
	

	function postval(listID) {

		document.getElementById('showval').value=listID;
		document.getElementById('showvaledit').value=listID;

		var items = $('#showlistview').serialize();
			$.post("http://192.168.100.112/index.php/go_list/editview", { items: items, action: "editlist" },
				 function(data){
				 	
                				var datas = data.split("--");
                				var i=0;
                				var count_listid = datas.length;
                				
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
	                  			$('#box').animate({'top':'70px'},500);
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
				$.post("http://192.168.100.112/index.php/go_list/editsubmit", { itemsumit: itemsumit, action: "editlistfinal" },
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
					
					
					$.post("http://192.168.100.112/index.php/go_list/deletesubmit", { listid_delete: listID, action: "deletelist" },
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
		
</script>
<!-- end Javascript section -->

		<!-- CSS section -->
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
	            font-size: 12px;
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
	            background:transparent url(../../../img/images/go_list/cancel.png) repeat top left;
	            margin-top:-30px;
	            margin-right:-30px;
	            cursor:pointer;
	        }
	        
			.nowrap { 
			   background: white;
 			   font-size: 12px;
			}
			table {
			    border-top: 1px dashed rgb(208,208,208);
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

			.tablenodouble {
				border-top: 0px double; rgb(208,208,208);
			}

			
			.tr1 td{ background:#E0F8E0; color:#000;  border-top: 1px dashed rgb(208,208,208); }
			.tr2 td{ background:#EFFBEF; color:#000;  border-top: 1px dashed rgb(208,208,208); }
			.tredit td{ background:#EFFBEF; color:#000;  border-bottom: 1px dashed rgb(208,208,208); }   
			
			A:link {text-decoration: none; color: black;}
			A:visited {text-decoration: none; color: black;}
			A:active {text-decoration: none; color: black;}
			A:hover {text-decoration: underline overline; color: red;}

		</style>
		<!-- end CSS section -->

<!-- begin body -->
<div id='outbody' class="wrap">
    <div id="icon-list" class="icon32"></div>
    <h2>Lists</h2>
    <br><!-- spacer -->
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">

    	    <!-- start box -->
            <div class="postbox-container" style="width:95%;">
                <div class="meta-box-sortables ui-sortables">
                    <!-- List holder-->
                    <div class="postbox">
                        <div class="handlediv" title="Click to toggle">
                            <br>
                        </div>
                        <h3 class="hndle">
                            <span>List Listings</span>
                        
                        <span style="margin-left: 80%;">
								<a id="activator" class="activator"  onClick="addlistoverlay();" title="CREATE NEW LIST"><b>Create List</b></a></span>
                        </h3>
                        <div class="inside">

<div id="tabs" class="">
<ul style="background: transparent; border-bottom:1px dashed rgb(208,208,208);">
		<li><a href="#tabs-1" id="atab1" title="content_1" class="tab">Show Lists</a></li>
		<li><a href="#tabs-2" id="atab2" title="content_2" class="tab">Custom Fields</a></li>
		<li><a href="#tabs-3" id="atab3" title="content_3" class="tab">Load Leads</a></li>
	</ul>
		

	<div id="tabs-1">

				<!-- LISTs TAB -->
				<div id="showlist" style="display: block;">
				
				<form name="showlistview" id="showlistview">
				<input type="hidden" name="showval" id="showval">
				
				<br><br>
				<table cellspacing="1" cellpadding="1" border="0" style="margin-left:auto; margin-right:auto; width:95%; border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
					<tr align="center" class="nowrap">
						<td><b>LIST ID</b></td>
						<td colspan="2"><b>DESCRIPTION</b></td>
						<td><b>STATUS</b></td>
						<td><b>LAST CALL DATE</b></td>
						<td><b>CAMPAIGN</b></td>
						<td colspan="2"><b>ACTION</b></td>
					</tr>
					<?php
					   foreach($lists as $listsInfo){
					?>   		         	
							 <tr align="left" class="tr<?php echo alternator('1', '2') ?>">
								 <td align="center" bgcolor="<?=$rowColor?>">
								<a id="activator" class="activator"  onClick="postval('<? echo $listsInfo->list_id; ?>');"><u><? echo $listsInfo->list_id; ?></u></a></td>
								 <td colspan="2">
								 								 
								 <?
								 	echo ucwords(strtolower($listsInfo->list_name));							 
								 ?>
								 </td>
								 <td align="center">
								 <?php
								 	if($listsInfo->active=="Y") {
								 		echo "<b><font color=green>ACTIVE</font></b>";
								 	} else {
								 		echo "<b><font color=red>INACTIVE</font></b>";	
								 	}
								 	
								 ?>
								 </td>
								 <td align="center"><? echo $listsInfo->list_lastcalldate; ?></td>
								 <td align="center"  style="padding: 3px 0;"><? echo $listsInfo->campaign_id; ?></td>
  								 <td align="center"  style="padding: 3px 0;">
  								 
								<img src="<?=$base?>img/edit.gif" onclick="postval('<? echo $listsInfo->list_id; ?>');" style="cursor:pointer;width:22px;" title="MODIFY" />
  								 <br>
							   <a id="activator" class="activator"  onClick="postval('<? echo $listsInfo->list_id; ?>');" style="font-size:10px;"><u>modify</u></a>
								 </td>								  
								  
								 <td align="center">
								<img src="<?=$base?>img/delete.png" onclick="deletepost('<? echo $listsInfo->list_id; ?>');" style="cursor:pointer;width:22px;" title="DELETE" /><br>
								<a id="activator" class="activator"  onClick="deletepost('<? echo $listsInfo->list_id; ?>');" style="font-size:10px;"><u>delete</u></a>
								 </td>
							 </tr>
							<?php
							$i++;
							}
							?>
				</table>
				</form>
				</div>
				<!-- end view -->
				
				<br><br><br><br><br><br>				
				
				<div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="boxaddlist" id="boxaddlist">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closemeadd();"></a>
				<!-- start add -->
				<div id="addlist" style="display: block;">
					<form  method="POST" id="go_listfrm" name="go_listfrm">
				   	<input type="hidden" id="selectval" name="selectval" value="">
				   	
				   	
				   <div align="left" class="title-header">Create new list</div>
					
					<table class="tableedit" width="100%">
                  <tr>
							<td><label class="modify-value">Auto Generate: </label></td>
							<td><input type="checkbox" id="auto_gen" name="auto_gen" onclick="showRow();"></td>
						</tr>
                  <tr id="account_numTD" style="display:none;">
							<td><label class="modify-value">Account Number: </label></td>
							<td><select id="account_num" name="account_num" onchange="genListID(this.selectedIndex);">
							     	<option></option>
							<?php
                          foreach($accounts as $accountsInfo){
                          		$accountno = $accountsInfo->account_num;
                              $company = $accountsInfo->company;
                              echo '<option value="'.$accountno.'">'.$accountno.'---'.$company.'</option>';
                         	}
                     ?>
							</select></td>
						</tr>
                  <tr>
							<td><label class="modify-value">List ID:</label> </td>
							<td><input type="text" name="list_id" id="list_id" size="12" maxlength="12"> (digits only)</td>
						</tr>
						<tr>
							<td><label class="modify-value">List Name:</label> </td>
							<td><input type="text" name="list_name" id="list_name" size="30" maxlength="30"></td>
						</tr>
						<tr>
							<td><label class="modify-value">List Description:</label> </td>
							<td><input type="text" name="list_description" id="list_description" size="30" maxlength="255">
						</tr>
						<tr>
							<td><label class="modify-value">Campaign:</label> </td>
							<td><span id="campaign_list">
									<select name="campaign_id" id="campaign_id">
							        <option></option>
							<?php
   		               		foreach($campaigns as $campaignInfo){
									$cid = $campaignInfo->campaign_id;
									$cname = $campaignInfo->campaign_name;
                        		echo '<option value="'.$cid.'">'.$cid.'---'.$cname.'</option>';
								}
							?>
									</select></span>
									<span id="campaign_list_hidden" style="display:none"></span></td>
						</tr>
                	<tr>
							<td><label class="modify-value">Active:</label> </td>
							<td><select size="1" name="active"><option>Y</option><option>N</option></select></td>
						</tr>
                	<tr>
							<td align="center" colspan="2">
							<input type="submit" name="addSUBMIT" value="SUBMIT"></td>
						</tr>
					</table>	
					</form>
					</div>
				</div>
				<!-- end add -->
				
				<br><br><br><br><br><br>				
				
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
								<td><label class="modify-value">Reset Times:</label> </td>
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
								<td colspan="2"><label class="modify-value">Reset Lead-Called-Status:</label>
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
							<!-- <tr>
								<td>Answering Machine Message Override: </td>
								<td><input type="text" name="am_message_exten_override" id="am_message_exten_override" size="50" maxlength="100" value="<?=$eam_message_exten_override?>"></td>
							</tr> -->				
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
							
							<tr>
								<td align="center" colspan="2"><br>
								<input type="button" name="editSUBMIT" value="MODIFY" onclick="editpost(document.getElementById('showvaledit').value);">
<!--								<input type="submit" name="editSUBMIT" value="MODIFY">								-->
								</td>
								
							</tr>
							<tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
					</table>				
				</form>
				</center>
				</div>	
				<!-- end edit -->
	</div> <!-- end LISTs -->
	
	
	
	
	
	
	
	
	<!-- CUSTOM FIELDS -->
	<div id="tabs-2">
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
	
	</div><!-- end tab2 -->



	<!-- tab3 -->
	<div id="tabs-3">
				<!-- upload leads -->
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
                <!--<tr>
						<td><B><font face="arial, helvetica" size="2">File layout to use:</font></B></td>
						<td><font face="arial, helvetica" size="2"><input type="radio" name="file_layout" value="standard">Standard Format&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio name="file_layout" value="custom" checked>Custom layout</td>
                </tr>-->
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
	</div><!-- end tab3 -->
				

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
<!-- end body -->
