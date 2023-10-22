<?php
#***************************************************************************************#


				#****************************************#
				#********** PAGE CONFIGURATION **********#
				#****************************************#
				
				require_once('./include/config.inc.php');
				require_once('./include/db.inc.php');
				require_once('./include/form.inc.php');
				include_once('./include/dateTime.inc.php');
				
				
				#********** INCLUDE CLASSES **********#
				require_once('class/Blog.class.php');
				require_once('class/User.class.php');
				require_once('class/Category.class.php');


#***************************************************************************************#


				#**************************************#
				#********** OUTPUT BUFFERING **********#
				#**************************************#
				
				if( ob_start() === false ) {
					// Fehlerfall
if(DEBUG)		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Starten des Output Bufferings! <i>(" . basename(__FILE__) . ")</i></p>\r\n";				
					
				} else {
					// Erfolgsfall
if(DEBUG)		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Output Buffering erfolgreich gestartet. <i>(" . basename(__FILE__) . ")</i></p>\r\n";									
				}


#***************************************************************************************#

				#******************************************#
				#********** INITIALIZE VARIABLES **********#
				#******************************************#
				
				$loginError 			= NULL;
				$categoryFilterID		= NULL;
				
				$errorUserEmail  		= NULL;
				$errorPassword  		= NULL;


#***************************************************************************************#


				#*******************************************#
				#********** CHECK FOR LOGIN STATE **********#
				#*******************************************#
				
				#********** START|CONTINUE SESSION	**********#			
				session_name("wwwblogProjectde");
				session_start();
				
				
				#********** USER IS NOT LOGGED IN **********#
				if( isset($_SESSION['ID']) === false ) {
if(DEBUG)		echo "<p class='debug auth'><b>Line " . __LINE__ . "</b>: User ist nicht eingeloggt. <i>(" . basename(__FILE__) . ")</i></p>\n";

					// delete empty session
					session_destroy();
					
					// set Flag
					$login = false;
				
				
				#********** USER IS LOGGED IN **********#
				} else {
if(DEBUG)		echo "<p class='debug auth'><b>Line " . __LINE__ . "</b>: User ist eingeloggt. <i>(" . basename(__FILE__) . ")</i></p>\n";
					
					// set Flag
					$login = true;
				
				} // CHECK FOR LOGIN STATE END			


#***************************************************************************************#


				#****************************************#
				#********** PROCESS FORM LOGIN **********#
				#****************************************#				
				
				
				// Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
				if( isset($_POST['formLogin']) === true ) {
if(DEBUG)		echo "<p class='debug'>🧻 Line <b>" . __LINE__ . "</b>: Formular 'Login' wurde abgeschickt... <i>(" . basename(__FILE__) . ")</i></p>";	


					// Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
if(DEBUG)		echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Werte werden ausgelesen und entschärft... <i>(" . basename(__FILE__) . ")</i></p>\n";
					
					#********** GENERATE NEW USER OBJECT **********#
					// $userFirstName=NULL, $userLastName=NULL, $userEmail=NULL,
					// $userCity=NULL, $userPassword=NULL, $userID=NULL
					$user 			= new User( userEmail:$_POST['loginName'] );
					
					$loginPassword = sanitizeString($_POST['loginPassword']);
if(DEBUG_V)		echo "<p class='debug value'>Line <b>" . __LINE__ . "</b>: \$user->getUserEmail(): {$user->getUserEmail()} <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG_V)		echo "<p class='debug value'>Line <b>" . __LINE__ . "</b>: \$loginPassword: $loginPassword <i>(" . basename(__FILE__) . ")</i></p>";


					// Schritt 3 FORM: ggf. Werte validieren
if(DEBUG)		echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Feldwerte werden validiert... <i>(" . basename(__FILE__) . ")</i></p>\n";
					/*
						[x] Validieren der Formularwerte (Feldprüfungen)
						[ ] Vorbelegung der Formularfelder für den Fehlerfall 
						[x] Abschließende Prüfung, ob das Formular insgesamt fehlerfrei ist
					*/
					$errorLoginName 		= validateEmail($user->getUserEmail());
					$errorLoginPassword 	= validateInputString($loginPassword);
if(DEBUG_V)		echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$errorUserEmail: $errorUserEmail <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)		echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$errorPassword: $errorPassword <i>(" . basename(__FILE__) . ")</i></p>\n";
						
					
					#********** FINAL FORM VALIDATION **********#					
					if( $errorLoginName !== NULL OR $errorLoginPassword !== NULL ) {
						// Fehlerfall
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>";						
						$loginError = 'Benutzername oder Passwort falsch!';
						
					} else {
						// Erfolgsfall
if(DEBUG)			echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Formular ist formal fehlerfrei. <i>(" . basename(__FILE__) . ")</i></p>";						
						
						// Schritt 4 FORM: Daten weiterverarbeiten
if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Daten werden weiterverarbeitet... <i>(" . basename(__FILE__) . ")</i></p>\n";
						
						
						#***********************************#
						#********** DB OPERATIONS **********#
						#***********************************#
						
						// Schritt 1 DB: DB-Verbindung herstellen
						$PDO = dbConnect(DB_NAME);
						
						
						#********** FETCH USER DATA FROM DATABASE BY EMAIL **********#
if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Lese Userdaten aus DB aus... <i>(" . basename(__FILE__) . ")</i></p>\n";
						
						$emailValidation = $user->fetchFromDB($PDO);
						
						
						#********** CLOSE DB CONNECTION **********#
if(DEBUG_DB)		echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
						unset($PDO);
						
						
						#********** 1. VALIDATE EMAIL **********#
if(DEBUG)			echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Validiere Email-Adresse... <i>(" . basename(__FILE__) . ")</i></p>\n";

						if( $emailValidation === false ) {
							// Fehlerfall:
if(DEBUG)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Die Email-Adresse '{$user->getUserEmail()}' wurde nicht in der DB gefunden! <i>(" . basename(__FILE__) . ")</i></p>\n";				
							$loginError = 'Benutzername oder Passwort falsch!';

						} else {
							// Erfolgsfall
if(DEBUG)				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Die Email-Adresse '{$user->getUserEmail()}' wurde in der DB gefunden. <i>(" . basename(__FILE__) . ")</i></p>\n";				
							
							
							#********** 2. VALIDATE PASSWORD **********#
if(DEBUG)				echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Validiere Passwort... <i>(" . basename(__FILE__) . ")</i></p>\n";
							
							/*
								Die Funktion password_verify() vergleicht einen String mit einem mittels
								password_hash() verschlüsseltem Passwort. Die Rückgabewerte sind true oder false.
							*/
							if( password_verify($loginPassword, $user->getUserPassword()) === false ) {
								// Fehlerfall
if(DEBUG)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Passwort stimmt nicht mit DB überein! <i>(" . basename(__FILE__) . ")</i></p>";
								$loginError = 'Benutzername oder Passwort falsch!';
							
							} else {
								// Erfolgsfall
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Passwort stimmt mit DB überein. LOGIN OK. <i>(" . basename(__FILE__) . ")</i></p>";
								
								
								#********** 3. START SESSION **********#
								if( session_start() === false ) {
									// Fehlerfall
if(DEBUG)						echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Starten der Session! <i>(" . basename(__FILE__) . ")</i></p>\n";				
									$loginError = 'Der Loginvorgang konnte nicht durchgeführt werden!<br>
														Bitte überprüfen Sie die Sicherheitseinstellungen Ihres Browsers und 
														aktivieren Sie die Annahme von Cookies für diese Seite.';
									
									// TODO: Eintrag in ErrorLogFile
									
								} else {
									// Erfolgsfall
if(DEBUG)						echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Session erfolgreich gestartet. <i>(" . basename(__FILE__) . ")</i></p>\n";				
									
									
									#********** SAVE USER DATA INTO SESSION **********#
if(DEBUG)						echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Schreibe Userdaten in Session... <i>(" . basename(__FILE__) . ")</i></p>";
									
									$_SESSION['IPAddress'] 		= $_SERVER['REMOTE_ADDR'];
									$_SESSION['ID'] 				= $user->getUserID();
									$_SESSION['userFirstName'] = $user->getUserFirstName();
									$_SESSION['userLastName'] 	= $user->getUserLastName();
									
									
									#********** REDIRECT TO DASHBOARD **********#								
									header('Location: dashboard.php');
									
								} // 3. START SESSION END
								
							} // 2. VALIDATE PASSWORD END
							
						} // 1. VALIDATE EMAIL END
						
					} // FINAL FORM VALIDATION END
					
				} // PROCESS FORM LOGIN END


#***************************************************************************************#

			
				#********************************************#
				#********** PROCESS URL PARAMETERS **********#
				#********************************************#
				
				// Schritt 1 URL: Prüfen, ob Parameter übergeben wurde
				if( isset($_GET['action']) === true ) {
if(DEBUG)		echo "<p class='debug'>🧻 <b>Line " . __LINE__ . "</b>: URL-Parameter 'action' wurde übergeben. <i>(" . basename(__FILE__) . ")</i></p>\n";										
			
					// Schritt 2 URL: Werte auslesen, entschärfen, DEBUG-Ausgabe
if(DEBUG)		echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Werte werden ausgelesen und entschärft... <i>(" . basename(__FILE__) . ")</i></p>\n";
					$action = sanitizeString($_GET['action']);
if(DEBUG_V)		echo "<p class='debug value'>Line <b>" . __LINE__ . "</b>: \$action: $action <i>(" . basename(__FILE__) . ")</i></p>";
		
					// Schritt 3 URL: ggf. Verzweigung
					
					
					#********** LOGOUT **********#					
					if( $_GET['action'] === 'logout' ) {
if(DEBUG)			echo "<p class='debug'>📑 Line <b>" . __LINE__ . "</b>: 'Logout' wird durchgeführt... <i>(" . basename(__FILE__) . ")</i></p>";	
						
						session_destroy();
						header("Location: index.php");
						exit();
						
						
					#********** FILTER BY CATEGORY **********#					
					} elseif( $action === 'filterByCategory' ) {
if(DEBUG)			echo "<p class='debug'>📑 Line <b>" . __LINE__ . "</b>: Kategoriefilter aktiv... <i>(" . basename(__FILE__) . ")</i></p>";				
						
						
						#********** FETCH SECOND URL PARAMETER **********#
						if( isset($_GET['catID']) === true ) {
							// use $categoryFilterID as flag
							$categoryFilterID = sanitizeString($_GET['catID']);
if(DEBUG_V)				echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$categoryFilterID: $categoryFilterID <i>(" . basename(__FILE__) . ")</i></p>\n";			
						
						}
						
					} // BRANCHING END
					
				} // PROCESS URL PARAMETERS END
				
				
#***************************************************************************************#
				
				
				#************************************************#
				#********** FETCH BLOG ENTRIES FORM DB **********#
				#************************************************#				
				
				
				#***********************************#
				#********** DB OPERATIONS **********#
				#***********************************#
				
				// Schritt 1 DB: DB-Verbindung herstellen
				$PDO = dbConnect(DB_NAME);
				
				
				#********** FETCH BLOG ENTRIES FROM DATABASE **********#
				if( isset($categoryFilterID) === false ) {
if(DEBUG)		echo "<p class='debug'>🧻 <b>Line " . __LINE__ . "</b>: Alle Blogeinträge auslesen... <i>(" . basename(__FILE__) . ")</i></p>\n";										
					
					// Alle Blog einträge
					$allBlogArticlesArray = Blog::fetchAllFromDb($PDO);
				} else {
if(DEBUG)		echo "<p class='debug'>🧻 <b>Line " . __LINE__ . "</b>: Blogeinträge der Kategorie ID$categoryFilterID auslesen... <i>(" . basename(__FILE__) . ")</i></p>\n";										
					
					// Blog einträge einer Kategorie
					$allBlogArticlesArray = Blog::fetchAllFromDb($PDO, $categoryFilterID);
				}
				
				
				#********** CLOSE DB CONNECTION **********#
if(DEBUG_DB)echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
				unset($PDO);
				
/*
if(DEBUG_V)	echo "<pre class='debug value'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	print_r($allBlogArticlesArray);					
if(DEBUG_V)	echo "</pre>";
*/
				
#***************************************************************************************#


				#**********************************************#
				#********** FETCH CATEGORIES FROM DB **********#
				#**********************************************#
if(DEBUG)	echo "<p class='debug'>📑 Line <b>" . __LINE__ . "</b>: Lade Kategorien aus DB... <i>(" . basename(__FILE__) . ")</i></p>";	
				
				
				// Schritt 1 DB: DB-Verbindung herstellen
				$PDO = dbConnect();
				
				
				#********** FETCH BLOG ENTRIES FROM DATABASE **********#
				$allCategoriesArray = Category::fetchAllFromDb($PDO);
				

				// DB-Verbindung schließen
if(DEBUG_DB)echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
				unset($PDO);
				
/*
if(DEBUG_V)	echo "<pre class='debug value'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	print_r($allCategoriesArray);					
if(DEBUG_V)	echo "</pre>";
*/
			
#***************************************************************************************#
?>

<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>PHP-Projekt Blog</title>
		<link rel="stylesheet" href="./css/main.css">
		<link rel="stylesheet" href="./css/debug.css">
	</head>

	<body>
		
		<!-- ---------- PAGE HEADER START ---------- -->
		<header class="fright">
			
			<?php if( $login === false ): ?>
				<?php if($loginError): ?>
				<p class="error"><b><?= $loginError ?></b></p>
				<?php endif ?>
				
				<!-- -------- Login Form START -------- -->
				<form action="" method="POST">
					<input type="hidden" name="formLogin">
					<input type="text" name="loginName" placeholder="Email">
					<input type="password" name="loginPassword" placeholder="Password">
					<input type="submit" value="Login">
				</form>
				<!-- -------- Login Form END -------- -->
				
			<?php else: ?>
				<!-- -------- PAGE LINKS START -------- -->
				<a href="?action=logout">Logout</a><br>
				<a href='dashboard.php'>zum Dashboard >></a>
				<!-- -------- PAGE LINKS END -------- -->
			<?php endif ?>
		
		</header>
		
		<div class="clearer"></div>
				
		<br>
		<hr>
		<br>		
		<!-- ---------- PAGE HEADER END ---------- -->
		
		
		
		<h1>PHP-Projekt Blog</h1>
		<p><a href='index.php'>:: Alle Einträge anzeigen ::</a></p>
		
		
		
		<!-- ---------- BLOG ENTRIES START ---------- -->		
		<main class="blogs fleft">
			
			<?php if( $allBlogArticlesArray === false ): ?>
				<p class="info">Noch keine Blogeinträge vorhanden.</p>
			
			<?php else: ?>
			
				<?php foreach( $allBlogArticlesArray AS $singleBlogItemArray ): ?>
					<?php $dateTimeArray = isoToEuDateTime($singleBlogItemArray->getBlogDate()) ?>
					
					<article class='blogEntry'>
					
						<a name='entry<?= $singleBlogItemArray->getBlogID() ?>'></a>
						
						<p class='fright'><a href='?action=filterByCategory&catID=<?= $singleBlogItemArray->getCategory()->getCatID() ?>'>Kategorie: <?= $singleBlogItemArray->getCategory()->getcatLabel() ?></a></p>
						<h2 class='clearer'><?= $singleBlogItemArray->getBlogHeadline() ?></h2>

						<p class='author'><?= $singleBlogItemArray->getUser()->getFullName() ?> (<?= $singleBlogItemArray->getUser()->getUserCity() ?>) schrieb am <?= $dateTimeArray['date'] ?> um <?= $dateTimeArray['time'] ?> Uhr:</p>
						
						<p class='blogContent'>
						
							<?php if($singleBlogItemArray->getBlogImagePath()): ?>
								<img class='<?= $singleBlogItemArray->getBlogImageAlignment() ?>' src='<?= $singleBlogItemArray->getBlogImagePath() ?>' alt='' title=''>
							<?php endif ?>
							
							<?= nl2br( $singleBlogItemArray->getBlogContent() ) ?>
						</p>
						
						<div class='clearer'></div>
						
						<br>
						<hr>
						
					</article>
					
				<?php endforeach ?>
			<?php endif ?>
			
		</main>		
		<!-- ---------- BLOG ENTRIES END ---------- -->
		
		
		
		<!-- ---------- CATEGORY FILTER LINKS START ---------- -->		
		<nav class="categories fright">

			<?php if( $allCategoriesArray === false ): ?>
				<p class="info">Noch keine Kategorien vorhanden.</p>
			
			<?php else: ?>
			
				<?php foreach( $allCategoriesArray AS $categorySingleItemArray ): ?>
					<p><a href="?action=filterByCategory&catID=<?= $categorySingleItemArray->getCatID() ?>" <?php if( $categorySingleItemArray->getCatID() == $categoryFilterID ) echo 'class="active"' ?>><?= $categorySingleItemArray->getCatLabel() ?></a></p>
				<?php endforeach ?>

			<?php endif ?>
		</nav>

		<div class="clearer"></div>
		<!-- ---------- CATEGORY FILTER LINKS END ---------- -->
		
	</body>

</html>
