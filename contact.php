<?php
// データベース接続情報
$servername = "localhost";
$username = "root";
$password = ""; // MAMPのMySQL rootユーザーのパスワード
$dbname = "contactform";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // XSS対策のためのエスケープ処理
    function test_input($data) {
        $data = trim($data);  // 不要な空白を除去
        $data = stripslashes($data);  // バックスラッシュを除去
        $data = htmlspecialchars($data);  // 特殊文字をHTMLエンティティに変換
        return $data;
    }

    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $message = test_input($_POST["message"]);

    // バリデーションチェック
    if (empty($name) || empty($email) || empty($message)) {
        echo "全てのフィールドを記入してください。";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "無効なメールアドレス形式です。";
        exit;
    }

    // データベース接続の作成
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 接続チェック
    if ($conn->connect_error) {
        die("接続失敗: " . $conn->connect_error);
    }

    // SQL文の準備と実行
    $stmt = $conn->prepare("INSERT INTO inquiries (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        echo "メッセージが送信され、データベースに保存されました。";
    } else {
        echo "データベース保存中にエラーが発生しました: " . $stmt->error;
    }

    // ステートメントと接続を閉じる
    $stmt->close();
    $conn->close();
} else {
    echo "無効なリクエストです。";
}
?>
