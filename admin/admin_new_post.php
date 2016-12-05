<?php


if(isset($_POST['submit'])){
	$title = $_POST['title'];
	$content = $_POST['content'];

	if(strlen($title) == 0){
		echo 'Title is needed£¡';
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
		$pre_data = array('pid'=>'new','title'=>'New Post ...','content'=>'',);
	}

?>



<div class="form-area">
<form method="post" action="">
	<input type="text" name="title" value="<?php echo $pre_data['title']; ?>">
	<textarea name="content" rows="" cols=""><?php echo $pre_data['content']; ?></textarea>
	<input type="hidden" name="edit_pid" value="<?php echo $pre_data['pid']; ?>">
	<input type="submit" name="submit">
</form>

</div>

<?php
}
?>










