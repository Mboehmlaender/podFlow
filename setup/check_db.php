<?php

function clearStoredResults(){
    global $con;
    do {
         if ($result = $con->store_result()) 
			{
				$result->free();
			}
        } while ($con->more_results() && $con->next_result());
}

if(isset($_GET['check_version']))
{
	require('../config/dbconnect.php');
	$content=file_get_contents("https://podflow.de/api/data/read.php");
	$data=json_decode($content, true);
	foreach($data[0] as $test)
	{
		$out = $test;
	}
	$split = preg_split("/[\s.]+/",$out);
	$data_part =$split;
	$split_string = $data_part[0].".".$data_part[1].".".$data_part[2].".";
	echo $split_string;
	return;
}

if(isset($_GET['update_to_101'])){
	require('../config/dbconnect.php');
	echo "Setze Versionsnummer";

	$set_version = $_POST['set_version'];
	$query_version = "UPDATE ".DB_PREFIX."INI SET KEYVALUE = '".$set_version."' WHERE KEYWORD = 'PF_VERSION'";
	$result = mysqli_multi_query($con,$query_version);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				printf(mysqli_error($con));
				return;
			}
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}	
}

if(isset($_GET['copy_cat'])){
	require('../config/dbconnect.php');
    global $con;
	$descr = $_POST['descr'];
	$pc = $_POST['pc'];
	$old_id = $_POST['id_podcast_cat'];
	$get_descr = "SELECT DESCR FROM ".DB_PREFIX."categories WHERE DESCR = '".$descr."'";
	$get_descr_result = mysqli_query($con, $get_descr);
	$get_descr_num =  mysqli_num_rows($get_descr_result);	

		$delete_old_cat = "DELETE FROM ".DB_PREFIX."categories WHERE ID= ".$old_id;
 		$result_delete_old_cat = mysqli_query($con,$delete_old_cat);

		$query_copy_cat= "INSERT INTO ".DB_PREFIX."categories (DESCR, MAX_ENTRIES, VISIBLE, REIHENF, ID_PODCAST, EXPORT_CAT, EXPORT_TITLE_CAT, EXPORT_TITLE_TOPICS, EXPORT_URL_LINKS, EXPORT_NOTICE, ID_EXPORT_OPTION) VALUES ('".$descr."', '0', '1', '5', '".$pc."', '1', '1', '1', '1', '1', '1')";
		$result_copy_cat = mysqli_query($con,$query_copy_cat);
		
		$update_epsiode_categories = "UPDATE ".DB_PREFIX."episode_categories SET ID_CATEGORY = ".$con->insert_id." WHERE ID_CATEGORY = ".$old_id." AND ID_EPISODE IN (SELECT ID FROM ".DB_PREFIX."episoden WHERE ID_PODCAST=".$pc.");";
		$update_epsiode_categories .= "UPDATE ".DB_PREFIX."links SET ID_CATEGORY = ".$con->insert_id." WHERE ID_CATEGORY = ".$old_id." AND ID_PODCAST =".$pc.";";
		$update_epsiode_categories .= "UPDATE ".DB_PREFIX."topics SET ID_CATEGORY = ".$con->insert_id." WHERE ID_CATEGORY = ".$old_id." AND ID_PODCAST =".$pc;
 		$result_update_epsiode_categories = mysqli_multi_query($con,$update_epsiode_categories);
 		
		echo $update_epsiode_categories."<br>";
		


}


if(isset($_GET['update_101_to_120'])){
	require('../config/dbconnect.php');
    global $con;
	echo "Erzeuge neue Tabellen";
		
 		//Tabellenstruktur für Tabelle `export_options`
	
		$query = "CREATE TABLE `".DB_PREFIX."export_options` (
		  `ID` int(11) NOT NULL,
		  `DESCR` varchar(255) NOT NULL,
		  `EXAMPLE` varchar(255) DEFAULT NULL,
		  `PH2` varchar(255) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";	
		
		clearStoredResults();
		$result = mysqli_multi_query($con,$query);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				return;
			}
		
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}	 

		
	echo "Aktualisiere Tabellenstruktur";
		
		//Tabellenstruktur aktualisieren
		
		//Struktur der Tabelle `categories`
		$query_alter = "ALTER TABLE `".DB_PREFIX."categories` ADD `ID_PODCAST` INT(11) NOT NULL AFTER `REIHENF`;";	
		$query_alter .= "ALTER TABLE `".DB_PREFIX."categories` ADD `EXPORT_CAT` TINYINT(1) NOT NULL DEFAULT '1';";	
		$query_alter .= "ALTER TABLE `".DB_PREFIX."categories` ADD `EXPORT_TITLE_CAT` TINYINT(1) NOT NULL DEFAULT '1';";	
		$query_alter .= "ALTER TABLE `".DB_PREFIX."categories` ADD `EXPORT_TITLE_TOPICS` TINYINT(1) NOT NULL DEFAULT '1';";	
		$query_alter .= "ALTER TABLE `".DB_PREFIX."categories` ADD `EXPORT_URL_LINKS` TINYINT(1) NOT NULL DEFAULT '1';";	
		$query_alter .= "ALTER TABLE `".DB_PREFIX."categories` ADD `EXPORT_NOTICE` TINYINT(1) NOT NULL DEFAULT '1';";	
		$query_alter .= "ALTER TABLE `".DB_PREFIX."categories` ADD `ID_EXPORT_OPTION` INT(11) NOT NULL DEFAULT '1';";	
		$query_alter .= "ALTER TABLE `".DB_PREFIX."categories` DROP `COLL`;";	
		$query_alter .= "ALTER TABLE `".DB_PREFIX."categories` DROP `ALLOW_TOPICS`;";	
		
		clearStoredResults();
		$result = mysqli_multi_query($con,$query_alter);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				echo $query_alter;
				return;
			}
		
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}	
			
			
			
	echo "Erzeuge Views";

		//Struktur des Views `view_episoden`
		$query_views = "DROP VIEW IF EXISTS `".DB_PREFIX."view_episoden`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".DB_PREFIX."view_episoden`  
		AS  
		select `".DB_PREFIX."podcast`.`ID` AS `PODCAST_ID`,
		`".DB_PREFIX."podcast`.`DESCR` AS `PODCAST_DESCR`,
		`".DB_PREFIX."podcast`.`SHORT` AS `PODCAST_SHORT`,
		`".DB_PREFIX."episoden`.`NUMMER` AS `EPISODEN_NUMMER`,
		`".DB_PREFIX."episoden`.`TITEL` AS `EPISODEN_TITEL`,
		`".DB_PREFIX."episoden`.`DATE` AS `EPISODEN_DATE`,
		`".DB_PREFIX."episoden`.`DONE` AS `EPISODEN_DONE`,
		`".DB_PREFIX."episode_users`.`ID_EPISODE` AS `EPISODE_USERS_ID_EPISODE`,
		`".DB_PREFIX."episode_users`.`ID_USER` AS `EPISODE_USERS_ID_USER`,
		`".DB_PREFIX."users`.`USERNAME` AS `USERS_USERNAME` 
		from (((`".DB_PREFIX."podcast` 
		join `".DB_PREFIX."episoden` on((`".DB_PREFIX."episoden`.`ID_PODCAST` = `".DB_PREFIX."podcast`.`ID`))) 
		join `".DB_PREFIX."episode_users` on((`".DB_PREFIX."episode_users`.`ID_EPISODE` = `".DB_PREFIX."episoden`.`ID`))) 
		join `".DB_PREFIX."users` on((`".DB_PREFIX."users`.`ID` = `".DB_PREFIX."episode_users`.`ID_USER`))) ;";

		//Struktur des Views `view_episode_categories`

		$query_views .= "DROP VIEW IF EXISTS `".DB_PREFIX."view_episode_categories`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".DB_PREFIX."view_episode_categories`  
		AS  
		select `".DB_PREFIX."categories`.`DESCR` AS `DESCR`,
		`".DB_PREFIX."categories`.`VISIBLE` AS `VISIBLE`,
		`".DB_PREFIX."categories`.`MAX_ENTRIES` AS `MAX_ENTRIES`,
		`".DB_PREFIX."categories`.`EXPORT_CAT` AS `EXPORT_CAT`,
		`".DB_PREFIX."categories`.`EXPORT_TITLE_CAT` AS `EXPORT_TITLE_CAT`,
		`".DB_PREFIX."categories`.`EXPORT_TITLE_TOPICS` AS `EXPORT_TITLE_TOPICS`,
		`".DB_PREFIX."categories`.`EXPORT_URL_LINKS` AS `EXPORT_URL_LINKS`,
		`".DB_PREFIX."categories`.`EXPORT_NOTICE` AS `EXPORT_NOTICE`,
		`".DB_PREFIX."categories`.`ID_EXPORT_OPTION` AS `ID_EXPORT_OPTION`,
		`".DB_PREFIX."categories`.`ID_PODCAST` AS `CATEGORIES_ID_PODCAST`,		
		`".DB_PREFIX."episode_categories`.`ID` AS `ID`,
		`".DB_PREFIX."episode_categories`.`ID_EPISODE` AS `ID_EPISODE`,
		`".DB_PREFIX."categories`.`REIHENF` AS `REIHENF`,
		`".DB_PREFIX."episode_categories`.`ID_CATEGORY` AS `ID_CATEGORY`,
		`".DB_PREFIX."episoden`.`DONE` AS `EPISODE_DONE`,
		`".DB_PREFIX."episoden`.`ID_PODCAST` AS `EPISODE_ID_PODCAST` 
		from ((`".DB_PREFIX."categories` 
		join `".DB_PREFIX."episode_categories` on((`".DB_PREFIX."episode_categories`.`ID_CATEGORY` = `".DB_PREFIX."categories`.`ID`))) 
		join `".DB_PREFIX."episoden` on((`".DB_PREFIX."episoden`.`ID` = `".DB_PREFIX."episode_categories`.`ID_EPISODE`))) ;";

		//Struktur des Views `view_episode_users`

		$query_views .= "DROP VIEW IF EXISTS `".DB_PREFIX."view_episode_users`;

		CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `".DB_PREFIX."view_episode_users`  
		AS  
		select `".DB_PREFIX."episode_users`.`ID_EPISODE` AS `EPISODE_USERS_ID_EPISODE`,
		`".DB_PREFIX."episode_users`.`ID_USER` AS `EPISODE_USERS_ID_USER`,
		`".DB_PREFIX."users`.`NAME_SHOW` AS `USERS_NAME_SHOW`,
		`".DB_PREFIX."episoden`.`DONE` AS `EPISODE_DONE` 
		from ((`".DB_PREFIX."episode_users` 
		join `".DB_PREFIX."users` on((`".DB_PREFIX."users`.`ID` = `".DB_PREFIX."episode_users`.`ID_USER`))) 
		join `".DB_PREFIX."episoden` on((`".DB_PREFIX."episoden`.`ID` = `".DB_PREFIX."episode_users`.`ID_EPISODE`))) ;";

		//Struktur des Views `view_links`

		$query_views .= "DROP VIEW IF EXISTS `".DB_PREFIX."view_links`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".DB_PREFIX."view_links`  
		AS  
		select `".DB_PREFIX."links`.`ID` AS `LINKS_ID`,
		`".DB_PREFIX."links`.`ID_USER` AS `LINKS_ID_USER`,
		`".DB_PREFIX."links`.`ID_CATEGORY` AS `LINKS_ID_CATEGORY`,
		`".DB_PREFIX."links`.`ID_TOPIC` AS `LINKS_ID_TOPIC`,
		`".DB_PREFIX."links`.`DESCR` AS `LINKS_DESCR`,
		`".DB_PREFIX."links`.`REIHENF` AS `LINKS_REIHENF`,
		`".DB_PREFIX."links`.`URL` AS `LINKS_URL`,
		`".DB_PREFIX."links`.`INFO` AS `LINKS_INFO`,
		`".DB_PREFIX."links`.`DONE` AS `LINKS_DONE`,
		`".DB_PREFIX."links`.`DONE_TS` AS `LINKS_DONE_TS`,
		`".DB_PREFIX."categories`.`DESCR` AS `CATEGORIES_DESCR`,
		`".DB_PREFIX."categories`.`MAX_ENTRIES` AS `CATEGORIES_MAX_ENTRIES`,
		`".DB_PREFIX."categories`.`VISIBLE` AS `CATEGORIES_VISIBLE`,
		`".DB_PREFIX."episoden`.`ID` AS `EPISODEN_ID`,
		`".DB_PREFIX."episoden`.`NUMMER` AS `EPISODEN_NUMMER`,
		`".DB_PREFIX."episoden`.`TITEL` AS `EPISODEN_TITEL`,
		`".DB_PREFIX."episoden`.`DONE` AS `EPISODEN_DONE`,
		`".DB_PREFIX."topics`.`ID_USER` AS `TOPICS_ID_USER`,
		`".DB_PREFIX."topics`.`ID_EPISODE` AS `TOPICS_ID_EPISODE`,
		`".DB_PREFIX."topics`.`ID_CATEGORY` AS `TOPICS_ID_CATEGORY`,
		`".DB_PREFIX."topics`.`ID` AS `TOPICS_ID`,
		`".DB_PREFIX."topics`.`DESCR` AS `TOPICS_DESCR`,
		`".DB_PREFIX."topics`.`REIHENF` AS `TOPICS_REIHENF`,
		`".DB_PREFIX."topics`.`DONE` AS `TOPICS_DONE`,
		`".DB_PREFIX."topics`.`DONE_TS` AS `TOPICS_DONE_TS` 
		from (((`".DB_PREFIX."links` 
		left join `".DB_PREFIX."categories` on(((`".DB_PREFIX."categories`.`ID` = `".DB_PREFIX."links`.`ID_CATEGORY`) and (`".DB_PREFIX."categories`.`ID` = `".DB_PREFIX."links`.`ID_CATEGORY`)))) 
		join `".DB_PREFIX."episoden` on((`".DB_PREFIX."episoden`.`ID` = `".DB_PREFIX."links`.`ID_EPISODE`))) 
		left join `".DB_PREFIX."topics` on(((`".DB_PREFIX."topics`.`ID_CATEGORY` = `".DB_PREFIX."categories`.`ID`) and (`".DB_PREFIX."topics`.`ID` = `".DB_PREFIX."links`.`ID_TOPIC`)))) ;";

		//Struktur des Views `view_podcasts_users`
		
		$query_views .= "DROP VIEW IF EXISTS `".DB_PREFIX."view_podcasts_users`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".DB_PREFIX."view_podcasts_users`  
		AS  
		select `".DB_PREFIX."podcast_users`.`ID_PODCAST` AS `PODCASTS_USERS_ID_PODCAST`,
		`".DB_PREFIX."podcast_users`.`ID_USER` AS `PODCASTS_USERS_ID_USER`,
		`".DB_PREFIX."users`.`NAME_SHOW` AS `USERS_NAME_SHOW`,
		`".DB_PREFIX."podcast`.`SHORT` AS `PODCAST_SHORT`,
		`".DB_PREFIX."podcast`.`COLOR` AS `PODCAST_COLOR` 
		from ((`".DB_PREFIX."podcast_users` 
		join `".DB_PREFIX."users` on((`".DB_PREFIX."users`.`ID` = `".DB_PREFIX."podcast_users`.`ID_USER`))) 
		join `".DB_PREFIX."podcast` on((`".DB_PREFIX."podcast`.`ID` = `".DB_PREFIX."podcast_users`.`ID_PODCAST`))) ;";

		//Struktur des Views `view_topics`
		
		$query_views .= "DROP VIEW IF EXISTS `".DB_PREFIX."view_topics`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".DB_PREFIX."view_topics`  
		AS  
		select `".DB_PREFIX."topics`.`ID` AS `TOPICS_ID`,
		`".DB_PREFIX."topics`.`ID_USER` AS `TOPICS_ID_USER`,
		`".DB_PREFIX."topics`.`ID_CATEGORY` AS `TOPICS_ID_CATEGORY`,
		`".DB_PREFIX."topics`.`DESCR` AS `TOPICS_DESCR`,
		`".DB_PREFIX."topics`.`INFO` AS `TOPICS_INFO`,
		`".DB_PREFIX."topics`.`DONE` AS `TOPICS_DONE`,
		`".DB_PREFIX."topics`.`DONE_TS` AS `TOPICS_DONE_TS`,
		`".DB_PREFIX."categories`.`ID` AS `CATEGORIES_ID`,
		`".DB_PREFIX."categories`.`DESCR` AS `CATEGORIES_DESCR`,
		`".DB_PREFIX."categories`.`MAX_ENTRIES` AS `CATEGORIES_MAX_ENTRIES`,
		`".DB_PREFIX."categories`.`VISIBLE` AS `CATEGORIES_VISIBLE`,
		`".DB_PREFIX."episoden`.`ID` AS `EPISODEN_ID`,
		`".DB_PREFIX."episoden`.`NUMMER` AS `EPISODEN_NUMMER`,
		`".DB_PREFIX."episoden`.`TITEL` AS `EPISODEN_TITEL`,
		`".DB_PREFIX."episoden`.`DONE` AS `EPISODEN_DONE` from ((`".DB_PREFIX."topics` join `".DB_PREFIX."categories` on(((`".DB_PREFIX."topics`.`ID_CATEGORY` = `".DB_PREFIX."topics`.`ID_CATEGORY`) and (`".DB_PREFIX."categories`.`ID` = `".DB_PREFIX."topics`.`ID_CATEGORY`)))) join `".DB_PREFIX."episoden` on((`".DB_PREFIX."episoden`.`ID` = `".DB_PREFIX."topics`.`ID_EPISODE`))) ;";

		//Struktur des Views `view_users_usergroups`
		
		$query_views .= "DROP VIEW IF EXISTS `".DB_PREFIX."view_users_usergroups`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".DB_PREFIX."view_users_usergroups`  
		AS  
		select `".DB_PREFIX."users`.`USERNAME` AS `USER_NAME`,
		`".DB_PREFIX."users`.`NAME_SHOW` AS `USER_NAME_SHOW`,
		`".DB_PREFIX."users`.`EMAIL` AS `USER_EMAIL`,
		`".DB_PREFIX."users`.`AVATAR` AS `USER_AVATAR`,
		`".DB_PREFIX."users`.`ID` AS `ID_USER`,
		`".DB_PREFIX."users`.`LEVEL_ID` AS `ID_LEVEL`,
		`".DB_PREFIX."usergroups`.`DESCR` AS `USERGROUPS_DESCR` from (`".DB_PREFIX."users` 
		join `".DB_PREFIX."usergroups` on((`".DB_PREFIX."usergroups`.`LEVEL` = `".DB_PREFIX."users`.`LEVEL_ID`))) ;";
				
		clearStoredResults();					
		$result = mysqli_multi_query($con,$query_views);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				return;
			}
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}
			
	echo "Erzeuge Primärschlüssel";
		
		//Indizes für die Tabelle `export_options`
		  
		$query_keys = "ALTER TABLE `".DB_PREFIX."export_options`
		  ADD PRIMARY KEY (`ID`);";			

		clearStoredResults();					
				$result = mysqli_multi_query($con,$query_keys);
				if(!$result)
					{
						echo " --> <span style='color:red'>FEHLER</span>";
						return;
					}
				else
					{
						echo " --> <span style='color:green'>OK!</span><br>";
					}	

	echo "Erzeuge Autoincrements";
		
		//AUTO_INCREMENT für Tabelle `export_options`
	
		$query_incs = "ALTER TABLE `".DB_PREFIX."export_options`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";
		
		clearStoredResults();					
				$result = mysqli_multi_query($con,$query_incs);
				if(!$result)
					{
						echo " --> <span style='color:red'>FEHLER</span>";
						return;
					}
				else
					{
						echo " --> <span style='color:green'>OK!</span><br>";
					}	
					 
	echo "Befülle die INI-Tabelle";
		
		//Befüllen der INI-Tabelle
				
			$query_ini = "DELETE FROM `".DB_PREFIX."ini` WHERE `".DB_PREFIX."ini`.`KEYWORD` = 'COLL';";
			$query_ini .= "DELETE FROM `".DB_PREFIX."ini` WHERE `".DB_PREFIX."ini`.`KEYWORD` = 'ALLOW_TOPICS';";
			$query_ini .= "UPDATE `".DB_PREFIX."ini` SET KEYVALUE = '1.2.0.', INFO='Barracuda' WHERE `".DB_PREFIX."ini`.`KEYWORD` = 'PF_VERSION';";

			clearStoredResults();					
			$result = mysqli_multi_query($con,$query_ini);
			if(!$result)
				{
					echo " --> <span style='color:red'>FEHLER</span>";
					echo $query_ini;
					return;
				}
			else
				{
					echo " --> <span style='color:green'>OK!</span><p>";
				}	
				
	echo "Befülle die export_options-Tabelle";
		
		//Befüllen der export_options
				
			$query_export_options = "INSERT INTO `".DB_PREFIX."export_options` (`DESCR`, `EXAMPLE`, `PH2`) VALUES
			('Liste (Bindestriche)', 'Link 1 - Link 2 - Link 3', NULL),
			('Aufzählung (nummeriert)', '<ol>\r\n<li>Link 1</li>\r\n<li>Link 2</li>\r\n<li>Link 3 </li>\r\n</ol>', NULL),
			('Aufzählung (bullets)', '<ul>\r\n<li>Link 1</li>\r\n<li>Link 2</li>\r\n<li>Link 3 </li>\r\n</ul>', NULL),
			('Aufzählung (ohne Formatierung)', '<ul style=\"list-style-type:none\">\r\n<li>Link 1</li>\r\n<li>Link 2</li>\r\n<li>Link 3 </li>\r\n</ul>', NULL);";
			clearStoredResults();					
			$result = mysqli_multi_query($con,$query_export_options);
			if(!$result)
				{
					echo " --> <span style='color:red'>FEHLER</span>";
					echo $query_export_options;
					return;
				}
			else
				{
					echo " --> <span style='color:green'>OK!</span><p>";
				}	
				
	echo "Entferne die Kategorien in den Templates";
		
		//Befüllen der INI-Tabelle
				
			$query_templates = "UPDATE `".DB_PREFIX."episode_templates` SET CATEGORIES = NULL";

			clearStoredResults();					
			$result = mysqli_multi_query($con,$query_templates);
			if(!$result)
				{
					echo " --> <span style='color:red'>FEHLER</span>";
					echo $query_ini;
					return;
				}
			else
				{
					echo " --> <span style='color:green'>OK!</span><p>";
				}	

	echo "<p class='lead' style='color:green'>Aktualisiert auf Version 1.2.0. \"Barracuda\"</p>";
	echo "<p class='lead' >Bitte auf \"Weiter\" drücken, um die Konfiguration vorzunehmen</p>";
}

if(isset($_GET['check_connection'])){

	$database_host = $_POST['database_host'];
	$database_name = $_POST['database_name'];
	$database_username = $_POST['database_username'];
	$database_password = $_POST['database_password'];

	$con = @mysqli_connect($database_host, $database_username, $database_password);

	if (!$con) 
		{
			echo "<hr><h4><span style='color:red; font-weight:bold'>Es konnte keine Verbindung zur Datenbank hergestellt werden!</span></h4><hr>";
			echo "<script>
					$(\"#submit\").attr('disabled',true);
				</script>";
			return;
		}
	else
		{
			$db_selected = mysqli_select_db($con, $database_name);
			if(!$db_selected)
				{
					echo "<hr><h4><span style='color:red; font-weight:bold'>Der Datenbankname wurde nicht gefunden!</span></h4><hr>";
					echo "<script>
							$(\"#submit\").attr('disabled',true);
						</script>";
					return;
				}
			echo "<hr><h4><span style='color:green; font-weight:bold'>Die Verbindung konnte hergestellt werden</span><br></h4><hr>";	
			$a = mysqli_get_server_info($con);
			$b = substr($a, 0, 3);
			if($b < '5.1')
				{
					echo "<hr><h4><span style='color:red; font-weight:bold'>Die SQL-Datenbank ist nicht kompatibel!</span></h4><hr>";	
					echo "<script>
							$(\"#submit\").attr('disabled',true);
						</script>";
					return;			
				}
			else{
			echo "<script>
					$(\"#submit\").removeAttr('disabled');
				</script>";
		}
	mysqli_close($con);
	return;
	}
}
	
if(isset($_GET['create_database'])){

	$database_host = $_POST['database_host'];
	$database_name = $_POST['database_name'];
	$database_username = $_POST['database_username'];
	$database_password = $_POST['database_password'];
	$database_prefix = $_POST['database_prefix'];
	
	$admin_name = $_POST['admin_name'];
	$admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
	
	$pc_short = $_POST['pc_short'];

	$con = mysqli_connect($database_host, $database_username, $database_password);
	mysqli_select_db($con,$database_name);

	echo "Erzeuge benötigte Tabellen";

		//Tabellenstruktur für Tabelle `categories`
		
		$query = "CREATE TABLE `".$database_prefix."categories` (
		  `ID` int(11) NOT NULL,
		  `DESCR` varchar(100) NOT NULL,
		  `MAX_ENTRIES` int(11) NOT NULL DEFAULT '0',
		  `VISIBLE` tinyint(1) NOT NULL DEFAULT '0',
		  `REIHENF` int(10) DEFAULT NULL,
		  `ID_PODCAST` int(11) NOT NULL,
		  `EXPORT_CAT` tinyint(1) NOT NULL DEFAULT '1',
		  `EXPORT_TITLE_CAT` tinyint(1) NOT NULL DEFAULT '1',
		  `EXPORT_TITLE_TOPICS` tinyint(1) NOT NULL DEFAULT '1',
		  `EXPORT_URL_LINKS` tinyint(1) NOT NULL DEFAULT '1',
		  `EXPORT_NOTICE` tinyint(1) NOT NULL DEFAULT '1',
		  `ID_EXPORT_OPTION` int(11) NOT NULL DEFAULT '1'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		//Tabellenstruktur für Tabelle `episoden`
		
		$query .= "CREATE TABLE `".$database_prefix."episoden` (
		  `ID` int(11) NOT NULL,
		  `NUMMER` text NOT NULL,
		  `TITEL` text,
		  `ID_PODCAST` int(11) NOT NULL,
		  `DATE` date DEFAULT NULL,
		  `DONE` tinyint(1) DEFAULT '0'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		

		//Tabellenstruktur für Tabelle `episode_categories`
		
		$query .= "CREATE TABLE `".$database_prefix."episode_categories` (
		  `ID` int(11) NOT NULL,
		  `ID_EPISODE` int(11) NOT NULL,
		  `ID_CATEGORY` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		//Tabellenstruktur für Tabelle `episode_templates`

		$query .= "CREATE TABLE `".$database_prefix."episode_templates` (
		  `ID` int(11) NOT NULL,
		  `DESCR` varchar(255) NOT NULL,
		  `CATEGORIES` text,
		  `USERS` text,
		  `ID_PODCAST` int(11) NOT NULL,
		  `INFO` text
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		//Tabellenstruktur für Tabelle `episode_users`

		$query .= "CREATE TABLE `".$database_prefix."episode_users` (
		  `ID` int(11) NOT NULL,
		  `ID_EPISODE` int(11) NOT NULL,
		  `ID_USER` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		//Tabellenstruktur für Tabelle `export_options`
		
		$query .= "CREATE TABLE `".$database_prefix."export_options` (
		  `ID` int(11) NOT NULL,
		  `DESCR` varchar(255) NOT NULL,
		  `EXAMPLE` varchar(255) DEFAULT NULL,
		  `PH2` varchar(255) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		//Tabellenstruktur für Tabelle `ini`

		$query .= "CREATE TABLE `".$database_prefix."ini` (
		  `ID` int(11) NOT NULL,
		  `KEYWORD` varchar(100) NOT NULL,
		  `SETTING` varchar(100) NOT NULL DEFAULT '0',
		  `KEYVALUE` varchar(100) NOT NULL,
		  `INFO` varchar(200) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		//Tabellenstruktur für Tabelle `links`

		$query .= "CREATE TABLE `".$database_prefix."links` (
		  `ID` int(11) NOT NULL,
		  `ID_USER` int(11) NOT NULL,
		  `ID_EPISODE` int(11) NOT NULL,
		  `ID_TOPIC` int(11) DEFAULT NULL,
		  `ID_CATEGORY` int(11) DEFAULT NULL,
		  `ID_PODCAST` int(10) DEFAULT NULL,
		  `DESCR` text NOT NULL,
		  `REIHENF` int(11) DEFAULT NULL,
		  `URL` text,
		  `INFO` text,
		  `DONE` tinyint(1) DEFAULT '0',
		  `DONE_TS` timestamp NULL DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		//Tabellenstruktur für Tabelle `podcast`
		$query .= "CREATE TABLE `".$database_prefix."podcast` (
		  `ID` int(11) NOT NULL,
		  `DESCR` varchar(100) NOT NULL,
		  `SHORT` varchar(10) NOT NULL,
		  `COLOR` varchar(100) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		//Tabellenstruktur für Tabelle `podcast_users`

		$query .= "CREATE TABLE `".$database_prefix."podcast_users` (
		  `ID` int(11) NOT NULL,
		  `ID_PODCAST` int(11) NOT NULL,
		  `ID_USER` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		//Tabellenstruktur für Tabelle `topics`

		$query .= "CREATE TABLE `".$database_prefix."topics` (
		  `ID` int(11) NOT NULL,
		  `ID_USER` int(11) DEFAULT NULL,
		  `ID_EPISODE` int(11) NOT NULL,
		  `ID_CATEGORY` int(11) NOT NULL,
		  `ID_PODCAST` int(10) DEFAULT NULL,
		  `DESCR` text NOT NULL,
		  `REIHENF` int(11) DEFAULT NULL,
		  `INFO` text,
		  `DONE` int(11) DEFAULT '0',
		  `DONE_TS` timestamp NULL DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		//Tabellenstruktur für Tabelle `usergroups`

		$query .= "CREATE TABLE `".$database_prefix."usergroups` (
		  `ID` int(11) NOT NULL,
		  `DESCR` varchar(100) NOT NULL,
		  `LEVEL` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		//Tabellenstruktur für Tabelle `users`

		$query .= "CREATE TABLE `".$database_prefix."users` (
		  `ID` int(10) NOT NULL,
		  `USERNAME` varchar(255) NOT NULL,
		  `NAME_SHOW` varchar(100),
		  `EMAIL` varchar(255),
		  `PASSWORD` varchar(255) NOT NULL,
		  `LEVEL_ID` int(11) NOT NULL,
		  `ACTIVE` int(1) DEFAULT '1',
		  `AVATAR` varchar(100)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		clearStoredResults();
		$result = mysqli_multi_query($con,$query);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				return;
			}
		
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}
		
		echo "Erzeuge Views";

		//Struktur des Views `view_episoden`
		$query_views = "DROP TABLE IF EXISTS `".$database_prefix."view_episoden`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_episoden`  
		AS  
		select `".$database_prefix."podcast`.`ID` AS `PODCAST_ID`,
		`".$database_prefix."podcast`.`DESCR` AS `PODCAST_DESCR`,		`".$database_prefix."podcast`.`SHORT` AS `PODCAST_SHORT`,		`".$database_prefix."episoden`.`NUMMER` AS `EPISODEN_NUMMER`,		`".$database_prefix."episoden`.`TITEL` AS `EPISODEN_TITEL`,		`".$database_prefix."episoden`.`DATE` AS `EPISODEN_DATE`,		`".$database_prefix."episoden`.`DONE` AS `EPISODEN_DONE`,		`".$database_prefix."episode_users`.`ID_EPISODE` AS `EPISODE_USERS_ID_EPISODE`,		`".$database_prefix."episode_users`.`ID_USER` AS `EPISODE_USERS_ID_USER`,		`".$database_prefix."users`.`USERNAME` AS `USERS_USERNAME` 
		from (((`".$database_prefix."podcast` 
		join `".$database_prefix."episoden` on((`".$database_prefix."episoden`.`ID_PODCAST` = `".$database_prefix."podcast`.`ID`))) 
		join `".$database_prefix."episode_users` on((`".$database_prefix."episode_users`.`ID_EPISODE` = `".$database_prefix."episoden`.`ID`))) 
		join `".$database_prefix."users` on((`".$database_prefix."users`.`ID` = `".$database_prefix."episode_users`.`ID_USER`))) ;";

		//Struktur des Views `view_episode_categories`

		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_episode_categories`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_episode_categories`  
		AS  
		select `".$database_prefix."categories`.`DESCR` AS `DESCR`,
		`".$database_prefix."categories`.`VISIBLE` AS `VISIBLE`,
		`".$database_prefix."categories`.`MAX_ENTRIES` AS `MAX_ENTRIES`,
		`".$database_prefix."categories`.`EXPORT_CAT` AS `EXPORT_CAT`,
		`".$database_prefix."categories`.`EXPORT_TITLE_CAT` AS `EXPORT_TITLE_CAT`,
		`".$database_prefix."categories`.`EXPORT_TITLE_TOPICS` AS `EXPORT_TITLE_TOPICS`,
		`".$database_prefix."categories`.`EXPORT_URL_LINKS` AS `EXPORT_URL_LINKS`,
		`".$database_prefix."categories`.`EXPORT_NOTICE` AS `EXPORT_NOTICE`,
		`".$database_prefix."categories`.`ID_EXPORT_OPTION` AS `ID_EXPORT_OPTION`,
		`".$database_prefix."categories`.`ID_PODCAST` AS `CATEGORIES_ID_PODCAST`,		
		`".$database_prefix."episode_categories`.`ID` AS `ID`,
		`".$database_prefix."episode_categories`.`ID_EPISODE` AS `ID_EPISODE`,
		`".$database_prefix."categories`.`REIHENF` AS `REIHENF`,
		`".$database_prefix."episode_categories`.`ID_CATEGORY` AS `ID_CATEGORY`,
		`".$database_prefix."episoden`.`DONE` AS `EPISODE_DONE`,
		`".$database_prefix."episoden`.`ID_PODCAST` AS `EPISODE_ID_PODCAST` 
		from ((`".$database_prefix."categories` 
		join `".$database_prefix."episode_categories` on((`".$database_prefix."episode_categories`.`ID_CATEGORY` = `".$database_prefix."categories`.`ID`))) 
		join `".$database_prefix."episoden` on((`".$database_prefix."episoden`.`ID` = `".$database_prefix."episode_categories`.`ID_EPISODE`))) ;";

		//Struktur des Views `view_episode_users`

		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_episode_users`;

		CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `".$database_prefix."view_episode_users`  
		AS  
		select `".$database_prefix."episode_users`.`ID_EPISODE` AS `EPISODE_USERS_ID_EPISODE`,		`".$database_prefix."episode_users`.`ID_USER` AS `EPISODE_USERS_ID_USER`,		`".$database_prefix."users`.`NAME_SHOW` AS `USERS_NAME_SHOW`,		`".$database_prefix."episoden`.`DONE` AS `EPISODE_DONE` 
		from ((`".$database_prefix."episode_users` 
		join `".$database_prefix."users` on((`".$database_prefix."users`.`ID` = `".$database_prefix."episode_users`.`ID_USER`))) 
		join `".$database_prefix."episoden` on((`".$database_prefix."episoden`.`ID` = `".$database_prefix."episode_users`.`ID_EPISODE`))) ;";

		//Struktur des Views `view_links`

		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_links`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_links`  
		AS  
		select `".$database_prefix."links`.`ID` AS `LINKS_ID`,		`".$database_prefix."links`.`ID_USER` AS `LINKS_ID_USER`,		`".$database_prefix."links`.`ID_CATEGORY` AS `LINKS_ID_CATEGORY`,		`".$database_prefix."links`.`ID_TOPIC` AS `LINKS_ID_TOPIC`,		`".$database_prefix."links`.`DESCR` AS `LINKS_DESCR`,		`".$database_prefix."links`.`REIHENF` AS `LINKS_REIHENF`,		`".$database_prefix."links`.`URL` AS `LINKS_URL`,		`".$database_prefix."links`.`INFO` AS `LINKS_INFO`,		`".$database_prefix."links`.`DONE` AS `LINKS_DONE`,		`".$database_prefix."links`.`DONE_TS` AS `LINKS_DONE_TS`,		`".$database_prefix."categories`.`DESCR` AS `CATEGORIES_DESCR`,		`".$database_prefix."categories`.`MAX_ENTRIES` AS `CATEGORIES_MAX_ENTRIES`,		`".$database_prefix."categories`.`VISIBLE` AS `CATEGORIES_VISIBLE`,		`".$database_prefix."episoden`.`ID` AS `EPISODEN_ID`,		`".$database_prefix."episoden`.`NUMMER` AS `EPISODEN_NUMMER`,		`".$database_prefix."episoden`.`TITEL` AS `EPISODEN_TITEL`,		`".$database_prefix."episoden`.`DONE` AS `EPISODEN_DONE`,		`".$database_prefix."topics`.`ID_USER` AS `TOPICS_ID_USER`,		`".$database_prefix."topics`.`ID_EPISODE` AS `TOPICS_ID_EPISODE`,		`".$database_prefix."topics`.`ID_CATEGORY` AS `TOPICS_ID_CATEGORY`,		`".$database_prefix."topics`.`ID` AS `TOPICS_ID`,		`".$database_prefix."topics`.`DESCR` AS `TOPICS_DESCR`,		`".$database_prefix."topics`.`REIHENF` AS `TOPICS_REIHENF`,		`".$database_prefix."topics`.`DONE` AS `TOPICS_DONE`,		`".$database_prefix."topics`.`DONE_TS` AS `TOPICS_DONE_TS` 
		from (((`".$database_prefix."links` 
		left join `".$database_prefix."categories` on(((`".$database_prefix."categories`.`ID` = `".$database_prefix."links`.`ID_CATEGORY`) and (`".$database_prefix."categories`.`ID` = `".$database_prefix."links`.`ID_CATEGORY`)))) 
		join `".$database_prefix."episoden` on((`".$database_prefix."episoden`.`ID` = `".$database_prefix."links`.`ID_EPISODE`))) 
		left join `".$database_prefix."topics` on(((`".$database_prefix."topics`.`ID_CATEGORY` = `".$database_prefix."categories`.`ID`) and (`".$database_prefix."topics`.`ID` = `".$database_prefix."links`.`ID_TOPIC`)))) ;";

		//Struktur des Views `view_podcasts_users`
		
		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_podcasts_users`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_podcasts_users`  
		AS  
		select `".$database_prefix."podcast_users`.`ID_PODCAST` AS `PODCASTS_USERS_ID_PODCAST`,
		`".$database_prefix."podcast_users`.`ID_USER` AS `PODCASTS_USERS_ID_USER`,
		`".$database_prefix."users`.`NAME_SHOW` AS `USERS_NAME_SHOW`,
		`".$database_prefix."podcast`.`SHORT` AS `PODCAST_SHORT`,
		`".$database_prefix."podcast`.`COLOR` AS `PODCAST_COLOR` 
		from ((`".$database_prefix."podcast_users` 
		join `".$database_prefix."users` on((`".$database_prefix."users`.`ID` = `".$database_prefix."podcast_users`.`ID_USER`))) 
		join `".$database_prefix."podcast` on((`".$database_prefix."podcast`.`ID` = `".$database_prefix."podcast_users`.`ID_PODCAST`))) ;";

		//Struktur des Views `view_topics`
		
		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_topics`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_topics`  
		AS  
		select `".$database_prefix."topics`.`ID` AS `TOPICS_ID`,
		`".$database_prefix."topics`.`ID_USER` AS `TOPICS_ID_USER`,
		`".$database_prefix."topics`.`ID_CATEGORY` AS `TOPICS_ID_CATEGORY`,
		`".$database_prefix."topics`.`DESCR` AS `TOPICS_DESCR`,
		`".$database_prefix."topics`.`INFO` AS `TOPICS_INFO`,
		`".$database_prefix."topics`.`DONE` AS `TOPICS_DONE`,
		`".$database_prefix."topics`.`DONE_TS` AS `TOPICS_DONE_TS`,
		`".$database_prefix."categories`.`ID` AS `CATEGORIES_ID`,
		`".$database_prefix."categories`.`DESCR` AS `CATEGORIES_DESCR`,
		`".$database_prefix."categories`.`MAX_ENTRIES` AS `CATEGORIES_MAX_ENTRIES`,
		`".$database_prefix."categories`.`VISIBLE` AS `CATEGORIES_VISIBLE`,
		`".$database_prefix."episoden`.`ID` AS `EPISODEN_ID`,
		`".$database_prefix."episoden`.`NUMMER` AS `EPISODEN_NUMMER`,
		`".$database_prefix."episoden`.`TITEL` AS `EPISODEN_TITEL`,
		`".$database_prefix."episoden`.`DONE` AS `EPISODEN_DONE` from ((`".$database_prefix."topics` join `".$database_prefix."categories` on(((`".$database_prefix."topics`.`ID_CATEGORY` = `".$database_prefix."topics`.`ID_CATEGORY`) and (`".$database_prefix."categories`.`ID` = `".$database_prefix."topics`.`ID_CATEGORY`)))) join `".$database_prefix."episoden` on((`".$database_prefix."episoden`.`ID` = `".$database_prefix."topics`.`ID_EPISODE`))) ;";

		//Struktur des Views `view_users_usergroups`
		
		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_users_usergroups`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_users_usergroups`  
		AS  
		select `".$database_prefix."users`.`USERNAME` AS `USER_NAME`,
		`".$database_prefix."users`.`NAME_SHOW` AS `USER_NAME_SHOW`,
		`".$database_prefix."users`.`EMAIL` AS `USER_EMAIL`,
		`".$database_prefix."users`.`AVATAR` AS `USER_AVATAR`,
		`".$database_prefix."users`.`ID` AS `ID_USER`,
		`".$database_prefix."users`.`LEVEL_ID` AS `ID_LEVEL`,
		`".$database_prefix."usergroups`.`DESCR` AS `USERGROUPS_DESCR` from (`".$database_prefix."users` 
		join `".$database_prefix."usergroups` on((`".$database_prefix."usergroups`.`LEVEL` = `".$database_prefix."users`.`LEVEL_ID`))) ;";
				
		clearStoredResults();					
		$result = mysqli_multi_query($con,$query_views);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				return;
			}
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}
		
		echo "Erzeuge Primärschlüssel";

		// Indizes für die Tabelle `categories`
		
		$query_keys = "ALTER TABLE `".$database_prefix."categories`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `episoden`
		
		$query_keys .= "ALTER TABLE `".$database_prefix."episoden`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `episode_categories`

		$query_keys .= "ALTER TABLE `".$database_prefix."episode_categories`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `episode_templates`
		
		$query_keys .= "ALTER TABLE `".$database_prefix."episode_templates`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `episode_users`
		
		$query_keys .= "ALTER TABLE `".$database_prefix."episode_users`
		  ADD PRIMARY KEY (`ID`);";
		  
		//Indizes für die Tabelle `export_options`
		  
		$query_keys .= "ALTER TABLE `".$database_prefix."export_options`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `ini`

		$query_keys .= "ALTER TABLE `".$database_prefix."ini`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `links`

		$query_keys .= "ALTER TABLE `".$database_prefix."links`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `podcast`

		$query_keys .= "ALTER TABLE `".$database_prefix."podcast`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `podcast_users`

		$query_keys .= "ALTER TABLE `".$database_prefix."podcast_users`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `topics`

		$query_keys .= "ALTER TABLE `".$database_prefix."topics`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `usergroups`

		$query_keys .= "ALTER TABLE `".$database_prefix."usergroups`
		  ADD PRIMARY KEY (`ID`);";

		//Indizes für die Tabelle `users`

		$query_keys .= "ALTER TABLE `".$database_prefix."users`
		  ADD PRIMARY KEY (`ID`);";
		  
		clearStoredResults();					
		$result = mysqli_multi_query($con,$query_keys);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				return;
			}
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}
		
		echo "Erzeuge Autoincrements";

		//AUTO_INCREMENT für Tabelle `categories`

		$query_incs = "ALTER TABLE `".$database_prefix."categories`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `episoden`

		$query_incs .= "ALTER TABLE `".$database_prefix."episoden`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `episode_categories`

		$query_incs .= "ALTER TABLE `".$database_prefix."episode_categories`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `episode_templates`

		$query_incs .= "ALTER TABLE `".$database_prefix."episode_templates`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `episode_users`

		$query_incs .= "ALTER TABLE `".$database_prefix."episode_users`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `export_options`
	
		$query_incs .= "ALTER TABLE `".$database_prefix."export_options`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";
		  
		//AUTO_INCREMENT für Tabelle `ini`

		$query_incs .= "ALTER TABLE `".$database_prefix."ini`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `links`

		$query_incs .= "ALTER TABLE `".$database_prefix."links`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `podcast`

		$query_incs .= "ALTER TABLE `".$database_prefix."podcast`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `podcast_users`

		$query_incs .= "ALTER TABLE `".$database_prefix."podcast_users`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `topics`

		$query_incs .= "ALTER TABLE `".$database_prefix."topics`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `usergroups`

		$query_incs .= "ALTER TABLE `".$database_prefix."usergroups`
		  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

		//AUTO_INCREMENT für Tabelle `users`

		$query_incs .= "ALTER TABLE `".$database_prefix."users`
		  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;";

		clearStoredResults();					
		$result = mysqli_multi_query($con,$query_incs);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				return;
			}
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			} 

		//Befüllen der INI-Tabelle
		echo "Befülle die INI-Tabelle";
		
		$query_ini = "INSERT INTO `".$database_prefix."ini` (`KEYWORD`, `SETTING`, `KEYVALUE`, `INFO`) VALUES
			('PF_VERSION', '0', '1.2.0.', 'Barracuda'),
			('CATEGORY_VISIBLE', '1', 'fas fa-eye', 'Sichtbar'),
			('CATEGORY_VISIBLE', '0', 'fas fa-eye-slash', 'Nicht sichtbar'),
			('MAX_ENTRIES', '0', 'fas fa-clipboard', 'Beiträge maximal'),
			('PC_PREFIX', '1', '".$pc_short."', 'Podcast Kurzbezeichner'),
			('PC_COLOR', '1', '#FFFFFF', 'Podcast Farbe');";

		clearStoredResults();					
		$result = mysqli_multi_query($con,$query_ini);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				echo $query_ini;
				return;
			}
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}	

	echo "Befülle die export_options-Tabelle";
		
		//Befüllen der export_options
				
			$query_export_options = "INSERT INTO `".$database_prefix."export_options` (`DESCR`, `EXAMPLE`, `PH2`) VALUES
			('Liste (Bindestriche)', 'Link 1 - Link 2 - Link 3', NULL),
			('Aufzählung (nummeriert)', '<ol>\r\n<li>Link 1</li>\r\n<li>Link 2</li>\r\n<li>Link 3 </li>\r\n</ol>', NULL),
			('Aufzählung (bullets)', '<ul>\r\n<li>Link 1</li>\r\n<li>Link 2</li>\r\n<li>Link 3 </li>\r\n</ul>', NULL),
			('Aufzählung (ohne Formatierung)', '<ul style=\"list-style-type:none\">\r\n<li>Link 1</li>\r\n<li>Link 2</li>\r\n<li>Link 3 </li>\r\n</ul>', NULL)";
			clearStoredResults();					
			$result = mysqli_multi_query($con,$query_export_options);
			if(!$result)
				{
					echo " --> <span style='color:red'>FEHLER</span>";
					echo $query_export_options;
					return;
				}
			else
				{
					echo " --> <span style='color:green'>OK!</span><p>";
				}				

		//Eintragen der Benutzergruppen (Benutzer, Admin, Superadmin)
		echo "Erzeuge Nutzergruppen";
		
		$query_usergroups = "INSERT INTO `".$database_prefix."usergroups` (`DESCR`, `LEVEL`) VALUES
			('User', 1),
			('Admin', 2),
			('Superadmin', 3);";

		clearStoredResults();					
		$result = mysqli_multi_query($con,$query_usergroups);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				return;
			}
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}
		
		//Eintragen des ersten Podcasts
		echo "Erzeuge Podcast";
		
		$query_podcast = "INSERT INTO `".$database_prefix."podcast` (`DESCR`, `SHORT`, `COLOR`) VALUES
			('".$pc_short."', '".$pc_short."', '#FFFFFF');";

		clearStoredResults();					
		$result = mysqli_multi_query($con,$query_podcast);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				return;
			}
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}
		
		//Eintragen des Superadmins
		echo "Registriere den Administrator";
		
		$query_admin = "INSERT INTO `".$database_prefix."users` (`USERNAME`, `PASSWORD`, `LEVEL_ID`) VALUES
			('".$admin_name."', '".$admin_password."', '3');";

		clearStoredResults();					
		$result = mysqli_multi_query($con,$query_admin);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				printf(mysqli_error($con));
				return;
			}
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
			}					
	   $f=fopen("../config/dbsettings.php","w");
	   $database_inf="<?php
	define('DB_HOST','".$database_host."');
	define('DB_USER','".$database_username."');
	define('DB_PASSWORD','".$database_password."');
	define('DB_NAME','".$database_name."');
	define('DB_PREFIX','".$database_prefix."');
?>";
	  if (fwrite($f,$database_inf)>0)
		{
			fclose($f);
			echo "<hr><h4>Die Installation ist abgeschlossen. Bitte lösche den Ordner \"setup\".";
			echo "<p>";
			echo "<a href='../login.php'>Zum Login</a></h4>";
			echo "<script>
					$(\"#submit\").attr('disabled', true);
				</script>";
		}
}
?>