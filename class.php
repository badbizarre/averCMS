<?php
namespace my\name; // см. раздел "Определение пространств имен"

class MyClass {}
function myfunction() {}
const MYCONST = 1;

$a = new MyClass;
$c = new \my\name\MyClass; // см. раздел "Глобальная область видимости"

$a = strlen('hi'); // см. раздел "Использование пространств имен: возврат
                   // к глобальной функции/константе"

$d = namespace\MYCONST; // см. раздел "оператор пространства имен и
                        // константа __NAMESPACE__"
$d = __NAMESPACE__ . '\MYCONST';
echo constant($d); // см. раздел "Пространства имен и динамические особенности языка"
?>