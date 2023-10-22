<?php
#****************************************************************************************************#

				
				#*************************************#
				#********** SANITIZE STRING **********#
				#*************************************#
				
				/**
				*
				*	Ersetzt potentiell gefährliche Steuerzeichen durch HTML-Entities
				*	Entfernt vor und nach einem String unnötige Whitespaces
				*	Ersetzt Leerstring und reine Wehitespaces durch NULL
				*	
				*	@params		String	$value								Die zu bereinigende Zeichenkette
				*	@params		Bool		$convertEmptyStringToNull		Angabe, ob Leerstrings in NULL umgewandelt werden sollen
				*
				*	@return		String|NULL			Die bereinigte Zeichenkette|NULL, falls $value NULL oder '' ist
				*
				*/
				function sanitizeString($value, $convertEmptyStringToNull=true) {
					#********** LOCAL SCOPE START **********#
if(DEBUG_F)		echo "<p class='debug sanitizeString'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$value') <i>(" . basename(__FILE__) . ")</i></p>\n";
					
					/*
						Da in PHP künftig kein Aufruf der PHP-eigenen Funktionen
						mit NULL-Werten erlaubt ist, rufen wir die PHP-Funktionen
						nur auf, wenn $value NICHT NULL ist.
						Für DB-Operationen soll NULL nicht mit Leersteings überschrieben
						werden. Daher wird an dieser Stelle ein Leerstring durch NULL ersetzt.
					*/
					if( $value !== NULL ) {
						
						/*
							SCHUTZ GEGEN EINSCHLEUSUNG UNERWÜNSCHTEN CODES:
							Damit so etwas nicht passiert: <script>alert("HACK!")</script>
							muss der empfangene String ZWINGEND entschärft werden!
							htmlspecialchars() wandelt potentiell gefährliche Steuerzeichen wie
							< > " & in HTML-Code um (&lt; &gt; &quot; &amp;).
							
							Der Parameter ENT_QUOTES wandelt zusätzlich einfache ' in &apos; um.
							Der Parameter ENT_HTML5 sorgt dafür, dass der generierte HTML-Code HTML5-konform ist.
							
							Der 1. optionale Parameter regelt die zugrundeliegende Zeichencodierung 
							(NULL=Zeichencodierung wird vom Webserver übernommen)
							
							Der 2. optionale Parameter bestimmt die Zeichenkodierung
							
							Der 3. optionale Parameter regelt, ob bereits vorhandene HTML-Entities erneut entschärft werden
							(false=keine doppelte Entschärfung)
						*/
						$value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, double_encode:false);
						
						/*
							trim() entfernt VOR und NACH einem String (aber nicht mitten drin) 
							sämtliche sog. Whitespaces (Leerzeichen, Tabs, Zeilenumbrüche)
						*/
						$value = trim($value);
						
						/*
							Sollte $value ausschließlich Whitespaces beinhalten, liefert trim() an dieser
							Stelle einen Leersrting zurück. Dieser Leerstring muss wieder in NULL umgewandelt werden.
						*/
						if( $convertEmptyStringToNull === true AND $value === '' ) {
							$value = NULL;
						}
						
						return $value;
					}					
					#********** LOCAL SCOPE END **********#
				}
				
				
#****************************************************************************************************#

				
				#*******************************************#
				#********** VALIDATE INPUT STRING **********#
				#*******************************************#
				
				/**
				*
				*	Prüft einen übergebenen String auf Mindestlänge und Maximallänge sowie optional 
				* 	zusätzlich auf Pflichtangabe.
				*	Generiert Fehlermeldung bei Leerstring, NULL oder ungültiger Länge
				*
				*	@param	NULL|String	$value									Der zu übergebende String
				*	@param	Bool			$mandatory=true						Angabe zu Pflichteingabe
				*	@param	Integer		$maxLength=INPUT_MAX_LENGTH		Die zu prüfende Maximallänge
				*	@param	Integer		$minLength=INPUT_MIN_LENGTH		Die zu prüfende Mindestlänge															
				*
				*	@return	String|NULL												Fehlermeldung | ansonsten NULL
				*
				*/
				function validateInputString($value, $mandatory=true, $maxLength=INPUT_MAX_LENGTH, $minLength=INPUT_MIN_LENGTH) {
					#********** LOCAL SCOPE START **********#
if(DEBUG_F)		echo "<p class='debug validateInputString'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$value', [$minLength | $maxLength], mandatory: $mandatory) <i>(" . basename(__FILE__) . ")</i></p>\n";
					
					
					#********** MANDATORY CHECK **********#
					/*
						Da ein zu prüfender String nicht zwangsläufig aus einem Formular,
						sondern beispielswesie auch aus einem JSON-Objekt stammen könnte, sollten
						hier auch NULL-Werte mit geprüft werden.
					*/
					if( $mandatory === true AND ($value === '' OR $value === NULL) ) {
						// Fehlerfall
						return 'Dies ist ein Pflichtfeld';
					
					
					#********** MAXIMUM LENGTH CHECK **********#
					/*
						Da die Felder in der Datenbank oftmals eine Längenbegrenzung besitzen,
						die Datenbank aber bei Überschreiten dieser Grenze keine Fehlermeldung
						ausgibt, sondern alles, das über diese Grenze hinausgeht, stillschweigend 
						abschneidet, muss vorher eine Prüfung auf diese Maximallänge durchgeführt 
						werden. Nur so kann dem User auch eine entsprechende Fehlermeldung ausgegeben
						werden.
					*/
					/*
						mb_strlen() erwartet als Datentyp einen String. Wenn (später bei der OOP)
						jedoch ein anderer Datentyp wie Integer oder Float übergeben wird, wirft
						mb_strlen() einen Fehler. Da es ohnehin keinen Sinn maht, einen Zahlenwert
						auf seine Länge (Anzahl der Zeichen) zu prüfen, wird diese Prüfung nur für
						den Datentyp 'String' durchgeführt.
					*/
					} elseif( $value !== NULL AND mb_strlen($value) > $maxLength ) {
						// Fehlerfall
						return "Darf maximal $maxLength Zeichen lang sein";
					
					
					#********** MINIMUM LENGTH CHECK **********#
					/*
						Es gibt Sonderfälle, bei denen eine Mindestlänge für einen Userinput
						vorgegeben ist, beispielsweise bei der Erstellung von Passwörtern.
						Damit nicht-Pflichtfelder aber auch weiterhin leer sein dürfen, muss
						die Mindestlänge als Standardwert mit 0 vorbelegt sein.
						
						Bei einem optionalen Feldwert, der gleichzeitig eine Mindestlänge
						einhalten muss, darf die Prüfung keine Leersrtings validieren, da 
						diese nie die Mindestlänge erfüllen und somit der Wert nicht mehr 
						optional wäre.
					*/
					/*
						mb_strlen() erwartet als Datentyp einen String. Wenn (später bei der OOP)
						jedoch ein anderer Datentyp wie Integer oder Float übergeben wird, wirft
						mb_strlen() einen Fehler. Da es ohnehin keinen Sinn macht, einen Zahlenwert
						auf seine Länge (Anzahl der Zeichen) zu prüfen, wird diese Prüfung nur für
						den Datentyp 'String' durchgeführt.
					*/
					} elseif( $value !== NULL AND mb_strlen($value) < $minLength ) {
						// Fehlerfall
						return "Muss mindestens $minLength Zeichen lang sein";
					}
					
					return NULL;
					#********** LOCAL SCOPE END **********#
				}


#****************************************************************************************************#

				
				#*******************************************#
				#********** VALIDATE EMAIL FORMAT **********#
				#*******************************************#
								
				/**
				*
				*	Prüft einen übergebenen String auf eine valide Email-Adresse und optional auf Leerstring.
				*	Generiert Fehlermeldung bei ungültiger Email-Adresse und Leerstring, sofern der Parameter
				*	$mandatory true ist.
				*
				*	@param	String	$value							Der zu übergebende String
				*	@param	Bool		$mandatory=true				Angabe zu Pflichteingabe
				*
				*	@return	String|NULL									Fehlermeldung | ansonsten NULL
				*
				*/
				function validateEmail($value, $mandatory=true) {
					#********** LOCAL SCOPE START **********#
if(DEBUG_F)		echo "<p class='debug validateEmail'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$value') <i>(" . basename(__FILE__) . ")</i></p>\n";
					
					
					#********** MANDATORY CHECK **********#
					/*
						Da ein zu prüfender String nicht zwangsläufig aus einem Formular,
						sondern beispielswesie auch aus einem JSON-Objekt stammen könnte, sollten
						hier auch NULL-Werte mit geprüft werden.
					*/
					if( $mandatory === true AND ($value === '' OR $value === NULL) ) {
						// Fehlerfall
						return 'Dies ist ein Pflichtfeld';
					
					
					#********** VALIDATE EMAIL ADDRESS FORMAT **********#
					} elseif( $value !== NULL AND $value !== '' AND filter_var($value, FILTER_VALIDATE_EMAIL) === false ) {
						// Fehlerfall
						return 'Dies ist keine gültige Email-Adresse';
						
					
					} else {
						// Erfolgsfall
						return NULL;
					} 
					#********** LOCAL SCOPE END **********#
				}


#****************************************************************************************************#


				#*******************************************#
				#********** VALIDATE IMAGE UPLOAD **********#
				#*******************************************#
				
				/**
				*
				*	Validiert ein auf den Server geladenes Bild, generiert einen unique Dateinamen
				*	sowie eine sichere Dateiendung und verschiebt das Bild in ein anzugebendes Zielverzeichnis.
				*	Validiert werden der aus dem Dateiheader ausgelesene MIME-Type, die aus dem Dateiheader
				*	ausgelesene Bildgröße in Pixeln sowie die auf Dateiebene ermittelte Dateigröße. 
				*	Der Dateiheader wird außerdem auf Plausibilität geprüft.
				*
				*	@param	String	$fileTemp															Der temporäre Pfad zum hochgeladenen Bild im Quarantäneverzeichnis
				*	@param	Integer	$imageMaxHeight			=IMAGE_MAX_HEIGHT					Die maximal erlaubte Bildhöhe in Pixeln
				*	@param	Integer	$imageMaxWidth				=IMAGE_MAX_WIDTH					Die maximal erlaubte Bildbreite in Pixeln				
				*	@param	Integer	$imageMinSize				=IMAGE_MIN_SIZE					Die minimal erlaubte Dateigröße in Bytes
				*	@param	Integer	$imageMaxSize				=IMAGE_MAX_SIZE					Die maximal erlaubte Dateigröße in Bytes
				*	@param	Array		$imageAllowedMimeTypes	=IMAGE_ALLOWED_MIME_TYPES		Whitelist der zulässigen MIME-Types mit den zugehörigen Dateiendungen
				*	@param	String	$imageUploadPath			=IMAGE_UPLOAD_PATH				Das Zielverzeichnis
				*
				*	@return	Array		{'imagePath'	=>	String|NULL, 								Bei Erfolg der Speicherpfad zur Datei im Zielverzeichnis | bei Fehler NULL
				*							 'imageError'	=>	String|NULL}								Bei Erfolg NULL | Bei Fehler Fehlermeldung
				*
				*/
				function validateImageUpload( $fileTemp,
														$imageMaxHeight 			= IMAGE_MAX_HEIGHT,
														$imageMaxWidth 			= IMAGE_MAX_WIDTH,
														$imageMinSize 				= IMAGE_MIN_SIZE,
														$imageMaxSize 				= IMAGE_MAX_SIZE,
														$imageAllowedMimeTypes 	= IMAGE_ALLOWED_MIME_TYPES,
														$imageUploadPath			= IMAGE_UPLOAD_PATH )
				{
					#********** LOCAL SCOPE START **********#
if(DEBUG_F)		echo "<p class='debug validateImageUpload'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$fileTemp') <i>(" . basename(__FILE__) . ")</i></p>\n";
					
					
					#**************************************************************************#
					#********** 1. GATHER INFORMATION FOR IMAGE FILE VIA FILE HEADER **********#
					#**************************************************************************#
					
					/*
						Die Funktion getimagesize() liest den Dateiheader einern Bilddatei aus und 
						liefert bei gültigem MIME Type ('image/...') ein gemischtes Array zurück:
						
						[0] 				Bildbreite in PX 
						[1] 				Bildhöhe in PX 
						[3] 				Einen für das HTML <img>-Tag vorbereiteten String (width="480" height="532") 
						['bits']			Anzahl der Bits pro Kanal 
						['channels']	Anzahl der Farbkanäle (somit auch das Farbmodell: RGB=3, CMYK=4) 
						['mime'] 		MIME Type
						
						Bei ungültigem MIME Type (also nicht 'image/...') liefert getimagesize() false zurück
					*/
					
					$imageDataArray = getimagesize($fileTemp);
/*					
if(DEBUG_F)		echo "<pre class='debug value validateImageUpload'><b>Line " . __LINE__ . "</b>: \$imageDataArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_F)		print_r($imageDataArray);					
if(DEBUG_F)		echo "</pre>";					
*/					
					
					#********** CHECK FOR VALID MIME TYPE **********#
					if( $imageDataArray === false ) {
						// Fehlerfall (MIME TYPE IS NO VALID IMAGE TYPE)						
						return array('imagePath' => NULL, 'imageError' => 'Dies ist keine Bilddatei');
						
					} elseif( is_array($imageDataArray) === true ) {
						// Erfolgsfall (MIME TYPE IS VALID IMAGE TYPE)
						
						$imageWidth 	= sanitizeString($imageDataArray[0]);
						$imageHeight 	= sanitizeString($imageDataArray[1]);
						$imageMimeType = sanitizeString($imageDataArray['mime']);
						$fileSize		= sanitizeString(fileSize($fileTemp));	

if(DEBUG_F)			echo "<p class='debug value validateImageUpload'><b>Line " . __LINE__ . "</b>: \$imageWidth: $imageWidth px <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_F)			echo "<p class='debug value validateImageUpload'><b>Line " . __LINE__ . "</b>: \$imageHeight: $imageHeight px <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_F)			echo "<p class='debug value validateImageUpload'><b>Line " . __LINE__ . "</b>: \$imageMimeType: $imageMimeType <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_F)			echo "<p class='debug value validateImageUpload'><b>Line " . __LINE__ . "</b>: \$fileSize: " . round($fileSize/1024, 2) . "kB <i>(" . basename(__FILE__) . ")</i></p>\n";
						
					} // 1. GATHER INFORMATION FOR IMAGE FILE VIA FILE HEADER END
					#*************************************************************#
					
					
					#*****************************************#
					#********** 2. IMAGE VALIDATION **********#
					#*****************************************#					
					
					#********** VALIDATE PLAUSIBILITY OF FILE HEADER **********#
					/*
						Diese Prüfung setzt darauf, dass ein maniplulierter Dateiheader nicht konsequent
						gefälscht wurde:
						Ein Hacker ändert den MimeType einer Textdatei mit Schadcode aud 'image/jpg', vergisst
						allerdings, zusätzlich weitere Einträge wie 'imageWidth' oder 'imageHeight' hinzuzufügen.
						
						Da wir den Datentyp eines im Dateiheader fehlenden Wertes nicht kennen (NULL, '', 0), 
						wird an dieser Stelle ausdrücklich nicht typsicher, sondern auf 'falsy' geprüft.
						Ein ! ('NOT') vor einem Wert oder einer Funktion negiert die Auswertung: Die Bedingung 
						ist erfüllt, wenn die Auswertung false ergibt.
					*/
					if( !$imageWidth OR !$imageHeight OR !$imageMimeType OR $fileSize < $imageMinSize ) {
						// 1. Fehlerfall (verdächtiger Datei Header)
						return array('imagePath' => NULL, 'imageError' => 'Verdächtiger Datei Header');
					}					
					
					
					#********** VALIDATE IMAGE MIME TYPE **********#
					// Whitelist mit erlaubten MIME TYPES
					// $imageAllowedMimeTypes = array('image/jpg' => '.jpg', 'image/jpeg' => '.jpg', 'image/png' => '.png', 'image/gif' => '.gif');
					
					/*
						Die Funktion in_array() prüft, ob eine übergebene Needle einem Wert (value) innerhalb 
						eines zu übergebenden Arrays entspricht.
						
						Die Funktion array_key_exists() prüft, ob eine übergebene Needle einem Index (key) innerhalb 
						eines zu übergebenden Arrays entspricht.
					*/
					if( array_key_exists($imageMimeType, $imageAllowedMimeTypes) === false ) {
						// 2. Fehlerfall (unerlaubter Bildtyp)
						return array('imagePath' => NULL, 'imageError' => 'Dies ist kein erlaubter Bildtyp');
					}
					
					
					#********** VALIDATE IMAGE WIDTH **********#
					if( $imageWidth > $imageMaxWidth ) {
						// 3. Fehlerfall (unerlaubte Bildbreite)
						return array('imagePath' => NULL, 'imageError' => "Die Bildbreite darf maximal $imageMaxWidth Pixel betragen");
					}					
					
					
					#********** VALIDATE IMAGE HEIGHT **********#
					if( $imageHeight > $imageMaxHeight ) {
						// 4. Fehlerfall (unerlaubte Bildhöhe)
						return array('imagePath' => NULL, 'imageError' => "Die Bildhöhe darf maximal $imageMaxHeight Pixel betragen");
					}					
					
					
					#********** VALIDATE IMAGE SIZE **********#
					if( $fileSize > $imageMaxSize ) {
						// 5. Fehlerfall (unerlaubte Dateigröße)
						return array('imagePath' => NULL, 'imageError' => 'Die Dateigröße darf maximal ' . $imageMaxSize/1024 . 'kB betragen');
					
					} // 2. IMAGE VALIDATION END
					#*************************************************************#
					
					
					#*************************************************************#
					#********** 3. PREPARE IMAGE FOR PERSISTANT STORAGE **********#
					#*************************************************************#					
					
					#********** GENERATE UNIQUE FILE NAME **********#
					/*
						Da der Dateiname selbst Schadcode in Form von ungültigen oder versteckten Zeichen,
						doppelte Dateiendungen (dateiname.exe.jpg) etc. beinhalten kann, darüberhinaus ohnehin 
						sämtliche, nicht in einer URL erlaubten Sonderzeichen und Umlaute entfernt werden müssten 
						sollte der Dateiname aus Sicherheitsgründen komplett neu generiert werden.
						
						Hierbei muss außerdem bedacht werden, dass die jeweils generierten Dateinamen unique
						sein müssen, damit die Dateien sich bei gleichem Dateinamen nicht gegenseitig überschreiben.
					*/
					/*
						- 	mt_rand() stellt die verbesserte Version der Funktion rand() dar und generiert 
							Zufallszahlen mit einer gleichmäßigeren Verteilung über das Wertesprektrum. Ohne zusätzliche
							Parameter werden Zahlenwerte zwischen 0 und dem höchstmöglichem von mt_rand() verarbeitbaren 
							Zahlenwert erzeugt.
						- 	str_shuffle() mischt die Zeichen eines übergebenen Strings zufällig durcheinander.
						- 	microtime() liefert einen Timestamp mit Millionstel Sekunden zurück (z.B. '0.57914300 163433596'),
							aus dem für eine URL-konforme Darstellung der Dezimaltrenner und das Leerzeichen entfernt werden.
					*/
					$fileName = mt_rand() . str_shuffle('abcdefghijklmnopqrstuvwxyz__--01234567890123456789') . str_replace( array('.', ' '), '', microtime() );
					
					
					#********** GENERATE FILE EXTENSION **********#
					/*
						Aus Sicherheitsgründen wird nicht die ursprüngliche Dateinamenerweiterung aus dem
						Dateinamen verwendet, sondern eine vorgenerierte Dateiendung aus dem Array der 
						erlaubten MIME Types.
						Die Dateiendung wird anhand des ausgelesenen MIME Types [key] ausgewählt.
					*/
					$fileExtension = $imageAllowedMimeTypes[$imageMimeType];
					
					
					#********** GENERATE FILE TARGET **********#
					/*
						Endgültigen Speicherpfad auf dem Server generieren:
						destinationPath/fileName.fileExtension
					*/
					$fileTarget = $imageUploadPath . $fileName . $fileExtension;
					
if(DEBUG)		echo "<p class='debug value hint validateImageUpload'><b>Line " . __LINE__ . "</b>:\$fileTarget: $fileTarget <i>(" . basename(__FILE__) . ")</i></p>\n";
					
					// 3. PREPARE IMAGE FOR PERSISTANT STORAGE END
					#*************************************************************#
					
					
					#********************************************************#
					#********** 4. MOVE IMAGE TO FINAL DESTINATION **********#
					#********************************************************#
					/*
						move_uploaded_file() verschiebt eine hochgeladene Datei an einen 
						neuen Speicherort und benennt die Datei um
					*/
					if( @move_uploaded_file($fileTemp, $fileTarget) === false ) {
						// 6. Fehlerfall (Bild kann nicht verschoben werden)
if(DEBUG)			echo "<p class='debug validateImageUpload err'><b>Line " . __LINE__ . "</b>: FEHLER beim Speichern des Bildes unter <i>'$fileTarget'</i>! <i>(" . basename(__FILE__) . ")</i></p>\n";				
						return array('imagePath' => NULL, 'imageError' => 'Es ist ein Fehler aufgetreten! Bitte versuchen Sie es später noch einmal.');
						
					} else {
						// Erfolgsfall
if(DEBUG)			echo "<p class='debug validateImageUpload ok'><b>Line " . __LINE__ . "</b>: Bild erfolgreich nach <i>'$fileTarget' verschoben</i>. <i>(" . basename(__FILE__) . ")</i></p>\n";				
						return array('imagePath' => $fileTarget, 'imageError' => NULL);
					}
					// 4. MOVE IMAGE TO FINAL DESTINATION END
					#*************************************************************#
					
					
					#********** LOCAL SCOPE END **********#
				}
				

#****************************************************************************************************#