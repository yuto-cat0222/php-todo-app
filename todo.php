<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="todo.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>todo</title>
</head>

<body>
    <h1>TODOリスト</h1>
    <p>メモを追加・更新・削除できます</p>

    <form id="add" action="todo.php" method="post">
        <textarea class="form" name="data" value="" rows="3" cols="20" wrap="hard"></textarea>
        <input class="submit-btn add-btn" type="submit" value="追加" />
</form>



<?php
if(isset($_POST['data'])) {
    try{

        $db = new PDO('mysql:host=localhost;dbname=todo_db;charset=utf8' , 'root' , '');
        $todo_text = $_POST['data'];
        date_default_timezone_set('Asia/Tokyo');
        $todo_date = date("Y-m-d H:i:s");
        $sto = $db->prepare('INSERT INTO todo_list(todo,tododate) VALUES (:todoValue,:tododateValue)');
        $sto->bindValue(':todoValue' , $todo_text);
        $sto->bindValue(':tododateValue' , $todo_date);
        if($sto->execute()) {
            print("<p>データを追加しました</p>");
        } else {
            print("<p>SQL文実行時にエラーが発生しました</p>");
        }
        $db = null;
    }  catch(PDOException $e) {
        die('処理に失敗しました:' . $e->getMessage());
    }
}


try{
    $db=new PDO('mysql:host=localhost;dbname=todo_db;charset=utf8' , 'root' , '');
    $sql="SELECT * FROM todo_list ORDER BY tododate DESC";
    $sto = $db->prepare($sql);
    $sto->execute();

    $dataList = array();
    while($row = $sto->fetch()) {
        array_push($dataList, ["id" => $row['id'], "todo" => $row['todo'], "tododate" =>$row['tododate']]);
    }

    $db = NULL;
} catch (PDOException $e) {
    die($e->getMessage());
}
?>

<?php foreach($dataList as $data) : ?>
    <div class="group">
        <p><?php echo $data['todo']; ?></p>
        <p><?php echo $data['tododate']; ?></p>
        <form id='del' action='del.php' method='post'>
            <input class="del-btn" type='submit' value='削除'/>
            <input type='hidden' name='del' value='<?php echo $data['id'];?>'/>
        </form>
        <form id='update' action='update.php' method='post'>
            <input class="edit-btn" type='submit' value='編集'/>
            <input type='hidden' name='update' value='<?php echo $data['id']; ?>'/>
        </form>
    </div>
    <br>
  <?php endforeach ?>
</body>

</html>