<html>
    <body>
        <header>
            <meta>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
            <title></title>
            <script src="http://yandex.st/jquery/2.0.2/jquery.min.js"></script>	
            <link rel="stylesheet" href="css/style.css">
            <link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="js/jquery-ui-1.12.1.custom/jquery-ui.min.css" type="text/css" />
            <script type="text/javascript" src="js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
            <script src="js/ckeditor/ckeditor.js"></script>
            <script src="js/cellSelection.min.js"></script>
            <script src="js/function.js"></script>
        </header>
        <div class="window">
            <div class="modal">
                <div class="closed">
                    <i class="fa fa-window-close-o" aria-hidden="true"></i>
                </div>
                <div class="modal_content"></div>
            </div>
            <div class="tools">
                <ul>
                    <li id="create_file" title="Новый Файл">
                        <i class="fa fa-file-o" aria-hidden="true"></i><br> 
                        
                    </li>
                    <li id="create_dir" title="Новая папка">
                        <i class="fa fa-folder-o" aria-hidden="true"></i><br>
                       
                    </li>
                    <li id="upload" title="Загрузить файлы" style="overflow: hidden;">
                       <i class="fa fa-upload" aria-hidden="true"></i><br>
                       <form enctype="multipart/form-data" style="overflow: hidden;margin-bottom: -6px;">
                           <input type="file" name="user_file" multiple style="width: 27px;height: 30px;">
                       </form>
                    </li>
                    <li id="arhiv" title="Заархивировать">
                         <i class="fa fa-file-archive-o" aria-hidden="true"></i><br>
                    </li>
                    <li id="drop" title="Удалить">
                        <i class="fa fa-times" aria-hidden="true"></i><br>
                        
                    </li>
                    <li id="rename" title="Переименовать">
                        <i class="fa fa-font" aria-hidden="true"></i><br>
                        
                    </li>
                    <li class="save" title="Сохранить">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i><br>
                        
                    </li>
                    <li id="refresh" title="Обновить каталог">
                         <i class="fa fa-refresh" aria-hidden="true"></i><br>
                         
                    </li>
                    <li id="move" title="переместить">
                         <i class="fa fa-files-o" aria-hidden="true"></i><br>
                         
                    </li>
                    <li id="prava" title="Установить права">
                         <i class="fa fa-arrows-alt" aria-hidden="true"></i><br>
                         
                    </li>
                    <li id="watch_dost" title="Свойства">
                         <i class="fa fa-eye" aria-hidden="true"></i><br>
                         
                    </li>
                    <li id="sort_file" title="Сортировать">
                         <i class="fa fa-sort" aria-hidden="true"></i>
                         <select name="sort" style="display:none;">
                             <option value="asc">А-Я</option>
                             <option value="desc">Я-А</option>
                             <option value="dateAsc">Дата создания</option>
                             <option value="dateDEsc">Дата изменения</option>
                             <option value="size">Размер</option>
                         </select>
                    </li>
                    <li id="find" title="поиск">
                         <i class="fa fa-search" aria-hidden="true"></i>
                             <input type="find"  multiple="multiple" name="find" placeholder="Найти" style="position:relative; opacity:1; height:20px;">
                         <button>Найти</button>
                    </li>
                </ul>
            </div>
            <div class="main">
                <div class="dom">
                    <?php 
                        $path = "../test/";
                        createDir($path);
                    ?>
                </div>
                <div class="field">
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </body>
</html>