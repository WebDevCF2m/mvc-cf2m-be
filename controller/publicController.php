<?php
/**
 * public Controller
 */


# 11 ) que récupère-t-on et de quel fichier provient cette fonction ? | la liste
# de toutes la catégories pour récupérer les données du menu, 
# elle vient de model/CategoryModel.php
$recupMenu = getAllCategoryMenu($connectPDO);

# 12 ) cette condition vérifie quoi ? | Si il existe la variable get 
# nommée 'postId' ET Qu'il n'y a que des digits [0-9] dans la variable 
# get (qui est un string par défaut)
if (isset($_GET['postId'])&&ctype_digit($_GET['postId'])) {

    # 13 ) que fait-on à cette ligne ? | On crée une variable locale 
    # nommée $idpost auquel on attribue la valeur de $_GET['postId']
    # convertit en entier

    $idpost = (int) $_GET['postId'];

    # 14 ) que récupère-t-on et de quel fichier provient cette fonction ?
    # | on récupère 1 ou 0 article depuis model/PostModel.php
    $recupPost = postOneById($connectPDO,$idpost, true);

    # 15 ) que reçoit'on en cas d'erreur, et que fait-on ensuite ? | On  reçoit
    # un booléen (false), on crée la variable d'erreur puis on charge la vue 404
    if(is_bool($recupPost)){
        // suite
        $error = "Cet article n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";
       
    // on a trouvé l'article    
    }else{

        // 16 ) qu'appelle-t-on à cette ligne. | on importe la vue affichant
        # l'article détaillé
        require_once('../view/publicView/detailView.php');
}

// 17 ) cette condition vérifie quoi ? | Si il existe la variable get 
# nommée 'categoryId' ET Qu'il n'y a que des digits [0-9] dans la variable 
# get (qui est un string par défaut)
}elseif(isset($_GET['categoryId'])&&ctype_digit($_GET['categoryId'])){   
    
    $id = (int) $_GET['categoryId'];

    $recupcateg=recupCategoryById($connectPDO,$id);

    // 18 )si on récupère quel type de valeur, on fait quoi ? | On  reçoit
    # un booléen (false), on crée la variable d'erreur puis on charge la vue 404
    if(is_bool($recupcateg)){
        // suite
        $error = "Cet catégorie n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";

    }else{
    # 19 ) que récupère-t-on et de quel fichier provient cette fonction ? |
    # on récupère les posts d'une catégorie  depuis model/PostModel.php
        $recupAllPost = postByCategoryId($connectPDO, $id);

        # 20 ) que fait-on ici ? | on compte le nombre de posts récupérés

        $nbPost = count($recupAllPost);

        # 21 ) que fait-on ici ? | on importe la vue
        include_once("../view/publicView/publicCategorieView.php");
}

# 22) cette condition vérifie quoi ? | Si il existe la variable get 
# nommée 'userId' ET Qu'il n'y a que des digits [0-9] dans la variable 
# get (qui est un string par défaut)
}elseif(isset($_GET['userId'])&&ctype_digit($_GET['userId'])){ 

    
    $iduser = (int) $_GET['userId'];
    # 23 ) qu'essaye t'on de récupérer ici, et de quel fichier provient cette fonction ? | On récupère l'utilisateur via son id (1 ou 0 utilisateur) de model/UserModel.php
    $user = getOneUserById($connectPDO,$iduser);

    # 24 ) si on récupère quel type de valeur, on fait quoi ? | si c'est
    # un booléen l'utilisateur n'existe pas/plus, on charge la page 404
    if(is_bool($user)){
        // suite
        $error = "Cet utilisateur n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";
    }else{
        # 25 ) que récupère-t-on et de quel fichier provient cette fonction ? |
        # On crée la variable $recupAllPost qui contient les posts écrits par 
        # cet utilisateur, ça provient du fichier model/PostModel.php
        $recupAllPost = postByUserId($connectPDO,$iduser);

        # 26 ) que fait-on ici ? | idem 20 :  on compte le nombre de posts récupérés 
        $nbPost = count($recupAllPost);

        # 27 ) que fait-on ici ? | on charge la vue
        include_once "../view/publicView/publicUserView.php";
    }

// si on veut se connecter
}elseif(isset($_GET['connect'])){ 

    // si la personne a envoyé le formulaire
    if(isset($_POST['username'],$_POST['userpwd'])){
        // on essaye de connecter l'utilisateur
        $connect = connectUserByUsername($connectPDO,
                                $_POST['username'],
                                $_POST['userpwd']
                            );
        // # 28 ) que reçoit-on en cas d'erreur, et que fait-on ensuite ?
        # | On reçoit un string avec le message d'erreur, on met cette erreur 
        # dans la variable $message
        if(is_string($connect)) {
            $message = $connect;
        // #29) sinon, que fait-on ? | redirection vers l'accueil, l'exit quitte
        # le script (pour éviter un bug sur certains serveurs)
        }else{
            header("Location: ./");
            exit();
        }
    }

    # 30 ) que fait-on ici ? | Inclusion de la vue
    include "../view/publicView/connectView.php";

# 31 ) sinon, où sommes nous ? | Nous sommes sur la page d'accueil
}else{
    # homepage's datas from MODEL
    $recupAllPost = postHomepageAll($connectPDO);

    # Post count
    $nbPost = count($recupAllPost);


    # homepage's view from VIEW
    require "../view/publicView/publicHomepageView.php";
}