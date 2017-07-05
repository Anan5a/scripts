<?php
require_once 'Upload.php';
if (isset($_POST['submit'])) {
    echo "Max 5MB<br>";
    $upl = new Upload();
    $t = $upl->upload($_FILES['im']);
    if (!empty($t)) {
        foreach ($t as $r) {
            echo "<a href=\'/$r\'>$r</a>";
        }
    } else {
        echo "<h1>No file uploaded!";
    }
   // $upl->dumpInfo();
}

//Deletor for demo. don't use the following code
function del()
{
    $f = glob('upload/*');
    foreach ($f as $value) {
        if (filemtime($value)+(3600) > time()) {
            unlink($value);
            printf('<br> Deleted <strong>%s</strong><br>', $value);
        }
    }
}
echo "/********These files are deleted!********/<br>";
del();
