<?php 
include('../../config/dbconnect.php');
include('../../inc/config.php');
session_start();
if(!isset($_SESSION['userid']))
{
header('Location: ../login.php');
}		



//Version prüfen
if(isset($_GET['check_version']))
{
	$content=file_get_contents("https://podflow.de/api/data/read.php");
	$data=json_decode($content, true);
	foreach($data[0] as $test)
	{
		$out = $test;
	}
	$split = preg_split("/[\s.]+/",$out);
	$data_part =$split;
	$split_string = $data_part[0].".".$data_part[1].".".$data_part[2].".";
		echo "<p class='lead'>Online: ".$split_string." \"".$data_part[3]."\"</p>";
			echo "<p class='lead'>Installiert: ";
				$sql = "SELECT * FROM ".DB_PREFIX."ini WHERE KEYWORD = 'PF_VERSION' AND SETTING = '0'";
				$res = mysqli_query($con, $sql);
				$row = mysqli_fetch_row($res);
				echo $row[3]." \"".$row[4]."\"</p>";
	
	if($split_string === $row[3])
		{
			echo "<p style='color:green'>Deine Version ist aktuell</p>";
		}
	else
		{
			echo "<p style='color:red'>Eine neuere Version ist verfügbar!</p>";
			echo "<a style='text-decoration: none;' href='https://podflow.de' target='_blank'>Download</a></p>";
		}

	return;
}

//Modal: Podcast wechseln
if(isset($_GET['change'])){
	$change_podcast_select = "SELECT * FROM ".DB_PREFIX."podcast";	
	$change_podcast_result = mysqli_query($con, $change_podcast_select);
	echo "<div class='row' style='max-height: 300px' data-simplebar data-simplebar-auto-hide='false'>";
		$number_of_rows = mysqli_num_rows($change_podcast_result);
		while($change_podcast_row = mysqli_fetch_assoc($change_podcast_result))
			{
				if(empty($change_podcast_row['DESCR']))
					{
						$descr = "";
					}
				else
					{
						$descr = $change_podcast_row['DESCR'];
					}
			echo "<div class='col-md-12'>";
				echo "<div class='notice' style='border-color: ".$change_podcast_row['COLOR'].";' id='podcast".$change_podcast_row['ID']."' data-pk='".$change_podcast_row['ID']."'>";
					echo "<div class='row'>";
						echo "<div class='col-2'>";
							echo "<strong style='border-color: ".$change_podcast_row['COLOR'].";'>".$change_podcast_row['SHORT']."</strong>";
						echo "</div>";
						echo "<div class='col-10'>";
							echo $descr;
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "<script>
						$(\"#podcast".$change_podcast_row['ID']."\").click(function(){
							var podcast = $(this).attr(\"data-pk\");
							$.ajax({
								url: \"inc/update.php?set_session_podcast=1\",
								type: \"POST\",
								data: {\"podcast\":podcast},
								success: function(data)
									{ 
										console.log(data);
										location.reload();
									}
								});
						});							
						</script>";
			echo "</div>";
	}
	return;
}


//Benutzer bearbeiten (Maske)
if(isset($_GET['edit_user'])){
	$id = $_POST['pk'];
	$table = $_POST['table'];
	echo "<div class='tile' id='user_edit'>";
		echo "<div class='tile-title'>Benutzer bearbeiten<button id='close_edit_episode' style='cursor:pointer; float:right; padding:0px; background: transparent; border:none;'><i class='fas fa-window-close fa-fw'></i></button></div>";
			echo "<hr>";
			echo "<div class='tile-body'>";
			$sql_select_user = "SELECT * FROM ".DB_PREFIX.$table." WHERE ID = ".$id;
			$sql_select_user_result = mysqli_query($con, $sql_select_user);
			while($sql_select_user_row = mysqli_fetch_assoc($sql_select_user_result))
				{
					if(($sql_select_user_row['ID'] == $_SESSION['userid']) || (getPermission($_SESSION['userid']) < 3))
						{
							$button_del = 	"<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Das geht nicht!'>";
							$button_del .= 	"<button id='tool' type='button' disabled style='pointer-events: none;' class='btn btn-outline-danger'><i class='far fa-times-circle'></i> Benutzer löschen</button>";
							$button_del .= 	"</span>";
						}
					else
						{
							$button_del = 	"<button type='button' style='margin: 3px;' onclick='delete_user(\"".$id."\")' class='btn btn-outline-danger'><i class='far fa-times-circle fa-fw'></i> Benutzer löschen</button>";
						}
					echo "<div class='form-group'>";
						echo "<label id='tool_user_edit' data-toggle='tooltip' title='Diesen Benutzernamen gibt es schon!'>Benutzername <span style='color: red'>*</span></label>";
						echo "<div class='input-group mb-3'>";
							echo "<input type='text' name='username_edit' id ='username_edit' class='form-control' value='".$sql_select_user_row['USERNAME']."' username_cur='".$sql_select_user_row['USERNAME']."'>";
								echo "<div class='input-group-append'>";
									echo "<span class='input-group-text' min='0' id='username_edit-availability-status-new'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
								echo "</div>";
						echo "</div>";
					echo "</div>";				
					echo "<div class='form-group'>";
						echo "<label for='Username_Show'>Anzeigename</label>";
						echo "<input value='".htmlspecialchars($sql_select_user_row['NAME_SHOW'])."' name='name_show' type='text' class='form-control' id='Username_Show' aria-describedby='emailHelp' >";
					echo "</div>";								
					echo "<div class='form-group'>";
						echo "<label for='Username_Show'>E-Mail</label>";
						echo "<input type='email' value='".htmlspecialchars($sql_select_user_row['EMAIL'])."' name='User_Mail' type='text' class='form-control' id='User_Mail' aria-describedby='emailHelp' >";
					echo "</div>";	
					if($_SESSION['userid'] == $sql_select_user_row['ID'])
						{
						}
					else
						{
							echo "<div class='form-group'>";
								echo "<label for='Username_Show'>Benutzer-Ebene</label>";
								echo "<select name='level' class='form-control' id='level'>";
									echo "<option disabled>Benutzer-Ebene wählen</option>";		
									if(getPermission($_SESSION['userid']) != 3)
										{
											$where = "WHERE LEVEL <> 3";
										}							
									else 
										{
											$where = "";
										}
									$sql_levels = "SELECT * FROM ".DB_PREFIX."usergroups ".$where;
									$sql_levels_result = mysqli_query($con, $sql_levels);
									while ($sql_levels_row = mysqli_fetch_assoc($sql_levels_result))
										{
											echo "<option ";
												if($sql_select_user_row['LEVEL_ID'] == $sql_levels_row['LEVEL'])
													{
														echo "selected";
													}
											echo " value='".$sql_levels_row['LEVEL']."'>".$sql_levels_row['DESCR']."</option>";	
										}
								echo "</select>";							
							echo "</div>";	
						}
					echo "<div class='form-group'>";
						echo "<label for='Username_Show'>Neues Passwort erzeugen</label>";
						echo "<div class='input-group' id='password'>";
							echo "<div class='input-group-prepend'>";
								echo "<div class='input-group-text'><button type='button' style='background: transparent;border: none !important;' id='renew_edit'><i class='fas fa-sync-alt'></i></button>";
								echo "</div>";
							echo "</div>";
							echo "<input type='text' class='form-control' rel='gp' data-size='12' data-character-set='a-z,A-Z,0-9,#' id='Password_add_edit'>";
						echo "</div>";
		/* 				echo "</div>";	
		 */			echo "</div>";	
					echo "<hr>";
					echo "<button type='button' style='margin: 3px;' id='button_save_user' user_id ='".$id."' name='submit' class='btn btn-outline-primary'><i class='fas fa-save'></i> Speichern</button>";
					echo $button_del;
					echo "<br><small><span style='color: red'>* <span style='color:black'>Pflichtfeld</span></span></small>";

					echo "<script>
							$(function (){
								$('[data-toggle=\"tooltip\"]').tooltip()
							});

							$(\"#username_edit\").on('change input keyup blur', function(){
							$.ajax({
								url: \"inc/check.php?check_edit_user_short=1\",
								type: \"POST\",
								data: {	\"username_edit\":$(\"#username_edit\").val(),
										\"username_cur\":$(\"#username_edit\").attr('username_cur')
									},
								success: function(data)
									{
										console.log(data);
										$(\"#username_edit-availability-status-new\").html(data);
									}
								});

							});

							$(\"#button_save_user\").click(function(){
								user_id = $(this).attr('user_id');
								if($(\"#Username\").val() == '') 
									{
										$.gritter.add({
											title: 'Unvollständige Angaben',
											text: 'Bitte gib eine Usernamen ein!',
											image: '../images/delete.png',
											time: '1000'
										});		
										return;
									}
								$.ajax({
									url: \"inc/update.php?edit_user=1\",
									type: \"POST\",
									data: {	\"user_id\":user_id,
											\"username\":$(\"#username_edit\").val(),
											\"showname\":$(\"#Username_Show\").val(),
											\"email\":$(\"#User_Mail\").val(),
											\"password\":$(\"#Password_add_edit\").val(),
											\"level\":$(\"#level option:selected\").val()
										},
									success: function(data)
										{ 
											console.log(data);
												$.gritter.add({
												title: 'OK!',
												text: 'Änderungen gespeichert',
												image: '../images/confirm.png',
												time: '1000'
											});	
										}
									});
								$(\"#episode_info\").load(\" #episode_info > *\");
							});

							$(\"#renew_edit\").on(\"click\", function(){
								$(\"#Password_add_edit\").each(function(){
									$(this).val(randString($(this)));
									});
							});

							$(\"#Password_add_edit\").on(\"click\", function (){
								$(this).select();
							});

							$(\"#close_edit_episode\").click(function(){
								$(\"#edit\").hide(\"slow\");
								$(\"#user_list\").show(\"slow\");
								$(\"#results\").hide(\"slow\");
							});
						</script>";
				}
			echo "</div>";	
		echo "</div>";	
	echo "</div>";	
	return;
}

//Vorlage bearbeiten (Maske)
if(isset($_GET['edit_template'])){
	$id = $_POST['pk'];
	$table = $_POST['table'];
	$podcast_id = $_POST['podcast_id'];
	echo "<div class='tile' id='episode_edit'>";
		echo "<div class='tile-title'>Vorlage bearbeiten<button id='close_edit_template' style='cursor:pointer; float:right; padding:0px; background: transparent; border:none;'><i class='fas fa-window-close fa-fw'></i></button></div>";
			echo "<hr>";
			echo "<div class='tile-body'>";
				$sql_edit_template = "SELECT * FROM ".DB_PREFIX.$table." WHERE ID = ".$id;
				$sql_edit_template_result = mysqli_query($con, $sql_edit_template);
				while($row_edit_template = mysqli_fetch_assoc($sql_edit_template_result))
					{
						echo "<div class='form-group'>";
							echo "<label>Podcast</label>";
							echo "<input disabled type='text' name='podcast' id ='podcast' class='form-control' value='".getSetting('PC_PREFIX',$podcast_id)."'>";
						echo "</div>";				

						echo "<div class='form-group'>";
							echo "<label>Titel<span style='color: red'> *</span></label>";
							echo "<input type='text' name='title' id ='title' class='form-control' value='".htmlspecialchars($row_edit_template['DESCR'],ENT_QUOTES)."'>";
						echo "</div>";
						echo "<div class='form-group'>";
							echo "<label>Kategorien</label>";
							echo "<button type='button' data-toggle='collapse' data-target='#cat_toggle' aria-expaned='false' style='margin-top: 10px; white-space: normal;' class='btn btn-outline-success btn-block btn-lg'><i class='fas fa-plus-square fa-fw'></i> Kategorien wählen</button>";
							echo "<div class='collapse' id='cat_toggle'>";
								echo "<ul class='list-group' id='cat_episodes'>";
								$sql_select_topics = "SELECT * FROM ".DB_PREFIX."categories ORDER BY REIHENF, DESCR";
								$sql_select_topics_result = mysqli_query($con, $sql_select_topics);
								while($sql_select_topics_rows = mysqli_fetch_assoc($sql_select_topics_result))	
									{
										if ($sql_select_topics_rows['MAX_ENTRIES'] >= 1)
											{
												if ($sql_select_topics_rows['MAX_ENTRIES'] == 1)
													{
														$entries = "Eintrag";
													}
												else
													{
														$entries = "Einträge";
													}
												$max_entries = "<i data-toggle='tooltip' data-placement='top' title='Max. ".$sql_select_topics_rows['MAX_ENTRIES']." ".$entries."' class='fa-fw ".getSetting('MAX_ENTRIES',0)."'></i>";
											}
										else 
											{
											$max_entries = "";
											}
										echo "<li class='list-group-item check_if".$sql_select_topics_rows['ID']."' id='category".$sql_select_topics_rows['ID']."'>";
											echo "<div class='row'>";
												echo "<div class='col-2'>";
												$sql_select_cats = "SELECT CATEGORIES FROM ".DB_PREFIX."episode_templates WHERE ID = ".$id;
												$sql_select_cats_result = mysqli_query($con, $sql_select_cats);
												$row = mysqli_fetch_assoc($sql_select_cats_result);
												$string = implode(",", $row);
												$myArray = explode(',', $string);
												if(in_array($sql_select_topics_rows['ID'], $myArray))
													{
														$checked = "checked";
													}
												else
													{
														$checked = "";
													}
													echo "<div class='toggle lg'>";
														echo "<label class='switch'>";
															echo "<input ".$checked." type='checkbox' name='cats' template='".$id."' template_cat='".$sql_select_topics_rows['ID']."'>";
															echo "<span class='button-indecator'></span>";
														echo "</label>";
													echo "</div>";
												echo "</div>";							
												echo "<div class='col-8'>";
													echo "<p class='lead'>".$sql_select_topics_rows['DESCR']."</p>";
												echo "</div>";
												echo "<div class='col-2' style='padding-right: 0px; padding-left: 0px; text-align:right'>";
													echo "<i data-toggle='tooltip' data-placement='top' title='Kollaborativ' class='fa-fw ".getSetting('COLL',$sql_select_topics_rows['COLL'])."'></i>";
													echo "<i data-toggle='tooltip' data-placement='top' title='Sichtbarkeit' class='fa-fw ".getSetting('CATEGORY_VISIBLE',$sql_select_topics_rows['VISIBLE'])."'></i>";
													echo "<i data-toggle='tooltip' data-placement='top' title='Themen' class='fa-fw ".getSetting('ALLOW_TOPICS',$sql_select_topics_rows['ALLOW_TOPICS'])."'></i>";
													echo $max_entries;
												echo "</div>";
											echo "</div>";
										echo "</li>";
									} 
								echo "</ul>";
							echo "</div>";  			  
						echo "</div>";  			  
						echo "<div class='form-group' >";
							echo "<label>Mitwirkende:</label>";
							echo "<button type='button' data-toggle='collapse' data-target='#user_toggle' aria-expaned='false' style='margin-top: 10px; white-space: normal;' class='btn btn-outline-success btn-block btn-lg'><i class='fas fa-plus-square fa-fw'></i> Mitwirkende wählen</button>";
							echo "<div class='collapse' id='user_toggle'>";			
								echo "<ul class='list-group' id='users_episode'>";
									echo "<li class='list-group-item'>";
										echo "<div class='row'>";
										$sql_select_users_episode = "SELECT * FROM ".DB_PREFIX."view_podcasts_users WHERE PODCASTS_USERS_ID_PODCAST=".$podcast_id;
										$sql_select_users_episode_result = mysqli_query($con, $sql_select_users_episode);
										if(mysqli_num_rows($sql_select_users_episode_result) == 0)
											{
												echo "<p class='lead'>Es wurden noch keine Benutzer dem Podcast zugeordnet!</p>";
											}
										else
											{
												while($sql_select_users_episode_rows = mysqli_fetch_assoc($sql_select_users_episode_result))	
													{
														if(empty(userinfos($sql_select_users_episode_rows['PODCASTS_USERS_ID_USER'], 'NAME_SHOW')))
															{
																$user_name = userinfos($sql_select_users_episode_rows['PODCASTS_USERS_ID_USER'], 'USERNAME');
															}
														else
															{
																$user_name = userinfos($sql_select_users_episode_rows['PODCASTS_USERS_ID_USER'], 'NAME_SHOW');
															}
														$sql_select_users_template = "SELECT USERS FROM ".DB_PREFIX."episode_templates WHERE ID = ".$id;
														$sql_select_users_template_result = mysqli_query($con, $sql_select_users_template);
														$row_users_template = mysqli_fetch_assoc($sql_select_users_template_result);
														$string_users_template = implode(",", $row_users_template);
														$myArray_users_template = explode(',', $string_users_template);
														if(in_array($sql_select_users_episode_rows['PODCASTS_USERS_ID_USER'], $myArray_users_template))
															{
																$checked = "checked";
															}
														else
															{
																$checked = "";
															}
														echo "<div class='col-2'>";
															echo "<div class='toggle lg'>";
																echo "<label class='switch'>";
																	echo "<input ".$checked." name='users' type='checkbox' template='".$id."' template_users='".$sql_select_users_episode_rows['PODCASTS_USERS_ID_USER']."'>";
																	echo "<span class='button-indecator'></span>";
																echo "</label>";
															echo "</div>";
														echo "</div>";
														echo "<div class='col-10'>";
															echo "<p class='lead'>".$user_name."</p>";												
														echo "</div>";

													} 
											}
										echo "</div>";
									echo "</li>";
								echo "</ul>";  
							echo "</div>";
							echo "<hr>";
							echo "<button type='button' style='margin: 3px;' id='button_save_template' template ='".$id."' name='submit' class='btn btn-outline-primary'><i class='fas fa-save'></i> Speichern</button>";
							echo "<button type='button' style='margin: 3px;' onclick='delete_template(\"".$id."\")' class='btn btn-outline-danger'><i class='far fa-times-circle fa-fw'></i> Vorlage löschen</button>";
							echo "<br><small><span style='color: red'>* <span style='color:black'>Pflichtfeld</span></span></small>";

							echo "<script>

							$(function (){
								$('[data-toggle=\"tooltip\"]').tooltip()
								$('#tool').tooltip('disable')
							})

							$(\"#title\").on('change keyup', function(){
								if($(this).val() == '')
									{
										$(\"#button_save_template\").attr('disabled', true);
									}		
								else
									{
										$(\"#button_save_template\").removeAttr('disabled');
									}
							});

							$(\"#button_save_template\").click(function(){
								var category = new Array();
								$('input[name=\"cats\"]:checked').each(function(){
									category.push($(this).attr('template_cat'));
								});			

								var users = new Array();
								$('input[name=\"users\"]:checked').each(function(){
									users.push($(this).attr('template_users'));
								});

								template = $(this).attr('template');

								if($(\"#title\").val() == '') 
									{
										$.gritter.add({
											title: 'Unvollständige Angaben',
											text: 'Bitte gib eine Bezeichnung ein!',
											image: '../images/delete.png',
											time: '1000'
										});		
										return;
									}
								$.ajax({
									url: \"inc/update.php?template_update=1\",
									type: \"POST\",
									data: {	\"template\":template,
											\"title\":$(\"#title\").val(),
											\"category\":category,
											\"users\":users
										},
									success: function(data)
										{ 
											console.log(data);
											$.gritter.add({
												title: 'OK!',
												text: 'Änderungen gespeichert',
												image: '../images/confirm.png',
												time: '1000'
											});	
											$(\"#template_list\").load(\" #template_list > *\");
										}
									});
							});

							$(\"#close_edit_template\").click(function(){
								$(\"#edit\").hide(\"slow\");
								$(\"#template_list\").show(\"slow\");
							});
							
							</script>";
 						echo "</div>";
					}
			echo "</div>";
		echo "</div>";
	echo "</div>";
	return;
}

//Episode bearbeiten (Maske)
if(isset($_GET['edit_episode'])){
	$id = $_POST['pk'];
	$table = $_POST['table'];
	echo "<div class='tile' id='episode_edit'>";
		echo "<div class='tile-title'>Episode bearbeiten<button id='close_edit_episode' style='cursor:pointer; float:right; padding:0px; background: transparent; border:none;'><i class='fas fa-window-close fa-fw'></i></button></div>";
			echo "<hr>";
			echo "<div class='tile-body'>";
				$sql_edit_episode = "SELECT * FROM ".DB_PREFIX.$table." WHERE ID = ".$id;
				$sql_edit_episode_result = mysqli_query($con, $sql_edit_episode);
				while($row_edit_episode = mysqli_fetch_assoc($sql_edit_episode_result))
					{
						echo "<div class='form-group'>";
							echo "<label id='tool' data-toggle='tooltip' title='Diese Folge gibt es schon!'>Episoden-Nummer<span style='color: red'>*</span></label>";
							echo "<div class='input-group mb-3'>";
								echo "<input type='number' name='nummer' min='0' id ='nummer' cur_podcast='".$id."' cur_number='".$row_edit_episode['NUMMER']."'class='form-control' value='".$row_edit_episode['NUMMER']."'>";
									echo "<div class='input-group-append'>";
										echo "<span class='input-group-text' min='0' id='number-availability-status'><i style='color: green;' class='fa-fw  fas fa-check'></i></span>";
									echo "</div>";
							echo "</div>";
						echo "</div>";	
						echo "<div class='form-group'>";
							echo "<label>Titel</label>";
							echo "<input type='text' name='title' id ='title' class='form-control' value='".htmlspecialchars($row_edit_episode['TITEL'],ENT_QUOTES)."'>";
						echo "</div>";
						echo "<div class='form-group'>";
							echo "<label>Datum</label>";
							$date_stamp = new DateTime($row_edit_episode['DATE']);
							$date = $date_stamp->format('d.m.Y');
							echo "<script>

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
							$(\"#date\").val(\"".$date."\");
							$(\"#date\").attr(\"type\",\"text\");
							$(\"#date\").datepicker({
							format: 'dd.mm.yyyy'
							})

							}

							</script>";

							echo "<input type='date' name='date' id='date' class='form-control datepicker' value='".$row_edit_episode['DATE']."'>";			
						echo "</div>";
						echo "<div class='form-group'>";
							echo "<label>Kategorien</label>";
							echo "<button type='button' data-toggle='collapse' data-target='#cat_toggle' aria-expaned='false' style='margin-top: 10px; white-space: normal;' class='btn btn-outline-success btn-block btn-lg'><i class='fas fa-plus-square fa-fw'></i> Kategorien wählen</button>";
							echo "<div class='collapse' id='cat_toggle'>";
								echo "<ul class='list-group' id='cat_episodes'>";
									$sql_select_topics = "SELECT * FROM ".DB_PREFIX."categories ORDER BY REIHENF, DESCR";
									$sql_select_topics_result = mysqli_query($con, $sql_select_topics);
									$sql_select_topics_result2 = mysqli_query($con, $sql_select_topics);
									while($sql_select_topics_rows = mysqli_fetch_assoc($sql_select_topics_result))	
										{
											if ($sql_select_topics_rows['MAX_ENTRIES'] >= 1)
												{
													if ($sql_select_topics_rows['MAX_ENTRIES'] == 1)
													{
														$entries = "Eintrag";
													}
												else
													{
														$entries = "Einträge";
													}
													$max_entries = "<i data-toggle='tooltip' data-placement='top' title='Max. ".$sql_select_topics_rows['MAX_ENTRIES']." ".$entries."' class='fa-fw ".getSetting('MAX_ENTRIES',0)."'></i>";
												}
											else 
												{
													$max_entries = "";
												}
											echo "<li class='list-group-item check_if".$sql_select_topics_rows['ID']."' id='category".$sql_select_topics_rows['ID']."'>";
												echo "<div class='row'>";
													echo "<div class='col-2'>";
													$sql_select_cats = "SELECT * FROM ".DB_PREFIX."view_episode_categories WHERE ID_CATEGORY = ".$sql_select_topics_rows['ID']." AND ID_EPISODE = ".$id;
													$sql_select_cats_result = mysqli_query($con, $sql_select_cats);
													if(mysqli_num_rows($sql_select_cats_result) > 0)
														{
															$checked = "checked";
														}
													else
														{
															$checked = "";
														}
														echo "<div class='toggle lg'>";
															echo "<label class='switch'>";
																echo "<input ".$checked." type='checkbox' onclick='edit_episode_cat(\"".$sql_select_topics_rows['ID']."\")' episode='".$id."' id='cat".$sql_select_topics_rows['ID']."'>";
																echo "<span class='button-indecator'></span>";
															echo "</label>";
														echo "</div>";
													echo "</div>";							
													echo "<div class='col-8'>";
														echo "<p class='lead'>".$sql_select_topics_rows['DESCR']."</p>";
													echo "</div>";
													echo "<div class='col-2' style='padding-right: 0px; padding-left: 0px; text-align:right'>";
														echo "<i data-toggle='tooltip' data-placement='top' title='Kollaborativ' class='fa-fw ".getSetting('COLL',$sql_select_topics_rows['COLL'])."'></i>";
														echo "<i data-toggle='tooltip' data-placement='top' title='Sichtbarkeit' class='fa-fw ".getSetting('CATEGORY_VISIBLE',$sql_select_topics_rows['VISIBLE'])."'></i>";
														echo "<i data-toggle='tooltip' data-placement='top' title='Themen' class='fa-fw ".getSetting('ALLOW_TOPICS',$sql_select_topics_rows['ALLOW_TOPICS'])."'></i>";
														echo $max_entries;
													echo "</div>";
												echo "</div>";
											echo "</li>";
										} 
								echo "</ul>";
							echo "</div>";  			  
						echo "</div>";  			  
						echo "<div class='form-group' >";
							echo "<label>Mitwirkende:</label>";
							echo "<button type='button' data-toggle='collapse' data-target='#user_toggle' aria-expaned='false' style='margin-top: 10px; white-space: normal;' class='btn btn-outline-success btn-block btn-lg'><i class='fas fa-plus-square fa-fw'></i> Mitwirkende wählen</button>";
							echo "<div class='collapse' id='user_toggle'>";			
								echo "<ul class='list-group' id='users_episode'>";
									echo "<li class='list-group-item'>";
										echo "<div class='row'>";
											$sql_select_users_episode = "SELECT * FROM ".DB_PREFIX."view_podcasts_users WHERE PODCASTS_USERS_ID_PODCAST=".$_SESSION['podcast'];
											$sql_select_users_episode_result = mysqli_query($con, $sql_select_users_episode);
											if(mysqli_num_rows($sql_select_users_episode_result) == 0)
												{
													echo "<p class='lead'>Es wurden noch keine Benutzer dem Podcast zugeordnet!</p>";
												}
											else
												{
													while($sql_select_users_episode_rows = mysqli_fetch_assoc($sql_select_users_episode_result))	
														{
															$sql_select_users_1 = "SELECT * FROM ".DB_PREFIX."view_episode_users WHERE EPISODE_USERS_ID_USER = ".$sql_select_users_episode_rows['PODCASTS_USERS_ID_USER']." AND EPISODE_USERS_ID_EPISODE = ".$id;
															$sql_select_users_result_1 = mysqli_query($con, $sql_select_users_1);
															if(mysqli_num_rows($sql_select_users_result_1) > 0)
																{
																	$checked = "checked";
																}
															else
																{
																	$checked = "";
																}
															if(empty(userinfos($sql_select_users_episode_rows['PODCASTS_USERS_ID_USER'], 'NAME_SHOW')))
																{
																	$user_name = userinfos($sql_select_users_episode_rows['PODCASTS_USERS_ID_USER'], 'USERNAME');
																}
															else
																{
																	$user_name = userinfos($sql_select_users_episode_rows['PODCASTS_USERS_ID_USER'], 'NAME_SHOW');
																}	
															echo "<div class='col-2'>";
																echo "<div class='toggle lg'>";
																	echo "<label class='switch'>";
																		echo "<input ".$checked." type='checkbox' onclick='edit_episode_user(\"".$sql_select_users_episode_rows['PODCASTS_USERS_ID_USER']."\")' episode='".$id."' id='user".$sql_select_users_episode_rows['PODCASTS_USERS_ID_USER']."'>";
																		echo "<span class='button-indecator'></span>";
																	echo "</label>";
																echo "</div>";
															echo "</div>";
															echo "<div class='col-10'>";
																echo "<p class='lead'>".$user_name."</p>";												
															echo "</div>";

														} 
												}
										echo "</div>";
									echo "</li>";
								echo "</ul>";  
							echo "</div>";
							echo "<hr>";
							echo "<button type='button' style='margin: 3px;' id='button_save_episode' current_episode ='".$id."' name='submit' class='btn btn-outline-primary'><i class='fas fa-save'></i> Speichern</button>";
							echo "<button type='button' style='margin: 3px;' onclick='delete_episode(\"".$id."\")' class='btn btn-outline-danger'><i class='far fa-times-circle fa-fw'></i> Episode löschen</button>";
							echo "<br><small><span style='color: red'>* <span style='color:black'>Pflichtfeld</span></span></small>";
							echo "<script>

							$(function (){
								$('[data-toggle=\"tooltip\"]').tooltip()
								$('#tool').tooltip('disable')
							})

							$(\"#nummer\").on('change keyup input', function(){
								$.ajax({
									url: \"inc/check.php?check_episode=1\",
									type: \"POST\",
									data: {	\"podcast\":$(\"#nummer\").attr('cur_podcast'),
											\"nummer_change\":$(\"#nummer\").val(),
											\"nummer_cur\":$(\"#nummer\").attr('cur_number')
										},
									success: function(data)
										{ 
										console.log(data);
										$(\"#number-availability-status\").html(data);
										}
									});

							});

							$(\"#button_save_episode\").click(function(){
								episode = $(this).attr('current_episode');
								if($(\"#nummer\").val() == '') 
									{
										$.gritter.add({
											title: 'Unvollständige Angaben',
											text: 'Bitte gib eine Episoden-Nummer ein!',
											image: '../images/delete.png',
											time: '1000'
										});		
										return;
									}
								$.ajax({
									url: \"inc/update.php?episode_update=1\",
									type: \"POST\",
									data: {	\"episode\":episode,
											\"date\":$(\"#date\").val(),
											\"title\":$(\"#title\").val(),
											\"nummer\":$(\"#nummer\").val()
										},
									success: function(data)
										{ console.log(data);
											$.gritter.add({
												title: 'OK!',
												text: 'Änderungen gespeichert',
												image: '../images/confirm.png',
												time: '1000'
											});	
										}
									});
								$(\"#episode_info\").load(\" #episode_info > *\");
							});

							$(\"#close_edit_episode\").click(function(){
								$(\"#edit\").hide(\"slow\");
								$(\"#episode_list\").show(\"slow\");
							});
							
							</script>";
						echo "</div>";
					}
			echo "</div>";
		echo "</div>";
	echo "</div>";
	return;
}
?>