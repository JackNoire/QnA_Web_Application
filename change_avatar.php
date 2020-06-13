<?php
session_start();
$avatar_url = $_POST["avatar"];
if (!filter_var($avatar_url, FILTER_VALIDATE_URL)) {
	echo "非法的url地址";
	die;
} elseif (strlen($avatar_url) > 200) {
	echo "url地址过长";
	die;
}
$conn = new mysqli("localhost", "user", "VEk8qg", "QnADB");
if($conn->connect_error) {
	die("连接失败:" . mysqli_connect_error());
}
$conn->set_charset("utf8");
$sql = "UPDATE user_wp SET avatar = ? WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $avatar_url, $_SESSION["user"]["uid"]);
if($stmt->execute()) {
	echo "修改成功！";
	$_SESSION["user"]["avatar"] = $avatar_url;
	echo "<a href='index.php'>" . "返回首页" . "</a><br>";
} else {
	echo $stmt->error;
}
$stmt->close();
$conn->close();

?>