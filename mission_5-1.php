<?php
$edit_name="名前";
$edit_comment="コメント";
$edit_number2="";
$error="";

//4-1データベースへの接続
$dsn ='データベース名';
$user ='ユーザー名';
$password ='パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
//4-1ここまで
	

//4-2テーブルの作成
//変数sqlに""内のこれらの文字を.でつなぎ合わせて代入
$sql = "CREATE TABLE IF NOT EXISTS tbtest3"
//「tbtest」テーブルを作成
." ("
."id INT AUTO_INCREMENT PRIMARY KEY,"
//"1つ目のカラムの名前「id」 データ型「INT」あとはオプション"
//idカラム（＝列）にオートインクレメント（自動的に連番を格納する）を設定。書き方は　CREATE TABLE テーブル名 (カラム名 データ型 AUTO_INCREMENT);
//AUTO_INCREMENT を設定するカラム(列)は、主キー（PRIMARY KEY）か、ユニークキー（UNIQUE KEY）である必要があります。
//https://26gram.com/mysql-auto-incrementを参照

//主キー (primary key)とはデータベースにおいてデータの出席番号として使われる項目。もう少し具体的に書くとデータベースのデータ（行、レコード）を一意に識別するための項目です。
//https://wa3.i-3-i.info/word1991.html参照
."name char(32),"//"2つ目のカラムの名前「name」データ型「char(32)」"
."comment TEXT,"//"3つ目のカラムの名前「commet」データ型「TEXT」"
." updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,"//https://hit.hateblo.jp/entry/2016/01/10/020958参照
."pass TEXT"
.");";
$stmt = $pdo->query($sql);//$pdoからquery($sql)を取り出し、$stmtに代入
//4-2ここまで


if (isset($_POST["submit_button"])){//送信ボタンを押した場合
	if(empty($_POST["edit_number2"])){//新規投稿の場合
		if (empty($_POST["name"])){//empty()関数でチェック
			$error="Error:Name is empty.";
		}else if(empty($_POST["comment"])){
			$error="Error:Comment is empty.";
		}else if(empty($_POST["pass1"])){
			$error="Error:Password is empty.";		
		}else{//コメント名前パスワードが送信された場合

			//4-5データの挿入
			//bindParamの引数（:nameなど）は4-2でどんな名前のカラムを設定したかで変える必要がある。
			//カラム (column)とは行列で表現される表の「列」のこと。もしくはデータベースに入っているデータの項目のことです.
			//なお、意図通り入力が出来ているかどうかは4-6にて確認できる。
			$sql = $pdo -> prepare("INSERT INTO tbtest3 (name, comment, pass) VALUES (:name, :comment, :pass)");
			//変数pdoからprepare()を取り出し、$sqlに代入
			//「tbtest」テーブルの「name」,「comment」というカラムのそれぞれにVALUES (:name, :comment) のように:name と :comment というパラメータを与える
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			//変数sqlからbindParam()を取り出す
			//ここで、:name とかのパラメータに値を入れてます。
			//bindParam ですが、こいつは (':name', $name, PDO::PARAM_STR) のように、一個目で :name のようにさっき与えたパラメータを指定。
			//２個目に、それに入れる変数を指定します。bindParam には直接数値を入れれない。変数のみです。
			//３個目で型を指定。PDO::PARAM_STR は「文字列だよ」って事。
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			//変数sqlからbindParam()を取り出す
			$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$name =$_POST["name"];
			$comment =$_POST["comment"]; //好きな名前、好きな言葉は自分で決めること
			$pass =$_POST["pass1"];
			$sql -> execute();//変数sqlからexecute()を取り出す
			//4-5ここまで
		}
	}else{

		//bindParamの引数（:nameなど）は4-2でどんな名前のカラムを設定したかで変える必要がある。
		$id = $_POST["edit_number2"]; //変更する投稿番号
		$name = $_POST["name"];
		$comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
		$pass = $_POST["pass1"];
		$sql = 'update tbtest3 set name=:name,comment=:comment,pass=:pass where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
	}

}else if (isset($_POST["delete_button"])){//削除ボタンを押した場合
	if (empty($_POST["delete_number"]) ){//empty()関数でチェック
		$error="Error:Delete number is empty.";
	}else if(empty($_POST["pass2"])){
		$error="Error:Password is empty.";
	}else{//削除対象番号送信された場合

		$id = $_POST["delete_number"];
		$pass = $_POST["pass2"];


		$sql = 'SELECT * FROM tbtest3';//tbtestから選ぶ
		$stmt = $pdo->query($sql);//$pdoからquery($sql)を抜き出し、$stmtに代入
		$results = $stmt->fetchAll();//stmtからfetchAll()を抜き出し、$resultに代入。fetchAll()は、結果セットに残っている全ての行を含む配列を返します。
		foreach ($results as $row){
			//$rowの中にはテーブルのカラム名が入る
			if($pass==$row['pass']){
				$sql = 'delete from tbtest3 where id=:id';//パスワードが一致している場合
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}else{
				$error="Error:Password is invalied.";
			}
		}
	}//削除対象番号送信された場合ここまで
//削除ボタン押した場合ここまで

}else if(isset($_POST["edit_button"])){//編集ボタン押した場合
	if (empty($_POST["edit_number"]) ){//empty()関数でチェック
		$error="Error:Edit number is empty.";
	}else if(empty($_POST["pass3"])){
		$error="Error:Password is empty.";
	}else{//編集対象番号送信された場合
		$edit_number2 =$_POST["edit_number"];//編集対象番号を変数に代入
		$pass3=$_POST["pass3"];

		$sql = 'SELECT * FROM tbtest3';//tbtestから選ぶ
		$stmt = $pdo->query($sql);//$pdoからquery($sql)を抜き出し、$stmtに代入
		$results = $stmt->fetchAll();//stmtからfetchAll()を抜き出し、$resultに代入。fetchAll()は、結果セットに残っている全ての行を含む配列を返します。
		foreach ($results as $row){
			//$rowの中にはテーブルのカラム名が入る
			if($edit_number2==$row['id']){
				if($pass3==$row['pass']){
					$edit_name=$row['name'];
					$edit_comment=$row['comment'];
				}else{
					$error="Error:Password is invalied.";
				}
			}
		}
	}//編集対象番号送信された場合ここまで */
}
//編集ボタン押した場合ここまで

?>

<html>
<body>

<meta charset="UTF-8">
<form method="POST" //
action="mission_5-1.php"//送信ボタンを押したときに指定したページに遷移させる
>
【投稿フォーム】<br>
<input type="text" name="name" value="<?php echo $edit_name; ?>"><br>
<textarea name="comment" cols="23" rows="3"><?php echo $edit_comment; ?></textarea><br>
<input type="text" name="pass1" value="パスワード">
<input type="submit" name="submit_button" value="投稿">
<input type="hidden" name="edit_number2" value="<?php echo $edit_number2; ?>"><br>
<br>
【削除フォーム】<br>
<input type="text" name="delete_number" value="削除対称番号"><br>
<input type="text" name="pass2" value="パスワード">
<input type="submit" name="delete_button" value="削除"><br>

<br>
【編集フォーム】<br>
<input type="text" name="edit_number" value="編集対象番号"><br>
<input type="text" name="pass3" value="パスワード">
<input type="submit" name="edit_button" value="編集"><br>

<br>
---------------------------------------------------
<br>
<?php
if(empty($error)){
echo "";
}else{
echo $error;
}
?>
<br>
---------------------------------------------------
<br>
<?php

//4-1データベースへの接続
$dsn ='データベース名';
$user ='ユーザー名';
$password ='パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
//4-1ここまで

//4-6データの表示
//$rowの添字（[ ]内）は4-2でどんな名前のカラムを設定したかで変える必要がある。
$sql = 'SELECT * FROM tbtest3';//tbtestから選ぶ
$stmt = $pdo->query($sql);//$pdoからquery($sql)を抜き出し、$stmtに代入
$results = $stmt->fetchAll();//stmtからfetchAll()を抜き出し、$resultに代入。fetchAll()は、結果セットに残っている全ての行を含む配列を返します。
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['updated_at'].'<br>';
	echo "<hr>";
	}
//4-6データの表示ここまで

?>
</form>
</body>
</html>











