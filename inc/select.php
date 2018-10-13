<?php 
include('config.php');
include('../config/dbconnect.php');
session_start();
if(!isset($_SESSION['userid']))
	{
		header('Location: ../login.php');
	}		

//Modal: Episode bereinigen
if(isset($_GET['clean_episode'])){
	echo "<div class='row' id='select_unchecked' episode_id_current='".$_SESSION['cur_episode']."'>";
		echo "<div class='col-md-12'>";
			echo "<div class='notice delete_links' style='border-color: red;'>";
				echo "<strong style='border-color: red;'><i class='fa-fw fas fa-trash-alt'></i> Nicht bestätigte Beiträge und Themen löschen</strong>";
			echo "</div>";		
		echo "</div>";
		echo "<div class='col-md-12'>";
			echo "<div class='notice move_links' style='border-color: red;'>";
				echo "<strong style='border-color: red;'><i class='fa-fw fas fa-reply'></i> Nicht bestätigte Beiträge und Themen verschieben</strong>";
			echo "</div>";		
		echo "</div>";
		echo "<div class='col-md-12' id='result_episodes'>";
		echo "</div>"; 
	echo "</div>"; 

	echo "<script>
		$(\".move_links\").on('click', function(){
			$.ajax({
				url: 'inc/select.php?move_links=1',
				type: 'POST',
				data: {},
				success: function(data)
					{
						$(\"#result_episodes\").html(data);
					}
				});
		});

		$(\".delete_links\").on('click', function(){
			($(\"#result_episodes\").empty());
			var episode_id_current = $(\"#select_unchecked\").attr('episode_id_current');
			$.confirm({
				title: 'Wirklich löschen?',
				content: 'Die Themen und Beiträge werden unwiderruflich gelöscht!',
				type: 'red',
				buttons: 
					{   
						ok: {
							text: \"ok!\",
							btnClass: 'btn-primary',
							keys: ['enter'],
							action: function()
								{
									jQuery.ajax({
										url: \"inc/delete.php?delete_unchecked_content=1\",
										data: {	episode_id_current:episode_id_current
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
	</script>";
}

//Modal: Themen/Beiträge verschieben
if(isset($_GET['move_links'])){
	echo "<hr>";
	echo "<div style='text-align:center'>";
		global $con;
		$sql_select_episodes_clean = "SELECT * FROM ".DB_PREFIX."episoden WHERE ID_PODCAST = ".$_SESSION['podcast']." AND DONE <> 1 ORDER BY NUMMER DESC";
		$sql_select_episode_result = mysqli_query($con, $sql_select_episodes_clean);
		if(mysqli_num_rows($sql_select_episode_result) == 0)
			{
				echo "<p margin='0 auto' class='lead'>Es gibt keine offenen Episoden. Wende dich an einen Admin!</p>";
				return;
			}
		echo "<select id='move_links_select' class='form-control'>";
			echo "<option sel='none' disabled selected>Episode wählen</option>";
			while($sql_select_episodes_clean_row = mysqli_fetch_assoc($sql_select_episode_result))
				{
					if(empty($sql_select_episodes_clean_row['DATE']))
						{
							$date = "";
						}
					else
						{
							$date = "vom ".date('d.m.Y',strtotime($sql_select_episodes_clean_row['DATE']));
						}
					echo "<option value='".$sql_select_episodes_clean_row['ID']."'>Episode ".str_pad($sql_select_episodes_clean_row['NUMMER'],3,'0', STR_PAD_LEFT)." ".$date."</option>";
				}
		echo "</select>";
		echo "<button style='margin-top: 10px' disabled id='move_button' class='btn btn-success btn-block'>Verschieben</button>";
	echo "</div>";
	
	echo "<script>
		$(\"#move_links_select\").on('change', function(){
			if($(\"option:selected\",this).attr('sel') == 'none')
				{
					$(\"#move_button\").attr('disabled', true);
				}
			else
				{
					$(\"#move_button\").removeAttr('disabled');
				}
			});

		$(\"#move_button\").on('click', function(){
			var episode_id_new = $(\"#move_links_select option:selected\").val();
			var episode_id_current = $(\"#select_unchecked\").attr('episode_id_current');
			$.confirm({
				title: 'Wirklich verschieben?',
				content: 'Die Themen und Beiträge werden unwiderruflich verschoben!',
				type: 'red',
				buttons: 
					{   
						ok: {
							text: \"ok!\",
							btnClass: 'btn-primary',
							keys: ['enter'],
							action: function()
								{
									jQuery.ajax({
										url: \"inc/update.php?move_unchecked_content=1\",
										data: {	episode_id_new:episode_id_new,
												episode_id_current:episode_id_current
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
	</script>";
}


//Modal: Themen/Beiträge hinzufügen	
if(isset($_GET['add_entry'])){
	$episode = $_POST['change_value'];
	$sql_add_entry = "SELECT * FROM ".DB_PREFIX."view_episode_categories WHERE CATEGORIES_ID_PODCAST = ".$_SESSION['podcast']." AND ID_EPISODE = ".$episode;	
	$sql_add_entry_result = mysqli_query($con, $sql_add_entry);
	$number_of_rows_entry = mysqli_num_rows($sql_add_entry_result);

	echo "<div class='row' id='cat_list_add' >";
		if($number_of_rows_entry == 0)
			{
				echo "<div class='col-md-12'>";
					echo "<p class='lead'>Dem Podcast wurde noch keine Kategorien zugewiesen! Wende dich an einen Admin!</p>";
				echo "</div>";
				echo "</div>";
				return;
			}
		echo "<div class='col-md-12'>";
			echo "<div class='form-group'>";
				echo "<select id='modal1' name='category' class='form-control'>";
					echo "<option selected disabled>Kategorie wählen</option>";
					while($add_entry_row = mysqli_fetch_assoc($sql_add_entry_result))
						{
							echo "<option id='option' max_entries='".$add_entry_row['MAX_ENTRIES']."' cat_id=".$add_entry_row['ID_CATEGORY'].">";
								echo $add_entry_row['DESCR'];
							echo "</option>";
						}
				echo "</select>";

				echo "<script>
					$(\"#modal1\").on('change', function(){
						$(\"#select_depend_2\").load(\" #select_depend_2 > *\");
						var pocast = $(this).attr('podcast');
						var max_entries = $('#option:selected', this).attr('max_entries');
						var value = $('#option:selected', this).attr('cat_id');
						jQuery.ajax({
							url: \"inc/check.php?select_category=1\",
							data: {	\"cat_id\":value,
									\"max_entries\":max_entries
								},
							type: \"POST\",
							success:function(data)
								{
									$(\"#select_depend\").html(data);
									console.log(data);
								},
							error:function ()
								{
								}
							});
						});								   
				</script>"; 
			echo "</div>";								  
			echo "<div class='form-group' id='select_depend'>";
			echo "</div>";
			echo "<div class='form-group' id='select_depend_2'>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
}

//Modal: Podcast/Episode wechseln
if(isset($_GET['change'])){

	$change_value = $_POST['change_value'];

	if($change_value == 'podcast')
		{
			echo "<script>
				$(\"#exampleModalLabel\").html(\"Podcast wählen\");
			</script>";
			
			if(getPermission($_SESSION['userid']) < 2)
				{						
					$change_podcast_select = "SELECT PODCAST_SHORT AS SHORT, PODCAST_COLOR AS COLOR, PODCASTS_USERS_ID_PODCAST AS ID FROM ".DB_PREFIX."view_podcasts_users WHERE PODCASTS_USERS_ID_USER = ".$_SESSION['userid'];
				}
			else
				{
					$change_podcast_select = "SELECT * FROM ".DB_PREFIX."podcast";	
				}		 

			$change_podcast_result = mysqli_query($con, $change_podcast_select);
			$number_of_rows = mysqli_num_rows($change_podcast_result);

			echo "<div class='row' style='max-height: 300px' data-simplebar data-simplebar-auto-hide='false'>";
			if($number_of_rows == 0)
				{
					echo "<div class='col-md-12'>";
						echo "<p class='lead'>Du wurdest noch keinem Podcast zugewiesen! Wende dich an einen Admin!</p>";
					echo "</div>";
					echo "</div>";
					return;
				}
				
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
					echo "</div>";
					
					echo "<script>
						$(\"#podcast".$change_podcast_row['ID']."\").click(function(){
							var podcast = $(this).attr(\"data-pk\");
							$.ajax({
								url: \"inc/update.php?set_session_podcast=1\",
								type: \"POST\",
								data: {	\"podcast\":podcast,
									},
								success: function(data)
									{ 
										console.log(data);
										location.reload();
									}
								});
						});							
					</script>";
				}
			echo "</div>";
		}
	else
		{
			echo "<script>
				$(\"#exampleModalLabel\").html(\"Episode wählen\");
			</script>";
			
			if(getPermission($_SESSION['userid']) < 2)
				{						
					$change_episode_select = "SELECT * FROM ".DB_PREFIX."view_episoden WHERE PODCAST_ID = ".$_SESSION['podcast']." AND EPISODE_USERS_ID_USER = ".$_SESSION['userid']." AND EPISODEN_DONE <> 1 ORDER BY EPISODEN_NUMMER DESC";
					$filter = "";
				}
			else
				{
					$change_episode_select = "SELECT ".DB_PREFIX."episoden.ID AS EPISODE_USERS_ID_EPISODE, ".DB_PREFIX."episoden.DONE AS EPISODEN_DONE, ".DB_PREFIX."episoden.DATE AS EPISODEN_DATE, ".DB_PREFIX."episoden.NUMMER AS EPISODEN_NUMMER FROM ".DB_PREFIX."episoden WHERE ".DB_PREFIX."episoden.ID_PODCAST = ".$_SESSION['podcast']." ORDER BY ".DB_PREFIX."episoden.NUMMER DESC";
					$filter =  "<div class='col-12' style='margin-bottom: 10px;'>";
					$filter .= "<button type='button' class='btn btn-outline-primary btn-block' id='filter'>Abgeschlossene anzeigen</button>";
					$filter .= "</div>";
					
					echo "<script>
						var episodes = $(\"#episodes\").find(\".done1\");
						var episodes_open = $(\"#episodes\").find(\".done0\");
						episodes.hide();

					$(\"#filter\").on('click', function(){
						$(episodes).toggle();
						$(episodes_open).toggle();
						if(episodes.is(\":visible\"))
							{
								$(\"#filter\").removeClass('btn-outline-success');
								$(\".lock_status\").removeClass('fas fa-lock-open');
								$(\"#filter\").addClass('btn-success');
								$(\".lock_status\").addClass('fas fa-lock');
								$(\"#filter\").css('background-color', '#009688');
								$(\"#filter\").css('color', 'white');
								$(\"#filter\").css('border-color', '#009688');
								$(\".simplebar-scrollbar\").removeClass('visible');
							}
						else
							{
								$(\".lock_status\").removeClass('fas fa-lock');
								$(\"#filter\").removeClass('btn-success');
								$(\"#filter\").addClass('btn-outline-success');
								$(\".lock_status\").addClass('fas fa-lock-open');
								$(\"#filter\").css('background-color', 'transparent');
								$(\"#filter\").css('color', '#009688');
								$(\"#filter\").css('border-color', '#009688');
								$(\"#filter\").css('border-color', '#009688');
								$(\".simplebar-scrollbar\").removeClass('visible');
							}
						});

					</script>";
				}

			$change_episode_result = mysqli_query($con, $change_episode_select);
			$number_of_rows_episode = mysqli_num_rows($change_episode_result);
			echo "<div class='row'>";
				echo $filter;
			echo "</div>";
			echo "<div class='row' style='max-height: 300px;' data-simplebar id='episodes'data-simplebar-auto-hide='false'>";
			if($number_of_rows_episode == 0)
				{
					echo "<div class='col-md-12'>";
						echo "<p class='lead'>Es wurde noch keine Episode angelegt oder du wurdest noch keiner Episode zugewiesen! Wende dich an einen Admin!</p>";
					echo "</div>";
					echo "</div>";
					return;
				}
			while($change_episode_row = mysqli_fetch_assoc($change_episode_result))
				{
					$pc_color = getSetting('PC_COLOR',$_SESSION['podcast']);
					$pc_pre = getSetting('PC_PREFIX',$_SESSION['podcast']);
					if(empty($change_episode_row['EPISODEN_DATE']))
						{
							$date = "";
						}
					else
						{
							$date = "vom ".date('d.m.Y',strtotime($change_episode_row['EPISODEN_DATE']));
						}
					echo "<div class='col-md-12 done".$change_episode_row['EPISODEN_DONE']."'>";
						echo "<div class='notice' style='border-color: ".$pc_color.";' id='episode".$change_episode_row['EPISODE_USERS_ID_EPISODE']."' data-pk='".$change_episode_row['EPISODE_USERS_ID_EPISODE']."'>";
							echo "<div class='row'>";
								echo "<div class='col-3'>";
									echo "<strong style='border-color: ".$pc_color.";'>".$pc_pre.str_pad($change_episode_row['EPISODEN_NUMMER'],3,'0', STR_PAD_LEFT)."</strong>";
								echo "</div>";
								echo "<div class='col-9'>";
									echo $date;
									echo "<span id='status'>";
										echo "<i style='float: right' class='fas fa-lock-open lock_status'></i>";
									echo "</span>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
					echo "<script>
					$(\"#episode".$change_episode_row['EPISODE_USERS_ID_EPISODE']."\").click(function(){
						var episode = $(this).attr(\"data-pk\");
						$.ajax({
							url: \"inc/update.php?set_session_episode=1\",
							type: \"POST\",
							data: {	\"episode\":episode,
								},
							success: function(data)
								{ 
									console.log(data);
									location.reload();
								}
							});
						});							
					</script>";
				}
			echo "</div>";		 
		}
return;
}

//Themen/Beiträge der Kategorie laden
if(isset($_GET['cat_list'])){
	if(empty($_SESSION['cur_episode']))
		{
			return;
		}

		echo "<script>
			$(function () {
				$('[data-toggle=\"popover\"]').popover({
				trigger: 'focus'})
			})
		</script>";

	$cat_id = $_POST['cat_id'];

	echo "<div style='padding: 0px 10px 10px 10px;' class='content-list' id='cat_content".$cat_id."' category_ID='".$cat_id."'>";
		echo "<div class='tile-body'>";
		//Kollaborative Kategorie laden
		if(getSettingCat('COLL', $cat_id) == 1)
			{
				global $con;
				$sql_select = "SELECT ".DB_PREFIX."users.ID AS USER_ID, ".DB_PREFIX."users.USERNAME AS USERNAME, ".DB_PREFIX."users.NAME_SHOW AS NAME_SHOW, ".DB_PREFIX."links.ID, ".DB_PREFIX."links.ID_CATEGORY, ".DB_PREFIX."links.ID_USER, ".DB_PREFIX."links.ID_EPISODE, ".DB_PREFIX."links.DESCR, NULL AS IS_TOPIC, ".DB_PREFIX."links.DONE, ".DB_PREFIX."links.DONE_TS, ".DB_PREFIX."links.URL AS URL, ".DB_PREFIX."links.INFO AS INFO, ".DB_PREFIX."episoden.DONE AS EPISODE_DONE from ".DB_PREFIX."links join ".DB_PREFIX."episoden on ".DB_PREFIX."episoden.ID = ".DB_PREFIX."links.ID_EPISODE join ".DB_PREFIX."users on ".DB_PREFIX."users.ID = ".DB_PREFIX."links.ID_USER WHERE ".DB_PREFIX."links.ID_EPISODE = ".$_SESSION['cur_episode']." AND ".DB_PREFIX."links.ID_CATEGORY = '".$cat_id."' AND ".DB_PREFIX."links.ID_TOPIC IS NULL UNION ALL SELECT ".DB_PREFIX."users.ID AS USER_ID, ".DB_PREFIX."users.USERNAME AS USERNAME, ".DB_PREFIX."users.NAME_SHOW AS NAME_SHOW, ".DB_PREFIX."topics.ID, ".DB_PREFIX."topics.ID_EPISODE, ".DB_PREFIX."topics.ID_USER, ".DB_PREFIX."topics.ID_CATEGORY, ".DB_PREFIX."topics.DESCR, 1 AS IS_TOPIC, ".DB_PREFIX."topics.DONE, ".DB_PREFIX."topics.DONE_TS, ".DB_PREFIX."topics.INFO AS INFO, NULL AS URL, ".DB_PREFIX."episoden.DONE AS EPISODE_DONE from ".DB_PREFIX."topics join ".DB_PREFIX."episoden on ".DB_PREFIX."episoden.ID = ".DB_PREFIX."topics.ID_EPISODE join ".DB_PREFIX."users on ".DB_PREFIX."users.ID = ".DB_PREFIX."topics.ID_USER where ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_CATEGORY = '".$cat_id."' ORDER BY `DESCR` ASC";
				$sql_select_result = mysqli_query($con, $sql_select);
				while ($sql_select_row = mysqli_fetch_assoc($sql_select_result))
					{
						if(empty(userinfos($sql_select_row['USER_ID'], 'NAME_SHOW')))
							{
								$name_show_row = userinfos($sql_select_row['USER_ID'], 'USERNAME');
							}
						else
							{
								$name_show_row = userinfos($sql_select_row['USER_ID'], 'NAME_SHOW');
							}
							
						if ($sql_select_row['DONE'] == 1  && $sql_select_row['EPISODE_DONE'] == 0)
							{
								$btn = "btn-success";
								$done = "";
							}
						else if ($sql_select_row['EPISODE_DONE'] == 1 && $sql_select_row['DONE'] == 1)
							{
								$btn = "btn-success";
								$done ="disabled";		
							}																
						else if ($sql_select_row['EPISODE_DONE'] == 1 && $sql_select_row['DONE'] == 0)
							{
								$btn = "btn-outline-success";
								$done ="disabled";		
							}
						else
							{
								$btn = "btn-outline-success";
								$done ="";		
							}

						if($sql_select_row['ID_USER'] != $_SESSION['userid']	)
							{
								$edit = "disabled";
							}
						else
							{
								$edit = "";
							}
						
						//Kollaborative Themen laden
						if($sql_select_row['IS_TOPIC'] == 1)
							{
								echo "<div class='row'>";
									echo "<div class='col-12'>";
										echo "<div class='badge badge-primary'>";
											echo $name_show_row;
										echo "</div>";
									echo "</div>";
									echo "<div class='col-12'>";
										echo "<div class='lead'>";
											echo $sql_select_row['DESCR'];
										echo "</div>";
									echo "</div>";
								echo "</div>";	
								echo "<div class='form-row'>";
									echo "<div class='col-md-6 col-sm-12' style='padding: 1px;'>";
										echo "<button onclick='location.href=(\"topics.php?topic=".$sql_select_row['ID']."\");' type='button' class='btn btn-tertiary btn-block' name='fund'>";
											echo "<i class='fas fa-angle-double-right fa-fw'></i>";
										echo "</button>";
									echo "</div>";																	
									echo "<div class='col-md-6 col-sm-12' style='padding: 1px;'>";
										echo "<button type='button'  ".$edit." ".$done." class='btn ".$btn." btn-block check_link' id='check_topics".$sql_select_row['ID']."' onclick='check_link(".$sql_select_row['ID'].", \"topics\")' data-name='DONE' data-checked='".$sql_select_row['DONE']."'>";
											echo "<i class='far fa-check-circle'></i>";
										echo "</button>";
									echo "</div>";
								echo "</div>";
								echo "<hr>";	
							}
						//Kollaborative Beiträge laden
						else
							{
								echo "<input type='text' hidden name='id_link' value='".$sql_select_row['ID']."' ><input type='text' hidden name='check_is' value='".$sql_select_row['DONE']."' >";
								echo "<div class='row'>";
									echo "<div class='col-12'>";
										echo "<div class='badge badge-primary'>";
											echo $name_show_row;
										echo "</div>";
									echo "</div>";
									echo "<div class='col-12'>";
										echo "<div class='lead' id='card_content".$sql_select_row['ID']."'>";
											echo $sql_select_row['DESCR'];
										echo "</div>";
									echo "</div>";
								echo "</div>";			
								echo "<div class='form-row'>";
								if($sql_select_row['URL'] == NULL || $sql_select_row['URL'] == '')
									{
										echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
											echo "<button disabled type='button' class='btn btn-warning btn-block'>";
												echo "<i class='fas fa-external-link-alt fa-fw'></i>";
											echo "</button>";
										echo "</div>";
										echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
											echo "<button disabled type='button' class='btn btn-info btn-block'>";
												echo "<i style='color:black' class='far fa-copy fa-fw'></i>";
											echo "</button>";
										echo "</div>";
									}
								else
									{
										echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
											$fund_url = $sql_select_row['URL'];
											$pos = "http";
											if (strpos($fund_url, $pos) === false)
												{
													$base = "http://".$fund_url;
												}
												else
												{
													$base = $fund_url;
												}
											echo "<button onclick='window.open(\"".$base."\");' type='button' class='btn btn-warning btn-block'>";
												echo "<i class='fas fa-external-link-alt fa-fw'></i>";
											echo "</button>";
										echo "</div>";
										echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
											echo "<div data-clipboard-text='".$sql_select_row['URL']."' class='btn".$sql_select_row['ID']." btn btn-info btn-block clipboard'>";
												echo "<i style='color:black' class='far fa-copy fa-fw'></i>";
											echo "</div>";
										echo "</div>";
										
										echo "<script>
											var clip = new ClipboardJS('.btn".$sql_select_row['ID']."');
										</script>";
									}	

									echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
										echo "<button type='button'  ".$edit." ".$done." class='btn ".$btn." btn-block check_link' id='check_links".$sql_select_row['ID']."' onclick='check_link(".$sql_select_row['ID'].",\"links\")' data-name='DONE' data-checked='".$sql_select_row['DONE']."'>";
											echo "<i class='far fa-check-circle'></i>";
										echo "</button>";
									echo "</div>";
								echo "</div>";

								if($sql_select_row['INFO'] != NULL || $sql_select_row['INFO'] != '')
									{
										echo "<div class='form-row'>";
											echo "<div class='col-12' style='margin-top: 10px; padding: 1px;'>";
												echo "<button class='btn btn-outline-notice btn-block' type='button' data-container='body' data-html='true' data-toggle='popover' data-placement='top' data-content='".$sql_select_row['INFO']."'>";
													echo "Notizen";
												echo "</button>";
											echo "</div>";
										echo "</div>";
									}
								echo "<hr>";										
							}		
					}
			}
		//Nicht kollaborative Kategorien laden
		else
			{
				echo "<div class='row'>";
					$sql_select_users_cat = "SELECT * FROM ".DB_PREFIX."view_episode_users WHERE EPISODE_USERS_ID_EPISODE = ".$_SESSION['cur_episode'];
					$sql_select_users_result = mysqli_query($con, $sql_select_users_cat);
					while($sql_select_users_row = mysqli_fetch_assoc($sql_select_users_result))
						{
							if(getSettingCat('ALLOW_TOPICS', $cat_id) == 1)
								{
									$sql_number_users = "SELECT COUNT(*) FROM ".DB_PREFIX."topics WHERE ID_CATEGORY = ".$cat_id." AND ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_USER = ".$sql_select_users_row['EPISODE_USERS_ID_USER'];
									$res = mysqli_query($con, $sql_number_users);
									$row = mysqli_fetch_row($res);
								}							
							else
								{
									$sql_number_users = "SELECT COUNT(*) FROM ".DB_PREFIX."links WHERE ID_CATEGORY = ".$cat_id." AND ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_USER = ".$sql_select_users_row['EPISODE_USERS_ID_USER']." AND ID_TOPIC IS NULL";
									$res = mysqli_query($con, $sql_number_users);
									$row = mysqli_fetch_row($res);
								}

							if(empty(userinfos($sql_select_users_row['EPISODE_USERS_ID_USER'], 'NAME_SHOW')))
								{
									$name_show_row = userinfos($sql_select_users_row['EPISODE_USERS_ID_USER'], 'USERNAME');
								}
							else
								{
									$name_show_row = userinfos($sql_select_users_row['EPISODE_USERS_ID_USER'], 'NAME_SHOW');
								}

							echo "<div class='col-md-6 col-sm-12'>";
								echo "<button class='btn btn-outline-secondary btn-block collapse-content' data-toggle='collapse' data-target='#user".$sql_select_users_row['EPISODE_USERS_ID_USER']."cat".$cat_id."' type='button'>".$name_show_row."<span style='margin-left: 3px; vertical-align: middle' class='badge badge-secondary'>".$row[0]."</span></button><br>";
									echo "<div class='collapse content' id='user".$sql_select_users_row['EPISODE_USERS_ID_USER']."cat".$cat_id."'>";
									$sql_select = "SELECT ".DB_PREFIX."links.ID, ".DB_PREFIX."links.ID_CATEGORY, ".DB_PREFIX."links.ID_USER, ".DB_PREFIX."links.ID_EPISODE, ".DB_PREFIX."links.DESCR, NULL AS IS_TOPIC, ".DB_PREFIX."links.DONE, ".DB_PREFIX."links.DONE_TS, ".DB_PREFIX."links.URL AS URL, ".DB_PREFIX."links.INFO AS INFO, ".DB_PREFIX."episoden.DONE AS EPISODE_DONE from ".DB_PREFIX."links join ".DB_PREFIX."episoden on ".DB_PREFIX."episoden.ID = ".DB_PREFIX."links.ID_EPISODE WHERE ".DB_PREFIX."links.ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_USER = ".$sql_select_users_row['EPISODE_USERS_ID_USER']." AND ".DB_PREFIX."links.ID_CATEGORY = '".$cat_id."' AND ".DB_PREFIX."links.ID_TOPIC IS NULL UNION ALL SELECT ".DB_PREFIX."topics.ID, ".DB_PREFIX."topics.ID_EPISODE, ".DB_PREFIX."topics.ID_USER, ".DB_PREFIX."topics.ID_CATEGORY, ".DB_PREFIX."topics.DESCR, 1 AS IS_TOPIC, ".DB_PREFIX."topics.DONE, ".DB_PREFIX."topics.DONE_TS, NULL AS URL, ".DB_PREFIX."topics.INFO AS INFO, ".DB_PREFIX."episoden.DONE AS EPISODE_DONE from ".DB_PREFIX."topics join ".DB_PREFIX."episoden on ".DB_PREFIX."episoden.ID = ".DB_PREFIX."topics.ID_EPISODE where ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_USER = ".$sql_select_users_row['EPISODE_USERS_ID_USER']." AND ID_CATEGORY = '".$cat_id."' ORDER BY DESCR ASC";
									$sql_select_result = mysqli_query($con, $sql_select);
									while ($sql_select_row = mysqli_fetch_assoc($sql_select_result))
										{
											if ($sql_select_row['DONE'] == 1  && $sql_select_row['EPISODE_DONE'] == 0)
												{
													$btn = "btn-success";
													$done = "";
												}
											else if ($sql_select_row['EPISODE_DONE'] == 1 && $sql_select_row['DONE'] == 1)
												{
													$btn = "btn-success";
													$done ="disabled";		
												}																
											else if ($sql_select_row['EPISODE_DONE'] == 1 && $sql_select_row['DONE'] == 0)
												{
													$btn = "btn-outline-success";
													$done ="disabled";		
												}
											else
												{
													$btn = "btn-outline-success";
													$done ="";		
												}

											if($sql_select_row['ID_USER'] != $_SESSION['userid']	)
												{
													$edit = "disabled";
												}
											else
												{
													$edit = "";
												}

											if((getSettingCat('VISIBLE', $cat_id) == 0) && ($sql_select_row['ID_USER'] != $_SESSION['userid']) && ($sql_select_row['DONE'] != 1))
												{
													echo "Gesperrt<hr>";
												}
											else
												{	
													//Nicht kollaborative Themen laden											
													if($sql_select_row['IS_TOPIC'] == 1)
														{
															echo "<div class='lead'>";
																echo $sql_select_row['DESCR'];
															echo "</div>";
															echo "<div class='form-row'>";
																echo "<div class='col-md-6 col-sm-12' style='padding: 1px;'>";
																	echo "<button onclick='location.href=(\"topics.php?topic=".$sql_select_row['ID']."\");' type='button' class='btn btn-tertiary btn-block' name='fund'>";
																		echo "<i class='fas fa-angle-double-right fa-fw'></i>";
																	echo "</button>";
																echo "</div>";																	
															echo "<div class='col-md-6 col-sm-12' style='padding: 1px;'>";
																echo "<button type='button'  ".$edit." ".$done." class='btn ".$btn." btn-block check_link' id='check_topics".$sql_select_row['ID']."' onclick='check_link(".$sql_select_row['ID'].", \"topics\")' data-name='DONE' data-checked='".$sql_select_row['DONE']."'>";
																	echo "<i class='far fa-check-circle'></i>";
																echo "</button>";
															echo "</div>";
															echo "</div>";
															echo "<hr>";	
														}
													//Nicht kollaborative Beiträge laden
													else
														{
															echo "<input type='text' hidden name='id_link' value='".$sql_select_row['ID']."' ><input type='text' hidden name='check_is' value='".$sql_select_row['DONE']."' >";
																echo "<div class='lead' id='card_content".$sql_select_row['ID']."'>";
																	echo $sql_select_row['DESCR'];
																echo "</div>";
																echo "<div class='form-row'>";
																if($sql_select_row['URL'] == NULL || $sql_select_row['URL'] == '')
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
																		$fund_url = $sql_select_row['URL'];
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
																			echo "<div data-clipboard-text='".$sql_select_row['URL']."' class='btn".$sql_select_row['ID']." btn btn-info btn-block clipboard'>";
																				echo "<i style='color:black' class='far fa-copy fa-fw'></i>";
																			echo "</div>";
																		echo "</div>";
																		echo "<script>
																			var clip = new ClipboardJS('.btn".$sql_select_row['ID']."');
																		</script>";
																	}	

															echo "<div class='col-md-4 col-sm-12' style='padding: 1px;'>";
																echo "<button type='button'  ".$edit." ".$done." class='btn ".$btn." btn-block check_link' id='check_links".$sql_select_row['ID']."' onclick='check_link(".$sql_select_row['ID'].", \"links\")' data-name='DONE' data-checked='".$sql_select_row['DONE']."'>";
																	echo "<i class='far fa-check-circle'></i>";
																echo "</button>";
															echo "</div>";
														echo "</div>";
														
														if($sql_select_row['INFO'] != NULL || $sql_select_row['INFO'] != '')
															{
																echo "<div class='form-row'>";
																	echo "<div class='col-12' style='margin-top: 10px; padding: 1px;'>";
																		echo "<button class='btn btn-outline-notice btn-block' type='button' data-container='body' data-html='true' data-toggle='popover' data-placement='top' data-content='".$sql_select_row['INFO']."'>";
																			echo "Notizen";
																		echo "</button>";
																	echo "</div>";
																echo "</div>";
															}
														echo "<hr>";
														}										
												}

										}
								echo "</div>";
							echo "</div>";
						}
				echo "</div>";
			}
		echo "</div>";	
	echo "</div>";	

	echo "<script>
		$(\".collapse\").on('show.bs.collapse', function (){
			var content_id = $(this).attr('id');
			Cookies.set(content_id, 'content', { expires: 7 });
		});	
			
		$(\".collapse\").on('hide.bs.collapse', function (){
			var content_id_remove = $(this).attr('id');
			Cookies.remove(content_id_remove);
		});								

		var content=Cookies.get(); //get all cookies
		for (var panel in content){ //<-- panel is the name of the cookie
			if ($(\"#\"+panel).hasClass(\"content\")) // check if this is a panel
				{
					$(\"#\"+panel).show();
					$(\"#\"+panel).removeAttr('style');
					$(\"#\"+panel).addClass('show');
				}  
		}	
		
		$(\"#close\").click(function(){
			$(\"#edit\").hide(\"slow\");
			$(\"#category_list\").show(\"slow\");
			Cookies.remove('category');
			Cookies.remove(panel);
		});
		
		$(\".clipboard\").on('click', function(){
			$.gritter.add({
				title: 'Link kopiert',
				text: 'Der Link wurde in die Zwischenablage kopiert!',
				image: '../images/confirm.png',
				time: '1000'
			});		
		});		
	</script>";
	echo "<hr>";	
	return;
}

?>