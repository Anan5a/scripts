# Docs for Upload.php

Uses :
```php
//Create new instance
$uploader = new Upload();//accept save path(/path/to/upload/) as 1st and allowed type as 2nd parameter
//Upload
$files = $uploader->upload($_FILES['name']);//accept max size in bytes as 2nd parameter
var_dump($files);
```

