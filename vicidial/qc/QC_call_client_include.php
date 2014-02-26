<?php
# Copyright (C) 2012  poundteam.com    LICENSE: AGPLv2
#
# changes:
# 121116-1328 - First build, added to vicidial codebase
#
/* QC_call_client_include.php
 * Part of QC system by Poundteam.
 * Location: /vicidial/qc
 * Included in: qc_modify_lead.php
 * Purpose: Determine availability of "Call Lead" button and call the lead when button is pressed.
 * Also: display REASON if lead cannot be called by pressing button (to allow agent to resolve the reason without guessing)
 */
/* 1. Is logged in admin user also logged in as an agent?
 *    If not: display admin user name followed by "not logged in as agent"
 * 2. Is logged in agent available to manual dial this prospect?
 *    Requires pause or manual mode and campaign with this prospect available.
 * 3. If possible, log the agent in with NO permission to change data on the agent screen. Require changes ONLY on QC screen (except hangup button).
 */

//Is logged in admin user also logged in as an agent?
function is_user_logged_in($user){
    global $link;
    $stmt="select status from vicidial_live_agents where user='$user'";
    if ($DB) {echo "|$stmt|\n";}
    $rslt=mysql_query($stmt, $link);
    $live_agent_count = mysql_num_rows($rslt);
    if($live_agent_count != '1'){
        if($live_agent_count == '0'){
            return "Cannot call prospect. You are not logged in as $user.";
        } else {
            return "Cannot call prospect. $live_agent_count agents logged in as $user.";
        }
    }
    $row=mysql_fetch_row($rslt);
    $status = $row[0];
    if($status!='PAUSED'){
        return "Status must be paused to call lead. $user is presently in $status status.";
    }
//@TODO: check user is in the correct CAMPAIGN for this lead.

    return true;
}

?>
