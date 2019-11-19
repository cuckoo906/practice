<?php
$filename="mission_3-5-4.txt";
$edit_name="名前";
$edit_comment="コメント";
$edit_number="";
$edit_number2="";


if(isset($_POST["edit_button"])){//編集ボタン押した場合
	if (empty($_POST["edit_number"]) ){//empty()関数でチェック
		$error="Error:Edit number is empty.";
	}else if(empty($_POST["pass3"])){
		$error="Error:Password is empty.";
	}else{//編集対象番号送信された場合
		$o=$_POST["edit_number"];//編集対象番号を変数に代入
		$p=file($filename);//file()でファイルの中身を1行ずつ読み込み配列変数に代入

		foreach ($p as $value){//配列の数（＝行数）だけループさせる
			$q=explode("<>", $value);//区切り文字「<>」で分割
			$r=$q[0];//投稿番号を取得
			if($r == $o){//編集対称番号と投稿番号が等しい場合
				if($q[4] == $_POST["pass3"]){//パスワードが一致している場合
					$edit_name=$q[1];//名前を取得
					$edit_comment=$q[2];//コメントを取得
					$edit_number2=$_POST["edit_number"];//hiddenの編集番号に代入
				}else{
					$error="Error:Password is invalied.";
				}
			}else{
			}//編集対象番号と投稿番号が等しい場合ここまで
		}//foreachここまで
	}//編集対象番号送信された場合ここまで
}
//編集ボタン押した場合ここまで

else if (isset($_POST["submit_button"])){//送信ボタンを押した場合
	if(empty($_POST["edit_number2"])){//新規投稿の場合
		if (empty($_POST["name"])){//empty()関数でチェック
			$error="Error:Name is empty.";
		}else if(empty($_POST["comment"])){
			$error="Error:Comment is empty.";
		}else if(empty($_POST["pass1"])){
			$error="Error:Password is empty.";		
		}else{//コメント名前パスワードが送信された場合
			//投稿番号の取得
			if (file_exists($filename)){
				$x=file($filename);//ファイルを配列に代入
				$n=count($x);//ファイル（配列）の行数をカウント
				$q=explode("<>", $x[$n-1]);//配列の最終行を区切り文字「<>」で分割
				$n=$q[0];//最新の投稿番号を取得
			}else{
				$n=0;
			}//投稿番号の取得ここまで

			$k=$_POST["name"];
			$i=$_POST["comment"];
			$d=date("Y/m/d H:i:s");
			$z=$_POST["pass1"];
			$n=$n+1;
			$l=$n."<>".$k."<>".$i."<>".$d."<>".$z."<>";
		
			$fp=fopen($filename, "a");
			fwrite($fp, $l."\n");
			fclose($fp);

		}//コメントと名前とパスワードが送信された場合ここまで

	}else {//再編集したものの投稿の場合
		if (empty($_POST["name"])){//empty()関数でチェック
			$error="Error:Name is empty.";
			$edit_number2=$_POST["edit_number2"];
		}else if(empty($_POST["comment"])){
			$error="Error:Comment is empty.";
			$edit_number2=$_POST["edit_number2"];
		}else if(empty($_POST["pass1"])){
			$error="Error:Password is empty.";
			$edit_number2=$_POST["edit_number2"];		
		}else{//コメント名前パスワードが送信された場合
		//編集された（＝編集番号と）コメントと名前とパスワードが送信された場合（
			$o=$_POST["edit_number2"];//編集対象番号を変数に代入
			$k=$_POST["name"];
			$i=$_POST["comment"];
			$d=date("Y/m/d H:i:s");
			$z=$_POST["pass1"];

			$p=file($filename);//file()でファイルの中身を1行ずつ読み込み配列変数に代入

			$fp2=fopen($filename, "w");//ファイルを一度からにしてあらためてファイルを開き、
		
			foreach ($p as $value){//配列の数（＝行数）だけループさせる
				$q=explode("<>", $value);//区切り文字「<>」で分割
				$r=$q[0];//投稿番号を取得
		
				if($r == $o){//編集対称番号と投稿番号が等しい場合
					$l=$o."<>".$k."<>".$i."<>".$d."<>".$z."<>";
					fwrite($fp2, $l."\n");
				}else{
					fwrite($fp2, $value);
				}
			}//foreachここまで
			fclose($fp2);
		}//編集された(=編集番号と)コメントと名前とパスワードが送信された場合ここまで
	}//再編集したものの投稿の場合ここまで
//送信ボタン押した場合ここまで



}else if (isset($_POST["delete_button"])){//削除ボタンを押した場合
	if (empty($_POST["delete_number"]) ){//empty()関数でチェック
		$error="Error:Delete number is empty.";
	}else if(empty($_POST["pass2"])){
		$error="Error:Password is empty. ";
	}else{//削除対象番号送信された場合
		$o=$_POST["delete_number"];//削除対象番号を変数に代入
		$p=file($filename);//file()でファイルの中身を1行ずつ読み込み配列変数に代入

		$fp2=fopen($filename, "w");//ファイルを一度からにしてあらためてファイルを開き、

		foreach ($p as $value){//配列の数（＝行数）だけループさせる
			$q=explode("<>", $value);//区切り文字「<>」で分割
			$r=$q[0];//投稿番号を取得
			if($r == $o){//削除対称番号と投稿番号が等しい場合
				if($q[4] == $_POST["pass2"]){//パスワードが一致している場合
				}else{	
					$error="Error:Password is invalied.";
					fwrite($fp2, $value);
				}
			}else{
				fwrite($fp2, $value);
			}
		}//foreachここまで
		fclose($fp2);
	}//削除対象番号送信された場合ここまで
}
//削除ボタン押した場合ここまで

?>




<html>
<body>

<meta charset="UTF-8">
<form method="POST" //
action="mission_3-5-4.php"//送信ボタンを押したときに指定したページに遷移させる
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
if (file_exists($filename)){
	$w=file($filename);
	foreach ($w as $value){
		$m=explode("<>", $value);
		echo $m[0]."　".$m[1]."　".$m[2]."　".$m[3]."<br>";//配列の一行を出力
	}

}else{
}
?>
</form>
</body>
</html>