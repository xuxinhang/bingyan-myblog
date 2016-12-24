<?php
include 'header.php';

function getPageLink($page_index){
	$new_url = "index.php?page={$page_index}";
	return $new_url;
}


$page = isset($_GET['page'])?intval($_GET['page']):1;
$page = $page<1?1:$page;

$posts_pre_page = 2;
$start_index = ($page-1)*$posts_pre_page;
$end_index = $page*$posts_pre_page;

//echo $start_index,$posts_pre_page;

$post_list = get_post_list($start_index, $posts_pre_page);

//var_dump($post_list);

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
			<div class="post_time"><?php echo transTime($post_info['timestamp']);?></div>
			<div class="dot_btn"><?php echo $post_info['comment_counter']; ?></div>
		</div>
		<div class="post_block">
			<p class="post_part"><?php echo $post_info['content'];?></p>
		</div>
	</li>

<?php
}
?>

	</section>

	<div class="page_jump">
		<span class="previous_page"><a href="<?php echo getPageLink($page-1); ?>">上一页</a></span>
		<span class="next_page"><a href="<?php echo getPageLink($page+1); ?>">下一页</a></span>
	</div>

</div>

<div style="clear:both;"></div>

<?php
include 'footer.php';

