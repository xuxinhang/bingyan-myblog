<?php

$_db = 0;
connect_db();

function connect_db(){
	global $_db;
	$_db =  new mysqli('localhost', 'root', '', 'myblog');
	if ($_db->connect_error) {
		die('Connect Error (' . $_db->connect_errno . ') '. $_db->connect_error);
	}
	$_db->query ( "set character_set_client ='utf8'");
	$_db->query ( "set character_set_results ='utf8'" );
	$_db->query ( "set character_set_connection ='utf8'");

	$_db->query ("SET NAMES UTF8");
	$_db->query ("SET CHARACTER SET 'UTF8'");
	$_db->query ("SET CHARACTER_SET_RESULTS='UTF8'");
}

function db_query($sql){
	global $_db;
	$result = $_db->query($sql);
	return $result;
}

function add_post($title, $content, $timestamp=null, $cid=0){
	$timestamp = time();
	$sql = "INSERT INTO `posts` (`pid`, `title`, `content`, `timestamp`, `cid`, `good_counter`, `view_counter`) VALUES (NULL, '$title', '$content', '$timestamp', '$cid', '0', '0')";
	echo $sql;
	$new_line = db_query($sql);
	if($new_line){
		return true;
	}else{
		return false;
	}
}

function update_post($pid, $title, $content){
	$sql = "UPDATE `posts` SET `title` = '$title', `content` = '$content' WHERE `pid` = $pid";
	$result = db_query($sql);
	return $result;
}

function delete_post($pid){
	$sql = "DELETE FROM `posts` WHERE `pid` = $pid";
	$result = db_query($sql);
	return $result;
}

function fetch_post($pid){
	$result = db_query("SELECT * FROM `posts` WHERE `pid` = $pid");
	$row = $result->fetch_array();
	//print_r($row);
	$data = array(
		'pid'=>$row['pid'],
		'title'=>$row['title'],
		'content'=>$row['content'],
		);

	return  $data;
}


function get_post_list(){
	$sql = "SELECT * FROM `posts` ORDER BY `timestamp` DESC ";
	$result = db_query($sql);

	$post_list =array();
	while($row =  $result->fetch_array()){
		$post_list[] = array(
			'pid'=>$row['pid'],
			'title'=>$row['title'],
			'content'=>$row['content'],
			'timestamp'=>$row['timestamp'],
			'good'=>$row['good_counter'],
			);
	}

	return $post_list;
}


/*INSERT INTO `posts` (`pid`, `title`, `content`, `timestamp`, `cid`, `good_counter`, `view_counter`) VALUES (NULL, 'xscdvdfgbfhgjmfgdgg发的还是打电话brb', '都纷纷表示打开v的发挥v不喝咖啡v就', '23', '0', '0', '0');*/


/************  Comment **************/

function add_comment($pid,$title,$content,$nickname ='',$timestamp=''){
	$timestamp = time();
	$sql = "INSERT INTO `comments` (`cid`, `pid`, `title`, `content`, `timestamp`) VALUES (NULL, '$pid', '$title', '$content', '$timestamp' )";
	echo $sql;
	$new_line = db_query($sql);
	if($new_line){
		return true;
	}else{
		return false;
	}
}

function delete_comment($cid){
	$sql = "DELETE FROM `comments` WHERE `cid` = $cid";
	$result = db_query($sql);
	return $result;
}

function fetch_comment($cid){
	$result = db_query("SELECT * FROM `comments` WHERE `cid` = $cid");
	$row = $result->fetch_array();
	//print_r($row);
	$data = array(
		'cid'=>$row['cid'],
		'title'=>$row['title'],
		'content'=>$row['content'],
		);

	return  $data;
}

function get_comments_list($filter='new'){
	$sql = "SELECT * FROM `comments` WHERE `pid` = '$filter' ORDER BY `timestamp` ASC";
	$result = db_query($sql);

	$comments_list =array();
	while($row =  $result->fetch_array()){
		$comments_list[] = array(
			'title'=>$row['title'],
			'content'=>$row['content'],
			'timestamp'=>$row['timestamp'],
			'cid'=>$row['cid'],
			'pid'=>$row['pid'],
			);
	}

	return $comments_list;
}

/********** login ***********/

function checkLogin(){
	if(isset($_SESSION['password'])){
		if($_SESSION['password'] == '123'){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}


function linkToLogin(){
	echo '请先登录';
}


