<?php session_start(); ?>

<html>
<body>

<head>
<meta charset="UTF-8">
<title>问题</title>
</head>

<?php
$conn = new mysqli("localhost", "guest", "100000", "QnADB");
if($conn->connect_error) {
	die("连接失败:" . mysqli_connect_error());
}
$conn->set_charset("utf8");
$sql = "SELECT qid, content, uid FROM question WHERE qid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_GET["qid"]);
$stmt->execute();
$stmt->bind_result($qid, $content, $askuid);
if(!$stmt->fetch()) {
	echo "<h1>404</h1><h2>你似乎来到了没有知识存在的荒原</h2>";
	$stmt->close();
	$conn->close();
	die;
}
$stmt->close();
$sql = "SELECT name, avatar FROM users WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $askuid);
$stmt->execute();
$stmt->bind_result($name, $avatar);
if(!$stmt->fetch()) {
	echo "<h1>404</h1><h2>你似乎来到了没有知识存在的荒原</h2>";
	$stmt->close();
	$conn->close();
	die;
}
echo is_null($avatar)?"<img src='' height='50' width='50'><br>":("<img src='" . $avatar . "' height='50', width='50'") . "<br>";
echo "<h2>" . htmlspecialchars($name, ENT_QUOTES) . "的提问</h2>";
echo "<h3>" . htmlspecialchars($content, ENT_QUOTES) . "</h3>";
echo "<hr />";
$stmt->close();
$sql = "SELECT avatar, name, content FROM users NATURAL JOIN answer WHERE qid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $qid);
$stmt->execute();
$stmt->bind_result($avatar, $name, $content);
while($stmt->fetch()) {
	echo is_null($avatar)?"<img src='' height='50' width='50'><br>":("<img src='" . $avatar . "' height='50', width='50'") . "<br>";
	echo "<h2>" . htmlspecialchars($name, ENT_QUOTES) . "的回答</h2>";
	echo "<p>" . htmlspecialchars($content, ENT_QUOTES) . "</p>";
	echo "<hr />";
}
$stmt->close();
if(isset($_SESSION["user"])) {
	?>
	<h2>写下你的回答</h2>
	<form method="post">
	<textarea rows="10" cols="80" name="answer"></textarea><br>
	<input type="submit" value="提交"><br>
	</form>
	<?php
	if($_SERVER["REQUEST_METHOD"]=="POST" && trim($_POST["answer"]) == true) {
		$conn->close();
		$conn = new mysqli("localhost", "user", "VEk8qg", "QnADB");
		if($conn->connect_error) {
			die("连接失败:" . mysqli_connect_error());
		}
		$conn->set_charset("utf8");
		$sql = "REPLACE INTO answer(qid, uid, content) VALUES (?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("sss", $qid, $_SESSION["user"]["uid"], $_POST["answer"]);
		if($stmt->execute()) {
			echo("成功发送回答！");
		} else {
			echo("发送回答失败！");
		}
		$stmt->close();
	}
}
$conn->close();

?>