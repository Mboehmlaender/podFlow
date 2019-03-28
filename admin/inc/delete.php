/*********************************************************************
    Michael Böhmländer <info@podflow.de>
    Copyright (c)  2019 podflow!
    http://www.podflow.de
    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See license.txt for details.
**********************************************************************/

<?php 
include('../../config/dbconnect.php');
session_start();
global $con;


if(isset($_POST)){
	
	//Kategorie löschen
	if(isset($_GET['del_category'])){
		
		$sql_select_templates = "SELECT * FROM ".DB_PREFIX."episode_templates";
		$sql_select_templates_result = mysqli_query($con, $sql_select_templates);
		while($sql_select_templates_row = mysqli_fetch_assoc($sql_select_templates_result))
		{
			$array = explode(',', $sql_select_templates_row['CATEGORIES']);
			$array = array_diff($array, [$_POST['cat_id']]);
			$array_new = implode(',', $array);
			
			$sql_update_template = "UPDATE ".DB_PREFIX."episode_templates SET CATEGORIES = '".$array_new."' WHERE ID = ".$sql_select_templates_row['ID'];
			mysqli_query($con, $sql_update_template);
		}
		
				
		
		$sql_delete_category = "DELETE FROM ".DB_PREFIX."categories WHERE ID = ".$_POST['cat_id'].";";
		$sql_delete_category .= "DELETE FROM ".DB_PREFIX."episode_categories WHERE ID_CATEGORY = ".$_POST['cat_id'].";";
		$sql_delete_category .= "DELETE FROM ".DB_PREFIX."links WHERE ID_CATEGORY = ".$_POST['cat_id'].";";
		$sql_delete_category .= "DELETE FROM ".DB_PREFIX."topics WHERE ID_CATEGORY = ".$_POST['cat_id'];
 		mysqli_multi_query($con, $sql_delete_category); 
 		echo $sql_delete_category;
		return;
	}

	//Benutzer löschen
	if(isset($_GET['delete_user'])){
		
		$sql_select_templates = "SELECT * FROM ".DB_PREFIX."episode_templates";
		$sql_select_templates_result = mysqli_query($con, $sql_select_templates);
		while($sql_select_templates_row = mysqli_fetch_assoc($sql_select_templates_result))
		{
			$array = explode(',', $sql_select_templates_row['USERS']);
			$array = array_diff($array, [$_POST['user_id']]);
			$array_new = implode(',', $array);
			
			$sql_update_template = "UPDATE ".DB_PREFIX."episode_templates SET USERS = '".$array_new."' WHERE ID = ".$sql_select_templates_row['ID'];
			mysqli_query($con, $sql_update_template);
		}
		
		$sql_delete_user = "DELETE FROM ".DB_PREFIX."users WHERE ID = ".$_POST['user_id'].";";
		$sql_delete_user .= "DELETE FROM ".DB_PREFIX."topics WHERE ID_USER = ".$_POST['user_id'].";";
		$sql_delete_user .= "DELETE FROM ".DB_PREFIX."links WHERE ID_USER = ".$_POST['user_id'].";";
		$sql_delete_user .= "DELETE FROM ".DB_PREFIX."podcast_users WHERE ID_USER = ".$_POST['user_id'].";";
		$sql_delete_user .= "DELETE FROM ".DB_PREFIX."episode_users WHERE ID_USER = ".$_POST['user_id'];
		mysqli_multi_query($con, $sql_delete_user); 
		echo $sql_delete_user;
		return;
	}

	//Vorlage löschen
	if(isset($_GET['delete_template'])){
		$sql_delete_template = "DELETE FROM ".DB_PREFIX."episode_templates WHERE ID = ".$_POST['template_id'].";";
		mysqli_multi_query($con, $sql_delete_template); 
		echo $sql_delete_template;
		return;
	}

	//Episode löschen
	if(isset($_GET['delete_episode'])){
		$sql_delete_episode = "DELETE FROM ".DB_PREFIX."episoden WHERE ID = ".$_POST['episode_id'].";";
		$sql_delete_episode .= "DELETE FROM ".DB_PREFIX."topics WHERE ID_EPISODE = ".$_POST['episode_id'].";";
		$sql_delete_episode .= "DELETE FROM ".DB_PREFIX."links WHERE ID_EPISODE = ".$_POST['episode_id'].";";
		$sql_delete_episode .= "DELETE FROM ".DB_PREFIX."episode_users WHERE ID_EPISODE = ".$_POST['episode_id'].";";
		$sql_delete_episode .= "DELETE FROM ".DB_PREFIX."episode_categories WHERE ID_EPISODE = ".$_POST['episode_id'];
		mysqli_multi_query($con, $sql_delete_episode); 
		echo $sql_delete_episode;
		$_SESSION['cur_episode'] = "";
		return;
	}

	//Podcast löschen
	if(isset($_GET['delete_podcast'])){
		$sql_delete_podcast = "DELETE FROM ".DB_PREFIX."episode_categories WHERE ID_EPISODE IN (SELECT ID FROM ".DB_PREFIX."episoden WHERE ID_PODCAST = ".$_POST['podcast_id'].");";
		$sql_delete_podcast .= "DELETE FROM ".DB_PREFIX."episode_users WHERE ID_EPISODE IN (SELECT ID FROM ".DB_PREFIX."episoden WHERE ID_PODCAST = ".$_POST['podcast_id'].");";
		$sql_delete_podcast .= "DELETE FROM ".DB_PREFIX."episoden WHERE ID_PODCAST = ".$_POST['podcast_id'].";";
		$sql_delete_podcast .= "DELETE FROM ".DB_PREFIX."episode_templates WHERE ID_PODCAST = ".$_POST['podcast_id'].";";

		$sql_delete_podcast .= "DELETE FROM ".DB_PREFIX."ini WHERE KEYWORD = 'PC_PREFIX' AND SETTING = ".$_POST['podcast_id'].";";
		$sql_delete_podcast .= "DELETE FROM ".DB_PREFIX."ini WHERE KEYWORD = 'PC_COLOR' AND SETTING = ".$_POST['podcast_id'].";";


		$sql_delete_podcast .= "DELETE FROM ".DB_PREFIX."topics WHERE ID_PODCAST = ".$_POST['podcast_id'].";";
		$sql_delete_podcast .= "DELETE FROM ".DB_PREFIX."links WHERE ID_PODCAST = ".$_POST['podcast_id'].";";

		$sql_delete_podcast .= "DELETE FROM ".DB_PREFIX."podcast_users WHERE ID_PODCAST = ".$_POST['podcast_id'].";";
		$sql_delete_podcast .= "DELETE FROM ".DB_PREFIX."podcast WHERE ID = ".$_POST['podcast_id'].";";
		
		$sql_delete_podcast .= "DELETE FROM ".DB_PREFIX."categories WHERE ID_PODCAST = ".$_POST['podcast_id'].";";

		mysqli_multi_query($con, $sql_delete_podcast); 
		echo $sql_delete_podcast;
		if($_SESSION['podcast'] == $_POST['podcast_id'])
			{
				$_SESSION['podcast'] = "";
			}
		return;
	}
}
?>