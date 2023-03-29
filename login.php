<?php
include_once('header.php');

function validate_login($username, $password){
	global $conn;
	$username = mysqli_real_escape_string($conn, $username);
	$pass = hash('sha256',$password);
	$query = "SELECT * FROM users WHERE username='$username' AND password='$pass'";
	$res = $conn->query($query);
	if($user_obj = $res->fetch_object()){
		return $user_obj;
	}
	return -1;
}

$error="";
if(isset($_POST["submit"])){
	//Preveri prijavne podatke
	$user_id = validate_login($_POST["username"], $_POST["password"]);
	if($user_id->id >= -1){
		//Zapomni si prijavljenega uporabnika v seji in preusmeri na index.php
		$_SESSION["USER_ID"] = $user_id;
		header("Location: index.php");
		die();
	} else{
		$error = "Prijava ni uspela.";
	}
}
?>
	<form action="login.php" method="POST" class="p-2 bg-info text-black text-left">
		<h2>Prijava</h2>
		<label>Uporabniško ime</label><input type="text" name="username"/> <br/>
		<label>Geslo</label><input type="password" name="password"/> <br/>
		<input type="submit" name="submit" value="Prijava" class="btn btn-secondary text-white" /> <br/>
		<label><?php echo $error; ?></label>
	</form>
<?php
include_once('footer.php');
?>