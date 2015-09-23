<?php
	
	require_once("../../config.php");
	$database = "if15_Jork";
	$mysqli = new mysqli($servername, $username, $password, $database);
	
	//echo $_POST["email"];
	$email_error = "";
	$create_email_error = "";
	$password_error = "";
	$create_password_error = "";
	$create_date_error = "";
	
	//teen uue muutuja 
	$email = "";
	$create_date = "";
	$password = "";
	$create_email = "";
	$create_password = "";
	
	//keegi näppis mu nuppu
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		
		
		//vajutas login nuppu
		if(isset($_POST["login"])){
			
					echo " vajutas login nuppu! ";
				
					//echo "   Näpud eemale!";
					//kontrollin et email poleks tühi
						
				
					if ( empty($_POST["email"]) ) {
						$email_error = "See väli on kohustuslik";
					}else{
						$email = test_input($_POST["email"]);
					}
					
					//kontrollin, et parool ei ole tühi
					if ( empty($_POST["password"]) ) {
						$password_error = "See väli on kohustuslik";
					} else {
						
						//kui oleme siia jõudnud siis parool ei ole tühi
						if(strlen($_POST["password"]) < 8) {
							
							$password_error = "peab olema vähemalt 8 tähemärki pikk";
						}	
						
					}
					
					//kontrollin et poleks erroreid
					if($email_error == "" && $password_error == ""){
						
							echo "kontrollin sisselogimist ".$email." ja parool ";
					}
					
				//keegi vajutas create nuppu
				}elseif(isset($_POST["create"])){
					
					echo " vajutas create nuppu";
					
					if ( empty($_POST["date"]) ) {
						$create_date_error = "See väli on kohustuslik";
					}else{
						$date = test_input($_POST["date"]);
					}
					if($create_date_error == ""){
						echo "         salvestan ab'i    ".$create_date;
					}
					if ( empty($_POST["create_email"]) ) {
						$create_email_error = "See väli on kohustuslik";
					}else{
						$name = test_input($_POST["name"]);
					}
					if ( empty($_POST["password"]) ) {
						$create_password_error = "See väli on kohustuslik";
					}
							//räsi paroolist, mille salvestame ab'i 
					$hash = hash("sha512", $create_password);
					
					echo "Võib kasutajat luua! Kasutajanimi on ".$create_email." ja parool on ".$create_password."ja räsi on".$hash;
					$stmt = $mysqli->prepare("INSERT INTO users (email, password) Values (?, ?)");
					echo $mysqli->error;
					//echo $stmt->error;
					$hash = hash("sha512", $password);
					
					$stmt->bind_param("ss", $create_email, $hash);
					$stmt->execute();
					$stmt->close();
				}
		
		}
	
		function test_input($data){
			//võtab ära tühikud, enterid, tabid
			$data = trim ($data);
			//tagurpidi kaldkriipsud
			$data =stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;	
			
		}
	
	
	
	
	
?>
<?php 
	$page_title = "Sisselogimise leht";
	$page_file_name = "login.php";
?>
<?php require_once("../header.php"); ?>
	<h2>Log in</h2>
		<form action="login.php" method="post" >
			<input name="email" type="email" placeholder="Email"> <?php echo $email_error ?> <br><br>
			<input name="pass" type="password" placeholder="Parool"> <?php echo $password_error ?> <br><br>
			<input name="login" type="submit" value="login">
		</form>
	
	<h2>Create user</h2>
	Tärniga märgitud lahtrid on kohustuslikud
		<form action="login.php" method="post" >
		<input name="email" type="email" placeholder="Email ">*<?php echo $create_email_error ?><br><br>
		<input name="pass" type="password" placeholder="Parool ">*<?php echo $create_password_error ?><br><br>
		<input name="name" type="firstname" placeholder="Eesnimi"><br><br>
		<input name="name" type="lastname" placeholder="Perekonnanimi"><br><br>
		<input name="date" type="date" placeholder="Sünniaeg ">*<?php echo $create_date_error ?><br>
		<input name="create" type="submit" value="create">
		</form>
<?php require_once("../footer.php"); ?>