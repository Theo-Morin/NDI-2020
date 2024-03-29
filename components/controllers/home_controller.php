<?php

$uc1 = isset($_GET['uc1']) ? htmlspecialchars($_GET['uc1']) : "home";
switch($uc1)
{
    case "home":
        $view = "home.php";
        $title .= "Home";
        $nbSpots = Spots::getNbSpots();
        $nbUsers = User::nb_users();

        if(isset($_POST['city'])) {
            $city = htmlspecialchars($_POST['city']);
            $_SESSION['city'] = $city;
        }
        if(isset($_SESSION['city'])) {
            $weather = MeteoAPI::getCurrentWeather($_SESSION['city']);
            if($weather['location'] == NULL) {
                $_SESSION['city'] = "Bordeaux";
                $weather = MeteoAPI::getCurrentWeather($_SESSION['city']);
            }
        } else $weather = MeteoAPI::getCurrentWeather("Bordeaux");
    break;
    case "spots":
        $title .= "Spots";
        $view = "spots.php";
        $spots = Spots::getDatas();
    break;
    case "informations-surf":
        $view = "Remplir le formulaire";
        if(!isset($_SESSION['user_id'])) exit(header('Location: /user/login'));
        $title .= "Renseignez vos informations de Ride";
        $view = "form.php";
        $spots = Spots::getDatas();
        $products = SurfData::getProducts();
        /**
         * City, Spot, Products,
         * baigneurs, praticants, bateaux_peche, bateaux_loisir, bateaux_voile
         * date_entree, date_sortie
         */
        if(isset($_POST['city'], $_POST['date_entree'], $_POST['date_sortie'])) {
            $city = htmlspecialchars($_POST['city']);
            $products = htmlspecialchars($_POST['products']);
            $date_entree = date("Y-m-d H:i:s", strtotime(htmlspecialchars($_POST['date_entree'])));
            $date_sortie = date("Y-m-d H:i:s", strtotime(htmlspecialchars($_POST['date_sortie'])));
            if(isset($_POST['spot_id']) && $_POST['spot_id'] != "new" && $_POST['spot_id'] != 0) {
                $spot_id = htmlspecialchars($_POST['spot_id']);
            }
            else {
                if(!empty($_POST['spot']['name']) && !empty($_POST['spot']['lng']) && !empty($_POST['spot']['lat'])) {
                    $name = htmlspecialchars($_POST['spot']['name']);
                    $lng = htmlspecialchars($_POST['spot']['lng']);
                    $lat = htmlspecialchars($_POST['spot']['lat']);
                    Spots::saveDatas($name, $lng, $lat);
                    $spot_id = Spots::getLastSpot();
                }
                else {
                    $_SESSION['error'] = "Veuillez entrer toutes les données nécessaires à la création d'un nouveau spot";
                    exit(header('Location: /informations-surf'));
                }
            }
            if(!empty($city) && !empty($spot_id) && !empty($date_entree) && !empty($date_sortie)) {
                $baigneurs = isset($_POST['baigneurs']) && !empty($_POST['baigneurs']) ? htmlspecialchars($_POST['baigneurs']) : 0;
                $praticants = isset($_POST['praticants']) && !empty($_POST['praticants']) ? htmlspecialchars($_POST['praticants']) : 0;
                $bateaux_peche = isset($_POST['bateaux_peche']) && !empty($_POST['bateaux_peche']) ? htmlspecialchars($_POST['bateaux_peche']) : 0;
                $bateaux_loisir = isset($_POST['bateaux_loisir']) && !empty($_POST['bateaux_loisir']) ? htmlspecialchars($_POST['bateaux_loisir']) : 0;
                $bateaux_voile = isset($_POST['bateaux_voile']) && !empty($_POST['bateaux_voile']) ? htmlspecialchars($_POST['bateaux_voile']) : 0;
                SurfData::saveDatas($city, $spot_id, $products, $date_entree, $date_sortie, $baigneurs, $praticants, $bateaux_peche, $bateaux_loisir, $bateaux_voile);
                exit(header('Location: /home'));
            }
            else {
                $_SESSION['error'] = "Veuillez remplir tous les champs";
                exit(header('Location: /informations-surf'));
            }
        }
    break;
    case "like":
        $uc2 = isset($_GET['uc2']) ? htmlspecialchars($_GET['uc2']) : exit(header('Location: /home'));
        if(!isset($_SESSION['user_id'])) exit(header('Location: /user/login'));

        SpotsLikes::makeLike($uc2);
        exit(header('Location: /spots'));
    break;
    case "user":
        require "user_controller.php";
    break;
    default:
        $title += "Page not found";
        $view = "error_docs/404.php";
    break;
}

?>