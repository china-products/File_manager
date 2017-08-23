        
            //Функция сохранения файлов
            var file_extend = 'index.php';  
            function save(){
                    var save = document.getElementsByTagName("iframe")[0].contentDocument.getElementsByTagName("body")[0].innerHTML;
                    if(save == ''){
                        save = '';
                    }
                    var puth = $('.field textarea').attr('file');
                    $.ajax({
                        url:  file_extend,
                        type: 'POST',
                        data: {puth_file_save:puth,save:save},
                        success:function(data){
                            $('.field').html(data)
                            CKEDITOR.replace( 'ckeditor' );
                        }
                    });
                };
                
             function refresh_field(){
                 var puth = $('.back').attr('puth_old');
                 $.ajax({
                    url: file_extend,
                    type: 'POST',
                    data: {open_dir:puth},
                    success:function(data){
                        $('.field').html(data)
                    }
                });
             }
                         
            //Удаление файла или папки
            function remove(file,name){
                    $.ajax({
                        url: file_extend,
                        type: 'POST',
                        data: {remove:file,name:name},
                        success:function(data){
                            $('.modal').css('display','none')
                            refresh();
                        }
                    });
                }
                
            
             //Переименование файла (для папки нужно дописать)
            function rename(puth,rename){
                $.ajax({
                    url: file_extend,
                    type: 'POST',
                    data: {old_puth:puth,rename:rename},
                    success:function(data){
                        alert(data)
                        $('.modal').css('display','none')
                        refresh();
                    }
                });
            }
            
            function move(old_puth,new_puth){
                $.ajax({
                    url: file_extend,
                    type: 'POST',
                    data: {old:old_puth,new_puth:new_puth},
                    success:function(data){
                        $('.modal').css('display','none')
                        refresh();
                    }
                });
            }
            function spiner(){
                $('.modal').html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
                $('.modal').css('display','block')
            }
            
            //Создание новой папки
            function create_dir(puth,name,type){
                $.ajax({
                    url: file_extend,
                    type: 'POST',
                    data: {puth_file:puth,name:name,type:type},
                    success:function(data){
                        alert(data)
                        $('.modal').css('display','none')
                        refresh();
                    }
                });
            }
            
            function prava(dir,prava_chek){
                $.ajax({
                    url: file_extend,
                    type: 'POST',
                    data: {dir_prava:dir,prava_chek:prava_chek},
                    success:function(data){
                        alert(data)
                        $('.modal').css('display','none')
                        refresh();
                    }
                });
            }
            function create_arhive(dir){
                $.ajax({
                    url: file_extend,
                    type: 'POST',
                    data: {arhiv:dir},
                    success:function(data){
                        alert(data)
                        refresh();
                    }
                });
            }
            //Обновление дерева папок
            function refresh(){
                var chec = $('.select').index();
                var old_puth = '';
                chec = chec + 1;
                $.ajax({
                    url: file_extend,
                    type: 'POST',
                    data: {Refresh:1},
                    success:function(data){
                        $('.dom').html(data);
                        refresh_field();
                        $(".file").draggable({
                            stop: function() {
                              old_puth =  $(this).attr('puth');
                            }
                        });
                        $(".toggle").droppable({
                            drop: function() {
                               var new_puth = $(this).attr('puth');
                                if(old_puth){
                                    alert("Сейчас переместится в "+new_puth)
                                    move(old_puth,new_puth)
                                }
                            },
                            over: function() {
                                $(this).css({
                                    border: "1px solid #000"
                                });
                            },
                            out: function() {
                                $(this).css("border", "");
                            }
                        })
                    }
                }); 
            }
            
            $(document).ready(function(){
               
                $(".file").draggable({
                    stop: function() {
                      old_puth =  $(this).attr('puth');
                    }
                });
                $(".toggle").droppable({
                    drop: function() {
                       var new_puth = $(this).attr('puth');
                        if(old_puth){
                            alert("Сейчас переместится в "+new_puth)
                            move(old_puth,new_puth)
                        }
                    },
                    over: function() {
                        $(this).css({
                            border: "1px solid #000",
                        });
                    },
                    out: function() {
                        $(this).css("border", "");
                    }
                })
                               
                var old_puth = '';
                var text = '';
                var file = '';
                var puth = '';
                var value = '';
                var dom = $('.dom').html();
                
                //выделяем выбраный файл
                $('body').on('click', '.file a', function(e){
                    e.preventDefault();
                    file = $(this);
                    $('.select').removeClass('select');
                    $(file).addClass('select')
                })
                
                //выделяем выбраную папку
                $('body').on('click', '.toggle input', function(){
                    file = $(this).parent('.el');
                    $('.act').removeClass('act');
                    $('.select').removeClass('select');
                    $(file).find('span').eq(0).addClass('select')
                    $(file).addClass('act')
                })
                            
                //Изменение размеров картинки
                 $('body').on('click', '#exchange', function(e){
                    var img_reg = /^.+\.(jpg|png|jpeg|gif)$/;
                    var img = $(file).text();
                    if(img.match(img_reg)){
                        var puth_img = $(file).attr('href');
                         $('.modal .modal_content').html('<input name="width" placeholder="ширина"></br><span style="color:white;">(Указывать размер только в цифрах)</span></br><input type="checkbox" name="new_img"><span style="color:white;">Создать новый файл?</span></br><button id="send">Отправить</button>');
                         $('.modal').css('display','block');
                         $('#send').click(function(){
                            var new_img = 0;
                            if($("input[name='new_img']").prop('checked')){
                                new_img = 1;
                            }
                            var width = $("input[name='width']").val();
                            var re = /[A-z][a-z]/g;
                            var width = width.replace(re,'');
                            $.ajax({
                                url: file_extend,
                                type: 'POST',
                                data: {new_img:new_img,width:width,resize:1,img:img,puth_img:puth_img},
                                success:function(data){
                                    $('.modal').css('display','none');
                                    alert(data)
                                    refresh();
                                }
                            });
                         })
                    }else{
                        aleert('Картинка не выбрана!');
                    }
                })
                 $('body').on('click', '.rotate', function(e){
                     $('.modal .modal_content').html('<input type="text" name="rotate_pers" placeholder="повернуть на..." value="90"><br><p>Создать новое изобржние<br><input type="checkbox" id="new_img"></p><button id="rotate_img">Повернуть</button>')
                     $('.modal').css('display','block');
                     var type_rot = $(this).prop('id');
                     var puth = $('.text img').attr('src');
                     var new_img = 0;
                     $('#rotate_img').click(function(){
                        
                         
                         if($('#new_img').prop('checked')){
                             new_img = 1;
                         }
                         var persent = $('.modal').find('input[name="rotate_pers"]').val();
                         $.ajax({
                            url: file_extend,
                            type: 'POST',
                            data: {rotate:persent,new_img_rot:new_img,type_rot:type_rot,img_puth:puth},
                            success:function(data){
                                $('.modal').css('display','none');
                                $('.field').html(data);
                            }
                         })
                     
                    })
                })
                
                  $('body').on('dblclick', '.el', function(e){
                    puth = $('.select').parent('li').attr('puth');
                    $.ajax({
                        url: file_extend,
                        type: 'POST',
                        data: {open_dir:puth},
                        success:function(data){
                            value = $('.field').html();
                            $('.field').html(data)
                            text =  $('.field textarea').val();
                            $(".field .file").draggable({
                                stop: function() {
                                  old_puth =  $(this).attr('puth');
                                }
                            });
                            $(".toggle").droppable({
                                drop: function() {
                                   var new_puth = $(this).attr('puth');
                                    if(old_puth){
                                        alert("Сейчас переместится в "+new_puth)
                                        move(old_puth,new_puth)
                                    }
                                },
                                over: function() {
                                    $(this).css({
                                        border: "1px solid #000",
                                    });
                                },
                                out: function() {
                                    $(this).css("border", "");
                                }
                            })
                            if(data.indexOf('textarea') + 1) {
                             CKEDITOR.replace( 'ckeditor' );
                            }
                            
                        }
                    });
                  });
                
                //Закрываем модальное окно
                $('.closed').click(function(){
                    $('.modal').css('display','none')
                })
                
                //Удаляем выбраный файл папку
                $('#drop').click(function(){
                    var puth_file = [];
                    puth_file[0] = $('.select').parent('li').attr('puth');
                    var name = $('.select').find('span').text();
                    if( $('.field li').prop('ctrl') == 'check'){
                        var leng = $('.ctrl').parent('li').length;
                        leng = leng;
                        for(var i = 1; i < leng; i++){
                            if($('.field').find('li').eq(i).find('.ctrl').prop('checked')){
                                var puth = $('.field').find('li').eq(i).attr('puth');
                                var text = $('.field').find('li').eq(i).find('span').text();
                                puth_file[i] = puth;
                                name = name+","+text;
                            }
                        }
                    }
                    $('.modal .modal_content').html('<p>Удалить файлы '+name+'</p><button id="yes">Да</button><button id="no">Нет</button>');
                    $('.modal').css('display','block');
                    $('#yes').click(function(){
                        if(puth_file){
                            remove(puth_file,name);
                        }
                    })
                    $('#no').click(function(){
                        $('.modal').css('display','none');
                    })
                })
                
                $('html').keydown(function(eventObject){ //отлавливаем нажатие клавиш
                  if (event.keyCode == 17) { //если нажали Enter, то true
                        if( $('.field li').prop('ctrl') != 'check'){
                            $('.field li').prop('ctrl','check')
                            $('.field li').append('<br><input type="checkbox" class="ctrl">')
                        }
                  }
                });
                
                //Сохраняем
                $('.save').click(function(){
                    save()
                })
                
                //Закрываем файл
                $('body').on('click', '#closed', function(){
                    if(text !=  $('.field textarea').val()){
                        $('.modal .modal_content').html('<button class="save_clos">Сохранить</button><button class="dnsave">Не сохранять</button><button class="otm">Отмена</button>');
                        $('.modal').css('display','block');
                        $('.dnsave').click(function(){
                            $('.field').html('');
                            $('.modal').css('display','none');
                        })
                        $('.save_clos').click(function(){
                            save()
                            $('.modal').css('display','none');
                            setTimeout(function() { $('.field').html(value) }, 500);
                        })
                    }else{
                        $('.field').html(value);
                        $('.modal').css('display','none');
                    }
                })
                
                //Закрываем таблицу
                $('body').on('click', '#closed_table', function(){
                    $('.field').html(value);
                })
                
                //Отмена какой либо функции
                $('body').on('click', '.otm', function(){
                    $('.modal').css('display','none');
                })
                
                //Обновить дерево папок
                $('#refresh').click(function(){
                    refresh();
                })
                
                //Переименовать файл
                $('#rename').click(function(){
                    var val = $('.select').text();
                    if(val){
                        $('.modal .modal_content').html('<input type="text" value="'+val+'" id="new_name"><br><br><button id="save_name">Переименовать</button><button class="otm">Отмена</button>');
                        $('.modal').css('display','block')
                        $('#save_name').click(function(){
                            var name = $('#new_name').val(); 
                            if(name != ''){
                                var puth_file =  $('.select').parent('li').attr('puth');
                                rename(puth_file,name)
                            }else{
                                alert('Имя файла не должно быть пустым!');
                            }
                        })
                    }
                })
                
                //Записываем путь к папке
                $('body').on('click', '.tree input', function(){
                    puth = $(this).parent('.el').attr('puth');
                })
                
                //Создаем папку
                $('#create_dir').click(function(){
                    $('.modal .modal_content').html('<input type="text" value="" id="new_name"><br><br><button id="new_file">Создать</button><button class="otm">Отмена</button>');
                    $('.modal').css('display','block')
                    $('#new_file').click(function(){
                        var val = $('#new_name').val();
                        var new_puth = '';
                        if(val != ''){
                            new_puth =  $('.select').parent('li').attr('puth');
                            if(!new_puth){
                                new_puth = puth;
                            }
                            create_dir(new_puth,val,"folder")
                        }else{
                            alert("Название папки не может быть пустым");
                        }
                    })
                })
                
                $('#arhiv').click(function(){
                   var dir = $('.select').parent('li').attr('puth');
                   if(dir){
                       create_arhive(dir);
                   }else{
                       alert('Не выбран файл/папка');
                   }
                })
                
                $('#prava').click(function(){
                    var dir = $('.select').parent('li').attr('puth')
                    if(dir){
                        $('.modal .modal_content').html('<input type="text" value="" id="prava_chek" placeholder="Пример (0777)"><br><br><button id="indicate" >Задать</button><button class="otm">Отмена</button>');
                        $('.modal').css('display','block')
                        $('#indicate').click(function(){
                            var prava_chek = $('#prava_chek').val();
                            prava(dir,prava_chek);
                        })
                    }
                })
                
                //Создаем файл
                $('#create_file').click(function(){
                    $('.modal .modal_content').html('<input type="text" value="" id="new_name"><br><br><button id="new_file">Создать</button><button class="otm">Отмена</button>');
                    $('.modal').css('display','block')
                    $('#new_file').click(function(){
                        var new_puth = '';
                        var val = $('#new_name').val();
                        if(val != ''){
                            new_puth =  $('.select').parent('li').attr('puth');
                            if(!new_puth){
                                new_puth = puth;
                            }
                            create_dir(new_puth,val,"file")
                        }else{
                            alert("Название файла не может быть пустым");
                        }
                    })                    
                })
                
                $('#watch_dost').click(function(){
                    var dir = $('.select').parent('li').attr('puth');
                    value = $('.field').html();
                    $.ajax({
                        url: file_extend,
                        type: 'POST',
                        data: {watch_dost:dir},
                        success:function(data){
                            $('.field').html(data)
                        }
                    });
                })
                
                $('#move').click(function(){
                    var puth_move = $('.dom .select').parent('li').attr('puth');
                    if(!puth_move){
                        puth_move = $('.field .select').parent('li').attr('puth');
                    }
                    var puth_new = '';
                    $('.modal .modal_content').html(dom+"<br><button id='move_click'>Переместить</button>");
                    $('.modal').css('display','block')
                    $('#move_click').click(function(){
                        puth_new = $('.modal .select').parent('li').attr('puth');
                        move(puth_move,puth_new);
                    })
                })
                $('#sort_file').click(function(){
                    $(this).find('select').fadeIn();   
                })
                
                $('#sort_file').find('select').change(function(){
                    var sort = $(this).val();
                    var puth_sort = '';
                    if(puth){
                        puth_sort = puth;
                    }else{
                      puth_sort = '../test/';  
                    }
                    sortFile(puth_sort,sort);
                })
                
                $('#find button').click(function(){
                    var val = $('#find input[name="find"]').val();
                    if(val){
                        find(val);
                    }else{
                         $('#find input[name="find"]').css('border-color','red')
                    }
                })
                $('#upload input').change(function(){
                    var files = this.files;
                    var data = new FormData();
                    $.each( files, function( key, value ){
                        data.append( key, value );
                    }); 
                    if(puth){
                        puth_sort = puth;
                    }else{
                        puth_sort = '../test/';  
                    }
                    data.append( 'puth_to_file', puth_sort );
                    $.ajax({
                        url: file_extend,
                        type: 'POST',
                        data: data,
                        cache: false,
                        processData: false, // Не обрабатываем файлы (Don't process the files)
                        contentType: false, // Так jQuery скажет серверу что это строковой запрос
                        success:function(data){
                           alert(data)
                           $('#upload input').val('');
                           refresh();
                        },
                    });
                })
            })
            function find(val){
                $.ajax({
                    url: file_extend,
                    type: 'POST',
                    data: {find:val},
                    success:function(data){
                        $('.field').html(data)
                    }
                });
            }
            function  sortFile(puth,sort){
                $.ajax({
                    url: file_extend,
                    type: 'POST',
                    data: {sort:sort,puth_sort:puth},
                    success:function(data){
                        $('.field').html(data)
                    }
                });
            }