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

//Neue Beuträge erfassen: Kategorie ausgewählt --> Auswahl Themen bzw. Formular zur Erfassung von Beiträgen
if(isset($_GET['select_category'])){
	$allow_topics = $_POST['allow_topics'];
	$cat_id = $_POST['cat_id'];

	if($allow_topics == 1) 
		{
			echo "<select id='select_depend' class='form-control'>";
				echo "<option id='option2' selected disabled>Wählen</option>";
				echo "<option id='option2' value_option='new_topic'>Neues Thema</option>";
				echo "<option id='option2' disabled>Bestehende Themen</option>";
				$sql_modal_cat_topic = "SELECT * FROM ".DB_PREFIX."view_topics WHERE TOPICS_ID_CATEGORY =".$cat_id;
				$sql_modal_cat_topic_result = mysqli_query($con, $sql_modal_cat_topic);
				while($sql_modal_cat_topic_row = mysqli_fetch_assoc($sql_modal_cat_topic_result))
					{
						if($sql_modal_cat_topic_row['CATEGORIES_VISIBLE'] == 0 && $sql_modal_cat_topic_row['TOPICS_ID_USER'] != $_SESSION['userid'] && $sql_modal_cat_topic_row['TOPICS_DONE'] == 0)
							{
							}
						else
							{
								echo "<option id='option2' value_option='".$sql_modal_cat_topic_row['TOPICS_ID']."'>".$sql_modal_cat_topic_row['TOPICS_DESCR']."</option>";					
							}
					}
			echo "<select>";
			echo "<script>
			$(\"#select_depend\").on('change', function(){
				var select_depend_value = $('option:selected', this).attr('value_option');
				jQuery.ajax({
					url: \"../inc/check.php?select_topic=1\",
					data: {\"select_depend_value\":select_depend_value},
					type: \"POST\",
					success:function(data)
						{
							$(\"#select_depend_2\").html(data);
							console.log(data);
						},
					error:function ()
						{
						}
					});
			});								   
			</script>"; 

		}
	else
		{
			echo "<label for='link_topics_title'>Link-Titel</label>";
			echo "<input type='text' class='form-control' id='link_title'>";	 
			echo "<label for='link_url'>URL</label>";
			echo "<input type='text' class='form-control' id='link_url'>";
			echo "<hr>";
			echo "<button type='button' id='absenden_link_new' name='absenden_link_new' class='btn btn-outline-secondary'><i class='fas fa-save'></i> Speichern</button>";
			echo "<script>
			$(\"#absenden_link_new\").on('click', function(){
				var category = $(\"#modal1\").val();
				var descr = $(\"#link_title\").val();
				var url = $(\"#link_url\").val();
				if(descr == '' && url == '')
					{
						$.gritter.add({
							title: 'Unvollständige Angaben!',
							text: 'Bitte geben Sie entweder einen Titel oder eine URL ein!',
							image: 'images/delete.png',
							time: '1000'
						});		
					return;
					}
				jQuery.ajax({
					url: \"../inc/insert.php?add_link=1\",
					data: {	\"descr\":descr,
							\"category\":category,
							\"url\":url
					},
					type: \"POST\",
					success:function(data)
						{
							$(\"#newentry\").modal('hide');
							location.reload();
							console.log(data);
						},
					error:function ()
						{
						}
					});

			});								   
			</script>"; 
		}
	exit;
} 

//Neue Beuträge erfassen: Thema ausgewählt --> Neues Thema bzw Erfassung von Beiträgen zu einem bestehenden Thema
if(isset($_GET['select_topic'])) {
	
	$value_dep = $_POST['select_depend_value'];

	if($value_dep == 'new_topic') 
		{
			echo "<label for='topic_new_title'>Titel des Themas:</label>";
			echo "<input required type='text' class='form-control' id='topic_new_title'>";
			echo "<hr>";
			echo "<label for='topic_new_link_title'>Link-Titel</label>";
			echo "<input type='text' class='form-control' name='topic_new_link_title' id='topic_new_link_title'>";	 
			echo "<label for='topic_new_link_url'>URL</label>";
			echo "<input type='text' class='form-control' name='topic_new_link_url' id='topic_new_link_url'>";
			echo "<hr>";
			echo "<button type='button' id='topic_new_send' name='topic_new_send' class='btn btn-outline-secondary'><i class='fas fa-save'></i> Speichern</button>";
			echo "<script>
			$(\"#topic_new_send\").on('click', function(){
				var category = $(\"#modal1\").val();
				var descr = $(\"#topic_new_title\").val();
				var link_descr = $(\"#topic_new_link_title\").val();
				var link_url = $(\"#topic_new_link_url\").val();
				if(descr == '')
					{
						$.gritter.add({
							title: 'Unvollständige Angaben!',
							text: 'Bitte geben Sie einen Titel ein!',
							image: 'images/delete.png',
							time: '1000'
						});		
					return;
					}
				jQuery.ajax({
					url: \"../inc/insert.php?add_topic=1\",
					data: {	\"descr\":descr,
							\"category\":category,
							\"link_descr\":link_descr,
							\"link_url\":link_url
						},
					type: \"POST\",
					success:function(data)
						{
							$(\"#newentry\").modal('hide');
							location.reload();
							console.log(data);
						},
					error:function ()
						{
						}
					});

			});								   
			</script>"; 

		}
	else
		{
			echo "<label for='link_topics_title'>Link-Titel</label>";
			echo "<input type='text' class='form-control' id='link_topics_title'>";	 
			echo "<label for='link_topics_url'>URL</label>";
			echo "<input type='text' class='form-control' id='link_topics_url'>";
			echo "<hr>";
			echo "<button type='button' id='topic_new_link_send' name='topic_new_link_send' class='btn btn-outline-secondary'><i class='fas fa-save'></i> Speichern</button>";
			echo "<script>
			$(\"#topic_new_link_send\").on('click', function(){
				var category = $(\"#modal1\").val();
				var topic = $('option:selected', \"#select_depend\").attr('value_option');
				var descr = $(\"#link_topics_title\").val();
				var url = $(\"#link_topics_url\").val();
				if(descr == '' && url == '')
					{
						$.gritter.add({
							title: 'Unvollständige Angaben!',
							text: 'Bitte geben Sie entweder einen Titel oder eine URL ein!',
							image: 'images/delete.png',
							time: '1000'
							});		
						return;	
					}
				jQuery.ajax({
					url: \"../inc/insert.php?add_topiclink=1\",
					data: {	\"category\":category,
							\"topic\":topic,
							\"descr\":descr,
							\"url\":url
						},
					type: \"POST\",
					success:function(data)
						{
							$(\"#newentry\").modal('hide');
							location.reload();
							console.log(data);
						},
					error:function ()
						{
						}
					});
			});
			</script>"; 
		}
	exit;
} 
?>