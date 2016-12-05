<?php
include 'admin_header.php';

$op = empty($_GET['op'])?'admin_post':$_GET['op'];

if($op == 'new_post'){
	include 'admin_new_post.php';
}elseif($op == 'admin_post'){
	include 'admin_post_overview.php';
}elseif($op == 'comment'){
	include 'admin_comment.php';
}






