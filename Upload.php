<?php

/**
 * Class Upload
 * @author S. M. A. Sayem Prodhan (Anan5a)
 */
class Upload
{
    private $savePath = './upload/';
    private $allowedType = ['iamge/jpeg','image/png','image/gif'];
    private $maxSize = 6000000;
    public $failed = 0;
    public $success = 0;
    public $error = [];

    public function __construct($path='', $types = [])
    {
        if (is_dir($path)) {
            $this->savePath = $path;
        }
        if (is_array($types)) {
            array_push($types, $$this->allowedType);
        }
    }

    public function upload($files)
    {
        $ret = $this->tryUpload($files);
        //if the first element isn\t null then return $ret
        if ($ret[0] != null) {
            return $ret;
        } else {
            return false;
        }
    }

    private function tryUpload($files)
    {
        $ret = [];
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                if (!($files['size'[$i]] > $this->maxSize)) {
                    if (is_Valid_Type($files['tmp_name'][$i])) {
                        $newName = $this->savePath.sha1($files['tmp_name'][$i]);
                        if (move_uploaded_file($files['tmp_name'][$i], $newName)) {
                            $ret[] = $newName;
                        } else {
                            $this->error['UNK'] = "Unknown error occured for file <b> ".basename($files['name'][$i])."</b>";
                            $this->failed += 1;
                        }
                    } else {
                        $this->error['UFT'] = "The filetype <b><{$this->getMime($files['tmp_name'][$i])}/b> isn't supported for<b> $files[name][$i]</b>";
                        $this->failed += 1;
                    }
                } else {
                    $this->error['SLE'] = "Maximum filesize limit <code>{$this->maxSize} bytes</code> exceeded @ <code>$files[name][$i]</code>";
                    $this->failed += 1;
                }
            } else {
                $this->error['UE'] = "Upload of file <b>$files[name][$i]</b> failed with code <b>$files[error][$i]</b>";
                $this->failed += 1;
            }
        }
        return $ret;
    }

    private function getMime($file)
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        return $finfo->file($file);
    }

    private function is_Valid_Type($file)
    {
        $mime  = $this->getMime($file);
        if (in_array($mime, $this->allowedType)) {
            return true;
        } else {
            return false;
        }
    }
}
