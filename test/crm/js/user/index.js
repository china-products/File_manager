//exports
var db = require('db'); //Подключение файлов
var log = require('logger')(module);
db.connect();
function User(name){
    this.name = name;
}
User.prototype.hello = function(who){
    console.log(db.getPhrases('Hello') + ", " + who.name);
};
console.log("User.js is required");
module.exports = User; //export переменных в другие файлы