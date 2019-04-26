<?php 
include('../../config/dbconnect.php');
session_start();
if(!isset($_SESSION['userid']))
	{
		header('Location: ../login.php');
	}		

//Kategeorien/Benutzer einer Episode hinzufügen 
if(isset($_GET['add'])){
	$id = $_POST['id'];
	$episode = $_POST['episode'];
	$table = $_POST['table'];
	$column = $_POST['column'];
	$row = $_POST['id_podcast'];
	$sql_add = "INSERT INTO ".DB_PREFIX.$table." (".$column.", ".$row.") VALUES (".$id.", ".$episode.")";
	$sql_add_result = mysqli_query($con, $sql_add);
	echo $sql_add;
	exit;
}

//Kategeorien/Benutzer einer Episode löschen
if(isset($_GET['remove'])){
	$id = $_POST['id'];
	$episode = $_POST['episode'];
	$table = $_POST['table'];
	$column = $_POST['column'];
	$row = $_POST['id_podcast'];
	$sql_delete = "DELETE FROM ".DB_PREFIX.$table." WHERE ".$column." = ".$id." AND ".$row." = ".$episode;
	$sql_delete_result = mysqli_query($con, $sql_delete);
	echo $sql_delete;					   
	
	if ($table == 'podcast_users')
	{
	$sql_select_templates = "SELECT * FROM ".DB_PREFIX."episode_templates";
			$sql_select_templates_result = mysqli_query($con, $sql_select_templates);
			while($sql_select_templates_row = mysqli_fetch_assoc($sql_select_templates_result))
			{
				$array = explode(',', $sql_select_templates_row['USERS']);
				$array = array_diff($array, [$id]);
				$array_new = implode(',', $array);
				
				$sql_update_template = "UPDATE ".DB_PREFIX."episode_templates SET USERS = '".$array_new."' WHERE ID = ".$sql_select_templates_row['ID'];
				mysqli_query($con, $sql_update_template);
				echo $sql_update_template;
			}
	}
		
	exit;
}

//Neuer Benutzer: Login-Namen prüfen
if(isset($_GET['check_new_user'])) {
	$username = $_POST['username'];
	$query = "SELECT * FROM ".DB_PREFIX."users WHERE USERNAME = '".$username."'";
	$user_count = mysqli_num_rows(mysqli_query($con,$query));
	if( ($user_count>0 ) || (empty($username)) )
		{
			echo "<span class='input-group-addon status-not-available'><i style='color: red;' class='fa-fw fas fa-times'></i></span>";
			echo "<script>
			$(document).ready(function(){
				if($('#User_add').val().length > 0)
					{
						$('#tool_user').tooltip('enable');
						$('#tool_user').tooltip('show');
					}
				$(\"#add_user\").attr(\"disabled\", true);
			});
			</script>";
		}
	else
		{
			echo "<span class='input-group-addon status-available'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
			echo "<script>
			$(document).ready(function(){
				if($(\"#add_user\").attr(\"disabled\"))
					{
						$('.tooltip').tooltip('hide');
						$('#tool_user').tooltip('disable')
					}
			});
			</script>";	  
		}
	exit;
}  

//Neuer Podcast: Podcast-Kurzbezeichner prüfen
if(isset($_GET['check_podcast_short'])){
	$podcast = $_POST['short'];
	$query = "SELECT * FROM ".DB_PREFIX."podcast WHERE SHORT = '".$podcast."'";
	$podcast_count = mysqli_num_rows(mysqli_query($con,$query));
	if( ($podcast_count>0 ) || (empty($podcast)) ) 
		{
			echo "<span class='input-group-addon status-not-available'><i style='color: red;' class='fa-fw fas fa-times'></i></span>";
			echo "<script>
			$(document).ready(function(){
				if($('#short').val().length > 0)
					{
						$('#tool_new_podcast').tooltip('enable');
						$('#tool_new_podcast').tooltip('show');
					}
				$(\"#add_new_podcast\").attr(\"disabled\", true);
			});
			</script>";
		}
	else
		{
			echo "<span class='input-group-addon status-available'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
			echo "<script>
			$(document).ready(function(){
			if($(\"#add_new_podcast\").attr(\"disabled\"))
				{
					$('.tooltip').tooltip('hide');
					$(\"#add_new_podcast\").removeAttr(\"disabled\");
					$('#tool_new_podcast').tooltip('disable')
				}
			});
			</script>";	  
		}
	exit;
} 

//Episode bearbeiten: Episoden-Nummer prüfen
if(isset($_GET['check_episode'])) {
	$nummer_change = $_POST['nummer_change'];
	$nummer_current = $_POST['nummer_cur'];
	$podcast_current = $_POST['podcast'];
	$query = "SELECT * FROM ".DB_PREFIX."episoden WHERE ID_PODCAST = '".$podcast_current."' AND NUMMER='".$nummer_change."'";
	$user_count = mysqli_num_rows(mysqli_query($con,$query));
	if(($user_count>0 && $nummer_current != $nummer_change ) || (empty($nummer_change) && $nummer_change !== '0') ) 
		{
			echo "<span class='input-group-addon status-not-available'><i style='color: red;' class='fa-fw fas fa-times'></i></span>";
			echo "<script>
			$(document).ready(function(){
				if($('#nummer').val().length > 0)
					{
						$('#tool').tooltip('enable');
						$('#tool').tooltip('show');
					}
				$(\"#button_save_episode\").attr(\"disabled\", true);
			});
			</script>";
		}
	else
		{
			echo "<span class='input-group-addon status-available'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
			echo "<script>
			$(document).ready(function(){
				if($(\"#button_save_episode\").attr(\"disabled\"))
					{
						$('.tooltip').tooltip('hide');
						$(\"#button_save_episode\").removeAttr(\"disabled\");
						$('#tool').tooltip('disable')
					}
			});
			</script>";	  
		}
	exit;
}  

//Podcast bearbeiten: Podcast-Kurzbezeichner prüfen
if(isset($_GET['check_edit_podcast_short'])){
	$podcast_edit = $_POST['short_edit'];
	$podcast_cur = $_POST['short_cur'];
	$query_pc_edit = "SELECT * FROM ".DB_PREFIX."podcast WHERE SHORT = '".$podcast_edit."'";
	$pc_edit_check = mysqli_num_rows(mysqli_query($con,$query_pc_edit));
	if( ($pc_edit_check>0  && $podcast_edit != $podcast_cur ) || (empty($podcast_edit)) ) 
		{
			echo "<span class='input-group-addon status-not-available'><i style='color: red;' class='fa-fw fas fa-times'></i></span>";
			echo "<script>
			$(document).ready(function(){
				if($('#short_edit').val().length > 0)
					{
						$('#tool_podcast_edit').tooltip('enable');
						$('#tool_podcast_edit').tooltip('show');
					}
				$(\"#update_podcast\").attr(\"disabled\", true);
			});
			</script>";
		}
	else
		{
			echo "<span class='input-group-addon status-available'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
			echo "<script>
			$(document).ready(function(){
				if($(\"#add_new_podcast\").attr(\"disabled\"))
				{
					$('.tooltip').tooltip('hide');
					$(\"#update_podcast\").removeAttr(\"disabled\");
					$('#tool_podcast_edit').tooltip('disable')
				}
			});
			</script>";	  
		}
	exit;
} 

//Benutzer bearbeiten: Login-Namen prüfen
if(isset($_GET['check_edit_user_short'])) {
	$username_edit = $_POST['username_edit'];
	$username_cur = $_POST['username_cur'];
	$query_user_edit = "SELECT * FROM ".DB_PREFIX."users WHERE USERNAME = '".$username_edit."'";
	$user_edit_check = mysqli_num_rows(mysqli_query($con,$query_user_edit));
	if( ($user_edit_check>0  && $username_edit != $username_cur ) || (empty($username_edit)) ) 
		{
			echo "<span class='input-group-addon status-not-available'><i style='color: red;' class='fa-fw fas fa-times'></i></span>";
			echo "<script>
			$(document).ready(function(){
				if($('#username_edit').val().length > 0)
					{
						$('#tool_user_edit').tooltip('enable');
						$('#tool_user_edit').tooltip('show');
					}
				$(\"#button_save_user\").attr(\"disabled\", true);
			});
			</script>";
		}
	else
		{
			echo "<span class='input-group-addon status-available'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
			echo "<script>
			$(document).ready(function(){
			if($(\"#button_save_user\").attr(\"disabled\"))
				{
					$('.tooltip').tooltip('hide');
					$(\"#button_save_user\").removeAttr(\"disabled\");
					$('#tool_user_edit').tooltip('disable')
				}
			});
			</script>";	  
		}
	exit;
} 

//Neue Episode bearbeiten: Episoden-Nummer prüfen
if(isset($_GET['check_new_episode'])) {
	$nummer_change_new = $_POST['nummer'];
	$podcast_current_new = $_POST['podcast'];
	$query_new = "SELECT * FROM ".DB_PREFIX."episoden WHERE ID_PODCAST = '".$podcast_current_new."' AND NUMMER='".$nummer_change_new."'";
	$user_count_new = mysqli_num_rows(mysqli_query($con,$query_new));
	if($user_count_new>0 || (empty($nummer_change_new) && $nummer_change_new !== '0'))  
		{
			echo "<span class='input-group-addon status-not-available'><i style='color: red;' class='fa-fw fas fa-times'></i></span>";
			echo "<script>
			$(document).ready(function(){
				if($('#nummer_add_neu').val().length > 0)
					{
						$('#tool_new').tooltip('enable');
						$('#tool_new').tooltip('show');
					}
				$(\"#add_new_episode\").attr(\"disabled\", true);
			});
			</script>";
		}
	else
	{
		echo"<span class='input-group-addon status-available'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
		echo "<script>
		$(document).ready(function(){
			if($(\"#add_new_episode\").attr(\"disabled\"))
				{
					$('.tooltip').tooltip('hide');
					$(\"#add_new_episode\").removeAttr(\"disabled\");
					$('#tool_new').tooltip('disable')
				}
		});
		</script>"; 
	}
	exit;
}  

?>