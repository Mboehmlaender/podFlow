<?php
	require('config/dbconnect.php');
	include('inc/functions.php');
?>
<?php
	head();
?>
  <body class="app sidebar-mini rtl">
  

    <!-- Navbar-->
	<?php
	echo "<header class='app-header' style='padding-right: 0px;'><div class='app-header__logo'></div>";
		echo "<div id='slider' class='app-sidebar__toggle' data-toggle='sidebar' aria-label='Hide Sidebar'></div>";
		echo "<style>
		  .app-sidebar__toggle:before {
				content: \"Notizen\";
		  }
		</style>";
		echo "<ul class='app-nav' id='podcast_menu'>";

		echo "</ul>";
	echo "</header>";
	?>
    <!-- Sidebar menu-->
   <main class="app-content" style="margin-left:0px">
	<div class="row">
			<div class="col-12">
				<div class="tile">
					<?php
						if(isset($_GET['notiz']))
						{
							$sql_get_notice = 	"SELECT ".DB_PREFIX."links.INFO AS INFO, ".DB_PREFIX."links.INFO_TOKEN AS INFO_TOKEN FROM links WHERE INFO_TOKEN = '".$_GET['notiz']."' UNION ALL SELECT ".DB_PREFIX."topics.INFO AS INFO, ".DB_PREFIX."topics.INFO_TOKEN AS INFO_TOKEN FROM topics WHERE INFO_TOKEN = '".$_GET['notiz']."'";
							$sql_get_notice_result = mysqli_query($con, $sql_get_notice);
							while ($sql_get_notice_row = mysqli_fetch_assoc($sql_get_notice_result))
							{
								echo $sql_get_notice_row['INFO'];
							}
						}
						else	
						{
							echo "Nichts gesetzt";
						}
					?>
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
    <script src="js/pace.min.js"></script> 

  </body>
</html>