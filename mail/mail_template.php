<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

    <div class="container">

    <p>
    Hi <?=$username?>,<br/>
    <?=$message?><br/>
    <a href="<?=$link?>" class="btn btn-info btn-round" role="button"><?=$link_message?></a><br/>
    </p>
    <i>- the QTest team</i>

 
</div>
</body>
</html>

