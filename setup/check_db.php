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
		  `ALLOW_TOPICS` tinyint(1) NOT NULL DEFAULT '0',
		  `VISIBLE` tinyint(1) NOT NULL DEFAULT '0',
		  `COLL` int(11) NOT NULL DEFAULT '0',
		  `REIHENF` int(10) DEFAULT NULL
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

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_episoden`  AS  select `".$database_prefix."podcast`.`ID` AS `PODCAST_ID`,`".$database_prefix."podcast`.`DESCR` AS `PODCAST_DESCR`,`".$database_prefix."podcast`.`SHORT` AS `PODCAST_SHORT`,`".$database_prefix."episoden`.`NUMMER` AS `EPISODEN_NUMMER`,`".$database_prefix."episoden`.`TITEL` AS `EPISODEN_TITEL`,`".$database_prefix."episoden`.`DATE` AS `EPISODEN_DATE`,`".$database_prefix."episoden`.`DONE` AS `EPISODEN_DONE`,`".$database_prefix."episode_users`.`ID_EPISODE` AS `EPISODE_USERS_ID_EPISODE`,`".$database_prefix."episode_users`.`ID_USER` AS `EPISODE_USERS_ID_USER`,`".$database_prefix."users`.`USERNAME` AS `USERS_USERNAME` from (((`".$database_prefix."podcast` join `".$database_prefix."episoden` on((`".$database_prefix."episoden`.`ID_PODCAST` = `".$database_prefix."podcast`.`ID`))) join `".$database_prefix."episode_users` on((`".$database_prefix."episode_users`.`ID_EPISODE` = `".$database_prefix."episoden`.`ID`))) join `".$database_prefix."users` on((`".$database_prefix."users`.`ID` = `".$database_prefix."episode_users`.`ID_USER`))) ;";

		//Struktur des Views `view_episode_categories`

		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_episode_categories`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_episode_categories`  AS  select `".$database_prefix."categories`.`DESCR` AS `DESCR`,`".$database_prefix."categories`.`VISIBLE` AS `VISIBLE`,`".$database_prefix."categories`.`COLL` AS `COLL`,`".$database_prefix."categories`.`MAX_ENTRIES` AS `MAX_ENTRIES`,`".$database_prefix."categories`.`ALLOW_TOPICS` AS `ALLOW_TOPICS`,`".$database_prefix."episode_categories`.`ID` AS `ID`,`".$database_prefix."episode_categories`.`ID_EPISODE` AS `ID_EPISODE`,`".$database_prefix."categories`.`REIHENF` AS `REIHENF`,`".$database_prefix."episode_categories`.`ID_CATEGORY` AS `ID_CATEGORY`,`".$database_prefix."episoden`.`DONE` AS `EPISODE_DONE`,`".$database_prefix."episoden`.`ID_PODCAST` AS `EPISODE_ID_PODCAST` from ((`".$database_prefix."categories` join `".$database_prefix."episode_categories` on((`".$database_prefix."episode_categories`.`ID_CATEGORY` = `".$database_prefix."categories`.`ID`))) join `".$database_prefix."episoden` on((`".$database_prefix."episoden`.`ID` = `".$database_prefix."episode_categories`.`ID_EPISODE`))) ;";

		//Struktur des Views `view_episode_users`

		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_episode_users`;

		CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `".$database_prefix."view_episode_users`  AS  select `".$database_prefix."episode_users`.`ID_EPISODE` AS `EPISODE_USERS_ID_EPISODE`,`".$database_prefix."episode_users`.`ID_USER` AS `EPISODE_USERS_ID_USER`,`".$database_prefix."users`.`NAME_SHOW` AS `USERS_NAME_SHOW`,`".$database_prefix."episoden`.`DONE` AS `EPISODE_DONE` from ((`".$database_prefix."episode_users` join `".$database_prefix."users` on((`".$database_prefix."users`.`ID` = `".$database_prefix."episode_users`.`ID_USER`))) join `".$database_prefix."episoden` on((`".$database_prefix."episoden`.`ID` = `".$database_prefix."episode_users`.`ID_EPISODE`))) ;";

		//Struktur des Views `view_links`

		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_links`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_links`  AS  select `".$database_prefix."links`.`ID` AS `LINKS_ID`,`".$database_prefix."links`.`ID_USER` AS `LINKS_ID_USER`,`".$database_prefix."links`.`ID_CATEGORY` AS `LINKS_ID_CATEGORY`,`".$database_prefix."links`.`ID_TOPIC` AS `LINKS_ID_TOPIC`,`".$database_prefix."links`.`DESCR` AS `LINKS_DESCR`,`".$database_prefix."links`.`REIHENF` AS `LINKS_REIHENF`,`".$database_prefix."links`.`URL` AS `LINKS_URL`,`".$database_prefix."links`.`INFO` AS `LINKS_INFO`,`".$database_prefix."links`.`DONE` AS `LINKS_DONE`,`".$database_prefix."links`.`DONE_TS` AS `LINKS_DONE_TS`,`".$database_prefix."categories`.`DESCR` AS `CATEGORIES_DESCR`,`".$database_prefix."categories`.`MAX_ENTRIES` AS `CATEGORIES_MAX_ENTRIES`,`".$database_prefix."categories`.`ALLOW_TOPICS` AS `CATEGORIES_ALLOW_TOPICS`,`".$database_prefix."categories`.`VISIBLE` AS `CATEGORIES_VISIBLE`,`".$database_prefix."episoden`.`ID` AS `EPISODEN_ID`,`".$database_prefix."episoden`.`NUMMER` AS `EPISODEN_NUMMER`,`".$database_prefix."episoden`.`TITEL` AS `EPISODEN_TITEL`,`".$database_prefix."episoden`.`DONE` AS `EPISODEN_DONE`,`".$database_prefix."topics`.`ID_USER` AS `TOPICS_ID_USER`,`".$database_prefix."topics`.`ID_EPISODE` AS `TOPICS_ID_EPISODE`,`".$database_prefix."topics`.`ID_CATEGORY` AS `TOPICS_ID_CATEGORY`,`".$database_prefix."topics`.`ID` AS `TOPICS_ID`,`".$database_prefix."topics`.`DESCR` AS `TOPICS_DESCR`,`".$database_prefix."topics`.`REIHENF` AS `TOPICS_REIHENF`,`".$database_prefix."topics`.`DONE` AS `TOPICS_DONE`,`".$database_prefix."topics`.`DONE_TS` AS `TOPICS_DONE_TS` from (((`".$database_prefix."links` left join `".$database_prefix."categories` on(((`".$database_prefix."categories`.`ID` = `".$database_prefix."links`.`ID_CATEGORY`) and (`".$database_prefix."categories`.`ID` = `".$database_prefix."links`.`ID_CATEGORY`)))) join `".$database_prefix."episoden` on((`".$database_prefix."episoden`.`ID` = `".$database_prefix."links`.`ID_EPISODE`))) left join `".$database_prefix."topics` on(((`".$database_prefix."topics`.`ID_CATEGORY` = `".$database_prefix."categories`.`ID`) and (`".$database_prefix."topics`.`ID` = `".$database_prefix."links`.`ID_TOPIC`)))) ;";

		//Struktur des Views `view_podcasts_users`
		
		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_podcasts_users`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_podcasts_users`  AS  select `".$database_prefix."podcast_users`.`ID_PODCAST` AS `PODCASTS_USERS_ID_PODCAST`,`".$database_prefix."podcast_users`.`ID_USER` AS `PODCASTS_USERS_ID_USER`,`".$database_prefix."users`.`NAME_SHOW` AS `USERS_NAME_SHOW`,`".$database_prefix."podcast`.`SHORT` AS `PODCAST_SHORT`,`".$database_prefix."podcast`.`COLOR` AS `PODCAST_COLOR` from ((`".$database_prefix."podcast_users` join `".$database_prefix."users` on((`".$database_prefix."users`.`ID` = `".$database_prefix."podcast_users`.`ID_USER`))) join `".$database_prefix."podcast` on((`".$database_prefix."podcast`.`ID` = `".$database_prefix."podcast_users`.`ID_PODCAST`))) ;";

		//Struktur des Views `view_topics`
		
		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_topics`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_topics`  AS  select `".$database_prefix."topics`.`ID` AS `TOPICS_ID`,`".$database_prefix."topics`.`ID_USER` AS `TOPICS_ID_USER`,`".$database_prefix."topics`.`ID_CATEGORY` AS `TOPICS_ID_CATEGORY`,`".$database_prefix."topics`.`DESCR` AS `TOPICS_DESCR`,`".$database_prefix."topics`.`INFO` AS `TOPICS_INFO`,`".$database_prefix."topics`.`DONE` AS `TOPICS_DONE`,`".$database_prefix."topics`.`DONE_TS` AS `TOPICS_DONE_TS`,`".$database_prefix."categories`.`ID` AS `CATEGORIES_ID`,`".$database_prefix."categories`.`DESCR` AS `CATEGORIES_DESCR`,`".$database_prefix."categories`.`MAX_ENTRIES` AS `CATEGORIES_MAX_ENTRIES`,`".$database_prefix."categories`.`ALLOW_TOPICS` AS `CATEGORIES_ALLOW_TOPICS`,`".$database_prefix."categories`.`VISIBLE` AS `CATEGORIES_VISIBLE`,`".$database_prefix."episoden`.`ID` AS `EPISODEN_ID`,`".$database_prefix."episoden`.`NUMMER` AS `EPISODEN_NUMMER`,`".$database_prefix."episoden`.`TITEL` AS `EPISODEN_TITEL`,`".$database_prefix."episoden`.`DONE` AS `EPISODEN_DONE` from ((`".$database_prefix."topics` join `".$database_prefix."categories` on(((`".$database_prefix."topics`.`ID_CATEGORY` = `".$database_prefix."topics`.`ID_CATEGORY`) and (`".$database_prefix."categories`.`ID` = `".$database_prefix."topics`.`ID_CATEGORY`)))) join `".$database_prefix."episoden` on((`".$database_prefix."episoden`.`ID` = `".$database_prefix."topics`.`ID_EPISODE`))) ;";

		//Struktur des Views `view_users_usergroups`
		
		$query_views .= "DROP TABLE IF EXISTS `".$database_prefix."view_users_usergroups`;

		CREATE ALGORITHM=MERGE SQL SECURITY DEFINER VIEW `".$database_prefix."view_users_usergroups`  AS  select `".$database_prefix."users`.`USERNAME` AS `USER_NAME`,`".$database_prefix."users`.`NAME_SHOW` AS `USER_NAME_SHOW`,`".$database_prefix."users`.`EMAIL` AS `USER_EMAIL`,`".$database_prefix."users`.`AVATAR` AS `USER_AVATAR`,`".$database_prefix."users`.`ID` AS `ID_USER`,`".$database_prefix."users`.`LEVEL_ID` AS `ID_LEVEL`,`".$database_prefix."usergroups`.`DESCR` AS `USERGROUPS_DESCR` from (`".$database_prefix."users` join `".$database_prefix."usergroups` on((`".$database_prefix."usergroups`.`LEVEL` = `".$database_prefix."users`.`LEVEL_ID`))) ;";
		
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
		
		$query_ini= "INSERT INTO `".$database_prefix."ini` (`KEYWORD`, `SETTING`, `KEYVALUE`, `INFO`) VALUES
			('PF_VERSION', '0', '1.0.0.', 'Alpaca'),
			('CATEGORY_VISIBLE', '1', 'fas fa-eye', 'Sichtbar'),
			('CATEGORY_VISIBLE', '0', 'fas fa-eye-slash', 'Nicht sichtbar'),
			('ALLOW_TOPICS', '1', 'fas fa-bars', 'Themen'),
			('ALLOW_TOPICS', '0', '', NULL),
			('MAX_ENTRIES', '0', 'fas fa-clipboard', 'Beiträge maximal'),
			('COLL', '1', 'fas fa-hands-helping', 'Kollaborativ'),
			('COLL', '0', '', NULL),
			('PC_PREFIX', '1', '".$pc_short."', 'Podcast Kurzbezeichner'),
			('PC_COLOR', '1', '#FFFFFF', 'Podcast Farbe');";

		clearStoredResults();					
		$result = mysqli_multi_query($con,$query_ini);
		if(!$result)
			{
				echo " --> <span style='color:red'>FEHLER</span>";
				return;
			}
		else
			{
				echo " --> <span style='color:green'>OK!</span><br>";
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