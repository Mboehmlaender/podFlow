<?php 
include('../../config/dbconnect.php');
session_start();
global $con;


if(isset($_POST)){

	//Podcast-Session setzen
	if(isset($_GET['set_session_podcast'])){
			$_SESSION['podcast'] = $_POST['podcast'];
			$_SESSION['cur_episode'] = "";
			$sql_update_user = "UPDATE ".DB_PREFIX."users SET LAST_PODCAST = ".$_SESSION['podcast'].", LAST_EPISODE = NULL WHERE ID = ". $_SESSION['userid'];
			mysqli_query($con, $sql_update_user);
			return;
	}

/* 	if(isset($_GET['update_links'])){
	$now = date("Y-m-d G:i:s");
	if($_POST['value'] === '0')
	{
	$ts = 'NULL';
	}
	else	
	{
	$ts = "'".$now."'";
	}
	$sql = "UPDATE ".DB_PREFIX.$_POST['table']." SET DONE_TS = ".$ts.", ".$_POST['name']."='".$_POST['value']."' WHERE ID=".$_POST['pk'];
	$sql_result = mysqli_query($con, $sql);
	if($_POST['table'] === 'topics'){
	$sql_links_topics = "UPDATE ".DB_PREFIX."links SET DONE_TS = ".$ts.", DONE = '".$_POST['value']."'  WHERE ID_TOPIC =".$_POST['pk'];
	$sql_result_links_topics = mysqli_query($con, $sql_links_topics);
	echo $sql_links_topics;
	}
	echo $sql;
	return;

	} */

	//Reihenfolge der Kategorien speichern
	if(isset($_GET['set_cat_order'])){
		$arrayItems = $_POST['category'];
		$order = 0;
		foreach ($arrayItems as $item)
			{
				$order++; 
				$update = "UPDATE ".DB_PREFIX."categories SET REIHENF = '$order' WHERE ID='$item'";
				echo $update;
				mysqli_query($con, $update);
			}					
		return;
	}

	//Episode bearbeiten
	if(isset($_GET['episode_update'])){
		$title = mysqli_real_escape_string($con,$_POST['title']);
		$number = mysqli_real_escape_string($con,$_POST['nummer']);
		if ($_POST['date'] == '')
			{
				$date = "NULL";
			}
			else
			{
				$date_post = new DateTime($_POST['date']);
				$date = "'".$date_post->format('Y-m-d')."'";
			}				
		$sql_change_episode = "UPDATE ".DB_PREFIX."episoden SET NUMMER = '".$number."', TITEL = '".$title."', DATE = ".$date." WHERE ID = ".$_POST['episode'];
		$sql_change_episode_result = mysqli_query($con, $sql_change_episode);
		echo $sql_change_episode;
		return;
	}	

	//Vorlage bearbeiten	
	if(isset($_GET['template_update']))
	{
		$title = mysqli_real_escape_string($con,$_POST['title']);			
		$category = implode(",", $_POST['category']);			
		$users = implode(",", $_POST['users']);			
		$sql_change_template = "UPDATE ".DB_PREFIX."episode_templates SET DESCR = '".$title."', CATEGORIES = '".$category."', USERS = '".$users."' WHERE ID = ".$_POST['template'];
		$sql_change_template_result = mysqli_query($con, $sql_change_template);
		echo $sql_change_template;
		return;
	}	

	//Kategorie bearbeiten
	if(isset($_GET['up_cat'])){
		$id = mysqli_real_escape_string($con,$_POST['pk']);
		$row = mysqli_real_escape_string($con,$_POST['row']);
		$table = mysqli_real_escape_string($con,$_POST['table']);
		$value = mysqli_real_escape_string($con,$_POST['value']);

		$sql_update_category = "UPDATE ".DB_PREFIX.$table." SET ".$row." = '".$value."' WHERE ID = ".$id;
		$sql_update_category_result = mysqli_query($con, $sql_update_category);
		echo $sql_update_category;
		return;
	}
	
	//Podcastbearbeiten	
	if(isset($_GET['podcast_update'])){
		$descr = mysqli_real_escape_string($con,$_POST['podcast_desc']);
		$short = mysqli_real_escape_string($con,$_POST['podcast_short']);
		$color = mysqli_real_escape_string($con,$_POST['color']);

		$sql_change_podcast = "UPDATE ".DB_PREFIX."podcast SET COLOR = '".$color."', DESCR = '".$descr."', SHORT = '".$short."' WHERE ID = ".$_POST['Podcast'];
		$sql_change_ini = "UPDATE ".DB_PREFIX."ini SET KEYVALUE = '".$short."' WHERE KEYWORD = 'PC_PREFIX' AND SETTING = ".$_POST['Podcast'].";";
		$sql_change_ini .= "UPDATE ".DB_PREFIX."ini SET KEYVALUE = '".$color."' WHERE KEYWORD = 'PC_COLOR' AND SETTING = ".$_POST['Podcast'];
		$sql_change_podcast_result = mysqli_query($con, $sql_change_podcast);
		$sql_change_ini_result = mysqli_multi_query($con, $sql_change_ini); 
		echo $sql_change_podcast;
		echo $sql_change_ini;
		return;
	}

	//Benutzer bearbeiten
	if(isset($_GET['edit_user'])){
		$password = mysqli_real_escape_string($con,$_POST['password']);
		$name_show = mysqli_real_escape_string($con,$_POST['showname']);
		$email = mysqli_real_escape_string($con,$_POST['email']);
		$username = mysqli_real_escape_string($con,$_POST['username']);
		$User_Id = mysqli_real_escape_string($con,$_POST['user_id']);
		
		if(isset($_POST['level']))
			{
				$level = "LEVEL_ID = ".mysqli_real_escape_string($con,$_POST['level']).",";
			}
			else 
			{
				$level = "";
			}
			
		$passwort_hash = password_hash($password, PASSWORD_DEFAULT);
			if(!empty($_POST['password']))
			{
				$password_set = "PASSWORD = '".$passwort_hash."',";
			}
			else
			{
				$password_set = "";
			}
			
		$sql_update_user = "UPDATE ".DB_PREFIX."users SET ".$password_set." USERNAME = '".$username."', EMAIL = '".$email."', ".$level." NAME_SHOW ='".$name_show."' WHERE ID = ".$User_Id;
		mysqli_query($con, $sql_update_user);
		echo $sql_update_user;
		return;
	}
	
	//Sonstuge Updatebefehle
	$sql = "UPDATE ".DB_PREFIX.$_POST['table']." SET ".$_POST['name']."='".$_POST['value']."' WHERE ID=".$_POST['pk'];
	$sql_result = mysqli_query($con, $sql);
	echo $sql;
	
}
?>