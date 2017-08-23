<?PHP
require ('imgresize.php');
if ($handle = opendir('upload/')) {
    
    /* Именно этот способ чтения элементов каталога является правильным. */
	$mas_fle[]='';
	$i=0;
    while (false !== ($file = readdir($handle))) { 
       if ($file != "." && $file != "..") { 
             $mas_file[$i]= 'upload/'.$file.'';
             $mas_file_name[$i]= $file;
			 $i++;
        } 
    }
	closedir($handle);
}

foreach($mas_file as $key=>$val){
    $img = $val;
    $size = 400;
    $new_name = "min/".$mas_file_name[$key]."";
    $kache = 100;
     list($width, $height, $type, $attr) = getimagesize($img);
        if($width > $height){
            $dif = $width / $size; $width = $size; $height = $height / $dif;
        }
        else{
            $dif = $height / $size; $height = $size; $width = $width / $dif;
        }
   

      
      if (img_resize($img, $new_name, $width,$height, $kache))
        echo 'Размер картинки изменен!';
      else
        echo 'Ошибка!'; 
}
?>