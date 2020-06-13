<?php session_start(); ?>

<html>
<body>

<head>
<meta charset="UTF-8">
<title>个人主页</title>
</head>

<?php
if (!isset($_SESSION["user"]) or is_null($_SESSION["user"])) {
	echo "<h1>尚未登录！</h1>";
	exit();
}
echo "<h1>" . htmlspecialchars($_SESSION["user"]["name"], ENT_QUOTES) . "的个人主页</h1>";
$conn = new mysqli("localhost", "user", "VEk8qg", "QnADB");
if($conn->connect_error) {
	die("连接失败:" . mysqli_connect_error());
}
$conn->set_charset("utf8");
$sql = "SELECT qid, content FROM question WHERE uid = ? ORDER BY uploadtime DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION["user"]["uid"]);
$stmt->execute();
$stmt->bind_result($qid, $content);
echo "<h2>我的提问</h2>";
while($stmt->fetch()) {
	echo "<a href='show_question.php?qid=" . $qid . "'>" . htmlspecialchars($content, ENT_QUOTES) . "</a><br>";
}
$stmt->close();

$sql = "SELECT qid, content FROM question WHERE qid IN (SELECT qid FROM answer WHERE uid = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION["user"]["uid"]);
$stmt->execute();
$stmt->bind_result($qid, $content);
echo "<h2>我的回答</h2>";
while($stmt->fetch()) {
	echo "<a href='show_question.php?qid=" . $qid . "'>" . htmlspecialchars($content, ENT_QUOTES) . "</a><br>";
}
$stmt->close();

$conn->close();
?>

<h2>修改密码</h2>
<form action="change_password.php" method="post">
旧密码：<input type="password" name="old_pwd"><br>
新密码：<input type="password" name="new_pwd"><br>
确认新密码：<input type="password" name="re_pwd"><br>
<input type="submit" value="提交"><br>
</form>

<h2>修改头像</h2>
<form action="change_avatar.php" method="post">
原头像：<?php echo is_null($_SESSION["user"]["avatar"])?"null<br>":("<img src='" . $_SESSION["user"]["avatar"] . "' 
						height='200', width='200'"); ?><br>
新头像url：<input type="text" name="avatar" size="50"><br>
<input type="submit" value="提交"><br>
</form>

<?php

$conn = new mysqli("localhost", "user", "VEk8qg", "QnADB");
if($conn->connect_error) {
	die("连接失败:" . mysqli_connect_error());
}
$conn->set_charset("utf8");
$sql = "SELECT privilege FROM users WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION["user"]["uid"]);
$stmt->execute();
$stmt->bind_result($privilege);
if($stmt->fetch() && $privilege==="user") {
	?>
	<h2>提问</h2>
	<form action="add_question.php" method="post">
	<textarea rows="5" cols="80" name="question"></textarea><br>
	<input type="submit" value="提交"><br>
	</form>
	<?php
}

?>

<form method="post">
<input type="submit" value="退出登录" name="quit"><br>
</form>
<?php
if(isset($_POST["quit"])) {
	session_destroy();
	unset($_POST["quit"]);
	echo "已退出登录！";
	echo "<a href='index.php'>" . "返回首页" . "</a><br>";
}
?>

</body>
</html>