<?php
session_start();

// データベース接続情報
$servername = "localhost";
$username = "root";
$password = ""; // MAMPのMySQL rootユーザーのパスワード
$dbname = "contactform";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // データベース接続の作成
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 接続チェック
    if ($conn->connect_error) {
        die("接続失敗: " . $conn->connect_error);
    }

    // SQL文の準備と実行
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // ユーザーが存在するかチェック
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // パスワードの検証
        if (password_verify($password, $hashed_password)) {
            // ログイン成功
            $_SESSION["userid"] = $id;
            $_SESSION["username"] = $username;
            header("Location: index.html");
            exit();
        } else {
            // パスワードが間違っています
            echo "ユーザー名またはパスワードが間違っています。";
        }
    } else {
        // ユーザーが存在しません
        echo "ユーザー名またはパスワードが間違っています。";
    }

    // ステートメントと接続を閉じる
    $stmt->close();
    $conn->close();
} else {
    echo "無効なリクエストです。";
}
?>
