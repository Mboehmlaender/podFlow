<?php

include('inc/config.php');
$now = date("Y-m-d G:i:s");
$today = date("Y-m-d");

//Session starten
function session(){
	session_start();
	if(!isset($_SESSION['userid']))
		{
			header('Location: login.php');
		}		
}

//HTML-Header
function head(){

	echo "<!DOCTYPE html>";
	echo "<html lang='de'>";
	echo "<head>";

		echo "<meta charset='utf-8'>";
		echo "<title>podflow!</title>";
		echo "<meta name='description' content=''>";
		echo "<meta name='keywords' content=''>";
		echo "<meta name='author' content=''>";
		echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
		echo "<link rel='icon' type='image/png' href='images/podflow_Logo_v2c.png' />";

		echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";

		echo "<link href='//fonts.googleapis.com/css?family=Raleway:400,300,600' rel='stylesheet' type='text/css'>";
		echo "<link href='//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css' rel='stylesheet'/>";
		echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>";
		echo "<link rel='stylesheet' href='css/main.css'>";
		echo "<link rel='stylesheet' href='css/simplebar.css'>";
		echo "<link rel='stylesheet' href='css/tooltipster.bundle.min.css'>";
		echo "<link rel='stylesheet' href='css/tooltipster-sideTip-shadow.min.css'>";
		echo "<link rel='stylesheet' href='css/custom.css'>";
		echo "<link rel='stylesheet' href='css/jquery.gritter.css'>";
		echo "<link rel='stylesheet' href='css/jquery-confirm.min.css'>";
		echo "<script src='//cdn.ckeditor.com/4.11.1/basic/ckeditor.js'></script>";
		echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js' integrity='sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49' crossorigin='anonymous'></script>";
		echo "<script src='https://code.jquery.com/jquery-3.3.1.min.js' integrity='sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=' crossorigin='anonymous'></script>";
		echo "<script src='js/jquery.gritter.min.js'></script>";
		echo "<script src='https://code.jquery.com/ui/1.12.0/jquery-ui.min.js'></script>";
		echo "<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js' integrity='sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy' crossorigin='anonymous'></script>";
		echo "<script src='js/js.cookie.min.js'></script>";
		echo "<script src='js/clipboard.min.js'></script>";
		echo "<script src='js/jquery-confirm.min.js'></script>";
		echo "<script src='js/jquery.ui.touch-punch.min.js'></script>";
		echo "<script src='js/bootstrap-editable.min.js'></script>";
		echo "<script src='js/simplebar.js'></script>";
		echo "<script src='js/tooltipster.bundle.min.js'></script>";
		echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js'></script>";
		echo "<link rel='stylesheet' href='css/all.min.css'>";	
		echo "<noscript>
		  <style>
			[data-simplebar] {
			  overflow: auto;
			}
		  </style>
		</noscript>";
		echo "<script>
		
			$(document).ready(function() {
				$('.tooltipster').tooltipster({
					theme: 'tooltipster-shadow',
					side: 'top',
					minWidth: 300,
					interactive: 'true',
					trigger: 'custom',
					triggerOpen: {
						mouseenter: true,
						touchstart: true,
						scroll: false
					},
					triggerClose: {
						click: true,
						mouseleave: true,
						scroll: false
					}			
	});
			});
		
			$(function () {
				$('[data-toggle=\"popover\"]').popover({
					trigger: 'focus'
					})
				})
		
		</script>";

		
	echo "</head>";
}

//Automatische Auswahl, falls Benutzer nur einem Podcast zugewiesen wurde
function select_podcast(){
	
	if(empty($_SESSION['podcast']))
		{
			last_podcast();
			/* global $con;
			if(getPermission($_SESSION['userid']) < 2)
				{
					$login_podcast_select = "SELECT PODCASTS_USERS_ID_PODCAST AS ID FROM ".DB_PREFIX."view_podcasts_users WHERE PODCASTS_USERS_ID_USER = ".$_SESSION['userid'];
				}
			else
				{
					$login_podcast_select = "SELECT ID FROM ".DB_PREFIX."podcast";
				}
			$login_podcast_result = mysqli_query($con, $login_podcast_select);
			if(mysqli_num_rows($login_podcast_result) == 1)
				{
					$id = mysqli_fetch_assoc($login_podcast_result);
					$_SESSION['podcast'] = $id['ID'];
					return;
				} */
		}
	if((!empty($_SESSION['podcast'])) && (empty($_SESSION['cur_episode'])))
		{
			last_episode();
			
		return;
		}
}

//Obere Navigation
function navbar_top(){
	$podcast_color = getSetting('PC_COLOR',$_SESSION['podcast']);

	echo "<header class='app-header' style='padding-right: 0px;'><a class='app-header__logo' href='index.php'></a>";
		echo "<a class='app-sidebar__toggle' href='#' data-toggle='sidebar' aria-label='Hide Sidebar'></a>";
		echo "<ul class='app-nav' id='podcast_menu'>";
			if(!empty($_SESSION['cur_episode']))
				{
					if ((getPermission($_SESSION['userid']) > 1 && !userInEpisode($_SESSION['userid'], $_SESSION['cur_episode'])) || episodeclosed($_SESSION['cur_episode']) == 1)
						{
						}
					else
						{
							echo "<li class='dropdown'><div class='app-nav__item add_entry'  change_value='".$_SESSION['cur_episode']."' style='cursor: pointer'><i class='fas fa-plus-circle fa-fw fa-lg'></i></div></li>";
						}
				}
			echo "<li class='dropdown'><a class='app-nav__item' href='#' data-toggle='dropdown' aria-label='Open Profile Menu'><i class='fa fas fa-exchange-alt fa-lg'></i></a>";
				echo "<ul class='dropdown-menu settings-menu drop down-menu-right' x-placement='bottom-end' style='position: absolute; transform: translate3d(-117px, 50px, 0px); top: 0px; left: 0px; will-change: transform;'>";
					echo "<li><div class='dropdown-item change' change_value='podcast' style='padding-left: 5px; cursor: pointer'><i class='fas fa-podcast fa-fw fa-lg'></i> Podcast wählen</div></li>";
					if(!empty($_SESSION['podcast']))
						{
							echo "<li><div class='dropdown-item change' change_value='episode' style='padding-left: 5px; cursor: pointer'><i class='fas fa-microphone fa-fw fa-lg'></i> Episode wählen</div></li>";
						}
				echo "</ul>";
			echo "</li>";
			echo "<li class='dropdown'>";
				echo "<a class='app-nav__item' href='logout.php'><i class='fas fa-power-off fa-lg'></i></a>";
			echo "</li>";
			echo "<li class='podcast-name-top' id='podcast-name-top' data-toggle='tooltip' data-placement='bottom' title='Aktuelle Auswahl'>";
				echo "<div style='border-color: ".$podcast_color."' id='menu_pc_name' class='podcast-info-menu'>";
					echo "<i style='color:black; margin-right: 5px' class='fa fas fa-podcast topbarpclogo'></i>".getSetting('PC_PREFIX',$_SESSION['podcast']);
					if(!empty($_SESSION['cur_episode']))
						{
							echo str_pad(episode_nummer($_SESSION['cur_episode']),3,'0', STR_PAD_LEFT);
						}
				echo "</div>";  
			echo "</li>";
		echo "</ul>";
	echo "</header>";
}

//Dashboard
function dashboard(){	
	$user_level = getPermission($_SESSION['userid']);
		if((empty($_SESSION['podcast']) || empty($_SESSION['cur_episode'])) && $user_level > 1)
		{
			$col = "col-md-6";
		}
	else if ((empty($_SESSION['podcast']) || empty($_SESSION['cur_episode'])) && $user_level == 1)
		{
			$col = "col-md-12";
		}
	else
		{
			$col = "col-xl-4";
		}
		
	if(!empty($_SESSION['cur_episode']))
		{
			echo "<div class='col-xl-4'>";
				echo "<a style='text-decoration: none' href='episode.php'><div class='widget-small primary coloured-icon'><i class='icon fas fa-ellipsis-v fa-3x fa-fw'></i>";
					echo "<div class='info'>";
						echo "<h4  style='text-transform: none'>Timeline</h4>";
					echo "</div>";
				echo "</div></a>";
			echo "</div>";
			if(getPermission($_SESSION['userid']) > 1)
				{	
					echo "<div class='col-xl-4'>";
						echo "<a style='text-decoration: none' href='export.php'><div class='widget-small primary coloured-icon'><i class='icon fas fas fa-upload fa-3x fa-fw'></i>";
							echo "<div class='info'>";
								echo "<h4  style='text-transform: none'>Export</h4>";
							echo "</div>";
						echo "</div></a>";
					echo "</div>";
				}
		}
	echo "<div class='".$col."'>";
		echo "<a style='text-decoration: none' href='links.php'><div class='widget-small primary coloured-icon'><i class='icon fas fa-bookmark fa-3x fa-fw'></i>";
			echo "<div class='info'>";
				echo "<h4  style='text-transform: none'>Meine Beiträge</h4>";
			echo "</div>";
		echo "</div></a>";
	echo "</div>";
	echo "<div class='".$col."'>";
		echo "<a style='text-decoration: none' href='profile.php'><div class='widget-small primary coloured-icon'><i class='icon fas fa-user fa-3x fa-fw'></i>";
			echo "<div class='info'>";
				echo "<h4  style='text-transform: none'>Mein Profil</h4>";
			echo "</div>";
		echo "</div></a>";
	echo "</div>";
	if($user_level > 1)
		{
			echo "<div class='".$col."'>";
				echo "<a style='text-decoration: none' href='admin/index.php'><div class='widget-small primary coloured-icon'><i class='icon fas fa-chevron-circle-right fa-3x fa-fw'></i>";
					echo "<div class='info'>";
						echo "<h4  style='text-transform: none'>Backend</h4>";
					echo "</div>";
				echo "</div></a>";
			echo "</div>";
		}
}

//Sidebar-Navigation
function sidebar(){
	echo "<div class='app-sidebar__overlay' data-toggle='sidebar'></div>";
	echo "<aside class='app-sidebar'>";
		echo "<div class='app-sidebar__user'>";
			/* 	 echo "<img class='app-sidebar__user-avatar' src='";
			echo userinfos($_SESSION['userid'], 'AVATAR');
			echo "' alt='User Image'>"; */
			echo "<div id='app-sidebar__user-name'>";
				echo "<p class='app-sidebar__user-name'>";
				if(empty(userinfos($_SESSION['userid'], 'NAME_SHOW')))
					{
						echo userinfos($_SESSION['userid'], 'USERNAME');
					}
				else
					{
						echo userinfos($_SESSION['userid'], 'NAME_SHOW');
					}
				echo "</p>";
			echo "</div>";
		echo "</div>";
		echo "<ul class='app-menu' style='position: relative;'>";
			echo "<li><a class='app-menu__item' id='menu_dash' href='index.php'><i class='app-menu__icon fab fa-fort-awesome'></i><span class='app-menu__label'>Dashboard</span></a></li>";
			if(!empty($_SESSION['cur_episode']))
				{
					echo "<li><a class='app-menu__item' id='menu_episode' href='episode.php'><i class='app-menu__icon fas fa-ellipsis-v'></i><span class='app-menu__label'>Timeline</span></a></li>";
					if(getPermission($_SESSION['userid']) > 1)
						{
							echo "<li><a class='app-menu__item' id='menu_export' href='export.php'><i class='app-menu__icon fas fa-upload'></i><span class='app-menu__label'>Export</span></a></li>";
						}
				}
			echo "<li><a class='app-menu__item' id='menu_links' href='links.php'><i class='app-menu__icon fas fa-bookmark fa-lg'></i><span class='app-menu__label'>Meine Beiträge</span></a></li>";
			echo "<li><a class='app-menu__item' id='menu_profile' href='profile.php'><i class='app-menu__icon fas fa-user'></i><span class='app-menu__label'>Mein Profil</span></a></li>";
			if(getPermission($_SESSION['userid']) > 1)
				{
					echo "<li><a class='app-menu__item' id='menu_backend' href='admin/index.php'><i class='app-menu__icon fas fa-chevron-circle-right'></i><span class='app-menu__label'>Backend</span></a></li>";
				}
		echo "</ul>";
		echo "<ul class='app-menu' style='position: relative;'>";
			echo "<li style='opacity: 0.3'><a class='app-menu__item' href='https://podflow.de' target='_blank'><i class='app-menu__icon fas fa-home fa-lg'></i><span class='app-menu__label'>podflow!</span></a></li>";
			echo "<li style='opacity: 0.3'><a class='app-menu__item' href='https://github.com/Mboehmlaender/podflow' target='_blank'><i class='app-menu__icon fab fa-github fa-lg'></i><span class='app-menu__label'>podflow! bei github</span></a></li>";
			echo "<li style='opacity: 0.3'><a class='app-menu__item' href='https://bugs.podflow.de/' target='_blank'><i class='app-menu__icon fas fa-bug fa-lg'></i><span class='app-menu__label'>Fehler melden</span></a></li>";
		echo "</ul>";
	echo "</aside>";
}

//Eigene Beiträge
function own_entries($userid){
	global $con;
	echo "<div class='row'>";
		echo "<div class='col-12'>";
			echo "<div class='tile'>";
				echo "<div class='row'>";
					echo "<div class='col-12 col-md-6'>";
						echo "<div class='form-group' id='podcast_filter'>";
							
						echo "</div>";
					echo "</div>";
					echo "<div class='col-12 col-md-6'>";
						echo "<div class='form-group' id='episode_filter'>";

						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "<hr>";
				echo "<ul class='topic_links'>";
					$sql_own_entries = "SELECT ".DB_PREFIX."podcast.SHORT, ".DB_PREFIX."links.ID, ".DB_PREFIX."links.ID_PODCAST, ".DB_PREFIX."links.ID_USER, ".DB_PREFIX."links.ID_EPISODE, ".DB_PREFIX."links.ID_CATEGORY, ".DB_PREFIX."links.DESCR, ".DB_PREFIX."links.REIHENF, 0 AS IS_TOPIC, ".DB_PREFIX."links.DONE FROM ".DB_PREFIX."links join ".DB_PREFIX."podcast ON ".DB_PREFIX."podcast.ID = ".DB_PREFIX."links.ID_PODCAST WHERE (".DB_PREFIX."links.DONE IS NULL OR ".DB_PREFIX."links.DONE = '') AND ".DB_PREFIX."links.ID_USER = ".$userid." AND (".DB_PREFIX."links.ID_TOPIC IS NULL OR ".DB_PREFIX."links.ID_TOPIC = '') UNION ALL SELECT ".DB_PREFIX."podcast.SHORT, ".DB_PREFIX."topics.ID, ".DB_PREFIX."topics.ID_PODCAST, ".DB_PREFIX."topics.ID_USER, ".DB_PREFIX."topics.ID_EPISODE, ".DB_PREFIX."topics.ID_CATEGORY, ".DB_PREFIX."topics.DESCR, ".DB_PREFIX."topics.REIHENF, 1 AS IS_TOPIC, ".DB_PREFIX."topics.DONE FROM ".DB_PREFIX."topics join ".DB_PREFIX."podcast ON ".DB_PREFIX."podcast.ID = ".DB_PREFIX."topics.ID_PODCAST WHERE (".DB_PREFIX."topics.DONE IS NULL OR ".DB_PREFIX."topics.DONE = '') AND ".DB_PREFIX."topics.ID_USER = ".$userid." ORDER BY ID_PODCAST, ID_EPISODE, REIHENF";
					$sql_own_entries_result = mysqli_query($con, $sql_own_entries);
					while($sql_own_entries_row = mysqli_fetch_assoc($sql_own_entries_result))
						{
							if($sql_own_entries_row['IS_TOPIC'] == 1)
							{
								$icon = "topic_icon";
								$icon_symbol = "<i class='fas fa-bars fa-fw'></i>";
								$table = "topics";
							}
						else
							{
								$icon = "link_icon";
								$icon_symbol = "<i class='fas fa-link fa-fw'></i>";
								$table = "links";
							}

							echo "<li class='topic_links_item episodes active_content' id='".$table."_".$sql_own_entries_row['ID']."' id_podcast_list='".$sql_own_entries_row['ID_PODCAST']."' id_episode_list='".$sql_own_entries_row['ID_EPISODE']."'>";
								echo "<div class='row lead'>";
									echo "<div class='col-xl-5 col-12' style='margin-top:auto; margin-bottom:auto; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>";
										echo "<div class='".$icon."'>".$icon_symbol."</div>".$sql_own_entries_row['DESCR'];
										echo "</div>";
										echo "<div class='col-xl-3 col-md-6 col-12' style='margin-top:5px; margin-bottom:5px'>";
											echo "<div style='margin-top:auto; margin-bottm:auto;'>";
											if($sql_own_entries_row['DONE'] === '1')
												{
													echo "<span style='color:red'>Eintrag wurde bereits gecheckt!</span>";
												}
											else
												{	
													echo "<select id_category= '".$sql_own_entries_row['ID_CATEGORY']."' table='".$table."' id_entry='".$sql_own_entries_row['ID']."' class='form-control change_episode'>";
														$sql_select_episodes2 = "SELECT ".DB_PREFIX."view_episode_users.EPISODE_USERS_ID_EPISODE AS ID, ".DB_PREFIX."episoden.TITEL, ".DB_PREFIX."episoden.NUMMER AS NUMBER, ".DB_PREFIX."episoden.DATE, ".DB_PREFIX."episoden.DONE FROM ".DB_PREFIX."view_episode_users JOIN ".DB_PREFIX."episoden ON ".DB_PREFIX."episoden.ID = ".DB_PREFIX."view_episode_users.EPISODE_USERS_ID_EPISODE WHERE ".DB_PREFIX."view_episode_users.EPISODE_USERS_ID_USER = ".$userid." AND ID_PODCAST = '".$sql_own_entries_row['ID_PODCAST']."' ORDER BY DATE";
														$sql_select_episodes_result2 = mysqli_query($con, $sql_select_episodes2);
														while($sql_select_episodes_row2 = mysqli_fetch_assoc($sql_select_episodes_result2))
														{
															if($sql_select_episodes_row2['DONE'] == '1')
															{
																$done = " (abgeschlossen) ";
															}
															else
															{
																$done = "";
															}
															echo "<option ";
																if($sql_select_episodes_row2['ID'] == $sql_own_entries_row['ID_EPISODE'])
																{
																	echo "selected disabled";
																}
															echo " id_entry='".$sql_own_entries_row ['ID']."' id_episode='".$sql_select_episodes_row2['ID']."'>".$sql_own_entries_row['SHORT']." | ".str_pad($sql_select_episodes_row2['NUMBER'],3,'0', STR_PAD_LEFT)." ".$sql_select_episodes_row2['TITEL']." ".$done."</option>";
														}
													echo "</select>";
												}
										echo "</div>";	
									echo "</div>";
										echo "<div  class='col-xl-3 col-md-6  col-12 change_div' style='margin-top:5px; margin-bottom:5px'>";
										
										echo "</div>";
										echo "<div class='col-xl-1 col-12' style='margin-top:5px; margin-bottom:5px'>";
												echo "<div class='btn btn-outline-danger btn-block delete_entry' table='".$table."' data-pk='".$sql_own_entries_row ['ID']."'><i class='far fa-times-circle fa-fw'></i></div>";
										echo "</div>";
								echo "</div>";
							echo "</li>";
						}
				echo "</ul>";
				echo "<nav aria-label='Page navigation example'>";
					echo "<ul class='pagination justify-content-center' id='pagin'>";	 
					echo "</ul>";
				echo "</nav>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	
	echo "<script>


    $( document ).ready(function() {

	podcast_list_change();	
	episode_list_change	();
	get_unchecked_categories()

	});	
	</script>";	
}

//Episode exportieren
function export(){
	if(empty($_SESSION['cur_episode']))
		{
			echo "<p class='lead'>Keine Episode gewählt</p>";
			return;
		}
	global $today;
	global $con;
	$sql_episode_close = "SELECT * FROM ".DB_PREFIX."episoden WHERE ID=".$_SESSION['cur_episode'];
	$sql_episode_close_result = mysqli_query($con, $sql_episode_close);
	while ($sql_episode_close_row = mysqli_fetch_assoc($sql_episode_close_result))
		{		
			if($sql_episode_close_row['DONE'] != 1)
				{
					echo "<p class='lead'>Diese Episode ist noch nicht abgeschlossen!</p>";
					return;
				}
			echo "<h3>Shownotes sortieren</h3>";
				echo "<div class='form-check'>";
					echo "<input class='form-check-input export_check' type='radio' name='exampleRadios' id='exampleRadios1' value='REIHENF' checked>";
					echo "<label class='form-check-label' for='exampleRadios1'>";
						echo "Sortierung der Timeline";
					echo "</label>";
				echo "</div>";
			echo "<div class='form-check'>";
				echo "<input class='form-check-input export_check' type='radio' name='exampleRadios' id='exampleRadios2' value='DONE_TS'>";
				echo "<label class='form-check-label' for='exampleRadios2'>";
					echo "Zeitpunkt des Abhakens";
				echo "</label>";
			echo "</div>";
			echo "<div class='form-check export_check'>";
				echo "<input class='form-check-input export_check' type='radio' name='exampleRadios' id='exampleRadios3' value='DESCR'>";
				echo "<label class='form-check-label' for='exampleRadios3'>";
					echo "Alphabetisch (A-Z)";
				echo "</label>";
			echo "</div>";	
			echo "<div style='margin-top: 10px'>";
				echo "<button type='button' id='export_list' class='btn btn-outline-primary btn-block' export_episode_id='".$_SESSION['cur_episode']."'><i class='fas fa-upload fa-fw'></i> Liste exportieren</button>";
				echo "<button type='button' id='clean_episode' class='btn btn-outline-tertiary btn-block clean_episode' change_value='".$_SESSION['cur_episode']."'><i class='fas fa-broom fa-fw'></i> Episode bereinigen</button>";		
			echo "</div>";							
			echo "<hr>";
			echo "<div class='row'>";
				echo "<div class='col-12'>";
					echo "<div id='export_result'>";
					echo "</div>";
				echo "</div>";					
			echo "</div>";		
		}
}

//Kanban-View

function kanban(){
	echo "<div class='container' id='container' style='padding: 0px;'>";
	echo "<a style='font-size: 1.5rem; margin-right: 10px; cursor: pointer' id='show'><i id='edit_cat_link' class='fas fa-users fa-fw'></i></a><div style='display:inline-flex; font-size: 1.5rem;'>Kategorien</div><span id='collapse_icon' style='float:right; cursor:pointer;'><i style='margin: 0 5px' class='fas fa-angle-double-up fa-2x collapse_me'></i><i style='margin: 0 5px' class='fas fa-angle-double-down fa-2x expand_me'></i></span>";
	echo "<hr>";
	if(empty($_SESSION['podcast']))
		{
			echo "<p class='lead'><button class='btn btn-outline-success btn-block change' change_value='podcast' style='cursor: pointer'>Podcast wählen</button>";
			echo "</div";
			return;
		}

	if(empty($_SESSION['cur_episode']))
		{
			echo "<p class='lead'><button class='btn btn-outline-success btn-block change' change_value='episode' style='cursor: pointer'>Episode wählen</button>";
			echo "</div";
			return;
		}
		global $con;
		$sql_categories_list = "SELECT * FROM ".DB_PREFIX."view_episode_categories WHERE EPISODE_ID_PODCAST = ".$_SESSION['podcast']." AND ID_EPISODE = ".$_SESSION['cur_episode']." ORDER BY REIHENF, DESCR";
		$sql_categories_list_result = mysqli_query($con, $sql_categories_list);

		if(mysqli_num_rows($sql_categories_list_result) == 0)
			{
				echo "<p class='lead'>Es wurden noch keine Kategorien angelegt!</p>";
				echo "</div>";
				return;
			}
		while($sql_categories_list_rows = mysqli_fetch_assoc($sql_categories_list_result))
		{
			
			if ($sql_categories_list_rows['MAX_ENTRIES'] >= 1)
				{
					$max_entries_check = $sql_categories_list_rows['MAX_ENTRIES'];
					if ($sql_categories_list_rows['MAX_ENTRIES'] == 1)
						{
							$entries = "Eintrag";
						}
					else
						{
							$entries = "Einträge";
						}
					$max_entries = "<i data-toggle='tooltip' style='float:right' data-placement='top' title='Max. ".$sql_categories_list_rows['MAX_ENTRIES']." ".$entries."' class='fa-fw ".getSetting('MAX_ENTRIES',0)."'></i>";
				}
			else 
				{
					$max_entries_check = 0;
					$max_entries = "";
				}
 				$number_user = getnumber($sql_categories_list_rows['ID_CATEGORY'], $_SESSION['cur_episode'], ' AND ID_USER = ', $_SESSION['userid']);
 				$number_all = getnumber($sql_categories_list_rows['ID_CATEGORY'], $_SESSION['cur_episode'], '', '');
				echo "<div class='row'>";
					echo "<div class='col-8 col-sm-8 col-xl-10 load_content' data-toggle='collapse' href='#collapse_category_".$sql_categories_list_rows['ID_CATEGORY']."' role='button' aria-expanded='false' aria-controls='collapse_category_".$sql_categories_list_rows['ID_CATEGORY']."' category_ID ='".$sql_categories_list_rows['ID_CATEGORY']."'>";
						echo "<div class='btn-select-cat'><h5 style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0px' ><span style='display:none; margin-right: 3px; margin-top: -0.3rem; vertical-align: middle; width: 26px;' class='badge badge-secondary cat_number_user' id='cat_".$sql_categories_list_rows['ID_CATEGORY']."_number_user'>".$number_user."</span><span style='margin-right: 3px; margin-top: -0.3rem; vertical-align: middle; width: 26px;' class='badge badge-secondary cat_number_all' id='cat_".$sql_categories_list_rows['ID_CATEGORY']."_number_all'>".$number_all."</span>".$sql_categories_list_rows['DESCR']."</h5></div>";
					echo "</div>";
					echo "<div class='col-2 col-sm-2 col-xl-1'>";
						echo "<i data-toggle='tooltip' style='float:right' data-placement='top' title='Sichtbarkeit' class='fa-fw ".getSetting('CATEGORY_VISIBLE',$sql_categories_list_rows['VISIBLE'])."'></i>";
						echo $max_entries;
					echo "</div>";
					echo "<div class='col-2 col-sm-2 col-xl-1 load_content' style='text-align:right' data-toggle='collapse' href='#collapse_category_".$sql_categories_list_rows['ID_CATEGORY']."' role='button' aria-expanded='false' aria-controls='collapse_category_".$sql_categories_list_rows['ID_CATEGORY']."' category_ID ='".$sql_categories_list_rows['ID_CATEGORY']."'>";
						echo "<i style='cursor:pointer' class='rotate-arrow cat-rotate-arrow cat_icon_".$sql_categories_list_rows['ID_CATEGORY']." fas fa-angle-double-left fa-2x'></i>";
					echo "</div>";
				echo "</div>";
			echo "<hr class='seperator'>";		
			echo "<div class='collapse collapse-outer' id_cat='".$sql_categories_list_rows['ID_CATEGORY']."' id='collapse_category_".$sql_categories_list_rows['ID_CATEGORY']."' style='margin-top: 15px;'>";
				echo "<ul class='timeline kanban_sortable' max_entries='".$max_entries_check."' cat_id='".$sql_categories_list_rows['ID_CATEGORY']."' id='cat_".$sql_categories_list_rows['ID_CATEGORY']."' style='margin-bottom: 0px'>";
				global $con;
				$sql_kanban_entries = "SELECT ".DB_PREFIX."users.USERNAME, ".DB_PREFIX."users.NAME_SHOW, ".DB_PREFIX."links.ID AS ID, ".DB_PREFIX."links.URL AS URL, ".DB_PREFIX."links.ID_USER AS ID_USER, ".DB_PREFIX."links.ID_EPISODE, ".DB_PREFIX."links.ID_CATEGORY, ".DB_PREFIX."links.DESCR, NULL AS IS_TOPIC, ".DB_PREFIX."links.REIHENF, ".DB_PREFIX."links.DONE, ".DB_PREFIX."links.DONE_TS, ".DB_PREFIX."links.INFO AS INFO, ".DB_PREFIX."episoden.DONE AS EPISODE_DONE from ".DB_PREFIX."links JOIN ".DB_PREFIX."users on ".DB_PREFIX."users.ID = ".DB_PREFIX."links.ID_USER JOIN ".DB_PREFIX."episoden on ".DB_PREFIX."episoden.ID = ".DB_PREFIX."links.ID_EPISODE WHERE ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_CATEGORY = ".$sql_categories_list_rows['ID_CATEGORY']." AND ID_TOPIC IS NULL UNION ALL SELECT ".DB_PREFIX."users.USERNAME, ".DB_PREFIX."users.NAME_SHOW, ".DB_PREFIX."topics.ID AS ID, NULL AS URL, ".DB_PREFIX."topics.ID_USER AS ID_USER, ".DB_PREFIX."topics.ID_EPISODE, ".DB_PREFIX."topics.ID_CATEGORY, ".DB_PREFIX."topics.DESCR, 1 AS IS_TOPIC, ".DB_PREFIX."topics.REIHENF, ".DB_PREFIX."topics.DONE, ".DB_PREFIX."topics.DONE_TS, ".DB_PREFIX."topics.INFO AS INFO, ".DB_PREFIX."episoden.DONE AS EPISODE_DONE from ".DB_PREFIX."topics JOIN ".DB_PREFIX."users on ".DB_PREFIX."users.ID = ".DB_PREFIX."topics.ID_USER JOIN ".DB_PREFIX."episoden on ".DB_PREFIX."episoden.ID = ".DB_PREFIX."topics.ID_EPISODE WHERE ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_CATEGORY = ".$sql_categories_list_rows['ID_CATEGORY']." ORDER BY REIHENF, ID ASC";
				$sql_kanban_entries_result = mysqli_query($con, $sql_kanban_entries);
				while($sql_kanban_entries_row = mysqli_fetch_assoc($sql_kanban_entries_result))
					{
						if ($sql_kanban_entries_row['DONE'] == 1  && $sql_kanban_entries_row['EPISODE_DONE'] == 0)
							{
								$done_ind = "entry_done";
								$btn = "btn-success";
								$done = "";
								$entry_done = "<i class='far fa-check-circle'></i>";
								$entry_done2 = "<style='background-color: rgba(0, 209, 0, 0.3)'>";
							}
						else if ($sql_kanban_entries_row['EPISODE_DONE'] == 1 && $sql_kanban_entries_row['DONE'] == 1)
							{
								$done_ind = "entry_done";
								$btn = "btn-success";
								$done ="disabled";		
								$entry_done = "<i class='far fa-check-circle'></i>";
								$entry_done2 = "<style='background-color: rgba(0, 209, 0, 0.3)'>";
							}																
						else if ($sql_kanban_entries_row['EPISODE_DONE'] == 1 && $sql_kanban_entries_row['DONE'] == 0)
							{
								$done_ind = "";
								$btn = "btn-outline-success";
								$done ="disabled";		
								$entry_done = "";
								$entry_done2 = "";
							}
						else
							{
								$done_ind = "";
								$btn = "btn-outline-success";
								$done ="";		
								$entry_done = "";
							}

						if($sql_kanban_entries_row['ID_USER'] != $_SESSION['userid']	)
							{
								$edit = "disabled";
							}
						else
							{
								$edit = "";
							}
										
						if(empty($sql_kanban_entries_row['NAME_SHOW']))
							{
								$user = $sql_kanban_entries_row['USERNAME'];
							}
						else
							{
								$user = $sql_kanban_entries_row['NAME_SHOW'];
							}
						if($sql_kanban_entries_row['ID_USER'] == $_SESSION['userid'])
							{
								$editable = "edit_topic_".$sql_kanban_entries_row['ID'];
								$own = "1";
							}
						else
							{
								$editable = "";
								$own = "0";
							}
							
						if($sql_kanban_entries_row['IS_TOPIC'] == 1)
							{
								$class = "class='kanban_entry timeline-inverted' cat=".$sql_kanban_entries_row['ID_CATEGORY']." own='".$own."' id='item-t".$sql_kanban_entries_row['ID']."'";
								$icon = "<i class='fas fa-bars fa-fw'></i>";
								$icon_color = " info";
								$title = "<div class='timeline-heading'>";
								$title .= "<h6 class='".$editable." timeline-title' table='topics' data-name='DESCR' data-type='text' data-pk='".$sql_kanban_entries_row['ID']."' style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>".$sql_kanban_entries_row['DESCR']."</h6>";
								$title .= "</div>";
								$type="topics";
							}
						else
							{
								$class = "class='kanban_entry' cat=".$sql_kanban_entries_row['ID_CATEGORY']." own='".$own."'id='item-l".$sql_kanban_entries_row['ID']."'";
								$icon = "<i class='fas fa-link fa-fw'></i>";
								$icon_color = " warning";
								$title = "";
								$type="links";
							}
						
						echo "<li ".$class." table='".$type."' data-pk='".$sql_kanban_entries_row['ID']."'>";
							echo "<div class='timeline-badge timeline-handle".$icon_color."'>".$icon."</div>";
								echo "<div class='timeline-panel ".$done_ind."' id='panel_".$type."_".$sql_kanban_entries_row['ID']."'>";
									echo "<div id='entry_buttons_".$type.$sql_kanban_entries_row['ID']."' style='display:none'>";
										echo "<div class='row' style='margin: 0px;'>";
											echo "<div class='col-4' style='padding:1px'>";
												echo "<button type='button' class='btn btn-outline-danger btn-block delete_entry btn-sm' cat='".$sql_kanban_entries_row['ID_CATEGORY']."' id='delete_".$type.$sql_kanban_entries_row['ID']."' table='".$type."' option='".$type."' data-pk='".$sql_kanban_entries_row['ID']."'><i class='far fa-times-circle fa-fw'></i></button>";
											echo "</div>"; 
											echo "<div class='col-4' style='padding:1px'>";
												echo "<button type='button' edit_type='".$type."' edit_id='".$sql_kanban_entries_row['ID']."' class='btn btn-outline-tertiary btn-block edit_entry btn-sm' id='".$type."_edit_button_".$sql_kanban_entries_row['ID']."'><i class='fas fa-edit fa-fw'></i></button>";
											echo "</div>";
											echo "<div class='col-4' style='padding:1px'>";
												echo "<button type='button' ".$edit." ".$done." class='btn ".$btn." btn-block check_link btn-sm' id='check_".$type."".$sql_kanban_entries_row['ID']."' onclick='check_link(".$sql_kanban_entries_row['ID'].", \"".$type."\")' data-name='DONE' data-checked='".$sql_kanban_entries_row['DONE']."'>";
													echo "<i class='far fa-check-circle'></i>";
												echo "</button>";									
											echo "</div>";
										echo "</div>";
										echo "<hr>";
									echo "</div>";
									echo " <small class='text-muted'>".$user."</small><span style='margin-left: 10px; color: green' class='check_icon_".$type."_".$sql_kanban_entries_row['ID']."'>".$entry_done."</span>";;
									if($sql_kanban_entries_row['ID_USER'] != $_SESSION['userid'])
									{
										$actions = "";
									}
									else
									{ 
										$actions = "<a class='toggle_entry_buttons rotate-arrow' id='toggle_entry_buttons_".$type."_".$sql_kanban_entries_row['ID']."' entry_id='".$sql_kanban_entries_row['ID']."' style='float:right; cursor:pointer' type='".$type."'>";
										$actions .= "<i class='fas fa-angle-double-left fa-2x'></i>";
										$actions .= "</a>";
									}
									echo $actions;
							
									if((getSettingCat('VISIBLE', $sql_categories_list_rows['ID_CATEGORY']) == 0) && ($sql_kanban_entries_row['ID_USER'] != $_SESSION['userid']) && ($sql_kanban_entries_row['DONE'] != 1))
										{
											echo "<div class='timeline-heading'>";
												echo "<h6 class='timeline-title' style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>Gesperrt</h6>";
											echo "</div>";		
										}
									else
										{
											echo $title;
												echo "<div class='timeline-body'>";
												if(!empty($sql_kanban_entries_row['INFO']))
													{
														$notice_vis = "";
													}
												else
													{
														$notice_vis = "display: none";
													}
														$notice = "<hr>";
														$notice .= "<div style='text-align:right;'>";
														$notice .= "<i id_entry='".$sql_kanban_entries_row['ID']."' id='notice_toggle_".$type."_".$sql_kanban_entries_row['ID']."' type='".$type."' style='cursor:pointer; color: #6c3600; ".$notice_vis."' class='rotate-arrow far fa-sticky-note fa-2x toggle_notice'></i>";
														$notice .= "</div>";
												if($sql_kanban_entries_row['IS_TOPIC'] == 1)
													{
														echo "<div class='collapse-inner' style='cursor:pointer; color:#009688; text-align: right' id_topic='".$sql_kanban_entries_row['ID']."'>";
															echo "<i class='rotate-arrow fas fa-angle-double-left fa-2x expand_icon_".$sql_kanban_entries_row['ID']."'></i>";
														echo "</div>";
														echo "<div class='collapse collapse-inner-content' style='margin-top:10px' id='collapse_topic_".$sql_kanban_entries_row['ID']."' topic='".$sql_kanban_entries_row['ID']."'>";
															echo "<ul class='topic_links' >";
															$select_topic_links = "SELECT * FROM ".DB_PREFIX."links WHERE ID_TOPIC = ".$sql_kanban_entries_row['ID'];
															$select_topic_links_result = mysqli_query($con, $select_topic_links);
															while($select_topic_links_rows = mysqli_fetch_assoc($select_topic_links_result))
																{
																	echo "<li class='topic_links_item' id='item-l".$select_topic_links_rows['ID']."'>";
																		echo "<div class='row centered-items' style='padding: 0px 14px;'>";
																			echo "<div class='col-12 col-xl-8' style='padding:1px;'>";
																				echo "<div class='link_icon topic_link_icon_".$sql_kanban_entries_row['ID']."' id='".$type."_".$sql_kanban_entries_row['ID']."'><i class='fas fa-link fa-fw'></i></div>";
																					echo "<div class='lead link_topic_".$sql_kanban_entries_row['ID']."' table='links' data-name='DESCR' data-type='text' data-pk='".$select_topic_links_rows['ID']."' style='margin-bottom: 0px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>";
																						if(!empty($select_topic_links_rows['DESCR']))
																							{
																								echo $select_topic_links_rows['DESCR'];
																							}
																						else{
																							echo "Kein Titel";
																							}
																					echo "</div>";
																				echo "</div>";
																				echo "<div class='link_topic_delete_".$sql_kanban_entries_row['ID']." delete_entry' table='links' option='links' data-pk='".$select_topic_links_rows['ID']."'>";
																				echo "</div>";
																				echo "<div class='col-12 col-xl-8 links_url_".$sql_kanban_entries_row['ID']."' style='padding:1px; display: none'>";
																					echo "<div class='lead link_topic_".$sql_kanban_entries_row['ID']."' beschr='URL' table='links' data-name='URL' data-type='text' data-pk='".$select_topic_links_rows['ID']."' style='margin-bottom: 0px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>".$select_topic_links_rows['URL']."</div>";
																				echo "</div>";																	
																				if($select_topic_links_rows['URL'] == NULL || $select_topic_links_rows['URL'] == '')
																					{
																						echo "<div class='col-6 col-xl-2' style='padding:1px' id='buttons_link_open_".$select_topic_links_rows['ID']."'>";
																							echo "<button type='button' class='btn btn-outline-warning btn-block btn-sm' disabled><i class='fas fa-external-link-alt fa-fw'></i></button>";
																						echo "</div>";
																						echo "<div class='col-6 col-xl-2' style='padding:1px' id='buttons_link_copy_".$select_topic_links_rows['ID']."'>";
																							echo "<button type='button' class='btn btn-outline-info btn-block btn-sm' disabled><i class='far fa-copy fa-fw fa-fw'></i></button>";
																						echo "</div>";
																					}
																				else
																					{
																						echo "<div class='col-6 col-xl-2' style='padding:1px' id='buttons_link_open_".$select_topic_links_rows['ID']."'>";
																							$fund_url = $select_topic_links_rows['URL'];
																							if (substr($fund_url, 0,4) !== "http")
																								{
																									$base = "http://".$fund_url;
																								}
																								else
																								{
																									$base = $fund_url;
																								}
																							echo "<button onclick='window.open(\"".$base."\");' type='button' class='btn btn-warning btn-block btn-sm'>";
																								echo "<i class='fas fa-external-link-alt fa-fw'></i>";
																							echo "</button>";
																						echo "</div>";
																						echo "<div class='col-6 col-xl-2' style='padding:1px' id='buttons_link_copy_".$select_topic_links_rows['ID']."'>";
																							echo "<button data-clipboard-text='".$base."' onclick='copy_link()' class='btn".$select_topic_links_rows['ID']." btn btn-info btn-block clipboard btn-sm'>";
																								echo "<i class='far fa-copy fa-fw'></i>";
																							echo "</button>";
																						echo "</div>";
																						
																						echo "<script>
																							var clip = new ClipboardJS('.btn".$select_topic_links_rows['ID']."');
																						</script>";
																					}		
																		echo "</div>";
																	echo "</li>";
																}
															echo "</ul>";
														echo "</div>";
														echo $notice;
														echo "<div style='display:none' id ='".$type."_notice_".$sql_kanban_entries_row['ID']."'>";
															echo "<div class='lead' id='".$type."_notice_edit_".$sql_kanban_entries_row['ID']."' style='font-size: 0.9rem'>".$sql_kanban_entries_row['INFO']."</div>";
															echo "<div id='savebutton".$type.$sql_kanban_entries_row['ID']."'></div>";
														echo "</div>";
													}
												else
													{
														echo "<div class='row' style='padding: 0px 14px 0px 14px; margin-top: 15px'>";
															echo "<ul class='topic_links'>";
																echo "<li class='topic_links_item'>";
																	echo "<div class='row centered-items' style='padding: 0px 14px;'>";
																		echo "<div class='col-12 col-xl-8 link_title' style='padding:1px;'>";
																			echo "<div class='link_icon link_icon_".$sql_kanban_entries_row['ID']."' id='".$type."_".$sql_kanban_entries_row['ID']."'>";
																				echo "<i class='fas fa-link fa-fw'></i>";
																			echo "</div>";
																			echo "<p class='lead edit_link_".$sql_kanban_entries_row['ID']."' table='links' data-name='DESCR' data-type='text' data-pk='".$sql_kanban_entries_row['ID']."' style='margin-bottom: 0px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>";
																						if(!empty($sql_kanban_entries_row['DESCR']))
																							{
																								echo $sql_kanban_entries_row['DESCR'];
																							}
																						else{
																							echo "Kein Titel";
																							}
																			echo "</p>";
																		echo "</div>";
																		echo "<div class='col-12 col-xl-8' id='".$type."_url_".$sql_kanban_entries_row['ID']."' style='padding:1px; display:none'>";
																			echo "<p class='lead edit_link_".$sql_kanban_entries_row['ID']."' beschr='URL' table='links' data-name='URL' data-type='text' data-pk='".$sql_kanban_entries_row['ID']."' style='margin-bottom: 0px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>".$sql_kanban_entries_row['URL']."</p>";
																		echo "</div>";
																		if($sql_kanban_entries_row['URL'] == NULL || $sql_kanban_entries_row['URL'] == '')
																			{
																				echo "<div class='col-6 col-xl-2' style='padding:1px' id='buttons_link_open_".$sql_kanban_entries_row['ID']."'>";
																					echo "<button type='button' class='btn btn-outline-warning btn-block btn-sm' disabled><i class='fas fa-external-link-alt fa-fw'></i></button>";
																				echo "</div>";
																				echo "<div class='col-6 col-xl-2' style='padding:1px' id='buttons_link_copy_".$sql_kanban_entries_row['ID']."'>";
																					echo "<button type='button' class='btn btn-outline-info btn-block btn-sm' disabled><i class='far fa-copy fa-fw fa-fw'></i></button>";
																				echo "</div>";
																			}
																		else
																			{
																				echo "<div class='col-6 col-xl-2' style='padding:1px' id='buttons_link_open_".$sql_kanban_entries_row['ID']."'>";
																					$fund_url = $sql_kanban_entries_row['URL'];
																					if (substr($fund_url, 0,4) !== "http")
																						{
																							$base = "http://".$fund_url;
																						}
																						else
																						{
																							$base = $fund_url;
																						}
																					echo "<button onclick='window.open(\"".$base."\");' type='button' class='btn btn-warning btn-block btn-sm'>";
																						echo "<i class='fas fa-external-link-alt fa-fw'></i>";
																					echo "</button>";
																				echo "</div>";
																				echo "<div class='col-6 col-xl-2' style='padding:1px' id='buttons_link_copy_".$sql_kanban_entries_row['ID']."'>";
																					echo "<div data-clipboard-text='".$base."' onclick='copy_link()' class='btn".$sql_kanban_entries_row['ID']." btn btn-info btn-block clipboard btn-sm'>";
																						echo "<i class='far fa-copy fa-fw'></i>";
																					echo "</div>";
																				echo "</div>";
																				
																				echo "<script>
																					var clip = new ClipboardJS('.btn".$sql_kanban_entries_row['ID']."');
																				</script>";
																			}	
																	echo "</div>";
																echo "</li>";
															echo "</ul>";
														echo "</div>";
														echo $notice;
															echo "<div style='display:none' id ='".$type."_notice_".$sql_kanban_entries_row['ID']."'>";
																echo "<div class='lead' id='".$type."_notice_edit_".$sql_kanban_entries_row['ID']."' style='font-size: 0.9rem'>".$sql_kanban_entries_row['INFO']."</div>";
																echo "<div id='savebutton".$type.$sql_kanban_entries_row['ID']."'></div>";
															echo "</div>";
													}
												echo "</div>";
											echo "</div>";
										echo "</li>";
										}
					}   
				echo "</ul>";
				if(!empty($_SESSION['cur_episode']))
					{
						if ((getPermission($_SESSION['userid']) > 1 && !userInEpisode($_SESSION['userid'], $_SESSION['cur_episode'])) || episodeclosed($_SESSION['cur_episode']) == 1)
							{
							}
						else
						{
							echo "<ul class='timeline no_change' style='margin-bottom: 20px'>";
								echo "<li>";
										echo "<div class='timeline-badge success add_entry_category' change_value='".$_SESSION['cur_episode']."' max_entries='".$sql_categories_list_rows['MAX_ENTRIES']."' id_cat='".$sql_categories_list_rows['ID_CATEGORY']."' style='cursor:pointer; margin-top: -16px;'><i class='fas fa-plus fa-fw'></i></div>";
								echo "</li>"; 
							echo "</ul>"; 
						}
					}
				echo "<hr>";
			echo "</div>";
		}
	echo "</div>";
}

//Episode abschließen
function close_episode(){
	global $today;
	global $con;
	$sql_episode_close = "SELECT * FROM ".DB_PREFIX."episoden WHERE ID=".$_SESSION['cur_episode'];
	$sql_episode_close_result = mysqli_query($con, $sql_episode_close);
	while ($sql_episode_close_row = mysqli_fetch_assoc($sql_episode_close_result))
		{
			if(getPermission($_SESSION['userid']) > 1 && $sql_episode_close_row['DONE'] == 0 && $sql_episode_close_row['DATE'] <= $today)
				{
					echo "<div class='row'>";
						echo "<div class='col-md-12'>";
							echo "<div class='tile'>";
								echo "<button type='button' id='closeepisode' episode='".$_SESSION['cur_episode']."' class='btn btn-outline-primary btn-block'>Episode abschließen</button>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				}	

			if($sql_episode_close_row['DONE'] == 1 && getPermission($_SESSION['userid']) > 1)
				{
					echo "<div class='row'>";
						echo "<div class='col-md-12'>";
							echo "<div class='tile'>";
								echo "<button type='button' id='openepisode' episode='".$_SESSION['cur_episode']."' class='btn btn-primary btn-block'>Episode wieder öffnen</button>";
							echo "</div>";
						echo "</div>";
						echo "</div>";
				}	
		}
}

//Profil bearbeiten
function profil_edit(){
	global $con;
	echo "<div class='tile'>";
		echo "<div class='tile-title'>";
			echo "Mein Profil";
		echo "</div>";
		echo "<hr>";
		echo "<div class='tile-body'>";

		$sql_profil = "SELECT * FROM ".DB_PREFIX."users WHERE ID = ".$_SESSION['userid'];
		$sql_profil_result = mysqli_query($con, $sql_profil);
		$rows = mysqli_num_rows($sql_profil_result);
		while ($rows_profil = mysqli_fetch_assoc($sql_profil_result))
			{				
				echo "<div class='form-group'>";
					echo "<label for='Username'>Benutzername</label>";
					echo "<input disabled value=".$rows_profil['USERNAME']." name='uname' type='text' class='form-control' id='Username' aria-describedby='emailHelp' >";
				echo "</div>";	
				echo "<div class='form-group'>";
					echo "<label for='Username_Show' id='Tool_Username_Show' data-toggle='tooltip' title='Diesen Anzeigenamen gibt es schon!'>Anzeigename <small>(Wenn leer, dann wir der Login-Name angezeigt)</small></label>";
					echo "<div class='input-group'>";
						echo "<input type='text' name='Username_Show' id ='Username_Show' class='form-control' value='".htmlspecialchars($rows_profil['NAME_SHOW'])."' name_show_cur='".htmlspecialchars($rows_profil['NAME_SHOW'])."'>";
						echo "<div class='input-group-append'>";
							echo "<span class='input-group-text' min='0' id='Username_Show_availability-status-new'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
						echo "</div>";
					echo "</div>";
				echo "</div>";	
				echo "<script>
					$(\"#Tool_Username_Show\").tooltip('disable');
				</script>";

				echo "<div class='form-group'>";
					echo "<label for='email'>E-Mail</label>";
					echo "<input value='".htmlspecialchars($rows_profil['EMAIL'])."' name='email' type='text' class='form-control' id='email' aria-describedby='emailHelp' >";
				echo "</div>";
				echo "<div class='form-group'>";
					echo "<label for='Password'>Neues Passwort</label>";
					echo "<input name='password' type='password' class='form-control' id='Password' >";
				echo "</div>";					  
				echo "<div class='form-group'>";
					echo "<label for='PasswordRepeat'>Neues Passwort wiederholen</label>";
					echo "<input name='password2' type='password' class='form-control' id='PasswordRepeat' >";
				echo "</div>";
				echo "<hr>";
				echo "<div class='form-group'>";
					echo "<div style='text-align:left'>";
						echo "<div class='toggle lg'>";
							echo "<label class='switch'>";
								echo "<input class='form-check-input' ";
									if($rows_profil['SAVE_PODCAST'] ==1)
									{
										echo "checked";
									}
								echo " type='checkbox' name='save_podcast' id='save_podcast' data-type='text' >";
								echo "<span class='button-indecator'>";
									echo "Podcast-Auswahl speichern";
								echo "</span>";
							echo "</label>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "<div class='form-group'>";
					echo "<div style='text-align:left'>";
						echo "<div class='toggle lg'>";
							echo "<label class='switch'>";
								echo "<input class='form-check-input' ";
								if($rows_profil['SAVE_EPISODE'] ==1)
									{
										echo "checked";
									}
								echo " type='checkbox' name='save_episode' id='save_episode' data-type='text'>";
								echo "<span class='button-indecator'>";
									echo "Episoden-Auswahl speichern";
								echo "</span>";
							echo "</label>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			}			
		echo "<br><button type='button' name='update_profile' user_id = '".$_SESSION['userid']."' id='save_profile' class='btn btn-outline-primary'><i class='fas fa-save'></i> Speichern</button>";
		echo "</div>";
	echo "</div>";
}

//Footer
function footer(){
	echo "<script src='js/frontend.js'></script>";
	echo "<div class='modal fade' id='change' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
		echo "<div class='modal-dialog' role='document'>";
			echo "<div class='modal-content'>";
				echo "<div class='modal-header'>";
					echo " <h5 class='modal-title' id='exampleModalLabel'></h5>";
				echo "</div>";
				echo "<div class='modal-body' id='change_content'>";
				echo "</div>";
				echo "<div class='modal-footer'>";
					echo "<div id='button_footer'>";
					echo "</div>";
					echo "<button type='button' class='btn btn-outline-secondary' data-dismiss='modal'>Schließen</button>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
}
?>