<?php

/**
 * Class Upload
 * @author S. M. A. Sayem Prodhan (Anan5a)
 */
class Upload
{
    private $savePath = './upload/';
    private $allowedType = ['image/jpeg','image/png','image/gif'];
    private $maxSize = 6000000;
    public $failed = 0;
    public $success = 0;
    public $error = '';
    public $files;

    public function __construct($path='', $types = [])
    {
        if (is_dir($path)) {
            $this->savePath = $path;
        }
        if (is_array($types)) {
            foreach ($types as $type) {
                $this->allowedType[] = $type;
            }
        }
    }

    public function upload($files, $max=null)
    {
        if ($max != null && is_int($max)) {
            $this->maxSize = $max;
        }
        $this->files = $files;
        $ret = $this->tryUpload($files);
        
        if (!empty($ret)) {
            return $ret;
        } else {
            return false;
        }
    }


    public function dumpInfo()
    {
        printf("Total: %s\n", count($this->files['name']));
        printf("Failed: %s\n", $this->failed);
        printf("Success: %s\n", $this->success);
        printf("Error: %s\n", $this->error);
        var_dump($this->files);
    }

    private function tryUpload($files)
    {
        $ret = [];
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                if (!($files['size'][$i] > $this->maxSize)) {
                    if ($this->is_Valid_Type($files['tmp_name'][$i])) {
                        $this->stripEXIF($files['tmp_name'][$i]);
                        $newName = $this->savePath.sha1(random_bytes(16));
                        if (move_uploaded_file($files['tmp_name'][$i], $newName)) {
                            $ret[] = $newName;
                            $this->success += 1;
                        } else {
                            $this->error = "Unknown error occured for file <b> ".basename($files['name'][$i])."</b>";
                            $this->failed += 1;
                        }
                    } else {
                        $this->error = "The filetype <b>".$this->getMime($files['tmp_name'][$i])."</b> isn't supported for<b> ".basename($files['name'][$i])."</b>";
                        $this->failed += 1;
                    }
                } else {
                    $this->error = "Maximum filesize limit <code>{$this->maxSize} bytes</code> exceeded @ <code>".$files['name'][$i]."</code>";
                    $this->failed += 1;
                }
            } else {
                $this->error = "Upload of file <b>".$files['name'][$i]."</b> failed with code <b>".$files['error'][$i]."</b>";
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
    /*
     * function stripEXIF
     * remove EXIF data from jpeg images
     */
    private function stripEXIF($file)
    {
        if (!function_exists('imagecreatefromjpeg')) {
            throw new RuntimeException("Cannot perform image processing, please install <b>ext-gd</b>");
        } else {
            if ($this->getMime($file) == 'image/jpeg') {
                @$res = imagecreatefromjpeg($file);
                if ($res) {
                    imagejpeg($res, $file, 100);
                    imagedestroy($res);
                    file_put_contents('file.log', 'Exif striping for '.$file."\n", FILE_APPEND|LOCK_EX);
                }
            } else {
                return true;
            }
        }
    }
}
