<?php
#**********************************************************************************#

				
				#****************************************#
				#********** PAGE CONFIGURATION **********#
				#****************************************#
				
				/*
					include(Pfad zur Datei): Bei Fehler wird das Skript weiter ausgeführt. Problem mit doppelter Einbindung derselben Datei
					require(Pfad zur Datei): Bei Fehler wird das Skript gestoppt. Problem mit doppelter Einbindung derselben Datei
					include_once(Pfad zur Datei): Bei Fehler wird das Skript weiter ausgeführt. Kein Problem mit doppelter Einbindung derselben Datei
					require_once(Pfad zur Datei): Bei Fehler wird das Skript gestoppt. Kein Problem mit doppelter Einbindung derselben Datei
				*/
				require_once('./include/config.inc.php');
				require_once('./include/form.inc.php');
				require_once('./include/functionen.inc.php');


#**********************************************************************************#
				
				#******************************************************************#
				#********** Rechner **********#
				#******************************************************************#
				
				function rechner($zahl1=5, $zahl2=10, $operator='+') {
					#********** LOCAL SCOPE START **********#
if(DEBUG_F)		echo "<p class='debug function'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$zahl1, $zahl2, $operator') <i>(" . basename(__FILE__) . ")</i></p>\n";

					switch( $operator ) {
						case '+':		return $zahl1 + $zahl2;
						case '-':		return $zahl1 - $zahl2;
						case '*':		return $zahl1 * $zahl2;
						
						case '/':		if( $zahl2 == 0 ) return 'Division durch 0 ist nicht erlaubt!';
											return $zahl1 / $zahl2;
						default:			return 'Ungültiger Rechenoperator!';
					}
					#********** LOCAL SCOPE END **********#
				}
				
				
#****************************************************************************************************#

				#******************************************************************#
				#********** Rechner **********#
				#******************************************************************#
				
				function istHaus($eingabe) {
					#********** LOCAL SCOPE START **********#
if(DEBUG_F)		echo "<p class='debug function'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$eingabe') <i>(" . basename(__FILE__) . ")</i></p>\n";

					if($eingabe === 'Haus'){
						return true;
					}else{
						return false;
					}
					#********** LOCAL SCOPE END **********#
				}
				
				
#****************************************************************************************************#

				#******************************************************************#
				#********** Rechner **********#
				#******************************************************************#
				
				/**
				 * Überprüft, ob ein Wert innerhalb eines bestimmten Bereichs liegt.
				 *
				 * @param int|float $value Der zu überprüfende Wert.
				 * @param int|float $min Das untere Limit des Bereichs (Standard: 20).
				 * @param int|float $max Das obere Limit des Bereichs (Standard: 30).
				 * @return string Eine Nachricht, die den Status des Wertes im Bereich angibt.
				 */
				function isValueBetreen($value, $min=20, $max=30) {
					#********** LOCAL SCOPE START **********#
if(DEBUG_F)		echo "<p class='debug function'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$value, $min, $max') <i>(" . basename(__FILE__) . ")</i></p>\n";

					// Überprüfung, ob der Wert innerhalb des Bereichs liegt
					if($value < $min){
						return "Der übergebene Wert ist kleiner als " . $min;
					} elseif($value > $max){
						return "Der übergebene Wert ist größer als " . $max;
					} else {
						return "Der übergebene Wert liegt zwischen " . $min . " und " . $max;
					}
					
					#********** LOCAL SCOPE END **********#
				}
				
				
#****************************************************************************************************#


				#******************************************************************#
				#********** Stringlängenvergleich **********#
				#******************************************************************#
				
				/**
				 * Vergleicht die Länge von zwei Zeichenketten und gibt eine entsprechende Nachricht zurück.
				 *
				 * @param string $string1 Die erste Zeichenkette.
				 * @param string $string2 Die zweite Zeichenkette.
				 * @return string Die Vergleichsnachricht basierend auf den Zeichenkettenlängen.
				 */
				function strLenghtCompare($string1, $string2) {
					#********** LOCAL SCOPE START **********#
if(DEBUG_F)		echo "<p class='debug function'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$string1, $string2) <i>(" . basename(__FILE__) . ")</i></p>\n";

					// Vergleich der Zeichenkettenlängen
					if(mb_strlen($string1) < mb_strlen($string2)){
						return "Der erste String ist länger als der zweite";
					} elseif(mb_strlen($string1) > mb_strlen($string2)){
						return "Der zweite String ist länger als der erste";
					} else {
						return "Beide Strings sind gleich lang";
					}
					#********** LOCAL SCOPE END **********#
				}
				
				
#****************************************************************************************************#


