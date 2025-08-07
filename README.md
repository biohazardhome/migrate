<p align="center"><strong>Визуальный конструктор миграций баз данных для Laravel</strong> </p>
<div align="center"> <a href="#features">Особенности</a> • <a href="#requirements">Требования</a> • <a href="#installation">Установка</a> • <a href="#usage">Использование</a> • <a href="#contributing">Вклад в проект</a> • <a href="#license">Лицензия</a> </div>
<h2 id="about">🚀 О проекте</h2>
<p>Laravel Migration Generator - это мощный инструмент с графическим интерфейсом для быстрого создания миграций баз данных в Laravel. Проект позволяет визуально проектировать структуру таблиц без написания кода и генерирует готовый PHP-код миграций.</p>
<h2 id="features">✨ Особенности</h2>
<ul>
	<li>📝 Визуальное проектирование структуры таблиц</li>
	<li>⚡️ Поддержка всех типов данных Laravel</li>
	<li>🔑 Создание индексов и внешних ключей</li>
	<li>🔄 Drag & Drop интерфейс</li>
	<li>📦 Экспорт готового кода миграции</li>
</ul>
<h2 id="requirements">⚙️ Требования</h2>
<table>
	<tr>
		<th>Компонент</th>
		<th>Версия</th>
	</tr>
	<tr>
		<td>PHP</td>
		<td>8.1+</td>
	</tr>
	<tr>
		<td>Laravel</td>
		<td>10.x+</td>
	</tr>
	<tr>
		<td>Node.js</td>
		<td>18.x+</td>
	</tr>
	<tr>
		<td>MySQL</td>
		<td>8.0+</td>
	</tr>
</table>
<h2 id="installation">🚀 Установка</h2>
<ol>
	<li>Клонируйте репозиторий: <pre><code>git clone https://github.com/yourusername/laravel-migration-generator.git cd laravel-migration-generator</code></pre> </li>
	<li>Установите зависимости: <pre><code>composer install npm install</code></pre> </li>
	<li>Настройте окружение: <pre><code>cp .env.example .env php artisan key:generate</code></pre> </li>
	<li>Соберите фронтенд: <pre><code>npm run build</code></pre> </li>
	<li>Запустите сервер: <pre><code>php artisan serve</code></pre> </li>
</ol>
<h2 id="usage">🖥 Использование</h2>
<h3>Создание миграции</h3>
<div class="usage-steps">
	<div class="step">
		<h4>1. Основные параметры</h4>
		<p>Введите имя таблицы, выберите движок и кодировку</p>
	</div>
	<div class="step">
		<h4>2. Добавление полей</h4>
		<p>Настройте параметры каждого поля: тип, длину, модификаторы</p>
	</div>
	<div class="step">
		<h4>3. Создание индексов</h4>
		<p>Добавьте индексы и выберите тип</p>
	</div>
	<div class="step">
		<h4>4. Внешние ключи</h4>
		<p>Настройте связи между таблицами</p>
	</div>
</div>
<h3>Пример сгенерированного кода</h3> <pre><code class="language-php">
public function up() {
  Schema::create('products', function (Blueprint $table) { 
    $table->id(); 
    $table->string('name', 255); 
    $table->text('description');
    $table->decimal('price', 8, 2); 
  
    $table->foreignId('category_id') 
      ->constrained()
      ->onDelete('cascade');
    $table->timestamps();
  
    $table->index('name', 'products_name_index', 'hash');
    $table->fullText('description');
});
}</code></pre>
<h2 id="contributing">🤝 Вклад в проект</h2>
<p>Мы приветствуем вклад в проект! Порядок действий:</p>
<ol>
	<li>Форкните репозиторий</li>
	<li>Создайте ветку для своей функции</li>
	<li>Зафиксируйте изменения</li>
	<li>Отправьте изменения</li>
	<li>Создайте pull request</li>
</ol>
<h2 id="license">📜 Лицензия</h2>
<p>Проект распространяется под лицензией <a href="LICENSE">MIT</a>.</p>