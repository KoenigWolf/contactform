<?php
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

// SQL文の準備と実行
$stmt = $conn->prepare("DELETE FROM inquiries WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "メッセージが削除されました。";
} else {
    echo "メッセージ削除中にエラーが発生しました: " . $stmt->error;
}

// ステートメントと接続を閉じる
$stmt->close();
$conn->close();
?>
