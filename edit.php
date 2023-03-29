<?php
include_once('header.php');

// Funkcija vstavi nov oglas v bazo
// Preveri ce so vsi podatki pravilni 
// Vrne false, če je prišlo do napake
// Vrne true ce je bil oglas uspesno vstavljen
function publish($title, $desc, $img, $cats)
{
    global $conn;
    $title = mysqli_real_escape_string($conn, $title);
    $desc = mysqli_real_escape_string($conn, $desc);
    $cats = mysqli_real_escape_string($conn, $cats);
    $user_id = $_SESSION["USER_ID"]->id;

    $img_file = file_get_contents($img["tmp_name"]);
    $img_file = mysqli_real_escape_string($conn, $img_file);
    $dateNow = date("d.m.Y");
    $id = $_GET['id'];

    $query = "UPDATE ads SET title='" . $title . "', description='" . $desc . "', image='" . $img_file . "', categories='" . $cats . "', date='" . $dateNow . "' WHERE ads.user_id=$user_id AND ads.id=$id;";
    if ($conn->query($query)) {
        return true;
    } else {
        echo mysqli_error($conn);
        return false;
    }
}

$id = $_GET['id'];
global $conn;

$query = "SELECT * FROM ads WHERE ads.id=$id";
$res = $conn->query($query);
$data = $res->fetch_object();
$cats = explode("||", $data->categories);

$error = "";
if (isset($_POST["edit"])) {
    $finalCats = "";
    foreach ($_POST["category"] as $subcats) {
        $finalCats = $finalCats . $subcats . "||";
    }
    if (publish($_POST["title"], $_POST["description"], $_FILES["image"], $finalCats)) {
        header("Location: mojoglas.php");
        die();
    } else {
        $error = "Prišlo je do našpake pri urejanu oglasa.";
    }
}

?>
<form class="p-2 bg-info text-black text-left" action="edit.php?id=<?php echo $id ?>" method="POST" enctype="multipart/form-data">
    <h2>Uredi oglas</h2>
    <label>Naslov</label><br><input type="text" name="title" value="<?php echo $data->title ?>" /> <br /><br />
    <label>Vsebina</label><br><textarea name="description" rows="10" cols="50"><?php echo $data->description ?></textarea> <br /><br />
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
                foreach ($cats as $cat) {
                    if ($cat != "" && $cat == $y) {
                        echo '<label class="form-check-label">' . $y . '</label> <input class="form-check-input" type="checkbox" checked id=' . $y . ' name="category[]" value=' . $y . '> <br>';
                        break;
                    } else {
                        echo '<label class="form-check-label">' . $y . '</label> <input class="form-check-input" type="checkbox" id=' . $y . ' name="category[]" value=' . $y . '> <br>';
                        break;
                    }
                }
            }
        }
        ?>
    </section>
    <br />
    <label>Slika</label><br><input type="file" name="image"/> <br /><br />
    <input class="btn btn-secondary text-white" type="submit" name="edit" value="Končano" /> <br />
    <label><?php echo $error; ?></label>
    
</form>
<?php
include_once('footer.php');
?>