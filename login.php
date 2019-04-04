<?php
	require('inc/functions.php');
	require('config/dbconnect.php');
	session_start();

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
	head();
?>

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
