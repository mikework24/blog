<?php
#*******************************************************************************************#
				
				
				#******************************************#
				#********** ENABLE STRICT TYPING **********#
				#******************************************#
				
				declare(strict_types=1);
				
				
#*******************************************************************************************#


				#********************************#
				#********** CLASS BLOG **********#
				#********************************#

				/**
				*
				*	Class represents a blog
				*
				*/
				class Blog {
					
					#*******************************#
					#********** ATTRIBUTE **********#
					#*******************************#
					
					private $blogID;
					private $blogHeadline;
					private $blogImagePath;
					private $blogImageAlignment;
					private $blogContent;					
					private $blogDate;
					
					// eingebettete Objekte
					private $user;
					private $category;
					
					
					#***********************************************************#
					
					
					#*********************************#
					#********** CONSTRUCTOR **********#
					#*********************************#
					
					public function __construct(  $user=new User(), $category=new Category(),
															$blogHeadline=NULL, $blogImagePath=NULL, $blogImageAlignment=NULL,
															$blogContent=NULL, $blogDate=NULL, $blogID=NULL )
					{
if(DEBUG_CC)		echo "<p class='debug class'>🛠 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</p>\n";						
						
						/*
							Entweder wird beim Constructor-Aufruf ein eingebettetes Objekt übergeben, oder es wird bei der 
							Parameterübernahme ein leeres Objekt erzeugt. In beiden Fällen wird das einzubettende Objekt
							IMMER in das einbettende Objekt geschachtelt.
						*/
						$this->setUser($user);
						$this->setCategory($category);
						
						// Setter nur aufrufen, wenn der jeweilige Parameter keinen Leerstring und nicht NULL enthält
						if( $blogHeadline 		!== '' 	AND $blogHeadline 		!== NULL )	$this->setBlogHeadline($blogHeadline);
						if( $blogImagePath 		!== '' 	AND $blogImagePath 		!== NULL )	$this->setBlogImagePath($blogImagePath);
						if( $blogImageAlignment !== '' 	AND $blogImageAlignment !== NULL )	$this->setBlogImageAlignment($blogImageAlignment);
						if( $blogContent 			!== '' 	AND $blogContent 			!== NULL )	$this->setBlogContent($blogContent);
						if( $blogDate 				!== '' 	AND $blogDate 				!== NULL )	$this->setBlogDate($blogDate);
						if( $blogID 				!== '' 	AND $blogID 				!== NULL )	$this->setBlogID($blogID);
						
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

				
					#********** BLOG ID **********#
					public function getBlogID():NULL|int {
						return $this->blogID;
					}
					public function setBlogID(int|string $value):Void {
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
							$this->blogID = intval($value);
						}
					}
					
					#********** BLOG HEADLINE **********#
					public function getBlogHeadline():NULL|string {
						return $this->blogHeadline;
					}
					public function setBlogHeadline(string $value):void{
						$this->blogHeadline = sanitizeString($value);
					}
					
					#********** BLOG IMAGE PATH **********#
					public function getBlogImagePath():NULL|string {
						return $this->blogImagePath;
					}
					public function setBlogImagePath(string $value):void {
						$this->blogImagePath = sanitizeString($value);
					}
					
					#********** BLOG IMAGE ALIGNMENT **********#
					public function getBlogImageAlignment():NULL|string {
						return $this->blogImageAlignment;
					}
					public function setBlogImageAlignment(string $value):void{
						$this->blogImageAlignment = sanitizeString($value);
					}
						
					#********** BLOG CONTENT **********#
					public function getBlogContent():NULL|string {
						return $this->blogContent;
					}
					public function setBlogContent(string $value):void{
						$this->blogContent = sanitizeString($value);
					}
					
					#********** BLOG DATE **********#
					public function getBlogDate():NULL|string {
						return $this->blogDate;
					}
					public function setBlogDate(string $value):void{
						$this->blogDate = sanitizeString($value);
					}
					
					
					#********** CATEGORY **********#
					public function getCategory():Category{
						return $this->category;
					}
					public function setCategory(Category $value):void{
						$this->category = $value;
					}
					
					#********** USER **********#
					public function getUser():User{
						return $this->user;
					}
					public function setUser(User $value):void{
						$this->user = $value;
					}
					
					
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#
					
					
					#********** FETCH ALL BLOG DATA FROM DB **********#
					/**
					*
					*	FETCH ALL BLOG ENTRY DATA FROM DB AN RETURNS ARRAY WITH BLOG-OBJECTS
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	ARRAY			An array containing all Blog-Entrys as Blog-objects
					*
					*/
					public static function fetchAllFromDb(PDO $PDO, $categoryFilterID = NULL) {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						/*
							Here are two kinds of db operation needed:
							case a) means all blog entries are loaded
							case b) means only blog entries matching a given category id are loaded
							
							For both cases we need a basic sql statement:
						*/
						
						$blogObjectsArray = array();
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
						$sql 		= 'SELECT * FROM blog
										INNER JOIN user USING(userID)
										INNER JOIN category USING(catID)';
						
						$params 	= NULL;
						
						#********** A) FETCH ALL BLOG ENTRIES **********#
						if( isset($categoryFilterID) === false ) {
if(DEBUG)				echo "<p class='debug'>📑 Line <b>" . __LINE__ . "</b>: Lade alle Blog-Einträge... <i>(" . basename(__FILE__) . ")</i></p>";
							
							
						#********** B) FILTER BLOG ENTRIES BY CATEGORY ID **********#				
						} else {
if(DEBUG)				echo "<p class='debug'>📑 Line <b>" . __LINE__ . "</b>: Filtere Blog-Einträge nach Kategorie-ID$categoryFilterID... <i>(" . basename(__FILE__) . ")</i></p>";					
					
							/*
								for case b) a condition for the category filter 
								has to be added to the sql statement
							*/
							$sql		.=	' WHERE catID = ?';
							
							/*
								And therefore a placeholder must be assigned and 
								filled with a value
							*/
							$params = array( $categoryFilterID );
						}					 
						#************************************************************#
						
						/*
							for both cases finally add the 'order by' command, which has to be 
							the last command in the sql statement (after any WHERE condition)
						*/
						$sql		.= ' ORDER BY blogDate DESC';	
						
						
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
							
							$blogObjectsArray[ $row['blogID'] ] = new Blog();
							
							#** BLOG OBJECT'S VALUES **#
							if( $row['blogID'] 				!== '' AND $row['blogID']					!== NULL )	$blogObjectsArray[ $row['blogID'] ]->setBlogID($row['blogID']);
							if( $row['blogHeadline'] 		!== '' AND $row['blogHeadline']			!== NULL )	$blogObjectsArray[ $row['blogID'] ]->setBlogHeadline($row['blogHeadline']);
							if( $row['blogImagePath'] 		!== '' AND $row['blogImagePath'] 		!== NULL )	$blogObjectsArray[ $row['blogID'] ]->setBlogImagePath($row['blogImagePath']);
							if( $row['blogImageAlignment']!== '' AND $row['blogImageAlignment']	!== NULL )	$blogObjectsArray[ $row['blogID'] ]->setBlogImageAlignment($row['blogImageAlignment']);
							if( $row['blogContent'] 		!== '' AND $row['blogContent']			!== NULL )	$blogObjectsArray[ $row['blogID'] ]->setBlogContent($row['blogContent']);
							if( $row['blogDate'] 			!== '' AND $row['blogDate']				!== NULL )	$blogObjectsArray[ $row['blogID'] ]->setBlogDate($row['blogDate']);
							
							#** USER OBJECT'S VALUES **#
							if( $row['userID']				!== '' AND $row['userID']					!== NULL )	$blogObjectsArray[ $row['blogID'] ]->getUser()->setUserID($row['userID']);
							if( $row['userFirstName']		!== '' AND $row['userFirstName']			!== NULL )	$blogObjectsArray[ $row['blogID'] ]->getUser()->setUserFirstName($row['userFirstName']);
							if( $row['userLastName']		!== '' AND $row['userLastName']			!== NULL )	$blogObjectsArray[ $row['blogID'] ]->getUser()->setUserLastName($row['userLastName']);
							if( $row['userEmail']			!== '' AND $row['userEmail']				!== NULL )	$blogObjectsArray[ $row['blogID'] ]->getUser()->setUserEmail($row['userEmail']);
							if( $row['userCity']				!== '' AND $row['userCity']				!== NULL )	$blogObjectsArray[ $row['blogID'] ]->getUser()->setUserCity($row['userCity']);
							if(  $row['userPassword']		!== '' AND $row['userPassword']			!== NULL )	$blogObjectsArray[ $row['blogID'] ]->getUser()->setUserPassword($row['userPassword']);
							
							#** CATEGORY OBJECT'S VALUES **#
							if( $row['catID']					!== '' AND $row['catID']					!== NULL )	$blogObjectsArray[ $row['blogID'] ]->getCategory()->setCatID($row['catID']);
							if( $row['catLabel']				!== '' AND $row['catLabel']				!== NULL )	$blogObjectsArray[ $row['blogID'] ]->getCategory()->setCatLabel($row['catLabel']);
						}
						
						return $blogObjectsArray;						
					}
					
					
					#***********************************************************#
					
					
					#********** SAVES OBJECTDATA TO DB **********#
					/**
					*
					*	SAVES BLOG-OBJECTDATA TO DB
					*	WRITES LAST INSERT ID INTO BLOG-OBJECT
					*
					*	@param	PDO $PDO		DB-Connection object
					*
					*	@return	BOOLEAN		true if writing was successful, else false
					*
					*/
					public function saveToDb(PDO $PDO):bool {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
						
						// Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen						
						$sql 		= "INSERT INTO blog
										(blogHeadline, blogImagePath, blogImageAlignment, blogContent, catID , userID)
										VALUES
										(?,?,?,?,?,?)";
										
						$params	= array(
												$this->getBlogHeadline(),
												$this->getBlogImagePath(),
												$this->getBlogImageAlignment(),
												$this->getBlogContent(),
												$this->getCategory()->getCatID(),
												$this->getUser()->getUserID()
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
							$this->setBlogID( $PDO->lastInsertId() );
							return true;
						}						
					}
					
					#***********************************************************#
				}
#*******************************************************************************************#
?>