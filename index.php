<?php
	include('inc/functions.php');
	require('config/dbconnect.php');
	session();
?>
<?php
	head();
?>
  <body class="app sidebar-mini rtl">
  
 <?php
	select_podcast();
?>
    <!-- Navbar-->
<?php
		if(isset($_POST['change_episode']))
		{
			$_SESSION['cur_episode'] = $_POST['change_episode'];
		}
	navbar_top();
	sidebar();
?>
    <!-- Sidebar menu-->
   <script>
	$("#menu_dash").addClass("active");
   </script>
   <main class="app-content">
     <div class="app-title">
       <div>
         <h1>Dashboard</h1>
         <p>Frontend</p>
       </div>
       <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><i class="fab fa-fort-awesome fa-lg"></i></li>
         <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
       </ul>
     </div>
	<div class="row">
	<?php
		if(empty($_SESSION['podcast']))
		{
			
		echo "<div class='col-md-12 change' change_value='podcast' style='cursor: pointer'>";
			echo "<div class='widget-small primary'><i class='icon fa fas fa-podcast fa-3x fa-fw'></i>";
				echo "<div class='info'>";
					echo "<h4  style='text-transform: none'>Podcast wählen</h4>";
				echo "</div>";
			echo "</div>";
		echo "</div>";		
		}
		if(empty($_SESSION['cur_episode']) && !empty($_SESSION['podcast']))
		{
		echo "<div class='col-md-12 change' change_value='episode' style='cursor: pointer'>";
			echo "<div class='widget-small primary'><i class='icon fas fa-microphone fa-3x fa-fw'></i>";
				echo "<div class='info'>";
					echo "<h4  style='text-transform: none'>Episode wählen</h4>";
				echo "</div>";
			echo "</div>";
		echo "</div>";		
		}
	 ?> 
      </div>
	<div class="row">
		<?php
			dashboard();
		?>
      </div>

    </main>
		<?php
		footer();
	  ?>
    <!-- Essential javascripts for application to work-->
    <script src="js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="js/pace.min.js"></script> 

  </body>
</html>