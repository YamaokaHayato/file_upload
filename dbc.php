<?php

function dbc()
{
    $host = "localhost";
    $dbname = "file_db";
    $user = "root";
    $pass = "root";

    try {
        $dns = "mysql:host=$host;dbname=$dbname;charset=utf8";

        $pdo = new PDO($dns, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        exit($e->getMessage());
    }

}

/**
 * ファイルデータを保存
 * 
 * @param string  $filename ファイル名
 * @param string  $save_path 保存先のパス
 * @param string  $caption 投稿の説明
 * @return bool $result
 */
function fileSave($filename, $save_path, $caption)
{
    $result = false;

    $sql = "INSERT INTO file_table (file_name, file_path, description) VALUE (?, ?, ?)";

    try {
        $stmt = dbc()->prepare($sql);
        $stmt->bindValue(1, $filename);
        $stmt->bindValue(2, $save_path);
        $stmt->bindValue(3, $caption);
        $result = $stmt->execute();
        return $result;
    } catch (Exception $e) {
        echo $e->getMessage();
        return $result;
    }



}

/**
 * ファイルデータを取得
 * @return array $fileData
 */
function getAllFile()
{
    $sql = "SELECT * FROM file_table";

    $fileData = dbc()->query($sql);

    return $fileData;
}

function h($s)
{
    return htmlspecialchars(($s . ENT_QUOTES . "UTF-8"));
}

?>