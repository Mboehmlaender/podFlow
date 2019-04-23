<?php
	require('config/dbconnect.php');
 	if(isset($_GET['login'])) 
		{
			global $con;
			$email = $_POST['email'];
			$passwort = $_POST['passwort'];
			
			$statement_login = $con->prepare("SELECT ID, PASSWORD FROM ".DB_PREFIX."users WHERE USERNAME = ?");
			$statement_login->bind_param('s', $email);
			$statement_login->execute();
			$statement_login->bind_result($id, $result);
			$statement_login->fetch();
			$statement_login->close();
			if (password_verify($passwort, $result)) 
				{
					session_start();
					$_SESSION['cur_episode'] = '';
					$_SESSION['podcast'] = '';
					$_SESSION['userid'] = $id;
					
							header('Location: index.php');						
				}
			else 
				{
					$errorMessage = "Benutzername oder Passwort ungültig<br>";
				}
	 
		} 
		
?>
<!DOCTYPE html>
<html lang="en">

<?php
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
		echo "<link rel='stylesheet' href='css/custom.min.css'>";
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

		
	echo "</head>";?>

  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="login-box">
        <form class="login-form" method="post" action="?login=1" style="padding: 20px">
          <h3 class="login-head"><img src='images/podflow_Logo_v2c.png' style='width: 50px;'></h3>
          <div class="form-group">
            <label class="control-label">Benutzername</label>
            <input class="form-control" name="email" type="text" autofocus>
          </div>
          <div class="form-group">
            <label class="control-label">Passwort</label>
            <input class="form-control" name="passwort" type="password">
          </div>
          <div class="form-group" style="border-bottom: 1px solid #ddd; margin-bottom 10px;">
							<?php 
								if(isset($errorMessage)) 
								{
									echo "<p><p>".$errorMessage."</p>";
								}
							?>
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block">Anmelden</button>
          </div>
        </form>
      </div>
    </section>
  </body>

</html>
