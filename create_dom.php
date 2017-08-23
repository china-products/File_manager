<?php
/**
 * 
 * @param type $puth_file
 * @return тип файла в нижнем регистре
 */
    function get_type($puth_file){
        $info = new SplFileInfo($puth_file);
        return strtolower($info->getExtension());
    }
    /**
     * 
     * @param type $mas
     * Вспомогательная функция
     */
     function prin($mas){
        echo'<pre>';
        print_r($mas);
    }
    
     /**
     * 
     * @param type $new_puth string
     * @return готовый путь к папке где лежит файл
     */
    function prepare_puth($new_puth){
        if(is_dir($new_puth)){
            $puth = $new_puth;
        }else{
            $new_puth = explode('/',$new_puth);
            array_pop($new_puth);
            $puth = implode('/',$new_puth);
        }
        return $puth;
    }
    
    /**
     * 
     * @param type $puth_file
     * @return путь к временной папке архива
     */
    function  openZip($puth_file){
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
    function get_file_show($puth_file){
        //$puth_file = iconv('CP1251', 'UTF-8', $puth_file);
        //получаю тип файла
        $type = get_type($puth_file);
        //получаю полный путь к файлу 
        $puth = realpath ($puth_file);
        
        $mas_video_type = ['mp4','avi','mkv','3gp','wmv','mov','flv','swf','aac'];
        if(@exif_imagetype($puth_file)){
            echo "<div class='text'><p><i class='fa fa-window-close-o' id = 'closed' aria-hidden='true'></i></p><div class='panel_instrum'><i class='rotate fa fa-undo' id='left' aria-hidden='true' title='развернуть влево на 90°'></i><i class='rotate fa fa-repeat' aria-hidden='true' id='right' title='развернуть вправо на 90°'></i><i class='fa fa-exchange' aria-hidden='true' id='exchange' title='Изменить размер изображения'></i></div><img src='{$puth_file}' puth='$puth' style='max-width:100%;'></div>";          
        }else if(in_array($type, $mas_video_type)){
            echo "<div class='text'><p><i class='fa fa-window-close-o' id = 'closed' aria-hidden='true'></i></p><video width='400' height='300' controls='controls'>
                 <source src='{$puth}' type='video/{$type}'>Тег video не поддерживается вашим браузером.<a href='video/{$puth}'>Скачайте видео</a></video></div>";
        }else if($type == 'zip' || $type == 'rar'){
                $new_puth = prepare_puth($puth_file);
                $dir = openZip($puth_file);
                new_dir_open($dir);
        }else{
            $text = file_get_contents ("$puth_file/");
            echo "<div class='text'><p><i class='fa fa-window-close-o' id = 'closed' aria-hidden='true'></p></i><textarea name='ckeditor' file = '{$puth_file}'>$text</textarea></div>";
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
        echo "<div class='text'><p><i class='fa fa-window-close-o' id = 'closed' aria-hidden='true'></p></i><textarea name='ckeditor' file = '{$puth_file}'>$text</textarea></div>";
    }
    
    if($_POST['save']){
        save_tex($_POST['puth_file_save'],$_POST['save'],$_POST['puth_file']);   
    }
    
    //Рекурсивное удаление папки и файлов в ней
    function delFolder($dir){
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delFolder("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
    
    //Удаление файла или папки
    if($_POST['remove']){
        $file_remove = $_POST['remove'];
        foreach($file_remove as $val){
            if($val != ''){
                if(@unlink($val)){
                   echo "Файл {$_POST['name']} удален";
                }else{
                   delFolder($val);
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
            echo "Файл переименован!";
        }
    }
    
   //Переименование файла или папки
    if($_POST['rename']){
        renameFileOrDir($_POST['rename'],$_POST['old_puth']);
    }
    
    /**
     * 
     * @param type $puth
     * @param type $name
     * @param type $type
     * @return string
     */
    function createDirOrFile($puth,$name,$type){
        $dir_new = prepare_puth($puth);
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
    
   //Создание файла или папки
    if($_POST['puth_file']){
       echo createDirOrFile($_POST['puth_file'],$_POST['name'],$_POST['type']);
    }  
    /**
     * 
     * @param type $puth
     * Функция открывает папку и показывает файлы
     */
    function new_dir_open($puth){
        $type = get_type("$puth");
        $name = basename($puth);
        $name = mb_convert_encoding($name, 'utf-8', 'cp1251');
        $size = get_size($puth);
        $date = get_spesial_date($puth);
        $date_ch = $date['data_ch'];
        $mas_video_type = ['mp4','avi','mkv','3gp','wmv','mov','flv','swf','aac'];
        if(is_dir("$puth")){
            $type = 'папка';
            echo "<li class='toggle el' puth='$puth' title='$name\nразмер: $size кб.\nТип \"$type\"\nДата изменения $date_ch'><input type='checkbox'><img src='img/folder.png'><br><span>$name</span></li>";
        }else{
             if(@exif_imagetype("$puth")){
                echo "<li class='file el' puth='$puth' title='$name\nразмер: $size кб.\nТип \"$type\"\nДата изменения $date_ch'><div class='img'><img src='$puth'></div><a href='$puth'><span>$name</span></a></li>";
             }else if($type == 'zip'){
                 echo "<li class='file el' puth='$puth' title='$name\nразмер: $size кб.\nТип \"$type\"\nДата изменения $date_ch'><img src='img/zip.png'><br><a href='$puth'><span>$name</span></a></li>";
             }else if(in_array($type, $mas_video_type)){
                 echo "<li class='file el' puth='$puth' title='$name\nразмер: $size кб.\nТип \"$type\"\nДата изменения $date_ch'><img src='img/video.png'><br><a href='$puth'><span>$name</span></a></li>";
             }else{
                echo "<li class='file el' puth='$puth' title='$name\nразмер: $size кб.\nТип \"$type\"\nДата изменения $date_ch'><img src='img/file.png'><br><a href='$puth'><span>$name</span></a></li>";
             }
        }
    }
    
    function open_dir($dir,$puth=''){
        foreach($dir as $val){
            if($val != '..' && $val != '.'){
              new_dir_open("$puth/$val");
            }
        }
    }

   /**
    * 
    * @param type $puth
    * @return type
    * Функция получения специальной даты
    */ 
function get_spesial_date($puth){
    $last_change = date("F d.Y г. H:i:s", filectime($puth));
        $stat=stat($puth);
        $data_mod = date("F d.Y г. H:i:s", $stat[9]);
        $mas_mounth=['January'=>'Январь','February'=>'Февраль','March'=>'Март','April'=>'Апрель','May'=>'Май','June'=>'Июнь',
            'July'=>'Июль','August'=>'Август','September'=>'Сентябрь','October'=>'Октябрь','November'=>'Ноябрь','December'=>'Декабрь'
        ];
        
        $data_mod = (string) $data_mod;
        $last_change = (string) $last_change;
        foreach ($mas_mounth as $k=>$v){
            if(stristr($data_mod,$k)){
               $data_mod = str_replace($k, $v, $data_mod);
            }
            if(stristr($last_change,$k)){
               $last_change = str_replace($k, $v, $last_change);
            }
        }
    return $mas=[
        'create' =>   $data_mod,
        'data_ch' => $last_change
    ];
}
/**
 * 
 * @param type $puth
 * @return type
 * Получение даты в unix формате
 */
function get_date($puth){
    $mas_date = [];
    $last_change = filectime($puth);
    $stat=stat($puth);
    $data_mod = $stat[9];
    return $mas_date =[
      'create' =>   $data_mod,
      'data_ch' => $last_change
    ];
}
/**
 * 
 * @param type $find
 * @param type $puth
 * Поиск файлов
 */
function find_file($find,$puth = '../test/'){
    $dir = scandir($puth);
    foreach($dir as $key=>$val){
        if($val != '.' && $val != '..'){
            if(stristr($val,$find)){
                new_dir_open("$puth/$val");
            }else{
                if(is_dir("$puth/$val")){
                    find_file($find,"$puth/$val");
                }
            }
        }
    }    
}

if($_POST['find']){
    find_file($_POST['find']);
}
/**
 * 
 * @param type $new_mas2
 * @param type $fild
 * @param type $revers
 * @return type
 * Сортировка файлов
 */
function sort_mass($new_mas2,$fild,$revers=0){
    for($i = 0; $i < count($new_mas2)-1; $i++){
        $min = $i;
        for($a = $i + 1; $a < count($new_mas2); $a++){
            if ($new_mas2[$a][$fild] < $new_mas2[$min][$fild]){
                $min = $a;
            }
        }
        $dummy = $new_mas2[$i];
        $new_mas2[$i] = $new_mas2[$min];
        $new_mas2[$min] = $dummy;
    }
    foreach($new_mas2 as $k=>$v){
        $key = array_search($v,$new_mas2);
        if($k != $key){
            unset($new_mas2[$k]);
        }
    }
    if($revers == 1){
        $new_mas2 = array_reverse($new_mas2);
    }
    return $new_mas2;
}

/**
 * 
 * @param type $string
 * @return type
 * Для создание имен файлов в транслите лажа какаето с utf-8
 */
// Транслитерация строк.
function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}
function str2url($str) {
    // переводим в транслит
    $str = rus2translit($str);
    // в нижний регистр
    $str = strtolower($str);
    // заменям все ненужное нам на "-"
    $str = preg_replace('~[^-a-z0-9_]+~u', '.', $str);
    // удаляем начальные и конечные '-'
    $str = trim($str, "-");
    return $str;
}
/**
 * 
 * @param type $uploaddir
 * @param type $files
 * Загрузка файлов
 */
function  uploadFiles($uploaddir,$files){
    $exit = '';
    for($i = 0; $i < count($files); $i++){
        $name = str2url(basename($files[$i]['name']));
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
if($_FILES){
    echo uploadFiles($_POST['puth_to_file'],$_FILES);
}


/**
 * 
 * @param type $dir
 * @return type
 * получаю отформатированый размер файла
 */
function get_size($dir){
    $size =  filesize($dir);
    $size = $size / 1024;
    return round($size,2);
}

/**
 * Сначала собираю массив файлов потом его сортирую и вывожу
 */
if($_POST['sort']){
    $puth =  prepare_puth($_POST['puth_sort']);
    $dir = scandir($puth);
    $sort = $_POST['sort'];
    $i = 0;
    foreach ($dir as $key => $val){
        if($val != '.' && $val != '..'){
            $data = get_date("$puth/$val");
            $type = get_type("$puth/$val");
            if(!$type){
                $type = 'folder';
            }
            $ran = mt_rand(0,200);
            $name = $val;
            $puth_file = "$puth/$val";
            $date_create = $data['create'];
            $data_ch = $data['data_ch'];
            $size = get_size($puth_file);
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
        echo "<li class='toggle el back' puth='$back' puth_old = '$puth' title='Назад'><input type='checkbox'><img src='img/back.png'><span title='назад'></span></li>";
    }
    if($sort == 'asc'){
        $new_mas = sort_mass($mas,'name');   
    }else if($sort == 'desc'){
        $new_mas = sort_mass($mas,'name',1);  
    }else if($sort == 'dateAsc'){
        $new_mas = sort_mass($mas,'data_create'); 
    }else if($sort == 'dateDEsc'){
         $new_mas = sort_mass($mas,'data_ch'); 
    }else if($sort == 'size'){
          $new_mas = sort_mass($mas,'size'); 
    }
    foreach($new_mas as $val){
       new_dir_open($val['puth']); 
    }
}

/**
 * 
 * @param type $puth
 * функция брожения по папкам с выводом кнопки назад
 */
    function open_show_dir($puth){
        $open = scandir($puth);
        $back = explode('/',$puth);
        array_pop($back);
        $back = implode('/', $back);
        if(count($open) > 2){
            if(!empty($back)  && $back != '..' && $back != '.'){
                echo "<li class='toggle el back' puth='$back' puth_old = '$puth' title='Назад'><input type='checkbox'><img src='img/back.png'><span title='назад'></span></li>";
            }
            open_dir($open,$puth);
        }else{
            if(!empty($back) && $back != '..' && $back != '.'){
                echo "<li class='toggle el back' puth='$back' puth_old = '$puth' title='Назад'><input type='checkbox'><img src='img/back.png'><span title='назад'></span></li>";
            }
            echo'Файлов и папок нет!';
        }
        echo'<div class="clear"></div>';
    }

    
    if($_POST['open_dir']){
        $puth = $_POST['open_dir'];
        if(is_dir($puth)){
            open_show_dir($puth);
        }else{
            get_file_show($puth);;
        }
    }
    function createPrava($dir,$prava){
        $prava = (integer) $prava;
        $exit = '';
        if(chmod($dir, $prava)){
            $exit = 'Права изменены';
        }else{
             $exit = 'Ошибка';
        }
        return  $exit;
    }
    
    if($_POST['dir_prava']){
        echo createPrava($_POST['dir_prava'],$_POST['prava_chek']);
    }
    
    /**
     * 
     * @param type $dir
     * @return type
     * Функция сбора аттрибутов
     */
    function  getAttributes($dir){
        $mas_attr = [];
        $prava = substr(sprintf('%o', fileperms($dir)), -4);
        $size = get_size($dir)." кб.";
        $puth = realpath ($dir);
        $name = basename($puth);
        $date = get_spesial_date($puth);
        $data_mod = $date['create'];
        $last_change = $date['data_ch'];
        $type = get_type($puth);
        if(@exif_imagetype($dir)){
           $val = getimagesize($dir);
           $type = $val['mime'];
           $height = "{$val[1]} px.";
           $width = "{$val[0]} px.";
           $mas_attr=[
            'Название:'=>$name,
            'Путь к файлу:'=>$puth,
            'Ширина:'=>$width,
            'Высота:'=>$height,
            'Тип:'=>$type,
            'Права:'=>$prava,
            'Размер файла:'=>$size,
            'Дата создания файла:'=>$data_mod,
            'Последнее изменение:'=>$last_change,
            ];
        } else {
            if(empty($type)){
               $type = 'Папка';
           }
            $mas_attr=[
            'Название:'=>$name,
            'Путь к файлу:'=>$puth,
            'Тип:'=>$type,
            'Права:'=>$prava,
            'Размер файла:'=>$size,
            'Дата создания файла:'=>$data_mod,
            'Последнее изменение:'=>$last_change,
            ];
        }
        return $mas_attr;
    }


    if($_POST['watch_dost']){
        $mas_attr = [];
        $dir = $_POST['watch_dost'];
        $mas_attr = getAttributes($dir);
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
        $exit = [];
        require ('resize/imgresize.php');
        $puth_new = prepare_puth($img)."/";
        if($new == 1){
            $new_name = "min_".$name."";
            $puth_new .= $new_name;
        }else{
            $puth_new .= $name;
        }     
        list($width, $height, $type, $attr) = getimagesize($img);
        if($width > $height){
            $dif = $width / $size; 
            $width = $size; $height = $height / $dif;
        }
        else{
            $dif = $height / $size; 
            $height = $size; $width = $width / $dif;
        }
        if (img_resize($img, $puth_new, $width,$height)){
          $exit =['puth' => $puth_new];
        }else{
          $exit = ['error' =>'Размер не изменен ошибка!'];
        }
      return $exit;
    }
    
    if($_POST['resize']){
        $exit = resizeImg($_POST['puth_img'],$_POST['img'],$_POST['width'],$_POST['new_img']);
        if(isset($exit['puth'])){
            new_dir_open($exit['puth']);
        }else{
            echo $exit['error'];
        }
    }

    function RotateImg($rotate,$rotate_type,$image_puth,$new_image){
        $type = get_type($image_puth);
        if($new_image == 1){
            $new_puth  = prepare_puth($image_puth);
            $new_name = explode('/',$image_puth);
            $new_name = array_pop($new_name);
            $data = time();
            $new_image = "$new_puth/new_$data.$new_name";
            $type = get_type($new_image);
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
    
if($_POST['rotate']){    
    RotateImg($_POST['rotate'],$_POST['type_rot'],$_POST['img_puth'],$_POST['new_img_rot']);
}   

if(isset($_POST['arhiv'])){
    $src_dir = $_POST['arhiv'];
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
        add_to_arhive($src_dir,$zip);
    }else{
       if(!$zip->addFile($src_dir, $src_dir)){
            return('Неудалось записать файлы в архив');
        } 
    }
    $zip->close();
}
    function add_to_arhive($dir,$zip){
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? add_to_arhive("$dir/$file",$zip) : $zip->addFile("$dir/$file","$dir/$file");
        }
    }
    function remove($oldPuth,$newPuth){
        $newPuth = prepare_puth($newPuth);
        $old = explode('/',$oldPuth);
        $newPuth = "$newPuth/".end($old)."";
        if(@rename($oldPuth,$newPuth)){
            echo "Файл перемещен!";
        }else{
            echo "Файл не перемещен!";
        }
    }
    if($_POST['new_puth'] && $_POST['old']){
        echo remove($_POST['old'],$_POST['new_puth']);
    }
    
?>
