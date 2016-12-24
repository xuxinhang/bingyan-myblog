<?php
include 'header.php';

$pid = isset($_GET['pid'])?intval($_GET['pid']):0;

/******** ADD Comment (abandent)*********/
if(isset($_POST['comment_submit'])){
	$res = add_comment($pid, $_POST['title'], $_POST['content']);
	if($res){
		echo '<script>alert("发表评论成功！");</script>';
	}
}



$pd = fetch_post($pid);
add_view_counter($pid, $pd['view_counter']+1);

if(empty($pd)){
	echo '找不到文章';
}else{
	//var_dump($pd);
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

<section id="action_bar">
	<ul>
		<li id="add_comment"><a href="#new_comment"><i class="fa fa-comments-o"></i><?php echo count($cd); ?></a></li>
		<li id="add_good_counter" class="good-btn" onclick="addNewGood()"><i class="fa fa-thumbs-o-up"></i><span><?php echo intval($pd['good_counter']); ?></span></li>
		<li id=""><i class="fa fa-book"></i><?php echo intval($pd['view_counter']); ?></li>
	</ul>
</section>

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
	<li id="added_comment" style="display:none;">
		<p class="comment_title"></p>
		<p class="comment_content"></p>
		<div class="timestamp"></div>
	</li>
</ul>

</section>

<section id="new_comment">
	<div class="new_comment_sidebar">
		新评论
		留下你的 — — 评论
	</div>
	<div class="comment_form">
		<form method="post" action="" onsubmit="return false;">
			<p class="comment_form_title"><input type="text" name="title" id="newcomment_title" placeholder="评论标题"></p>
			<p class="comment_form_content"><textarea name="content" id="newcomment_content" rows="5" placeholder="在这里输入正文..."></textarea></p>
			<p class="comment_form_submit"><input type="submit" id="newcomment_submit" name="comment_submit" value="发表评论" onclick="if(checkForm()){submitComment();}"></p>
		</form>
	</div>
</section>

</div>

<script>

var PID = <?php echo $pid;?>;

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

function submitComment(){
	var title_field = document.getElementById('newcomment_title');
	var content_field = document.getElementById('newcomment_content');
	$.post('./ajax/submit_comment.php',{'pid':PID, 'title':title_field.value, 'content':content_field.value},function(){
		alert('感谢你的评论！');
		addCommentItem(title_field.value,content_field.value);
	});
}

function addCommentItem(title,content){
	var obj =document.querySelector('#added_comment');
	obj.style.display = null;
	obj.querySelector('.comment_title').innerHTML = title;
	obj.querySelector('.comment_content').innerHTML = content;
	obj.querySelector('.timestamp').innerHTML = '刚刚';
}

function addNewGood(){
	$.get('./ajax/add_good_counter.php?pid='+PID,function(){
	});
	var obj = document.getElementById('add_good_counter');
	obj.className += ' clicked';
	obj.onclick = function(){return false;}
	var counter_display = obj.getElementsByTagName('span')[0];
	counter_display.innerHTML = 1 + parseInt(counter_display.innerHTML);
}

</script>

<?php
include 'footer.php';

