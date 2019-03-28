/*********************************************************************
    Michael Böhmländer <info@podflow.de>
    Copyright (c)  2019 podflow!
    http://www.podflow.de
    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See license.txt for details.
**********************************************************************/

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
							$step = (isset($_GET['step']) && $_GET['step'] != '') ? $_GET['step'] : '';
							switch($step){
								case '1':
								step_1();
								break;
								case '1_5':
								step_1_5();
								break;
								case '2':
								step_2();
								break;
								case '3':
								step_3();
								break;
								case '4':
								step_4();
								break;
								default:
								step_1();
							}
							?>	
							<?php
							function step_1(){ 
							?>
								<div class="tile-title">
									Willkommen bei der Installation von podflow v1.2!
								</div>
								<div class="tile-body">
									<p class="lead">Im Folgenden wird geprüft, ob dein Server die Voraussetzungen für die Installation erfüllt.</p>
								</div>
								
								<div class="tile-footer">
									<button type="button" id="step2" class="btn btn-primary">Weiter</button>
									<script>
										$("#step2").on("click", function(){
											window.location.href = "install.php?step=1_5";
										});
											

									</script>
								</div>
							<?php 
							}	
							function step_1_5(){ 
							?>
								<div class="tile-title">
									Browserkompatibilität
								</div>
								<div class="tile-body">
									<div class='row'>
										<div class='col-xl-2' style="padding-bottom: 10px;">
											<i class="fab fa-chrome fa-fw fa-2x"></i> Google Chrome <i style='color: green' class="fas fa-check"></i>
										</div>
										<div class='col-xl-2' style="padding-bottom: 10px;">
											<i class="fab fa-firefox fa-fw fa-2x"></i> Mozilla Firefox <i style='color: green' class="fas fa-check"></i>
										</div>
										<div class='col-xl-2' style="padding-bottom: 10px;">
											<i class="fab fa-edge fa-fw fa-2x"></i> Microsoft Edge <i style='color: green' class="fas fa-check"></i>
										</div>
										<div class='col-xl-2' style="padding-bottom: 10px;">
											<i class="fab fa-internet-explorer fa-fw fa-2x"></i> Internet Explorer <i style='color: green' class="fas fa-check"></i>
										</div>
										<div class='col-xl-2' style="padding-bottom: 10px;">
											<i class="fab fa-safari fa-fw fa-2x"></i> Safari <i style='color: green' class="fas fa-check"></i>
										</div>
										<div class='col-xl-2' style="padding-bottom: 10px;">
											<i class="fab fa-opera fa-fw fa-2x"></i> Opera <i style='color: green' class="fas fa-check"></i>
										</div>
									</div>
									<hr>
									<p class="lead">podflow wurde mit allen gängigen Browsern sowohl in der Desktopausführung als auch in der mobilen Variante getestet und auf Kompatibilität überprüft.</p>
									<p class="lead">Empfohlen werden Chrome oder Firefox jeweils in aktueller Version.</p>
								</div>
								
								<div class="tile-footer">
									<button type="button" id="step2" class="btn btn-primary">Weiter</button>
									<script>
										$("#step2").on("click", function(){
											window.location.href = "install.php?step=2";
										});
											

									</script>
								</div>
							<?php 
							}	
							function step_2(){
							  ?>
								<div class="tile-title">
									Voraussetzungen
								</div>
								<div class="tile-body">
									<div class="row">
										<div class="col-3">
											<span style="font-weight: bold">Was?</span>
										</div>
										<div class="col-3">
											<span style="font-weight: bold">Aktuell</span>
										</div>
										<div class="col-3">
											<span style="font-weight: bold">Benötigt</span>
										</div>
										<div class="col-3">
											<span style="font-weight: bold">Status</span>
										</div>
									</div>
									<hr>
									<div  class="row" id="php_version" value="<?php echo phpversion(); ?>">
										<div class="col-3">
											PHP Version:
										</div>
										<div class="col-3">
											<?php echo phpversion(); ?>
										</div>
										<div class="col-3">
											7.0+
										</div>
										<div class="col-3">
											<?php echo (phpversion() >= '7.0') ? '<span style="color: green">OK</span>' : '<span style="color: red">Bitte auf eine höhere PHP-Version updaten!</span>'; ?>
										</div>
									</div>
									<hr>
									<div class="row" id="mysqli_ext" value="<?php echo extension_loaded('mysqli') ?>">
										<div class="col-3" >
											MySQLi-Erweiterung:
										</div>
										<div class="col-3">
											<?php echo extension_loaded('mysqli') ? 'Ja' : 'Nein'; ?>
										</div>
										<div class="col-3">
											Ja
										</div>
										<div class="col-3">
											<?php echo extension_loaded('mysqli') ? '<span style="color: green">OK</span>' : '<span style="color: red">MySQLI-Erweiterung in der php.ini aktivieren!</span>'; ?>
										</div>
									</div>
									<hr>
									<?php
									$_SESSION['myscriptname_sessions_work']= "1";
									
										if(empty($_SESSION['myscriptname_sessions_work']))
										{
											$session = "0";
											$session_text = "Nein";
										}
										else
										{
											$session = "1";
											$session_text = "Ja";
										}
									?>
									<div class="row" value="<?php echo $session ?>">
										<div class="col-3">
											Sessions:
										</div>
										<div class="col-3">
											<?php echo $session_text ?>
										</div>
										<div class="col-3">
											Ja
										</div>							
										<div class="col-3">
											<?php 

												if($session == 1)
												{
													echo "<span style='color: green'>OK</span>";
												}
												else
												{
													echo "<span style='color: red'>Sessions aktivieren</span>";
												}
											
											
											?>
										</div>
									</div>
									<hr>
									<div class="row" id="write" value="<?php echo is_writable('../config/dbsettings.php')?>">
										<div class="col-3">
											dbsettings.php beschreibbar:
										</div>
										<div class="col-3">
											<?php echo is_writable('../config/dbsettings.php') ? 'Schreibrechte vorhanden' : 'Schreibrechte nicht vorhanden'; ?>
										</div>
										<div class="col-3">
											Schreibrechte
										</div>
										<div class="col-3">
											<?php echo is_writable('../config/dbsettings.php') ? '<span style="color: green">OK</span>' : '<span style="color: red">Datei config/dbsettings.php mit Schreibrechten versehen</span>'; ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-3">
											Datenbank:
										</div>
										<div class="col-6">
											MySQL >= 5.1
										</div>
										<div class="col-3">
											Wird im nächsten Schritt geprüft
										</div>
									</div>
								</div>
								<div class="tile-footer">
									<button type="button" id="step3" class="btn btn-primary">Weiter</button>
									<script>
										var php_version = $("#php_version").attr('value');
										var mysqli_ext = $("#mysqli_ext").attr('value');
										var write = $("#write").attr('value');
										

										if(php_version.substr(0, 1) < 7 || mysqli_ext != 1 || write != 1)
										{
											console.log(sub)
										$("#step3").attr('disabled', true);
										}
										$("#step3").on("click", function(){
											window.location.href = "install.php?step=3";
										});
											

									</script>					</div>
							 
							<?php
							}

							function step_3(){
								?>
							<div class="tile-title">
								Server und Administration
							</div>
							<div class="tile-body">
								<div class="form-group">
									<label for="database_host">Datenbank-Server<span style="color:red">*</span></label>
									<input type="text" class="form-control" id="database_host" name="database_host" value='localhost' size="30">
								</div>
								<div class="form-group">
									<label for="database_name">Datenbank-Name<span style="color:red">*</span></label>
									<input type="text" class="form-control" id="database_name" name="database_name" size="30">
									</div>				
								<div class="form-group">
									<label for="database_name">Datenbank-Präfix</label>
									<input type="text" class="form-control" id="database_prefix" name="database_prefix" size="30" value="pf_">
								</div>
								<div class="form-group">
									<label for="database_username">Datenbank-Benutzer<span style="color:red">*</span></label>
									<input type="text" class="form-control" id="database_username" name="database_username" size="30">
								</div>
								<div class="form-group">
									<label for="database_password">Datenbank-Passwort</label>
									<input type="text" class="form-control" id="database_password" name="database_password" size="30">
								</div>
								<button class="btn btn-primary" type="button" id="test_conn">Datenbankverbindung testen</button>
								<br/>
								<div id="result_db_test">
								</div>
								<br/>
								<div class="form-group">
									<label for="username">Administrator-Benutzername<span style="color:red">*</span></label>
									<input type="text" class="form-control" id="admin_name" name="admin_name" size="30">
								</div>
								<div class="form-group">
									<label for="password">Administrator-Passwort<span style="color:red">*</span></label>
									<input type="password" class="form-control" id="admin_password" name="admin_password" size="30">
								</div>					
								<div class="form-group">
									<label for="password">Administrator-Passwort wiederholen<span style="color:red">*</span></label>
									<input type="password" class="form-control" id="admin_password_repeat" name="admin_password_repeat" size="30">
								</div>				
								<div class="form-group">
									<label for="pc_short">Podcast-Kurzbezeichner<span style="color:red">*</span></label>
									<input type="text" maxlength='5' class="form-control" id="pc_short" name="pc_short" size="30" maxlength="15">
								</div>
								<div class="form-group">
									<div id="result_db_operation">
									</div>
									<div class="tile-footer">
									</div>
								<button hidden class="btn btn-primary" type="button" id="submit" name="submit" disabled>Installieren!</button>
								<small style="float:right"><span style="font-weight: bold; color: red">*</span> = Pflichtfeld</small>
								</div>
							</div>
							  
							<script>
								$("#test_conn").on("click", function(){
									var database_host = $("#database_host").val();
									var database_name = $("#database_name").val();
									var database_username = $("#database_username").val();
									var database_password = $("#database_password").val();
									
									if(database_name == '' && database_username == '')
										{
											$("#result_db_test").html('<hr><h4><span style=\"font-weight: bold; color: red\">Bitte die Pflichtfelder ausfüllen!</span></h4><hr>')
											return;
										}
									
									$.ajax({
										url: 'check_db.php?check_connection=1',
										type: 'POST',
										data: {
												database_host:database_host,
												database_name:database_name,
												database_username:database_username,
												database_password:database_password
												},
												success: function(data){
												$("#result_db_test").html(data)
												$("#submit").removeAttr('hidden')
											},
										}); 	
								});
								
								$("#submit").on("click", function(){
									var database_host = $("#database_host").val();
									var database_name = $("#database_name").val();
									var database_username = $("#database_username").val();
									var database_password = $("#database_password").val();
									var database_prefix = $("#database_prefix").val();
									
									var pc_short = $("#pc_short").val();
									
									var admin_name = $("#admin_name").val();
									var admin_password = $("#admin_password").val();
									var admin_password_repeat = $("#admin_password_repeat").val();

									if(admin_name == '')
										{
											$("#result_db_operation").html('<hr><h4><span style=\"font-weight: bold; color: red\">Bitte einen Administrator angeben!</span></h4>')
											return;							
										}
									if(admin_password == '')
										{
											$("#result_db_operation").html('<hr><h4><span style=\"font-weight: bold; color: red\">Bitte gib ein Passwort ein!</span></h4>')
											return;							
										}			
									if(pc_short == '')
										{
											$("#result_db_operation").html('<hr><h4><span style=\"font-weight: bold; color: red\">Bitte einen Podcast anlegen!</span>')
											return;							
										}
									if(admin_password_repeat != admin_password)
										{
											$("#result_db_operation").html('<hr><h4><span style=\"font-weight: bold; color: red\">Die Passwörter müssen übereinstimmen!</span></h4>')
											return;							
										}							
				
									$.ajax({
										url: 'check_db.php?create_database=1',
										type: 'POST',
										beforeSend: function() { $('#result_db_operation').html('<i class=\"fas fa-spinner fa-pulse\"></i> Datenbankoperationen werden ausgeführt'); },
										data: {	database_host:database_host,
												database_name:database_name,
												database_username:database_username,
												database_password:database_password,
												database_prefix:database_prefix,
												admin_name:admin_name,
												admin_password:admin_password,
												pc_short:pc_short
											},
												success: function(data)
													{
														$("#result_db_operation").html(data)
													},
										}); 			
								});
							</script>
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