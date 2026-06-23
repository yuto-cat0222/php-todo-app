<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>データベース削除</title>
</head>

<body>
    <?php
    if(!isset($_POST['del'])) {
        header("Location:todo.php");
        exit;
    }
    $del = $_POST['del'];

    try {
        $db = new PDO('mysql:host=localhost;dbname=todo_db;charset=utf8', 'root', '');
        $sto = $db->prepare('DELETE FROM todo_list WHERE id=:id');
        $sto->bindParam(':id', $del, PDO::PARAM_INT);
        if($sto->execute()) {
            print("<p>データを削除しました。TODOリストに戻ります。</p>");
            header("refresh:2;url=todo.php");
        } else {
            print("<p>SQL文実行時にエラーが発生しました</p>");
        }
        $db = null;
    } catch(PDOException $e) {
         die('処理に失敗しました: ' . $e->getMessage());
    }

    ?>
</body>

</html>