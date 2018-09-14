<?php

//Einstellungen aus der INI-Tabelle lesen
function getSetting($keyword, $setting){
	global $con;
	$sql = "SELECT KEYVALUE FROM ".DB_PREFIX."ini WHERE KEYWORD = '".$keyword."' AND SETTING = '".$setting."'";
	$res = mysqli_query($con, $sql);
	$row = mysqli_fetch_row($res);
	return $row[0];
}

//Prüfen, ob Episode geschlossen ist
function episodeclosed($episode){
	global $con;
	$sql = "SELECT DONE FROM ".DB_PREFIX."episoden WHERE ID = ".$episode;
	$res = mysqli_query($con, $sql);
	$row = mysqli_fetch_row($res);
	return $row[0];
}

//Anzahl der Beiträge einer Kategorie
function getnumber($table, $category, $episode, $append){
	global $con;
	$sql = "SELECT * FROM ".DB_PREFIX.$table." WHERE ID_CATEGORY = '".$category."' AND ID_EPISODE = '".$episode."' ".$append;
	$res = mysqli_query($con, $sql);
	$num = mysqli_num_rows($res);
	return $num;
}

//???
function getnumber_user($value, $episode, $user){
	global $con;
	$sql = "SELECT ".$value." FROM ".DB_PREFIX."view_episode_users WHERE EPISODE_USERS_ID_EPISODE = '".$episode."' AND EPISODE_USERS_ID_USER = ".$user;
	$res = mysqli_query($con, $sql);
	$row = mysqli_fetch_row($res);
	return $row[0];
}

//Einstellungen der Kategorie lesen
function getSettingCat($value, $cat_id){
	global $con;
	$sql_cat_setting = "SELECT ".$value." FROM ".DB_PREFIX."categories WHERE ID= ".$cat_id;
	$res_cat_setting = mysqli_query($con, $sql_cat_setting);
	$row_cat_setting = mysqli_fetch_row($res_cat_setting);
	return $row_cat_setting[0];
}

//Benutzerebene lesen
function getPermission($user_id){
	global $con;
	$sql_user_permission = "SELECT ID_LEVEL FROM ".DB_PREFIX."view_users_usergroups WHERE ID_USER = ".$user_id;
	$user_permission_result = mysqli_query($con, $sql_user_permission);
	$user_permission_row = mysqli_fetch_row($user_permission_result);
	return $user_permission_row[0]; 
	}	

//Benutzerinfos lesen	
function userinfos($user_id, $row){
	global $con;
	$sql_user_infos = "SELECT ".$row." FROM ".DB_PREFIX."users WHERE ID = ".$user_id;
	$user_info_result = mysqli_query($con, $sql_user_infos);
	$user_info_row = mysqli_fetch_row($user_info_result);
	return $user_info_row[0];
}

//Prüfen, ob Benutzer der Episode zugewiesen ist
function userInEpisode($user_id, $id_episode){
	global $con;
	$sql_user_in_episode = "SELECT * FROM ".DB_PREFIX."episode_users WHERE ID_EPISODE = ".$id_episode." AND ID_USER = ".$user_id;
	$sql_user_in_episode_result = mysqli_query($con, $sql_user_in_episode);
	return mysqli_num_rows($sql_user_in_episode_result);
}

//Anzahl der Links/Beuträge einer Kategorie prüfen
function linksincat($table, $id_episode, $id_cat){
	global $con;
	$sql_links_in_cat = "SELECT * FROM ".DB_PREFIX.$table." WHERE ID_CATEGORY = ".$id_cat." AND ID_EPISODE = ".$id_episode;
	$sql_links_in_result = mysqli_query($con, $sql_links_in_cat);
	return mysqli_num_rows($sql_links_in_result);
}

//Nummer der aktuellen Episode
function episode_nummer($id_episode){
	global $con;
	$sql_episode_nummer = "SELECT NUMMER FROM ".DB_PREFIX."episoden WHERE ID=".$id_episode;
	$sql_episode_nummer_result = mysqli_query($con, $sql_episode_nummer);
	$sql_episode_nummer_row = mysqli_fetch_row($sql_episode_nummer_result);
	return $sql_episode_nummer_row[0];
}
?>