<?php 
include('config.php');
include('../config/dbconnect.php');
session_start();
if(!isset($_SESSION['userid']))
	{
		header('Location: ../login.php');
	}	
  

if(isset($_POST)){

	//Podcast-Session setzen
	if(isset($_GET['set_session_podcast'])){
			$_SESSION['podcast'] = $_POST['podcast'];
			$_SESSION['cur_episode'] = "";
			return;
	}

	//Episoden-Session setzen
	if(isset($_GET['set_session_episode'])){
			$_SESSION['cur_episode'] = $_POST['episode'];
			return;
	}

	//Episoden-Session setzen --> prüfen, wo genutzt
	if(isset($_GET['update_cur_episode'])){
			$_SESSION['cur_episode'] = $_POST['episode'];
			return;
	}
			
	//Nicht abgehakte Themen/Beuträge in eine andere Episode verschieben
	if(isset($_GET['move_unchecked_content'])){
			$episode_id_current = mysqli_real_escape_string($con,$_POST['episode_id_current']);
			$episode_id_new = mysqli_real_escape_string($con,$_POST['episode_id_new']);
			$sql = "UPDATE ".DB_PREFIX."links SET ID_EPISODE = ".$episode_id_new." WHERE DONE <> 1 AND ID_EPISODE = ".$episode_id_current.";";
			$sql .= "UPDATE ".DB_PREFIX."topics SET ID_EPISODE = ".$episode_id_new." WHERE DONE <> 1 AND ID_EPISODE = ".$episode_id_current;
 			$sql_result = mysqli_multi_query($con, $sql);
			echo $sql;
			return;
		
	}
	
	//Themen/Beiträge abhaken
	if(isset($_GET['update_links'])){
			$value = mysqli_real_escape_string($con,$_POST['value']);
			$name = mysqli_real_escape_string($con,$_POST['name']);
			$pk = mysqli_real_escape_string($con,$_POST['pk']);
			$table = mysqli_real_escape_string($con,$_POST['table']);
			$now = date("Y-m-d G:i:s");
			if($value === '0')
				{
					$ts = 'NULL';
				}
			else	
				{
					$ts = "'".$now."'";
				}
			$sql = "UPDATE ".DB_PREFIX.$table." SET DONE_TS = ".$ts.", DONE='".$value."' WHERE ID=".$pk;
			$sql_result = mysqli_query($con, $sql);
			if($table === 'topics')
				{
					$sql_links_topics = "UPDATE ".DB_PREFIX."links SET DONE_TS = ".$ts.", DONE = '".$value."'  WHERE ID_TOPIC =".$pk;
					$sql_result_links_topics = mysqli_query($con, $sql_links_topics);
					echo $sql_links_topics;
				}
			echo $sql;
			return;
	}

	//Export --> Reihenfolge der Themen/Beiträge speichern	
	if(isset($_GET['set_order'])){
		$arrayItems = $_POST['item'];
		$order = 0;
		foreach ($arrayItems as $item) 
		{
			if(substr($item,0,1) == "l")
				{
					$table = "links";
				}
			else
				{
					$id_link = substr($item,1);
					$table = "topics";
					$update1 = "UPDATE ".DB_PREFIX."links SET REIHENF = '$order' WHERE ID_TOPIC='$id_link' ";
					mysqli_query($con, $update1);
				}
			$id = substr($item,1);
			$update = "UPDATE ".DB_PREFIX.$table." SET REIHENF = '$order' WHERE ID='$id' ";
			$order++; 
 			mysqli_query($con, $update);
 		}					
		return;
	}
	
	//Kategorien der Themen/Beuiträge ändern
	if(isset($_GET['set_category_sortable'])){
		$cat_id = $_POST['cat_id'];
		$table = $_POST['table'];
		$id = $_POST['pk'];
		
		if($table == 'topics')
		{
			$update = "UPDATE ".DB_PREFIX."topics SET ID_CATEGORY = '$cat_id' WHERE ID='$id' ;";
			$update .= "UPDATE ".DB_PREFIX."links SET ID_CATEGORY = '$cat_id' WHERE ID_TOPIC='$id' ";
		}
		else
		{
			$update = "UPDATE ".DB_PREFIX."links SET ID_CATEGORY = '$cat_id' WHERE ID='$id' ;";
		}
		mysqli_multi_query($con, $update);
		echo $update;
		return;
 		}		

	//Episode eines Beitrags ändern
	if(isset($_GET['set_episode_new'])){
		$episode_new = $_POST['episode_new'];
		$table = $_POST['table'];
		$id_entry = $_POST['id_entry'];
		
		if($table == 'topics')
		{
			$update = "UPDATE ".DB_PREFIX."topics SET ID_EPISODE = '$episode_new' WHERE ID='$id_entry' ;";
			$update .= "UPDATE ".DB_PREFIX."links SET ID_EPISODE = '$episode_new' WHERE ID_TOPIC='$id_entry' ";
		}
		else
		{
			$update = "UPDATE ".DB_PREFIX."links SET ID_EPISODE = '$episode_new' WHERE ID='$id_entry' ;";
		}
		mysqli_multi_query($con, $update);
		echo $update;
		return;
 		}			
	
	
/* 	//Kategorien der Themen/Beuiträge ändern
 	if(isset($_GET['up_cat'])){
		$id = mysqli_real_escape_string($con,$_POST['pk']);
		$row = mysqli_real_escape_string($con,$_POST['row']);
		$table = mysqli_real_escape_string($con,$_POST['table']);
		$value = mysqli_real_escape_string($con,$_POST['value']);
	
		$sql_update_category = "UPDATE ".DB_PREFIX.$table." SET ".$row." = '".$value."' WHERE ID = ".$id;
		$sql_update_category_result = mysqli_query($con, $sql_update_category);
		echo $sql_update_category;
		return;
	}  */

	//Episode abschließen
	if(isset($_GET['close_episode'])){
		$episode_close = mysqli_real_escape_string($con,$_POST['episode_close']);
		$sql_close_episode = "UPDATE ".DB_PREFIX."episoden SET DONE = '1' WHERE ID = ".$episode_close;
 		$sql_close_episode_result = mysqli_query($con, $sql_close_episode);
 		echo $sql_close_episode;
		return;
	}

	//Episode abschließen
	if(isset($_GET['open_episode'])){
		$episode_open = mysqli_real_escape_string($con,$_POST['episode_open']);
		$sql_open_episode = "UPDATE ".DB_PREFIX."episoden SET DONE = '0' WHERE ID = ".$episode_open;
 		$sql_open_episode_result = mysqli_query($con, $sql_open_episode);
 		echo $sql_open_episode;
		return;
	}

	//Benutzerinformationen bearbeiten	
	if(isset($_GET['edit_user'])){
		$password = mysqli_real_escape_string($con,$_POST['password_new']);
		$name_show = mysqli_real_escape_string($con,$_POST['name_show']);
		$email = mysqli_real_escape_string($con,$_POST['email']);
		$User_Id = mysqli_real_escape_string($con,$_POST['user_id']);
		$save_podcast = mysqli_real_escape_string($con,$_POST['save_podcast']);
		$save_episode = mysqli_real_escape_string($con,$_POST['save_episode']);
		$passwort_hash = password_hash($password, PASSWORD_DEFAULT);
		
		if(!empty($password))
		{
			$password_set = " PASSWORD = '".$passwort_hash."',";
		}
		else
		{
			$password_set = "";
		}
			$sql_update_user = "UPDATE ".DB_PREFIX."users SET".$password_set." EMAIL = '".$email."', NAME_SHOW ='".$name_show."', SAVE_PODCAST = ".$save_podcast.", SAVE_EPISODE = ".$save_episode." WHERE ID = ".$User_Id;
			mysqli_query($con, $sql_update_user);
			echo $sql_update_user;
			return;
	}

	//Sonstige Updatebefehle
	$table = mysqli_real_escape_string($con,$_POST['table']);
	$name = mysqli_real_escape_string($con,$_POST['name']);
	$value = mysqli_real_escape_string($con,$_POST['value']);
	$pk = mysqli_real_escape_string($con,$_POST['pk']);
	$sql = "UPDATE ".DB_PREFIX.$table." SET ".$name."='".$value."' WHERE ID=".$pk;
 	$sql_result = mysqli_query($con, $sql);
 	echo $sql;
}


?>