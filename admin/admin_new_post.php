<?php

if(isset($_POST['edit_pid'])){
	$title = strip_tags($_POST['title']);
	$content = strip_tags($_POST['content']);
	$status = intval($_POST['status']);
	$summary = substr($content,0,100);

	if(strlen($title) == 0){
		echo 'Title is needed！';
	}else{
		$goal_post = isset($_POST['edit_pid'])?$_POST['edit_pid']:'new';
		if($goal_post == 'new'){
			$res = add_post(['title'=>$title, 'content'=>$content, 'status'=>$status, 'summary'=>$summary]);
		}else{
			$pid = intval($goal_post);
			$res = update_post($pid,['title'=>$title, 'content'=>$content, 'status'=>$status, 'summary'=>$summary]);
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
			$pre_data = array('pid'=>'new','title'=>'New Post ...','content'=>'','raw_text'=>'');
		}

	}else{
		$pre_data = array('pid'=>'new','title'=>'','content'=>'','raw_text'=>'',);
	}

?>

<script src="lib/jquery.form.min.js"></script>
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<link rel="stylesheet" href="lib/simplemde/simplemde.css">

<script src="https://cdn.rawgit.com/showdownjs/showdown/1.5.2/dist/showdown.min.js"></script>

<div class="new_post">
	<form id="newpost_form" method="post" action="" onsubmit="return checkPostForm()">
		<input type="text" name="title" id="newpost_title" value="<?php echo $pre_data['title']; ?>" placeholder='在这里输入文章标题'>
		<textarea name="content" id="newpost_content" rows="12" cols=""><?php echo $pre_data['raw_text']; ?></textarea>
		<input type="hidden" name="edit_pid" id="newpost_pid" value="<?php echo $pre_data['pid']; ?>">
		<input type="hidden" name="status" id="newpost_status" value="2">
		<div class="form_row">
			<input type="button" id="show-preview" class="button" onclick="showPreview()" value="预览">
			<input type="button" name="publish" id="newpost_submit" value="发布文章">
			<input type="button" name="save" class="button" id="newpost_save" value="存为草稿">
		</div>
	</form>
	
	<div class="extra_functions">
		<div id="img_upload" class="function_area">
			<span class="function_title">插入本地图片</span>
			<form id="img_upload_form" method="POST" enctype="multipart/form-data" action="../ajax/img_upload.php">
				<input type="file" name="file" id="file_path_input"/>
				<input type="button" value="上传并插入图片" class="button" onclick="uploadImg()">
			</form>
		</div>
	</div>

</div>

<div id="markdown-preview" style="display:none;">
	<div class="preview-wrap">
		<div class="preview-container" id="preview-container">
		</div>
	</div>
	<div class="preview-close submit button" onclick="closePreview()">关闭预览</div>
</div>

<style>
#markdown-preview {
	position: fixed;
	top:0; left:0; right:0; bottom:0;
	background: rgba(0,0,0,0.3);
}
	.preview-wrap {
		position: fixed;
		padding: 1em;
		background: #FFFFFF;
		border-radius: 1em;
		top:32px; left:1em; right:1em; bottom:1em;
		box-shadow: 0 0 3px 3px rgba(0,0,0,0.3);
		overflow: auto;
		box-sizing:border-box;
	}

	.preview-close {
		position:fixed;
		top:0; right:1em;
		line-height: 32px;
		padding:0 1em; margin:0;
	}


</style>


<?php
}
?>


<script>

function uploadImg(){
	var inpobj = document.getElementById('file_path_input');
	if(!inpobj.value){
		alert('Empty');
		return false;
	}

	var formObj = $('#img_upload_form');
	$('#img_upload_form').ajaxSubmit({
		'success':function(response){
			console.log(response);
			data = JSON.parse(response);
			console.log(response,data);
			if(data['error_msg'] !== undefined){
				alert('error_msg');
			}else{
				textBoxOp.insertText("![img]("+data['url']+")");
			}
		}
	});



	/*var inpObj = $('#file_path_input').AjaxFileUpload({
		action: "../ajax/img_upload.php",
		onSubmit: function(filename) {
			alert(filename);
		},
		onComplete: function(filename, data) {
			if(data['error_msg'] !== undefined){
				alert(data['error_msg']);
			}else{
				alert(data['url']);
			}
		}
	});*/
}

textBoxOp = {
	'texto': document.getElementById('newpost_content'),

	'getCursor':function(){
		return {'start':this.texto.selectionStart,'end':this.texto.selectionEnd};
	},
	
	'insertText': function(str){
		var textCon = this.texto.value;
		var focus = this.getCursor();
		this.texto.value = textCon.substring(0,focus.start) + str + textCon.substring(focus.end);
	}
}


function checkPostForm(){
	var title_el = document.getElementById('newpost_title');
	if(title_el.value.replace(/\s/g,'') == ''){
		alert('标题不可为空');
		return false;
	}
	return true;
}

function showPreview(md){
	var md = document.getElementById('newpost_content').value;
	var converter = new showdown.Converter(),
		html      = converter.makeHtml(md);


	var previewBlock = document.getElementById('markdown-preview');
	previewBlock.style.display = 'block';
	var previewContainer = document.getElementById('preview-container');
	previewContainer.innerHTML = html;
}

function closePreview(){
	var previewBlock = document.getElementById('markdown-preview');
	previewBlock.style.display = 'none';
}




document.getElementById('newpost_submit').addEventListener('click',function(){
	document.getElementById('newpost_status').value = '2';
	if(checkPostForm()){
		document.getElementById('newpost_form').submit();
	}
});

document.getElementById('newpost_save').addEventListener('click',function(){
	document.getElementById('newpost_status').value = '1';
	if(checkPostForm()){
		document.getElementById('newpost_form').submit();
	}
});
</script>







