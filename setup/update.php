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
								Versionsnummer setzen
							</div>
							<div class="tile-body">
								<div class="form-group">
									<div id="result_db_operation">
									</div>
									<div class="tile-footer">
									</div>
									<div id="update_button">
										<button class="btn btn-primary" type="button" id="submit" name="submit">Update!</button>
									</div>
								</div>
							</div>
							  
							<script>							
								$("#submit").on("click", function(){	
				
									$.ajax({
										url: 'check_db.php?update_to_120=1',
										type: 'POST',
										beforeSend: function() { $('#result_db_operation').html('<i class=\"fas fa-spinner fa-pulse\"></i> Datenbankoperationen werden ausgeführt'); },
										data: {},
												success: function(data)
													{
														$("#result_db_operation").html(data);
														$("#update_button").empty().append("<button class='btn btn-primary' type='button' onclick=\"window.location.href='../login.php'\" name='login'>Zum Log-In!</button>");
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