<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination

if(!empty($_POST['session'])){
    session_id($_POST['session']);
    session_start();
} else {
    exit;
}


$targetFolder = '/uploads'; // Relative to the root
if (!empty($_FILES)) {

	#$tempFile = $_POST['account']."_".$_FILES['Filedata']['tmp_name'];

	$dir = dirname($_FILES['Filedata']['tmp_name']);
        #$tempFile = rename($_FILES['Filedata']['tmp_name'],$dir."/".$_POST['account']."_".$_FILES['Filedata']['name']);
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	#$targetFile = rtrim($targetPath,'/') . '/' . $_POST['account'] ."_". $_FILES['Filedata']['name'];

	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','csv'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
 
		#move_uploaded_file("{$dir}/{$_POST['account']}_{$_FILES['Filedata']['tmp_name']}","$targetFile");
                rename($_FILES['Filedata']['tmp_name'],$targetPath."/".$_POST['account']."_".$_FILES['Filedata']['name']);
		echo '1';
	} else {
		echo 'Invalid file type.';
	}
}
?>
