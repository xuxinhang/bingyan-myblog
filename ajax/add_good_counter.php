<?php
include 'ajax_common.php';
$pid = intval($_GET['pid']);
//some filters needed
//$op = $_GET['op'];

//if($op="newcomment"){
	$add_result = add_good_counter($pid);
	if($add_result){
		echo "{success:1}";
	}else {
		echo "{success:0}";
	}
//}else{
	//undefined operation.
//}
