/*********************************************************************
    Michael Böhmländer <info@podflow.de>
    Copyright (c)  2019 podflow!
    http://www.podflow.de
    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See license.txt for details.
**********************************************************************/

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
	$("#menu_profile").addClass("active");
   </script>
   <main class="app-content">
     <div class="app-title">
       <div>
         <h1>Mein Profil</h1>
         <p>Meine Daten</p>
       </div>
       <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><i class="fas fa-user fa-lg"></i></li>
         <li class="breadcrumb-item"><a href="#">Mein Profil</a></li>
       </ul>
     </div>
      <div class="row">
		<div class="col-md-12">
							<?php
							profil_edit();
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