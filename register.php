<?php
// データベース接続情報
$db_servername = "localhost";
$db_username = "root";
$db_password = ""; // MAMPのMySQL rootユーザーのパスワード
$dbname = "contactform";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // パスワードのハッシュ化
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // データベース接続の作成
    $conn = new mysqli($db_servername, $db_username, $db_password, $dbname);

    // 接続チェック
    if ($conn->connect_error) {
        die("接続失敗: " . $conn->connect_error);
    }

    // SQL文の準備と実行
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "ユーザーが登録されました。";
    } else {
        echo "ユーザー登録中にエラーが発生しました: " . $stmt->error;
    }

    // ステートメントと接続を閉じる
    $stmt->close();
    $conn->close();
} else {
    echo "無効なリクエストです。";
}
?>
