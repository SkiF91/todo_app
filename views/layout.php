<?php
  ViewTemplate::$rendered = true;
?>
<!DOCTYPE HTML>
<html lang="ru">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title><?php if(isset(ViewTemplate::$title)) { echo ViewTemplate::$title; } ?></title>
    <?php if(isset(ViewTemplate::$head)) { include ViewTemplate::$head; } ?>
    <link rel="stylesheet" media="all" href="/css/app.css">
    <script src="/js/app.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  </head>
  <body>
    <div id="wrapper">
      <h1 class="main-header">todos</h1>
      <div id="content">

        <?php if(isset(ViewTemplate::$body)) { include ViewTemplate::$body; } ?>
      </div>
    </div>
  </body>
</html>