<?php
session_start();

//Seja poteče po 30 minutah - avtomatsko odjavi neaktivnega uporabnika
if (isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] < 1800) {
	session_regenerate_id(true);
}
$_SESSION['LAST_ACTIVITY'] = time();

//Poveži se z bazo
$conn = new mysqli('localhost', 'root', '', 'vaja1');
//Nastavi kodiranje znakov, ki se uporablja pri komunikaciji z bazo
$conn->set_charset("UTF8");
?>
<html>

<head>
	<title>Vaja 1</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
	<article class="text-center bg-dark bg-gradient p-1">
		<h1 class="text-white">Oglasnik</h1>
		<h2 class="text-white"><?php 
			if (isset($_SESSION['USER_ID']->username)) {
				echo "Pozdravljeni uporabnik: " . $_SESSION['USER_ID']->username;
			}
			?></h2>
		<nav>
			<ul class="nav nav-tabs">
				<li><a class="link-primary nav-link active bg-light" href="index.php">Domov</a></li>
				<?php
				if (isset($_SESSION["USER_ID"])) {
				?>
					<li class="active"><a class="link-primary nav-link active bg-light" href="publish.php">Objavi oglas</a></li>
					<li class="nav-item"><a class="link-primary nav-link active bg-light" href="logout.php">Odjava</a></li>
					<li class="nav-item"><a class="link-primary nav-link active bg-light" href="mojoglas.php">Moji Oglasi</a></li>
				<?php
				} else {
				?>
					<li class="nav-item"><a class="link-primary nav-link active bg-light" href="login.php">Prijava</a></li>
					<li class="nav-item"><a class="link-primary nav-link active bg-light" href="register.php">Registracija</a></li>
				<?php
				}
				?>
			</ul>
			</nav>
	</article>