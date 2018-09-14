<?php 
include('config.php');
include('../config/dbconnect.php');
session_start();
if(!isset($_SESSION['userid']))
	{
		header('Location: ../login.php');
	}	
  

if(isset($_POST)){
	
	//Beitrag löschen
	if(isset($_GET['del_link'])){
		$pk = mysqli_real_escape_string($con,$_POST['pk']);
		$sql_delete_link = "DELETE FROM ".DB_PREFIX."links WHERE ID=".$pk;
		$sql_delete_link_result = mysqli_query($con, $sql_delete_link); 
		echo $sql_delete_link;
		return;	 
	}
		
	//Thema löschen
	if(isset($_GET['del_topic'])){
		$pk = mysqli_real_escape_string($con,$_POST['pk']);
		$sql_delete_topic = "DELETE FROM ".DB_PREFIX."links WHERE ID_TOPIC=".$pk.";";
		$sql_delete_topic .= "DELETE FROM ".DB_PREFIX."topics WHERE ID=".$pk;
		$sql_result_topic_result = mysqli_multi_query($con, $sql_delete_topic); 
		echo $sql_delete_topic;
		return;	 
	}	

	//Export --> Nicht abgehakte Themen/Beiträge löschen
	if(isset($_GET['delete_unchecked_content'])){
		$episode_id_current = mysqli_real_escape_string($con,$_POST['episode_id_current']);
		$sql_delete_unchecked = "DELETE FROM ".DB_PREFIX."links WHERE DONE <> 1 AND ID_EPISODE=".$episode_id_current.";";
		$sql_delete_unchecked .= "DELETE FROM ".DB_PREFIX."topics WHERE DONE <> 1 AND ID_EPISODE=".$episode_id_current;
 		$sql_delete_unchecked_result = mysqli_multi_query($con, $sql_delete_unchecked); 
 		echo $sql_delete_unchecked;
		return;	 
	}
}

 
?>