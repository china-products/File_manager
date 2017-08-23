<?php
    class fileWork extends help{
        
        /**
        * 
        * @param type $puth_file
        * @return путь к временной папке архива
        */
       public function  openZip($puth_file){
           $zip = new ZipArchive;
           $zip->open($puth_file);
           $dir = explode('/',$_SERVER['REQUEST_URI']);
           array_pop($dir);
           $dir = implode('/',$dir);
           $dir = "{$_SERVER['DOCUMENT_ROOT']}{$dir}/temp_dir/";
           $zip->extractTo($dir);
           $zip->close();
           return $dir;
       }
       /**
        * 
        * @param type $puth_file
        * Функция выодит файл для просмотра
        */
       public function get_file_show($puth_file){
           //получаю тип файла
           $type = $this->get_type($puth_file);
           //получаю полный путь к файлу 
           $puth = realpath ($puth_file);
           $mas_video_type = ['mp4','avi','mkv','3gp','wmv','mov','flv','swf','aac'];
           if(@exif_imagetype($puth_file)){
               return "<div class='text'><p><i class='fa fa-window-close-o' id = 'closed' aria-hidden='true'></i></p><div class='panel_instrum'><i class='rotate fa fa-undo' id='left' aria-hidden='true' title='развернуть влево на 90°'></i><i class='rotate fa fa-repeat' aria-hidden='true' id='right' title='развернуть вправо на 90°'></i><i class='fa fa-exchange' aria-hidden='true' id='exchange' title='Изменить размер изображения'></i></div><img src='{$puth_file}' puth='$puth' style='max-width:100%;'></div>";          
           }else if(in_array($type, $mas_video_type)){
               return "<div class='text'><p><i class='fa fa-window-close-o' id = 'closed' aria-hidden='true'></i></p><video width='400' height='300' controls='controls'>
                    <source src='{$puth}' type='video/{$type}'>Тег video не поддерживается вашим браузером.<a href='video/{$puth}'>Скачайте видео</a></video></div>";
           }else if($type == 'zip' || $type == 'rar'){
                   $new_puth = $this->prepare_puth($puth_file);
                   $dir = openZip($puth_file);
                   new_dir_open($dir);
           }else{
               $text = file_get_contents($puth_file);
               return "<div class='text'><p><i class='fa fa-window-close-o' id = 'closed' aria-hidden='true'></p></i><textarea name='ckeditor' file = '{$puth_file}'>$text</textarea></div>";
           }
       }
       /**
        * 
        * @param type $puth
        * @param type $save
        * @param type $puth_file
        * функция сохранения файла
        */
       function save_tex($puth,$save,$puth_file){
           $text = file_put_contents ($puth,$save);
           $text = file_get_contents ($puth);
           return "<div class='text'><p><i class='fa fa-window-close-o' id = 'closed' aria-hidden='true'></p></i><textarea name='ckeditor' file = '{$puth_file}'>$text</textarea></div>";
       }
       
       /**
        * 
        * @param type $mas
        * @param type $name
        * @return type
        */
       public function fileRemove($mas,$name){
            foreach($mas as $val){
                if($val != ''){
                    if(@unlink($val)){
                       return "Файл {$name} удален";
                    }else{
                        $this->delFolder($val);
                    }
                }
            }
       }
       /**
        * 
        * @param type $new_name
        * @param type $puth
        * переименование файла
        */
       function renameFileOrDir($new_name,$puth){
           $new_puth = explode('/',$puth);
           array_pop($new_puth);
           $new_puth = implode('/',$new_puth);
           $new_puth = "{$new_puth}/{$new_name}";
           if(rename($puth,$new_puth)){
               return "Файл переименован!";
           }
       }
       /**
        * 
        * @param type $puth
        * @param type $name
        * @param type $type
        * @return string
        */
       function createDirOrFile($puth,$name,$type){
           $dir_new = $this->prepare_puth($puth);
           $dir_new = "{$dir_new}/{$name}";
           $exit = '';
           if($type == 'folder'){          
               if(is_dir($dir_new)){
                   $exit = "Такая папка существует!";
               }else{
                   mkdir($dir_new);
                  $exit = 'Папка создана!';
               }
           }else{
               if(!file_exists($dir_new)){
                   $fp =  fopen($dir_new, "w");
                   fclose($fp);
                   $exit = 'Файл создана!';
               }else{
                   $exit = 'Такой файл уже существует!';
               }
           }
           return $exit;
       }
        /**
        * 
        * @param type $puth
        * Функция открывает папку и показывает файлы
        */
       public function new_dir_open($puth){
            $type = $this->get_type("$puth");
            $name = basename($puth);
            $size = $this->get_size($puth);
            $date = $this->get_spesial_date($puth);
            $date_ch = $date['data_ch'];
            $mas_video_type = ['mp4','avi','mkv','3gp','wmv','mov','flv','swf','aac'];
            if(is_dir("$puth")){
                $type = 'папка';
                return "<li class='toggle el' puth='$puth' title='$name\nразмер: $size кб.\nТип \"$type\"\nДата изменения $date_ch'><input type='checkbox'><img src='img/folder.png'><br><span>$name</span></li>";
            }else{
                 if(@exif_imagetype("$puth")){
                    return "<li class='file el' puth='$puth' title='$name\nразмер: $size кб.\nТип \"$type\"\nДата изменения $date_ch'><div class='img'><img src='$puth'></div><a href='$puth'><span>$name</span></a></li>";
                 }else if($type == 'zip'){
                     return "<li class='file el' puth='$puth' title='$name\nразмер: $size кб.\nТип \"$type\"\nДата изменения $date_ch'><img src='img/zip.png'><br><a href='$puth'><span>$name</span></a></li>";
                 }else if(in_array($type, $mas_video_type)){
                     return "<li class='file el' puth='$puth' title='$name\nразмер: $size кб.\nТип \"$type\"\nДата изменения $date_ch'><img src='img/video.png'><br><a href='$puth'><span>$name</span></a></li>";
                 }else{
                    return "<li class='file el' puth='$puth' title='$name\nразмер: $size кб.\nТип \"$type\"\nДата изменения $date_ch'><img src='img/file.png'><br><a href='$puth'><span>$name</span></a></li>";
                 }
            }
        }
        public function open_dir($dir,$puth=''){
             foreach($dir as $val){
                 if($val != '..' && $val != '.'){
                     $this->new_dir_open("$puth/$val");
                 }
             }
         }
         /**
        * 
        * @param type $find
        * @param type $puth
        * Поиск файлов
        */
        public function find_file($find,$puth = '../test/'){
            $dir = scandir($puth);
            foreach($dir as $key=>$val){
                if($val != '.' && $val != '..'){
                    if(stristr($val,$find)){
                        $this->new_dir_open("$puth/$val");
                    }else{
                        if(is_dir("$puth/$val")){
                            $this->find_file($find,"$puth/$val");
                        }
                    }
                }
            }    
        }
        /**
        * 
        * @param type $uploaddir
        * @param type $files
        * Загрузка файлов
        */
        public function  uploadFiles($uploaddir,$files){
           $exit = '';
           for($i = 0; $i < count($files); $i++){
               $name = $this->str2url(basename($files[$i]['name']));
               $uploadfile = "{$uploaddir}/$name";
               if(file_exists($uploadfile)){
                   $uploadfile = "{$uploaddir}/".time()."_$name";
                   $name = "".time()."_$name";
               }
               if (move_uploaded_file($files[$i]['tmp_name'], $uploadfile)) {
                   $exit = "Файл корректен и был успешно загружен.\n, так же файл был переименован в $name так как файл с таим именем уже существует!";
               } else {
                   $exit = "Возможная атака с помощью файловой загрузки!\n";
               }
           }
           return $exit;
       }
       
        public function createMas($sort,$puth){
            $puth = $this->prepare_puth($puth);
            $dir = scandir($puth);
            $i = 0;
            foreach ($dir as $key => $val){
                if($val != '.' && $val != '..'){
                    $data = $this->get_date("$puth/$val");
                    $type = $this->get_type("$puth/$val");
                    if(!$type){
                        $type = 'folder';
                    }
                    $ran = mt_rand(0,200);
                    $name = $val;
                    $puth_file = "$puth/$val";
                    $date_create = $data['create'];
                    $data_ch = $data['data_ch'];
                    $size = $this->get_size($puth_file);
                    $mas[] = [
                        'name' => $name,
                        'data_create' => $date_create,
                        'data_ch' => $data_ch,
                        'puth' => $puth_file,
                        'type' => $type,
                        'size' => $size
                    ];
                    $i++;
                }
            }
            $back = explode('/',$puth);
            array_pop($back);
            $back = implode('/', $back);
            if(!empty($back)){
                return "<li class='toggle el back' puth='$back' puth_old = '$puth' title='Назад'><input type='checkbox'><img src='img/back.png'><span title='назад'></span></li>";
            }
            if($sort == 'asc'){
                $new_mas = $this->sort_mass($mas,'name');   
            }else if($sort == 'desc'){
                $new_mas = $this->sort_mass($mas,'name',1);  
            }else if($sort == 'dateAsc'){
                $new_mas = $this->sort_mass($mas,'data_create'); 
            }else if($sort == 'dateDEsc'){
                $new_mas = $this->sort_mass($mas,'data_ch'); 
            }else if($sort == 'size'){
                $new_mas = $this->sort_mass($mas,'size'); 
            }
            foreach($new_mas as $val){
                echo new_dir_open($val['puth']); 
            }
        }
       
        public function createPrava($dir,$prava){
            $prava = (integer) $prava;
            $exit = '';
            if(chmod($dir, $prava)){
                $exit = 'Права изменены';
            }else{
                 $exit = 'Ошибка';
            }
            return  $exit;
        }
        
        public function watch_dost($dir){
            $mas_attr = [];
            $mas_attr = $this->getAttributes($dir);
            echo"<div class='text'><p><i class='fa fa-window-close-o' id = 'closed_table' aria-hidden='true'></p>"
                . "<table class='properties' align='center'><thead><tr><td colspan='2'  align='center'>Свойства</td></tr>"
                . "<tr><td align='center'>Название</td><td align='center'>Описание</td></tr></thead><tbody>";
            foreach ($mas_attr as $key=>$val){
                    echo"<tr><td>{$key}</td><td>$val</td></tr>";        
            }
            echo "</tbody></table></div>";
        }
        
        /**
        * 
        * @param type $img
        * @param type $name
        * @param type $size
        * @param int $new
        * @return string
        */
        function resizeImg($img,$name,$size,$new = 0){
            $exit = '';
            require ('resize/imgresize.php');
            $puth = explode('/',$img);
            for($i = 0; $i < count($puth)-1; $i++){
                $puth_new .= "$puth[$i]/";
            }
            if($new = 1){
                $new_name = "min_".$name."";
                $puth_new .= $new_name;
            }else{
                 $new_name = $name;
                 $puth_new .= $new_name;
            }        
            list($width, $height, $type, $attr) = $this->getimagesize($img);
            if($width > $height){
                $dif = $width / $size; $width = $size; $height = $height / $dif;
            }
            else{
                $dif = $height / $size; $height = $size; $width = $width / $dif;
            }
            if (img_resize($img, $puth_new, $width,$height))
              $exit = 'Image resized OK';
            else
              $exit = 'Resize failed!';

          return $exit;
        }
        
        
        function RotateImg($rotate,$rotate_type,$image_puth,$new_image){
            $type = $this->get_type($image_puth);
            if($new_image == 1){
                $new_puth  = $this->prepare_puth($image_puth);
                $new_name = explode('/',$image_puth);
                $new_name = array_pop($new_name);
                $data = time();
                $new_image = "$new_puth/new_$data.$new_name";
                $type = $this->get_type($new_image);
            }
            if($rotate_type == 'left'){
                $rotate = "-$rotate";
            }
            if($type == 'jpeg' || $type == 'jpg'){
                $type = 'jpeg';
            }
            $name_func1 = "imagecreatefrom".$type;
            $name_func2 = "image".$type;
            $img = $name_func1($image_puth);    // Картинка                        
            $imgRotated = imagerotate($img, $rotate, 0);
            unlink($image_puth);
            $name_func2($imgRotated, $new_image, 9);
            imagedestroy($imgRotated);
            echo "<div class='text'><p><i class='fa fa-window-close-o' id = 'closed' aria-hidden='true'></i></p>"
            . "<div class='panel_instrum'><i class='rotate fa fa-undo' rotate_type = 'left' aria-hidden='true' title='развернуть влево на 90°'></i>"
            . "<i class='rotate fa fa-repeat' aria-hidden='true' rotate_type = 'right' title='развернуть вправо на 90°'></i>"
            . "<i class='fa fa-exchange' aria-hidden='true' id='exchange' title='Изменить размер изображения'></i></div>"
            . "<img src='{$new_image}' style='max-width:100%;'></div>";
        }
        
        public function createArhiv($src_dir){
            $new_folder = explode('/',$src_dir);
            $name = array_pop($new_folder);
            $new_folder = implode('/',$new_folder);
            $archive_dir = $new_folder;
            $zip = new ZipArchive();
            $fileName = "$archive_dir/$name.zip";
            if ($zip->open($fileName, ZIPARCHIVE::CREATE) !== true) {
                return('Неудалось создать архив');
            }
            if(is_dir($src_dir)){
                $this->add_to_arhive($src_dir,$zip);
            }else{
               if(!$zip->addFile($src_dir, $src_dir)){
                    return('Неудалось записать файлы в архив');
                } 
            }
            $zip->close();
        }
    }
?>