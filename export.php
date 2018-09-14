a<?php
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
	$("#menu_export").addClass("active");
   </script>
   <main class="app-content">
     <div class="app-title">
       <div>
         <h1>Export</h1>
         <p>Beiträge und Themen exportieren</p>
       </div>
       <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><i class="fas fa-upload fa-lg"></i></li>
         <li class="breadcrumb-item"><a href="#">Export</a></li>
       </ul>
     </div>
      <div class="row">
		<div class="col-md-12" id="export_links">
		<div class="tile">
		<div class="tile-title">Export</div>
		<hr>
		<div class="tile-body">
			 <?php
				export();
			?>
	  </div>
	  </div>
      </div>
      </div>
      <div class="row">
		<div class="col-md-12" id="edit" style="display:none">

	  </div>
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