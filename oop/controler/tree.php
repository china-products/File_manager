<?php
function createDir($path = '.'){	
    if ($handle = opendir($path)){
        echo '<ol class="tree">';
        while (false !== ($file = readdir($handle))){
            if (is_dir($path.$file) && $file != '.' && $file !='..')
                printSubDir($file, $path, $queue);
            else if ($file != '.' && $file !='..')
                $queue[] = $file;
        }
        printQueue($queue, $path);
        echo "</ol>";
    }
}

function printQueue($queue, $path){       
    if($queue){
        foreach ($queue as $file){
            printFile($file, $path);
        } 
    }
}

function printFile($file, $path){
    echo "<li class=\"file el\" puth='".$path.$file."'><a href=\"".$path.$file."\"><span>$file</span></a></li>";
}

function printSubDir($dir, $path){
    echo "<li class=\"toggle el\" puth=\"$path$dir\" name='$dir~'><span>$dir</span><input type=\"checkbox\">";
    createDir($path.$dir."/");
    echo "</li>";
}

if($_POST['Refresh']){
    $path = "../test/";
    createDir($path);
}
	
?>
