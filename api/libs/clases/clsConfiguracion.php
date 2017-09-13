<?php
class Configuracion
{
	public  $site;
	public  $host;
	public  $dirImgs;
 	public  $dirImgsThumbs;


	public function __construct()
	{
		$this->site = "taxytaller";
		$this->host = "http://www.taxytaller.com/";
		$this->dirImgs = "http://www.taxytaller.com/catalogo_imgs/";
		$this->dirImgsThumbs = "http://www.taxytaller.com/catalogo_imgs/thumbs/";
	}
	
	public function get_config(){
		return  array(
			"host" => $this->host,
			"site_name" => $this->site,
			"dirImgs" => $this->dirImgs,
			"dirImgsThumbs" => $this->dirImgsThumbs
		);
	}
	

}
?>