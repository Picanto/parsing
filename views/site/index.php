<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Парсинг https://gkb81.ru/sovety/</h1>

        <p><a class="btn btn-lg btn-success" href="http://parsing/web/site/parser">Распарсить сайт</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
                <h2>Описание задания</h2>

                Необходимо создать парсер на [yii2|Symfony] с сохранением в базу.<br>
                Результат задания должен быть архив с файлами которые попадают под гит<br>
                В качестве донора использовать https://gkb81.ru/sovety/<br>
                В базу необходимо сохранить данные:<br>
                1.Название статьи<br>
                2.Ссылка на статьи<br>
                3.Детальный текст статьи<br>
                4.Дата публикации статьи<br>
                5.Сохранить в папку картинку (превью)<br>
                *Дополнительная задача (необязательная, если есть знание Битрикс)<br>
                Написать под 1С-Битрикс агент, который будет каждые 60 минут активировать парсер<br>
            </div>
        </div>

    </div>

</div>
