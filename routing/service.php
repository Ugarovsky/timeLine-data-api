<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.beitgamliel.org.il/privatePhotoArchive.php");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
$result = curl_exec($ch);
?>

<script>
    const apiData = JSON.parse(`<? echo $result ?>`);
    console.log(apiData);
</script> 