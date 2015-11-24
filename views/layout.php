<?php
  ViewTemplate::$rendered = true;
?>
<!DOCTYPE HTML>
<html lang="ru">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title><?php if(isset(ViewTemplate::$title)) { echo ViewTemplate::$title; } ?></title>
    <?php if(isset(ViewTemplate::$head)) { include ViewTemplate::$head; } ?>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <link rel="stylesheet" media="all" href="/css/app.css">
    <script src="/js/app.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  </head>
  <body>
    <div id="wrapper">
      <?= render_menu(); ?>
      <h1 class="main-header">todos</h1>
      <div id="content">

        <?php if(isset(ViewTemplate::$body)) { include ViewTemplate::$body; } ?>
      </div>
    </div>
  </body>
</html>