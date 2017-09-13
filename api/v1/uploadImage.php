
<?php 

$file = $_FILES["file"]["name"];
if(!is_dir("imagenes/"))
    mkdir("imagenes/", 0777);
if($file && move_uploaded_file($_FILES["file"]["tmp_name"], "imagenes/".$file))
{
    echo $file;
}


?>