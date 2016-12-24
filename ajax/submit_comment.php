<?php
include 'ajax_common.php';

$title = strip_tags($_POST['title']);
$content = strip_tags($_POST['content']);
$pid = intval($_POST['pid']);
//some filters needed
$op = $_GET['op'];

if($op="newcomment"){
	$add_result = add_comment($pid, $title, $content);
	if($add_result){
		echo "{success:1}";
	}else {
		echo "{success:0}";
	}
}else{
	//undefined operation.
}



