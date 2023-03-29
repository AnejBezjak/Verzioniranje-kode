<?php
include_once('header.php');

//Funkcija izbere oglas s podanim ID-jem. Doda tudi uporabnika, ki je objavil oglas.
function get_ad($id)
{
	global $conn;
	$id = mysqli_real_escape_string($conn, $id);
	$query = "SELECT ads.*, users.username, users.name, users.surname, users.email, users.mail, users.address, users.phone FROM ads LEFT JOIN users ON users.id = ads.user_id WHERE ads.id = $id;";
	$res = $conn->query($query);
	if ($obj = $res->fetch_object()) {
		return $obj;
	}
	return null;
}

if (!isset($_GET["id"])) {
	echo "Manjkajoči parametri.";
	die();
}
$id = $_GET["id"];
$ad = get_ad($id);
global $conn;
$query = "SELECT * from ads WHERE ads.id=$id;";
$res = $conn->query($query);
$obj = $res->fetch_object();

$temp = 0;

if (isset($_SESSION['USER_ID']->username) && $temp == 0) {
	$query = "UPDATE ads SET views='" . $obj->views . $_SESSION['USER_ID']->username . "||"  . "' WHERE ads.id=$id;";
	$conn->query($query);
}

if ($ad == null) {
	echo "Oglas ne obstaja.";
	die();
}

?>
<div class="p-2 bg-info text-black text-left">
	<h4><?php echo $ad->title; ?></h4>
	<p><?php echo $ad->description; ?></p>
	<p id="slideshow">
		<?php
		global $conn;
		$query = "SELECT * FROM images WHERE id_ad=$id;";
		$res = $conn->query($query);
		while ($img = $res->fetch_object()) {
			$img_data = base64_encode($img->image);
			echo "<img src='data:image/jpg;base64,  $img_data' width='400' />";
		}
		?>
	</p>
	<p>Objavil: <?php echo $ad->username . "<br> <br> Podatki o prodajalcu: <br>" . $ad->name . " " . $ad->surname . "<br>" . $ad->email . "<br>" . $ad->address . " " . $ad->mail . "<br>" . $ad->phone . "<br>" ?></p>
	<p>Datum Objave: <?php echo $ad->date; ?></p>
	<p>Kategorije:
	<ul><?php
		$cats = explode("||", $ad->categories);
		foreach ($cats as $cat) {
			if ($cat != "") {
				echo "<li>" . $cat . "</li>";
			}
		}
		?></ul>
	</p>


	<a href="index.php"><button class="btn btn-secondary text-white">Nazaj</button></a>
</div>

<?php

include_once('footer.php');
?>
