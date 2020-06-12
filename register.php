<html>
<body>
 
<head>
<meta charset="UTF-8">
<title>用户注册</title>
</head>

<form method="post">
用户ID：<input type="text" name="uid"><br><br>
用户名：<input type="text" name="name"><br><br>
密码：<input type="password" name="password"><br><br>
确认密码：<input type="password" name="re_password"><br><br>
<input type="submit" value="注册"><br>
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
	if(empty($_POST["name"])) {
		echo "<font color='#FF0000'> *用户名不能为空 </font><br>";
		$noError = false;
	} elseif(strlen($_POST["name"]) > 20) {
		echo "<font color='#FF0000'> *用户名不能超过20字节 </font><br>";
		$noError = false;
	}
	if(empty($_POST["password"])) {
		echo "<font color='#FF0000'> *密码不能为空 </font><br>";
		$noError = false;
	}
	if(empty($_POST["re_password"])) {
		echo "<font color='#FF0000'> *确认密码不能为空 </font><br>";
		$noError = false;
	}
	$hashed_password = md5($_POST["password"]);
	$hashed_re_password = md5($_POST["re_password"]);
	$_POST["password"] = null;
	$_POST["re_password"] = null;
	if(strcmp($hashed_password, $hashed_re_password)) {
		echo "<font color='#FF0000'> *两次输入的密码必须相等 </font><br>";
		$noError = false;
	}
	if($noError) {
		$conn = new mysqli("localhost", "guest", "100000", "QnADB");
		if($conn->connect_error) {
			die("连接失败:" . mysqli_connect_error());
		}
		$conn->set_charset('utf8');
		$sql = "SELECT uid FROM users WHERE uid = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $_POST[uid]);
		if($stmt->execute()) {
			$stmt->store_result();
			if($stmt->num_rows() > 0) {
				echo "<font color='#FF0000'> *用户ID已存在 </font><br>";
				$stmt->free_result();
				$stmt->close();
				$conn->close();
				die;
			}
		} else {
			echo "<font color='#FF0000'> *检查用户ID时发生错误 </font><br>";
			$stmt->free_result();
			$stmt->close();
			$conn->close();
			die;
		}
		$stmt->close();
		$sql = "INSERT INTO users(uid, name, password, privilege) VALUES (?, ?, ?, 'guest')";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("iss", intval($_POST["uid"]), $_POST["name"], $hashed_password);
		if($stmt->execute()) {
			echo "成功创建用户，用户ID " . $_POST["uid"] . "<br>";
			echo "<a href='index.php'>" . "返回首页" . "</a><br>";
			$stmt->close();
			$conn->close();
		} else {
    		echo "<font color='#FF0000'> *注册用户时发生错误 </font><br>";
			$stmt->close();
			$conn->close();
    		die;
		}

	}
}
?>

</body>
</html>