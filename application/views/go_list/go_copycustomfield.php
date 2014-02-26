<?php
############################################################################################
####  Name:             go_copycustomfield.php                                          ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>

									<span id="spancopycustom">
                                	<div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
                                        	<font color="#333" style="font-size:16px;"><b>Custom Field Wizard » Copy Custom Field</b></font>
                                	</div>

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
        						   <tr><td> <label class="modify-value">List I.D.:</label></td>
							    <td> 
								<select name="hide_listid" id="hide_listid" Onchange="selectlistid();">
									<option value="createcustomselect">Create Custom Field</option>
									<option>elseds</option>
									<?php
									foreach($lists as $listsInfo){
										echo "<option value='$listsInfo->list_id'>".$listsInfo->list_id." - ".$listsInfo->list_name." </option>";				
									}
									?>
								</select> 		
							    </td></tr>
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
					</form>
					</span>

