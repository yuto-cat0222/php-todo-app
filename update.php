<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="todo.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>データベース更新</title>
</head>

<body>

    <?php

    if(!isset($_POST['update']) && (!isset($_POST['newdata']))) {
        header("Location: todo.php");
    }

    if(isset($_POST['newdata'])) {
        $newdata = $_POST['newdata'];
        $decision = $_POST['decision'];
        
        try{
            $db = new PDO('mysql:host=localhost;dbname=todo_db;charset=utf8', 'root', '');
            $sto = $db->prepare('UPDATE todo_list SET todo=:newdata WHERE id=:id');
            $sto->bindParam(':id', $decision, PDO::PARAM_INT);
            $sto->bindParam(':newdata', $newdata, PDO::PARAM_STR);
            if($sto->execute()) {
                header("Location:todo.php");
                exit;
            } else{
                print("<p>SQL文実行時にエラーが発生しました</p>");
            }
            $db = null;
        } catch(PDOException $e) {
            die("<p>処理が失敗しました</p>");
        }
    }

    if(isset($_POST['update'])) {
        $update= $_POST['update'];
        try{
            $db = new PDO('mysql:host=localhost;dbname=todo_db;charset=utf8', 'root', '');
            $sto=$db->prepare('SELECT todo FROM todo_list WHERE id=:id');
            $sto->bindParam(':id', $update, PDO::PARAM_INT);
            $sto->execute();


            $dataList = array();
            while($row=$sto->fetch()) {
                array_push($dataList, ["todo" => $row['todo']]);
            }

            $db = NULL;
        } catch (PDOException $e) {
            die('処理に失敗しました:' . $e->getMessage());
        }
    }
    ?>

    <?php foreach ($dataList as $data) : ?>
        <p>「<?php echo $data['todo']; ?>」の編集画面</p>
        <form id="decision" action="update.php" method="post">
            <textarea class="form" name="newdata" value="" rows="3" cols="20" wrap="hard"><?php echo $data['todo']; ?></textarea>
            <input class="update-btn submit-btn" type="submit" value="更新"/>
            <input type='hidden' name='decision' value='<?php echo $update; ?>'/>
        </form>
    <?php endforeach ?>

</body>

</html>