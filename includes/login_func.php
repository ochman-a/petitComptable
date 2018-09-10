<?php
function formFunc() 
{
    if(isset($_POST['submitForm']))
    {
        $db = db_connect();
        $req = $db->prepare("SELECT * FROM users WHERE email_user = :email");
        $req->execute(array("email" => $_POST['email']));
        
        while($data = $req->fetch())
        {
            if($data['pwd_user'] == $_POST['password'])
            {
                header("Location: accountPage.php");
                $_SESSION['id_user'] = $data['id_user'];
                exit;
            }
        }
    }
}