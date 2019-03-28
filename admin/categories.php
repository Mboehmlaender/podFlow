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
	$("#menu_cat").addClass("active");
   </script>
   <main class="app-content">
     <div class="app-title">
       <div>
         <h1>Kategorien</h1>
         <p>Kategorien des gewählten Podcasts bearbeiten</p>
       </div>
       <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><i class="fas fa-tags fa-lg"></i></li>
         <li class="breadcrumb-item"><a href="categories.php">Kategorien</a></li>
       </ul>
     </div>
      <div class="row">
		<div class="col-md-12">
			<div class="tile">
				<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#new_category">Neue Kategorie anlegen</button>
			</div>
	  </div>
      </div>   
      <div class="row">
		<div class="col-md-12">
			  <?php
				categories_edit_list();
			  ?>
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
<div class="modal fade" id="new_category" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Neue Kategorie</h5>
      </div>
      <div class="modal-body">
        <?php
			category_add();
		?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Schließen</button>
        <button type="button" class="btn btn-outline-primary" disabled id="cat_add_send" data-dismiss="modal" podcast="<?php echo $_SESSION['podcast'] ?>">Anlegen</button>
      </div>
    </div>
  </div>
</div>	
  </body>
</html>