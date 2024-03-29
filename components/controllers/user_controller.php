<?php

$uc2 = isset($_GET['uc2']) ? htmlspecialchars($_GET['uc2']) : "home";
switch($uc2)
{
    case "login":
        if(isset($isLogged)) exit(header('Location: /home'));
        $title .= "Login / Sign-up";
        $view = "user/login.php";
        if(isset($_POST['login'],$_POST['passwd'])){
            $login = htmlspecialchars($_POST['login']);
            $passwd = htmlspecialchars($_POST['passwd']);
            if(!empty($login) && !empty($passwd)){
               if(user::signin($login,$passwd)){
                exit(header('Location: /home'));
               }
               else{
                $_SESSION['error'] = "Veuillez entrer des identifiants valides";
                exit(header('Location: /user/login'));
               }

            }
            else{
                $_SESSION['error'] = "Veuillez remplir toutes les informations nécessaire";
                exit(header('Location: /user/login'));
            }
        }
        elseif(isset($_POST['email'],$_POST['fullname'],$_POST['passwd'],$_POST['confpasswd'])){
            $email = htmlspecialchars($_POST['email']);
            $fullname = htmlspecialchars($_POST['fullname']);
            $passwd = htmlspecialchars($_POST['passwd']);
            $confpasswd = htmlspecialchars($_POST['confpasswd']);
            if(!empty($_POST['email']) && !empty($_POST['fullname']) && !empty($_POST['passwd']) && !empty($_POST['confpasswd']))
            {
                if($passwd == $confpasswd){
                    user::signup($email,$fullname,$passwd);
                }else{
                    $_SESSION['error'] = "Les mots de passe ne correspondent pas";
                }
                exit(header('Location: /user/login'));
            }

        }
    break;
    case "change-informations":
        $view = "user/form.php";
        $title .= "Change informations";
        $user = User::get_user($_SESSION['user_id']);
        if(isset($_POST['email'],$_POST['fullname'],$_POST['verifpasswd'])){
            $email = htmlspecialchars($_POST['email']);
            $fullname = htmlspecialchars($_POST['fullname']);
            $verifpasswd = htmlspecialchars($_POST['verifpasswd']);
            if(!empty($email) && !empty($verifpasswd) && !empty($fullname)){
                if(!user::edit_user($email,$fullname,$verifpasswd)) {
                    $_SESSION['error'] = "Mot de passe incorrect";
                }
            }
            else $_SESSION['error'] = "Veuillez remplir tous les champs";
            exit(header('Location: /user/change-informations'));
        }
        else if(isset($_POST['passwd'], $_POST['newpasswd'], $_POST['confpasswd'])) {
            $passwd = htmlspecialchars($_POST['passwd']);
            $newpasswd = htmlspecialchars($_POST['newpassd']);
            $confpasswd = htmlspecialchars($_POST['confpasswd']);
            if($newpasswd == $confpasswd) {
                User::changePasswd($newpasswd);
                exit(header('Location: /home'));
            }else {
                $_SESSION['error'] = "Les mots de passe ne sont pas identiques";
                exit(header('Location: /user/change-informations'));
            } 
        }

    break;
    case "signout":
        user::signout();
        exit(header('Location: /home'));
    break;

    default:
        $title .= "Page not found";
        $view = "error_docs/404.php";
    break;
}


?>