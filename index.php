<?php
include_once('header.php');

function get_ads()
{
	global $conn;
	$query = "SELECT * FROM ads;";
	$res = $conn->query($query);
	$ads = array();
	while ($ad = $res->fetch_object()) {
		array_push($ads, $ad);
	}
	return $ads;
}

$ads = get_ads();
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
		
		<hr />
		<a href="ad.php?id=<?php echo $ad->id; ?>"><button class="btn btn-secondary text-white">Preberi več</button></a>
		<hr />
	</div>
<?php
}


include_once('footer.php');
?>
