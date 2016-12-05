<?php
include 'header.php';

$post_list = get_post_list();

var_dump($post_list);

?>



<nav class="sidebar">
	<div class="side-block">
		<h2>��ʼ</h2>
		<ul class="func-list">
			<li><a href="index.php">��ҳ</a></li>
			<li><a href="">���¹鵵</a></li>
			<li><a href="">��������</a></li>
			<li><a href="admin/">����ƽ̨</a></li>
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
