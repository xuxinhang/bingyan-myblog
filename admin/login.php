<!doctype html>
<html>
 <head>
  <meta charset="UTF-8">
  <title>Admin Page</title>
	<link rel="stylesheet" href="admin.css">
 </head>
 <body>


<?php

include '../include.php';
session_start();

if(isset($_SESSION['password']) && $_SESSION['password'] == '123'){

	if(!empty($_GET['exit'])){
		$_SESSION['password'] = null;
	?>
	<div class="login_main">
		<h3>已登出</h3>
		<a href="login.php" class="submit">重新登录</a>
	</div>
	<?php
	}else{
	?>
	<div class="login_main">
		<h3>已登录</h3>
		<a href="index.php" class="submit">进入后台</a>
	</div>
	<?php
	}
}else{

	if(isset($_POST['password'])){
		if($_POST['password'] == '123'){
			$_SESSION['password'] = $_POST['password'];
			//echo 'Success!'
	?>
	<div class="login_main">
		<h3>一次新的登录</h3>
		<a href="index.php" class="submit">正在进入后台</a>
		<script>window.location.href="index.php"; </script> 
	</div>
	<?php
		}else{
			echo 'Error!';
		}

	}else{

	?>
	<div class="login_main">
		<h3>登录后台</h3>
		<form method="post" action="">
			<input type="text"   id="login_pw" name="password">
			<input type="submit" id="login_submit" class="submit" value="登录">
		</form>
	</div>
	<?php

	}
}
?>

</body>
</html>