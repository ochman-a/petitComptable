<?php 
    include ('includes/db_connect.inc.php');
    include ('includes/login_func.php');
    session_start();
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="style/petitComptable.css">
        <link href="https://fonts.googleapis.com/css?family=Inconsolata|Indie+Flower" rel="stylesheet"> 
        <title>Petit Comptable</title>
    </head>
    <body>
        <div class="form">
            <h1>Petit Comptable</h1>
            <?php formFunc(); ?>
            <div class="login">
                <form method="POST" action="petitComptable.php">
                    <input type="email" name="email" placeHolder="Votre e-mail"/>
                    <input type="password" name="password" placeHolder="Votre mot de passe"/> <br>
                    <input id="sub" type="submit" name="submitForm" value="Valider">
                </form>
            </div>
        </div>
        <div class="date">
            <?php  echo date("d/m/Y h:i"); ?>
        </div>
    </body>
</html>