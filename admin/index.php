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
	$("#menu_dash").addClass("active");
   </script>
   <main class="app-content">
     <div class="app-title">
       <div>
         <h1>Dashboard</h1>
         <p>Backend</p>
       </div>
       <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><i class="fab fa-fort-awesome fa-lg"></i></li>
         <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
       </ul>
     </div>
	<div class="row">
        <div class="col-xl-4">
          <a style="text-decoration: none" href="users.php"><div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x fa-fw"></i>
            <div class="info">
              <h4  style="text-transform: none">Benutzer</h4>
            </div>
          </div></a>
        </div>
        <div class="col-xl-4">
           <a style="text-decoration: none" href="podcast.php"><div class="widget-small primary coloured-icon"><i class="icon fa fas fa-podcast fa-3x fa-fw"></i>
            <div class="info">
              <h4  style="text-transform: none">Podcasts</h4>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
           <a style="text-decoration: none" href="categories.php"><div class="widget-small primary coloured-icon"><i class="icon fas fa-tags fa-3x fa-fw"></i>
            <div class="info">
              <h4  style="text-transform: none">Kategorien</h4>
            </div>
          </div></a>
        </div>
        <div class="col-xl-4">
           <a style="text-decoration: none" href="episoden.php"><div class="widget-small primary coloured-icon"><i class="icon fas fa-microphone fa-3x fa-fw"></i>
            <div class="info">
              <h4  style="text-transform: none">Episoden</h4>
            </div>
          </div></a>
        </div>
        <div class="col-xl-4">
           <a style="text-decoration: none" href="templates.php"><div class="widget-small primary coloured-icon"><i class="icon fas fa-recycle fa-3x fa-fw"></i>
            <div class="info">
              <h4  style="text-transform: none">Vorlagen</h4>
            </div>
          </div></a>
        </div>
        <div class="col-xl-4">
           <a style="text-decoration: none" href="../index.php"><div class="widget-small primary coloured-icon"><i class="icon fas fa-chevron-circle-left fa-3x fa-fw"></i>
            <div class="info">
              <h4  style="text-transform: none">Frontend</h4>
            </div>
          </div></a>
        </div>
      </div>
	<div class="row">
		<div class="col-12">
			<button class="btn btn-primary btn-block check_version" data-toggle="collapse" href="#chck" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Version prüfen</button>
		</div>
	</div>
	<div class="row">
	 <div class="collapse col-12" id="chck">
		  <div class="card card-body" id="version_check">

		  </div>
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
   

  </body>
</html>