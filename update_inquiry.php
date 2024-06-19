<?php
// データベース接続情報
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contactform";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // データベース接続の作成
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 接続チェック
    if ($conn->connect_error) {
        die("接続失敗: " . $conn->connect_error);
    }

    // SQL文の準備と実行
    $stmt = $conn->prepare("UPDATE inquiries SET name=?, email=?, message=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $message, $id);

    if ($stmt->execute()) {
        echo "メッセージが更新されました。";
    } else {
        echo "メッセージ更新中にエラーが発生しました: " . $stmt->error;
    }

    // ステートメントと接続を閉じる
    $stmt->close();
    $conn->close();
} else {
    echo "無効なリクエストです。";
}
?>
