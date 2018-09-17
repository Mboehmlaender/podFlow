<?php 
include('../../config/dbconnect.php');
session_start();
if(!isset($_SESSION['userid']))
{
header('Location: ../login.php');
}	
global $con;


if(isset($_POST)){

	//Benutzer hinzufügen
	if(isset($_GET['add_user'])){
		$passwort_hash = password_hash($_POST['Password_add'], PASSWORD_DEFAULT);
		$username = mysqli_real_escape_string($con,$_POST['User_add']);
		$usermail = mysqli_real_escape_string($con,$_POST['User_add_mail']);
		$sql_new_user = "INSERT INTO ".DB_PREFIX."users (USERNAME, NAME_SHOW, EMAIL, PASSWORD, LEVEL_ID, ACTIVE, AVATAR) VALUES ('".$username."', '".$username."', '".$usermail."', '".$passwort_hash."', '1', '1', 'img/kein-bild.png')";
		$sql_result = mysqli_query($con, $sql_new_user);	

	}

	//Kategorie hinzufügen
	if(isset($_GET['add_cat'])){
		$cat_name = mysqli_real_escape_string($con,$_POST['cat_name']);
		$cat_visible = mysqli_real_escape_string($con,$_POST['cat_visible']);
		$cat_topics = mysqli_real_escape_string($con,$_POST['cat_topics']);
		$cat_entries = mysqli_real_escape_string($con,$_POST['cat_entries']);
		$cat_coll = mysqli_real_escape_string($con,$_POST['cat_coll']);
		$podcast = mysqli_real_escape_string($con,$_POST['podcast']);
		if(empty($cat_entries))
			{
				$cat_entries = '0';
			}

		$sql_new_cat= "INSERT INTO ".DB_PREFIX."categories (DESCR, VISIBLE, ALLOW_TOPICS, MAX_ENTRIES, COLL, ID_PODCAST) VALUES ('".$cat_name."', '".$cat_visible."','".$cat_topics."','".$cat_entries."','".$cat_coll."', '".$podcast."')";
		$sql_result_cat = mysqli_query($con, $sql_new_cat);
		echo $sql_new_cat;
		return;
	}		

	//Episode hinzufügen
	if(isset($_GET['add_episode'])){
		$title = mysqli_real_escape_string($con,$_POST['title_new']);
		$users = mysqli_real_escape_string($con,$_POST['users']);
		$cats = mysqli_real_escape_string($con,$_POST['cats']);
		if ($_POST['date'] == '')
			{
				$date = "NULL";
			}
		else
			{
				$date_post = new DateTime($_POST['date']);
				$date = "'".$date_post->format('Y-m-d')."'";		
			}

		$sql_new_episode= "INSERT INTO ".DB_PREFIX."episoden (NUMMER, TITEL, ID_PODCAST, DATE, DONE) VALUES ('".$_POST['nummer']."', '".$title."', '".$_POST['podcast']."', $date , '0')";
		$sql_result_new_episode = mysqli_query($con, $sql_new_episode);
		$new_id_episode = $con->insert_id;
		$_SESSION['cur_episode'] = $new_id_episode;
		if(!empty($cats))
			{
				$array_cats = explode(",",$cats);
				foreach($array_cats AS $cat_key)
					{
						$sql_add_cat_fromtpl = "INSERT INTO ".DB_PREFIX."episode_categories (ID_EPISODE, ID_CATEGORY) VALUES('".$new_id_episode."', '".$cat_key."')";
						$sql_add_cat_fromtpl_result = mysqli_query($con, $sql_add_cat_fromtpl);
						echo $sql_add_cat_fromtpl;
					}
			}

		if(!empty($users))
			{
			$array_users = explode(",",$users);
			foreach($array_users AS $user_key)
				{
					$sql_add_user_fromtpl = "INSERT INTO ".DB_PREFIX."episode_users (ID_EPISODE, ID_USER) VALUES('".$new_id_episode."', '".$user_key."')";
					$sql_add_user_fromtpl_result = mysqli_query($con, $sql_add_user_fromtpl);
					echo $sql_add_user_fromtpl;
				}
			}		
		return;
	}	

	//Vorlage hinzufügen
	if(isset($_GET['add_template'])){
		$title = mysqli_real_escape_string($con,$_POST['title']);
		$podcast =  mysqli_real_escape_string($con,$_POST['podcast']);
		$sql_new_template = "INSERT INTO ".DB_PREFIX."episode_templates (DESCR, ID_PODCAST) VALUES ('".$title."', '".$podcast."')";
		$sql_result_new_template = mysqli_query($con, $sql_new_template);
		echo $sql_new_template;
		return;
	}	

	//Podcast hinzufügen
	if(isset($_GET['add_podcast'])){
		$title = mysqli_real_escape_string($con,$_POST['descr']);
		$short = mysqli_real_escape_string($con,$_POST['short']);
		
		$sql_new_podcast = "INSERT INTO ".DB_PREFIX."podcast (DESCR, SHORT, COLOR) VALUES ('".$title."', '".$short."', '#FFFFFF')";
		$sql_result_new_podcast = mysqli_query($con, $sql_new_podcast);
		$new_id = $con->insert_id;

		$sql_new_podcast_INI = "INSERT INTO ".DB_PREFIX."ini (KEYWORD, SETTING, KEYVALUE, INFO) VALUES ('PC_PREFIX', '".$new_id."', '".$short."', 'Podcast Kurzbezeichner');";
		$sql_new_podcast_INI .= "INSERT INTO ".DB_PREFIX."ini (KEYWORD, SETTING, KEYVALUE, INFO) VALUES ('PC_COLOR', '".$new_id."', '#FFFFFF', 'Podcast Farbe')";
		$sql_result_new_podcast_INI = mysqli_multi_query($con, $sql_new_podcast_INI);

		$_SESSION['podcast'] = $new_id;
		$_SESSION['cur_episode'] = "";
		return;
	}	
}
?>
