<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: login.html");
    exit();
}

// データベース接続情報
$servername = "localhost";
$username = "root";
$password = ""; // MAMPのMySQL rootユーザーのパスワード
$dbname = "contactform";

// データベース接続の作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// SQLクエリの実行
$sql = "SELECT id, name, email, message, created_at FROM inquiries";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // データをテーブルで表示
    echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Created At</th><th>Actions</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"]. "</td><td>" . $row["name"]. "</td><td>" . $row["email"]. "</td><td>" . $row["message"]. "</td><td>" . $row["created_at"]. "</td>";
        echo "<td><a href='edit_inquiry.php?id=" . $row["id"] . "'>Edit</a> | <a href='delete_inquiry.php?id=" . $row["id"] . "'>Delete</a></td></tr>";
    }
    echo "</table>";
} else {
    echo "0 件の結果";
}

// 接続を閉じる
$conn->close();
?>
