<?php
include_once 'ajax_common.php';
header("Content-Type = 'application/json;charset=UTF-8'");

//print_r($_FILES);

$allowed_type = ["image/gif","image/jpeg","image/png"];
if(!in_array($_FILES["file"]["type"], $allowed_type)){
	//echo $_FILES["file"]["type"];
	exit("{'error_code': 1, 'error_msg':'invalid_file_type'}");
}

switch($_FILES["file"]["type"]){
	case 'image/gif':
		$tail = 'gif';
	break;
	case 'image/jpeg':
		$tail = 'jpg';
	break;
	case 'image/png':
		$tail = 'png';
	break;
}

if($_FILES["file"]["size"] > 200000){
	exit("{'error_msg':'too_large'}");
}

if($_FILES["file"]["error"] > 0){
	exit("{'error_msg':'Return Code : $_FILES[file][error]'}");
}


/*    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
*/
if (file_exists("upload/" . $_FILES["file"]["name"])){
	exit("{'error_msg':'already exists.'}");
}else{
	//get Upload Path
	$store_path = dirname(dirname(__FILE__)).'/upload';
	//echo $store_path;
	$new_filename = 'uploadimg_'.time().'_'.md5($_FILES["file"]["name"]).'.'.$tail;
	move_uploaded_file($_FILES["file"]["tmp_name"],	$store_path.'/'.$new_filename);
	//echo "Stored in: " . "upload/" . $_FILES["file"]["name"];

	exit("{\"url\":\"".$BLOG_URL."/upload/{$new_filename}\"}");
}

