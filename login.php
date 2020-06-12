<?php session_start() ?>

<html>
<body>

<head>
<meta charset="UTF-8">
<title>用户登录</title>
</head>

<form method="post">
用户ID：<input type="text" name="uid"><br><br>
密码：<input type="password" name="password"><br><br>
<input type="submit" value="登录"><br>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$noError = true;
	if(empty($_POST["uid"])) {
		echo "<font color='#FF0000'> *用户ID不能为空 </font><br>";
		$noError = false;
	} elseif(!filter_var($_POST["uid"], FILTER_VALIDATE_INT, array("options"=>array("min_range"=>1, "max_range"=>9999999999999)))) {
		echo "<font color='#FF0000'> *用户ID必须为不高于13位的正整数 </font><br>";
		$noError = false;
	}
	if(empty($_POST["password"])) {
		echo "<font color='#FF0000'> *密码不能为空 </font><br>";
		$noError = false;
	}
	$hashed_password = md5($_POST["password"]);
	$_POST["password"] = null;
	if($noError) {
		$conn = new mysqli("localhost", "guest", "100000", "QnADB");
		if($conn->connect_error) {
			die("连接失败:" . mysqli_connect_error());
		}
		$conn->set_charset('utf8');
		$sql = "SELECT uid, name, password, avatar FROM users WHERE uid = ? AND password = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $_POST[uid], $hashed_password);
		$stmt->execute();
		$stmt->bind_result($uid, $name, $password, $avatar);
		if($stmt->fetch()) {
			echo "登录成功，用户ID " . $uid . "<br>";
			$_SESSION["user"] = array("uid"=>$uid, "name"=>$name, "avatar"=>$avatar);
			echo "<a href='index.php'>" . "返回首页" . "</a><br>";
		} else {
			echo "<font color='#FF0000'> *登录失败，用户名或密码错误 </font><br>";
		}
		$stmt->close();
		$conn->close();
	}
}

?>

</body>
</html>