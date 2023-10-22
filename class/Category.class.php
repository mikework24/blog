<?php
#*******************************************************************************************#
				
				
				#******************************************#
				#********** ENABLE STRICT TYPING **********#
				#******************************************#
				
				declare(strict_types=1);
				
				
#*******************************************************************************************#


				#************************************#
				#********** CLASS CATEGORY **********#
				#************************************#

				/**
				*
				*	Class represents a Category
				*
				*/
				class Category {
					
					#*******************************#
					#********** ATTRIBUTE **********#
					#*******************************#
					
					private $catID;
					private $catLabel;

					
					#***********************************************************#
					
					
					#*********************************#
					#********** CONSTRUCTOR **********#
					#*********************************#
					
					public function __construct( $catLabel=NULL, $catID=NULL	)
					{
if(DEBUG_CC)		echo "<p class='debug class'>🛠 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</p>\n";						
						
						// Setter nur aufrufen, wenn der jeweilige Parameter keinen Leerstring und nicht NULL enthält
						if( $catID 		!== '' 	AND $catID 		!== NULL )		$this->setCatID($catID);
						if( $catLabel	!== '' 	AND $catLabel	!== NULL )		$this->setCatLabel($catLabel);
						
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

				
					#********** CAT ID **********#
					public function getCatID():NULL|int {
						return $this->catID;
					}
					public function setCatID(int|string $value):Void {
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
							$this->catID = intval($value);
						}
					}
					

					
					#********** CAT LABEL **********#
					public function getCatLabel():NULL|string {
						return $this->catLabel;
					}
					public function setCatLabel(string $value):void{
						$this->catLabel = sanitizeString($value);
					}
					
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#
					
					
					
					#********** CHECK IF CATEGORY ALREADY EXISTS IN DB **********#
					/**
					*
					*	Checks if catLabel already exists in Database
					*
					*	@param	PDO	$PDO		DB-connection object
					*
					*	@return	Bool				String if exists, else false
					*
					*/
					public function checkIfExists(PDO $PDO):int {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "('{$this->getCatLabel()}') (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
						/*
							<=>: NULL-Safe-Vergleichsoperator in MySQL "NOT usr_id <=> ? (usr_id != ?)"
							für UPDATE: 
							WENN Datensatz bereits besteht, darf die eigene Email NICHT mit geprüft werden, 
							weil sich der Datensatz ansonsten nicht updaten lässt sog. 
							Hierzu muss der sog. NULL-safe Vergleichsoperator <=> benutzt werden, der Vergleiche
							gegen NULL ermöglicht.
							Da der NULL-safe Vergleichsoperator keine Verneinung kennt (!=), muss auf = 
							geprüft und der gesamte Ausdruck mittels NOT negiert werden.
						*/
						$sql 		= 'SELECT COUNT(catLabel) FROM category
										WHERE catLabel = ?';
						
						$params	= array(	$this->getCatLabel() );
						
						
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
						$count = $PDOStatement->fetchColumn();
if(DEBUG_C)			echo "<p class='debug class value'><b>Line " . __LINE__ . "</b>: \$count: $count <i>(" . basename(__FILE__) . ")</i></p>\n";
						
						return $count;
					}
					
					
					#***********************************************************#
					
					
					#********** FETCH ALL CATEGORYIES DATA FROM DB **********#
					/**
					*
					*	FETCH ALL CATEGORIES DATA FROM DB AN RETURNS ARRAY WITH CATEGORY-OBJECTS
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	ARRAY			An array containing all categories as category-objects
					*
					*/
					public static function fetchAllFromDb(PDO $PDO) {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						$categoryObjectsArray = array();
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
						$sql 		= 'SELECT * FROM category';
						
						$params 	= NULL;
						
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
						while( $row = $PDOStatement->fetch(PDO::FETCH_ASSOC) ) {
							// Je Datensatz ein Objekt der jeweiligen Klasse erstellen und in ein Array speichern
							// Variante mit Automation
							// Ohne Automation muss hier der Constructor mit allen einzelnen Werten aus dem $row-Array als Attribute aufgerufen werden
							$categoryObjectsArray[ $row['catID'] ] = new Category( $catLabel=$row['catLabel'] , $catID=$row['catID'] );

						}
						
						return $categoryObjectsArray;						
					}
					
					
					#***********************************************************#
					
					
					#********** SAVES OBJECTDATA TO DB **********#
					/**
					*
					*	SAVES CATEGORY-OBJECTDATA TO DB
					*	WRITES LAST INSERT ID INTO CATEGORY-OBJECT
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	BOOLEAN		true if writing was successful, else false
					*
					*/
					public function saveToDb(PDO $PDO):bool {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen						
						$sql 		= "INSERT INTO category
										(catLabel)
										VALUES
										(?)";
						
						$params	= array( $this->getCatLabel() );						
						
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
							Bei schreibenden Operationen (INSERT/UPDATE/DELETE):
							Schreiberfolg prüfen anhand der Anzahl der betroffenen Datensätze (number of affected rows).
							Diese werden über die PDOStatement-Methode rowCount() ausgelesen.
							Der Rückgabewert von rowCount() ist ein Integer; wurden keine Daten verändert, wird 0 zurückgeliefert.
						*/
						$rowCount = $PDOStatement->rowCount();
if(DEBUG_C)			echo "<p class='debug class value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";

						if( $rowCount === 0 ) {
							// Fehlerfall
							return false;
							
						} else {
							// Erfolgsfall
							$this->setCatID( $PDO->lastInsertId() );
							return true;
						}						
					}
					
					#***********************************************************#
					
				}
#*******************************************************************************************#
?>