<?php
#**************************************************************************************#


				#******************************************#
				#********** GLOBAL CONFIGURATION **********#
				#******************************************#
				
				/*
					Konstanten werden in PHP mittels der Funktion define() oder über 
					das Schlüsselwort const (const DEBUG = true;) definiert. Seit PHP7
					ist der Unterschied zwischen den beiden Varianten, dass über 
					const definierte Konstanten nicht innerhalb von Funktionen, Schleifen, 
					if-Statements oder try/catch-Blöcken definiert werden können. 
					Konstanten besitzen im Gegensatz zu Variablen kein $-Präfix
					Üblicherweise werden Konstanten komplett GROSS geschrieben.
					
					Konstanten können in PHP auf zwei unterschiedliche Arten deklariert werden:
					Über das Schlüsselwort const und über die Funktion define().
					
					const DEBUG = true;
					define('DEBUG', true);
				*/
				
				
				#********** DATABASE CONFIGURATION **********#
				define('DB_SYSTEM',							'mysql');
				define('DB_HOST',								'localhost');
				define('DB_NAME',								'blog_v1');
				define('DB_USER',								'root');
				define('DB_PWD',								'');
				
				
				#********** FORM CONFIGURATION **********#
				define('INPUT_MIN_LENGTH',					0);
				define('INPUT_MAX_LENGTH',					256);
				
				
				#********** IMAGE UPLOAD CONFIGURATION **********#
				define('IMAGE_ALLOWED_MIME_TYPES',		array('image/jpg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/png'=>'.png', 'image/gif'=>'.gif'));
				define('IMAGE_MIN_SIZE',					1024);
				define('IMAGE_MAX_SIZE',					128*1024);
				define('IMAGE_MAX_WIDTH',					800);
				define('IMAGE_MAX_HEIGHT',					800);
				
				
				#********** STANDARD PATHS CONFIGURATION **********#
				define('IMAGE_UPLOAD_PATH',				'uploads/blogimages/');
				define('AVATAR_DUMMY_PATH',				'./css/images/avatar_dummy.png');
				
				
				#********** DEBUGGING **********#
				define('DEBUG', 								true);	// Debugging for main document
				define('DEBUG_V', 							true);	// Debugging for values				
				define('DEBUG_F', 							true);	// Debugging for form functions				
				define('DEBUG_DB', 							true);	// Debugging for database functions		
				define('DEBUG_C', 							true);	// Debugging for classes
				define('DEBUG_CC', 							true);	// Debugging for class constructors and destructors



#**************************************************************************************#
?>