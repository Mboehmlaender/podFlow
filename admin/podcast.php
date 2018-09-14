<?php
	include('inc/functions.php');
	require('../config/dbconnect.php');
	session();
?>
<?php
	head();
?>
  <body class="app sidebar-mini rtl">
    <!-- Navbar-->

<?php
	select_podcast();
	navbar_top();
	sidebar();
?>
    <!-- Sidebar menu-->
   <script>
	$("#menu_pc").addClass("active");
   </script>
   <main class="app-content">
     <div class="app-title">
       <div>
         <h1>Podcasts</h1>
         <p>Infos des Podcasts bearbeiten</p>
       </div>
       <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><i class="fa fas fa-podcast fa-lg"></i></li>
         <li class="breadcrumb-item"><a href="podcast.php">Podcasts</a></li>
       </ul>
     </div>
      <div class="row">
		<div class="col-md-12">
			<div class="tile">
				<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#new_podcast">Neuen Podcast anlegen</button>
			</div>
	  </div>
      </div> 
      <div class="row">
		<div class="col-md-12">
				<?php
					podcast_list();
				?>
	  </div>
      </div>  
	<?php if(!empty($_SESSION['podcast']))
	{
	?>
      <div class="row" id="pc_info">
		<div class="col-md-12">
	  <?php
		podcast_info();
	  ?>
	  </div>
      </div>
	  <?php
	}
	?>
    </main>
		<?php
		footer();
	  ?>
    <!-- Essential javascripts for application to work-->
    <script src="js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="../js/pace.min.js"></script>

<div class="modal fade" id="new_podcast" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Podcast hinzufügen</h5>
      </div>
      <div class="modal-body">
        <?php
			podcast_add();
		?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Schließen</button>
        <button type="button" class="btn btn-outline-primary" disabled id="add_new_podcast" data-dismiss="modal">Anlegen</button>
      </div>
    </div>
  </div>
</div>	

  </body>
</html>