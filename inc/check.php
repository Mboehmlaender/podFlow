<?php 
include('config.php');
include('../config/dbconnect.php');
session_start();
if(!isset($_SESSION['userid']))
	{
		header('Location: ../login.php');
	}		


if(isset($_POST)){

	//Kategeorien/Benutzer einer Episode hinzufügen 
	if(isset($_GET['add_episode_category'])){
		$id = $_POST['cat_id'];
		$episode = $_POST['next_episode'];
		$sql_add = "INSERT INTO ".DB_PREFIX."episode_categories (ID_EPISODE, ID_CATEGORY) VALUES (".$episode.", ".$id.")";
		$sql_add_result = mysqli_query($con, $sql_add);
		echo $sql_add;
		exit;
	}

	if(isset($_GET['check_categories_next'])){
		$current_episode = mysqli_real_escape_string($con,$_POST['episode_id_current']);
		$next_episode = mysqli_real_escape_string($con,$_POST['id_next']);	

		$array_next = array();
		$query_cat_next = "SELECT * FROM ".DB_PREFIX."view_episode_categories WHERE ID_EPISODE = '".$next_episode."'";
		$query_cat_next_result = mysqli_query($con, $query_cat_next);
		while($query_cat_next_row = mysqli_fetch_assoc($query_cat_next_result))
		{
			array_push($array_next, $query_cat_next_row['ID_CATEGORY']);	
		}
		
 		$query_cat_current = "SELECT DISTINCT ".DB_PREFIX."categories.DESCR, ".DB_PREFIX."links.ID_CATEGORY FROM ".DB_PREFIX."links JOIN ".DB_PREFIX."categories ON ".DB_PREFIX."categories.ID = ".DB_PREFIX."links.ID_CATEGORY WHERE (".DB_PREFIX."links.DONE IS NULL OR ".DB_PREFIX."links.DONE = '' ) AND ".DB_PREFIX."links.ID_EPISODE = '".$current_episode."' UNION SELECT DISTINCT ".DB_PREFIX."categories.DESCR, ".DB_PREFIX."topics.ID_CATEGORY FROM ".DB_PREFIX."topics JOIN ".DB_PREFIX."categories ON ".DB_PREFIX."categories.ID = ".DB_PREFIX."topics.ID_CATEGORY WHERE (".DB_PREFIX."topics.DONE IS NULL OR ".DB_PREFIX."topics.DONE = '' ) AND ".DB_PREFIX."topics.ID_EPISODE = '".$current_episode."'";
		$query_cat_next_result = mysqli_query($con, $query_cat_current);
		echo "<hr>";
		echo "<div style='color: red; font-weight: bold; margin: 10px 0px'>Folgende Kategorien sind noch nicht in der Zielepisode angelegt:</div>";
				echo "<script>
					
					if($(\".create_category\").length !== 0)
					{
						$(\"#warning\").show();
						$(\"#move_button\").attr('disabled', true);
					}
					$('#move_eitherway').click(function(){
						if (this.checked) 
						{
							$(\"#move_button\").attr('disabled', false);
						}
						
						else
						{
							$(\"#move_button\").attr('disabled', true);
						}
					}) 
					$(\".create_category\").on('click', function(){
						var cat_id = $(this).attr('cat_id');
						var next_episode = $(this).attr('next_episode');
									jQuery.ajax({
										url: \"inc/check.php?add_episode_category=1\",
										data: {	cat_id:cat_id,
												next_episode:next_episode
											},
										type: \"POST\",
										success:function(data)
											{
											},
										error:function ()
											{
											}
										});
						$(\"#cat_missing_\"+cat_id).remove();
						if($(\".create_category\").length == 0)
						{
							$(\"#warning\").hide();
							$(\"#move_button\").attr('disabled', false);
							
						}
					});

				</script>";
		while($query_cat_current_row = mysqli_fetch_assoc($query_cat_next_result))
		{
			if(!in_array($query_cat_current_row['ID_CATEGORY'], $array_next))
			{
				echo "<div id='cat_missing_".$query_cat_current_row['ID_CATEGORY']."'> <p>\"".$query_cat_current_row['DESCR']."\"<br><div class='btn btn-outline-info btn-block btn-sm create_category' next_episode='".$next_episode."' cat_id='".$query_cat_current_row['ID_CATEGORY']."'>Jetzt anlegen</div></p></div>";
			}
		}
		echo "<div style='color: red; font-weight: bold; margin: 10px 0px'>Beiträge und Themen, die übernommen werden, zu denen keine Kategorie existiert, werden nicht angezeigt, so lange die Kategorie nicht in der Folge aktiviert wurde!</div>";
		  echo "<div class='form-check'>";
			echo "<input type='checkbox' class='form-check-input' id='move_eitherway'>";
			echo "<label class='form-check-label' for='exampleCheck1'>Trotzdem übernehmen</label>";
		  echo "</div>";
		echo "<hr>";
		
		
		
	
	}
	//Anzeigenamen prüfen
	if(isset($_GET['check_edit_user_short'])){
		$user_show_name_edit = mysqli_real_escape_string($con,$_POST['name_show_edit']);
		$user_show_name_cur = mysqli_real_escape_string($con,$_POST['name_show_cur']);
		$query_user_edit = "SELECT * FROM ".DB_PREFIX."users WHERE NAME_SHOW = '".$user_show_name_edit."'";
		$user_edit_check = mysqli_num_rows(mysqli_query($con,$query_user_edit));
		if( ($user_edit_check>0  && $user_show_name_edit != $user_show_name_cur )) 
			{
				echo "<span class='input-group-addon status-not-available'><i style='color: red;' class='fa-fw fas fa-times'></i></span>";
				echo "<script>
					$(document).ready(function(){
						if($('#Username_Show').val().length > 0)
							{
								$('#Tool_Username_Show').tooltip('enable');
								$('#Tool_Username_Show').tooltip('show');
							}
						$(\"#save_profile\").attr(\"disabled\", true);
					});
				</script>";
			}
		else
			{
				echo "<span class='input-group-addon status-available'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
				echo "<script>
					$(document).ready(function(){
					if($(\"#save_profile\").attr(\"disabled\"))
						{
							$('.tooltip').tooltip('hide');
							$(\"#save_profile\").removeAttr(\"disabled\");
							$('#Tool_Username_Show').tooltip('disable')
						}
					});
				</script>";	  
			}
			exit;
	} 

	//Neuer Beitrag --> Kategorie wählen
	if(isset($_GET['select_category'])){
		$cat_id =  mysqli_real_escape_string($con,$_POST['cat_id']);
		$max_entries =  mysqli_real_escape_string($con,$_POST['max_entries']);

				$sql_modal_cat_topic = "SELECT * FROM ".DB_PREFIX."view_topics WHERE TOPICS_ID_CATEGORY =".$cat_id;
				$sql_modal_cat_topic_result = mysqli_query($con, $sql_modal_cat_topic);
				
				$cur_entries_links = linksincat($_SESSION['cur_episode'], $cat_id); 
				if($cur_entries_links >= $max_entries && $max_entries > 0)
					{
						if(mysqli_num_rows($sql_modal_cat_topic_result))
						{
							echo "<select id='select_depend_menu' class='form-control'>";
								echo "<option id='option2' disabled selected>Bestehende Themen</option>";
								while($sql_modal_cat_topic_row = mysqli_fetch_assoc($sql_modal_cat_topic_result))
									{
										if($sql_modal_cat_topic_row['CATEGORIES_VISIBLE'] == 0 && $sql_modal_cat_topic_row['TOPICS_ID_USER'] != $_SESSION['userid'] && $sql_modal_cat_topic_row['TOPICS_DONE'] == 0)
											{}
										else
											{
												echo "<option id='option2' sel_cat_id ='".$cat_id."' max_entries= '".$max_entries."' value_option='".$sql_modal_cat_topic_row['TOPICS_ID']."'>".$sql_modal_cat_topic_row['TOPICS_DESCR']."</option>";					
											}
									}
							echo "</select>";							
						}
						echo "Die maximale Anzahl an neuen Beiträgen in dieser Kategorie ist bereits erreicht!";
					}
					else{
				echo "<select id='select_depend_menu' class='form-control'>";
					echo "<option id='option2' selected disabled>Bitte wählen</option>";
					echo "<option id='option2' sel_cat_id ='".$cat_id."' max_entries= '".$max_entries."' value_option='new_link'>Neuer Beitrag</option>";
					echo "<option id='option2' sel_cat_id ='".$cat_id."' max_entries= '".$max_entries."' value_option='new_topic'>Neues Thema</option>";
					echo "<option id='option2' disabled>Bestehende Themen</option>";
					while($sql_modal_cat_topic_row = mysqli_fetch_assoc($sql_modal_cat_topic_result))
						{
							if($sql_modal_cat_topic_row['CATEGORIES_VISIBLE'] == 0 && $sql_modal_cat_topic_row['TOPICS_ID_USER'] != $_SESSION['userid'] && $sql_modal_cat_topic_row['TOPICS_DONE'] == 0)
								{}
							else
								{
									echo "<option id='option2' sel_cat_id ='".$cat_id."' max_entries= '".$max_entries."' value_option='".$sql_modal_cat_topic_row['TOPICS_ID']."'>".$sql_modal_cat_topic_row['TOPICS_DESCR']."</option>";					
								}
						}
					}
					echo "</select>";
								echo "<div class='form-group' id='select_depend_2'>";
				echo "</div>";
				echo "<script>
					if($(\".savebtn\")[0])
						{
							$(\".savebtn\").attr('disabled', true);
						}
					$(\"#select_depend_menu\").on('change', function(){
						var select_depend_value = $('option:selected', this).attr('value_option');
						var max_entries = $('option:selected', this).attr('max_entries');
						var sel_cat_id = $('option:selected', this).attr('sel_cat_id');
						jQuery.ajax({
							url: \"inc/check.php?select_topic=1\",
							data: {	\"select_depend_value\":select_depend_value,
									\"max_entries\":max_entries,
									\"sel_cat_id\":sel_cat_id
									},
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
/* 				echo "<script>
					var button = \"<button type='button' id='absenden_link_new' name='absenden_link_new' class='btn btn-outline-secondary savebtn'>Speichern</button>\";

					$(\"#button_footer\").html(button);
					$(\".savebtn\").removeAttr('disabled');
					$(\"#absenden_link_new\").on('click', function(){
					var descr = $(\"#link_title\").val();
					var url = $(\"#link_url\").val();
					var category = $('option:selected', \"#select_depend_menu\").attr('sel_cat_id');
					if(descr == '' && url == '')
						{
							$.gritter.add({
								title: 'Unvollständige Angaben!',
								text: 'Bitte gib entweder einen Beitragstitel oder eine URL ein!',
								image: 'images/delete.png',
								time: '1000'
							});		
							return;
						}
						
					jQuery.ajax({
						url: \"../inc/insert.php?add_link=1\",
						data: { \"descr\":descr,
								\"category\":category,
								\"url\":url
								},
						type: \"POST\",
						success:function(data)
							{
								console.log(data);
								location.reload();
							},
						error:function ()
							{
							}
						});
					});								   
				</script>";  */
			
			return;
	} 

	//Neuer Beitrag --> Kategorie wählen --> Thema wählen
	if(isset($_GET['select_topic'])){
		$value_dep =  mysqli_real_escape_string($con,$_POST['select_depend_value']);
/* 		$max_entries =  mysqli_real_escape_string($con,$_POST['max_entries']);
		$cur_entries = linksincat('topics', $_SESSION['cur_episode'], $sel_cat_id); 
 */		$sel_cat_id =  mysqli_real_escape_string($con,$_POST['sel_cat_id']);

		if($value_dep == 'new_topic')
			{
/* 				if($cur_entries >= $max_entries && $max_entries > 0)
					{
						echo "Die maximale Anzahl an Themen in dieser Kategorie ist bereits erreicht!";
						return;
					}  */
				echo "<hr>";								
				echo "<div class='form-group'>";
					echo "<label for='topic_new_title'>Titel des Themas</label>";
					echo "<input required type='text' class='form-control' id='topic_new_title'>";
				echo "</div>";
				echo "<hr>";
				echo "<div class='form-group'>";
					echo "<label for='topic_new_link_title'>Titel des Beitrags</label>";
					echo "<input type='text' class='form-control' name='topic_new_link_title' id='topic_new_link_title'>";	 
				echo "</div>";
				echo "<div class='form-group'>";
					echo "<label for='topic_new_link_url'>URL</label>";
					echo "<input type='text' class='form-control' name='topic_new_link_url' id='topic_new_link_url'>";
				echo "</div>";
				echo "<script>
					var button = \"<button type='button' id='topic_new_send' name='topic_new_send' class='btn btn-outline-secondary savebtn'>Speichern</button>\";

					$(\"#button_footer\").html(button);
					$(\"#topic_new_send\").on('click', function(){
					var category = $('option:selected', \"#select_depend_menu\").attr('sel_cat_id');
					var descr = $(\"#topic_new_title\").val();
					var link_descr = $(\"#topic_new_link_title\").val();
					var link_url = $(\"#topic_new_link_url\").val();

					if(descr == '')
						{
							$.gritter.add({
								title: 'Unvollständige Angaben!',
								text: 'Bitte gib einen Thementitel ein!',
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
								console.log(data);
								location.reload();

							},
						error:function ()
							{
							}
						});

					});								   
				</script>"; 

			}
		else if($value_dep == 'new_link')
			{
/* 				if($cur_entries >= $max_entries && $max_entries > 0)
					{
						echo "Die maximale Anzahl an Beiträgen in dieser Kategorie ist bereits erreicht!";
						return;
					} */
				echo "<hr>";								
				echo "<div class='form-group'>";
					echo "<label for='link_topics_title'>Titel des Beitrags</label>";
					echo "<input type='text' class='form-control' id='link_title'>";	 
				echo "</div>";
				echo "<div class='form-group'>";
					echo "<label for='link_url'>URL</label>";
					echo "<input type='text' class='form-control' id='link_url'>";
				echo "</div>";
				echo "<script>
					var button = \"<button type='button' id='absenden_link_new' name='absenden_link_new' class='btn btn-outline-secondary savebtn'>Speichern</button>\";

					$(\"#button_footer\").html(button);
					$(\".savebtn\").removeAttr('disabled');
					$(\"#absenden_link_new\").on('click', function(){
				
					var descr = $(\"#link_title\").val();
					var url = $(\"#link_url\").val();
					var category = $('option:selected', \"#select_depend_menu\").attr('sel_cat_id');
					if(descr == '' && url == '')
						{
							$.gritter.add({
								title: 'Unvollständige Angaben!',
								text: 'Bitte gib entweder einen Beitragstitel oder eine URL ein!',
								image: 'images/delete.png',
								time: '1000'
							});		
							return;
						}
						
					jQuery.ajax({
						url: \"../inc/insert.php?add_link=1\",
						data: { \"descr\":descr,
								\"category\":category,
								\"url\":url
								},
						type: \"POST\",
						success:function(data)
							{
								console.log(data);
								window.location.reload(true);						
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
				echo "<hr>";								
				echo "<div class='form-group'>";
					echo "<label for='link_topics_title'>Titel des Beitrags</label>";
					echo "<input type='text' class='form-control' id='link_topics_title'>";	 
				echo "</div>";
				echo "<div class='form-group'>";
					echo "<label for='link_topics_url'>URL</label>";
					echo "<input type='text' class='form-control' id='link_topics_url'>";
				echo "</div>";
				echo "<script>
					var button = \"<button type='button' id='topic_new_link_send' name='topic_new_link_send' class='btn btn-outline-secondary savebtn'>Speichern</button>\";
					
					$(\"#button_footer\").html(button);
					$(\"#topic_new_link_send\").on('click', function(){
					var category = $('option:selected', \"#select_depend_menu\").attr('sel_cat_id');
					var topic = $('option:selected', \"#select_depend_menu\").attr('value_option');
					var descr = $(\"#link_topics_title\").val();
					var url = $(\"#link_topics_url\").val();
					
					if(descr == '' && url == '')
						{
							$.gritter.add({
							title: 'Unvollständige Angaben!',
							text: 'Bitte gib entweder einen Beitragstitel oder eine URL ein!',
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
								console.log(data);
								window.location.reload(true);						

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
}
?>