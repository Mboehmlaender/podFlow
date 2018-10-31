<?php

include('../inc/config.php');
$now = date("Y-m-d G:i:s");
$today = date("Y-m-d");

//Session-Funktion
function session(){
	session_start();
	if(!isset($_SESSION['userid']))
		{
		header('Location: ../login.php');
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
	echo "<link rel='icon' type='image/png' href='../images/podflow_Logo_v2c.png' />";

	echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";

	echo "<link href='//fonts.googleapis.com/css?family=Raleway:400,300,600' rel='stylesheet' type='text/css'>";
	echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>";
	echo "<link rel='stylesheet' href='css/main.css'>";
	echo "<link rel='stylesheet' href='css/custom.min.css'>";
	echo "<link rel='stylesheet' href='css/color-picker.css'>";
	echo "<link rel='stylesheet' href='css/datepicker.css'>";
	echo "<link rel='stylesheet' href='../css/jquery.gritter.css'>";
	echo "<link rel='stylesheet' href='../css/jquery-confirm.min.css'>";
	echo "<link rel='stylesheet' href='../css/simplebar.css'>";
	echo "<script src='//cdn.ckeditor.com/4.9.2/basic/ckeditor.js'></script>";
	echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js' integrity='sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49' crossorigin='anonymous'></script>";
	echo "<script src='https://code.jquery.com/jquery-3.3.1.min.js' integrity='sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=' crossorigin='anonymous'></script>";
	echo "<script src='../js/jquery.gritter.min.js'></script>";
	echo "<script src='https://code.jquery.com/ui/1.12.0/jquery-ui.min.js'></script>";
	echo "<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js' integrity='sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy' crossorigin='anonymous'></script>";
	echo "<script src='https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js'></script>";
	echo "<script src='../js/clipboard.min.js'></script>";
	echo "<script src='../js/jquery-confirm.min.js'></script>";
	echo "<script src='../js/jquery.ui.touch-punch.min.js'></script>";
	echo "<script src='../js/bootstrap-editable.min.js'></script>";
	echo "<script src='../js/simplebar.js'></script>";
	echo "<script src='js/color-picker.js'></script>";
	echo "<script src='js/bootstrap-datepicker.js'></script>";
	echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js'></script>";
	echo "<link rel='stylesheet' href='../css/all.min.css'>";
	echo "<noscript>
	  <style>
		[data-simplebar] {
		  overflow: auto;
		}
	  </style>
	</noscript>";	
	echo "<script>

	$(function (){
		$('[data-toggle=\"tooltip\"]').tooltip()
		$('#tool').tooltip('disable')
	})

	$(function () {
		$('[data-toggle=\"popover\"]').popover({
		trigger: 'focus'})
	})
	</script>";
	echo "<link href='//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css' rel='stylesheet'/>";
	echo "</head>";
}

//Moal: Podast auswählen 
function select_podcast(){

	if(empty($_SESSION['podcast']))
		{
			global $con;

			$login_podcast_select = "SELECT ID, SHORT FROM ".DB_PREFIX."podcast";	
			$login_podcast_result = mysqli_query($con, $login_podcast_select);
			if(mysqli_num_rows($login_podcast_result) == 1)
				{
					$id = mysqli_fetch_assoc($login_podcast_result);
					$_SESSION['podcast'] = $id['ID'];
					return;
				}
			echo "<script>
					$(document).ready(function(){
						$(\"#change\").modal('show');
						$.ajax({
							url: 'inc/select.php?change=1',
							type: 'POST',
							data: {},
							success: function(data)
								{
									console.log(data),
									$(\"#change_content\").html(data);
								},
							});
					});
					</script>"; 
		}
}

//Obere Navigation
function navbar_top(){
	if(isset($_POST['update_podcast'])) 
		{
			$sql_update_podcast="UPDATE ".DB_PREFIX."podcast SET DESCR = '".$_POST['descr']."', SHORT = '".$_POST['short']."' WHERE ID =".$_SESSION['podcast'];
			mysqli_query($con,$sql_update_podcast) or exit("Fehler im SQL-Kommando: $sql_update_podcast");				
			$sql_update_podcast_INI="UPDATE ".DB_PREFIX."ini SET KEYVALUE = '".$_POST['short']."' WHERE KEYWORD = 'PC_PREFIX' AND SETTING =".$_SESSION['podcast'];
			mysqli_query($con,$sql_update_podcast_INI) or exit("Fehler im SQL-Kommando: $sql_update_podcast_INI");
		} 
	if(isset($_POST['change_podcast']))
		{
			$_SESSION['podcast'] = $_POST['change_podcast'];
			$_SESSION['cur_episode'] = '';
		}
	$podcast_color = getSetting('PC_COLOR',$_SESSION['podcast']);
	echo "<header class='app-header' style='padding-right: 0px;'><a class='app-header__logo' href='index.php'></a>";
		echo "<a class='app-sidebar__toggle' href='#' data-toggle='sidebar' aria-label='Hide Sidebar'></a>";
		echo "<ul class='app-nav' id='podcast_menu'>";
			echo "<li class='dropdown' data-toggle='tooltip' data-placement='bottom' title='Podcast wählen'><a class='app-nav__item change' href='#' data-toggle='dropdown' aria-label='Open Profile Menu'><i class='fa fas fa-exchange-alt fa-lg'></i></a>";
			echo "</li>";
			echo "<li class='dropdown' data-toggle='tooltip' data-placement='bottom' title='Abmelden'>";
				echo "<a class='app-nav__item' href='../logout.php'><i class='fas fa-power-off fa-lg'></i></a>";
			echo "</li>";		
			echo "<li class='podcast-name-top' id='podcast-name-top' data-toggle='tooltip' data-placement='bottom' title='Aktuelle Auswahl'>";
				echo "<div style='border-color: ".$podcast_color."' id='menu_pc_name' class='podcast-info-menu'>";
					echo "<i style='color:black' class='fa fas fa-podcast topbarpclogo'></i> ".getSetting('PC_PREFIX',$_SESSION['podcast']);
				echo "</div>";  
			echo "</li>";
		echo "</ul>";  
	echo "</header>";
}


//Sidebar-Navigation
function sidebar(){
	echo "<div class='app-sidebar__overlay' data-toggle='sidebar'></div>";
	echo "<aside class='app-sidebar'>";
		echo "<div class='app-sidebar__user'>";
			/* 	 echo "<img class='app-sidebar__user-avatar' src='../";
			echo userinfos($_SESSION['userid'], 'AVATAR');
			echo "' alt='User Image'>"; */
			echo "<div>";
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
			echo "<li><a class='app-menu__item' id='menu_users' href='users.php'><i class='app-menu__icon fas fa-users'></i><span class='app-menu__label'>Benutzer</span></a></li>";
			echo "<li><a class='app-menu__item' id='menu_pc' href='podcast.php'><i class='app-menu__icon fa fas fa-podcast'></i><span class='app-menu__label'>Podcasts</span></a></li>";
			echo "<li><a class='app-menu__item' id='menu_cat' href='categories.php'><i class='app-menu__icon fas fa-tags'></i><span class='app-menu__label'>Kategorien</span></a></li>";
			echo "<li><a class='app-menu__item' id='menu_epi' href='episoden.php'><i class='app-menu__icon fas fa-microphone'></i><span class='app-menu__label'>Episoden</span></a></li>";
			echo "<li><a class='app-menu__item' id='menu_temp' href='templates.php'><i class='app-menu__icon fas fa-recycle'></i><span class='app-menu__label'>Vorlagen</span></a></li>";
			echo "<li><a class='app-menu__item' id='website' href='../index.php'><i class='app-menu__icon fas fa-chevron-circle-left'></i><span class='app-menu__label'>Frontend</span></a></li>";
		echo "</ul>";
	echo "</aside>";

}

//Modal: Neue Episode anlegen
function episode_add(){

	global $con;	
	$sql_select_template = "SELECT * FROM ".DB_PREFIX."episode_templates WHERE ID_PODCAST = ".$_SESSION['podcast'];
	$sql_select_template_result = mysqli_query($con, $sql_select_template);

	echo "<div class='form-group'>";
		echo "<label id='tool_new' data-toggle='tooltip' title='Diese Folge gibt es schon!'>Episoden-Nummer <span style='color: red'>*</span></label>";
		echo "<div class='input-group mb-3'>";
			echo "<input type='number' name='nummer_add_neu' min='0' podcast='".$_SESSION['podcast']."' id ='nummer_add_neu' class='form-control'>";
				echo "<div class='input-group-append'>";
					echo "<span class='input-group-text' min='0' id='number-availability-status-new'><i style='color: red;' class='fa-fw fas fa-times'></i></span>";
				echo "</div>";
		echo "</div>";
	echo "</div>";	

	echo "<div class='form-group'>";
		echo "<label>Titel</label>";
		echo "<input type='text' name='title_new_episode' id ='title_new_episode' class='form-control'>";
	echo "</div>";
	echo "<div class='form-group'>";
		echo "<label>Datum</small></label>";
		echo "<input type='date' name='date' id='date_new' class='form-control' value=''>";
	echo "</div>";	
	if(mysqli_num_rows($sql_select_template_result) > 0)
		{
			echo "<div class='form-check' style='margin-bottom: 16px'>";
				echo "<input class='form-check-input' type='checkbox' value='' id='template_check'>";
				echo "<label class='form-check-label' for='template_check'>Aus einer Vorlage</label>";
			echo "</div>";
			echo "<div class='form-group' style='display:none' id='template_select_div'>";
				echo "<select class='form-control' id='template_select'>";
					echo "<option disabled>Vorlage wählen</option>";
					while($sql_select_template_row = mysqli_fetch_assoc($sql_select_template_result))
						{
							echo "<option value='".$sql_select_template_row['ID']."' podcast='".$_SESSION['podcast']."' cats='".$sql_select_template_row['CATEGORIES']."' users='".$sql_select_template_row['USERS']."'>".$sql_select_template_row['DESCR']."</option>";
						}
				echo "</select>";
			echo "</div>";
		}
	echo "<p></p><small><span style='color: red'>* <span style='color:black'>Pflichtfeld</span></span></small>";
	echo "<script>
			$(\"#template_check\").on('click', function() {
				$(\"#template_select_div\").toggle(\"slow\");
			});

			// browser detect
			var BrowserDetect = {
			init: function() {
			this.browser = this.searchString(this.dataBrowser) || \"An unknown browser\";
			this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || \"an unknown version\";
			this.OS = this.searchString(this.dataOS) || \"an unknown OS\";
			},
			searchString: function(data) {
			for (var i = 0; i < data.length; i++) {
			var dataString = data[i].string;
			var dataProp = data[i].prop;
			this.versionSearchString = data[i].versionSearch || data[i].identity;
			if (dataString) {
			if (dataString.indexOf(data[i].subString) != -1) return data[i].identity;
			} else if (dataProp) return data[i].identity;
			}
			},
			searchVersion: function(dataString) {
			var index = dataString.indexOf(this.versionSearchString);
			if (index == -1) return;
			return parseFloat(dataString.substring(index + this.versionSearchString.length + 1));
			},
			dataBrowser: [{
			string: navigator.userAgent,
			subString: \"Chrome\",
			identity: \"Chrome\"
			}, {
			string: navigator.userAgent,
			subString: \"OmniWeb\",
			versionSearch: \"OmniWeb/\",
			identity: \"OmniWeb\"
			}, {
			string: navigator.vendor,
			subString: \"Apple\",
			identity: \"Safari\",
			versionSearch: \"Version\"
			}, {
			prop: window.opera,
			identity: \"Opera\",
			versionSearch: \"Version\"
			}, {
			string: navigator.vendor,
			subString: \"iCab\",
			identity: \"iCab\"
			}, {
			string: navigator.vendor,
			subString: \"KDE\",
			identity: \"Konqueror\"
			}, {
			string: navigator.userAgent,
			subString: \"Firefox\",
			identity: \"Firefox\"
			}, {
			string: navigator.vendor,
			subString: \"Camino\",
			identity: \"Camino\"
			}, { // for newer Netscapes (6+)
			string: navigator.userAgent,
			subString: \"Netscape\",
			identity: \"Netscape\"
			}, {
			string: navigator.userAgent,
			subString: \"MSIE\",
			identity: \"Explorer\",
			versionSearch: \"MSIE\"
			}, {
			string: navigator.userAgent,
			subString: \"Gecko\",
			identity: \"Mozilla\",
			versionSearch: \"rv\"
			}, { // for older Netscapes (4-)
			string: navigator.userAgent,
			subString: \"Mozilla\",
			identity: \"Netscape\",
			versionSearch: \"Mozilla\"
			}],
			dataOS: [{
			string: navigator.platform,
			subString: \"Win\",
			identity: \"Windows\"
			}, {
			string: navigator.platform,
			subString: \"Mac\",
			identity: \"Mac\"
			}, {
			string: navigator.userAgent,
			subString: \"iPhone\",
			identity: \"iPhone/iPod\"
			}, {
			string: navigator.platform,
			subString: \"Linux\",
			identity: \"Linux\"
			}]

			};
			BrowserDetect.init();

			///// mobile
			var isMobile = {
			Android: function() {
			return navigator.userAgent.match(/Android/i);
			},
			BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
			},
			iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
			},
			Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
			},
			Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
			},
			any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
			}
			};

			if (BrowserDetect.browser === 'Safari' && !(isMobile.iOS()) ) {
				$(\"#date_new\").attr(\"type\",\"text\");
				$(\"#date_new\").datepicker({
					format: 'dd.mm.yyyy'
				})
			}
	</script>";
}

//Modal: Vorlage hinzufügen
function template_add(){
	global $con;	
	echo "<div class='form-group'>";
		echo "<label>Bezeichnung <span style='color: red'>*</span></label>";
		echo "<div class='input-group mb-3'>";
			echo "<input type='text' name='title_template_new' id ='title_template_new' class='form-control'>";
		echo "</div>";
	echo "</div>";			
	echo "<label>Podcast <span style='color: red'>*</span></label>";
	echo "<div class='input-group mb-3'>";
		echo "<select class='form-control' id='podcast_template_new'>";
			echo "<option value='not' disabled selected='selected'>Podcast wählen</option>";
		$sql_select_template_podcast = "SELECT * FROM ".DB_PREFIX."podcast";
		$sql_select_template_podcast_result = mysqli_query($con, $sql_select_template_podcast);
		while ($sql_select_template_podcast_row = mysqli_fetch_assoc($sql_select_template_podcast_result))
			{
				echo "<option value='".$sql_select_template_podcast_row['ID']."'>".$sql_select_template_podcast_row['SHORT']."</option>";
			}
		echo "</select>";
	echo "</div>";	
	echo "<p></p><small><span style='color: red'>* <span style='color:black'>Pflichtfeld</span></span></small>";
}

//Modal: Podcast hinzufügen
function podcast_add(){
echo "<div class='form-group'>";
	echo "<label for='Beschreibung'>Beschreibung</label>";
	echo "<input type='text' class='form-control' name='descr' id='descr' aria-describedby='emailHelp'>";
echo "</div>";
echo "<div class='form-group'>";
	echo "<label id='tool_new_podcast' data-toggle='tooltip' title='Diesen Kurzbezeichner gibt es schon!'>Kurzbezeichner <span style='color: red'>*</span></label>";
		echo "<div class='input-group mb-3'>";
			echo "<input type='text' maxlength='5' name='short' id='short' class='form-control'>";
			echo "<div class='input-group-append'>";
				echo "<span class='input-group-text' id='podcast-availability-status-new'><i style='color: red;' class='fa-fw fas fa-times'></i></span>";
			echo "</div>";
		echo "</div>";
echo "</div>";	

echo "<p></p><small><span style='color: red'>* <span style='color:black'>Pflichtfeld</span></span></small>";
}

//Episode bearbeiten
function episode_edit(){
	echo "<a href='javascript:void(0);' style='font-size: 1.5rem;' id='show'><i class='fas fa-bars fa-fw'></i></a><div style='display:inline-flex; font-size: 1.5rem;'>".getSetting('PC_PREFIX',$_SESSION['podcast'])." Episodenliste</div>";
	echo "<div class='tile-body' id='episode_list'>";
		echo "<hr>";
		global $con;
		$sql_episode_list = "SELECT * FROM ".DB_PREFIX."episoden WHERE ID_PODCAST = ".$_SESSION['podcast']." ORDER BY NUMMER DESC";
		$sql_episode_result = mysqli_query($con, $sql_episode_list);
		if(mysqli_num_rows($sql_episode_result) == 0)
			{
				echo "<p class='lead'>Keine Episode angelegt</p>";
				echo "</div";
				echo "</div";
				return;
			}
		while ($sql_episode_row = mysqli_fetch_assoc($sql_episode_result))
			{
				if(!empty($sql_episode_row['TITEL']))
					{
						$title = " - ".$sql_episode_row['TITEL'];
					}
				else
					{
						$title = "";
					}
			echo "<div class='row'>";
				echo "<div class='col-md-4 col-6'>";
					echo "<p class='lead'>".str_pad($sql_episode_row['NUMMER'],3,'0', STR_PAD_LEFT).$title."</p>";
				echo "</div>";
				echo "<div class='col-md-4 col-6'>";
					echo "<p class='lead'>".date('d.m.Y',strtotime($sql_episode_row['DATE']))."</p>";
				echo "</div>";
				echo "<div class='col-md-4 col-12'>";
					echo "<div class='row'>";
						echo "<div class='col-md-6 col-12' style='padding: 2px;'>";
							echo "<button class='btn btn-outline-warning btn-block' onclick='edit_episode(".$sql_episode_row['ID'].")' data-pk='".$sql_episode_row['ID']."' table ='episoden'><i class='fas fa-edit'></i></button>";
						echo "</div>";
						echo "<div class='col-md-6 col-12' style='padding: 2px;'>";
							echo "<button class='btn btn-outline-danger btn-block' onclick='delete_episode(".$sql_episode_row['ID'].")' data-pk='".$sql_episode_row['ID']."' table ='episoden'><i class='fas fa-times-circle'></i></button>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "<hr>";
			}
	echo "</div>";
	echo "<script>
			$(\"#show\").on(\"click\", function(){
				$(\"#episode_list\").toggle(\"slow\");
			});
	</script>";
}

//Vorlage bearbeiten
function template_edit(){
	echo "<a href='javascript:void(0);' style='font-size: 1.5rem;' id='show'><i class='fas fa-bars fa-fw'></i></a><div style='display:inline-flex; font-size: 1.5rem;'>Vorlagenliste</div>";
		echo "<div class='tile-body' id='template_list'>";
		echo "<hr>";
		global $con;
		$sql_template_list = "SELECT * FROM ".DB_PREFIX."episode_templates";
		$sql_template_result = mysqli_query($con, $sql_template_list);
		if(mysqli_num_rows($sql_template_result) == 0)
			{
				echo "<p class='lead'>Keine Vorlage angelegt</p>";
				echo "</div";
				echo "</div";
				return;
			}
		while ($sql_template_row = mysqli_fetch_assoc($sql_template_result))
			{
				echo "<div class='row'>";
					echo "<div class='col-md-8 col-6'>";
						echo "<p class='lead'>".$sql_template_row['DESCR']."</p>";
					echo "</div>";
					echo "<div class='col-md-4 col-12'>";
						echo "<div class='row'>";
							echo "<div class='col-md-6 col-12' style='padding: 2px;'>";
								echo "<button class='btn btn-outline-warning btn-block' onclick='edit_template(".$sql_template_row['ID'].", ".$sql_template_row['ID_PODCAST'].")' data-pk='".$sql_template_row['ID']."' table ='templates'><i class='fas fa-edit fa-fw'></i></button>";
							echo "</div>";
							echo "<div class='col-md-6 col-12' style='padding: 2px;'>";
								echo "<button class='btn btn-outline-danger btn-block' onclick='delete_template(".$sql_template_row['ID'].")' data-pk='".$sql_template_row['ID']."' table ='templates'><i class='far fa-times-circle fa-fw'></i></button>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "<hr>";
			}
		echo "</div>";
		echo "<script>
				$(\"#show\").on(\"click\", function(){
					$(\"#template_list\").toggle(\"slow\");
				});
		</script>";
}

//Podcastliste
function podcast_list(){
	echo "<div class='tile'>";
		echo "<a href='javascript:void(0);' style='font-size: 1.5rem;' id='show'><i class='fas fa-bars fa-fw'></i></a><div style='display:inline-flex; font-size: 1.5rem;'>Podcastliste</div>";
			echo "<div class='tile-body' id='podcast_list'>";
			echo "<hr>";
			global $con;
			$sql_podcast_list = "SELECT * FROM ".DB_PREFIX."podcast";
			$sql_podcast_list_result = mysqli_query($con, $sql_podcast_list);
			while ($sql_podcast_list_row = mysqli_fetch_assoc($sql_podcast_list_result))
				{
					if(mysqli_num_rows($sql_podcast_list_result) == 1)
						{
							$button_del = 	"<span class='d-inline-block btn-block' tabindex='0' data-toggle='tooltip' title='Der letzte Podcast kann nicht gelöscht werden!'>";
							$button_del .= 	"<button id='tool' type='button' disabled style='pointer-events: none;' class='btn btn-outline-danger btn-block'><i class='far fa-times-circle'></i></button>";
							$button_del .= 	"</span>";
						}
					else
						{
							$button_del = "<button class='btn btn-outline-danger btn-block' onclick='delete_podcast(".$sql_podcast_list_row['ID'].", ".$_SESSION['podcast'].")'  data-pk='".$sql_podcast_list_row['ID']."' table ='podcast'><i class='fas fa-times-circle'></i></button>";
						}
					echo "<div class='row'>";
						echo "<div class='col-md-8 col-6'>";
							echo "<p class='lead'>".$sql_podcast_list_row['SHORT']."</p>";
						echo "</div>";
						echo "<div class='col-md-4 col-12'>";
							echo $button_del;
						echo "</div>";
					echo "</div>";
					echo "<hr>";
				}
			echo "</div>";
	echo "</div>";
	echo "<script>
			$(\"#show\").on(\"click\", function(){
				$(\"#podcast_list\").toggle(\"slow\");
			});
	</script>";
}

//Podcast bearbeiten
function podcast_info(){
	global $con;
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";
		$sql_podcast = "SELECT * FROM ".DB_PREFIX."podcast WHERE ID = ".$_SESSION['podcast'];
		$sql_podcast_result = mysqli_query($con, $sql_podcast);
			while ($rows_podcast = mysqli_fetch_assoc($sql_podcast_result))
				{
					if(empty($rows_podcast['COLOR']))
						{
							$podcast_color = "";
						}
					else
						{
							$podcast_color = $rows_podcast['COLOR'];
						}
					echo "<div class='tile'>";
						echo "<div id='pc' class='tile-title'>";
							if(empty($rows_podcast['DESCR']))
								{
									echo $rows_podcast['SHORT'];
								}
							else
								{
									echo $rows_podcast['DESCR'];
								}
						echo "</div>";
						echo "<hr>";
						echo "<div class='tile-body'>";
							echo "<div class='form-group'>";
								echo "<label for='Beschreibung'>Beschreibung</label>";
								echo "<input type='text' class='form-control' name='descr' id='Beschreibung' aria-describedby='emailHelp' value='".$rows_podcast['DESCR']."'>";
							echo "</div>";
							echo "<div class='row'>";
								echo "<div class='col-6'>";
									echo "<label id='tool_podcast_edit' data-toggle='tooltip' title='Diesen Kurzbezeichner gibt es schon!'>Kurzbezeichner <span style='color: red'>*</span></label>";
										echo "<div class='input-group mb-3'>";
											echo "<input type='text' maxlength='5' name='short_edit' id ='short_edit' class='form-control' value='".$rows_podcast['SHORT']."' short_cur='".$rows_podcast['SHORT']."'>";
												echo "<div class='input-group-append'>";
													echo "<span class='input-group-text' min='0' id='podcast_edit-availability-status-new'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
												echo "</div>";
										echo "</div>";
								echo "</div>";	
								echo "<div class='col-6'>";
									echo "<label>Farbe</label>";
										echo "<div class='input-group mb-3' id='color-picker'>";
											echo "<input type='text' name='color_edit' id ='color_edit' value='".$rows_podcast['COLOR']."' class='form-control'>";
														echo "<span class='input-group-append'>";
															echo "<span class='input-group-text colorpicker-input-addon'><i></i></span>";
														echo "</span>";
										echo "</div>";
										echo "<script>
										$(function (){
											$('#color-picker').colorpicker({
												format: 'auto'
											});
											$('#color-picker').colorpicker({
												format: null
											});
										});									
										</script>";
								echo "</div>";	
							echo "</div>";	
							echo "<div class='form-group' >";
								echo "<label>Mitwirkende:</label>";
									echo "<button type='button' data-toggle='collapse' data-target='#user_toggle' aria-expaned='false' style='margin-top: 10px; white-space: normal;' class='btn btn-outline-success btn-block btn-lg'><i class='fas fa-plus-square fa-fw'></i> Mitwirkende wählen</button>";
										echo "<div class='collapse' id='user_toggle'>";			
											echo "<ul class='list-group' id='users_episode'>";
											$sql_select_users_podcast = "SELECT * FROM ".DB_PREFIX."users";
											$sql_select_users_podcast_result = mysqli_query($con, $sql_select_users_podcast);
												while($sql_select_users_podcast_rows = mysqli_fetch_assoc($sql_select_users_podcast_result))	
													{
														if(empty(userinfos($sql_select_users_podcast_rows['ID'], 'NAME_SHOW')))
															{
																$user_name = userinfos($sql_select_users_podcast_rows['ID'], 'USERNAME');
															}
														else
															{
																$user_name = userinfos($sql_select_users_podcast_rows['ID'], 'NAME_SHOW');
															}
														echo "<li class='list-group-item'>";
															echo "<div class='row'>";
															$sql_select_users_2 = "SELECT * FROM ".DB_PREFIX."view_podcasts_users WHERE PODCASTS_USERS_ID_USER = ".$sql_select_users_podcast_rows['ID']." AND PODCASTS_USERS_ID_PODCAST = ".$_SESSION['podcast'];
															$sql_select_users_result_2 = mysqli_query($con, $sql_select_users_2);
															if(mysqli_num_rows($sql_select_users_result_2) > 0)
																{
																	$checked = "checked";
																}
																else{
																	$checked = "";
																}
																echo "<div class='col-2'>";
																	echo "<div class='toggle lg'>";
																		echo "<label class='switch'>";
																			echo "<input ".$checked." type='checkbox' onclick='edit_podcast_user(\"".$sql_select_users_podcast_rows['ID']."\")' podcast='".$_SESSION['podcast']."' id='user".$sql_select_users_podcast_rows['ID']."'>";
																			echo "<span class='button-indecator'></span>";
																		echo "</label>";
																	echo "</div>";
																echo "</div>";
																echo "<div class='col-10'>";
																	echo "<p class='lead'>".$user_name."</p>";												
																echo "</div>";
															echo "</div>";
														echo "</li>";
													} 
											echo "</ul>";  
										echo "</div>";	  	
										echo "<hr>";
										echo "<button type='button' id='update_podcast' podcast='".$_SESSION['podcast']."' class='btn btn-outline-primary'><i class='fas fa-save'></i> Speichern</button>";
										echo "<br><small><span style='color: red'>* <span style='color:black'>Pflichtfeld</span></span></small>";
							echo "</div>";
						echo " </div>";
				}
			echo " </div>";
		echo " </div>";
	echo " </div>";
}

//Benutzer suchen
function user_search(){
	echo "<div class='tile'>";
		echo "<div class='tile-title'>Benutzer suchen</div>";
		echo "<hr>";
			echo "<div class='tile-body'>";
				echo "<div class='form-group'>";

					echo "<input type='text' check='dist' class='form-control' name='UserSearch' id='UserSearch' aria-describedby='UserSearch'>";								
				echo "</div>";							

			echo "</div>";
		echo "</div>";
	echo "</div>";
}

//Benutzer hinzufügen
function user_add(){
	echo "<div class='form-group'>";
		echo "<label id='tool_user' data-toggle='tooltip' title='Diesen Benutzernamen gibt es schon!'>Benutzername <span style='color: red'>*</span></label>";
		echo "<div class='input-group mb-3'>";
			echo "<input type='text' name='User_add' id ='User_add' class='form-control'>";
				echo "<div class='input-group-append'>";
					echo "<span class='input-group-text' min='0' id='user-availability-status-new'><i style='color: red;' class='fa-fw fas fa-times'></i></span>";
				echo "</div>";
		echo "</div>";
	echo "</div>";	
	echo "<div class='form-group'>";
		echo "<label for='User_add_mail'>E-Mail</label>";
		echo "<input type='email' class='form-control' name='User_add_mail' id='User_add_mail' aria-describedby='emailHelp'>";
	echo "</div>";
	echo "<div class='form-group' >";
		echo "<label for='Password_add'>Passwort <span style='color: red'>*</span></label></label>";
		echo "<div class='input-group' id='password'>";
			echo "<div class='input-group-prepend'>";
				echo "<div class='input-group-text'><button type='button' style='background: transparent;border: none !important;' class='getNewPass' id='renew'><i class='fas fa-sync-alt'></i></button></div>";
			echo "</div>";
			echo "<input type='text' class='form-control' rel='gp' data-size='12' data-character-set='a-z,A-Z,0-9,#' id='Password_add'>";
		echo "</div>";
	echo "</div>";
	echo "<p></p><small><span style='color: red'>* <span style='color:black'>Pflichtfeld</span></span></small>";

}

//Benutzerliste
function users(){
	echo "<div class='tile'>";
		echo "<a href='javascript:void(0);' style='font-size: 1.5rem;' id='show'><i class='fas fa-bars fa-fw'></i></a><div style='display:inline-flex; font-size: 1.5rem;'>Benutzerliste</div>";
		echo "<div class='tile-body' id='results'>";
		echo "</div>";
		echo "<div class='tile-body' id='user_list'>";
		echo "<hr>";
		global $con;
		$sql_user_list = "SELECT * FROM ".DB_PREFIX."users";
		$sql_userlist_result = mysqli_query($con, $sql_user_list);
		while ($sql_userlist_row = mysqli_fetch_assoc($sql_userlist_result))
			{
				if(($sql_userlist_row['ID'] == $_SESSION['userid']) || (getPermission($_SESSION['userid']) < 3))
					{
						$button_del = 	"<span class='d-inline-block btn-block' tabindex='0' data-toggle='tooltip' title='Das geht nicht!'>";
						$button_del .= 	"<button id='tool' type='button' disabled style='pointer-events: none;' class='btn btn-outline-danger btn-block'><i class='far fa-times-circle'></i></button>";
						$button_del .= 	"</span>";
					}
				else
					{
						$button_del = 	"<button class='btn btn-outline-danger btn-block' onclick='delete_user(".$sql_userlist_row['ID'].")' data-pk='".$sql_userlist_row['ID']."' table ='users'><i class='fas fa-times-circle'></i></button>";
					}
				echo "<div class='row'>";
					echo "<div class='col-md-6 col-6'>";
						echo "<p class='lead'>".$sql_userlist_row['USERNAME']."</p>";
					echo "</div>";
					echo "<div class='col-md-6 col-12'>";
						echo "<div class='row'>";
							echo "<div class='col-md-6 col-12' style='padding: 2px;'>";
								echo "<button class='btn btn-outline-warning btn-block' onclick='edituser(".$sql_userlist_row['ID'].")' data-pk='".$sql_userlist_row['ID']."' table ='users'><i class='fas fa-edit'></i></button>";
							echo "</div>";
							echo "<div class='col-md-6 col-12' style='padding: 2px;'>";
								echo $button_del;
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "<hr>";
			}
		echo "</div>";
		echo "<script>
				$(\"#show\").on(\"click\", function(){
					$(\"#user_list\").toggle(\"slow\");
				});
			</script>";			
	echo "</div>";

}

//Kategorie hinzufügen
function category_add(){
	echo "<div class='row'>";
		echo "<div class='col-6 lead'>";
			echo "Name der Kategorie <span style='color: red'>*</span></label>";
		echo "</div>";
		echo "<div class='col-6' style='text-align:right'>";
			echo "<input type='text' class='form-control' id='cat_name_new'>";
		echo "</div>";
	echo "</div>";
	echo "<hr>";
	echo "<div class='row'>";
		echo "<div class='col-9 lead'>";
			echo "Sichtbar für andere Benutzer";
		echo "</div>";
		echo "<div class='col-3' style='text-align:right'>";
			echo "<div class='toggle lg'>";
				echo "<label class='switch'>";
					echo "<input class='form-check-input' id='cat_visible_new' type='checkbox'>";
					echo "<span class='button-indecator'></span>";
				echo "</label>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	/* echo "<div class='row'>";
		echo "<div class='col-9 lead'>";
			echo "Kategorie mit Themen";
		echo "</div>";
		echo "<div class='col-3' style='text-align:right'>";
			echo "<div class='toggle lg'>";
				echo "<label class='switch'>";
					echo "<input class='form-check-input' id='cat_topics_new' type='checkbox'>";
					echo "<span class='button-indecator'></span>";
				echo "</label>";
			echo "</div>";
		echo "</div>";
	echo "</div>"; */
	echo "<div class='row'>";
		echo "<div class='col-9 lead'>";
			echo "Kollaborative Kategorie";
		echo "</div>";
		echo "<div class='col-3' style='text-align:right'>";
			echo "<div class='toggle lg'>";
				echo "<label class='switch'>";
					echo "<input class='form-check-input' id='cat_coll_new' type='checkbox'>";
					echo "<span class='button-indecator'></span>";
				echo "</label>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	echo "<div class='row '>";
		echo "<div class='col-9 lead'>";
			echo "Maximale Beiträge";
		echo "</div>";
		echo "<div class='col-3'>";
			echo "<input style='width: 3rem; float:right; padding:2px' min='0' class='form-control' id='cat_entries_new' type='number'>";
		echo "</div>";
	echo "</div>";		
	echo "<p></p><small><span style='color: red'>* <span style='color:black'>Pflichtfeld</span></span></small>";
}

//Kategorien bearbeiten
function categories_edit_list(){
	global $con;
	echo "<div class='row' id='cat_list'>";
	$sql_get_categories = "SELECT * FROM ".DB_PREFIX."categories WHERE ID_PODCAST = ".$_SESSION['podcast']." ORDER BY REIHENF, DESCR";
	$sql_get_categories_result = mysqli_query($con, $sql_get_categories);
	while ($sql_get_categories_rows = mysqli_fetch_assoc($sql_get_categories_result))
		{
			if ($sql_get_categories_rows['MAX_ENTRIES'] >= 1)
				{
					if ($sql_get_categories_rows['MAX_ENTRIES'] == 1)
						{
							$entries = "Eintrag";
						}
					else
						{
							$entries = "Einträge";
						}
				$max_entries = "<i data-toggle='tooltip' data-placement='top' title='Max. ".$sql_get_categories_rows['MAX_ENTRIES']." ".$entries."' class='fa-fw ".getSetting('MAX_ENTRIES',0)."'></i>";
				}
			else 
				{
					$max_entries = "";
				}
			echo "<div class='col-12 sectionsid' id='category-".$sql_get_categories_rows['ID']."'>";
				echo "<div class='tile'>";
					echo "<div class='tile-header'>";
						echo "<div class='row'>";
							echo "<div class='col-10' style='overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; white-space: nowrap;'>";
								echo "<a style='border: none; color:black; font-weight: 700' class='update' href='#' id='descr".$sql_get_categories_rows['ID']."' data-name='DESCR'  table='categories' data-type='text' data-pk='".$sql_get_categories_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung'>".$sql_get_categories_rows['DESCR']."</a>";
							echo "</div>";
							echo "<div class='col-2' style='padding-right:15px; padding-left: 0px; text-align:right'>";
								echo "<i data-toggle='tooltip' data-placement='top' title='Kollaborativ' class='fa-fw ".getSetting('COLL',$sql_get_categories_rows['COLL'])."'></i>";
								echo "<i data-toggle='tooltip' data-placement='top' title='Sichtbarkeit' class='fa-fw ".getSetting('CATEGORY_VISIBLE',$sql_get_categories_rows['VISIBLE'])."'></i>";
/* 								echo "<i data-toggle='tooltip' data-placement='top' title='Themen' class='fa-fw ".getSetting('ALLOW_TOPICS',$sql_get_categories_rows['ALLOW_TOPICS'])."'></i>";
 */								echo $max_entries;
							echo "</div>";
						echo "</div>";
					echo "</div>";
					echo "<hr>";
					echo "<div class='tile-body' style='padding: 10px 20px 10px 20px'>";
						echo "<div class='row'>";
							echo "<div class='col-12'>";
							if($sql_get_categories_rows['VISIBLE'] == 1)
								{
									$check_visible = 'checked';
								}
							else
								{
									$check_visible = '';
								}									
							if($sql_get_categories_rows['COLL'] == 1)
								{
									$check_coll = 'checked';
								}
							else
								{
									$check_coll = '';
								}	
 							if($sql_get_categories_rows['EXPORT_TITLE_CAT'] == 1)
								{
									$check_export_title_cat = 'checked';
								}
							else
								{
									$check_export_title_cat = '';
								} 
 							if($sql_get_categories_rows['EXPORT_TITLE_TOPICS'] == 1)
								{
									$check_export_title_topics = 'checked';
								}
							else
								{
									$check_export_title_topics = '';
								} 
 							if($sql_get_categories_rows['EXPORT_TITLE_LINKS'] == 1)
								{
									$check_export_title_links = 'checked';
								}
							else
								{
									$check_export_title_links = '';
								} 
 							if($sql_get_categories_rows['EXPORT_URL_LINKS'] == 1)
								{
									$check_export_url_links = 'checked';
								}
							else
								{
									$check_export_url_links = '';
								} 
 							if($sql_get_categories_rows['EXPORT_NOTICE'] == 1)
								{
									$check_export_notice = 'checked';
								}
							else
								{
									$check_export_notice = '';
								} 
								
								echo "<div class='row'>";
									echo "<div class='col-9 lead'>";
										echo "Sichtbar für andere Benutzer";
									echo "</div>";
									echo "<div class='col-3' style='text-align:right'>";
										echo "<div class='toggle lg'>";
											echo "<label class='switch'>";
												echo "<input ".$check_visible." class='form-check-input cat_up' type='checkbox' id_cat='".$sql_get_categories_rows['ID']."' row='VISIBLE'  table='categories' data-type='text' data-pk='".$sql_get_categories_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung' >";
												echo "<span class='button-indecator'></span>";
											echo "</label>";
										echo "</div>";
									echo "</div>";
							echo "</div>";
							echo "<div class='row'>";
								echo "<div class='col-9 lead'>";
									echo "Kollaborative Kategorie";
								echo "</div>";
								echo "<div class='col-3' style='text-align:right'>";
									echo "<div class='toggle lg'>";
										echo "<label class='switch'>";
											echo "<input ".$check_coll." class='form-check-input cat_up' type='checkbox' id_cat='".$sql_get_categories_rows['ID']."' row='COLL'  table='categories' data-type='text' data-pk='".$sql_get_categories_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung' >";
											echo "<span class='button-indecator'></span>";
											echo "</label>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
							echo "<div class='row' style='height: 38px;'>";
								echo "<div class='col-9 lead'>";
									echo "Maximale Beiträge";
								echo "</div>";
								echo "<div class='col-3'>";
									echo "<input style='width: 3rem; float:right; padding:2px' min='0' class='form-control cat_up' type='number' id_cat='".$sql_get_categories_rows['ID']."' row='MAX_ENTRIES'  table='categories' data-type='text' data-pk='".$sql_get_categories_rows['ID']."' data-url='inc/update.php' value='".$sql_get_categories_rows['MAX_ENTRIES']."' >";
								echo "</div>";
							echo "</div>";	
							echo "<hr>";
							echo "<div class='row'>";
								echo "<div class='col-9 lead'>";
									echo "Titel der Kategorie ";
								echo "</div>";
								echo "<div class='col-3' style='text-align:right'>";
									echo "<div class='toggle lg'>";
										echo "<label class='switch'>";
											echo "<input ".$check_export_title_cat." class='form-check-input cat_up' type='checkbox' id_cat='".$sql_get_categories_rows['ID']."' row='EXPORT_TITLE_CAT'  table='categories' data-type='text' data-pk='".$sql_get_categories_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung' >";
											echo "<span class='button-indecator'></span>";
											echo "</label>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
							echo "<div class='row'>";
								echo "<div class='col-9 lead'>";
									echo "Titel der Themen ";
								echo "</div>";
								echo "<div class='col-3' style='text-align:right'>";
									echo "<div class='toggle lg'>";
										echo "<label class='switch'>";
											echo "<input ".$check_export_title_topics." class='form-check-input cat_up' type='checkbox' id_cat='".$sql_get_categories_rows['ID']."' row='EXPORT_TITLE_TOPICS'  table='categories' data-type='text' data-pk='".$sql_get_categories_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung' >";
											echo "<span class='button-indecator'></span>";
											echo "</label>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
							echo "<div class='row'>";
								echo "<div class='col-9 lead'>";
									echo "Titel der Beiträge ";
								echo "</div>";
								echo "<div class='col-3' style='text-align:right'>";
									echo "<div class='toggle lg'>";
										echo "<label class='switch'>";
											echo "<input ".$check_export_title_links." class='form-check-input cat_up' type='checkbox' id_cat='".$sql_get_categories_rows['ID']."' row='EXPORT_TITLE_LINKS'  table='categories' data-type='text' data-pk='".$sql_get_categories_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung' >";
											echo "<span class='button-indecator'></span>";
											echo "</label>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
							echo "<div class='row'>";
								echo "<div class='col-9 lead'>";
									echo "URL der Beiträge";
								echo "</div>";
								echo "<div class='col-3' style='text-align:right'>";
									echo "<div class='toggle lg'>";
										echo "<label class='switch'>";
											echo "<input ".$check_export_url_links." class='form-check-input cat_up' type='checkbox' id_cat='".$sql_get_categories_rows['ID']."' row='EXPORT_URL_LINKS'  table='categories' data-type='text' data-pk='".$sql_get_categories_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung' >";
											echo "<span class='button-indecator'></span>";
											echo "</label>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
							echo "<div class='row'>";
								echo "<div class='col-9 lead'>";
									echo "Notizen";
								echo "</div>";
								echo "<div class='col-3' style='text-align:right'>";
									echo "<div class='toggle lg'>";
										echo "<label class='switch'>";
											echo "<input ".$check_export_notice." class='form-check-input cat_up' type='checkbox' id_cat='".$sql_get_categories_rows['ID']."' row='EXPORT_NOTICE'  table='categories' data-type='text' data-pk='".$sql_get_categories_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung' >";
											echo "<span class='button-indecator'></span>";
											echo "</label>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
							echo "<hr>";
							/* echo "<div class='row'>";
								echo "<div class='col-9 lead'>";
									echo "Kategorie mit Themen";
								echo "</div>";
								echo "<div class='col-3' style='text-align:right'>";
									echo "<div class='form-check'>";
									echo "<label class='switch'>";
										echo "<input class='form-check-input cat_up' ".$check_topics." type='checkbox' id_cat='".$sql_get_categories_rows['ID']."' row='ALLOW_TOPICS'  table='categories' data-type='text' data-pk='".$sql_get_categories_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung' >";
										echo "<span class='slider round'></span>";
									echo "</label>";
								echo "</div>";
							echo "</div>"; */
							echo "<div class='row' style='height: 40px; padding: .5rem''>";
								echo "<div class='col-sm-1 col-2'>";
									echo "<i style='cursor:pointer' class='fas fa-2x fa-arrow-up moveuplink'></i> ";
								echo "</div>";									
								echo "<div class='col-sm-10 col-8 lead' style='text-align: center; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; white-space: nowrap;'>";
									echo "Reihenfolge ändern";
								echo "</div>";
								echo "<div class='col-sm-1 col-2' style='text-align: right'>";
									echo "<i style='cursor:pointer' class='fas fa-2x fa-arrow-down movedownlink'></i>";									
									echo "</div>";
							echo "</div>";

								echo "<hr>";
								echo "<button type='button' class='btn btn-danger btn-block' onclick='delete_category(\"".$sql_get_categories_rows['ID']."\")'><i class='far fa-times-circle fa-fw'></i> Löschen</button>";

							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "</div>";
		}
	echo "</div>";
}

//Footer mit Change-Modal
function footer(){
	echo "<script src='js/admin.min.js'></script>";
	echo "<div class='modal fade' id='change' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' ";
	if(empty($_SESSION['podcast']))
		{
			$disabled = "disabled";
			echo "data-backdrop='static' data-keyboard='false'";
		}
	else
		{
			$disabled = "";
		}
	echo " aria-hidden='true'>";
		echo "<div class='modal-dialog' role='document'>";
			echo "<div class='modal-content'>";
				echo "<div class='modal-header'>";
					echo " <h5 class='modal-title' id='exampleModalLabel'>Wählen</h5>";
				echo "</div>";
				echo "<div class='modal-body' id='change_content'>";
				echo "</div>";
				echo "<div class='modal-footer'>";
					echo "<button type='button' ".$disabled." class='btn btn-outline-secondary' data-dismiss='modal'>Schließen</button>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
}
?>