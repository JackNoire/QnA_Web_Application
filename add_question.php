<?php
session_start();
if(empty($_POST["question"])) {
	echo "问题不能为空！";
	die;
}
$conn = new mysqli("localhost", "user", "VEk8qg", "QnADB");
if($conn->connect_error) {
	die("连接失败:" . mysqli_connect_error());
}
$conn->set_charset("utf8");
$sql = "SELECT max(qid)+1 FROM question";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($qid);
if(!$stmt->fetch()) {
	echo "创建qid失败";
	die;
}
$stmt->close();
$sql = "INSERT INTO question(qid, content, uid, uploadtime) VALUES (?, ?, ?, now())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $qid, $_POST["question"], $_SESSION["user"]["uid"]);
if($stmt->execute()) {
	echo "添加成功！问题编号为" . $qid;
	echo "<a href='index.php'>" . "返回首页" . "</a><br>";
} else {
	echo "添加失败！";
}

?>