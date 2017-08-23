<?php
    class help{
        /**
        * 
        * @param type $puth_file
        * @return тип файла в нижнем регистре
        */
        public function get_type($puth_file){
            $info = new SplFileInfo($puth_file);
            return strtolower($info->getExtension());
        }
        /**
        * 
        * @param type $mas
        * Вспомогательная функция
        */
        public function prin($mas){
           echo'<pre>';
           print_r($mas);
       }
       /**
        * 
        * @param type $new_puth string
        * @return готовый путь к папке где лежит файл
        */
       public function prepare_puth($new_puth){
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
        * @param type $puth
        * @return type
        * Функция получения специальной даты
        */ 
       public function get_spesial_date($puth){
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
        public function get_date($puth){
           $mas_date = [];
           $last_change = filectime($puth);
           $stat = stat($puth);
           $data_mod = $stat[9];
           return $mas_date =[
             'create' =>   $data_mod,
             'data_ch' => $last_change
           ];
       }
       
       /**
        * 
        * @param type $string
        * @return type
        * Для создание имен файлов в транслите лажа какаето с utf-8
        */
       // Транслитерация строк.
       private function rus2translit($string) {
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
       public function str2url($str) {
           // переводим в транслит
           $str = $this->rus2translit($str);
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
        * @param type $dir
        * @return type
        * получаю отформатированый размер файла
        */
       public function get_size($dir){
           $size =  filesize($dir);
           $size = $size / 1024;
           return round($size,2);
       }
       
       //Рекурсивное удаление папки и файлов в ней
       public function delFolder($dir){
            $files = array_diff(scandir($dir), array('.','..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? $this->delFolder("$dir/$file") : unlink("$dir/$file");
            }
            return rmdir($dir);
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
        * @param type $puth
        * функция брожения по папкам с выводом кнопки назад
        */
        public function open_show_dir($puth){
            $open = scandir($puth);
            $back = explode('/',$puth);
            array_pop($back);
            $back = implode('/', $back);
            if(count($open) > 2){
                if(!empty($back)  && $back != '..' && $back != '.'){
                    return "<li class='toggle el back' puth='$back' puth_old = '$puth' title='Назад'><input type='checkbox'><img src='img/back.png'><span title='назад'></span></li>";
                }
                open_dir($open,$puth);
            }else{
                if(!empty($back) && $back != '..' && $back != '.'){
                    return "<li class='toggle el back' puth='$back' puth_old = '$puth' title='Назад'><input type='checkbox'><img src='img/back.png'><span title='назад'></span></li>";
                }
                return'Файлов и папок нет!';
            }
            return'<div class="clear"></div>';
        }
        
        /**
        * 
        * @param type $dir
        * @return type
        * Функция сбора аттрибутов
        */
       public function  getAttributes($dir){
           $mas_attr = [];
           $prava = substr(sprintf('%o', fileperms($dir)), -4);
           $size = $this->get_size($dir)." кб.";
           $puth = realpath ($dir);
           $name = basename($puth);
           $date = $this->get_spesial_date($puth);
           $data_mod = $date['create'];
           $last_change = $date['data_ch'];
           $type = $this->get_type($puth);
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
       
       function add_to_arhive($dir,$zip){
            $files = array_diff(scandir($dir), array('.','..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? $this->add_to_arhive("$dir/$file",$zip) : $zip->addFile("$dir/$file","$dir/$file");
            }
       }
    }
?>