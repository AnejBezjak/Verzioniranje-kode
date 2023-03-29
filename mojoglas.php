<?php
include_once('header.php');

// Funkcija prebere oglase iz baze in vrne polje objektov
function get_ads()
{
	global $conn;
	$user_id = $_SESSION["USER_ID"]->id;
	$query = "SELECT * FROM ads WHERE ads.user_id=$user_id;";
	$res = $conn->query($query);
	$ads = array();
	while ($ad = $res->fetch_object()) {
		array_push($ads, $ad);
	}
	return $ads;
}

//Preberi oglase iz baze
$ads = get_ads();

//Izpiši oglase
//Doda link z GET parametrom id na oglasi.php za gumb 'Preberi več'
foreach ($ads as $ad) {
	$img_data = base64_encode($ad->image);
?>
	<div class="p-2 bg-info text-black text-left">
		<h4><?php echo $ad->title; ?></h4>
		<p>Objavljeno: <?php echo $ad->date; ?></p>
		<img src="data:image/jpg;base64, <?php echo $img_data; ?>" width="400" />
		<p id="cats">Kategorije:
		<ul><?php
			$cats = explode("||", $ad->categories);
			foreach ($cats as $cat) {
				if ($cat != "") {
					echo "<li>" . $cat . "</li>";
				}
			}
			?></ul>
		</p>
		<a href="edit.php?id=<?php echo $ad->id; ?>"><button class="btn btn-secondary text-white">Uredi</button></a>
		<a href="izbirisi.php?id=<?php echo $ad->id; ?>"><button class="btn btn-secondary text-white">Izbrisi</button></a>
	</div>

<?php
}


include_once('footer.php');
?>
