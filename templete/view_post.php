<?php
include 'header.php';

$pid = isset($_GET['pid'])?intval($_GET['pid']):0;

/******** ADD Comment *********/
if(isset($_POST['comment_submit'])){
	$res = add_comment($pid, $_POST['title'], $_POST['content']);
	if($res){
		echo '发表评论成功！';
	}
}




$pd = fetch_post($pid);

if(empty($pd)){
	echo '找不到文章';
}else{
	var_dump($pd);
?>

<div class="post_main_page">
	<div class="post_title"><h3><?php echo $pd['title']; ?></h3></div>
	<section class="post_main">
		<div class="post_note">
			<ul>
				<li>
				<p class="item-name">时间</p>
				<p class="item-cont"><?php echo transTime($pd['timestamp']); ?></p>
				</li>
			</ul>
		</div>
		<div class="post_content">
			<?php echo $pd['content']; ?>
		</div>
	</section>

<?php

}


$cd = get_comments_list($pid);

?>

<section id="comment_list">
<h2><?php echo count($cd); ?> 条评论</h2>

<ul class="comment_ul">
<?php

foreach($cd as $item){
?>
<li>
	<p class="comment_title"><?php echo $item['title']; ?></p>
	<p class="comment_content"><?php echo $item['content']; ?></p>
	<div class="timestamp"><?php echo transTime($item['timestamp']); ?></div>
</li>
<?php
}


?>


</ul>


</section>


<section id="new_comment">
	<div class="new_comment_sidebar">
		新评论
		留下你的 — — 评论
	</div>
	<div class="comment_form">
		<form method="post" action="" onsubmit="return checkForm()">
			<p class="comment_form_title"><input type="text" name="title" id="newcomment_title" placeholder="评论标题"></p>
			<p class="comment_form_content"><textarea name="content" id="newcomment_content" rows="5" placeholder="在这里输入正文..."></textarea></p>
			<p class="comment_form_submit"><input type="submit" id="newcomment_submit" name="comment_submit" value="发表评论"></p>
		</form>
	</div>
</section>

</div>

<script>
function checkForm(){
	var title_field = document.getElementById('newcomment_title');
	var content_field = document.getElementById('newcomment_content');
	var trim = function(t){
		return t.replace(/\s/g,'');
	}

	if(title_field.value.length == 0){
		alert('标题不可为空');
		return false;
	}

	return true;
}
</script>

<?php
include 'footer.php';

