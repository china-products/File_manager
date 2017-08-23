<?php
    include('controler/help_func.php');
    include('controler/controler_file.php');
    include('controler/tree.php');
    include ('view/files.php');    
    $file = new fileWork();
    if($_POST['save']){
        echo $file->save_tex($_POST['puth_file_save'],$_POST['save'],$_POST['puth_file']);   
    }
    if($_POST['remove']){
        echo $fil->fileRemove($_POST['remove'],$_POST['name']);
    }
    //Переименование файла или папки
    if($_POST['rename']){
        echo $file->renameFileOrDir($_POST['rename'],$_POST['old_puth']);
    }
    //Создание файла или папки
    if($_POST['puth_file']){
       echo $file->createDirOrFile($_POST['puth_file'],$_POST['name'],$_POST['type']);
    }
    if($_POST['find']){
        echo $file->find_file($_POST['find']);
    }
    if($_FILES){
        echo $file->uploadFiles($_POST['puth_to_file'],$_FILES);
    }
    if($_POST['sort']){
        echo $file->createMas($_POST['sort'], $_POST['puth_sort']);
    }
    if($_POST['open_dir']){
        $puth = $_POST['open_dir'];
        if(is_dir($puth)){
           echo $file->open_show_dir($puth);
        }else{
           echo $file->get_file_show($puth);;
        }
    }
    if($_POST['dir_prava']){
        echo $file->createPrava($_POST['dir_prava'],$_POST['prava_chek']);
    }
    if($_POST['watch_dost']){
       echo $file->watch_dost($_POST['watch_dost']);
    }
    if($_POST['resize']){
       echo $file->resizeImg($_POST['puth_img'],$_POST['img'],$_POST['width'],$_POST['new_img']);
    }
    if($_POST['rotate']){    
       echo $file->RotateImg($_POST['rotate'],$_POST['type_rot'],$_POST['img_puth'],$_POST['new_img_rot']);
    } 
    if(isset($_POST['arhiv'])){
       echo $file->createArhiv($_POST['arhiv']);
    }
    if($_POST['Refresh']){
        $path = "../test/";
        createDir($path);
    }
?>