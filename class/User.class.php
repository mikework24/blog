<?php
#*******************************************************************************************#
				
				
				#******************************************#
				#********** ENABLE STRICT TYPING **********#
				#******************************************#
				
				declare(strict_types=1);
				
				
#*******************************************************************************************#


				#********************************#
				#********** CLASS USER **********#
				#********************************#

				/**
				*
				*	Class represents a user
				*
				*/
				class User {
					
					#*******************************#
					#********** ATTRIBUTE **********#
					#*******************************#
					
					private $userID;
					private $userFirstName;
					private $userLastName;
					private $userEmail;
					private $userCity;					
					private $userPassword;
					
					
					#***********************************************************#
					
					
					#*********************************#
					#********** CONSTRUCTOR **********#
					#*********************************#
					
					public function __construct(  $userFirstName=NULL, $userLastName=NULL, $userEmail=NULL,
															$userCity=NULL, $userPassword=NULL, $userID=NULL	)
					{
if(DEBUG_CC)		echo "<p class='debug class'>🛠 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</p>\n";						
						
						// Setter nur aufrufen, wenn der jeweilige Parameter keinen Leerstring und nicht NULL enthält
						if( $userFirstName 	!== '' 	AND $userFirstName 	!== NULL )		$this->setUserFirstName($userFirstName);
						if( $userLastName 	!== '' 	AND $userLastName 	!== NULL )		$this->setUserLastName($userLastName);
						if( $userEmail 		!== '' 	AND $userEmail 		!== NULL )		$this->setUserEmail($userEmail);
						if( $userCity 			!== '' 	AND $userCity 			!== NULL )		$this->setUserCity($userCity);
						if( $userPassword 	!== '' 	AND $userPassword 	!== NULL )		$this->setUserPassword($userPassword);
						if( $userID 			!== '' 	AND $userID 			!== NULL )		$this->setUserID($userID);
						
if(DEBUG_CC)		echo "<pre class='debug class value'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_CC)		print_r($this);					
if(DEBUG_CC)		echo "</pre>";	
					}
					
					
					#********** DESTRUCTOR **********#
					public function __destruct() {
if(DEBUG_CC)		echo "<p class='debug class'>☠️  <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</p>\n";						
					}
					
					
					#***********************************************************#

					
					#*************************************#
					#********** GETTER & SETTER **********#
					#*************************************#
					
					
					#********** USER ID **********#
					public function getUserID():NULL|int {
						return $this->userID;
					}
					public function setUserID(int|string $value):Void {
						/*
							Datentypprüfung als weiteres Sicherheitsmerkmal:
							Als Präventivmaßnahme geprüft, ob die ID tatsächlich einer ID (also einem
							reinen Integer) entspricht.
							Die Funktion filter_var() mit der Konstante FILTER_VALIDATE_INT prüft den 
							Inhalt eines Strings auf einen Integer-Wert, ohne ihr dabei umzuwandeln und 
							damit ggf. negative Umwandlungseffekte in Kauf zu nehmen.
							Nach erfolgreicher Inhaltsprüfung kann der Wert dann sicher mittels der Funktion 
							intval() in einen echten Integer umgewandelt und somit intern mit einem Integer 
							weitergearbeitet werden.
							Das Ganze funktioniert auch mit dem Datentyp Float. Andere Datentypen machen hier 
							keinen Sinn.
						*/
						if( filter_var($value, FILTER_VALIDATE_INT) === false ) {
							// Fehlerfall
if(DEBUG_C)				echo "<p class='debug class err'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): Der Wert muss inhaltlich einem Integer entsprechen! (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						} else {
							// Erfolgsfall
							// Übergebenen Wert in einen Integer umwandeln
							$this->userID = intval($value);
						}
					}
					
					
					#********** USER FIRST NAME **********#
					public function getUserFirstName():NULL|string {
						return $this->userFirstName;
					}
					public function setUserFirstName(string $value):void{
						$this->userFirstName = sanitizeString($value);
					}
					
					#********** USER LAST NAME **********#
					public function getUserLastName():NULL|string {
						return $this->userLastName;
					}
					public function setUserLastName(string $value):void {
						$this->userLastName = sanitizeString($value);
					}
					
					#********** USER EMAIL **********#
					public function getUserEmail():NULL|string {
						return $this->userEmail;
					}
					public function setUserEmail(string $value):void{
						$this->userEmail = sanitizeString($value);
					}
					
					#********** USER CITY **********#
					public function getUserCity():NULL|string {
						return $this->userCity;
					}
					public function setUserCity(string $value):void{
						$this->userCity = sanitizeString($value);
					}
					
					#********** USER PASSWORD **********#
					public function getUserPassword():NULL|string {
						return $this->userPassword;
					}
					public function setUserPassword(string $value):void{
						$this->userPassword = sanitizeString($value);
					}
					
		
					#********** VIRTUAL ATTRIBUTES **********#
					public function getFullName():string{
						return $this->getUserFirstName() . ', ' . $this->getUserLastName();
					}
					
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#
					
					
					#********** FETCH SINGLE USER FROM DB **********#
					/**
					*
					*	FETCHES A SINGLE USER-DATASET FROM DB
					*	EITHER VIA THE USER_EMAIL ATTRIBUTE OR THE USER_ID-ATTRIBUTE
					*	IF DATASET WAS FOUND BUILDS A STATE-OBJECT AND WRITES THE STATE-OBJECT 
					*	PLUS ALL USER-DATA INTO THE GIVEN USER-OBJECT
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	BOOLEAN		true if dataset was found, else false
					*
					*/
					public function fetchFromDb(PDO $PDO):bool {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
						$sql 		= "SELECT * FROM user
										WHERE userEmail = ? OR userID = ?";
						
						$params	= array(	
												$this->getUserEmail(),
												$this->getUserID()
												);
						
						// Schritt 3 DB: Prepared Statements
						try {
							// Prepare: SQL-Statement vorbereiten
							$PDOStatement = $PDO->prepare($sql);
							
							// Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
							$PDOStatement->execute($params);
							
						} catch(PDOException $error) {
if(DEBUG_C) 			echo "<p class='debug class db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
						}
						
						// Schritt 4 DB: Daten weiterverarbeiten und DB-Verbindung schließen
						/*
							Bei lesenden Operationen wie SELECT und SELECT COUNT():
							Abholen der Datensätze bzw. auslesen des Ergebnisses
						*/
						$row = $PDOStatement->fetch(PDO::FETCH_ASSOC);
						
						// Prüfen, ob ein Datensatz zurückgeliefert wurde
						// Wenn ein Datensatz zurückgeliefert wurde, muss die Login-Email korekt sein
						if( !$row ) {
							// Fehlerfall
							return false;
							
						} else {
							// Erfolgsfall
							
							// Objekt mit Werten aus der DB füllen
							if( $row['userID']				!== '' AND $row['userID']					!== NULL )	$this->setUserID($row['userID']);
							if( $row['userFirstName']		!== '' AND $row['userFirstName']			!== NULL )	$this->setUserFirstName($row['userFirstName']);
							if( $row['userLastName']		!== '' AND $row['userLastName']			!== NULL )	$this->setUserLastName($row['userLastName']);
							if( $row['userEmail']			!== '' AND $row['userEmail']				!== NULL )	$this->setUserEmail($row['userEmail']);
							if( $row['userCity']				!== '' AND $row['userCity']				!== NULL )	$this->setUserCity($row['userCity']);
							if( $row['userPassword']		!== '' AND $row['userPassword']			!== NULL )	$this->setUserPassword($row['userPassword']);
							
							return true;							
						}
					}

					#***********************************************************#
					
				}
#*******************************************************************************************#
?>