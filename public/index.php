<?php
# 1 ) Contrôleur frontal

# 2) A - Lors du premier session_start, création d'un cookie de session local
#  nommé par défaut PHPSESSID et création d'un fichier temporaire côté serveur 
# (ici dans le dossier local C:\wamp64\tmp commençant par ses_{clef scrète})
# Sécurisé : toutes les informations ne se trouve QUE côté serveur
# B - Si une session est en cours et est valide, on la continue
session_start();

# 3) On charge 1x de manière obligatoire le fichier contenant 
# les constantes de connexion à PDO
# ! non existant dans l'arboresence (.gitignore)
require_once "../config.php";

# 4) Chargement des modèles représentant les tables (sans celles de jointure)
# de la base de donnée. En procédural elles contiennent en réalité des fonctions
require_once "../model/PostModel.php";# table post
require_once "../model/CategoryModel.php";# table category
require_once "../model/UserModel.php";# table user


# 5 ) Nous essayons de lancer l'instanciation de la classe PDO avec les
# constantes contenues dans config.php, pour créer une connexion SQL
try {
    $connectPDO = new PDO(
        DB_TYPE.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset='.DB_CHARSET,
        DB_LOGIN,
        DB_PWD
    );
        # 6 ) activation du mode erreur pour les requêtes, 
        # sinon risque de pages blanches, chargé par défaut depuis PHP 8.0
        $connectPDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        # 7 ) Par défaut Lors d'une récupération de resultat et du traitement 
        # d'un résultat par un fetch ou fetchAll,
        # nous renvoie un tableau associatif 
        $connectPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

    
# 8 ) le catch est appelé dans quel cas | Erreur dans le try
}catch(Exception $e){
    # 9 ) et que fait-il? | Il arrête le code à cette ligne et nous affiche 
    # l'instanciation de Exception (=== $e = new Exception()) et la méthode 
    # publique getMessage() qui est récupéré via le try
    die($e->getMessage());

}


# Router

// ici sont redirigés les administrateurs connectés.
if(isset($_SESSION['myID'])&&$_SESSION['myID']==session_id()){
    require_once "../controller/privateController.php";
  
// zone publique, pour les visiteurs
}else{
    require_once "../controller/publicController.php";
}


# 10 ) | Bonne pratique, fermeture de la connexion
$connectPDO = null;