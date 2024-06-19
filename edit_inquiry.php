<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: login.html");
    exit();
}

// データベース接続情報
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contactform";

$id = $_GET['id'];

// データベース接続の作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// SQLクエリの実行
$sql = "SELECT id, name, email, message FROM inquiries WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>問い合わせ内容の編集</title>
</head>
<body>
    <h1>問い合わせ内容の編集</h1>
    <form action="update_inquiry.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="name">名前:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required><br>
        <label for="email">メールアドレス:</label><br>
        <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required><br>
        <label for="message">メッセージ:</label><br>
        <textarea id="message" name="message" rows="4" cols="50" required><?php echo $row['message']; ?></textarea><br>
        <input type="submit" value="更新">
    </form>
</body>
</html>
