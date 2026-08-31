<?php
# 50 ) que fait-on ici ? on récupère l'id et le title de toutes les catégories ordonnées par id ascendant
function getAllCategoryMenu(PDO $db): array|Exception {
    $sql ="SELECT id, title FROM category ORDER BY id ASC";
    try{
        $query=$db->query($sql);
    }catch(Exception $e){
        return $e;
    }
    $bp = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $bp;
}

# 51 ) que fait-on ici ? | on récupère la catégorie via son id sous
# forme de tableau, si il n'existe pas, le fetch vaudra false
function recupCategoryById(PDO $db,int $id):array|false
{
    $recup = "SELECT * FROM category where id=?";
    $prepare = $db -> prepare($recup);
    try{
        $prepare->execute([$id]);
    }catch(Exception $e){
        die($e->getMessage());
    }
    $bp = $prepare->fetch(PDO::FETCH_ASSOC);
    $prepare->closeCursor();
    return $bp;
}

