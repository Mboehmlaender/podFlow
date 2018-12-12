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

	navbar_top();
	sidebar();
?>
    <!-- Sidebar menu-->
   <script>
	$("#menu_links").addClass("active");
   </script>
   <main class="app-content">
     <div class="app-title">
       <div>
         <h1>Meine Beiträge</h1>
         <p>Meine Beiträge der gewählten Episode</p>
       </div>
       <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><i class="fas fa-bookmark fa-lg"></i></li>
         <li class="breadcrumb-item"><a href="#">Meine Beiträge</a></li>
       </ul>
     </div>
      <div class="row">
		<div class="col-md-12">
		<?php
			own_entries($_SESSION['userid']);
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
    <script src="js/pace.min.js"></script>

  </body>
</html>