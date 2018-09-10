<?php
     include ('includes/db_connect.inc.php');
     $db = db_connect();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>ExoSubCat</title>
    </head>
     <body>
        <div class="add">
            <h2>Form to create sub-category</h2>
            <form method="POST" action="subCat.php">
                <select name="cat_select">
                    <?php
                        global $db;
                        $req = $db->prepare("SELECT * FROM category");
                        $req->execute();
                        while ($data = $req->fetch())
                        {
                            echo "<option value='" . $data['id'] . "'>" . $data['name'] . "</option>";
                        }
                    ?>
                </select>
                <input type="text" name="name" placeHolder="Sub-category name"/>
                <input type="submit" name="submitAddSub" value="Accept">
            </form>
            <?php
                global $db;
                if (isset($_POST['submitAddSub']))
                {
                    if (strlen($_POST['name']) == 0)
                    {
                        echo "Please write a name in order to add the sub-category.";
                    }
                    else if (strlen($_POST['name']) > 40)
                    {
                        echo "Name too long.";
                    }
                    else
                    {
                        $req = $db->prepare("SELECT COUNT(*) AS number FROM sub_category WHERE id_cat = :id_cat");
                        $req->execute(array("id_cat" => $_POST['cat_select']));
                        $number = $req->fetch()['number'];
                        if ($number < 40)
                        {
                            $req = $db->prepare("INSERT INTO `sub_category` (`id_cat`, `name`) VALUES (:id_cat, :name)");
                            $req->execute(array(
                                "id_cat" => $_POST['cat_select'],
                                "name"   => $_POST['name']
                            ));
                            echo "<br>Sub-category succesfully added !";
                        }
                        else
                        {
                            echo "<br>Sorry there is too much sub-categories in this category.";
                        }
                    }
                }
            ?>
        </div>
        <div class="delete">
            <h2>Form to delete sub-category</h2>
            <form method="POST" action="subCat.php">
                <select name="sub_select">
                    <?php
                        $req = $db->prepare("SELECT id_sub, sub_category.name as sname, category.name as cname FROM sub_category, category WHERE sub_category.id_cat = category.id");
                        $req->execute();
                        while ($data = $req->fetch())
                        {
                            echo "<option value='" . $data['id_sub'] . "'>Category: " . $data['cname'] . " | Sub-category: " . $data['sname'] . "</option>";
                        }
                    ?>
                </select>
                <input type="submit" name="submitDelSub" value="Delete">
            </form>
            <?php
                if (isset($_POST['submitDelSub']))
                {
                    global $db;
                    $req = $db->prepare("DELETE FROM sub_category WHERE id_sub = :id_sub");
                    $req->execute(array("id_sub" => $_POST['sub_select']));
                    echo "<br>Sub-category succesfully deleted !";
                    header("Location: subCat.php");
                }
            ?>
        </div>
    </body>
</html>