<?php
############################################################################################
####  Name:             go_customlist.php                                               ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
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

<script>
$(document).ready(function(){
	$("#customlisttableresult").tablesorter({sortList:[[0,0]], headers: { 6: { sorter: false}, 7: {sorter: false} }});
	$('#customlisttableresult').tablePagination();
					
        $("#field_label").keydown(function(event) {
		if (event.keyCode == 32) {
			event.preventDefault();
		}
	});
	
	$("#field_options").keydown(function(event) {
    	//				if (event.keyCode == 32) {
        //				event.preventDefault();
    	//			}
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
                        $('#go_action_menu').css('top',position.top-130);
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
	
	$('li.go_action_submenu').click(function () {
		
                var selectedlists = [];
                $('input:checkbox[id="delCampaign[]"]:checked').each(function()
                {
                        selectedlists.push($(this).val());
                });
                
                $('#go_action_menu').slideUp('fast');
                $('#go_action_menu').hide();
                toggleAction = $('#go_action_menu').css('display');

                var action = $(this).attr('id');
                if (selectedlists.length<1)
                {
			alert("Please select a custom field to delete.");
                } else {
                        var s = '';
                        if (selectedlists.length>1)
                                s = 's';
                        
                        
                        if (action == 'delete')
                        {
				delpostvalbacth(selectedlists);
				
				/* delpostvalbacth(fieldlabel,listid,fieldid)
				 var r=confirm("Are you sure you want to delete the selected List"+s);
				if (r==true) {
					deleteselectedlists(selectedlists,"deletelist");
				} else  {
					
				} */
                        }
                }
        });

                                                
    });
</script>
<!-- end Jquery -->

<!--  Javascript section -->
<script language="javascript"> 

	function viewadd(listid) {
	<?php
                             $permissions = $this->commonhelper->getPermissions("customfields",$this->session->userdata("user_group"));
                             if($permissions->customfields_create == "N"){
                                echo("alert('You don\'t have permission to create this record(s)');");
                                echo "return false;";
                             }
                ?>	
	
	 	document.getElementById('hide_listid').value=listid;	
	 	//document.getElementById('btnsub').innerHTML = "<input type=\"button\" name=\"addfield\" id=\"addfield\" value=\"SUBMIT\" onclick=\"addsubmit();\">";
	 	document.getElementById('btnsub').innerHTML = "<a id=\"searchcallhistory\" style=\"cursor: pointer;\" onclick=\"addsubmit();\"><font color=\"#7A9E22\">Submit</font></a>";
	 	document.getElementById('lbltitle').innerHTML = 'Custom Field Wizard » Create Custom Field';
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
	 	document.getElementById('field_label').disabled = false;
	 	
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

		//var field_options = $("#field_options").val().replace("\n", "");
		
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
		
                if(field_label == "list_id" || field_label == "lead_id") {
                        alert('Label should not be equal to `'+field_label+'`.');
                        return false;
                }
		
				
		$.post("<?=$base?>index.php/go_list/editcustomview", { action: "customadd", listid: hide_listid, field_label: field_label, field_rank: field_rank, field_order: field_order, field_name: field_name, name_position: name_position, field_description: field_description, field_type: field_type, field_options: field_options, multi_position: multi_position, field_size: field_size, field_max: field_max, field_default: field_default, field_required: field_required },
					 
				 function(data){
						//alert(data);
	    					alert("SUCCESS: Custom Field Added");
	    					location.reload();
				 });
			 
	}
	
	
	function editsubmit(listid,fakelblname){

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
		
		//var field_options = $("#field_options").val().replace("\n", "");
		
		
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
                if((field_size == "") || (field_size < 1)) {
                        alert('Field size is a required field.');
                        return false;
                }
		
				
				$.post("<?=$base?>index.php/go_list/editcustomview", { action: "customeditsub", listid: listid, field_label: field_label, field_rank: field_rank, field_order: field_order, field_name: field_name, name_position: name_position, field_description: field_description, field_type: field_type, field_options: field_options, multi_position: multi_position, field_size: field_size, field_max: field_max, field_default: field_default, field_required: field_required, field_id: field_id,fakelblname: fakelblname },
					 
				 function(data){
							//alert(data);
				 			alert("SUCCESS: Custom Field Updated");
							location.reload();
				});
			 
	}
	

	
	function delpostval(fieldlabel,listid,fieldid) {
		<?php
                             $permissions = $this->commonhelper->getPermissions("customfields",$this->session->userdata("user_group"));
                             if($permissions->customfields_delete == "N"){
                                echo("alert('You don\'t have permission to delete this record(s)');");
                                echo "return false;";
                             }
                ?>
		var confirmmessage=confirm("Confirm to delete "+fieldlabel+"?");
				if (confirmmessage==true){
				var items = $('#showlistview').serialize();
			$.post("<?=$base?>index.php/go_list/editcustomview", { action: "customdelete", field_id: fieldid, listid: listid, field_label: fieldlabel  },
				 function(data){
				 	//alert(data);
	    					alert("SUCCESS: Custom Field DELETED");
	    					//jQuery('#formcustomview').submit();
							location.reload();
						 });
				}
	}
	
	function delpostvalbacth(fieldid) {
		<?php
                             $permissions = $this->commonhelper->getPermissions("customfields",$this->session->userdata("user_group"));
                             if($permissions->customfields_delete == "N"){
                                echo("alert('You don\'t have permission to delete this record(s)');");
                                echo "return false;";
                             }
                ?>
		
		var i=0;
		var count_listid = (fieldid.length);
		var items = $('#showlistview').serialize();
		var confirmmessage=confirm("Are you sure you want to delete the selected custom field?");
		
		if (confirmmessage==true){
			for (i=0;i<count_listid;i++) {
				
					$.post("<?=$base?>index.php/go_list/editcustomview", { action: "custombatchdelete", field_id: fieldid[i]},
					function(data){
						alert("Custom field successfully deleted.");
						location.reload();
					}); 
			}
		}

	}

	function custompostval(customfieldid,listid) {
<?php
                             $permissions = $this->commonhelper->getPermissions("customfields",$this->session->userdata("user_group"));
                             if($permissions->customfields_update == "N"){
                                echo("alert('You don\'t have permission to update this record(s)');");
                                echo "return false;";
                             }
                ?>
		var items = $('#showlistview').serialize();
			$.post("<?=$base?>index.php/go_list/editcustomview", { action: "customedit", fieldid: customfieldid, list_id: listid   },
				 function(data){
                				var datas = data.split("||");
                				var i=0;
                				var count_listid = datas.length;
                				var fakelbl = datas[3];
                				
                				for (i=0;i<count_listid;i++) {

                						if(datas[i]=="") {
												datas[i]=" ";
 											}
 											//document.getElementById('btnsub').innerHTML = "<input type=\"button\" name=\"custfield\" id=\"custfield\" value=\"MODIFY\" onclick=\"editsubmit('"+listid+"','"+fakelbl+"');\">";
 											document.getElementById('btnsub').innerHTML = "<a id=\"searchcallhistory\" style=\"cursor: pointer;\" onclick=\"editsubmit('"+listid+"','"+fakelbl+"');\"><font color=\"#7A9E22\">Submit</font></a> ";
											document.getElementById('lbltitle').innerHTML = 'Modify field <b>'+datas[3]+'</b>'; 										
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
 													
											document.getElementById('field_label').disabled = true;
 									} 
 											
 								   $('#overlay').fadeIn('fast',function(){
	                  			   			$('#box').animate({'top':'70px'},500);
									$('#small_step_number').css('display', 'none');
									$('#step_number').css('display', 'none');
		             			   			});
	             				 
	             				   		   $('#boxclose').click(function(){
	              					   		$('#box').animate({'top':'-550px'},500,function(){
	                  						$('#overlay').fadeOut('fast');
									$('#small_step_number').css('display', 'block');
									$('#step_number').css('display', 'block');
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
   	            /*background:transparent url(../../img/images/go_list/overlay.png) repeat top left;*/
   	            background:transparent url(<? echo $base; ?>/img/images/go_list/overlay.png) repeat top left;
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
	        .boxview{
	            position:fixed;
	            top:-550px;
/*	            top:-200px;*/
	            left:10%;
	            right:10%;
/*	            background-color:#fff;
	            color:#7F7F7F;*/
  	            background-color: white;
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
	            left:20%;
	            right:20%;
  	            background-color: white;
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
	            width: 50%;
	        }	        
	        
	        
	        .box h1{
	            border-bottom: 1px dashed #7F7F7F;
	            margin:-20px -20px 0px -20px;
	            padding:50px;
	            background-color: white;
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
			    border-top: 0px dashed rgb(208,208,208);
			    
			}
			td {
			    /*background-color: #ccc;
			    background-color: #D0DEB9;
			    */
			}

			.tabnoborder {
				border: 0;
			}
			
			.title-header {
				font-weight:bold;
				color: black;	
				font-size: 15px;	
			}
			
			.modify-value {
                                font-weight: bold;
                                color: #7f7f7f;
				/*font-weight: bold;*/
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
				font-family: Verdana;
			}

			
			.tr1 td{ background:#E0F8E0; font-family: Verdana; color:#000;  border-top: 1px dashed rgb(208,208,208); }
			.tr2 td{ background:#EFFBEF; font-family: Verdana; color:#000;  border-top: 1px dashed rgb(208,208,208); }
			.tredit td{ background:#EFFBEF; color:#000; font-family: Verdana;  border-bottom: 1px dashed rgb(208,208,208); }   
			
			A:link {text-decoration: none; color: black;}
			A:visited {text-decoration: none; color: black;}
			A:active {text-decoration: none; color: black;}
			A:hover {text-decoration: underline overline;}

                        A#searchcallhistory:link {text-decoration: none; color: black;}
                        A#searchcallhistory:visited {text-decoration: none; color: black;}
                        A#searchcallhistory:active {text-decoration: none; color: black;}
                        A#searchcallhistory:hover {text-decoration: none; font-weight:bold;}
			
.thheader {
/*				background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/bg.gif);*/
        		background-repeat: no-repeat;
        		background-position: center right;
        		cursor: pointer;
        		font-family: Verdana;
			
			}  
			
			.xrigth{
				border: medium none;
				/*float: right;*/
				font-size: 12px;
				font-weight: bold;
				line-height: 1;
				margin: 0;
				padding: 1px;
				}
				
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

body {
	height: 90%;
}

		</style>
<!-- end CSS section -->

<!-- begin body -->

                        <div id='outbody' class="wrap">
    <div id="icon-list" class="icon32"></div>
    <h2 style="font-family: Verdana;">Custom Fields</h2>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">

    	    <!-- start box -->
            <div class="postbox-container" style="width:99%;">
                <div class="meta-box-sortables ui-sortables">
                    <!-- List holder-->
                    <div class="postbox" style="width: 99%; min-width: 1280px;">
                        <div>
                            
                        </div>
                        <!--<a id="activator" class="rightdiv toolTip" style="text-decoration: none; cursor:pointer;" onClick="addlistoverlay();" title="Create New Field"><b>Create New Field</b>  </a>-->
                        
						<a id="activator"  class="rightdiv toolTip" onClick="viewadd('<? echo $listidparam; ?>');" title="Create New Field" style="text-decoration: none; cursor:pointer; font-family: Verdana,Arial,Helvetica,sans-serif;"> Create New Field </a>
						
						
                        <h3 class="hndle" style="height:13px">
	                    	</h3>
                        <div class="inside inside-tab">
				<!-- start view -->
				
<div class="demo">

<div id="tabs" class="tabnoborder">

	<!-- show custom list -->
	<div id="tabs-2" >
<!--<a id="activator" class="rightdiv toolTip" style="text-decoration: none; cursor:pointer; margin-top: -10px;" onClick="customviews('<? echo $listidparam; ?>');" title="View Custom Fields"> View Custom Fields </a>-->

		<div id="showlist" style="display: block;" class="tabnoborder">
				<br>	
					<center>
						<table id="customlisttableresult" class="tablesorter" width="100%" class="" cellspacing="0" cellpadding="0" border="0" style="margin-left:auto; margin-right:auto; width:100%;">
						<thead> 
							<tr align="left" class="nowrap">
							<th class="thheader" align="left" style="padding-bottom:-1px;"><b>RANK</b> </th>
							<th class="thheader" align="left" style="padding-bottom:-1px;"><b>LABEL</b> </th>
							<th class="thheader" align="left" style="padding-bottom:-1px;"><b>NAME</b> </th>
							<th class="thheader" align="left" style="padding-bottom:-1px;"><b>TYPE</b> </th>
							<th class="thheader" align="left" style="padding-bottom:-1px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th colspan="3" class="thheader" style="width:7%;white-space: nowrap;padding-bottom:-1px;" align="right">
							<span style="cursor:pointer;" id="selectAction"><b>&nbsp;ACTION&nbsp;&nbsp;&nbsp;</b><img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span>							
							</th>
							<th align="center" width="26px" style="padding-bottom:-1px;">
								<input type="checkbox" id="selectAll">							 
							</th>
							</tr>
						</thead>	
						<tbody>
						<?php
						 if($permissions->customfields_read == "N"){
                                                   echo("<tr class='tr2'><td colspan='9'>You don't have permission to view this record(s)</td></tr>");
                                                   $countthis = 0;
                                                   $justpermission = true;
                                        	 }
						if(!$justpermission){
						echo $custeditview;
						}
						?>
						</tbody>
						</table>
				</center>
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
		<div class="overlayadd" id="overlay" style="display:none;"></div>
		
				<div class="box" id="box">
				<center>
				
 				<a class="boxclose" id="boxclose"></a>
                                <div id="small_step_number" style="float:right; margin-top: -5px;">
                                        <img src="<?=$base?>img/step1-nav-small.png">
                                </div>
                                <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
                                        <font color="black" style="font-size:16px;"><b><span id="lbltitle"></span> </b></font>

                                </div>
				
 						<form method="POST" name="formfields" id="formfields">
<!--							<input type=hidden name=action value=MODIFY_CUSTOM_FIELD_SUBMIT>-->
						<input type="hidden" name="hide_listid" id="hide_listid">
						<input type="hidden" name="fakename" id="fakename">
						<input type="hidden" name="field_id" id="field_id">
<!--						<div align="left" class="title-header"> <span id="lbltitle"></span> </div> -->

						<br>
                                                <table class="tableedit" width="100%">
                                                <tr>
                                                <td valign="top" style="">
                                                                        <div id="step_number" style="padding:0px 10px 0px 30px;">
                                                                <img src="<?=$base?>img/step1-trans.png">
                                                                </div>
                                                </td>
                                                <td style="padding-left:50px;" valign="top" colspan="2">
					
						<table class="tablenodouble" width="100%">
							<!--<tr align="center"><td> Add field <u><span id="lblfield"></span></u> </td></tr>-->
							<tr><td> <label class="modify-value">Labels:</label></td><td> <input type="text" name="field_label" id="field_label"> </td></tr>
							<tr><td align="left"> 
									<label class="modify-value">Rank:</label></td>
								<td align="left">
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
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
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
					</table>
 					</tr>
                                        <tr>
                                                <td align="right" colspan="9">
                                                <div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
								<span id="btnsub"></span>
                                                   
                                                </div>

                                                </td>
                                        </tr>



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

<div id='go_action_menu' class='go_action_menu'>
	<ul>
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
<!-- end body -->

