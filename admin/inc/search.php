<?php 

include('../../config/dbconnect.php');
include('../../inc/config.php');
session_start();
if(!isset($_SESSION['userid']))
	{
		header('Location: ../login.php');
	}		


//Benutzer suchen
if(isset($_GET['search_user']))
{
	$name = mysqli_real_escape_string($con, $_POST['UserSearch']);
	if(empty($name))
		{
			return;
		}

	$sql_user_search = "SELECT * FROM ".DB_PREFIX."users WHERE USERNAME LIKE '%".$name."%' OR NAME_SHOW LIKE '%".$name."%'";
	$sql_user_search_result = mysqli_query($con, $sql_user_search);
	if(mb_strlen($name, 'UTF-8') < 3 )
		{
			echo "<hr>";
			echo "Gib mindestens 3 Buchstaben ein";
			return;
		}
		
	if(mysqli_num_rows($sql_user_search_result) == 0)
		{
			echo "<hr>";
			echo "Kein Benutzer gefunden";
			return;
		}
		
	while($sql_user_search_row = mysqli_fetch_assoc($sql_user_search_result))
		{
			if(($sql_user_search_row['ID'] == $_SESSION['userid']) || (getPermission($_SESSION['userid']) < 3))
				{
					$button_del = 	"<span class='d-inline-block btn-block' tabindex='0' data-toggle='tooltip' title='Das geht nicht!'>";
					$button_del .= 	"<button id='tool' type='button' disabled style='pointer-events: none;' class='btn btn-outline-danger btn-block'><i class='far fa-times-circle'></i></button>";
					$button_del .= 	"</span>";
				}
			else
				{
				$button_del = 	"<button class='btn btn-outline-danger btn-block' onclick='delete_user(".$sql_user_search_row['ID'].")' data-pk='".$sql_user_search_row['ID']."' table ='users'><i class='fas fa-times'></i></button>";
				}
				
			echo "<hr>";
			echo "<div class='row'>";
				echo "<div class='col-md-6 col-6'>";
					echo "<p class='lead'>".$sql_user_search_row['USERNAME']."</p>";
				echo "</div>";
				echo "<div class='col-md-6 col-12'>";
					echo "<div class='row'>";
						echo "<div class='col-md-6 col-12' style='padding: 2px;'>";
							echo "<button class='btn btn-outline-warning btn-block' onclick='edituser(".$sql_user_search_row['ID'].")' data-pk='".$sql_user_search_row['ID']."' table ='users'><i class='fas fa-edit'></i></button>";
						echo "</div>";
						echo "<div class='col-md-6 col-12' style='padding: 2px;'>";
							echo $button_del;
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "<hr>";

			echo "<script>
			$(function (){
				$('[data-toggle=\"tooltip\"]').tooltip()
			});
			</script>"; 
		}
	return;
}

?>