<?php

/**
 * Class Upload
 * @author S. M. A. Sayem Prodhan (Anan5a)
 */
class Upload
{
	private $savePath = 'upload/';
	private $allowedType = ['iamge/jpeg'];
	public  $failed = 0,
			$success = 0,
			$error = [];

	public function __construct($path='' ,$types = []){
		if(is_dir($path)){
			$this->savePath = $path;
		}
		if(is_array($types)){
			array_push($types,$$this->allowedType);
		}
	}

}
