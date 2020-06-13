<?php
session_start();
if(!isset($_SESSION["user"])) {
	$_SESSION["user"]=null;
}
?>

<html>
<body>
 
<head>
<meta charset="UTF-8">
<title>QnA问答平台</title>
</head>
<?php
if(is_null($_SESSION["user"])) {
?>
<form action="register.php" method="post">
<input type="submit" value="注册">
</form>
<form action="login.php" method="post">
<input type="submit" value="登录">
</form>
<?php
} else {
?>
<h4>Welcome, <?php echo htmlspecialchars($_SESSION["user"]["name"], ENT_QUOTES); ?></h4>
<form action="home.php" method="post">
<input type="submit" value="个人主页">
</form>
<?php } ?>


<?php
$conn = mysqli_connect("localhost", "guest", "100000");

if(!$conn) {
	die("连接失败:" . mysqli_connect_error());
}
mysqli_query($conn, "set names 'utf8'");
$sql = "SELECT qid, content FROM QnADB.question";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // 输出数据
    while($row = mysqli_fetch_assoc($result)) {
    	echo "<a href='show_question.php?qid=" . $row["qid"] . "'>" . htmlspecialchars($row["content"], ENT_QUOTES) . "</a><br>";
    }
} else {
    echo "0 结果";
}
 
mysqli_close($conn);
?>

</body>
</html>