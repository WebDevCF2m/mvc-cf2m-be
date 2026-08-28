<?php

# 32 ) que fait-on ici ? | déconnexion
if (isset($_GET['disconnect'])) {
    // si déconnexion renvoie true
    if (deconnect()) {
        // redirection
        header("Location: ./");
        exit();
    }

// 33 ) à quoi pourrait servir ce bloc de code ? | on vérifie l'existance de 2
# variables et si elles ne contiennent que du numérique  
}elseif(isset($_GET['postVisible'],$_GET['id'])
    &&ctype_digit($_GET['postVisible'])
    &&ctype_digit($_GET['id'])
    ){
    $postId = (int) $_GET['id'];
    $postVisible = (int) $_GET['postVisible'];

    // 34a ) que fait-on ici ? | On rend un post visible ou invisible
    # et on redirige vers succès
    if (postAdminUpdateVisible($connectPDO, $postId, $postVisible)) {
        header("Location: ./?m=L'article dont l'id est $postId a été modifié");
        exit();
    } else {
        # redirection en cas d'erreur
        header("Location: ./?m=Problème lors de la modification de l'article!");
        exit();
    }

// 34b ) que veut on faire ici ?  Si il existe la variable get nommée  createPost
# On affiche le formulaire
}elseif(isset($_GET['createPost'])){

    // 35) si on a envoyé ... quoi ? | Si on a envoyé le formulaire
    if(isset($_POST['title'],$_POST['content'],$_POST['user_id'])){
        $UserId = (int) $_POST['user_id']; // si erreur => 0
        // 36 ) que fait-on ici ? | Protection des variables
        # trim() : il retire les espaces avant et arrière
        # strip_tags() : il supprime les balises, tags
        # htmlspecialchars() : il transforme les caractères spéciaux 
        # en enitiés html
        $postTitle = htmlspecialchars(strip_tags(trim($_POST['title'])),ENT_QUOTES);
        $postContent = htmlspecialchars(strip_tags(trim($_POST['content'])),ENT_QUOTES);
        // ternaire ! si tableau les valeurs et clefs ne sont pas protégée contre une manipulation externe (injection etc...)
        $idCateg = (isset($_POST['category_id'])&&is_array($_POST['category_id']))? $_POST['category_id'] : [];

    if(!empty($UserId)&&!empty($postTitle)&&!empty($postContent)) {
        //  Pouvoir insérer un article AVEC ses catégories
        $insert = postAdminInsert($connectPDO, $UserId, $postTitle, $postContent, $idCateg);
        if($insert===true){
            $message = "Article inséré dans la DB";
        }
    }
    }

    // 37 )Appel des catégories pour . | pour avoir le choix des catégories 
    # pour le formulaire
    $categoryChoice = getAllCategoryMenu($connectPDO);

    // 38 ) On appel qui? | On appel tous les utilisateurs pour avoir le choix # # des utilisateurs pour le formulaire
    $userChoice = getAllUsers($connectPDO);

    // 39) que fait-on ici ? | on inclut la vue d'insertion de post de l'administration
    include "../view/privateView/privateInsertView.php";

// 40 ) que fait-on ici ?  | Si il existe la variable get 
# nommée 'updatePost' ET Qu'il n'y a que des digits [0-9] dans la variable 
# get (qui est un string par défaut)
}elseif(isset($_GET['updatePost'])&&ctype_digit($_GET['updatePost'])){

    // si on a envoyé le formulaire de modification
    if(isset($_POST['title'])){
        // pas de vérification des variables $_POST au niveau du contrôleur !!! -> TOUTES LES Vérification doivent se trouver dans la fonction ! 
        $post = postAdminUpdate($connectPDO,$_POST); 
        // 41 ) quel type de retour pour avoir une erreur | un string
        if(is_string($post)){
            // affichage de l'erreur
            $message = $post;
        }
        // 42 ) quel type de retour pour avoir un succès | le booléen true
        if($post===true){
            $message = "L'article a bien été modifié<script>
            setTimeout(\"location.href = './';\", 2000);
             </script>";
        }
    }

    $idUpdatePost = (int) $_GET['updatePost'];

    # 43 ) que récupère t'on | on récupère un post (1 ou 0) par son id
    $recupPost = postOneById($connectPDO,$idUpdatePost);

    # 44 ) que type de valeur peut-on récupérer ici, et que fait-on ensuite ? | si
    # c'est un booléen, appel de la page 404
    if(is_bool($recupPost)){
        # récupération du menu pour l'erreur 404
        $recupMenu = getAllCategoryMenu($connectPDO);
        // création de l'erreur pour la 404
        $error = "Cet article n'existe plus";
        // appel de la vue 404
        include_once "../view/publicView/404View.php";
       
    // on a trouvé l'article    
    }else{

    // 45) on appel les ...
    $categoryChoice = getAllCategoryMenu($connectPDO);

    // 46 ) on appel les ...
    $userChoice = getAllUsers($connectPDO);

    // 47 ) on appel la ...
    include "../view/privateView/privateUpdateView.php";
}

// 48 ) que fait-on ici ? 
}elseif(isset($_GET['deletePost'])&&ctype_digit($_GET['deletePost'])){

    $postId = (int) $_GET['deletePost'];

    if(postAdminDeleteById($connectPDO,$postId)){
        header("Location: ./?m=L'article dont l'id est $postId a été supprimé");
        exit();
    }else{
        header("Location: ./?m=Problème lors de la modification de l'article!");
        exit();
    }

    
// 49) quel est cette page  
}else{
    // appel due la méthode (fonction) modèle PostModel pour afficher tous les articles SANS restrictions
    $postAll = postAdminHomepageAll($connectPDO);
    // on compte le nombre d'articles
    $postCount = count($postAll);
    // appel de la vue de l'accueil
    include "../view/privateView/privateHomepageView.php";
}