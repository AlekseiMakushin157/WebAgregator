<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $title; ?></title>
        <script src="/public/js/jquery-3.6.1.min.js"></script>
        <script type="text/javascript" src="/public/js/DataLoader.js"></script>
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <body>
        <div class="page-content">
            <header class="header">
                <h1>
                    <b>Агрегатор новостей</b>
                </h1>
            </header>
            <main class="main">
                <div class="wraper">
                    <div class="container-flex bottombar">
                        <h4>Выберите новостной сайт:</h4>
                        <div class="container">
                            <select class="js__select-source" name="sources">
                                <!--<option disabled selected value> Выберите источник</option>-->
                                <?php foreach ($sources as $ident => $name): ?>
                                    <option value=<?= $ident ?>><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <h4>Количество записей на странице:</h4>
                        <div class="container">
                            <select class="js__select-number" name="sources">
                                <?php foreach ($number as $ident => $name): ?>
                                    <option value=<?= $ident ?>><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="container">
                            <p><button class="js__load-btn">Получить</button></p>
                        </div>
                    </div>
                    <div class="container card-block js__news-block">
                        <div class="container-flow">
                            <p class="float-left">
                                <button class="hidden js__news-prev-btn">Показать предыдущие записи</button>
                            </p>
                            <p  class="float-right">
                                <button class="hidden js__news-next-btn">Показать новые записи</button>
                            </p>
                        </div>
                        <div class="container card-list js__news-list">
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>