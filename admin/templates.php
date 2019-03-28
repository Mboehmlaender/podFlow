/*********************************************************************
    Michael Böhmländer <info@podflow.de>
    Copyright (c)  2019 podflow!
    http://www.podflow.de
    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See license.txt for details.
**********************************************************************/

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
	$("#menu_temp").addClass("active");
   </script>
   <main class="app-content">
     <div class="app-title">
       <div>
         <h1>Vorlagen</h1>
         <p>Vorlagen bearbeiten</p>
       </div>
       <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><i class="fas fa-recycle fa-lg"></i></li>
         <li class="breadcrumb-item"><a href="episoden.php">Vorlagen</a></li>
       </ul>
     </div>
      <div class="row">
		<div class="col-md-12">
			<div class="tile">
				<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#new_template">Neue Vorlage anlegen</button>
			</div>
	  </div>
      </div>      
	  <div class="row">
		<div class="col-md-12">
		<div class="tile">
	  <?php
		template_edit();
	  ?>
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
    <script src="../js/pace.min.js"></script>
   
<div class="modal fade" id="new_template" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Neue Vorlage</h5>
      </div>
      <div class="modal-body">
        <?php
			template_add();
		?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Schließen</button>
        <button type="button" class="btn btn-outline-primary" disabled id="add_new_template" data-dismiss="modal">Anlegen</button>
      </div>
    </div>
  </div>
</div>
  </body>
</html>