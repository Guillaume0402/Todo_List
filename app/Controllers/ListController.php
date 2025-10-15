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

        $userId     = Auth::getUserId();
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;

        $lists = $this->listModel->getByUserId($userId, $categoryId);

        foreach ($lists as &$list) {
            $list['items'] = $this->itemModel->getByListId((int)($list['id'] ?? 0));
        }
        $categories = $this->categoryModel->getAll();

        // Log vue index (pratique pour de l’audit léger)
        $this->log('list.index_view', [
            'entity'  => 'list',
            'status'  => 'ok',
            'details' => [
                'category_filter' => $categoryId,
                'lists_count'     => is_countable($lists) ? count($lists) : 0,
            ],
        ]);

        $this->render('lists/index', [
            'lists'              => $lists,
            'categories'         => $categories,
            'selectedCategoryId' => $categoryId
        ]);
    }

    /**
     * Affiche le formulaire d'ajout/modification d'une liste
     */
    public function form(): void
    {
        Auth::requireAuth();

        $listId   = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $editMode = $listId !== null;

        $list       = $editMode ? $this->listModel->getById($listId) : ['title' => '', 'category_id' => ''];
        $categories = $this->categoryModel->getAll();
        $items      = $editMode ? $this->itemModel->getByListId($listId) : [];
        $flashMessages = $this->getFlashMessages();

        // Log vue formulaire
        $this->log('list.form_view', [
            'entity'    => 'list',
            'entity_id' => $editMode ? (int)$listId : null,
            'status'    => 'ok',
            'details'   => ['mode' => $editMode ? 'edit' : 'create']
        ]);

        $this->render('lists/form', [
            'list'         => $list,
            'categories'   => $categories,
            'items'        => $items,
            'editMode'     => $editMode,
            'flashMessages' => $flashMessages
        ]);
    }

    /**
     * Sauvegarde une liste (create/update)
     */
    public function saveList(): void
    {
        Auth::requireAuth();

        try {
            $data   = $this->validatePostRequest(['title', 'category_id']);
            $listId = isset($_GET['id']) ? (int)$_GET['id'] : null;
            $userId = Auth::getUserId();

            $result = $this->listModel->save($data['title'], $userId, (int)$data['category_id'], $listId);

            if ($result) {
                if ($listId) {
                    // update
                    $this->log('list.update_success', [
                        'entity'    => 'list',
                        'entity_id' => (int)$listId,
                        'status'    => 'ok'
                    ]);
                    $this->addFlashMessage('success', 'La liste a bien été mise à jour');
                    $this->redirect(\AppConfig::BASE_PATH . '?r=lists/form&id=' . $listId);
                } else {
                    // create (id retourné par $result)
                    $this->log('list.create_success', [
                        'entity'    => 'list',
                        'entity_id' => (int)$result,
                        'status'    => 'ok'
                    ]);
                    $this->redirect(\AppConfig::BASE_PATH . '?r=lists/form&id=' . $result);
                }
            } else {
                $this->log('list.save_failed', [
                    'entity'  => 'list',
                    'status'  => 'error',
                    'message' => "Save renvoie 'false'"
                ]);
                $this->addFlashMessage('error', 'La liste n\'a pas été enregistrée');
                $this->redirect($_SERVER['REQUEST_URI']);
            }
        } catch (Exception $e) {
            $this->log('list.save_failed', [
                'entity'  => 'list',
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
            $this->addFlashMessage('error', $e->getMessage());
            $this->redirect($_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Sauvegarde un item (create/update)
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

            // On tente de récupérer un éventuel id retourné par le modèle (si dispo),
            // sinon on se rabat sur $itemId pour l’update.
            $ret = $this->itemModel->save($data['name'], $listId, false, $itemId);
            $effectiveId = $itemId ?? (is_numeric($ret) ? (int)$ret : null);

            $this->log($itemId ? 'item.update_success' : 'item.create_success', [
                'entity'    => 'item',
                'entity_id' => $effectiveId,
                'status'    => 'ok',
                'details'   => [
                    'list_id'   => $listId,
                    'item_name' => $data['name']
                ]
            ]);

            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/form&id=' . $listId);
        } catch (Exception $e) {
            $this->log('item.save_failed', [
                'entity'  => 'item',
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
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
            $this->log('item.delete_failed', [
                'entity'  => 'item',
                'status'  => 'error',
                'message' => 'Paramètres manquants'
            ]);
            $this->addFlashMessage('error', 'Paramètres manquants');
            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/index');
        }

        $itemId = (int)$_GET['item_id'];
        $listId = (int)$_GET['id'];

        try {
            $this->itemModel->deleteById($itemId);

            $this->log('item.delete_success', [
                'entity'    => 'item',
                'entity_id' => $itemId,
                'status'    => 'ok',
                'details'   => ['list_id' => $listId]
            ]);

            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/form&id=' . $listId);
        } catch (Exception $e) {
            $this->log('item.delete_failed', [
                'entity'  => 'item',
                'status'  => 'error',
                'message' => $e->getMessage(),
                'details' => ['item_id' => $itemId, 'list_id' => $listId]
            ]);
            $this->addFlashMessage('error', $e->getMessage());
            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/index');
        }
    }

    /**
     * Met à jour le statut d'un item
     */
    public function updateItemStatus(): void
    {
        Auth::requireAuth();

        $isAjax = (
            (isset($_GET['ajax']) && (int)$_GET['ajax'] === 1)
            || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/json') !== false)
        );

        if (!isset($_GET['item_id']) || !isset($_GET['status']) || !isset($_GET['id'])) {
            $this->log('item.toggle_failed', [
                'entity'  => 'item',
                'status'  => 'error',
                'message' => 'Paramètres manquants'
            ]);

            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Paramètres manquants'], 400);
            }
            $this->addFlashMessage('error', 'Paramètres manquants');
            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/index');
        }

        $itemId = (int)$_GET['item_id'];
        $status = ((int)$_GET['status']) === 1; // true/false
        $listId = (int)$_GET['id'];

        try {
            $this->itemModel->updateStatus($itemId, $status);

            $this->log('item.toggle_status', [
                'entity'    => 'item',
                'entity_id' => $itemId,
                'status'    => 'ok',
                'details'   => [
                    'list_id' => $listId,
                    'to'      => $status ? 1 : 0
                ]
            ]);

            if ($isAjax) {
                $this->json([
                    'success' => true,
                    'status'  => $status ? 1 : 0,
                    'item_id' => $itemId,
                    'list_id' => $listId,
                ]);
            }

            if (isset($_GET['redirect']) && $_GET['redirect'] === 'list') {
                $this->redirect(\AppConfig::BASE_PATH . '?r=lists/index');
            } else {
                $this->redirect(\AppConfig::BASE_PATH . '?r=lists/form&id=' . $listId);
            }
        } catch (Exception $e) {
            $this->log('item.toggle_failed', [
                'entity'  => 'item',
                'status'  => 'error',
                'message' => $e->getMessage(),
                'details' => ['item_id' => $itemId, 'list_id' => $listId]
            ]);

            if ($isAjax) {
                $this->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            $this->addFlashMessage('error', $e->getMessage());
            $this->redirect(\AppConfig::BASE_PATH . '?r=lists/index');
        }
    }
}
