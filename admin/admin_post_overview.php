<h3>文章管理</h3>


<?php

$do = empty($_GET['do'])?null:$_GET['do'];

if($do == 'delete_post'){
	$pid = intval($_GET['pid']);
	$result = delete_post($pid);
	if($result){
		echo "<strong>删除成功！</strong>";
	}else{
		echo "<strong>:-(</strong>";
	}
}

?>


<div class="main">

<ul class="item_list">
<?php

$list = get_post_list();

foreach($list as $item){
?>
	<li>
		<span class="post_title"><?php echo $item['title'];?></span>
		<div class="operation_links">
			<a href="../index.php?do=viewpost&pid=<?php echo intval($item['pid']); ?>">查看</a>
			<a href="index.php?op=new_post&edit_pid=<?php echo intval($item['pid']);?>">编辑</a>
			<a href="index.php?op=admin_post&do=delete_post&pid=<?php echo intval($item['pid']);?>">删除</a>
			<a href="index.php?op=comment&do=overview&pid=<?php echo intval($item['pid']);?>">管理评论</a>
		</div>
	</li>

<?php
}
?>

</ul>
</div>








