<?php
include_once('header.php');
global $conn;
$error = "";
if (isset($_SESSION["USER_ID"]) && isset($_GET["id"])) {
    $ad_id = $_GET['id'];
    $user_id = $_SESSION["USER_ID"];

    // check if the user is the owner of the ad



    
    $query = "DELETE FROM ads WHERE id='$ad_id'";
    $result = $conn->query($query);
}
    /*
    $query = "SELECT id FROM ads WHERE id='$ad_id' AND user_id='$user_id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        // delete the ad
        $query = "DELETE FROM ads WHERE id='$ad_id'";
        if ($conn->query($query)) {
            header("Location: mojoglas.php");
            die();
        } else {
            $error = "Prišlo je do napake pri brisanju oglasa.";
        }
    } else {
        $error = "Oglas ne obstaja.";
    }
} else {
    $error = "Oglas ne obstaja.";
}*/
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card my-5">
                <div class="card-body">
                    <h2 class="card-title">Izbriši oglas</h2>
                    <p class="card-text"><?php echo $error; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>