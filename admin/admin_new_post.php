<?php

if(isset($_POST['submit'])){
	$title = $_POST['title'];
	$content = $_POST['content'];

	if(strlen($title) == 0){
		echo 'Title is needed！';
	}else{
		$goal_post = isset($_POST['edit_pid'])?$_POST['edit_pid']:'new';
		if($goal_post == 'new'){
			$res = add_post($title, $content);
		}else{
			$pid = intval($goal_post);
			$res = update_post($pid,$title,$content);
		}

		if($res){
			echo 'Success!!!';
		}else{
			echo 'Fail!';
		}
	}

}else{

	if(isset($_GET['edit_pid'])){
		$pid = intval($_GET['edit_pid']);
		$res = fetch_post($pid);
		if(!empty($res)){
			$pre_data = $res;
		}else{
			$pre_data = array('pid'=>'new','title'=>'New Post ...','content'=>'',);
		}

	}else{
		$pre_data = array('pid'=>'new','title'=>'','content'=>'',);
	}

?>



<div class="new_post">
	<form method="post" action="">
		<input type="text" name="title" id="newpost_title" value="<?php echo $pre_data['title']; ?>" placeholder='在这里输入文章标题'>
		<textarea name="content" id="newpost_content" rows="12" cols=""><?php echo $pre_data['content']; ?></textarea>
		<input type="hidden" name="edit_pid" id="newpost_pid" value="<?php echo $pre_data['pid']; ?>">
		<div class="form_row">
			<input type="submit" name="submit" id="newpost_submit">
		</div>
	</form>
</div>

<?php
}
?>










