<?php
    include ('includes/db_connect.inc.php');
    session_start();
    $db = db_connect();
    $_POST['id_account'] = 1;
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="style/accountPage.css">
        <link href="https://fonts.googleapis.com/css?family=Inconsolata|Indie+Flower" rel="stylesheet">
        <script src="js/javascriptfunc.js"></script>
        <title>Your account</title>
    </head>
    <body>
        <div id="add-op">
            <div class="op-white">
                <div class="op-form">
                    <button onclick="myFunction(2)" class="close but" type="button">X</button> 
                    <h3 style="text-align: center;">Add operation</h3>
                    <form method="POST" action="accountPage.php">
                        <input type="text" name="name" placeHolder="Operation name"/><br>
                        <select name="category">
                            <option value="13">Alimentaire / debit</option>
                            <option value="14">Vestimentaire / debit</option>
                            <option value="15">Loisir / debit</option>
                            <option value="16">Transport / debit</option>
                            <option value="17">Logement / debit</option>
                            <option value="18">Autres / debit</option>
                            <option value="19">Virement / credit</option>
                            <option value="20">Dépot / credit</option>
                            <option value="21">Salaire / credit</option>
                            <option value="22">Autre / credit</option>
                        </select><br>
                        <select name="payment">
                            <option value="chèque">Cheque</option>
                            <option value="virement">Virement</option>
                            <option value="prélèvement">Prelevement</option>
                        </select><br>
                        <input type="number" name="amount" step="0.01" placeHolder="Amount"/><br>
                        <input id="sub" type="submit" name="submitFormOP" value="Valider">
                    </form>
                    <?php
                        global $db;
                        if(isset($_POST['submitFormOP']))
                        {
                            $req = $db->prepare("INSERT INTO `operations` (`id_account`, `name_operation`, `amount_operation`, `category_operation`, `payment_method`) VALUES ('1', :name, :amount, :cat, :pay)");
                            $req->execute(array(
                                "name" => $_POST['name'],
                                "amount" => $_POST['amount'],
                                "cat" => $_POST['category'],
                                "pay" => $_POST['payment']
                            ));
                            $req = $db->prepare("SELECT balance_account FROM virtualBankAccounts WHERE id_account = :id_account");
                            $req->execute(array("id_account" => $_POST['id_account']));
                            $amount = $req->fetch()['balance_account'];
                            if ($_POST['category'] > 12 && $_POST['category'] < 19)
                            {
                                $amount = $amount - $_POST['amount'];
                            }
                            else
                            {
                                $amount = $amount + $_POST['amount'];
                            }
                            $req = $db->prepare("UPDATE `virtualBankAccounts` SET `balance_account` = :amount WHERE `virtualBankAccounts`.`id_account` = 1");
                            $req->execute(array("amount" => $amount));
                        }
                    ?>
                </div>
            </div>
        </div>
        <div id="add-account">
            <div class="op-white">
                <div class="account-form">
                    <button onclick="myFunction(4)" class="close but" type="button">X</button> 
                    <h3 style="text-align: center;">Add account</h3>
                    <form method="POST" action="accountPage.php">
                        <input type="text" name="name" placeHolder="Account name"/><br>
                        <select name="type">
                            <option value="épargne">Epargne</option>
                            <option value="courant">Courant</option>
                            <option value="compte joint">Compte joint</option>
                        </select><br>
                        <input type="number" name="provision" placeHolder="Balance"/><br>
                        <select name="currency">
                            <option value="eur">EUR | €</option>
                            <option value="usd">USD | $</option>
                        </select><br>
                        <input id="sub" type="submit" name="submitFormAC" value="Valider">
                    </form>
                    <?php
                        if(isset($_POST['submitFormAC']))
                        {
                            global $db;
                            $req = $db->prepare("SELECT COUNT(*) as myCount FROM virtualBankAccounts WHERE id_user = :id_user");
                            $req->execute(array("id_user" => $_SESSION['id_user']));
                            $number = $req->fetch()['myCount'];
                            if ($number < 10)
                            {
                                $req = $db->prepare("INSERT INTO `virtualBankAccounts` (`id_user`, `name_account`, `type_account`, `balance_account`, `currency`) VALUES (:id_user, :name, :type, :balance, :currency)");
                                $req->execute(array(
                                    "id_user" => $_SESSION['id_user'],
                                    "name" => $_POST['name'],
                                    "type" => $_POST['type'],
                                    "balance" => $_POST['provision'],
                                    "currency" => $_POST['currency']
                                ));
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="head">
            <h1 id="welcome">Welcome</h1>
            <a id="deco" class="but" href="petitComptable.php">Disconnect</a>
            <?php
                session_destroy();
            ?>
        </div>
        <div class="main">
            <div class="side-bar">
                <h2 id="text-accounts">Accounts</h2>
                <?php
                    global $db;

                    $req = $db->prepare("SELECT id_account, name_account FROM virtualBankAccounts");
                    $req->execute();
                    echo "<table style='margin-left: 80px;'><tbody>";
                    while ($data = $req->fetch())
                    {
                        echo "<tr>";
                        echo "<form method='POST' action='accountPage.php'>";
                        echo "<td><input type='hidden' name='change_id_account' value='" . $data['id_account'] . "'>";
                        echo "<input type='submit' name='change_ac' value='" . $data['name_account']  ."'></td>";
                        echo "<td><input type='hidden' name='delete_id_account' value='" . $data['id_account'] . "'>";
                        echo "<input type='submit' name='delete_ac' value='X'></td>";
                        echo "</form>";
                        echo "</tr>";                            
                    }
                    echo "</tbody></table>";

                    if (isset($_POST['change_ac']))
                    {
                        $_POST['id_account'] = $_POST['change_id_account'];
                    }

                    if (isset($_POST['delete_ac']))
                    {
                        $req = $db->prepare("DELETE FROM `virtualBankAccounts` WHERE `virtualBankAccounts`.`id_account` = :id_account;");
                        $req->execute(array("id_account" => $_POST['delete_id_account']));
                        header("Location: accountPage.php");
                    }
                ?>
                <button onclick="myFunction(3)" class="add-account but" type="button">Add account</button> 
            </div>
            <div class="main-info">
                <div class="top-info">
                    <div class="balance">
                        <h2 id="text-balance">Balance:</h2>
                        <div id="amount">
                            <?php
                                global $db;

                                $req = $db->prepare("SELECT * FROM virtualBankAccounts WHERE id_account = :id_account");
                                $req->execute(array("id_account" => $_POST['id_account']));
                                $data = $req->fetch();
                                if ($data['balance_account'] < 0)
                                {
                                    echo "<span style='color:#d41717'>" . $data['balance_account'] . " " . $data['currency'] . "</span>";
                                }
                                else
                                {
                                    echo "<span style='color:#1dd417'>" .  $data['balance_account'] . " " . $data['currency'] . "</span>";
                                }
                            ?>
                        </div>
                    </div>
                    <h2 id="text-ope">Operations:</h2>
                </div>
                <div class="op-info">
                    <table class='op'>
                        <thead>
                            <tr>
                                <th class='op'>Operation name</th>
                                <th class='op'>Category</th>
                                <th class='op'>Payment mode</th>
                                <th class='op'>Credit</th>
                                <th class='op'>Debit</th>
                            </tr>
                        </thead>
                        <tbody>    
                            <?php
                                global $db;
                                $req = $db->prepare("SELECT O.*, C.type, C.name FROM operations O, category C WHERE O.category_operation = C.id AND O.id_account = :id_account");
                                $req->execute(array("id_account" => $_POST['id_account']));
                                while($data = $req->fetch())
                                {
                                    echo "<tr>";
                                    echo "<td style='display: none;'>" . $data['id_operation'] . "</td>";
                                    echo "<td class='op'>" . $data['name_operation'] . "</td>";
                                    echo "<td class='op'>" . $data['name'] . "</td>";
                                    echo "<td class='op'>" . $data['payment_method'] . "</td>";
                                    if($data['type'] == "credit")
                                    {
                                        echo "<td class='op' style='color:#1dd417'>+" . $data['amount_operation'] . "</td>";
                                        echo "<td class='op'></td>";
                                    }
                                    else
                                    {
                                        echo "<td class='op'></td>";
                                        echo "<td class='op' style='color:#d41717'>-" . $data['amount_operation'] . "</td>";
                                    }
                                    echo "<form method='POST' action='accountPage.php'>";
                                    echo "<td><input type='hidden' name='id_operation' value='" . $data['id_operation'] . "'>";
                                    echo "<input type='submit' name='delete' value='Delete'></td>";
                                    echo "<td><input type='hidden' name='id_operation' value='" . $data['id_operation'] . "'>";
                                    echo "<input type='submit' name='modify' value='Modify'></td>";
                                    echo "</form>";
                                    echo "</tr>";
                                }

                                if (isset($_POST['delete']))
                                {
                                    $req = $db->prepare("SELECT balance_account FROM virtualBankAccounts WHERE id_account = 1");
                                    $req->execute();
                                    $amount = $req->fetch()['balance_account'];
                                    $req = $db->prepare("SELECT * FROM operations WHERE id_operation = :id_operation");
                                    $req->execute(array("id_operation" => $_POST['id_operation']));
                                    $req->execute();
                                    $operation = $req->fetch();
                                    $req = $db->prepare("DELETE FROM `operations` WHERE `id_operation` = :id_operation;");
                                    $req->execute(array("id_operation" => $_POST['id_operation']));
                                    if ($operation['category_operation'] > 12 && $operation['category_operation'] < 19)
                                    {
                                        $amount = $amount + $operation['amount_operation'];
                                    }
                                    else
                                    {
                                        $amount = $amount - $operation['amount_operation'];
                                    }
                                    $req = $db->prepare("UPDATE `virtualBankAccounts` SET `balance_account` = :amount WHERE `virtualBankAccounts`.`id_account` = 1");
                                    $req->execute(array("amount" => $amount));
                                    header("Location: accountPage.php");
                                } 
                                
                                if (isset($_POST['modify']))
                                {

                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <button onclick="myFunction(1)" class="add but" type="button">Add operation</button> 
    </body>         
</html>