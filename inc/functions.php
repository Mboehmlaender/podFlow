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
		echo "<script src='//cdn.ckeditor.com/4.9.2/basic/ckeditor.js'></script>";
		echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js' integrity='sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49' crossorigin='anonymous'></script>";
		echo "<script src='https://code.jquery.com/jquery-3.3.1.min.js' integrity='sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=' crossorigin='anonymous'></script>";
		echo "<script src='js/jquery.gritter.min.js'></script>";
		echo "<script src='https://code.jquery.com/ui/1.12.0/jquery-ui.min.js'></script>";
		echo "<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js' integrity='sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy' crossorigin='anonymous'></script>";
		echo "<script src='https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js'></script>";
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
			global $con;
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
				}
		return;
		}
}

//Obere Navigation
function navbar_top(){
/* 	if(isset($_POST['change_podcast']))
		{
			$_SESSION['podcast'] = $_POST['change_podcast'];
			$_SESSION['cur_episode'] = '';
		} */
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
				echo "<a style='text-decoration: none' href='episode.php'><div class='widget-small primary coloured-icon'><i class='icon fas fa-tasks fa-3x fa-fw'></i>";
					echo "<div class='info'>";
						echo "<h4  style='text-transform: none'>Alle Beiträge</h4>";
					echo "</div>";
				echo "</div></a>";
			echo "</div>";
			echo "<div class='col-xl-4'>";
				echo "<a style='text-decoration: none' href='links.php'><div class='widget-small primary coloured-icon'><i class='icon fas fa-bookmark fa-3x fa-fw'></i>";
					echo "<div class='info'>";
						echo "<h4  style='text-transform: none'>Meine Beiträge</h4>";
					echo "</div>";
				echo "</div>";
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
					echo "<li><a class='app-menu__item' id='menu_episode' href='episode.php'><i class='app-menu__icon fas fa-tasks'></i><span class='app-menu__label'>Alle Beiträge</span></a></li>";
					echo "<li><a class='app-menu__item' id='menu_links' href='links.php'><i class='app-menu__icon fas fa-bookmark'></i><span class='app-menu__label'>Meine Beiträge</span></a></li>";
					if(getPermission($_SESSION['userid']) > 1)
						{
							echo "<li><a class='app-menu__item' id='menu_export' href='export.php'><i class='app-menu__icon fas fa-upload'></i><span class='app-menu__label'>Export</span></a></li>";
						}
				}
			echo "<li><a class='app-menu__item' id='menu_profile' href='profile.php'><i class='app-menu__icon fas fa-user'></i><span class='app-menu__label'>Mein Profil</span></a></li>";
			if(getPermission($_SESSION['userid']) > 1)
				{
					echo "<li><a class='app-menu__item' id='menu_backend' href='admin/index.php'><i class='app-menu__icon fas fa-chevron-circle-right'></i><span class='app-menu__label'>Backend</span></a></li>";
				}
		echo "</ul>";
	echo "</aside>";
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
			echo "<div class='row'>";
				echo "<div class='col-6'>";
					echo "<div class='form-group'>";
						echo "<div class='btn btn-danger btn-block' style='pointer-events: none; text-align: center'><i class='fas fa-ban'></i></div>";
						echo "<ul class='list-group sortuncheck sortable' data-name='DONE' id='list_uncheck' table='links' style='min-height:80px; background-color: rgba(211,211,211,0.3); border-radius: .25rem; box-shadow: 2px 2px 2px grey'>";
						$sql_select = "SELECT ID, ID_EPISODE, DESCR, NULL AS IS_TOPIC, DONE, DONE_TS from ".DB_PREFIX."links WHERE ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_TOPIC IS NULL AND DONE = 0 UNION ALL SELECT ID, ID_EPISODE, DESCR, 1 AS IS_TOPIC, DONE, DONE_TS from ".DB_PREFIX."topics where ID_EPISODE = ".$_SESSION['cur_episode']." AND DONE = 0 ORDER BY `DESCR` ASC";
						$sql_select_result = mysqli_query($con, $sql_select);
						while ($sql_select_row = mysqli_fetch_assoc($sql_select_result))
							{
								if($sql_select_row['IS_TOPIC'] == 0)
									{													
										echo "<li table='links' id ='item-l".$sql_select_row['ID']."' data-pk='".$sql_select_row['ID']."' class='list-group-item sortli' style='padding: 12px 10px; background:white; opacity: 1;'><div class='innerwrap' style='overflow:hidden;white-space:nowrap; text-overflow:ellipsis'><span class='fas fa-arrows-alt fa-fw glyphicon-move' aria-hidden='true'></span><input hidden value='".$sql_select_row['ID']."'>".$sql_select_row['DESCR']."</div></li>";
									}
								else
									{
										echo "<li table='topics' id ='item-t".$sql_select_row['ID']."' data-pk='".$sql_select_row['ID']."' class='list-group-item sortli' style='padding: 12px 10px; background:white; opacity: 1;'><div class='innerwrap' style='overflow:hidden;white-space:nowrap; text-overflow:ellipsis'><span class='fas fa-arrows-alt fa-fw glyphicon-move' aria-hidden='true'></span><input hidden value='".$sql_select_row['ID']."'>".$sql_select_row['DESCR']."</div></li>";
									}	
							}
						echo"</ul>";
					echo "</div>"; 
				echo "</div>"; 
				echo "<div class='col-6'>";
					echo "<div class='form-group'>";
						echo "<div class='btn btn-success btn-block' style='pointer-events: none; text-align: center'><i class='fas fa-check'></i></div>";
							echo "<ul class='list-group sortcheck sortable' data-name='DONE' id='list_check'  table='links' style='min-height:100px; background-color: rgba(211,211,211,0.3); border-radius: .25rem; box-shadow: 2px 2px 2px grey'>";
							global $con;
							$sql_select = "SELECT ID, ID_EPISODE, DESCR, NULL AS IS_TOPIC, DONE, REIHENF, DONE_TS from ".DB_PREFIX."links WHERE ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_TOPIC IS NULL AND DONE = 1 UNION ALL SELECT ID, ID_EPISODE, DESCR, 1 AS IS_TOPIC, DONE, REIHENF,DONE_TS from ".DB_PREFIX."topics where ID_EPISODE = ".$_SESSION['cur_episode']." AND DONE = 1 ORDER BY REIHENF, DONE_TS";
							$sql_select_result = mysqli_query($con, $sql_select);
							while ($sql_select_row = mysqli_fetch_assoc($sql_select_result))
								{
									if($sql_select_row['IS_TOPIC'] == 0)
										{													
											echo "<li table='links' id='item-l".$sql_select_row['ID']."' data-pk='".$sql_select_row['ID']."' class='list-group-item sortli' style='padding: 12px 10px; background:white;  opacity: 1;'><div class='innerwrap' style='overflow:hidden;white-space:nowrap; text-overflow:ellipsis'><span class='fas fa-arrows-alt fa-fw glyphicon-move' aria-hidden='true'></span><input hidden value='".$sql_select_row['ID']."'>".$sql_select_row['DESCR']."</div></li>";
										}
									else
										{
											echo "<li table='topics' id='item-t".$sql_select_row['ID']."' data-pk='".$sql_select_row['ID']."' class='list-group-item sortli' style='padding: 12px 10px; background:white;  opacity: 1;'><div class='innerwrap' style='overflow:hidden;white-space:nowrap; text-overflow:ellipsis'><span class='fas fa-arrows-alt fa-fw glyphicon-move' aria-hidden='true'></span><input hidden value='".$sql_select_row['ID']."'>".$sql_select_row['DESCR']."</div>";
											echo "</li>";
										}	
								}
							echo"</ul>";
						echo "</div>"; 
					echo "</div>"; 
				echo "</div>";					
			echo "</div>";					
			echo "<button type='button' id='export_list' class='btn btn-outline-primary btn-block' data-toggle='modal' data-target='#export_modal'><i class='fas fa-upload fa-fw'></i> Liste exportieren</button>";
			echo "<button type='button' id='clean_episode' class='btn btn-outline-tertiary btn-block clean_episode' change_value='".$_SESSION['cur_episode']."'><i class='fas fa-broom fa-fw'></i> Episode bereinigen</button>";		
		
			echo "<div class='modal fade' id='export_modal' tabindex='-1' role='dialog' aria-labelledby='export_modal_title' aria-hidden='true'>";
				echo "<div class='modal-dialog modal-dialog-centered' role='document'>";
					echo "<div class='modal-content'>";
						echo "<div class='modal-header'>";
							echo "<h5 class='modal-title' id='export_modal_title'>Beiträge und Themen exportieren</h5>";
						echo "</div>";
						echo "<div class='modal-body' id='export_out'>";
							echo "<ul class='nav nav-pills mb-3' id='pills-tab' role='tablist'>";
								echo "<li class='nav-item'>";
									echo "<a class='nav-link active' id='pills-home-tab' data-toggle='pill' href='#HTML-list' role='tab' aria-controls='pills-home' aria-selected='true'>HTML Bindestriche</a>";
								echo "</li>";								
								echo "<li class='nav-item'>";
									echo "<a class='nav-link' id='pills-profile-tab' data-toggle='pill' href='#HTML-bullet' role='tab' aria-controls='pills-profile' aria-selected='false'>HTML Gliederung</a>";
								echo "</li>";
								echo "<li class='nav-item'>";
									echo "<a class='nav-link' id='pills-profile-tab' data-toggle='pill' href='#text' role='tab' aria-controls='pills-profile' aria-selected='false'>Text</a>";
								echo "</li>";
							echo "</ul>";
							echo "<div class='tab-content' id='pills-tabContent'>";
									$sql_select = "SELECT * FROM ".DB_PREFIX."view_links WHERE EPISODEN_ID=".$_SESSION['cur_episode']." AND LINKS_DONE = 1 ORDER BY LINKS_REIHENF, LINKS_DONE_TS ASC";
									$sql_select_result = mysqli_query($con, $sql_select);
									$stringarray = array();
									$stringarray2 = array();
									while ($sql_select_row = mysqli_fetch_assoc($sql_select_result))
									{
										$fund_url = $sql_select_row['LINKS_URL'];
										$pos = "http";
										if(empty($fund_url))
											{
												$base = "&lt;a href='#' &gt;".$sql_select_row['LINKS_DESCR']."&lt;/a&gt";
											}
										else if (strpos($fund_url, $pos) === false)
											{
												$base = "&lt;a href='http://".$fund_url."' target='_blank' &gt;".$sql_select_row['LINKS_DESCR']."&lt;/a&gt";
											}
										else
											{
												$base = "&lt;a href='".$fund_url."' target='_blank' &gt;".$sql_select_row['LINKS_DESCR']."&lt;/a&gt";
											}
											array_push($stringarray, $base);	
											array_push($stringarray2, "<li>".$sql_select_row['LINKS_DESCR']."</li>");	
									}
								echo "<div class='tab-pane fade show active' id='HTML-list' role='tabpanel' aria-labelledby='pills-home-tab'>";
									echo "<textarea class='form-control' id='exampleFormControlTextarea1' rows='5'>";
										echo implode(" - ",$stringarray);
									echo"</textarea>"; 
									echo "<div style='padding: 5px 5px 0px 5px; font-size:80%; font-weight: 400'>";
										echo "<br>Beispiel: <a href='http://www.google.de' target='_blank'>Beitrag 1</a> - <a href='http://www.google.de' target='_blank'>Beitrag 2</a>";
									echo "</i></div>";
								echo "</div>";								
								echo "<div class='tab-pane fade' id='HTML-bullet' role='tabpanel' aria-labelledby='pills-profile-tab'>";
									echo "<textarea class='form-control' id='exampleFormControlTextarea1' rows='5'>";
										echo "<ul>\r\n<li>";
										echo implode("</li>\r\n<li>",$stringarray);
										echo "</li>\r\n</ul>";
									echo"</textarea>";     
									echo "<div style='padding: 5px 5px 0px 5px; font-size:80%; font-weight: 400'>";
									echo "<br>Beispiel: <ul><li><a href='http://www.google.de' target='_blank'>Beitrag 1</a></li><li><a href='http://www.google.de' target='_blank'>Beitrag 2</a></li></ul>";
									echo "</i></div>";
								echo "</div>";
								echo "<div class='tab-pane fade' id='text' role='tabpanel' aria-labelledby='pills-profile-tab'>";
									echo "<ul style='list-style-type:none'>";
										echo implode($stringarray2);
									echo "</ul>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
						echo "<div class='modal-footer'>";
							echo "<button type='button' class='btn btn-outline-secondary' data-dismiss='modal'>Schließen</button>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";							
		}
}

//Beiträge eines Thema anzeigen			
function topics(){
	if(empty($_GET['topic']))
		{
			echo "<div class='tile-title'>";
				echo "Kein Thema";
			echo "</div>";
			echo "Kein Thema";
		echo "</div>";
		return;
		}
	else
		{
			echo "<div class='tile-title'>";
				echo "<div class='row'>";
					echo "<div class='col-10' style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>";
					global $con;
					$sql_topic_title = "SELECT * FROM ".DB_PREFIX."view_topics WHERE TOPICS_ID = ".$_GET['topic'];
					$sql_topic_title_result = mysqli_query($con, $sql_topic_title);
					$sql_topic_title_row = mysqli_fetch_assoc($sql_topic_title_result);
					if($sql_topic_title_row['TOPICS_ID_USER'] != $_SESSION['userid'] && $sql_topic_title_row['CATEGORIES_VISIBLE'] == 0 && $sql_topic_title_row['TOPICS_DONE'] != 1)
						{
							echo "Gesperrt";
						}
					else
						{
							echo $sql_topic_title_row['TOPICS_DESCR'];
						}
					echo "</div>";
					echo "<div class='col-2' style='text-align:right'>";
						echo "<div onclick=\"location.href='../episode.php';\" style='cursor:pointer'><i class='fas fa-fw fa-window-close'></i>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "<div class='tile-body'>";
			if(!empty($sql_topic_title_row['TOPICS_INFO']))
				{
					echo "<hr>";
					echo "<button class='btn btn-notice btn-block' type='button' data-toggle='collapse' data-target='#collapseExample".$sql_topic_title_row['TOPICS_ID']."' aria-expanded='false' aria-controls='collapseExample".$sql_topic_title_row['TOPICS_ID']."'>";
						echo "Notizen";
					echo "</button>";
					echo "<div class='collapse' id='collapseExample".$sql_topic_title_row['TOPICS_ID']."'>";
						echo "<div style='margin-top:10px; padding: 15px'>";
							echo $sql_topic_title_row['TOPICS_INFO'];
						echo "</div>";				
					echo "</div>";				
					echo "<hr>";
				}

			$sql_topic_links = "SELECT * FROM ".DB_PREFIX."view_links WHERE LINKS_ID_TOPIC = ".$_GET['topic'];
			$sql_topic_links_result = mysqli_query($con, $sql_topic_links);
			while($sql_topic_row = mysqli_fetch_assoc($sql_topic_links_result))
				{
					if($sql_topic_row['LINKS_ID_USER'] != $_SESSION['userid'] && $sql_topic_row['CATEGORIES_VISIBLE'] == 0 && $sql_topic_row['TOPICS_DONE'] != 1)
					{
						echo "Gesperrt";
						echo "</div>";
						return;
					}
					echo "<div class='lead'>";
						echo $sql_topic_row['LINKS_DESCR'];
					echo "</div>";
					echo "<div class='form-row'>";
					if($sql_topic_row['LINKS_URL'] == NULL || $sql_topic_row['LINKS_URL'] == '')
						{
							echo "<div class='col-md-6 col-sm-12' style='padding: 1px;'>";
								echo "<button disabled type='button' class='btn btn-warning btn-block'>";
									echo "<i class='fas fa-external-link-alt fa-fw'></i>";
								echo "</button>";
							echo "</div>";
							echo "<div class='col-md-6 col-sm-12' style='padding: 1px;'>";
								echo "<button disabled class='btn btn-info btn-block'>";
									echo "<i style='color:black' class='far fa-copy fa-fw'></i>";
								echo "</button>";
							echo "</div>";
						}
					else
						{
							$fund_url = $sql_topic_row['LINKS_URL'];
							$pos = "http";
							if (strpos($fund_url, $pos) === false)
								{
									$base = "http://".$fund_url;
								}
							else
								{
									$base = $fund_url;
								}
							echo "<div class='col-md-6 col-sm-12' style='padding: 1px;'>";
								echo "<button onclick='window.open(\"".$base."\");' type='button' class='btn btn-warning btn-block'>";
									echo "<i class='fas fa-external-link-alt fa-fw'></i>";
								echo "</button>";
							echo "</div>";
							echo "<div class='col-md-6 col-sm-12' style='padding: 1px;'>";
								echo "<div data-clipboard-text='".$sql_topic_row['LINKS_URL']."' class='btn".$sql_topic_row['LINKS_ID']." btn btn-info btn-block clipboard'>";
									echo "<i style='color:black' class='far fa-copy fa-fw'></i>";
								echo "</div>";
							echo "</div>";
							echo "<script>
								var clip = new ClipboardJS('.btn".$sql_topic_row['LINKS_ID']."');
							</script>";
						}					
					echo "</div>";
					echo "<hr>";
				}
			echo "</div>";

		} 
}	

//Kategorienliste mit Themen und Beiträge (Hauptseite)
function category_list(){
	echo "<a href='javascript:void(0);' style='font-size: 1.5rem;' id='show'><i class='fas fa-bars fa-fw'></i></a><div style='display:inline-flex; font-size: 1.5rem;'>Kategorien</div>";
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
		$sql_categories_list = "SELECT * FROM ".DB_PREFIX."view_episode_categories WHERE CATEGORIES_ID_PODCAST = ".$_SESSION['podcast']." AND ID_EPISODE = ".$_SESSION['cur_episode']." ORDER BY REIHENF, DESCR";
		$sql_categories_list_result = mysqli_query($con, $sql_categories_list);

	if(mysqli_num_rows($sql_categories_list_result) == 0)
		{
			echo "<p class='lead'>Es wurden noch keine Kategorien angelegt!</p>";
			echo "</div";
			return;
		}
			
	
	while ($sql_categories_list_row = mysqli_fetch_assoc($sql_categories_list_result))
		{
			if($sql_categories_list_row['ALLOW_TOPICS'] == 0)
				{
					$number = getnumber('links', $sql_categories_list_row['ID_CATEGORY'], $_SESSION['cur_episode'], 'AND ID_TOPIC IS NULL');
				}
			else
				{
					$number = getnumber('topics', $sql_categories_list_row['ID_CATEGORY'], $_SESSION['cur_episode'], '');
				}
		
			if ($sql_categories_list_row['MAX_ENTRIES'] >= 1)
				{
					if ($sql_categories_list_row['MAX_ENTRIES'] == 1)
						{
							$entries = "Eintrag";
						}
					else
						{
							$entries = "Einträge";
						}
					$max_entries = "<i data-toggle='tooltip' data-placement='top' title='Max. ".$sql_categories_list_row['MAX_ENTRIES']." ".$entries."' class='fa-fw ".getSetting('MAX_ENTRIES',0)."'></i>";
				}
			else 
				{
					$max_entries = "";
				}
		
			echo "<div class='row load_content' category_ID ='".$sql_categories_list_row['ID_CATEGORY']."' onclick='load_cat(".$sql_categories_list_row['ID_CATEGORY'].")'>";
				echo "<div class='col-9 col-sm-10'>";
					echo "<div class='btn-select-cat'><h5 style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0px' ><span style='margin-right: 3px; margin-top: -0.3rem; vertical-align: middle; width: 26px;' class='badge badge-secondary'>".$number."</span>".$sql_categories_list_row['DESCR']."</h5></div>";
				echo "</div>";
				echo "<div class='col-3 col-sm-2'>";
					echo "<i data-toggle='tooltip' data-placement='top' title='Kollaborativ' class='fa-fw ".getSetting('COLL',$sql_categories_list_row['COLL'])."'></i>";
					echo "<i data-toggle='tooltip' data-placement='top' title='Themen' class='fa-fw ".getSetting('ALLOW_TOPICS',$sql_categories_list_row['ALLOW_TOPICS'])."'></i>";
					echo "<i data-toggle='tooltip' data-placement='top' title='Sichtbarkeit' class='fa-fw ".getSetting('CATEGORY_VISIBLE',$sql_categories_list_row['VISIBLE'])."'></i>";
					echo $max_entries;				
				echo "</div>";
			echo "</div>";
			echo "<hr>";
			echo "<div class='row'>";
				echo "<div class='col-md-12 cat-content' id='edit".$sql_categories_list_row['ID_CATEGORY']."' style='display:none'>";

				echo "</div>";
			echo "</div>";
		}
	echo "<script>
		$(\"#show\").on(\"click\", function(){
			$(\"#category_list\").toggle(\"slow\");
		});
	</script>";
}

//Kanban-View

function kanban(){
	echo "<div class='container' style='padding: 0px;'>";
	echo "<a href='javascript:void(0);' style='font-size: 1.5rem;' id='show'><i class='fas fa-bars fa-fw'></i></a><div style='display:inline-flex; font-size: 1.5rem;'>Kategorien</div><span id='collapse_icon' style='float:right; cursor:pointer; font-size: 0.7rem'><i class='fas fa-chevron-circle-up fa-2x collapse_me'></i><i class='fas fa-chevron-circle-down fa-2x expand_me fa-fw'></i></span>";
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
		$sql_categories_list = "SELECT * FROM ".DB_PREFIX."view_episode_categories WHERE CATEGORIES_ID_PODCAST = ".$_SESSION['podcast']." AND ID_EPISODE = ".$_SESSION['cur_episode']." ORDER BY REIHENF, DESCR";
		$sql_categories_list_result = mysqli_query($con, $sql_categories_list);

		if(mysqli_num_rows($sql_categories_list_result) == 0)
			{
				echo "<p class='lead'>Es wurden noch keine Kategorien angelegt!</p>";
				echo "</div";
				return;
			}
		while($sql_categories_list_rows = mysqli_fetch_assoc($sql_categories_list_result))
		{
			
			if ($sql_categories_list_rows['MAX_ENTRIES'] >= 1)
				{
					if ($sql_categories_list_rows['MAX_ENTRIES'] == 1)
						{
							$entries = "Eintrag";
						}
					else
						{
							$entries = "Einträge";
						}
					$max_entries = "<i data-toggle='tooltip' data-placement='top' title='Max. ".$sql_categories_list_rows['MAX_ENTRIES']." ".$entries."' class='fa-fw ".getSetting('MAX_ENTRIES',0)."'></i>";
				}
			else 
				{
					$max_entries = "";
				}
				
			echo "<div data-toggle='collapse' href='#collapse_category_".$sql_categories_list_rows['ID_CATEGORY']."' role='button' aria-expanded='false' aria-controls='collapse_category_".$sql_categories_list_rows['ID_CATEGORY']."'class='row load_content' category_ID ='".$sql_categories_list_rows['ID_CATEGORY']."'>";
				echo "<div class='col-9 col-sm-10 col-xl-11'>";
					echo "<div class='btn-select-cat'><h5 style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0px' ><span style='margin-right: 3px; margin-top: -0.3rem; vertical-align: middle; width: 26px;' class='badge badge-secondary'></span>".$sql_categories_list_rows['DESCR']."</h5></div>";
				echo "</div>";
				echo "<div class='col-3 col-sm-2 col-xl-1'>";
 					echo "<i data-toggle='tooltip' data-placement='top' title='Kollaborativ' class='fa-fw ".getSetting('COLL',$sql_categories_list_rows['COLL'])."'></i>";
					echo "<i data-toggle='tooltip' data-placement='top' title='Sichtbarkeit' class='fa-fw ".getSetting('CATEGORY_VISIBLE',$sql_categories_list_rows['VISIBLE'])."'></i>";
					echo $max_entries;
				echo "</div>";
			echo "</div>";
			echo "<hr class='seperator'>";
			
			
/* 			echo "<a data-toggle='collapse' href='#collapse_category_".$sql_categories_list_rows['ID_CATEGORY']."' role='button' aria-expanded='false' aria-controls='collapse_category_".$sql_categories_list_rows['ID_CATEGORY']."'>";
				echo $sql_categories_list_rows['DESCR']."<br>";
			echo "</a>"; */
		
		echo "<div class='collapse collapse-outer' id='collapse_category_".$sql_categories_list_rows['ID_CATEGORY']."' style='margin-top: 15px;'>";
		echo "<ul class='timeline' style='margin-bottom: 10px'>";
 		    echo "<li>";
				    echo "<div class='timeline-badge success' style='margin-top: -15px;'><i class='fas fa-plus fa-fw'></i></div>";
			echo "</li>"; 
			echo "</ul>"; 
			
		echo "<ul class='timeline kanban_sortable'>";
		global $con;
		$sql_kanban_entries = "SELECT ".DB_PREFIX."users.USERNAME, ".DB_PREFIX."users.NAME_SHOW, ".DB_PREFIX."links.ID AS ID, ".DB_PREFIX."links.URL AS URL, ".DB_PREFIX."links.ID_USER AS ID_USER, ".DB_PREFIX."links.ID_EPISODE, ".DB_PREFIX."links.ID_CATEGORY, ".DB_PREFIX."links.DESCR, NULL AS IS_TOPIC, ".DB_PREFIX."links.REIHENF, ".DB_PREFIX."links.DONE, ".DB_PREFIX."links.DONE_TS, ".DB_PREFIX."episoden.DONE AS EPISODE_DONE from ".DB_PREFIX."links JOIN ".DB_PREFIX."users on ".DB_PREFIX."users.ID = ".DB_PREFIX."links.ID_USER JOIN ".DB_PREFIX."episoden on ".DB_PREFIX."episoden.ID = ".DB_PREFIX."links.ID_EPISODE WHERE ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_CATEGORY = ".$sql_categories_list_rows['ID_CATEGORY']." AND ID_TOPIC IS NULL UNION ALL SELECT ".DB_PREFIX."users.USERNAME, ".DB_PREFIX."users.NAME_SHOW, ".DB_PREFIX."topics.ID AS ID, NULL AS URL, ".DB_PREFIX."topics.ID_USER AS ID_USER, ".DB_PREFIX."topics.ID_EPISODE, ".DB_PREFIX."topics.ID_CATEGORY, ".DB_PREFIX."topics.DESCR, 1 AS IS_TOPIC, ".DB_PREFIX."topics.REIHENF, ".DB_PREFIX."topics.DONE, ".DB_PREFIX."topics.DONE_TS, ".DB_PREFIX."episoden.DONE AS EPISODE_DONE from ".DB_PREFIX."topics JOIN ".DB_PREFIX."users on ".DB_PREFIX."users.ID = ".DB_PREFIX."topics.ID_USER JOIN ".DB_PREFIX."episoden on ".DB_PREFIX."episoden.ID = ".DB_PREFIX."topics.ID_EPISODE WHERE ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_CATEGORY = ".$sql_categories_list_rows['ID_CATEGORY']." ORDER BY REIHENF, ID ASC";
		$sql_kanban_entries_result = mysqli_query($con, $sql_kanban_entries);
 		while($sql_kanban_entries_row = mysqli_fetch_assoc($sql_kanban_entries_result))
 		{
							
						if ($sql_kanban_entries_row['DONE'] == 1  && $sql_kanban_entries_row['EPISODE_DONE'] == 0)
							{
								$btn = "btn-success";
								$done = "";
								$entry_done = "<i class='far fa-check-circle'></i>";
								$entry_done2 = "<style='background-color: rgba(0, 209, 0, 0.3)'>";
							}
						else if ($sql_kanban_entries_row['EPISODE_DONE'] == 1 && $sql_kanban_entries_row['DONE'] == 1)
							{
								$btn = "btn-success";
								$done ="disabled";		
								$entry_done = "<i class='far fa-check-circle'></i>";
								$entry_done2 = "<style='background-color: rgba(0, 209, 0, 0.3)'>";
							}																
						else if ($sql_kanban_entries_row['EPISODE_DONE'] == 1 && $sql_kanban_entries_row['DONE'] == 0)
							{
								$btn = "btn-outline-success";
								$done ="disabled";		
								$entry_done = "<i class='far fa-check-circle'></i>";
								$entry_done2 = "<style='background-color: rgba(0, 209, 0, 0.3)'>";
							}
						else
							{
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
							
			if(empty($sql_kanban_entries_row['USERNAME']))
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
			}
			else
			{
				$editable = "";
			}
			if($sql_kanban_entries_row['IS_TOPIC'] == 1)
				{
					$class = "class='timeline-inverted'";
					$icon = "<i class='fas fa-bars fa-fw'></i>";
					$icon_color = " info";
					$title = "<div class='timeline-heading'>";
					$title .= "<h6 class='".$editable." timeline-title' table='topics' data-name='DESCR' data-type='text' data-pk='".$sql_kanban_entries_row['ID']."' style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>".$sql_kanban_entries_row['DESCR']."</h6>";
					$title .= "</div>";
					$type="topics";
				}
			else
				{
					$class = "class=''";
					$icon = "<i class='fas fa-link fa-fw'></i>";
					$icon_color = " warning";
					$title = "";
					$type="links";
				}
			
      echo "<li ".$class.">";
        echo "<div class='timeline-badge timeline-handle".$icon_color."'>".$icon."</div>";
        echo "<div class='timeline-panel'>";
				echo " <small class='text-muted'>".$user."</small><span style='margin-left: 10px; color: green' class='check_icon_".$type."_".$sql_kanban_entries_row['ID']."'>".$entry_done."</span>";;
				if($sql_kanban_entries_row['ID_USER'] != $_SESSION['userid'])
				{
					$actions = "";
				}
				else
				{ 
					$actions = "<a class='tooltipster' style='float:right' data-tooltip-content='#tooltip_content".$type."_".$sql_kanban_entries_row['ID']."'>";
					$actions .= "<i class='fas fa-angle-double-up'></i>";
					$actions .= "</a>";
				}
				echo $actions;
				
				if((getSettingCat('VISIBLE', $sql_categories_list_rows['ID_CATEGORY']) == 0) && ($sql_kanban_entries_row['ID_USER'] != $_SESSION['userid']) && ($sql_kanban_entries_row['DONE'] != 1))
					{
						echo "<div class='timeline-heading'>";
							echo "<h6 class='timeline-title' style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>Gesperrt</h6>";
						echo "</div>";		
					}
					else{
				echo $title;
          echo "<div class='timeline-body'>";
		  if($sql_kanban_entries_row['IS_TOPIC'] == 1)
		  {
				echo "<div class='tooltip_templates' style='display:none'>";
					echo "<span id='tooltip_content".$type."_".$sql_kanban_entries_row['ID']."'>";
								echo "<div class='row' style='margin: 0px;'>";
									echo "<div class='col-4' style='padding:1px'>";
										echo "<button type='button' ".$edit." ".$done." class='btn ".$btn." btn-block check_link' id='check_topics".$sql_kanban_entries_row['ID']."' onclick='check_link(".$sql_kanban_entries_row['ID'].", \"topics\")' data-name='DONE' data-checked='".$sql_kanban_entries_row['DONE']."'>";
											echo "<i class='far fa-check-circle'></i>";
										echo "</button>";									
									echo "</div>";
 									echo "<div class='col-4' style='padding:1px'>";
										echo "<button type='button' edit_type='topics' edit_id='".$sql_kanban_entries_row['ID']."' class='btn btn-outline-tertiary btn-block edit_entry' id='topic_edit_button_".$sql_kanban_entries_row['ID']."'><i class='fas fa-edit fa-fw'></i></button>";
									echo "</div>";
/* 									echo "<div class='col-6' style='padding:1px'>";
										echo "<button type='button' class='btn btn-outline-notice btn-block'><i class='far fa-comment fa-fw'></i></button>";
									echo "</div>"; */
									echo "<div class='col-4' style='padding:1px'>";
										echo "<button type='button' class='btn btn-outline-danger btn-block delete_entry' disabled id='delete_topic".$sql_kanban_entries_row['ID']."' table='topics' option='topics' data-pk='".$sql_kanban_entries_row['ID']."'><i class='far fa-times-circle fa-fw'></i></button>";
									echo "</div>"; 
								echo "</div>";
			echo "</span>"; 
				echo "</div>";
			echo "<span class='collapse-inner' style='cursor:pointer; color:#009688' id_topic='".$sql_kanban_entries_row['ID']."'>";
				echo "<i class='rotate-arrow fas fa-angle-double-down fa-2x expand_icon_".$sql_kanban_entries_row['ID']."'></i>";
			echo "</span>";
				echo "<div class='collapse collapse-inner-content' id='collapse_topic_".$sql_kanban_entries_row['ID']."' topic='".$sql_kanban_entries_row['ID']."'>";
				echo "<ul class='topic_links sortable_topic_links'>";
			$select_topic_links = "SELECT * FROM ".DB_PREFIX."links WHERE ID_TOPIC = ".$sql_kanban_entries_row['ID'];
			$select_topic_links_result = mysqli_query($con, $select_topic_links);
			while($select_topic_links_rows = mysqli_fetch_assoc($select_topic_links_result))
			{
				echo "<li class='topic_links_item'>";
				echo "<div class='row centered-items' style='padding: 0px 14px;'>";
					echo "<div class='col-12 col-xl-8' style='padding:1px;'>";
						echo "<div class='link_icon link_icon_".$sql_kanban_entries_row['ID']."' id='".$type."_".$sql_kanban_entries_row['ID']."'><i class='fas fa-link fa-fw'></i></div>";
							echo "<div class='lead link_topic_".$sql_kanban_entries_row['ID']."' table='links' data-name='DESCR' data-type='text' data-pk='".$select_topic_links_rows['ID']."' style='margin-bottom: 0px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>".$select_topic_links_rows['DESCR']."</div>";
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
			echo "<hr style='margin-bottom: 0px;margin-top: 10px'>";

				echo "</div>";
		  }
		  else
		  {
				echo "<div class='tooltip_templates' style='display:none'>";
					echo "<span id='tooltip_content".$type."_".$sql_kanban_entries_row['ID']."'>";
								echo "<div class='row' style='margin: 0px;'>";
									echo "<div class='col-4' style='padding:1px'>";
										echo "<button type='button'  ".$edit." ".$done." class='btn ".$btn." btn-block check_link' id='check_links".$sql_kanban_entries_row['ID']."' onclick='check_link(".$sql_kanban_entries_row['ID'].", \"links\")' data-name='DONE' data-checked='".$sql_kanban_entries_row['DONE']."'>";
											echo "<i class='far fa-check-circle'></i>";
										echo "</button>";									
									echo "</div>";
 									echo "<div class='col-4' style='padding:1px'>";
										echo "<button type='button' edit_type='links' edit_id='".$sql_kanban_entries_row['ID']."' class='btn btn-outline-tertiary btn-block edit_entry' id='link_edit_button_".$sql_kanban_entries_row['ID']."'><i class='fas fa-edit fa-fw'></i></button>";
									echo "</div>";
/* 									echo "<div class='col-6' style='padding:1px'>";
										echo "<button type='button' class='btn btn-outline-notice btn-block'><i class='far fa-comment fa-fw'></i></button>";
									echo "</div>"; */
									echo "<div class='col-4' style='padding:1px'>";
										echo "<button type='button' id='delete_link".$sql_kanban_entries_row['ID']."' disabled table='links' option='link' data-pk='".$sql_kanban_entries_row['ID']."' class='btn btn-outline-danger btn-block delete_entry'><i class='far fa-times-circle fa-fw'></i></button>";
									echo "</div>"; 
								echo "</div>";
			echo "</span>"; 
				echo "</div>";

			echo "<div class='row' style='padding: 0px 14px 0px 14px; margin-top: 15px'>";
				echo "<ul class='topic_links'>";
				echo "<li class='topic_links_item'>";
				echo "<div class='row centered-items' style='padding: 0px 14px;'>";
					echo "<div class='col-12 col-xl-8 link_title' style='padding:1px;'>";
						echo "<div class='link_icon link_icon_".$sql_kanban_entries_row['ID']."' id='".$type."_".$sql_kanban_entries_row['ID']."'><i class='fas fa-link fa-fw'></i></div><p class='lead edit_link_".$sql_kanban_entries_row['ID']."' table='links' data-name='DESCR' data-type='text' data-pk='".$sql_kanban_entries_row['ID']."' style='margin-bottom: 0px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>".$sql_kanban_entries_row['DESCR']."</p>";
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
		  }
          echo "</div>";
        echo "</div>";
      echo "</li>";
		}
		}  
		
    
echo "</ul>";
echo "</div>";
		}
echo "</div>";
	echo "<script>
		  $( function() {
			$( \".kanban_sortable\" ).sortable({ 
				handle: '.timeline-handle',
				connectWith: '.kanban_sortable'
				
});
		  } );		
	</script>";	
	echo "<script>
		  $( function() {
			$( \".sortable_topic_links\" ).sortable({ handle: '.link_icon',   connectWith: '.sortable_topic_links' });
		  } );		
	</script>";	

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

//Eigene Themen/Beiträge		
function topic_edit_list($id_user){
global $con;
	echo "<div class='tile'>";
		echo "<div class='tile-title'>";
			echo "Beiträge und Themen";
		echo "</div>";
		echo "<hr>";
		echo "<div class='tile-body'>";
		if(empty($_SESSION['cur_episode']))
			{
				echo "<p class='lead'>Bitte wähle eine Episode aus!</p>";
				echo "</div";
				echo "</div";
				return;
			}
			if (!empty($_SESSION['cur_episode']) && (getPermission($_SESSION['userid']) > 1 && !userInEpisode($_SESSION['userid'], $_SESSION['cur_episode'])))
			{
				echo "<p class='lead'>Du bist dieser Episode nicht zugewiesen!</p>";
				echo "</div";
				echo "</div";
				return;					
			}
			echo "<ul class='nav nav-pills mb-3' id='pills-tab' role='tablist'>";
				echo " <li class='nav-item'>";
					echo "<a class='btn btn-outline-secondary active btn-block' style='padding: 8px 16px 8px 16px' id='tab' data-toggle='pill' href='#pills-links' role='tab' aria-controls='pills-links' aria-selected='true'>Beiträge</a>";
				echo "</li>";
				echo "<li class='nav-item'>";
					echo "<a class='btn btn-outline-secondary btn-block' style='margin-left: 5px; padding: 8px 16px 8px 16px' id='tab' data-toggle='pill' href='#pills-topics' role='tab' aria-controls='pills-topics' aria-selected='false'>Themen</a>";
				echo "</li>";
			echo "</ul>";
		echo "</div>";
	echo "</div>";

	echo "<div class='tab-content' id='pills-tabContent'>";
		echo "<div class='tab-pane show active' id='pills-links' role='tabpanel' aria-labelledby='pills-links-tab'>";
			echo "<div class='tile' style='text-align:right; padding: 5px' id='filter'>";
				echo "<div class='dropdown'>";
					echo "<button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
						echo "<i class='fas fa-filter'></i>";
					echo "</button>";
					echo "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
						echo "<div class='form-group form-check' style='margin-bottom: 0px'>";
						$sql_get_cat = "SELECT * FROM ".DB_PREFIX."categories WHERE ALLOW_TOPICS = 0 AND ID_PODCAST =".$_SESSION['podcast'];
						$sql_get_cat_result = mysqli_query($con, $sql_get_cat);
						while($sql_get_cat_row = mysqli_fetch_assoc($sql_get_cat_result))
							{
								echo "<div class='dropdown-item' ><input type='checkbox' class='form-check-input' cat='cat".$sql_get_cat_row['ID']."'>";
									echo "<label class='form-check-label'>".$sql_get_cat_row['DESCR']."</label>";
								echo "</div>";					
							}
						echo "</div>";
					echo "</div>";
				echo "</div>";

				echo "<script>
					var checkboxes = $(\"#filter input:checkbox\");
					checkboxes.prop('checked', true);	

					$(checkboxes).on('click', function(){
						var cat = $(this).attr('cat');
						var rows = $(\"#links_user\").find(\".\"+cat);
						$(rows).toggle();
					});
				</script>";
			echo "</div>";

			echo "<div class='row' id='links_user'>";
			$sql_get_links = "SELECT ".DB_PREFIX."links.* FROM ".DB_PREFIX."links join ".DB_PREFIX."categories on ".DB_PREFIX."categories.ID = ".DB_PREFIX."links.ID_CATEGORY WHERE ID_USER = ".$id_user." AND ".DB_PREFIX."links.ID_PODCAST = ".$_SESSION['podcast']." AND ID_EPISODE=".$_SESSION['cur_episode']." ORDER BY ".DB_PREFIX."categories.REIHENF";
			$sql_get_links_result = mysqli_query($con, $sql_get_links);
			while ($sql_get_links_rows = mysqli_fetch_assoc($sql_get_links_result))
				{
					if($sql_get_links_rows['ID_TOPIC'] === NULL)
						{
							echo "<div class='col-xl-6 col-12 cat".$sql_get_links_rows['ID_CATEGORY']."' >";
								if ($sql_get_links_rows['DONE'] == 1)
									{
										$btn = "btn-success";
										$done = "";
									}
								else
									{
										$btn = "btn-outline-success";
										$done = "";								
									}
									echo "<div class='tile'>";
										echo "<div class='tile-title' style='overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; white-space: nowrap;'>";
											echo "<i style='margin-left: 4px;' class='fas fa-pencil-alt fa-xs'></i> ";
											echo "<a style='border: none; color:black; ' class='update' href='#' id='descr".$sql_get_links_rows['ID']."' table='links' data-name='DESCR' data-type='text' data-pk='".$sql_get_links_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung'>".$sql_get_links_rows['DESCR']."</a>";
										echo "</div>";
									echo "<hr>";
									echo "<div class='tile-body' style='padding: 10px 20px 10px 20px'>";
										echo "<div class='row'>";
											echo "<div class='col-12' >";
												echo "<div style='overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; white-space: nowrap; margin-bottom: 10px;'>";
													echo "<i class='fas fa-pencil-alt fa-xs' data-toggle='tooltip' data-placement='top' title='".$sql_get_links_rows['URL']."'></i> ";
													echo "<a style='border: none; color:black; ' class=' update' href='#' id='url".$sql_get_links_rows['ID']."' table='links' data-name='URL' data-type='text' data-pk='".$sql_get_links_rows['ID']."' data-url='inc/update.php' beschr='URL'>".$sql_get_links_rows['URL']."</a>";
												echo "</div>";
											echo "</div>";
											echo "<div class='col-12' style='margin-bottom: 10px;'>";
												echo "<select data-url='inc/update.php' data-name='ID_CATEGORY' class='form-control update_cat' style='padding: 0px;' table='links' data-pk='".$sql_get_links_rows['ID']."' id='category'>";
												$sql_get_cat = "SELECT * FROM ".DB_PREFIX."view_episode_categories WHERE ALLOW_TOPICS = 0 AND ID_EPISODE = ".$_SESSION['cur_episode']." AND EPISODE_ID_PODCAST = ".$_SESSION['podcast'];
												$sql_get_cat_result = mysqli_query($con, $sql_get_cat);
													while($sql_get_cat_rows = mysqli_fetch_assoc($sql_get_cat_result))
													{
														echo "<option ";
														if ($sql_get_cat_rows['ID_CATEGORY'] === $sql_get_links_rows['ID_CATEGORY'])
															{
																echo "selected ";
															}
														echo "value=".$sql_get_cat_rows['ID_CATEGORY'].">"; 
														echo $sql_get_cat_rows['DESCR'];
														echo "</option>";
													}	
												echo "</select>";
											echo "</div>";
											echo "<div class='col-12' style='margin-bottom: 10px;'>";
												echo "<div class='row' style='padding-left: 15px; padding-right: 15px;'>";
												if($sql_get_links_rows['URL'] == NULL || $sql_get_links_rows['URL'] == '')
													{
														echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
															echo "<button disabled type='button' class='btn btn-warning btn-block'>";
																echo "<i class='fas fa-external-link-alt fa-fw'></i>";
															echo "</button>";
														echo "</div>";
														echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
															echo "<button disabled class='btn btn-info btn-block'>";
																echo "<i style='color:black' class='far fa-copy fa-fw'></i>";
															echo "</button>";
														echo "</div>";
													}
												else
													{
														$fund_url = $sql_get_links_rows['URL'];
														$pos = "http";
														if (strpos($fund_url, $pos) === false)
															{
																$base = "http://".$fund_url;
															}
														else
															{
																$base = $fund_url;
															}
														echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
															echo "<button onclick='window.open(\"".$base."\");' type='button' class='btn btn-warning btn-block'>";
																echo "<i class='fas fa-external-link-alt fa-fw'></i>";
															echo "</button>";
														echo "</div>";
														echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
															echo "<div data-clipboard-text='".$sql_get_links_rows['URL']."' class='btn".$sql_get_links_rows['ID']." btn btn-info btn-block clipboard'>";
																echo "<i style='color:black' class='far fa-copy fa-fw'></i>";
															echo "</div>";
														echo "</div>";
														echo "<script>
															var clip = new ClipboardJS('.btn".$sql_get_links_rows['ID']."');
														</script>";
													}	

													echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
														echo "<button type='button' ".$done." class='btn ".$btn." btn-block check_link' id='check_links".$sql_get_links_rows['ID']."' onclick='check_link(".$sql_get_links_rows['ID'].", \"links\")' data-name='DONE' data-checked='".$sql_get_links_rows['DONE']."'>";
															echo "<i class='far fa-check-circle'></i>";
														echo "</button>";
													echo "</div>";
												echo "</div>";
											echo "</div>";
											echo "<div class='col-12' style='margin-bottom: 10px;'>";
												if($sql_get_links_rows['INFO'] == NULL || $sql_get_links_rows['INFO'] == '')
													{
														$has_info = "-outline-";
													}
													else
													{
														$has_info = "-";											
													}

												echo "<button class='btn btn".$has_info."notice btn-block' type='button' data-toggle='collapse' data-target='#collapseExample".$sql_get_links_rows['ID']."' aria-expanded='false' aria-controls='collapseExample".$sql_get_links_rows['ID']."'>";
													echo "Notizen";
												echo "</button>";
												echo "<div class='collapse' id='collapseExample".$sql_get_links_rows['ID']."'>";
													echo "<div style='margin-top:10px'>";
														echo "<textarea data-name='INFO' id='textarea_links".$sql_get_links_rows['ID']."' class='update_notizen' table='links' data-pk='".$sql_get_links_rows['ID']."' name='textarea_links".$sql_get_links_rows['ID']."'>";
															echo $sql_get_links_rows['INFO'];
														echo "</textarea>";
													echo "</div>";
													echo "<div style='margin-top:10px'>";	
														echo "<button class='btn btn-outline-success btn-block' id='update_notizen_links".$sql_get_links_rows['ID']."' type='button' ><i class='fas fa-save'></i> Notizen Speichern</button>";
														echo "<script>
															CKEDITOR.replace( 'textarea_links".$sql_get_links_rows['ID']."');
															$('#update_notizen_links".$sql_get_links_rows['ID']."').on('click', function(e) {
																var pk = $(\"#textarea_links".$sql_get_links_rows['ID']."\").attr(\"data-pk\")
																var name = $(\"#textarea_links".$sql_get_links_rows['ID']."\").attr(\"data-name\")
																var table = $(\"#textarea_links".$sql_get_links_rows['ID']."\").attr(\"table\")
																var value= CKEDITOR.instances['textarea_links".$sql_get_links_rows['ID']."'].getData();	
																$.ajax({
																	url: 'inc/update.php',
																	type: 'POST',
																	data: {name:name, pk:pk, value:value, table:table},
																	success: function(data){
																	$.ajax({
																		url: 'inc/update.php',
																		type: 'POST',
																		data: {name:name, pk:pk, value:value, table:table},
																		success: function(data){
																		console.log(data);
																		$.gritter.add({
																			title: 'Bearbeiten ok!',
																			text: 'Die Änderungen wurden gespeichert!',
																			image: '../images/confirm.png',
																			time: '1000'
																		});		
																		}
																	});
																	console.log(data);
																	}
																});
															});
														</script>";					
													echo "</div>";
												echo "</div>";
											echo "</div>";
											echo "<div class='col-12'>";
												echo "<button class='btn btn-danger btn-block delete_entry' id='delete_link".$sql_get_links_rows['ID']."' table='links' option='link' data-pk='".$sql_get_links_rows['ID']."'><i class='far fa-times-circle fa-fw'></i> Beitrag löschen</button>";
											echo "</div>";
										echo "</div>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
						}
				}
			echo "</div>";
		echo "</div>";

		echo "<div class='tab-pane' id='pills-topics' role='tabpanel' aria-labelledby='pills-topics-tab'>";
			echo "<div class='tile' style='text-align:right; padding: 5px' id='filter_topics'>";
				echo "<div class='dropdown'>";
					echo "<button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
						echo "<i class='fas fa-filter'></i>";
					echo "</button>";
					echo "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
						echo "<div class='form-group form-check' style='margin-bottom: 0px'>";
						$sql_get_cat = "SELECT * FROM ".DB_PREFIX."categories WHERE ALLOW_TOPICS = 1 AND ID_PODCAST =".$_SESSION['podcast'];
						$sql_get_cat_result = mysqli_query($con, $sql_get_cat);
						while($sql_get_cat_row = mysqli_fetch_assoc($sql_get_cat_result))
							{
								echo "<div class='dropdown-item' ><input type='checkbox' class='form-check-input' cat='cat".$sql_get_cat_row['ID']."'>";
									echo "<label class='form-check-label'>".$sql_get_cat_row['DESCR']."</label>";
								echo "</div>";					
							}				
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "<script>
			var checkboxes_topics = $(\"#filter_topics input:checkbox\");
			checkboxes_topics.prop('checked', true);	
			$(checkboxes_topics).on('click', function(){
				var cat_topics = $(this).attr('cat');
				var rows_topics = $(\"#topics_user\").find(\".\"+cat_topics);
				$(rows_topics).toggle();
			});

			</script>";
			echo "<div class='row' id='topics_user'>";
				echo "<div class='col-md-12'>";
					$sql_get_topics = "SELECT * FROM ".DB_PREFIX."topics WHERE (ID_USER = ".$id_user." OR ID_USER IS NULL) AND ID_PODCAST = ".$_SESSION['podcast']." AND ID_EPISODE = ".$_SESSION['cur_episode'];
					$sql_get_topics_result = mysqli_query($con, $sql_get_topics);
					while ($sql_get_topics_rows = mysqli_fetch_assoc($sql_get_topics_result))
						{
							if ($sql_get_topics_rows['DONE'] == 1)
								{
									$btn = "btn-success";
									$done = "";
								}
							else
								{
									$btn = "btn-outline-success";
									$done = "";								
								}
							echo "<div class='tile cat".$sql_get_topics_rows['ID_CATEGORY']."' >";
								echo "<div class='tile-title'>";
									echo "<div class='row'>";
										echo "<div class='col-md-8 col-sm-12' style='overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; white-space: nowrap;'>";
											echo "<i style='margin-left: 4px;' class='fas fa-pencil-alt fa-xs'></i> ";
											echo "<a style='border: none; color:black; ' class='update' href='#' id='descr".$sql_get_topics_rows['ID']."' table='topics' data-name='DESCR' data-type='text' data-pk='".$sql_get_topics_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung'>".$sql_get_topics_rows['DESCR']."</a>";
									echo "</div>";
									echo "<div class='col-md-4 col-sm-12 '>";
										if($sql_get_topics_rows['INFO'] == NULL || $sql_get_topics_rows['INFO'] == '')
											{
												$has_info = "-outline-";
											}
										else
											{
												$has_info = "-";											
											}
										echo "<button class='btn btn".$has_info."notice btn-block' type='button' data-toggle='collapse' data-target='#collapseExample_topics".$sql_get_topics_rows['ID']."' aria-expanded='false' aria-controls='collapseExample_topics".$sql_get_topics_rows['ID']."'>";
											echo "Notizen";
										echo "</button>";			
									echo "</div>";
									echo "<div class='col-12'>";
										echo "<div class='collapse' id='collapseExample_topics".$sql_get_topics_rows['ID']."'>";
											echo "<div style='margin-top:10px;'>";
												echo "<textarea data-name='INFO' id='notizen_topics".$sql_get_topics_rows['ID']."' class='update_notizen' table='topics' data-pk='".$sql_get_topics_rows['ID']."' name='notizen_topics".$sql_get_topics_rows['ID']."'>";
													echo $sql_get_topics_rows['INFO'];
												echo "</textarea>";
											echo "</div>";
											echo "<div style='margin-top:10px'>";	
												echo "<button class='btn btn-outline-success btn-block' id='update_notizen_topics".$sql_get_topics_rows['ID']."' type='button' ><i class='fas fa-save'></i> Notizen Speichern</button>";
												echo "<script>
													CKEDITOR.replace( 'notizen_topics".$sql_get_topics_rows['ID']."');
													$('#update_notizen_topics".$sql_get_topics_rows['ID']."').on('click', function(e) {
														var pk = $(\"#notizen_topics".$sql_get_topics_rows['ID']."\").attr(\"data-pk\")
														var name = $(\"#notizen_topics".$sql_get_topics_rows['ID']."\").attr(\"data-name\")
														var table = $(\"#notizen_topics".$sql_get_topics_rows['ID']."\").attr(\"table\")
														var value= CKEDITOR.instances['notizen_topics".$sql_get_topics_rows['ID']."'].getData();	
														var collapse_info = $(\"#collapseExample_topics".$sql_get_topics_rows['ID']."\")
														$.ajax({
															url: 'inc/update.php',
															type: 'POST',
															data: {name:name, pk:pk, value:value, table:table},
															success: function(data){
															console.log(data);
															$.gritter.add({
																title: 'Bearbeiten ok!',
																text: 'Die Änderungen wurden gespeichert!',
																image: '../images/confirm.png',
																time: '1000'
															});		
															}
														});
													});
												</script>";				
											echo "</div>";
										echo "</div>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
							echo "<hr>";
							echo "<div class='tile-body' style='padding: 10px; border-radius: 10px; background-color: rgba(150, 150, 150,0.1)' id='topic_entries".$sql_get_topics_rows['ID']."'>";
							$sql_get_topic_links = "SELECT * FROM ".DB_PREFIX."links WHERE ID_TOPIC = ".$sql_get_topics_rows['ID']." AND ID_PODCAST = ".$_SESSION['podcast']." AND ID_USER = ".$id_user." AND ID_EPISODE = ".$_SESSION['cur_episode'];
							$sql_get_topic_links_result = mysqli_query($con, $sql_get_topic_links);
							while ($sql_get_topic_links_rows = mysqli_fetch_assoc($sql_get_topic_links_result))
								{
									if(empty($sql_get_topic_links_rows['DONE'] ))
										{	
											$disabled = "";
										}
									else
										{
											$disabled = "disabled";
										}
									echo "<div class='row'>";
										echo "<div class='col-lg-5 col-sm-12' style='overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; white-space: nowrap;'>";
											echo "<i style='margin-left: 4px;' class='fas fa-pencil-alt fa-xs'></i> ";
											echo "<a style='border: none; color:black; ' class='update' href='#' id='descr".$sql_get_topic_links_rows['ID']."' table='links' data-name='DESCR' data-type='text' data-pk='".$sql_get_topic_links_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung'>".$sql_get_topic_links_rows['DESCR']."</a>";
											echo "<p></p>";
											echo "<i style='margin-left: 4px;' class='fas fa-pencil-alt fa-xs'></i> ";
											echo "<a style='border: none; color:black; ' class=' update' href='#' id='url".$sql_get_topic_links_rows['ID']."' table='links' data-name='URL' data-type='text' data-pk='".$sql_get_topic_links_rows['ID']."' data-url='inc/update.php' beschr='URL'>".$sql_get_topic_links_rows['URL']."</a>";
										echo "</div>";
										echo "<div class='col-lg-4 col-sm-12'>";
											echo "<div class='form-group'>";
												echo "<select ".$disabled." data-url='inc/update.php' data-name='ID_TOPIC' class='form-control update_cat' table='links' style='padding: 0px;' data-pk='".$sql_get_topic_links_rows['ID']."' id='category'>";
												$sql_get_cat_topic_links = "SELECT DISTINCT * FROM ".DB_PREFIX."view_topics WHERE (TOPICS_ID_USER = ".$id_user." OR CATEGORIES_VISIBLE = 1) AND EPISODEN_ID = ".$_SESSION['cur_episode'];
												$sql_get_cat_topic_links_result = mysqli_query($con, $sql_get_cat_topic_links);
												while($sql_get_cat_topic_links_rows = mysqli_fetch_assoc($sql_get_cat_topic_links_result))
													{
														echo "<option ";
														if ($sql_get_topic_links_rows['ID_TOPIC'] === $sql_get_cat_topic_links_rows['TOPICS_ID'])
															{
																echo "selected ";
															}
														echo "value=".$sql_get_cat_topic_links_rows['TOPICS_ID'].">"; 
															echo $sql_get_cat_topic_links_rows['TOPICS_DESCR'];
														echo "</option>";
													}	
												echo "</select>";
											echo "</div>";
										echo "</div>";
										echo "<div class='col-lg-3 col-sm-12'>";
											echo "<div class='row' style='padding: 0px 14px 5px 14px'>";
											if($sql_get_topic_links_rows['URL'] == NULL || $sql_get_topic_links_rows['URL'] == '')
												{
													echo "<div class='col-6' style='padding:1px'>";
														echo "<button disabled type='button' class='btn btn-warning btn-block'>";
															echo "<i class='fas fa-external-link-alt fa-fw'></i>";
														echo "</button>";
													echo "</div>";
													echo "<div class='col-6' style='padding:1px'>";
														echo "<button disabled class='btn btn-info btn-block'>";
															echo "<i style='color:black' class='far fa-copy fa-fw'></i>";
														echo "</button>";
													echo "</div>";
												}
											else
												{
													$fund_url = $sql_get_topic_links_rows['URL'];
													$pos = "http";
													if (strpos($fund_url, $pos) === false)
														{
															$base = "http://".$fund_url;
														}
													else
														{
															$base = $fund_url;
														}

													echo "<div class='col-6' style='padding:1px'>";
														echo "<button onclick='window.open(\"".$base."\");' type='button' class='btn btn-warning btn-block'>";
															echo "<i class='fas fa-external-link-alt fa-fw'></i>";
														echo "</button>";
													echo "</div>";
													echo "<div class='col-6' style='padding:1px'>";
														echo "<div data-clipboard-text='".$sql_get_topic_links_rows['URL']."' class='btn".$sql_get_links_rows['ID']." btn btn-info btn-block clipboard'>";
															echo "<i style='color:black' class='far fa-copy fa-fw'></i>";
														echo "</div>";
													echo "</div>";
													echo "<script>
														var clip = new ClipboardJS('.btn".$sql_get_topic_links_rows['ID']."');
													</script>";
												}													
											echo "</div>";
											echo "<div class='row'>";
												echo "<div class='col-12'>";
													echo "<button class='btn btn-danger btn-block delete_entry' id='delete_topic_link".$sql_get_topic_links_rows['ID']."' table='links' option='link' data-pk='".$sql_get_topic_links_rows['ID']."'><i class='far fa-times-circle fa-fw'></i> Beitrag löschen</button>";
												echo "</div>";
											echo "</div>";
										echo "</div>";
									echo "</div>";
									echo "<hr>";
								}	
							echo "</div>";

							echo "<div class='tile-footer' style='border:none'>";
								echo "<div class='row' style='padding-left: 15px; padding-right: 15px;'>";
									echo "<div class='col-md-4 col-sm-12' style='padding: 1px'>";
										echo "<div class='form-group'>";
											echo "<select data-url='inc/update.php' data-name='ID_CATEGORY' class='form-control update_cat' style='padding: 0px;' table='topics' data-pk='".$sql_get_topics_rows['ID']."' id='category'>";
												$sql_get_cat = "SELECT * FROM ".DB_PREFIX."view_episode_categories WHERE ALLOW_TOPICS = 1 AND CATEGORIES_ID_PODCAST = ".$_SESSION['podcast']." AND EPISODE_ID_PODCAST = ".$_SESSION['podcast']." AND ID_EPISODE = ".$_SESSION['cur_episode'];
												$sql_get_cat_result = mysqli_query($con, $sql_get_cat);
												while($sql_get_cat_rows = mysqli_fetch_assoc($sql_get_cat_result))
													{
														echo "<option ";
														if ($sql_get_cat_rows['ID_CATEGORY'] === $sql_get_topics_rows['ID_CATEGORY'])
															{
																echo "selected ";
															}
														echo "value=".$sql_get_cat_rows['ID_CATEGORY'].">"; 
															echo $sql_get_cat_rows['DESCR'];
														echo "</option>";
													}	
											echo "</select>";
										echo "</div>";
									echo "</div>";
									echo "<div class='col-md-4 col-sm-12' style='padding: 1px'>";
										echo "<button type='button' ".$done." class='btn ".$btn." btn-block check_link' id='check_topics".$sql_get_topics_rows['ID']."' onclick='check_link(".$sql_get_topics_rows['ID'].", \"topics\")' data-name='DONE' data-checked='".$sql_get_topics_rows['DONE']."'>";
											echo "<i class='far fa-check-circle'></i>";
										echo "</button>";
									echo "</div>";
									echo "<div class='col-md-4 col-sm-12' style='padding: 1px'>";
										echo "<button class='btn btn-danger btn-block delete_entry' id='delete_topic".$sql_get_topics_rows['ID']."' table='topics' option='topic' data-pk='".$sql_get_topics_rows['ID']."'><i class='far fa-times-circle fa-fw'></i> Thema löschen</button>";
									echo "</div>";		
								echo "</div>";
								echo "<p>";
							echo "</div>";
							echo "</div>";
						}
						echo "<SCRIPT>
							$(\".delete_entry\").on('click', function(){
								var pk = $(this).attr(\"data-pk\");
								var table = $(this).attr(\"table\");
								var option = $(this).attr(\"option\");

								if(table == 'topics')
									{
										var content = 'Das Thema und alle enthaltenen Beiträge werden gelöscht!';
									}
								else
									{
										var content = 'Der Beitrag wird gelöscht!';
									}
								$.confirm({
									title: 'Wirklich löschen?',
									content: content,
									type: 'red',
									buttons: {   
									ok: {
										text: \"ok!\",
										btnClass: 'btn-primary',
										keys: ['enter'],
										action: function(){
											jQuery.ajax({
												url: \"inc/delete.php?del_\"+option+\"=1\",
												data: {	\"pk\":pk,
														\"table\":table
													},
												type: \"POST\",
												success:function(data){
													console.log(data);
													location.reload();
													},
												error:function ()
													{
													}
												});
											}
										},
									cancel:	
										{
											text: \"abbrechen!\",
											action: function(){}
										}
									}
								});	
							});	
						</SCRIPT>"; 
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
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