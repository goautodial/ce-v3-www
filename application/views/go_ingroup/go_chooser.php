<?php
############################################################################################
####  Name:             go_chooser.php  	                                        ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  License:          AGPLv2                                                          ####
############################################################################################


## DATABASES CONNECTIVITY
$this->asteriskDB = $this->load->database('dialerdb',TRUE);
//$this->a2billingDB =$this->load->database('billingdb',TRUE);
$this->load->model('goingroup');

## POST VARIBLES
$user = $this->session->userdata('user_name');
$pass = $this->session->userdata('user_pass');
$action = $this->input->post('action'); 
$stage = $this->input->post('stage');
$format = $this->input->post('format');
$comments = $this->input->post('comments');
$defaultvalue = $this->input->post('defaultvalue');


## VARIABLES 
$MT[0]='';


#### START SOUND LIST  ######
if ($action == 'sounds_list') {
        $stmt = "SELECT count(*) as countuser from vicidial_users where user='$user' and user_level > 6;";
        $rslt = $this->asteriskDB->query($stmt);
		 $row = $rslt->row(); 
        $allowed_user=$row->countuser;
        if ($allowed_user < 1) {
                $result = 'ERROR';
                $result_reason = "sounds_list USER DOES NOT HAVE PERMISSION TO VIEW SOUNDS LIST";
                echo "$result: $result_reason: |$user|$allowed_user|\n";
                $data = "$allowed_user";
                //api_log($link,$api_logging,$api_script,$user,$agent_user,$function,$value,$result,$result_reason,$source,$data);
                exit;
        } else {
        		
                $server_name = getenv("SERVER_NAME");
                $server_port = getenv("SERVER_PORT");
                if (eregi("443",$server_port)) {$HTTPprotocol = 'https://';}
                  else {$HTTPprotocol = 'http://';}
                $admDIR = "$HTTPprotocol$server_name:$server_port";
				  
                #############################################
                ##### START SYSTEM_SETTINGS LOOKUP #####
                $stmt = "SELECT use_non_latin,sounds_central_control_active,sounds_web_server,sounds_web_directory FROM system_settings;";
                $rslt = $this->asteriskDB->query($stmt);
                $ss_conf_ct = $rslt->num_rows;

                if ($ss_conf_ct > 0) {
                		$row = $rslt->row();
                		$non_latin =                            $row->use_non_latin;
                		$sounds_central_control_active =        $row->sounds_central_control_active;
                		$sounds_web_server =                    $row->sounds_web_server;
                		$sounds_web_directory =                 $row->sounds_web_directory;
                		
				  }
                ##### END SETTINGS LOOKUP #####
                ###########################################

                if ($sounds_central_control_active < 1) {
                        $result = 'ERROR';
                        $result_reason = "sounds_list CENTRAL SOUND CONTROL IS NOT ACTIVE";
                        echo "$result: $result_reason: |$user|$sounds_central_control_active|\n";
                        $data = "$sounds_central_control_active";
                        exit;
				  } else {
                        $i=0;
                        $filename_sort=$MT;
                        #$dirpath = "$WeBServeRRooT/$sounds_web_directory";
                        $dirpath = "/var/lib/asterisk/sounds";
                        $dh = opendir($dirpath);
                     	   
                       // if ($DB>0) {echo "DEBUG: sounds_list variables - $dirpath|$stage|$format\n";}
                        while (false !== ($file = readdir($dh)))
                                {
                                # Do not list subdirectories
								$prefix = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "go_".$this->session->userdata('user_group')."_" : "go_";
                                if ( (!is_dir("$dirpath/$file")) and (preg_match('/\.wav$|\.gsm$/', $file)) and (preg_match("/^$prefix/", $file)) )
                                        {
                                        if (file_exists("$dirpath/$file"))
                                                {
                                                $file_names[$i] = $file;
                                                $file_namesPROMPT[$i] = preg_replace("/\.wav$|\.gsm$/","",$file);
                                                $file_epoch[$i] = filemtime("$dirpath/$file");
                                                $file_dates[$i] = date ("Y-m-d H:i:s.", filemtime("$dirpath/$file"));
                                                $file_sizes[$i] = filesize("$dirpath/$file");
                                                $file_sizesPAD[$i] = sprintf("[%020s]\n",filesize("$dirpath/$file"));
                                                if (eregi('date',$stage)) {$file_sort[$i] = $file_epoch[$i] . "----------" . $i;}
                                                if (eregi('name',$stage)) {$file_sort[$i] = $file_names[$i] . "----------" . $i;}
                                                if (eregi('size',$stage)) {$file_sort[$i] = $file_sizesPAD[$i] . "----------" . $i;}

                                                $i++;
                                                }
                                        }
                                }
                        closedir($dh);

                        if (eregi('date',$stage)) {rsort($file_sort);}
                        if (eregi('name',$stage)) {sort($file_sort);}
                        if (eregi('size',$stage)) {rsort($file_sort);}

                        sleep(1);

                        $k=0;
                        $sf=0;
                        while($k < $i)
                                {
                                	
                                $file_split = explode('----------',$file_sort[$k]);
                                $m = $file_split[1];
                                $NOWsize = filesize("$dirpath/$file_names[$m]");
                                //if ($DB>0) {echo "DEBUG: sounds_list variables - $file_sort[$k]|$size|$NOWsize|\n";}
								// && (preg_match("/^".$user."/",$file_names[$m]) || $user=="0001")
                                if ($file_sizes[$m] == $NOWsize)
                                        {
                                        if (eregi('tab',$format))
                                                { $field_HTML .= "$k\t$file_names[$m]\t$file_dates[$m]\t$file_sizes[$m]\t$file_epoch[$m]\n";}
                                        if (eregi('link',$format))
                                                { $field_HTML .= "<a href=\"http://$sounds_web_server/$sounds_web_directory/$file_names[$m]\">$file_names[$m]</a><br>\n";}
                                        if (eregi('selectframe',$format))
                                                {
                                                	
                                                if ($sf < 1)
                                                        {
                                                   
                                                        $field_HTML .= "\n";
                                                        //$field_HTML .= "<HTML><head><title>NON-AGENT API</title>\n";
                                                        $field_HTML .= "<script language=\"Javascript\">\n";
                                                        $field_HTML .= "function choose_file(filename,fieldname)\n";
                                                        $field_HTML .= "  {\n";
                                                        $field_HTML .= "  if (filename.length > 0)\n";
                                                        $field_HTML .= "          {\n";
                                                        $field_HTML .= "          parent.document.getElementById(fieldname).value = filename;\n";
                                                        $field_HTML .= "          document.getElementById(\"selectframe\").innerHTML = '';\n";
                                                        $field_HTML .= "          document.getElementById(\"selectframe\").style.visibility = 'hidden';\n";
                                                        $field_HTML .= "          parent.close_chooser();\n";
                                                        $field_HTML .= "          }\n";
                                                        $field_HTML .= "  }\n";
                                                        $field_HTML .= "function close_file()\n";
                                                        $field_HTML .= "  {\n";
                                                        $field_HTML .= "  document.getElementById(\"selectframe\").innerHTML = '';\n";
                                                        $field_HTML .= "  document.getElementById(\"selectframe\").style.visibility = 'hidden';\n";
                                                        $field_HTML .= "  parent.close_chooser();\n";
                                                        $field_HTML .= "  }\n";
                                                        $field_HTML .= "</script>\n";
                                                        $field_HTML .= "</head>\n\n";

                                                        $field_HTML .= "<body>\n";
                                                        $field_HTML .= "<a href=\"javascript:close_file();\"><font size=1 face=\"Arial,Helvetica\">close frame</font></a>\n";
                                                        $field_HTML .= "<div id='selectframe' style=\"height:400px;width:710px;overflow:scroll;\">\n";
                                                        $field_HTML .= "<table border=0 cellpadding=1 cellspacing=2 width=690 bgcolor=white style=\"font-family:Arial,Helvetica\"><tr>\n";
                                                        $field_HTML .= "<td>#</td>\n";
                                                        $field_HTML .= "<td><a href=\"$PHP_SELF?source=admin&function=sounds_list&user=$user&pass=$pass&format=selectframe&comments=$comments&stage=name\"><font color=black>FILENAME</td>\n";
                                                        $field_HTML .= "<td><a href=\"$PHP_SELF?source=admin&function=sounds_list&user=$user&pass=$pass&format=selectframe&comments=$comments&stage=date\"><font color=black>DATE</td>\n";
                                                        $field_HTML .= "<td><a href=\"$PHP_SELF?source=admin&function=sounds_list&user=$user&pass=$pass&format=selectframe&comments=$comments&stage=size\"><font color=black>SIZE</td>\n";
                                                        $field_HTML .= "<td>PLAY</td>\n";
                                                        $field_HTML .= "</tr>\n";
                                                        }
                                                $sf++;
                                                $field_HTML .= "<tr><td><font size=1 face=\"Arial,Helvetica\">$sf</td>\n";
                                                $field_HTML .= "<td><a href=\"javascript:choose_file('$file_namesPROMPT[$m]','$comments');\"><font size=1 face=\"Arial,Helvetica\">$file_names[$m]</a></td>\n";
                                                $field_HTML .= "<td><font size=1 face=\"Arial,Helvetica\">$file_dates[$m]</td>\n";
                                                $field_HTML .= "<td><font size=1 face=\"Arial,Helvetica\">$file_sizes[$m]</td>\n";
                                                $field_HTML .= "<td><a href=\"$admDIR/$sounds_web_directory/$file_names[$m]\" target=\"_blank\"><font size=1 face=\"Arial,Helvetica\">PLAY</a></td></tr>\n";
                                                
												$itemsumitexplode = explode('.', $file_names[$m]);
												$soundfiles .= "<option value=$itemsumitexplode[0]> $itemsumitexplode[0] </option>";
														 
                                                }
                                        }
                                       
                                $k++;
                                }
                                
								$finalHTML .= "<select onchange=\"java_script_:setDivVal('$comments',this.options[this.selectedIndex].value)\">";
								$finalHTML .= "<option value=\"$defaultvalue\" selected>-- Default Value --</option>";
								$finalHTML .= $soundfiles;
								$finalHTML .= "</select>";
								
								echo $finalHTML;
                                 		  
	                        if ($sf > 0){
	                        	//echo "</table></div></body></HTML>\n";
								}

                        exit;

       			}
		}
}
#### END sound list #####


#### Start VM List ######
if ($action == 'vm_list') {
	$stmt="SELECT count(*) as countuser from vicidial_users where user='$user' and user_level > 6;";
	$rslt = $this->asteriskDB->query($stmt);
	$row = $rslt->row(); 
	$allowed_user=$row->countuser;

	if ($allowed_user < 1) {
		$result = 'ERROR';
		$result_reason = "vm_list USER DOES NOT HAVE PERMISSION TO VIEW VOICEMAIL BOXES LIST";
		echo "$result: $result_reason: |$user|$allowed_user|\n";
		$data = "$allowed_user";
		exit;
	} else {
		$server_name = getenv("SERVER_NAME");
		$server_port = getenv("SERVER_PORT");
		if (eregi("443",$server_port)) {$HTTPprotocol = 'https://';}
		  else {$HTTPprotocol = 'http://';}
		$admDIR = "$HTTPprotocol$server_name:$server_port";


		/*$stmt="SELECT user_group from vicidial_users where user='$user' and user_level > 6;";
		die($stmt);*/
	/*	$rslt = $this->asteriskDB->query($stmt);
		$row = $rslt->row(); 
		$allowed_user=$row->countuser;*/
		
		$filterSQL = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "and user_group='".$this->session->userdata('user_group')."'" : "";

		$stmt="SELECT voicemail_id,fullname,email from vicidial_voicemail where active='Y' $filterSQL order by voicemail_id";
		$rslt2 = $this->asteriskDB->query($stmt);
		$vm_to_print = $rslt2->num_rows;
		$k=0;
		$sf=0;
		
		foreach ($rslt2->result_array() as $rowx) {
				 $k++;
				 $voicemail_id[$k] =        $rowx['voicemail_id'];
				 $fullname[$k] =		$rowx['fullname'];
				 $email[$k] =		$rowx['email'];
				 $vmfiles .= "<option value=$voicemail_id[$k]> $voicemail_id[$k] - $fullname[$k] </option>";
		}
		
		$stmt="SELECT voicemail_id,fullname,email from phones where active='Y' $filterSQL order by voicemail_id";
		$rslt3 = $this->asteriskDB->query($stmt);
		$vm_to_print = $rslt3->num_rows;
		//$k=0;
		//$sf=0;
		
		foreach ($rslt3->result_array() as $rowx) {
				 $k++;
				 $voicemail_id[$k] =        $rowx['voicemail_id'];
				 $fullname[$k] =		$rowx['fullname'];
				 $email[$k] =		$rowx['email'];
				 $vmfiles .= "<option value=$voicemail_id[$k]> $voicemail_id[$k] - $fullname[$k] </option>";
		}
// 			id=vmfilesdrop  
		    $finalHTML .= "<select onchange=\"java_script_:setDivVal('$comments',this.options[this.selectedIndex].value)\">";
			$finalHTML .= "<option value=\"$defaultvalue\" selected>-- Default Value --</option>";
			$finalHTML .= $vmfiles;
			$finalHTML .= "</select>";
			
			echo $finalHTML;

			exit;
		}
	}
#### END VM List ####


#### START MOH List ####
if ($action == 'moh_list')
	{
	$stmt="SELECT count(*) as countuser from vicidial_users where user='$user' and user_level > 6;";
	$rslt = $this->asteriskDB->query($stmt);
	$row = $rslt->row(); 
	$allowed_user=$row->countuser;


	if ($allowed_user < 1)
		{
		$result = 'ERROR';
		$result_reason = "sounds_list USER DOES NOT HAVE PERMISSION TO VIEW SOUNDS LIST";
		$data = "$allowed_user";
		exit;
		}
	else
		{
		$server_name = getenv("SERVER_NAME");
		$server_port = getenv("SERVER_PORT");
		if (eregi("443",$server_port)) {$HTTPprotocol = 'https://';}
		  else {$HTTPprotocol = 'http://';}
		$admDIR = "$HTTPprotocol$server_name:$server_port";

		#############################################
		##### START SYSTEM_SETTINGS LOOKUP #####
		$stmt = "SELECT use_non_latin,sounds_central_control_active,sounds_web_server,sounds_web_directory FROM system_settings;";
		$rslt = $this->asteriskDB->query($stmt);
       $ss_conf_ct = $rslt->num_rows;

       if ($ss_conf_ct > 0) {
       	$row = $rslt->row();
			$non_latin =						$row->use_non_latin;
			$sounds_central_control_active =	$row->sounds_central_control_active;
			$sounds_web_server =				$row->sounds_web_server;
			$sounds_web_directory =				$row->sounds_web_directory;
		}
		##### END SETTINGS LOOKUP #####
		###########################################

		if ($sounds_central_control_active < 1)
			{
			$result = 'ERROR';
			$result_reason = "sounds_list CENTRAL SOUND CONTROL IS NOT ACTIVE";
			$data = "$sounds_central_control_active";
			exit;
			}
		else
			{

			$filterSQL = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "and user_group='".$this->session->userdata('user_group')."'" : "";

			$stmt="SELECT moh_id,moh_name,random from vicidial_music_on_hold where active='Y' and moh_id='$user' $filterSQL order by moh_id";
			$rslt2 = $this->asteriskDB->query($stmt);
			$moh_to_print = $rslt2->num_rows;
			$k=0;
			$sf=0;
			
			foreach ($rslt2->result_array() as $rowx) {
				$k++;				
				$moh_id[$k] =	$rowx['moh_id'];
				$moh_name[$k] = $rowx['moh_name'];
				$random[$k] =	$rowx['random'];
				
				$mohfiles .= "<option value=$moh_id[$k]> $moh_id[$k] - $moh_name[$k] </option>";
			}
			
		    $finalHTML .= "<select name=mohfilesdrop id=mohfilesdrop  onchange=\"java_script_:setDivVal('$comments',this.options[this.selectedIndex].value)\">";
			$finalHTML .= "<option value=\"$defaultvalue\" selected>-- Default Value --</option>";
			$finalHTML .= $mohfiles;
			$finalHTML .= "</select>";
			
			echo $finalHTML;

			exit;
			}
		}
	}

#### END MOH List ####

?>
