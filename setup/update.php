	<!DOCTYPE html>
	<html lang="de">
	<head>

		<meta charset="utf-8">
		<title>podflow!</title>
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="author" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/png" href="../img/oeha_500x500.png" />

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
		<link rel="stylesheet" href="../css/main.css">
		<link rel="stylesheet" href="../css/jquery.gritter.css">
		<link rel="stylesheet" href="../css/jquery-confirm.min.css">
		<script src="//cdn.ckeditor.com/4.9.2/basic/ckeditor.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="../js/jquery.gritter.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
		<script src="../js/clipboard.min.js"></script>
		<script src="../js/jquery-confirm.min.js"></script>
		<script src="../js/jquery.ui.touch-punch.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
		<link rel="stylesheet" href="../css/all.min.css">	
		<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
		<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
	</head>
	
	<body >
		<main class="app-content" style="margin:0px">
			<div class="row">
				<div class="col-md-12">
					<div class="tile">
						<div class="tile-body">
							<?php
							$step = (isset($_GET['update_step']) && $_GET['update_step'] != '') ? $_GET['update_step'] : '';
							switch($step){
								case '1':
								update_step_1();
								break;
								case '2':
								update_step_2();
								break;
								case '3':
								update_step_3();
								break;
								default:
								update_step_1();
							}
							?>	
							<?php
							function update_step_1(){ 
							?>
								<div class="tile-title">
									Willkommen beim Update von podflow!
								</div>
								<div class="tile-body">
									<p class="lead">Im Folgenden wird deine podflow-Instanz auf die neueste Version upgedatet.</p>
								</div>
								
								<div class="tile-footer">
									<button type="button" id="step2" class="btn btn-primary">Weiter</button>
									<script>
										$("#step2").on("click", function(){
											window.location.href = "update.php?update_step=2";
										});
											

									</script>
								</div>
							<?php 
							}	
							function update_step_2(){
								?>
							<div class="tile-title">
								podflow! Update
							</div>
							<div class="tile-body">
								<p class="lead">Im Folgenden wird deine podflow-Instanz auf die neueste Version upgedatet.</p>
								<p class="lead">Bitte mache vor dem Update eine komplette Sicherung deiner Podflow-Datenbank! </p>
								<p class="lead" style="color:red; font-weight:bold">Die Datenbank wird im Updateprozess nicht gesichert!</p>
								<hr>
								<?php
								require('../config/dbconnect.php');
								$sql = "SELECT * FROM ".DB_PREFIX."ini WHERE KEYWORD = 'PF_VERSION' AND SETTING = '0'";
								$res = mysqli_query($con, $sql);
								$row = mysqli_fetch_row($res);
								echo "Aktuell installiert: ".$row[3]." \"".$row[4]."\"</p>";
								?>
								<div class="form-group">
									<div id="result_db_operation">
									
									</div>
									<div class="tile-footer">
									<div id="update_button">
										<button class="btn btn-primary" version="<?php echo $row[3] ?>" type="button" id="submit" name="submit">Update!</button>
									</div>
									</div>
								</div>
							</div>
							  
							<script>							
								$("#submit").on("click", function(){									
								var version = $(this).attr('version');
									if((version == '1.0.0.') || (version == '1.0.1.'))
									{
										var url = 'check_db.php?update_101_to_120=1';
									}
 									$.ajax({
										url: url,
										type: 'POST',
										beforeSend: function() { $('#result_db_operation').html('<i class=\"fas fa-spinner fa-pulse\"></i> Datenbankoperationen werden ausgeführt'); $("#submit").prop('disabled', true);},
										data: {},
												success: function(data)
													{
														$("#result_db_operation").html(data);
														$("#update_button").empty().append("<button class='btn btn-primary' type='button' onclick=\"window.location.href='update.php?update_step=3'\" name='login'>Weiter</button>");
													},
										});  		
								});
							</script>
							<?php
							}
							function update_step_3(){
								?>
							<div class="tile-title">
								podflow! Update
							</div>
							<div class="tile-body">
							<?
									
							echo "<p><button class='btn btn-success copy'>Doppelte Zuweisungen bereinigen</button><p>";
							echo "<div id='result_db_operation'>";
							echo "</div>";
							echo "<div id='check_result'>";
							require('../config/dbconnect.php');
								$sql_select_categories = "SELECT * FROM ".DB_PREFIX."categories ORDER BY ID";
								$sql_select_categories_result = mysqli_query($con, $sql_select_categories);
								$sql_select_categories_num =  mysqli_num_rows($sql_select_categories_result);
								while($sql_select_categories_row = mysqli_fetch_assoc($sql_select_categories_result))
								{
								echo "<hr>";
									echo "<p style='font-weight:bold'>".$sql_select_categories_row['DESCR'];
									
									$sql_select_view_categories = "SELECT DISTINCT ".DB_PREFIX."podcast.SHORT AS SHORT, ".DB_PREFIX."podcast.ID FROM ".DB_PREFIX."view_episode_categories join ".DB_PREFIX."podcast on ".DB_PREFIX."podcast.ID = ".DB_PREFIX."view_episode_categories.EPISODE_ID_PODCAST WHERE ID_CATEGORY =".$sql_select_categories_row['ID'];
									$sql_select_view_categories_result = mysqli_query($con, $sql_select_view_categories);
									$sql_select_view_categories_num = mysqli_num_rows($sql_select_view_categories_result);
									if($sql_select_view_categories_num > 1)
									{
										$set_podcast_direct = 0;
									}
									else
										
										{
										$set_podcast_direct = 1;
										}

									
									while($sql_select_view_categories_row = mysqli_fetch_assoc($sql_select_view_categories_result))
									{
										if($set_podcast_direct == 1)
										{
											echo "<p class='podcast_short' id_podcast='".$sql_select_view_categories_row['ID']."'>".$sql_select_view_categories_row['SHORT'];
											$query_add_cat = "UPDATE ".DB_PREFIX."categories SET ID_PODCAST = '".$sql_select_view_categories_row['ID']."' WHERE ID = '".$sql_select_categories_row['ID']."'";
											$result_add_cat = mysqli_multi_query($con,$query_add_cat);										
											echo " zugewiesen";
										}
										else
										{
											echo "<p descr='".$sql_select_categories_row['DESCR']."' id_podcast_cat='".$sql_select_categories_row['ID']."' class='multiple_podcast_short' id_podcast='".$sql_select_view_categories_row['ID']."'>".$sql_select_view_categories_row['SHORT'];
								
										}										
									}
									
								}
								echo "</div>";
								echo "<script>
									
									$(\".copy\").on('click', function(){
										
										
									$(\".multiple_podcast_short\").each(function(){
										var id_podcast_cat = $(this).attr('id_podcast_cat');
										var descr = $(this).attr('descr');
										var pc = $(this).attr('id_podcast');
										$.ajax({
										beforeSend: function() { $(\"#result_db_operation\").html(\"<i class='fas fa-spinner fa-pulse'></i> Datenbankoperationen werden ausgeführt\"); },
											url: 'check_db.php?copy_cat=1',
											type: 'POST',
											data: {descr:descr, pc:pc, id_podcast_cat:id_podcast_cat},
													success: function(data)
														{
															console.log(data);
															$(\"#result_db_operation\").html(\"<p class='lead'>Erledigt</p>\");
															$(\"#next\").attr('disabled', false);
															
														},
											}); 	
																					
										});
											
										$(\"#check_result\").remove();
											
											$(this).remove();
										
									});
								</script>";
							?>
											

								<div class="form-group">
									<div id="footer" class="tile-footer">
										<button class="btn btn-primary" id="next" disabled type="button" onclick='window.location.href="update.php?update_step=4"'>Weiter</button>
									</div>
								</div>
								<script>
									if($(".multiple_podcast_short").length == 0)
									{

									$("#check_result").empty().html("<p class='lead'>Keine Anpassung an den Kategorien notwendig!</p>");
									$(".copy").remove();
									$("#next").attr('disabled', false);
									
										
									}
								</script>
							</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</main>
	</body>
</html>