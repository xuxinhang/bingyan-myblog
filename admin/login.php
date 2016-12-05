<?php

include '../include.php';
session_start();

if(isset($_SESSION['password']) && $_SESSION['password'] == '123'){
	echo '目前登陆状态————已经登陆';
	
	if(!empty($_GET['exit'])){
		$_SESSION['password'] = null;
	?>
	<p>已经登出';</p>
	<a href="login.php">重新登录</a>
	<?php
	}
}else{

	if(isset($_POST['password'])){
		if($_POST['password'] == '123'){
			$_SESSION['password'] = $_POST['password'];
			echo 'Success!'
	?>
	<p>一次新登录</p>
	<a href="index.php">进入后台</a>
	<?php
		}else{
			echo 'Error!';
		}

	}else{

	?>
		<form method="post" action="">
			PASSWORD<input type="text" name="password"><br>
			<input type="submit" value="try to login">
		</form>
	<?php

	}
}
?>

