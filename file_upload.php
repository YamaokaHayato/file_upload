<?php
require_once("./dbc.php");
// ファイルのデータを受け取る
$file = $_FILES["img"];
// $fileの中身を変数に格納
$filename = basename($file["name"]);
$tmp_path = $file["tmp_name"];
$file_err = $file["error"];
$filesize = $file["size"];
$upload_dir = "images/";
$save_filename = date("YmdHis") . $filename;
$err_msgs = array();
$save_path = $upload_dir . $save_filename;

// キャプションの取得
// FILTER_SANITIZE_CHARS セキュリティー対策として使用
$caption = filter_input(INPUT_POST, "caption", FILTER_SANITIZE_SPECIAL_CHARS);

// キャプションのバリデーション
// 未入力の場合
if (empty($caption)) {
    array_push($err_msgs, "キャプションを入力してください");
    echo "<br>";
}

// 140文字か
// strlen 文字数を受け取る
if (strlen($caption) > 140) {
    array_push($err_msgs, "キャプションは140文字以内で入力してください");
    echo "<br>";
}

// ファイルのバリデーション
// ファイルサイズが1MB未満か
if ($filesize > 1048576 || $file_err == 2) {
    array_push($err_msgs, "ファイルサイズは1MB未満にしてください");
    echo "<br>";
}
// 拡張子は画像形式か
$allow_ext = array("jpg", "jpeg", "png");
// pathinfo() 拡張子が取得できる
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
// in_array 配列の中にデータがあったらtrueなければfalse
// 第一引数に調べたいデータ,第二引数に配列
// strtolower 全て小文字に直してくれる関数
if (!in_array(strtolower($file_ext), $allow_ext)) {
    array_push($err_msgs, "画像ファイルを添付してください");
    echo "<br>";
}
if (count($err_msgs) === 0) {
    // ファイルはあるかどうか
    if (is_uploaded_file($tmp_path)) {
        // ファイルの移動
        if (move_uploaded_file($tmp_path, $upload_dir . $save_filename)) {
            echo $filename . "を" . $upload_dir . "にアップしました";
            echo "<br>";
            // DBに保存する(ファイル名,ファイルパス,キャプション)
            $result = fileSave($filename, $save_path, $caption);

            if ($result) {
                echo "データベースに保存しました";
                echo "<br>";
            } else {
                echo "データベースへの保存が失敗しました";
                echo "<br>";
            }
        } else {
            echo "ファイルが保存できませんでした";
            echo "<br>";
        }
    } else {
        echo "ファイルが選択されていません";
        echo "<br>";
    }
} else {
    foreach ($err_msgs as $msg) {
        echo $msg;
        echo "<br>";
    }
}
?>

<a href="./upload_form.php">戻る</a>