<h1>Tandava Framework</h1>
MVC архитектура
Грубо говоря, это даже не фреймворк, а заготовка под ваши проекты.
В нем есть все самое необходимое, без чего-то лишнего.
Подключен <b>Twig</b>,  <b>Composer</b>, свой собственный <b>QueryBuilder</b>,  маршрутизатор, и логгер

<b>Содержание</b><br>
[1. Маршрутизатор](#маршрутизатор "1. Маршрутизатор")<br>
[2. Sql Builder](#sql-builder "2. Sql Builder")<br>
[3. Папка Public](#папка-public "3. Папка Public")<br>
[4. Класс View](#класс-view "3. Класс View")<br>

<h2>Маршрутизатор</h2>

<h3>Теория</h3>
Что такое маршрутизатор? Все запросы, которые идут на сервер, обрабатываются файлом <b>index.php</b>. Он вытаскивает путь запроса пользователя, и находит маршруты, которые соответствуют пути. После, он обрабатывает функцию, которая записана в маршруте. Возвращаемое значение этой функции, он выводит на экран.

<h3>Документация</h3>
Маршруты нужно указывать в файле <b>app/components/Routes.php</b>, через экземпляр класса в переменной <b>$router</b>

```php
$router->Any("/product/([0-9]*)", "products@list");
$router->Any("/products/(.*)/([0-9]*)", function ($category, $page) {
	return "Ваша категория: " . $category . "<br>Ваша страница: " . $page;
});

$router->Get("/home/", function ($getData) {
	return "Ваш первый GET параметр -- " . array_shift($getData) ;
});

$router->Post("/", "handler@handler");

$router->Error(404, function () {
	return "Страница не найдена";
});
```

Есть несколько вариантов записи функции, которая вызовется, если пользователь зайдет по этому маршруту. Первый, самый простой, это <b>анонимная функция</b>.
```php
$router->Any("/", function () {
	return "Hello World";
}); // выведет на экран Hello World
```

Вторым вариантом является указание пути к классу,  к функции, которую должен обработать скрипт. Путь записывается таким образом: <b>класс@функция</b>. Так, например, если указать <b>main</b>@<b>function</b>, то он вызовет класс <b>ControllerMain</b>, и функцию <b>ActionFunction</b>.
(<b>P.S, класс должен находиться в файле /app/controller/Имя класса.php</b>)

Если ваша функция возвратит текст, первым символом которого является <b>@</b>, то вызовется номер ошибки, который будет указан после первого символа. Т.е:

```php
$router->Any("/", function () {
	return "@403";
});

$router->Error(403, function () {
	return "Нет прав";
});
```

<h3>Список методов</h3>

```php

$router->Get("/", function ($getData) {}, $middlewares); // вызовется в случае, если будет хоть один GET параметр. Передаст функции первым аргументом массив, содержащий все GET параметры.

$router->Post("/", function ($postData) {}, $middlewares); // вызовется в случае, если будет передан метод PUT

$router->Put("/", function ($postData) {}, $middlewares); // вызовется в случае, если будет передан метод PUT

$router->Delete("/", function ($postData) {}, $middlewares); // вызовется в случае, если будет передан метод DELETE

$router->Match(["get", "post"], "/", function ($postData) {}, $middlewares); // вызовется в случае, если будет передан один из методов, указанных в первом аргументе

$router->Any("/", function() {}); // вызовется в любом случае

$router->Run(); // запускает маршрутизатор

```

<h2>Sql Builder</h2>
В библиотеку встроен SqlBuilder. Что это такое? 
Вы открываете файл <b>/app/config/config.ini</b>, и настраиваете подключение к базе данных. После чего, вы можете свободно использовать функции билдера.

```php

Sql::Select()->From("users")->Where("id", 1)->Where("messages", ">=", 1)->Where("login", "LIKE", "%lolicon%")->Exec(); // Возвращает массив с результатами

Sql::Insert(["id" => 2, "login" => "lolicon"])->From("users")->Exec(); // создаем юзера в таблице

Sql::Update(["login" => "lolicon2"])->From("users")->Where("id", 1)->Exec(); // обновляем логин у юзера

Sql::Delete()->From("users")->Where("id", 1)->Exec(); // удаляем пользователя из таблицы

```

Как вы уже заметили, в методе Where, можно указывать параметр 2 аргументом, а значение третим. Или, указывать значение вторым аргументом, опуская указание оператора (в таком случае, применится оператор <b>=</b>)

<h2>Папка Public</h2>
В проекте существует папка public.
Если в нее положить какой-то файл, то он станет доступным для чтения через адресную строку.
Например, если в нее положить файл <b>image.jpg</b>, то он станет доступным для просмотра, по адресу <b>/image.jpg</b>

<h2>Класс View</h2>
Этот класс предназначен для работы с Twig. Вот его методы:

```php
class View {
	/**
	* Получает содержимое папки /app/view/$file.twig, и возвращает его. 
	*/
	public function File(string $file, array $args = [], string $extension = ""): string

    /**
     * Устанавливает стандартное расширение для открываемого файла
     */
    public function SetExtension(string $extension): void
}
```

Пример его работы:

<b>Файл "/app/view/hello.twig"</b>
```html
<p>
Hello, {{ user }}
</p>
```

<b>Файл "/app/controller/ControllerTest.php"</b>
```php
class ControllerTest extends \Tandava\View
{
	public function ActionHello($userName)
	{
		return $this->File("hello", ["user" => $userName]);
	}
}
```

<b>Файл "/app/components/Routes.php"</b>

```php
$router->Request("/user/(.*)", "test@hello");
```
