<?php
############################################################################################
####  Name:             go_customlist.php                                                     ####
####  Type: 		       ci views 															      ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Jerico James Milo <james@goautodial.com>      #### 
####  License:          AGPLv2                                                          ####
############################################################################################

$base = base_url();
?>

<!-- Jquery -->
<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
</script>
<!-- end Jquery -->

<!--  Javascript section -->
<script language="javascript"> 

	function viewadd(listid) {
	
	 	document.getElementById('hide_listid').value=listid;	
	 	document.getElementById('btnsub').innerHTML = "<input type=\"button\" name=\"addfield\" id=\"addfield\" value=\"Add\" onclick=\"addsubmit();\">";
	 	document.getElementById('lbltitle').innerHTML = 'Add custom field';
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
	 	
	 	
	 	$('#overlay').fadeIn('fast',function(){
			$('#box').animate({'top':'70px'},500);
	 	});
	 				 
	 	$('#boxclose').click(function(){
			$('#box').animate({'top':'-550px'},500,function(){
				$('#overlay').fadeOut('fast');
			});
	 	});
	 	
	 	$('#btnclose').click(function(){
	  		$('#box').animate({'top':'-550px'},500,function(){
				$('#overlay').fadeOut('fast');
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
		
				
		$.post("http://192.168.100.112/index.php/go_list/editcustomview", { action: "customadd", listid: hide_listid, field_label: field_label, field_rank: field_rank, field_order: field_order, field_name: field_name, name_position: name_position, field_description: field_description, field_type: field_type, field_options: field_options, multi_position: multi_position, field_size: field_size, field_max: field_max, field_default: field_default, field_required: field_required },
					 
				 function(data){
	    					alert("SUCCESS: Custom Field Added");
	    					//jQuery('#formcustomview').submit();
	    					location.reload();
				 });
			 
	}
	
	
	function editsubmit(){

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
		var field_id = document.getElementById('field_id').value;
				
				$.post("http://192.168.100.112/index.php/go_list/editcustomview", { action: "customeditsub", listid: hide_listid, field_label: field_label, field_rank: field_rank, field_order: field_order, field_name: field_name, name_position: name_position, field_description: field_description, field_type: field_type, field_options: field_options, multi_position: multi_position, field_size: field_size, field_max: field_max, field_default: field_default, field_required: field_required, field_id: field_id },
					 
				 function(data){
				 			//alert(data);
	    					alert("SUCCESS: Custom Field Updated");
	    					//jQuery('#formcustomview').submit();
							location.reload();
				});
			 
	}


	function custompostval(customfieldid,listid) {

		var items = $('#showlistview').serialize();
			$.post("http://192.168.100.112/index.php/go_list/editcustomview", { action: "customedit", fieldid: customfieldid, list_id: listid   },
				 function(data){
                				var datas = data.split("||");
                				var i=0;
                				var count_listid = datas.length;
                				
                				for (i=0;i<count_listid;i++) {

                						if(datas[i]=="") {
												datas[i]=" ";
 											}
 											document.getElementById('btnsub').innerHTML = "<input type=\"button\" name=\"custfield\" id=\"custfield\" value=\"update\" onclick=\"editsubmit();\">";
											document.getElementById('lbltitle').innerHTML = 'Modify field <u><b>'+datas[3]+'</b></u>'; 										
 											//document.getElementById('lblfield').innerHTML = '<b>'+datas[3]+'</b>';
 											document.getElementById('field_label').value= datas[2];
 											document.getElementById('field_rank').value= datas[5];
 											document.getElementById('field_order').value= datas[16];
 											document.getElementById('field_name').value= datas[3];
 											document.getElementById('name_position').value= datas[14];
 											document.getElementById('field_description').value= datas[4];
 											document.getElementById('field_type').value= datas[7];
 											document.getElementById('field_options').value= datas[8];
											document.getElementById('multi_position').value= datas[15];
											document.getElementById('field_size').value= datas[9];
											document.getElementById('field_max').value= datas[10];
											document.getElementById('field_default').value= datas[11];
											document.getElementById('field_required').value= datas[13];
											document.getElementById('field_id').value= datas[0]; 
 													
										
 									} 
 											
 								   $('#overlay').fadeIn('fast',function(){
	                  			   $('#box').animate({'top':'70px'},500);
		             			   });
	             				 
	             				   $('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								 	});
								 	
								 	$('#btnclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								 	});		
						 });	
	}
	
	
	function customviews(listid) {
		

		var items = $('#showlistview').serialize();
			$.post("http://192.168.100.112/index.php/go_list/editcustomview", { action: "customview", list_id: listid   },
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
	           /* color: blue;*/
	          /*  position:absolute;*/
	            top:0px;
	            left:0px;
/*	            background:#fff url(clickme.png) no-repeat top left;*/
	            z-index:1;
	            cursor:pointer;
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
	        .boxview{
	            position:fixed;
	            top:-550px;
/*	            top:-200px;*/
	            left:10%;
	            right:10%;
/*	            background-color:#fff;
	            color:#7F7F7F;*/
  	            background-color: #EFFBEF;
	            color:#7F7F7F;
	            padding:20px;
/*	            border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	             -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            z-index:101;
	        }
	        
				.box{
	            position:fixed;
	            top:-550px;
/*	            top:-200px;*/
	            left:30%;
	            right:30%;
  	            background-color: #EFFBEF;
	            color:#7F7F7F;
	            padding:20px;
/*	            border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	             -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            z-index:101;
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
			}
			table {
			    border-top: 1px dashed rgb(208,208,208);
			    
			}
			td {
			    /*background-color: #ccc;
			    background-color: #D0DEB9;
			    */font-size : 10px;
			}

			.tabnoborder {
				border: 0;
			}
			
			.title-header {
				font-weight:bold;
				color: black;	
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
			
			A:link {text-decoration: none; color: blue;}
			A:visited {text-decoration: none; color: blue;}
			A:active {text-decoration: none; color: blue;}
			A:hover {text-decoration: underline overline; color: red;}

		</style>
<!-- end CSS section -->

<!-- begin body -->
<div id='outbody' class="wrap">
    <div id="icon-index" class="icon32"></div>
    <h3><?//$bannertitle?></h3>
    <br><!-- spacer -->
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">

    	    <!-- start box -->
            <div class="postbox-container" style="width:80%;">
                <div class="meta-box-sortables ui-sortables">
                    <!-- List holder-->
                    <div class="postbox">
                        <div class="handlediv" title="Click to toggle">
                            <br>
                        </div>
                        <h3 class="hndle">
                            <span>CUSTOM LIST&nbsp; <?php echo $listidparam; ?>
                            </span>
                        </h3>
                        <div class="inside">
                        
				<!-- start view -->
				
<div class="demo">

<div id="tabs" class="tabnoborder">

	<!-- show custom list -->
	<div id="tabs-2" >
		<div id="showlist" style="display: block;" class="tabnoborder">

				<?php
					echo $custeditview;
				?>
		</div>

		<!-- VIEW CUSTOM FIELDS -->		
		<div class="overlay" id="overlayview" style="display:none;"></div>
		
				<div class="boxview" id="boxview">
					<center>
			 				<a class="boxclose" id="boxcloseview"></a>			
							<span id="viewme"></span>
					</center>
				</div>
		
		<!-- MODIFY FIELDS -->
		<div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="box" id="box">
				<center>
				
 				<a class="boxclose" id="boxclose"></a>
 						<form method="POST" name="formfields" id="formfields">
<!--							<input type=hidden name=action value=MODIFY_CUSTOM_FIELD_SUBMIT>-->
						<input type="hidden" name="hide_listid" id="hide_listid">
						<input type="hidden" name="field_id" id="field_id">
						<div align="left" class="title-header"> <span id="lbltitle"></span> </div>
						<table class="tableedit" width="100%">
						<tr><td>&nbsp;</td></tr>
						</table>
						
						<center>
						<table class="tablenodouble" width="100%">
							<!--<tr align="center"><td> Add field <u><span id="lblfield"></span></u> </td></tr>-->
							<tr><td> <label class="modify-value">Labels:</label> <input type="text" name="field_label" id="field_label"> </td></tr>
							<tr><td> 
									<label class="modify-value">Rank:</label>
									<select name="field_rank" id="field_rank">
             						<option></option>
										<?php
								
										for ($i=1; $i <=$countfields; $i++) {
                   					echo "<option value='$i'>".$i."</option>";
                   					}
										$orderplus = $countfields + 1; 
											echo "<option value='$orderplus'>".$orderplus."</option>";
										?>
										</select>
										
									<label class="modify-value">Order:</label>
									<select name="field_order" id="field_order">
             						<option></option>
             						<?php
								
										for ($i=1; $i <=$countfields; $i++) {
                   					echo "<option value='$i'>".$i."</option>";
                   					}
											$orderplus = $countfields + 1; 
											echo "<option value='$orderplus'>".$orderplus."</option>";
										?>
										</select>

							</td></tr>			
							<tr><td> <label class="modify-value">Name:</label> <input type="text" name="field_name" id="field_name"></td></tr>
							<tr><td> <label class="modify-value">Position:</label> <select name="name_position" id="name_position">
														<option value="LEFT">LEFT</option>
														<option value="TOP">TOP</option> 
													</select>
							</td></tr>
							<tr><td> <label class="modify-value">Description:</label> <input type="text" name="field_description" id="field_description"> </td></tr>
							<tr><td> <label class="modify-value">Type</label> <select name="field_type" id="field_type">
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
							<tr><td> <label class="modify-value">Options:</label> <textarea name="field_options" id="field_options" ROWS="5" COLS="60"></textarea> 
							</td></tr>
							<tr><td> <label class="modify-value">Option Position:</label> <select name="multi_position" id="multi_position">
										<option value="HORIZONTAL">HORIZONTAL</option>
										<option value="VERTICAL">VERTICAL</option>
										</select> 
							</td></tr>
							<tr><td><label class="modify-value">Field Size:</label> <input type="text" name="field_size" id="field_size"> </td></tr>
							<tr><td><label class="modify-value">Field Max:</label> <input type="text" name="field_max" id="field_max"> </td></tr>
							<tr><td><label class="modify-value">Field Default:</label> <input type="text" name="field_default" id="field_default"> </td></tr>
							<tr><td><label class="modify-value">Field Required:</label> <select name="field_required" id="field_required">
											<option value="Y">YES</option>
											<option value="N">NO</option>
										</select> 
							</td></tr>
							<tr><td align="center"> 
								
								<span id="btnsub"></span>
								<input type="button" name="btnclose" id="btnclose" class="btnclose" value="close"> 
							</td></tr>
					</table>
				</center></form>
 				</div>
	</div>
	<!-- end show custom list -->	
	
<!--	<div id="tabs-3">

	</div>
	-->
</div>

</div><!-- End demo -->



<div style="display: none;" class="demo-description">
<p>Click tabs to swap between content that is broken into logical sections.</p>
</div><!-- End demo-description -->				
				
<br><br><br><br><br><br>				
				
				

				
				<br><br><br><br><br><br>				
				
				
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

