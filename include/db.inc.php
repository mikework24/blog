<?php
#**********************************************************************************#


				#**********************************#
				#********** DATABASE INC **********#
				#**********************************#


#**********************************************************************************#


				/**
				*
				*	Stellt eine Verbindung zu einer Datenbank mittels PDO her
				*	Die Konfiguration und Zugangsdaten erfolgen über eine externe Konfigurationsdatei
				*
				*	@param [String $dbname=DB_NAME]		Name der zu verbindenden Datenbank
				*
				*	@return Object								DB-Verbindungsobjekt
				*
				*/
				function dbConnect($DBName=DB_NAME) {
				
if(DEBUG_DB)	echo "<p class='debug db'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): Versuche mit der DB '<b>$DBName</b>' zu verbinden... <i>(" . basename(__FILE__) . ")</i></p>\r\n";					

					// EXCEPTION-HANDLING (Umgang mit Fehlern)
					// Versuche, eine DB-Verbindung aufzubauen
					try {
						// wirft, falls fehlgeschlagen, eine Fehlermeldung "in den leeren Raum"
						
						// $PDO = new PDO("mysql:host=localhost; dbname=market; charset=utf8mb4", "root", "");
						$PDO = new PDO(DB_SYSTEM . ":host=" . DB_HOST . "; dbname=$DBName; charset=utf8mb4", DB_USER, DB_PWD);
						/*
							DB-Stream so einstellen, dass die DB Zahlenwerte wie Integer und Float als echten 
							Number-Datentyp zurückliefert (funktioniert nur im Zusammenhang mit Prepared Statements).
						*/
						$PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
						$PDO->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
						
						
					// falls eine Fehlermeldung geworfen wurde, wird sie hier aufgefangen					
					} catch(PDOException $error) {
						// Ausgabe der Fehlermeldung
if(DEBUG_DB)		echo "<p class='debug db err'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): <i>FEHLER: " . $error->GetMessage() . " </i> <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						// Skript abbrechen
						exit;
					}
					// Falls das Skript nicht abgebrochen wurde (kein Fehler), geht es hier weiter
if(DEBUG_DB)	echo "<p class='debug db ok'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): Erfolgreich mit der DB '<b>$DBName</b>' verbunden. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						

					// DB-Verbindungsobjekt zurückgeben
					return $PDO;
				}
				
				
#**********************************************************************************#