<!doctype html>
<html>
 <head>
  <meta charset="UTF-8">
  <title>Admin Page</title>
	<link rel="stylesheet" href="admin.css">
	<script src="lib/jquery-3.1.1.min.js"></script>
 </head>
 <body>

<header>
	<a href="index.php?op=new_post">新文章</a>&nbsp;
	<a href="index.php?op=admin_post">文章管理</a>&nbsp;
	<a href="login.php?exit=1">登出</a>
</header>

<div class="main">

<?php

session_start();

include '../include.php';

if(!checkLogin()){
	die('Please login first !');
}










