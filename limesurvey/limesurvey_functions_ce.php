<?php
############################################################################################
####  Name:             limesurvey_functions.php                                        ####
####  Type:             functions for limesurvey integration                            ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Chris Lomuntad <chris@goautodial.com>         ####
####  License:          AGPLv2                                                          ####
############################################################################################

function GOGetBaseLanguageFromSurveyID($surveyid)
{
    static $cache = array();
    global $dbprefix, $connect, $limedb, $link;
    $surveyid=(int)($surveyid);
    if (!isset($cache[$surveyid])) {
        $query = "SELECT language FROM {$limedb}.{$dbprefix}surveys WHERE sid=$surveyid";
		$result = mysql_query($query, $link);
        $surveylanguage = mysql_result($result, 0); //Checked
        if (is_null($surveylanguage))
        {
            $surveylanguage='en';
        }
        $cache[$surveyid] = $surveylanguage;
    } else {
        $surveylanguage = $cache[$surveyid];
    }
    return $surveylanguage;
}

function GOgetSubQuestions($sid, $qid, $sLanguage) {
    global $dbprefix, $connect, $clang, $limedb, $link;
    static $subquestions;

    if (!isset($subquestions[$sid])) {
        $sid = sanitize_int($sid);
        $query = "SELECT sq.*, q.other FROM {$limedb}.{$dbprefix}questions as sq, {$limedb}.{$dbprefix}questions as q"
        ." WHERE sq.parent_qid=q.qid AND q.sid=$sid"
        ." AND sq.language='".$sLanguage. "' "
        ." AND q.language='".$sLanguage. "' "
        ." ORDER BY sq.parent_qid, q.question_order,sq.scale_id , sq.question_order";
        $result=mysql_query($query, $link) or safe_die ("Couldn't get perform answers query<br />$query<br />".$connect->ErrorMsg());    //Checked
        $resultset=array();
        while ($row=mysql_fetch_assoc($result))
        {
            $resultset[$row['parent_qid']][] = $row;
        }
        $subquestions[$sid] = $resultset;
    }
    if (isset($subquestions[$sid][$qid])) return $subquestions[$sid][$qid];
    return array();
}

function GOcreateFieldMap($surveyid, $style='short', $force_refresh=false, $questionid=false, $sQuestionLanguage=null) {

    global $dbprefix, $connect, $globalfieldmap, $clang, $aDuplicateQIDs, $limedb, $link;
    $surveyid=sanitize_int($surveyid);
    //checks to see if fieldmap has already been built for this page.
    if (isset($globalfieldmap[$surveyid][$style][$clang->langcode]) && $force_refresh==false) {
        return $globalfieldmap[$surveyid][$style][$clang->langcode];
    }

    $fieldmap["id"]=array("fieldname"=>"id", 'sid'=>$surveyid, 'type'=>"id", "gid"=>"", "qid"=>"", "aid"=>"");
    if ($style == "full")
    {
        $fieldmap["id"]['title']="";
        $fieldmap["id"]['question']=$clang->gT("Response ID");
        $fieldmap["id"]['group_name']="";
    }

    $fieldmap["submitdate"]=array("fieldname"=>"submitdate", 'type'=>"submitdate", 'sid'=>$surveyid, "gid"=>"", "qid"=>"", "aid"=>"");
    if ($style == "full")
    {
        $fieldmap["submitdate"]['title']="";
        $fieldmap["submitdate"]['question']=$clang->gT("Date submitted");
        $fieldmap["submitdate"]['group_name']="";
    }

    $fieldmap["lastpage"]=array("fieldname"=>"lastpage", 'sid'=>$surveyid, 'type'=>"lastpage", "gid"=>"", "qid"=>"", "aid"=>"");
    if ($style == "full")
    {
        $fieldmap["lastpage"]['title']="";
        $fieldmap["lastpage"]['question']=$clang->gT("Last page");
        $fieldmap["lastpage"]['group_name']="";
    }

    $fieldmap["startlanguage"]=array("fieldname"=>"startlanguage", 'sid'=>$surveyid, 'type'=>"startlanguage", "gid"=>"", "qid"=>"", "aid"=>"");
    if ($style == "full")
    {
        $fieldmap["startlanguage"]['title']="";
        $fieldmap["startlanguage"]['question']=$clang->gT("Start language");
        $fieldmap["startlanguage"]['group_name']="";
    }


    //Check for any additional fields for this survey and create necessary fields (token and datestamp and ipaddr)
    $pquery = "SELECT anonymized, datestamp, ipaddr, refurl FROM {$limedb}.{$dbprefix}surveys WHERE sid=$surveyid";
    $presult=mysql_query($pquery, $link); //Checked
    while($prow=mysql_fetch_assoc($presult))
    {
        if ($prow['anonymized'] == "N")
        {
            $fieldmap["token"]=array("fieldname"=>"token", 'sid'=>$surveyid, 'type'=>"token", "gid"=>"", "qid"=>"", "aid"=>"");
            if ($style == "full")
            {
                $fieldmap["token"]['title']="";
                $fieldmap["token"]['question']=$clang->gT("Token");
                $fieldmap["token"]['group_name']="";
            }
        }
        if ($prow['datestamp'] == "Y")
        {
            $fieldmap["datestamp"]=array("fieldname"=>"datestamp",
            'type'=>"datestamp",
            'sid'=>$surveyid,
            "gid"=>"",
            "qid"=>"",
            "aid"=>"");
            if ($style == "full")
            {
                $fieldmap["datestamp"]['title']="";
                $fieldmap["datestamp"]['question']=$clang->gT("Date last action");
                $fieldmap["datestamp"]['group_name']="";
            }
            $fieldmap["startdate"]=array("fieldname"=>"startdate",
            'type'=>"startdate",
            'sid'=>$surveyid,
            "gid"=>"",
            "qid"=>"",
            "aid"=>"");
            if ($style == "full")
            {
                $fieldmap["startdate"]['title']="";
                $fieldmap["startdate"]['question']=$clang->gT("Date started");
                $fieldmap["startdate"]['group_name']="";
            }

        }
        if ($prow['ipaddr'] == "Y")
        {
            $fieldmap["ipaddr"]=array("fieldname"=>"ipaddr",
            'type'=>"ipaddress",
            'sid'=>$surveyid,
            "gid"=>"",
            "qid"=>"",
            "aid"=>"");
            if ($style == "full")
            {
                $fieldmap["ipaddr"]['title']="";
                $fieldmap["ipaddr"]['question']=$clang->gT("IP address");
                $fieldmap["ipaddr"]['group_name']="";
            }
        }
        // Add 'refurl' to fieldmap.
        if ($prow['refurl'] == "Y")
        {
            $fieldmap["refurl"]=array("fieldname"=>"refurl", 'type'=>"url", 'sid'=>$surveyid, "gid"=>"", "qid"=>"", "aid"=>"");
            if ($style == "full")
            {
                $fieldmap["refurl"]['title']="";
                $fieldmap["refurl"]['question']=$clang->gT("Referrer URL");
                $fieldmap["refurl"]['group_name']="";
            }
        }
    }

    //Get list of questions
    if (is_null($sQuestionLanguage))
    {
        $s_lang = GOGetBaseLanguageFromSurveyID($surveyid);
    }
    else
    {
        $s_lang = $sQuestionLanguage;
    }
    $qtypes=getqtypelist('','array');
    $aquery = "SELECT *, "
    ." (SELECT count(1) FROM {$limedb}.{$dbprefix}conditions c\n"
    ." WHERE questions.qid = c.qid) AS hasconditions,\n"
    ." (SELECT count(1) FROM {$limedb}.{$dbprefix}conditions c\n"
    ." WHERE questions.qid = c.cqid) AS usedinconditions\n"
    ." FROM {$limedb}.{$dbprefix}questions as questions, {$limedb}.{$dbprefix}groups as groups"
    ." WHERE questions.gid=groups.gid AND "
    ." questions.sid=$surveyid AND "
    ." questions.language='{$s_lang}' AND "
    ." questions.parent_qid=0 AND "
    ." groups.language='{$s_lang}' ";
    if ($questionid!==false)
    {
        $aquery.=" and questions.qid={$questionid} ";
    }
    $aquery.=" ORDER BY group_order, question_order";
    $aresult = mysql_query($aquery, $link) or safe_die ("Couldn't get list of questions in GOcreateFieldMap function.<br />$query<br />".$connect->ErrorMsg()); //Checked

    while ($arow=mysql_fetch_assoc($aresult)) //With each question, create the appropriate field(s)
    {
        if ($arow['hasconditions']>0)
        {
            $conditions = "Y";
        }
        else
        {
            $conditions = "N";
        }
        if ($arow['usedinconditions']>0)
        {
            $usedinconditions = "Y";
        }
        else
        {
            // This question is not directly used in a condition, however we should
            // check if its SGQA code is not used as a value in another condition
            // as a @SGQA@ code
            $atsgqaQuery = "SELECT count(1) as sgqausedincondition "
            . "FROM {$limedb}.{$dbprefix}questions as q, "
            . "{$limedb}.{$dbprefix}conditions as c "
            . "WHERE c.qid=q.qid AND q.sid=".$arow['sid']." AND "
            . "c.value like '@".$arow['sid']."X".$arow['gid']."X".$arow['qid']."%'";
            $atsgqaResult = mysql_query($atsgqaQuery, $link) or safe_die ("Couldn't get list @sgqa@ conditions in GOcreateFieldMap function.<br />$atsgqaQuery<br />".$connect->ErrorMsg()); //Checked
            $atsgqaRow = mysql_fetch_assoc($atsgqaResult);
            if ($atsgqaRow['sgqausedincondition'] == 0 )
            {
                $usedinconditions = "N";
            }
            else
            {
                $usedinconditions = "Y";
            }
        }

        // Field identifier
        // GXQXSXA
        // G=Group  Q=Question S=Subquestion A=Answer Option
        // If S or A don't exist then set it to 0
        // Implicit (subqestion intermal to a question type ) or explicit qubquestions/answer count starts at 1

        // Types "L", "!" , "O", "D", "G", "N", "X", "Y", "5","S","T","U"

        if ($qtypes[$arow['type']]['subquestions']==0  && $arow['type'] != "R" && $arow['type'] != "|")
        {
            $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}";
            if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);
            $fieldmap[$fieldname]=array("fieldname"=>$fieldname, 'type'=>"{$arow['type']}", 'sid'=>$surveyid, "gid"=>$arow['gid'], "qid"=>$arow['qid'], "aid"=>"");
            if ($style == "full")
            {
                $fieldmap[$fieldname]['title']=$arow['title'];
                $fieldmap[$fieldname]['question']=$arow['question'];
                $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                $fieldmap[$fieldname]['hasconditions']=$conditions;
                $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                if ($qtypes[$arow['type']]['hasdefaultvalues'])
                {
                    if ($arow['same_default'])
                    {
                        $fieldmap[$fieldname]['defaultvalue']=mysql_result(mysql_query("SELECT defaultvalue FROM {$limedb}.{$dbprefix}defaultvalues WHERE qid={$arow['qid']} AND scale_id=0 AND language='".GOGetBaseLanguageFromSurveyID($surveyid)."'", $link), 0);
                    }
                    else
                    {
                        $fieldmap[$fieldname]['defaultvalue']=mysql_result(mysql_query("SELECT defaultvalue FROM {$limedb}.{$dbprefix}defaultvalues WHERE qid={$arow['qid']} AND scale_id=0 AND language='{$clang->langcode}'", $link), 0);
                    }
                }
            }
            switch($arow['type'])
            {
                case "L":  //RADIO LIST
                case "!":  //DROPDOWN LIST
                    if ($arow['other'] == "Y")
                    {
                        $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}other";
                        if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);

                        $fieldmap[$fieldname]=array("fieldname"=>$fieldname,
                        'type'=>$arow['type'],
                        'sid'=>$surveyid,
                        "gid"=>$arow['gid'],
                        "qid"=>$arow['qid'],
                        "aid"=>"other");
                        // dgk bug fix line above. aid should be set to "other" for export to append to the field name in the header line.
                        if ($style == "full")
                        {
                            $fieldmap[$fieldname]['title']=$arow['title'];
                            $fieldmap[$fieldname]['question']=$arow['question'];
                            $fieldmap[$fieldname]['subquestion']=$clang->gT("Other");
                            $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                            $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                            $fieldmap[$fieldname]['hasconditions']=$conditions;
                            $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                            if ($arow['same_default'])
                            {
                                $fieldmap[$fieldname]['defaultvalue']=mysql_result(mysql_query("SELECT defaultvalue FROM {$limedb}.{$dbprefix}defaultvalues WHERE qid={$arow['qid']} AND scale_id=0 AND language='".GetBaseLanguageFromSurveyID($surveyid)."' and specialtype='other'", $link), 0);
                            }
                            else
                            {
                                $fieldmap[$fieldname]['defaultvalue']=mysql_result(mysql_query("SELECT defaultvalue FROM {$limedb}.{$dbprefix}defaultvalues WHERE qid={$arow['qid']} AND scale_id=0 AND language='{$clang->langcode}' and specialtype='other'", $link), 0);
                            }
                        }
                    }
                    break;
                case "O": //DROPDOWN LIST WITH COMMENT
                    $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}comment";
                    if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);

                    $fieldmap[$fieldname]=array("fieldname"=>$fieldname,
                    'type'=>$arow['type'],
                    'sid'=>$surveyid,
                    "gid"=>$arow['gid'],
                    "qid"=>$arow['qid'],
                    "aid"=>"comment");
                    // dgk bug fix line below. aid should be set to "comment" for export to append to the field name in the header line. Also needed set the type element correctly.
                    if ($style == "full")
                    {
                        $fieldmap[$fieldname]['title']=$arow['title'];
                        $fieldmap[$fieldname]['question']=$arow['question'];
                        $fieldmap[$fieldname]['subquestion']=$clang->gT("Comment");
                        $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                        $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                        $fieldmap[$fieldname]['hasconditions']=$conditions;
                        $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                    }
                    break;
            }
        }
        // For Multi flexi question types
        elseif ($qtypes[$arow['type']]['subquestions']==2 && $qtypes[$arow['type']]['answerscales']==0)
        {
            //MULTI FLEXI
            $abrows = GOgetSubQuestions($surveyid,$arow['qid'],$s_lang);
            //Now first process scale=1
            $answerset=array();
            foreach ($abrows as $key=>$abrow)
            {
                if($abrow['scale_id']==1) {
                    $answerset[]=$abrow;
                    unset($abrows[$key]);
                }
            }
            reset($abrows);
            foreach ($abrows as $abrow)
            {
                foreach($answerset as $answer)
                {
                    $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}{$abrow['title']}_{$answer['title']}";
                    if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);
                    $fieldmap[$fieldname]=array("fieldname"=>$fieldname,
                    'type'=>$arow['type'],
                    'sid'=>$surveyid,
                    "gid"=>$arow['gid'],
                    "qid"=>$arow['qid'],
                    "aid"=>$abrow['title']."_".$answer['title'],
                    "sqid"=>$abrow['qid']);
                    if ($abrow['other']=="Y") {$alsoother="Y";}
                    if ($style == "full")
                    {
                        $fieldmap[$fieldname]['title']=$arow['title'];
                        $fieldmap[$fieldname]['question']=$arow['question'];
                        $fieldmap[$fieldname]['subquestion1']=$abrow['question'];
                        $fieldmap[$fieldname]['subquestion2']=$answer['question'];
                        $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                        $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                        $fieldmap[$fieldname]['hasconditions']=$conditions;
                        $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                    }
                }
            }
            unset($answerset);
        }
        elseif ($arow['type'] == "1")
        {
            $abrows = GOgetSubQuestions($surveyid,$arow['qid'],$s_lang);
            foreach ($abrows as $abrow)
            {
                $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}{$abrow['title']}#0";
                if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);
                $fieldmap[$fieldname]=array("fieldname"=>$fieldname, 'type'=>$arow['type'], 'sid'=>$surveyid, "gid"=>$arow['gid'], "qid"=>$arow['qid'], "aid"=>$abrow['title'], "scale_id"=>0);
                if ($style == "full")
                {
                    $fieldmap[$fieldname]['title']=$arow['title'];
                    $fieldmap[$fieldname]['question']=$arow['question'];
                    $fieldmap[$fieldname]['subquestion']=$abrow['question'];
                    $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                    $fieldmap[$fieldname]['scale']=$clang->gT('Scale 1');
                    $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                    $fieldmap[$fieldname]['hasconditions']=$conditions;
                    $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                }

                $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}{$abrow['title']}#1";
                if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);
                $fieldmap[$fieldname]=array("fieldname"=>$fieldname, 'type'=>$arow['type'], 'sid'=>$surveyid, "gid"=>$arow['gid'], "qid"=>$arow['qid'], "aid"=>$abrow['title'], "scale_id"=>1);
                if ($style == "full")
                {
                    $fieldmap[$fieldname]['title']=$arow['title'];
                    $fieldmap[$fieldname]['question']=$arow['question'];
                    $fieldmap[$fieldname]['subquestion']=$abrow['question'];
                    $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                    $fieldmap[$fieldname]['scale']=$clang->gT('Scale 2');
                    $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                    $fieldmap[$fieldname]['hasconditions']=$conditions;
                    $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                }
            }
        }

        elseif ($arow['type'] == "R")
        {
            //MULTI ENTRY
            $slots=mysql_result(mysql_query("select count(code) from {$limedb}.{$dbprefix}answers where qid={$arow['qid']} and language='{$s_lang}'", $link), 0);
            for ($i=1; $i<=$slots; $i++)
            {
                $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}$i";
                if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);
                $fieldmap[$fieldname]=array("fieldname"=>$fieldname, 'type'=>$arow['type'], 'sid'=>$surveyid, "gid"=>$arow['gid'], "qid"=>$arow['qid'], "aid"=>$i);
                if ($style == "full")
                {
                    $fieldmap[$fieldname]['title']=$arow['title'];
                    $fieldmap[$fieldname]['question']=$arow['question'];
                    $fieldmap[$fieldname]['subquestion']=sprintf($clang->gT('Rank %s'),$i);
                    $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                    $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                    $fieldmap[$fieldname]['hasconditions']=$conditions;
                    $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                }
            }
        }
        elseif ($arow['type'] == "|")
        {
            $abquery = "SELECT value FROM {$limedb}.{$dbprefix}question_attributes"
            ." WHERE attribute='max_num_of_files' AND qid=".$arow['qid'];
            $abresult = mysql_query($abquery, $link) or safe_die ("Couldn't get maximum number of files that can be uploaded <br />$abquery<br />".$connect->ErrorMsg());
            $abrow = mysql_fetch_assoc($abresult);

            for ($i = 1; $i <= $abrow['value']; $i++)
            {
                $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}";
                $fieldmap[$fieldname]=array("fieldname"=>$fieldname,
                'type'=>$arow['type'],
                'sid'=>$surveyid,
                "gid"=>$arow['gid'],
                "qid"=>$arow['qid'],
                "aid"=>''
                );
                if ($style == "full")
                {
                    $fieldmap[$fieldname]['title']=$arow['title'];
                    $fieldmap[$fieldname]['question']=$arow['question'];
                    $fieldmap[$fieldname]['max_files']=$abrow['value'];
                    $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                    $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                    $fieldmap[$fieldname]['hasconditions']=$conditions;
                    $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                }
                $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}"."_filecount";
                $fieldmap[$fieldname]=array("fieldname"=>$fieldname,
                'type'=>$arow['type'],
                'sid'=>$surveyid,
                "gid"=>$arow['gid'],
                "qid"=>$arow['qid'],
                "aid"=>"filecount"
                );
                if ($style == "full")
                {
                    $fieldmap[$fieldname]['title']=$arow['title'];
                    $fieldmap[$fieldname]['question']="filecount - ".$arow['question'];
                    //$fieldmap[$fieldname]['subquestion']=$clang->gT("Comment");
                    $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                    $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                    $fieldmap[$fieldname]['hasconditions']=$conditions;
                    $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                }
            }
        }
        else  // Question types with subquestions and one answer per subquestion  (M/A/B/C/E/F/H/P)
        {
            //MULTI ENTRY
            $abrows = GOgetSubQuestions($surveyid,$arow['qid'],$s_lang);
            foreach ($abrows as $abrow)
            {
                $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}{$abrow['title']}";
                if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);
                $fieldmap[$fieldname]=array("fieldname"=>$fieldname, 'type'=>$arow['type'], 'sid'=>$surveyid, "gid"=>$arow['gid'], "qid"=>$arow['qid'], "aid"=>$abrow['title']);
                if ($style == "full")
                {
                    $fieldmap[$fieldname]['title']=$arow['title'];
                    $fieldmap[$fieldname]['question']=$arow['question'];
                    $fieldmap[$fieldname]['subquestion']=$abrow['question'];
                    $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                    $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                    $fieldmap[$fieldname]['hasconditions']=$conditions;
                    $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                    if ($arow['same_default'])
                    {
                        $fieldmap[$fieldname]['defaultvalue']=mysql_result(mysql_query("SELECT defaultvalue FROM {$limedb}.{$dbprefix}defaultvalues WHERE sqid={$abrow['qid']} and qid={$arow['qid']} AND scale_id=0 AND language='".GOGetBaseLanguageFromSurveyID($surveyid)."'", $link), 0);
                    }
                    else
                    {
                        $fieldmap[$fieldname]['defaultvalue']=mysql_result(mysql_query("SELECT defaultvalue FROM {$limedb}.{$dbprefix}defaultvalues WHERE sqid={$abrow['qid']} and qid={$arow['qid']} AND scale_id=0 AND language='{$clang->langcode}'", $link), 0);
                    }
                }
                if ($arow['type'] == "P")
                {
                    $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}{$abrow['title']}comment";
                    if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);
                    $fieldmap[$fieldname]=array("fieldname"=>$fieldname, 'type'=>$arow['type'], 'sid'=>$surveyid, "gid"=>$arow['gid'], "qid"=>$arow['qid'], "aid"=>$abrow['title']."comment");
                    if ($style == "full")
                    {
                        $fieldmap[$fieldname]['title']=$arow['title'];
                        $fieldmap[$fieldname]['question']=$arow['question'];
                        $fieldmap[$fieldname]['subquestion']=$clang->gT('Comment');
                        $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                        $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                        $fieldmap[$fieldname]['hasconditions']=$conditions;
                        $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                    }
                }
            }
            if ($arow['other']=="Y" && ($arow['type']=="M" || $arow['type']=="P"))
            {
                $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}other";
                if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);
                $fieldmap[$fieldname]=array("fieldname"=>$fieldname, 'type'=>$arow['type'], 'sid'=>$surveyid, "gid"=>$arow['gid'], "qid"=>$arow['qid'], "aid"=>"other");
                if ($style == "full")
                {
                    $fieldmap[$fieldname]['title']=$arow['title'];
                    $fieldmap[$fieldname]['question']=$arow['question'];
                    $fieldmap[$fieldname]['subquestion']=$clang->gT('Other');
                    $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                    $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                    $fieldmap[$fieldname]['hasconditions']=$conditions;
                    $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                }
                if ($arow['type']=="P")
                {
                    $fieldname="{$arow['sid']}X{$arow['gid']}X{$arow['qid']}othercomment";
                    if (isset($fieldmap[$fieldname])) $aDuplicateQIDs[$arow['qid']]=array('fieldname'=>$fieldname,'question'=>$arow['question'],'gid'=>$arow['gid']);
                    $fieldmap[$fieldname]=array("fieldname"=>$fieldname, 'type'=>$arow['type'], 'sid'=>$surveyid, "gid"=>$arow['gid'], "qid"=>$arow['qid'], "aid"=>"othercomment");
                    if ($style == "full")
                    {
                        $fieldmap[$fieldname]['title']=$arow['title'];
                        $fieldmap[$fieldname]['question']=$arow['question'];
                        $fieldmap[$fieldname]['subquestion']=$clang->gT('Other comment');
                        $fieldmap[$fieldname]['group_name']=$arow['group_name'];
                        $fieldmap[$fieldname]['mandatory']=$arow['mandatory'];
                        $fieldmap[$fieldname]['hasconditions']=$conditions;
                        $fieldmap[$fieldname]['usedinconditions']=$usedinconditions;
                    }
                }
            }
        }
    }
    if (isset($fieldmap)) {
        $globalfieldmap[$surveyid][$style][$clang->langcode] = $fieldmap;
        return $fieldmap;
    }
}

function GOGetAttributeFieldNames($surveyid)
{
    global $dbprefix, $connect;
//    if (tableExists('tokens_'.$surveyid) === false)
//    {
//        return Array();
//    }
    $tokenfieldnames = array_values($connect->MetaColumnNames("{$dbprefix}tokens_$surveyid", true));
    return array_filter($tokenfieldnames,'filterforattributes');
}


function GOGetTokenFieldsAndNames($surveyid, $onlyAttributes=false)
{
    global $dbprefix, $connect, $clang;
//    if (tableExists('tokens_'.$surveyid) === false)
//    {
//        return Array();
//    }
    $extra_attrs=GOGetAttributeFieldNames($surveyid);
    $basic_attrs=Array('firstname','lastname','email','token','language','sent','remindersent','remindercount','usesleft');
    $basic_attrs_names=Array(
    $clang->gT('First name'),
    $clang->gT('Last name'),
    $clang->gT('Email address'),
    $clang->gT('Token code'),
    $clang->gT('Language code'),
    $clang->gT('Invitation sent date'),
    $clang->gT('Last Reminder sent date'),
    $clang->gT('Total numbers of sent reminders'),
    $clang->gT('Uses left')
    );

    $thissurvey=getSurveyInfo($surveyid);
    $attdescriptiondata=!empty($thissurvey['attributedescriptions']) ? $thissurvey['attributedescriptions'] : "";
    $attdescriptiondata=explode("\n",$attdescriptiondata);
    $attributedescriptions=array();
    $basic_attrs_and_names=array();
    $extra_attrs_and_names=array();
    foreach ($attdescriptiondata as $attdescription)
    {
        $attributedescriptions['attribute_'.substr($attdescription,10,strpos($attdescription,'=')-10)] = substr($attdescription,strpos($attdescription,'=')+1);
    }
    foreach ($extra_attrs as $fieldname)
    {
        if (isset($attributedescriptions[$fieldname]))
        {
            $extra_attrs_and_names[$fieldname]=$attributedescriptions[$fieldname];
        }
        else
        {
            $extra_attrs_and_names[$fieldname]=sprintf($clang->gT('Attribute %s'),substr($fieldname,10));
        }
    }
    if ($onlyAttributes===false)
    {
        $basic_attrs_and_names=array_combine($basic_attrs,$basic_attrs_names);
        return array_merge($basic_attrs_and_names,$extra_attrs_and_names);
    }
    else
    {
        return $extra_attrs_and_names;
    }
}

function GOdb_rename_table($oldtable, $newtable)
{
    global $connect, $limedb;

    $dict = NewDataDictionary($connect);
    $result=$dict->RenameTableSQL($oldtable, $newtable, $limedb);
    return $result[0].date("YmdHis");
}

function GOcreateTimingsFieldMap($surveyid, $style='full', $force_refresh=false, $questionid=false, $sQuestionLanguage=null) {

    global $dbprefix, $connect, $globalfieldmap, $clang, $aDuplicateQIDs;
    static $timingsFieldMap;

    $surveyid=sanitize_int($surveyid);
    //checks to see if fieldmap has already been built for this page.
    if (isset($timingsFieldMap[$surveyid][$style][$clang->langcode]) && $force_refresh==false) {
        return $timingsFieldMap[$surveyid][$style][$clang->langcode];
    }

    //do something
    $fields = GOcreateFieldMap($surveyid, $style, $force_refresh, $questionid, $sQuestionLanguage);
    $fieldmap['interviewtime']=array('fieldname'=>'interviewtime','type'=>'interview_time','sid'=>$surveyid, 'gid'=>'', 'qid'=>'', 'aid'=>'', 'question'=>$clang->gT('Total time'), 'title'=>'interviewtime');
    foreach ($fields as $field) {
        if (!empty($field['gid'])) {
            // field for time spent on page
            $fieldname="{$field['sid']}X{$field['gid']}time";
            if (!isset($fieldmap[$fieldname]))
            {
                $fieldmap[$fieldname]=array("fieldname"=>$fieldname, 'type'=>"page_time", 'sid'=>$surveyid, "gid"=>$field['gid'], "group_name"=>$field['group_name'], "qid"=>'', 'aid'=>'', 'title'=>'groupTime'.$field['gid'], 'question'=>$clang->gT('Group time').": ".$field['group_name']);
            }

            // field for time spent on answering a question
            $fieldname="{$field['sid']}X{$field['gid']}X{$field['qid']}time";
            if (!isset($fieldmap[$fieldname]))
            {
                $fieldmap[$fieldname]=array("fieldname"=>$fieldname, 'type'=>"answer_time", 'sid'=>$surveyid, "gid"=>$field['gid'], "group_name"=>$field['group_name'], "qid"=>$field['qid'], 'aid'=>'', "title"=>$field['title'].'Time', "question"=>$clang->gT('Question time').": ".$field['title']);
            }
        }
    }

    $timingsFieldMap[$surveyid][$style][$clang->langcode] = $fieldmap;
    return $timingsFieldMap[$surveyid][$style][$clang->langcode];
}

function GOactivateSurvey($postsid,$surveyid, $scriptname='limesurvey.php')
{
    global $dbprefix, $connect, $clang, $databasetype,$databasetabletype, $uploaddir, $limedb, $link;

     $createsurvey='';
     $activateoutput='';
     $createsurveytimings='';
     $createsurveydirectory=false;
    //Check for any additional fields for this survey and create necessary fields (token and datestamp)
    $pquery = "SELECT anonymized, allowregister, datestamp, ipaddr, refurl, savetimings FROM {$limedb}.{$dbprefix}surveys WHERE sid=$postsid";
    $presult=mysql_query($pquery, $link);
    $prow=mysql_fetch_assoc($presult);
    if ($prow['allowregister'] == "Y")
    {
        $surveyallowsregistration="TRUE";
    }
    if ($prow['savetimings'] == "Y")
    {
        $savetimings="TRUE";
    }
    //strip trailing comma and new line feed (if any)
    $createsurvey = rtrim($createsurvey, ",\n");
    //strip trailing comma and new line feed (if any)
    $createsurvey = rtrim($createsurvey, ",\n");

    //Get list of questions for the base language
    $fieldmap=GOcreateFieldMap($surveyid);
    foreach ($fieldmap as $arow) //With each question, create the appropriate field(s)
    {
        if ($createsurvey!='') {$createsurvey .= ",\n";}
        $createsurvey .= ' `'.$arow['fieldname'].'`';
        switch($arow['type'])
        {
            case 'startlanguage':
                $createsurvey .= " C(20) NOTNULL";
                break;
            case 'id':
                $createsurvey .= " I NOTNULL AUTO PRIMARY";
                $createsurveytimings .= " `{$arow['fieldname']}` I NOTNULL PRIMARY,\n";
                break;
            case "startdate":
            case "datestamp":
                $createsurvey .= " T NOTNULL";
                break;
            case "submitdate":
                $createsurvey .= " T";
                break;
            case "lastpage":
                $createsurvey .= " I";
                break;
            case "N":  //NUMERICAL
                $createsurvey .= " F";
                break;
            case "S":  //SHORT TEXT
                if ($databasetype=='mysql' || $databasetype=='mysqli')    {$createsurvey .= " X";}
                else  {$createsurvey .= " C(255)";}
                break;
            case "L":  //LIST (RADIO)
            case "!":  //LIST (DROPDOWN)
            case "M":  //Multiple choice
            case "P":  //Multiple choice with comment
            case "O":  //DROPDOWN LIST WITH COMMENT
                if ($arow['aid'] != 'other' && strpos($arow['aid'],'comment')===false && strpos($arow['aid'],'othercomment')===false)
                {
                    $createsurvey .= " C(5)";
                }
                else
                {
                    $createsurvey .= " X";
                }
                break;
            case "K":  // Multiple Numerical
                $createsurvey .= " F";
                break;
            case "U":  //Huge text
            case "Q":  //Multiple short text
            case "T":  //LONG TEXT
            case ";":  //Multi Flexi
            case ":":  //Multi Flexi
                $createsurvey .= " X";
                break;
            case "D":  //DATE
                $createsurvey .= " D";
                break;
            case "5":  //5 Point Choice
            case "G":  //Gender
            case "Y":  //YesNo
            case "X":  //Boilerplate
                $createsurvey .= " C(1)";
                break;
            case "I":  //Language switch
                $createsurvey .= " C(20)";
                break;
            case "|":
                $createsurveydirectory = true;
                if (strpos($arow['fieldname'], "_"))
                    $createsurvey .= " I1";
                else
                    $createsurvey .= " X";
                break;
            case "ipaddress":
                if ($prow['ipaddr'] == "Y")
                    $createsurvey .= " X";
                break;
            case "url":
                if ($prow['refurl'] == "Y")
                    $createsurvey .= " X";
                break;
            case "token":
                if ($prow['anonymized'] == "N")
                {
                    $createsurvey .= " C(36)";
                }
                break;
            default:
                $createsurvey .= " C(5)";
        }
    }
    $timingsfieldmap = GOcreateTimingsFieldMap($surveyid);
    $createsurveytimings .= '`'.implode("` F DEFAULT '0',\n`",array_keys($timingsfieldmap)) . "` F DEFAULT '0'";

    // If last question is of type MCABCEFHP^QKJR let's get rid of the ending coma in createsurvey
    $createsurvey = rtrim($createsurvey, ",\n")."\n"; // Does nothing if not ending with a comma

    $tabname = "{$dbprefix}survey_{$postsid}"; # not using db_table_name as it quotes the table name (as does CreateTableSQL)

    $taboptarray = array('mysql' => 'ENGINE='.$databasetabletype.'  CHARACTER SET utf8 COLLATE utf8_unicode_ci',
                         'mysqli'=> 'ENGINE='.$databasetabletype.'  CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    $dict = NewDataDictionary($connect);
    $sqlarray = $dict->CreateTableSQL($tabname, $createsurvey, $taboptarray, $limedb);

    if (isset($savetimings) && $savetimings=="TRUE")
    {
        $tabnametimings = $tabname .'_timings';
        $sqlarraytimings = $dict->CreateTableSQL($tabnametimings, $createsurveytimings, $taboptarray, $limedb);
    }

	$execresult=mysql_query($sqlarray[0], $link) or safe_die(mysql_error());
//    $execresult=$dict->ExecuteSQLArray($sqlarray,1);
	
    if (!$execresult)
    {
        $activateoutput .= "<br />\n<div class='messagebox ui-corner-all'>\n" .
        "<div class='header ui-widget-header'>".$clang->gT("Activate Survey")." ($surveyid)</div>\n" .
        "<div class='warningheader'>".$clang->gT("Survey could not be actived.")."</div>\n" .
        "<p>" .
        $clang->gT("Database error:")."\n <font color='red'>" . mysql_error() . "</font>\n" .
        "<pre>$createsurvey</pre>\n
        <a href='$scriptname?sid={$postsid}'>".$clang->gT("Main Admin Screen")."</a>\n</div>" ;
    }
	else
	{
        $anquery = "SELECT autonumber_start FROM {$dbprefix}surveys WHERE sid={$postsid}";
        if ($anresult=mysql_query($anquery, $link))
        {
            //if there is an autonumber_start field, start auto numbering here
            while($row=mysql_fetch_assoc($anresult))
            {
                if ($row['autonumber_start'] > 0)
                {
                    if ($databasetype=='odbc_mssql' || $databasetype=='odbtp' || $databasetype=='mssql_n' || $databasetype=='mssqlnative') {
                        mssql_drop_primary_index('survey_'.$postsid);
                        mssql_drop_constraint('id','survey_'.$postsid);
                        $autonumberquery = "alter table {$dbprefix}survey_{$postsid} drop column id ";
                        $connect->Execute($autonumberquery);
                        $autonumberquery = "alter table {$dbprefix}survey_{$postsid} add [id] int identity({$row['autonumber_start']},1)";
                        $connect->Execute($autonumberquery);
                    }
                    else
                    {
                        $autonumberquery = "ALTER TABLE {$dbprefix}survey_{$postsid} AUTO_INCREMENT = ".$row['autonumber_start'];
                        $result = @$connect->Execute($autonumberquery);

                    }
                }
            }
            if (isset($savetimings) && $savetimings=="TRUE")
            {
                $dict->ExecuteSQLArray($sqlarraytimings,1);    // create a timings table for this survey
            }
        }

        $activateoutput .= "<br />\n<div class='messagebox ui-corner-all'>\n";
        $activateoutput .= "<div class='header ui-widget-header'>".$clang->gT("Activate Survey")." ($surveyid)</div>\n";
        $activateoutput .= "<div class='successheader'>".$clang->gT("Survey has been activated. Results table has been successfully created.")."</div><br /><br />\n";

        // create the survey directory where the uploaded files can be saved
        if ($createsurveydirectory)
            if (!file_exists($uploaddir."/surveys/" . $postsid . "/files"))
            {
               if (!(mkdir($uploaddir."/surveys/" . $postsid . "/files", 0777, true)))
               {
                $activateoutput .= "<div class='warningheader'>".
                    $clang->gT("The required directory for saving the uploaded files couldn't be created. Please check file premissions on the limesurvey/upload/surveys directory.") . "</div>";

               }
               else
               {
                   file_put_contents($uploaddir."/surveys/" . $postsid . "/files/index.html",'<html><head></head><body></body></html>');
               }
            }

        $acquery = "UPDATE {$dbprefix}surveys SET active='Y' WHERE sid=".$surveyid;
        $acresult = $connect->Execute($acquery);

        if (isset($surveyallowsregistration) && $surveyallowsregistration == "TRUE")
        {
            $activateoutput .= $clang->gT("This survey allows public registration. A token table must also be created.")."<br /><br />\n";
            $activateoutput .= "<input type='submit' value='".$clang->gT("Initialise tokens")."' onclick=\"".get2post("$scriptname?action=tokens&amp;sid={$postsid}&amp;createtable=Y")."\" />\n";
        }
        else
        {
            $activateoutput .= $clang->gT("This survey is now active, and responses can be recorded.")."<br /><br />\n";
            $activateoutput .= "<strong>".$clang->gT("Open-access mode").":</strong> ".$clang->gT("No invitation code is needed to complete the survey.")."<br />".$clang->gT("You can switch to the closed-access mode by initialising a token table with the button below.")."<br /><br />\n";
            $activateoutput .= "<input type='submit' value='".$clang->gT("Switch to closed-access mode")."' onclick=\"".get2post("$scriptname?action=tokens&amp;sid={$postsid}&amp;createtable=Y")."\" />\n";
            $activateoutput .= "<input type='submit' value='".$clang->gT("No, thanks.")."' onclick=\"".get2post("$scriptname?sid={$postsid}")."\" />\n";
        }
        $activateoutput .= "</div><br />&nbsp;\n";
        $lsrcOutput = true;
    }

    if($scriptname=='lsrc')
    {
        if($lsrcOutput==true)
            return true;
        else
            return $activateoutput;
    }
    else
    {
        return $activateoutput;
    }
}
?>
