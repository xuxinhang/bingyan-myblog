<?php

$BLOG_URL = 'http://127.0.0.1/myblog'; 

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

function add_post($para, $cid=0){
	$title = $para['title'];
	$content = $para['content'];
	$timestamp = empty($para['timestamp'])?null:$para['timestamp'];
	$status = intval($para['status']);

	if(empty($para['html'])) {
		$parsed_html = markdown2html($content);
	}else {
		$parsed_html = $para['html'];
	}

	//获得下一个自增值
	$next_pid = db_query("SELECT IDENT_CURRENT('posts') + IDENT_INCR('posts');");
	//
	//$content = addslashes($content);
	//$title = addslashes($title);
	$timestamp = time();
	$sql = "INSERT INTO `posts` (`pid`, `title`, `content`, `timestamp`, `cid`, `good_counter`, `view_counter`, `summary`, `status`) VALUES (NULL, '$title', '$content', '$timestamp', '$cid', '0', '0', 'ABC', '$status'); ";
	$sql2="INSERT INTO `posts_content` (`pid`,`html`,`raw_text`) VALUES ('$next_pid', '{$parsed_html}', '{$content}')";
	//echo $sql;
	$new_line = db_query($sql);
	$new_line = db_query($sql2);
	if($new_line){
		return true;
	}else{
		return false;
	}
}

function update_post($pid, $data){
	$para = [];
	//foreach($data as $k=>$v) array_push($para, "`$k`='$v'");
	
	//For Main Table POSTS
	$para_list = array();
	if(isset($data['title'])) $para_list['title']=$data['title'];
	if(isset($data['comment_counter'])) $para_list['comment_counter']=$data['comment_counter'];
	if(isset($data['good_counter'])) $para_list['good_counter']=$data['good_counter'];
	if(isset($data['view_counter'])) $para_list['view_counter']=$data['view_counter'];
	if(isset($data['timestamp'])) $para_list['timestamp']=$data['timestamp'];
	if(isset($data['status'])) $para_list['status']=$data['status'];
	if(isset($data['cid'])) $para_list['cid']=$data['cid'];

	$para_str = [];
	foreach($para_list as $k=>$v) array_push($para_str, "`$k`='$v'");
	$sql = "UPDATE `posts` SET ".implode(',', $para_str)." WHERE `pid` = $pid ;";

	//For Content Table posts_content

	$para_list2 = array();
	if(isset($data['html'])){
		$para_list2['html'] = $data['html'];
	}else{
		if(isset($data['content'])){
			$para_list2['raw_text'] = $data['content'];
			$para_list2['html'] = markdown2html($data['content']);
			//echo $para_list2['html'];
		}
	}


	// gnerate SQL 
	$para_str = [];
	foreach($para_list2 as $k=>$v) array_push($para_str, "`$k`='".mysql_escape_string($v)."'");
	//print_r($para_list2);
	$sql2= "UPDATE `posts_content` SET ".implode(',', $para_str)." WHERE `pid` = $pid ;";

	//echo $sql2;
	$result1 = db_query($sql);
	$result2 = db_query($sql2);
	return $result2;
}

function delete_post($pid){
	$sql = "DELETE FROM `posts` WHERE `pid` = $pid";
	$result = db_query($sql);
	return $result;
}

function fetch_post($pid){
	$result = db_query("SELECT `posts`.*, `posts_content`.*  FROM `posts` INNER JOIN `posts_content` ON `posts`.`pid` = `posts_content`.`pid`  WHERE `posts`.`pid` = '$pid' ;");
	$row = $result->fetch_array();
	//print_r($row);
	$data = array(
		'pid'=>$row['pid'],
		'title'=>$row['title'],
		'content'=>$row['html'],
		'timestamp'=>$row['timestamp'],
		'view_counter'=>$row['view_counter'],
		'good_counter'=>$row['good_counter'],
		'raw_text'=>$row['raw_text'],
		);

	return  $data;
}


function get_post_list($start_index = null, $end_index = null, $status = 2){

	//echo $start_index, $end_index;

	$sql = "SELECT * FROM `posts` ";
	if($status == 0){
		$sql .= " WHERE `status`=2 OR `status`=1 OR `status`=0";
	}elseif($status == 1){
		$sql .= " WHERE `status`=1 OR `status`=0";
	}elseif($status == 2){
		$sql .= " WHERE `status`=2 OR `status`=0";
	}
	$sql .=  " ORDER BY `timestamp` DESC";

	if($start_index!==null && $end_index!==null){
		$sql .= " LIMIT $start_index , $end_index";
	}
	$result = db_query($sql);

	//echo $sql;

	$post_list =array();
	while($row =  $result->fetch_array()){
		$post_list[] = array(
			'pid'=>$row['pid'],
			'title'=>$row['title'],
			'content'=>$row['summary'],
			'timestamp'=>$row['timestamp'],
			'good'=>$row['good_counter'],
			'comment_counter'=>$row['comment_counter'],
			);
	}

	return $post_list;
}

function add_good_counter($pid, $setnum=-1){//点赞
	if($setnum==-1){
		$post_info = fetch_post($pid);
		$newset = $post_info['good_counter']+1;
	}else{
		$newset = $setnum;
	}
	$re = update_post($pid, ['good_counter'=>$newset]);
}

function add_view_counter($pid, $setnum=-1){//view-counter plus one
	if($setnum==-1){
		$post_info = fetch_post($pid);
		$newset = $post_info['view_counter']+1;
	}else{
		$newset = $setnum;
	}
	$re = update_post($pid, ['view_counter'=>$newset]);
}
/*INSERT INTO `posts` (`pid`, `title`, `content`, `timestamp`, `cid`, `good_counter`, `view_counter`) VALUES (NULL, 'xscdvdfgbfhgjmfgdgg发的还是打电话brb', '都纷纷表示打开v的发挥v不喝咖啡v就', '23', '0', '0', '0');*/


/************  Comment **************/

function add_comment($pid,$title,$content,$nickname ='',$timestamp=''){
	$timestamp = time();
	$sql = "INSERT INTO `comments` (`cid`, `pid`, `title`, `content`, `timestamp`) VALUES (NULL, '$pid', '$title', '$content', '$timestamp' )";
	//echo $sql;
	$new_line = db_query($sql);
	// add comment_counter
	$post_info = fetch_post($pid);
	update_post($pid, ['comment_counter'=>$post_info['comment_counter']+1] );

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


function transTime($ustime){
	$ytime = date("Y-m-d H:i",$ustime);              
	$rtime = date("n月j日 H:i",$ustime);            
	$htime = date("H:i",$ustime);            
	$time = time() - $ustime;            
	$todaytime = strtotime("today");            
	$time1 = time() - $todaytime;                            
	if($time < 60){                    
		$str = '刚刚';            
	}else if($time < 60 * 60){                             
		$min = floor($time/60);                    
		$str = $min.'分钟前';            
	}else if($time < $time1){                    
		$str = '今天 '.$htime;            
	}else{                    
		$str = $rtime;            
	}              
	return $str;
}


/*** MARKDOWN PRASER ***/

use \Michelf\Markdown;
function markdown2html($md) {
	//use \markdownlib\Markdown;
	$ree = require_once 'markdownlib/Markdown.inc.php';
	$ree = require_once 'markdownlib/Markdown.php';
	$ree = require_once 'markdownlib/MarkdownInterface.inc.php';
	//$ree = require_once 'markdownlib/Markdown.inc.php';
	//$ree = require_once 'markdownlib/Markdown.inc.php';
	//$ree = require_once 'markdownlib/Markdown.inc.php';
	//echo $ree;
	$html = Markdown::defaultTransform($md);
	return $html;
}

