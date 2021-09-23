<?php
    // $title = $_POST['title'];
    // $content = $_POST['content'];
    // $user_name = $_POST['user_name'];
    // if(empty($title) || empty($content) || empty($user_name)){
    //     exit("标题或者内容或者留言人不能为空");
    // }

    $host = 'localhost';
    $dbname = 'zerg';
    $user = 'root';
    $password = '';
    $user_name = "王帅";

    try{
        // $db = new PDO("mysql:host=$host;dbname=$dbname",$user,$password);
        // $sql = 'select id,title,content from message where id = :id';
        // $stmt = $db->prepare($sql);
        // $des = $stmt->execute([':id'=>1]);
        // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($result);
        // exit;

        $sql = 'insert into message(title,content,create_at,user_name) values (:title,:content,:create_at,:user_name)';
        $stmt = $db->prepare($sql);
        $data = [
            ':title' => $title,
            ':content' => $content,
            ':create_at' => time(),
            ':user_name' => $user_name,
        ];
        $result = $stmt->execute($data);
        $rows = $stmt->rowCount();
        if($rows){
            echo "留言成功";
            exit;
        }else{
            echo "留言失败";
            exit;
        }
    }catch(PDOException $e){
        echo $e->getMessage();
    }