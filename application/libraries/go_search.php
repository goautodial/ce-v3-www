<?php
########################################################################################################
####  Name:             	go_search.php                                                       ####
####  Type:             	ci libraries - administrator                                        ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Writeen by:      	        Rodolfo Januarius T. Manipol                                        ####
####  Edited by:	        GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

//$this->load->model('go_dashboard');

//$db = $this->load->database('dialerdb', true);

	$db = new mysqli('localhost', 'cron' ,'1234', 'asterisk');
	
	if(!$db) {
		echo 'ERROR: Could not connect to the database.';
	} else {
		// Is there a posted query string?
		if(isset($_POST['queryString'])) {
			$queryString = $db->real_escape_string($_POST['queryString']);
			
			// Is the string length greater than 0?
			
			if(strlen($queryString) >0) {
				// Run the query: We use LIKE '$queryString%'
				// The percentage sign is a wild-card, in my example of countries it works like this...
				// $queryString = 'Uni';
				// Returned data = 'United States, United Kindom';
				
				// YOU NEED TO ALTER THE QUERY TO MATCH YOUR DATABASE.
				// eg: SELECT yourColumnName FROM yourTable WHERE yourColumnName LIKE '$queryString%' LIMIT 10

				$query = $db->query("SELECT user FROM vicidial_users WHERE user rlike '$queryString' order by user asc LIMIT 10");
    $row_cnt = $query->num_rows;

				if($row_cnt>=1) {
					
					
					echo '<ul>User</ul>';

					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($result = $query ->fetch_object()) {
						// Format the results, im using <li> for the list, you can change it.
						// The onClick function fills the textbox with the result.
						
						// YOU MUST CHANGE: $result->value to $result->your_colum

	         			echo '<li onClick="fill(\''.$result->user.'\');">'.$result->user.'</li>';
	         		}
				}



				$query_list = $db->query("SELECT first_name,last_name FROM vicidial_list WHERE CONCAT_WS(' ', first_name, last_name) rlike '$queryString' LIMIT 10");
    $row_cnt_list = $query_list->num_rows;

if($row_cnt_list>=1) {
				

					echo '<ul>Lead</ul>';


					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($result = $query_list ->fetch_object()) {
						// Format the results, im using <li> for the list, you can change it.
						// The onClick function fills the textbox with the result.
						
						// YOU MUST CHANGE: $result->value to $result->your_colum

	         			echo '<li onClick="fill(\''.$result->first_name.'  '.$result->last_name.'\');">'.$result->first_name.'  '.$result->last_name.'</li>';

					}
}



				$query_phone = $db->query("SELECT phone_number FROM vicidial_list WHERE phone_number rlike '$queryString' LIMIT 10");
    $row_cnt_phone = $query_phone->num_rows;
if($row_cnt_phone>=1) {
				

					echo '<ul>Phone Number</ul>';


					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($result = $query_phone ->fetch_object()) {
						// Format the results, im using <li> for the list, you can change it.
						// The onClick function fills the textbox with the result.
						
						// YOU MUST CHANGE: $result->value to $result->your_colum

	         			echo '<li onClick="fill(\''.$result->phone_number.'\');">'.$result->phone_number.'</li>';

					}
}




			} else {
	         			//echo 'Data not found!';
			} // There is a queryString.
		} else {
			echo 'There should be no direct access to this script!';
		}
	}
?>
