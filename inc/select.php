
<?php 

include('config.php');
include('../config/dbconnect.php');
session_start();
if(!isset($_SESSION['userid']))
	{
		header('Location: ../login.php');
	}		


//Export: Liste

if(isset($_GET['export_list'])){
$id_episode = $_POST['id_episode'];
$order_by = $_POST['order_by'];
	echo "<div class='tab-content' id='pills-tabContent'>";
		echo "<div class='tab-pane fade show active lead' id='HTML-list' role='tabpanel' aria-labelledby='pills-home-tab'>";
		$sql_select = "SELECT * FROM ".DB_PREFIX."view_episode_categories WHERE ID_EPISODE=".$id_episode." AND EXPORT_CAT = 1 ORDER BY REIHENF ASC";
		$sql_select_result = mysqli_query($con, $sql_select);
			while ($sql_select_row = mysqli_fetch_assoc($sql_select_result))
			{	
				if($sql_select_row['ID_EXPORT_OPTION'] == 2)
					{
						$list_type_open = "<ol>";
						$close_type_close = "</ol>";
						$data_type_open = "<li>";
						$data_type_close = "</li>";
						$sep = "";
					}

				else if($sql_select_row['ID_EXPORT_OPTION'] == 3)
					{
						$list_type_open = "<ul>";
						$close_type_close = "</ul>";
						$data_type_open = "<li>";
						$data_type_close = "</li>";											
						$sep = "";
					}
				else if($sql_select_row['ID_EXPORT_OPTION'] == 1)
					{
						$list_type_open = "";
						$close_type_close = "";
						$data_type_open = "";
						$data_type_close = "";											
						$sep = " - ";
					}
				else
					{
						$list_type_open = "<ul style='list-style-type: none; padding-left: 0px'>";
						$close_type_close = "</ul>";
						$data_type_open = "<li style='list-style-type: none; padding-left: 0px'>";
						$data_type_close = "</li>";											
						$sep = "";
					}
					
				if($sql_select_row['EXPORT_TITLE_CAT'] == 1)
					{
						$cat_title_open = "<ul style='list-style-type:none; padding-left: 0px'>";
						$cat_title_open .= $sql_select_row['DESCR'];
						$cat_title_open .= "<li>";
						$cat_title_close = "</li>";
						$cat_title_close .= "</ul>";
					}
				else
					{
						$cat_title_open = "";
						$cat_title_close = "";
				}

				echo $cat_title_open;
				$sql_select_content_1 = "SELECT ID, ID_EPISODE, DESCR, INFO, URL, NULL AS IS_TOPIC, NULL AS ID_TOPIC, REIHENF, DONE, DONE_TS from ".DB_PREFIX."links WHERE ID_CATEGORY = ".$sql_select_row['ID_CATEGORY']." AND ID_EPISODE = ".$_SESSION['cur_episode']." AND ID_TOPIC IS NULL AND DONE = 1 UNION ALL SELECT ID, ID_EPISODE, DESCR, INFO, NULL AS URL, 1 AS IS_TOPIC, ID AS ID_TOPIC, REIHENF, DONE, DONE_TS from ".DB_PREFIX."topics where ID_CATEGORY = ".$sql_select_row['ID_CATEGORY']." AND ID_EPISODE = ".$_SESSION['cur_episode']." AND DONE = 1 ORDER BY ".$order_by." ASC";											
				$sql_select_content_1_result = mysqli_query($con, $sql_select_content_1);	
				$stringarray = array();
				$stringarray_notice = array();
				$count = 0;
				echo $list_type_open;
				while ($sql_select_content_1_row = mysqli_fetch_assoc($sql_select_content_1_result))
					{	
						if($sql_select_row['ID_EXPORT_OPTION'] == 1)
							{
								if($sql_select_content_1_row['IS_TOPIC'] == 1)
								{
									if($sql_select_row['EXPORT_TITLE_TOPICS'] == 0)
										{
											$sql_select_content_2 = "SELECT * FROM links WHERE ID_TOPIC = ".$sql_select_content_1_row['ID_TOPIC'];
											$sql_select_content_2_result = mysqli_query($con, $sql_select_content_2);
											$stringarray_2 = array();
											while ($sql_select_content_2_row = (mysqli_fetch_assoc($sql_select_content_2_result)))
												{	
													$descr2 = $data_type_open;
													if($sql_select_row['EXPORT_URL_LINKS'] == 0)
														{
															$fund_url = "";
														}
													else
														{
															$fund_url = $sql_select_content_2_row['URL'];
														}
													$pos = "http";
													
													if(empty($fund_url))
														{
															$descr2 .= $sql_select_content_2_row['DESCR'];
														}
													else if (strpos($fund_url, $pos) === false)
														{
															$descr2 .= "<a href='http://".$fund_url."' target='_blank' >".$sql_select_content_2_row['DESCR']."</a>";
														}
													else
														{
															$descr2 = "<a href='".$fund_url."' target='_blank' >".$sql_select_content_2_row['DESCR']."</a>";
														}																	
													$descr2 .= $data_type_close;

													array_push($stringarray_2, $descr2);	
												} 
											$descr .= implode(" | ",$stringarray_2);
											if(($sql_select_row['EXPORT_NOTICE'] == 1) && (!empty($sql_select_content_1_row['INFO'])))
												{
													$count++;
													$descr .= "<sup>".$count."</sup>";
													$descr_notice = "<p><span style='font-size:80%'><sup>".$count."</sup> ".$sql_select_content_1_row['INFO']."</span></p>";
												} 
											else
												{
												$descr_notice = "";
												}																	
											array_push($stringarray, $descr);
											array_push($stringarray_notice, $descr_notice);
										}
										else
											{
												$descr = $data_type_open;
												$descr .= $sql_select_content_1_row['DESCR'].": ";
												$sql_select_content_2 = "SELECT * FROM ".DB_PREFIX."links WHERE ID_TOPIC = ".$sql_select_content_1_row['ID_TOPIC'];
												$sql_select_content_2_result = mysqli_query($con, $sql_select_content_2);
												$stringarray_2 = array();
												$descr .= $list_type_open;
												while ($sql_select_content_2_row = (mysqli_fetch_assoc($sql_select_content_2_result)))
													{	
														$descr2 = $data_type_open;
														if($sql_select_row['EXPORT_URL_LINKS'] == 0)
															{
																$fund_url = "";
															}
														else
															{
																$fund_url = $sql_select_content_2_row['URL'];
															}																	
														$pos = "http";
														if(empty($fund_url))
															{
																$descr2 .= $sql_select_content_2_row['DESCR'];
															}
														else if (strpos($fund_url, $pos) === false)
															{
																$descr2 .= "<a href='http://".$fund_url."' target='_blank' >".$sql_select_content_2_row['DESCR']."</a>";
															}
														else
															{
																$descr2 = "<a href='".$fund_url."' target='_blank' >".$sql_select_content_2_row['DESCR']."</a>";
															}
														$descr2 .= $data_type_close;
														array_push($stringarray_2, $descr2);	
													} 
												$descr .= implode(" | ",$stringarray_2);
												$descr .= $close_type_close;
												$descr .= $data_type_close;

												if(($sql_select_row['EXPORT_NOTICE'] == 1) && (!empty($sql_select_content_1_row['INFO'])))
													{	
														$count++;
														$descr .= "<sup>".$count."</sup>";
														$descr_notice = "<p><span style='font-size:80%'><sup>".$count."</sup> ".$sql_select_content_1_row['INFO']."</span></p>";
													}  
												else
													{
														$descr_notice = "";
													}

												array_push($stringarray, $descr);	
												array_push($stringarray_notice, $descr_notice);	
											}
								}
								else
									{
										$fund_url = $sql_select_content_1_row['URL'];
										$pos = "http";
										if(empty($fund_url))
											{
												$descr = "<a href='#' >".$sql_select_content_1_row['DESCR']."</a>";
												array_push($stringarray, $descr);	
											}
										else if (strpos($fund_url, $pos) === false)
											{
												$descr = "<a href='http://".$fund_url."' target='_blank' >".$sql_select_content_1_row['DESCR']."</a>";
											}
											else
											{
												$descr = "<a href='".$fund_url."' target='_blank' >".$sql_select_content_1_row['DESCR']."</a>";
											}
											
										if(($sql_select_row['EXPORT_NOTICE'] == 1) && (!empty($sql_select_content_1_row['INFO'])))
											{
												$count++;
												$descr .= "<sup>".$count."</sup>";
												$descr_notice = "<p><span style='font-size:80%'><sup>".$count."</sup> ".$sql_select_content_1_row['INFO']."</span></p>";
											}  
										else
											{
												$descr_notice = "";
											}
											
										array_push($stringarray, $descr);	
										array_push($stringarray_notice, $descr_notice);	
									}
							}
						else
							{
								if($sql_select_content_1_row['IS_TOPIC'] == 1)
									{
										if($sql_select_row['EXPORT_TITLE_TOPICS'] == 0)
											{
												$sql_select_content_2 = "SELECT * FROM ".DB_PREFIX."links WHERE ID_TOPIC = ".$sql_select_content_1_row['ID_TOPIC'];
												$sql_select_content_2_result = mysqli_query($con, $sql_select_content_2);
												$stringarray_2 = array();
												$descr2 = $data_type_open;
												while ($sql_select_content_2_row = (mysqli_fetch_assoc($sql_select_content_2_result)))
													{	
														if($sql_select_row['EXPORT_URL_LINKS'] == 0)
															{
																$fund_url = "";
															}
														else
															{
																$fund_url = $sql_select_content_2_row['URL'];
															}
														$pos = "http";
														if(empty($fund_url))
															{
																$descr2 .= $sql_select_content_2_row['DESCR']."<br>";
															}
														else if (strpos($fund_url, $pos) === false)
															{
																$descr2 .= "<a href='http://".$fund_url."' target='_blank' >".$sql_select_content_2_row['DESCR']."</a><br>";
															}
														else
															{
																$descr2 = "<a href='".$fund_url."' target='_blank' >".$sql_select_content_2_row['DESCR']."</a><br>";
															}																	
													} 
												$descr2 .= $data_type_close;
												
												array_push($stringarray_2, $descr2);
												
												$descr = implode($sep,$stringarray_2);
												if(($sql_select_row['EXPORT_NOTICE'] == 1) && (!empty($sql_select_content_1_row['INFO'])))
													{
														$descr .= "<span style='font-size:80%'>".$sql_select_content_1_row['INFO']."</span>";
													}
													
												array_push($stringarray, $descr);
											}
										else
											{
												$descr = $data_type_open;
												$descr .= $sql_select_content_1_row['DESCR'];
												if(($sql_select_row['EXPORT_NOTICE'] == 1) && (!empty($sql_select_content_1_row['INFO'])))
													{
														$descr .= "<br><span style='font-size:80%'>".$sql_select_content_1_row['INFO']."</span>";
													}
												$sql_select_content_2 = "SELECT * FROM ".DB_PREFIX."links WHERE ID_TOPIC = ".$sql_select_content_1_row['ID_TOPIC'];
												$sql_select_content_2_result = mysqli_query($con, $sql_select_content_2);
												$stringarray_2 = array();
												$descr .= $list_type_open;
													while ($sql_select_content_2_row = (mysqli_fetch_assoc($sql_select_content_2_result)))
														{	
															$descr2 = $data_type_open;
															if($sql_select_row['EXPORT_URL_LINKS'] == 0)
																{
																	$fund_url = "";
																}
															else
																{
																	$fund_url = $sql_select_content_2_row['URL'];
																}																	
															$pos = "http";
															if(empty($fund_url))
																{
																	$descr2 .= $sql_select_content_2_row['DESCR'];
																}
															else if (strpos($fund_url, $pos) === false)
																{
																	$descr2 .= "<a href='http://".$fund_url."' target='_blank' >".$sql_select_content_2_row['DESCR']."</a>";
																}
															else
																{
																	$descr2 = "<a href='".$fund_url."' target='_blank' >".$sql_select_content_2_row['DESCR']."</a>";
																}
															$descr2 .= $data_type_close;
															array_push($stringarray_2, $descr2);	
														} 
												$descr .= implode($sep,$stringarray_2);
												$descr .= $close_type_close;
												$descr .= $data_type_close;
												array_push($stringarray, $descr);
												
											}
									}
								else
									{
										if($sql_select_row['EXPORT_URL_LINKS'] == 0)
											{
												$fund_url = "";
											}
										else
											{
												$fund_url = $sql_select_content_1_row['URL'];
											}				
										$pos = "http";
										if(empty($fund_url))
											{
												$descr = $data_type_open.$sql_select_content_1_row['DESCR']."</a>".$data_type_close;
											}
										else if (strpos($fund_url, $pos) === false)
											{
												$descr = $data_type_open."<a href='http://".$fund_url."' target='_blank' >".$sql_select_content_1_row['DESCR']."</a>".$data_type_close;
											}
										else
											{
												$descr = $data_type_open."<a href='".$fund_url."' target='_blank' >".$sql_select_content_1_row['DESCR']."</a>".$data_type_close;
											}
										if(($sql_select_row['EXPORT_NOTICE'] == 1) && (!empty($sql_select_content_1_row['INFO'])))
											{
												$descr .= "<span style='font-size:80%'>".$sql_select_content_1_row['INFO']."</span>";
											}
										array_push($stringarray, $descr);	

									}

							}
					}
				echo implode($sep,$stringarray);
				echo "";
				echo implode($stringarray_notice);
				echo $close_type_close;
				echo $cat_title_close;
			}
		echo "</div>";
		echo "<hr>";
		echo "<div class='form-group'>";
			echo "<label for='html_export'>HTML-Code:</label>";
				echo "<textarea class='form-control' id='html_export' rows='3'>";
				echo "</textarea>";
		echo "</div>";

		echo "<script>
			$(document).ready(function(){
				var htmlString = $(\"#HTML-list\").html();
				$(\"#html_export\").html(htmlString);
			});
		</script>";

	echo "</div>";			
}
//Modal: Eintrag bearbeiten

if(isset($_GET['edit_entry'])){
	
	$edit_id = $_POST['edit_id'];
	$type = $_POST['type'];
	if($type === 'links')
	{
		$sql_get_links = "SELECT * FROM ".DB_PREFIX."links WHERE ID = ".$edit_id ;
		$sql_get_links_result = mysqli_query($con, $sql_get_links);
		while ($sql_get_links_rows = mysqli_fetch_assoc($sql_get_links_result))
			{
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
				echo "<div class='tile-body'>";
					echo "<div class='row'>";
						echo "<div class='col-12' >";
							echo "<div style='overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; white-space: nowrap; margin-bottom: 10px;'>";
								echo "<div class='form-group'>";
									echo "<label>Titel</label>";
									echo "<span style='display:block; margin-left: 5px'>";
										echo "<i class='fas fa-pencil-alt fa-xs'></i> ";
										echo "<a style='border: none; color:black; ' class='update lead' href='#' id='descr".$sql_get_links_rows['ID']."' table='links' data-name='DESCR' data-type='text' data-pk='".$sql_get_links_rows['ID']."' data-url='inc/update.php' beschr='Beschreibung'>".$sql_get_links_rows['DESCR']."</a>";
									echo "</span>";
								echo "</div>";
								echo "<div class='form-group'>";
									echo "<label>URL</label>";
									echo "<span style='display:block; margin-left: 5px'>";
										echo "<i class='fas fa-pencil-alt fa-xs'></i> ";
										echo "<a style='border: none; color:black; ' class='lead update' href='#' id='url".$sql_get_links_rows['ID']."' table='links' data-name='URL' data-type='text' data-pk='".$sql_get_links_rows['ID']."' data-url='inc/update.php' beschr='URL'>".$sql_get_links_rows['URL']."</a>";
									echo "</span>";
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
							echo "<button class='btn btn".$has_info."notice btn-block' type='button'>";
								echo "Notizen:";
							echo "</button>";
							echo "<div id='collapseExample".$sql_get_links_rows['ID']."'>";
								echo "<div style='margin-top:10px'>";
									echo "<textarea data-name='INFO' id='textarea_links".$sql_get_links_rows['ID']."' class='update_notizen' table='links' data-pk='".$sql_get_links_rows['ID']."' name='textarea_links".$sql_get_links_rows['ID']."'>";
										echo $sql_get_links_rows['INFO'];
									echo "</textarea>";
								echo "</div>";
								echo "<div style='margin-top:10px'>";	
									echo "<button class='btn btn-outline-success btn-block' id='update_notizen_links".$sql_get_links_rows['ID']."' type='button' ><i class='fas fa-save'></i> Notizen Speichern</button>";
									
									echo "<script>
										$.fn.editable.defaults.mode = \"inline\";
										$(\".update\").editable({
											params: function(params)
												{ 
													var data = {};
													data[\"pk\"] = params.pk;
													data[\"name\"] = params.name;
													data[\"value\"] = params.value;
													data[\"table\"] = $(this).attr(\"table\");
													return data;
												},
											type: \"POST\",
											emptytext: \"Nichts hinterlegt\",			
											success: function(data)
												{
													console.log(data);
												}
										});
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
													console.log(data);
													$.gritter.add({
														title: 'Bearbeiten ok!',
														text: 'Die Änderungen wurden gespeichert!',
														image: 'images/confirm.png',
														time: '1000'
													});		
													console.log(data);
												}
											});
										});
									</script>";					
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			}
	}
return;
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
		echo "<div id='warning' style='text-align: left; display:none'></div>";
		echo "<button style='margin-top: 10px' disabled id='move_button' class='btn btn-success btn-block'>Verschieben</button>";
	echo "</div>";
	
	echo "<script>
		$(\"#move_links_select\").on('change', function(){
			
			var id_next = $(\"#move_links_select option:selected\").val();
			var episode_id_current = $(\"#select_unchecked\").attr('episode_id_current');
			
			if($(\"option:selected\",this).attr('sel') == 'none')
				{
					$(\"#move_button\").attr('disabled', true);
				}
			else
				{
					jQuery.ajax({
						url: \"inc/check.php?check_categories_next=1\",
						data: {	id_next:id_next,
								episode_id_current:episode_id_current
							},
						type: \"POST\",
						success:function(data)	
							{
								$(\"#warning\").html(data);
								console.log(data);
							},
						error:function ()
							{
							}
						});
										
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
	$sql_add_entry = "SELECT * FROM ".DB_PREFIX."view_episode_categories WHERE EPISODE_ID_PODCAST = ".$_SESSION['podcast']." AND ID_EPISODE = ".$episode;	
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

			echo "<div class='row' style='max-height: 300px; overflow-y:hidden' data-simplebar data-simplebar-auto-hide='true'>";
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
			echo "<div class='row' style='max-height: 300px; overflow-y:hidden' data-simplebar id='episodes' data-simplebar-auto-hide='true'>";
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

?>