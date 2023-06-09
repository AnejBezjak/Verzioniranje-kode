<?php
include_once('header.php');

// Funkcija preveri, ali v bazi obstaja uporabnik z določenim imenom in vrne true, če obstaja.
function username_exists($username){
	global $conn;
	$username = mysqli_real_escape_string($conn, $username);
	$query = "SELECT * FROM users WHERE username='$username'";
	$res = $conn->query($query);
	return mysqli_num_rows($res) > 0;
}

// Funkcija ustvari uporabnika v tabeli users. Poskrbi tudi za ustrezno šifriranje uporabniškega gesla.
function register_user($username, $password, $name, $surname, $email, $address, $mail, $phone){
	global $conn;
	$username = mysqli_real_escape_string($conn, $username);
	$name = mysqli_real_escape_string($conn, $name);
	$surname = mysqli_real_escape_string($conn, $surname);
	$email = mysqli_real_escape_string($conn, $email);
	$address = mysqli_real_escape_string($conn, $address);
	$mail = mysqli_real_escape_string($conn, $mail);
	$phone = mysqli_real_escape_string($conn, $phone);
	$pass = hash('sha256',$password);
	/* 
		Tukaj za hashiranje gesla uporabljamo sha1 funkcijo. V praksi se priporočajo naprednejše metode, ki k geslu dodajo naključne znake (salt).
		Več informacij: 
		http://php.net/manual/en/faq.passwords.php#faq.passwords 
		https://crackstation.net/hashing-security.htm
	*/
	$query = "INSERT INTO users (username, password, name, surname, email, mail, address, phone) VALUES ('$username', '$pass','$name', '$surname', '$email', '$mail', '$address', '$phone');";
	if($conn->query($query)){
		return true;
	}
	else{
		echo mysqli_error($conn);
		return false;
	}
}

$error = "";
if(isset($_POST["submit"])){
	/*
		VALIDACIJA: preveriti moramo, ali je uporabnik pravilno vnesel podatke (unikatno uporabniško ime, dolžina gesla,...)
		Validacijo vnesenih podatkov VEDNO izvajamo na strežniški strani. Validacija, ki se izvede na strani odjemalca (recimo Javascript), 
		služi za bolj prijazne uporabniške vmesnike, saj uporabnika sproti obvešča o napakah. Validacija na strani odjemalca ne zagotavlja
		nobene varnosti, saj jo lahko uporabnik enostavno zaobide (developer tools,...).
	*/
	//Preveri če se gesli ujemata
	if($_POST["password"] != $_POST["repeat_password"]){
		$error = "Gesli se ne ujemata.";
	}
	//Preveri ali uporabniško ime obstaja
	else if(username_exists($_POST["username"])){
		$error = "Uporabniško ime je že zasedeno.";
	}
	//Podatki so pravilno izpolnjeni, registriraj uporabnika
	else if(register_user($_POST["username"], $_POST["password"], $_POST["name"], $_POST["surname"], $_POST["email"], $_POST["address"], $_POST["mail"], $_POST["phone"])){
		header("Location: login.php");
		die();
	}
	//Prišlo je do napake pri registraciji
	else{
		$error = "Prišlo je do napake med registracijo uporabnika.";
	}
}

?>
	<form action="register.php" method="POST" class="p-2 bg-info text-black text-left">
	<h2>Registracija</h2>
	<label>Ime</label><input type="text" name="name"/> <br/>
	<label>Priimek</label><input type="text" name="surname"/> <br/>
	<label>E-mail</label><input type="text" name="email"/> <br/>
		<label>Uporabniško ime</label><input type="text" name="username" /> <br/>
		<label>Geslo</label><input type="password" name="password"/> <br/>
		<label>Ponovi geslo</label><input type="password" name="repeat_password"/> <br/>
		<label>Naslov</label><input type="text" name="address"/> <br/>
		<label>Pošta</label><input type="text" name="mail"/> <br/>
		<label>Telefon</label><input type="text" name="phone"/> <br/>
		<input type="submit" name="submit" value="Potrdi" class="btn btn-secondary text-white"/> <br/>
		<label><?php echo $error; ?></label>
	</form>
<?php
include_once('footer.php');
?>