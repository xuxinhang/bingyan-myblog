<?php

include 'include.php';

$do = empty($_GET['do'])? 'postlist':$_GET['do'];


if($do == 'postlist'){
	include 'templete/post_list.php';
}elseif($do == 'viewpost'){
	include 'templete/view_post.php';
}







