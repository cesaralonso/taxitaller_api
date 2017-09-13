
<?php 

$file = $_FILES["file"]["name"];
if(!is_dir("avatares/"))
    mkdir("avatares/", 0777);
if($file && move_uploaded_file($_FILES["file"]["tmp_name"], "avatares/".$file))
{
    echo $file;
}

?>