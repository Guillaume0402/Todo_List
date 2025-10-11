<?php
//fonction pour récupérer les listes d'un utilisateur
function getListsByUserId(PDO $pdo, int $userId, ?int $categoryId = null): array
{
    $sql = <<<SQL
        SELECT l.*,
               c.name AS category_name,
               c.icon AS category_icon
          FROM lists l
          LEFT JOIN categories c ON c.id = l.category_id
         WHERE l.user_id = :user_id
    SQL;

    if ($categoryId !== null) {
        $sql .= " AND l.category_id = :category_id";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

    if ($categoryId !== null) {
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//fonction pour récupérer une liste par son id 
function getListById(PDO $pdo, int $id): array|bool
{
    // on prépare la requête pour récupérer une liste par son id
    $query = $pdo->prepare('SELECT * FROM lists WHERE id = :id');
    // on exécute la requête en passant le paramètre id à la place du :id dans la requête SQL
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    // on exécute la requête
    $query->execute();
    // on récupère le résultat de la requête sous forme de tableau associatif (FETCH_ASSOC)
    return $query->fetch(PDO::FETCH_ASSOC);
}



// fonction qui va gérer l'ajout ou la modification d'une liste
function saveList(PDO $pdo, string $title, int $userId, int $categoryId, ?int $id = null): int|bool
{   // on vérifie si on a un id dans l'url
    if ($id) {
        // update
        $query = $pdo->prepare("UPDATE list SET title = :title, category_id = :category_id, user_id = :user_id
                                        WHERE id = :id");
        // on exécute la requête en passant le paramètre id à la place du :id dans la requête SQL
        $query->bindValue(':id', $id, PDO::PARAM_INT);
    } else {
        // insert
        $query = $pdo->prepare("INSERT INTO list (title, category_id, user_id)
                                            VALUES (:title, :category_id, :user_id)");
    }
    // on exécute la requête en passant les paramètres title, category_id et user_id à la place des :title, :category_id et :user_id dans la requête SQL
    $query->bindValue(':title', $title, PDO::PARAM_STR);
    $query->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);

    // on exécute la requête
    $res = $query->execute();
    // on vérifie si la liste a bien été enregistrée
    if ($res) {
        // on récupère l'id de la liste
        if ($id) {
            // si on a un id dans l'url on retourne l'id
            return $id;
            // on redirige l'utilisateur vers la page de la liste en cours d'édition
        } else {
            // si on a pas d'id dans l'url on retourne le dernier id inséré
            return $pdo->lastInsertId();
        }
        // on retourne true si la liste a bien été enregistrée
    } else {
        // on retourne false si la liste n'a pas été enregistrée
        return false;
    }
}

// fonction qui va gérer l'ajout ou la modification d'un item
function saveListItem(PDO $pdo, string $name, int $list_id, bool $status = false, ?int $id = null): int|bool
{
    if ($id) {
        //update 
        $query = $pdo->prepare("UPDATE item SET name = :name, list_id = :list_id, status = :status
                                        WHERE id = :id");
        // on exécute la requête en passant le paramètre id à la place du :id dans la requête SQL
        $query->bindValue(':id', $id, PDO::PARAM_INT);
    } else {
        //insert 
        $query = $pdo->prepare("INSERT INTO item (name, list_id, status)
                                            VALUES (:name, :list_id, :status)");
    }
    // on exécute la requête en passant les paramètres name, list_id et status à la place des :name, :list_id et :status dans la requête SQL
    $query->bindValue(':name', $name, PDO::PARAM_STR);
    $query->bindValue(':list_id', $list_id, PDO::PARAM_INT);
    $query->bindValue(':status', $status, PDO::PARAM_BOOL);
    // on exécute la requête
    return $query->execute();
}

function getListItems(PDO $pdo, int $listId): array
{
    $stmt = $pdo->prepare("
        SELECT id, list_id, name, is_done AS status, position, due_date, created_at
        FROM items
        WHERE list_id = :list_id
        ORDER BY (position IS NULL), position, id
    ");
    $stmt->bindValue(':list_id', $listId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function deleteListItemById(PDO $pdo, int $id): bool
{
    // on prépare la requête pour supprimer un item par son id
    $query = $pdo->prepare('DELETE FROM items WHERE id = :id');
    // on exécute la requête en passant le paramètre id à la place du :id dans la requête SQL
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    // on exécute la requête
    return $query->execute();
}

// fonction pour mettre à jour le statut d'un item
function updateStatusListItem(PDO $pdo, int $id, bool $status): bool
{
    // on prépare la requête pour mettre à jour le statut d'un item
    $query = $pdo->prepare('UPDATE item SET status = :status WHERE id = :id');
    // on exécute la requête en passant les paramètres id à la place des :id  dans la requête SQL
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    // on exécute la requête en passant le paramètre status à la place du :status dans la requête SQL
    $query->bindValue(':status', $status, PDO::PARAM_BOOL);

    return $query->execute();
}
