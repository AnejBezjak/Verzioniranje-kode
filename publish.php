<?php
include_once('header.php');

// Funkcija vstavi nov oglas v bazo. Preveri tudi, ali so podatki pravilno izpolnjeni. 
// Vrne false, če je prišlo do napake oz. true, če je oglas bil uspešno vstavljen.
function publish($title, $desc, $img, $cats)
{
	global $conn;
	$title = mysqli_real_escape_string($conn, $title);
	$desc = mysqli_real_escape_string($conn, $desc);
	$cats = mysqli_real_escape_string($conn, $cats);
	$user_id = $_SESSION["USER_ID"]->id;

	$img_file = file_get_contents($img["tmp_name"][0]);
	$img_file = mysqli_real_escape_string($conn, $img_file);
	$dateNow = date("d.m.Y");

	$query = "INSERT INTO ads (title, description, user_id, image, categories, date)
				VALUES('$title', '$desc', '$user_id', '$img_file', '$cats', '$dateNow');";
	if ($conn->query($query)) {
		$query = "SELECT max(id) FROM ads;";
		$res = $conn->query($query);
		$res->fetch_all(MYSQLI_ASSOC);
		$obj = 0;

		foreach ($res as $x) {
			foreach ($x as $y) {
				$obj = $y;
			}
		}

		foreach ($img["tmp_name"] as $img) {
			$img_file = file_get_contents($img);
			$img_file = mysqli_real_escape_string($conn, $img_file);
			$query = "INSERT INTO images (image, id_ad)
				VALUES('$img_file', '$obj');";
			$conn->query($query);
		}
		return true;
	} else {
		return false;
	}
}

$error = "";
if (isset($_POST["submit"])) {
	$finalCats = "";
	foreach ($_POST["category"] as $subcats) {
		$finalCats = $finalCats . $subcats . "||";
	}
	if (publish($_POST["title"], $_POST["description"], $_FILES["image"], $finalCats)) {
		header("Location: index.php");
		die();
	} else {
		$error = "Prišlo je do našpake pri objavi oglasa.";
	}
}
?>
<form class="p-2 bg-info text-black text-left" action="publish.php" method="POST" enctype="multipart/form-data">
	<h2>Objavi oglas</h2>
	<label>Naslov</label><br><input type="text" name="title" /> <br /><br />
	<label>Vsebina</label><br><textarea name="description" rows="10" cols="50"></textarea> <br /><br />
	<label>Izberi kategorije:</label><br>
	<section class="form-check" id="selection">
		<style>
			#selection {
				width: 200px;
				max-height: 200px;
				overflow: auto;
			}
		</style>
		<?php
		global $conn;
		$query = "SELECT name FROM categories;";
		$result = $conn->query($query);
		$result->fetch_all(MYSQLI_ASSOC);

		foreach ($result as $x) {
			foreach ($x as $y) {
				echo '<label class="form-check-label">' . $y . '</label> <input class="form-check-input" type="checkbox" id=' . $y . ' name="category[]" value=' . $y . '> <br>';
			}
		}
		?>
	</section>
	<br />
	<label>Slika</label><br><input type="file" name="image[]" multiple /> <br />
	<br />
	<input class="btn btn-secondary text-white" type="submit" name="submit" value="Objavi" /> <br />
	<label><?php echo $error; ?></label>
</form>
<?php
include_once('footer.php');
?>