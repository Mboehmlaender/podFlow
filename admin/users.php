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
	$("#menu_users").addClass("active");
   </script>
   <main class="app-content">
     <div class="app-title">
       <div>
         <h1>Benutzer</h1>
         <p>Benutzer bearbeiten</p>
       </div>
       <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><i class="fas fa-users fa-lg"></i></li>
         <li class="breadcrumb-item"><a href="users.php">Benutzer</a></li>
       </ul>
     </div>
      <div class="row">
		<div class="col-md-12">
			<div class="tile">
				<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#new_user">Neuen Benutzer anlegen</button>
			</div>
	  </div>
      </div>   
      <div class="row">
		<div class="col-md-12">
	  <?php
		user_search();
	  ?>	 
	  </div>
      </div>

      <div class="row">
		<div class="col-md-12">
	  	  <?php
			users();
		?>	 
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
    <script src="../js/pace.min.js"></script>
   
<!-- Modal -->
<div class="modal fade" id="new_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Neuer Benutzer</h5>
      </div>
      <div class="modal-body">
        <?php
			user_add();
		?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Schließen</button>
        <button type="button" class="btn btn-outline-primary" disabled id="add_user" data-dismiss="modal">Anlegen</button>
      </div>
    </div>
  </div>
</div>	
  </body>
</html>