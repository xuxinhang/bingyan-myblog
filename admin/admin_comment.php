<h3>评论管理</h3>

<?php
$do = empty($_GET['do'])?null:$_GET['do'];

if($do == 'delete'){
	$cid = intval($_GET['cid']);
	$result = delete_comment($cid);
	if($result){
		echo "<strong>删除成功！</strong>";
	}else{
		echo "<strong>:-(</strong>";
	}
}elseif($do == 'overview'){
?>

<div class="main">
<ul>
<?php

$pid = isset($_GET['pid'])?intval($_GET['pid']):0;
$list = get_comments_list($pid);

foreach($list as $item){
?>
<li>
<span class="comment_title"><?php echo $item['title'];?></span>
<a href="index.php?op=comment&do=delete&cid=<?php echo intval($item['cid']);?>">{删除}</a>
</li>

<?php
}
?>

</ul>
</div>



<?php

}






