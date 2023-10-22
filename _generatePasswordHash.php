<?php
	
	$passwordHash = NULL;
	$passwordCheck = NULL;
	
	if( isset($_POST['formsent']) ) {
		$password = trim( htmlspecialchars($_POST['password']));
		$passwordHash = password_hash($password,PASSWORD_DEFAULT);
		if( $password == "" ) $password = "Leerstring (stellt kein gültiges Passwort dar)";
		
		if( password_verify($password,$passwordHash) ) {
			$passwordCheck = "<span class='success'>bestanden</span>";
		} else {
			$passwordCheck = "<span class='error'>nicht bestanden</span>";
		}
	}

?>

<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>Password-Hash generieren</title>
		<style>
			body { color: #666 }
			div { width: 60%; margin: 15% auto; border: 1px solid #088A85; border-radius: 10px; padding: 50px; }
			h1 { color: #088A85 }
			h3 { color: #666 }
			.success, .error { font-seight: bold; letter-spacing: 0.03em; }
			.success { color: green }
			.error { color: red }
			.info { color: #088A85; font-weight: bold; font-style: italic; }
			input { border-radius: 5px }
			input[type="submit"] {  padding: 5px 20px; width: 20%; min-width: 120px }
			input[type="text"] { width: 70%; padding: 5px; margin-right: 10px; }
			input.secret { width: 100%; font-size: 1.1em; background-color: #eee; color: #088A85; text-align: center; }
			input.secret, span.info {
				font-family: courier new;
			}
		</style>
	</head>
	
	<body>
		<div>
			<h1>Password-Hash generieren</h1>
			<p>Dieses Formular generiert einen mittels der PHP-Funktion <span class="info">password_hash()</span> verschlüsselten Password-Hash.</p>
			
			<form action="" method="POST">
				<input type="hidden" name="formsent">
				<input type="text" name="password" placeholder="Hier das zu verschlüsselndes Passwort eingeben...">
				<input type="submit" value="Hash erzeugen">
			</form>
			<br>
			<br>
			<?php if(isset($passwordHash)): ?>
			<p>Der Password-Hash für <span class='info'><?php echo $password ?></span> lautet:</p>
			<input class="secret" type="text" value="<?php echo $passwordHash ?>">
				<?php if($passwordCheck): ?>
					<br>
					<br>
					<p>Der Password-Check mittels der PHP-Funktion <span class="info">password_verify()</span> wurde <?php echo $passwordCheck ?>!</p>
				<?php endif ?>
			<?php endif ?>
		</div>
	</body>

</html>