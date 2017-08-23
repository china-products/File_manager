var log = require('logger')(module);
var db = require('db'); //Подключение файлов
db.connect();
var User = require('./user');
function run(){
    var vasya = new User('Вася');
    var petya = new User('Петя');
    vasya.hello(petya);
    log(db.getPhrases("Run successful"));
}

if(module.parent){
    exports.run = run;
}else{
    run();
}