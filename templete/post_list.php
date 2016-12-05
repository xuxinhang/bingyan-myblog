<?php
include 'header.php';

$post_list = get_post_list();

var_dump($post_list);

?>



<nav class="sidebar">
	<div class="side-block">
		<h2>开始</h2>
		<ul class="func-list">
			<li><a href="index.php">首页</a></li>
			<li><a href="">文章归档</a></li>
			<li><a href="">最新评论</a></li>
			<li><a href="admin/">管理平台</a></li>
		</ul>
	</div>
</nav>


<h1></h1>
<div id="page-body">

<section id="main">
<ul>

<?php 
foreach ($post_list as $post_info){
?>

<li class="post_list_item">
	<div class="post_head">
		<h3 class="post_title">
			<a href="index.php?do=viewpost&pid=<?php echo $post_info['pid']; ?>">
			<?php echo $post_info['title'];?>
			</a>
		</h3>
		<div class="post_time"><?php echo $post_info['timestamp'];?></div>
	</div>
	<div class="post_block">
		<p class="post_part"><?php echo $post_info['content'];?></p>
	</div>
</li>

<?php
}
?>

</section>

</div>
