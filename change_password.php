<?php

session_start();
if(empty($_POST["new_pwd"])) {
	echo "新密码不能为空！";
	die;
}
$hashed_old = md5($_POST["old_pwd"]);
$hashed_new = md5($_POST["new_pwd"]);
$hashed_re = md5($_POST["re_pwd"]);
$_POST["old_pwd"] = null;
$_POST["new_pwd"] = null;
$_POST["re_pwd"] = null;

$conn = new mysqli("localhost", "user", "VEk8qg", "QnADB");
if($conn->connect_error) {
	die("连接失败:" . mysqli_connect_error());
}
$conn->set_charset("utf8");
$sql = "SELECT password FROM users WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION["user"]["uid"]);
$stmt->execute();
$stmt->bind_result($password);
if($stmt->fetch()) {
	if(!strcmp($password, $hashed_old) and !strcmp($hashed_new, $hashed_re)) {
		$stmt->close();
		$sql = "UPDATE user_wp SET password = ? WHERE uid = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $hashed_new, $_SESSION["user"]["uid"]);
		if($stmt->execute()) {
			echo "修改成功！";
			session_destroy();
			echo "<a href='index.php'>" . "返回首页" . "</a><br>";
		} else {
			echo $stmt->error;
		}
	} else {
		echo "输入有误";
	}
} else {
	echo "查询密码时出错";
}
$stmt->close();
$conn->close();

?>