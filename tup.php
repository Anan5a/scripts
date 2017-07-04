<?php
require_once 'Upload.php';
if (isset($_POST['submit'])) {
    $upl = new Upload();
    $upl->upload($_FILES['im']);
    var_dump($upl);
}
