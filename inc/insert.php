<?php 
include('config.php');
include('../config/dbconnect.php');
session_start();
if(!isset($_SESSION['userid']))
	{
		header('Location: ../login.php');
	}	
global $con;
  

if(isset($_POST)){
	
	//Thema hinzufügen
	if(isset($_GET['add_topic'])){
		$descr = mysqli_real_escape_string($con,$_POST['descr']);
		$category = mysqli_real_escape_string($con,$_POST['category']);
		$link_descr = mysqli_real_escape_string($con,$_POST['link_descr']);
		$link_url = mysqli_real_escape_string($con,$_POST['link_url']);
		$sql_new_topic = "INSERT INTO ".DB_PREFIX."topics (ID_USER, ID_EPISODE, ID_CATEGORY, DESCR, ID_PODCAST) VALUES ('".$_SESSION['userid']."', '".$_SESSION['cur_episode']."', '".$category."', '".$descr."', '".$_SESSION['podcast']."')";
		$sql_result = mysqli_query($con, $sql_new_topic);
		echo $sql_new_topic;
		if(!empty($link_descr) || !empty($link_url))
			{
				$id_new = $con->insert_id;
				$sql_new_topic_link = "INSERT INTO ".DB_PREFIX."links (ID_USER, ID_EPISODE, ID_TOPIC, ID_CATEGORY, DESCR, URL, ID_PODCAST) VALUES ('".$_SESSION['userid']."', '".$_SESSION['cur_episode']."', '".$id_new."', '".$category."', '".$link_descr."', '".$link_url."', '".$_SESSION['podcast']."')";
				$sql_result2 = mysqli_query($con, $sql_new_topic_link);
				return;
			}
		return;
	}	

	//Beitrag zu einem Thema hinzufügen
	if(isset($_GET['add_topiclink'])){
		$descr = mysqli_real_escape_string($con,$_POST['descr']);
		$url = mysqli_real_escape_string($con,$_POST['url']);
		$topic = mysqli_real_escape_string($con,$_POST['topic']);
		$category = mysqli_real_escape_string($con,$_POST['category']);
		$sql_new_topic_link = "INSERT INTO ".DB_PREFIX."links (ID_USER, ID_EPISODE, ID_TOPIC, ID_CATEGORY, DESCR, URL, ID_PODCAST) VALUES ('".$_SESSION['userid']."', '".$_SESSION['cur_episode']."', '".$topic."', '".$category."', '".$descr."', '".$url."', '".$_SESSION['podcast']."')";
		$sql_result = mysqli_query($con, $sql_new_topic_link);
		return;
	}	

	//Beitrag hinzufügen
	if(isset($_GET['add_link'])){	
		$descr = mysqli_real_escape_string($con,$_POST['descr']);
		$url = mysqli_real_escape_string($con,$_POST['url']);
		$category = mysqli_real_escape_string($con,$_POST['category']);
		$sql_new_link = "INSERT INTO ".DB_PREFIX."links (ID_USER, ID_EPISODE, ID_CATEGORY, DESCR, URL, ID_PODCAST) VALUES ('".$_SESSION['userid']."', '".$_SESSION['cur_episode']."', '".$category."', '".$descr."', '".$url."', '".$_SESSION['podcast']."')";
		$sql_result = mysqli_query($con, $sql_new_link);
		return;
	}
}
?>
