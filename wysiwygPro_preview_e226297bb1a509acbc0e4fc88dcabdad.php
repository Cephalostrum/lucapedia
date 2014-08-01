<?php
if ($_GET['randomId'] != "lYKI834KglaX4s62754o3v2qpJf1G4d79MX0CF_ZkGyy1yOfSgqNPEpQPhLK7Dvi") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
