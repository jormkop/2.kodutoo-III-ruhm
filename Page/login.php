<?php
	
	require_once("../../config.php");
	$database = "if15_Jork";
	$mysqli = new mysqli($servername, $username, $password, $database);
	
	//echo $_POST["email"];
	$email_error = "";
	$create_email_error = "";
	$password_error = "";
	$create_password_error = "";
	$first_name_error = "";
	$last_name_error = "";
	
	//teen uue muutuja 
	$email = "";
	$password = "";
	$create_email = "";
	$create_password = "";
	$first_name = "";
	$last_name = "";
	
	//keegi näppis mu nuppu
	if($_SERVER["REQUEST_METHOD"] == "POST" ) {
		
		if(isset($_POST["login"])){
			
			if ( empty($_POST["email"]) ) {
				$email_error = "Seda välja ei tohi tühjaks jätta!";
			}else{
				$email = test_input($_POST["email"]);
			}
			//kontrollin, et parool ei ole tühi
		
			if (empty($_POST["password"]) ) {
				$password_error = "Seda välja ei tohi tühjaks jätta!";
			}else{
				$password = test_input($_POST["password"]);
			} 
		
			if($email_error == "" && $password_error == ""){
				echo "Võib sisse logida ".$email." ja parool".$password;
			
				$hash = hash("sha512", $password);
			
				$stmt = $mysqli->prepare("SELECT id, email FROM user_sample WHERE email=?");
				$stmt->bind_param("ss", $email, $hash);
			
				//muutujad tulemustele
				$stmt->bind_result($id_from_db, $email_from_db);
				$stmt->execute();
			
				//Kontrollin kas tulemus leiti
				if($stmt->fetch()){
					//ab'is oli midagi
					echo " kasutajatunnused õiged, user id=".$id_from_db;
				}else{
					//ei olnud midagi
					echo " valed kasutajatunnused!";
				}
			
				$stmt->close();
			}	
		}
		
	 //***************************************************************************		
	    if(isset($_POST["create"])){		
			if (empty($_POST["create_email"])){
				$create_email_error = "Seda välja ei tohi tühjaks jätta!";
			}else{
				$create_email = test_input($_POST["create_email"]);
			}	
			
			if (empty($_POST["create_password"])){
				$create_password_error = "Seda välja ei tohi tühjaks jätta!";
			}else{
				if(strlen($_POST["create_password"]) <8) {
				$create_password_error = "Parool peab vähemalt 8 tähemärki pikk olema";
				}else{
				$create_password = test_input($_POST["create_password"]);
				}
			}
			
			if (empty ($_POST["first_name"])){
				$first_name_error = "Seda välja ei tohi tühjaks jätta!";
			}else{
				$first_name = test_input($_POST["first_name"]);
			}
			
			if (empty ($_POST["last_name"])){
				$last_name_error = "Seda välja ei tohi tühjaks jätta!";
			}else{
				$last_name = test_input($_POST["last_name"]);
			}
			
		if( $create_email_error== "" && $create_password==""){
					
			$hash = hash("sha512", $create_password);
			echo "kasutajanimi on ".$create_email." , parool  ".$create_password." ja räsi on ".$hash;
			
			$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password, first_name, last_name) VALUES (?,?,?,?)");
			
			$stmt->bind_param("ss", $create_email, $hash);
			$stmt->execute();
			$stmt->close();
		}
	}
}	
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
}
	
	$mysqli->close();
	
	
	
	
?>
<?php 
	$page_title = "Sisselogimise leht";
	$page_file_name = "login.php";
?>
<?php require_once("../header.php"); ?>
	<h2>Log in</h2>
		<form action="login.php" method="post" >
			<input name="email" type="email" placeholder="Email"> <?php echo $email_error ?> <br><br>
			<input name="password" type="password" placeholder="Parool"> <?php echo $password_error ?> <br><br>
			<input name="login" type="submit" value="login">
		</form>
	
	<h2>Create user</h2>
	Tärniga märgitud lahtrid on kohustuslikud
		<form action="login.php" method="post" >
			<input name="create_email" type="email" placeholder="E-mail" >*<?php echo $create_email_error; ?> <br><br>
			<input name="create_password" type="password" placeholder="Parool" >*<?php echo $create_password_error; ?> <br><br>
			<input name="first_name" type="text" placeholder="Eesnimi" >* <?php echo $first_name_error; ?> <br><br>
			<input name="last_name" type="text" placeholder="Perekonnanimi" >* <?php echo $last_name_error; ?> <br><br>
			<input name="create" type="submit" value="Create user" >
		</form>	
<?php require_once("../footer.php"); ?>