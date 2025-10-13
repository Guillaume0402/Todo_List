<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Auth;
use App\Models\ListModel;
use App\Models\CategoryModel;
use App\Models\ItemModel;
use Exception;

/**
 * Contrôleur des listes et items
 */

class ListController extends BaseController
{
    private ListModel $listModel;
    private CategoryModel $categoryModel;
    private ItemModel $itemModel;

    /**
     * Initialise les modèles de données nécessaires
     */
    public function __construct()
    {
        parent::__construct();
        $this->listModel = new ListModel();
        $this->categoryModel = new CategoryModel();
        $this->itemModel = new ItemModel();
    }

    /**
     * Affiche toutes les listes de l'utilisateur
     */
    public function index(): void
    {
        Auth::requireAuth();

        $userId = Auth::getUserId();
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;

        $lists = $this->listModel->getByUserId($userId, $categoryId);
        // Charger les items pour chaque liste pour simplifier la vue
        foreach ($lists as &$list) {
            $list['items'] = $this->itemModel->getByListId((int)($list['id'] ?? 0));
        }
        $categories = $this->categoryModel->getAll();

        $this->render('lists/index', [
            'lists' => $lists,
            'categories' => $categories,
            'selectedCategoryId' => $categoryId
        ]);
    }

    /**
     * Affiche le formulaire d'ajout/modification d'une liste
     */
    public function form(): void
    {
        Auth::requireAuth();

        $listId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $editMode = $listId !== null;

        // Récupération des données
        $list = $editMode ? $this->listModel->getById($listId) : ['title' => '', 'category_id' => ''];
        $categories = $this->categoryModel->getAll();
        $items = $editMode ? $this->itemModel->getByListId($listId) : [];
        $flashMessages = $this->getFlashMessages();

        $this->render('lists/form', [
            'list' => $list,
            'categories' => $categories,
            'items' => $items,
            'editMode' => $editMode,
            'flashMessages' => $flashMessages
        ]);
    }

    /**
     * Sauvegarde une liste
     */
    public function saveList(): void
    {
        Auth::requireAuth();

        try {
            $data = $this->validatePostRequest(['title', 'category_id']);

            $listId = isset($_GET['id']) ? (int)$_GET['id'] : null;
            $userId = Auth::getUserId();

            $result = $this->listModel->save($data['title'], $userId, (int)$data['category_id'], $listId);

            if ($result) {
                if ($listId) {
                    $this->addFlashMessage('success', 'La liste a bien été mise à jour');
                    $this->redirect(\AppConfig::BASE_PATH . '?r=lists/form&id=' . $listId);
                } else {
                    $this->redirect(\AppConfig::BASE_PATH . '?r=lists/form&id=' . $result);
                }
            } else {
                $this->addFlashMessage('error', 'La liste n\'a pas été enregistrée');
                $this->redirect($_SERVER['REQUEST_URI']);
            }
        } catch (Exception $e) {
            $this->addFlashMessage('error', $e->getMessage());
            $this->redirect($_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Sauvegarde un item
     */
    public function saveItem(): void
    {
        Auth::requireAuth();

        try {
            $data = $this->validatePostRequest(['name']);

            if (!isset($_GET['id'])) {
                throw new Exception('ID de liste requis');
            }

            $listId = (int)$_GET['id'];
            $itemId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;

            $this->itemModel->save($data['name'], $listId, false, $itemId);

            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/form&id=' . $listId);
        } catch (Exception $e) {
            $this->addFlashMessage('error', $e->getMessage());
            $this->redirect($_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Supprime un item
     */
    public function deleteItem(): void
    {
        Auth::requireAuth();

        if (!isset($_GET['item_id']) || !isset($_GET['id'])) {
            $this->addFlashMessage('error', 'Paramètres manquants');
            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/index');
        }

        $itemId = (int)$_GET['item_id'];
        $listId = (int)$_GET['id'];

        $this->itemModel->deleteById($itemId);
        $this->redirect(\AppConfig::BASE_PATH . '?r=lists/form&id=' . $listId);
    }

    /**
     * Met à jour le statut d'un item
     */
    public function updateItemStatus(): void
    {
        Auth::requireAuth();

        if (!isset($_GET['item_id']) || !isset($_GET['status']) || !isset($_GET['id'])) {
            $this->addFlashMessage('error', 'Paramètres manquants');
            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/index');
        }

        $itemId = (int)$_GET['item_id'];
        $status = (bool)$_GET['status'];
        $listId = (int)$_GET['id'];

        $this->itemModel->updateStatus($itemId, $status);

        if (isset($_GET['redirect']) && $_GET['redirect'] === 'list') {
            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/index');
        } else {
            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/form&id=' . $listId);
        }
    }
}
