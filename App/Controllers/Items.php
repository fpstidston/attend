<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Item;
use \App\Flash;
use \App\Auth;


/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class Items extends Authenticated
{

    /**
     * Items index
     *
     * @return void
     */
    public function indexAction()
    {
        $items = $this->getItems('id');


        View::renderTemplate('Items/index.html',[
            'items' => $items
        ]);
    }

    /**
     * Show the add a new item page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Items/new.html',[
            'user' => Auth::getUser()
        ]);
    }

    /**
     * Create a new item
     *
     * @return void
     */
    public function createAction()
    {
        $item = new Item($_POST);

        if ($item->save()) {

            Flash::addMessage('Item added');

            $this->redirect('/items/index');

        } else {

            Flash::addMessage('Save unsuccessful, please try again', Flash::WARNING);

            View::renderTemplate('Items/new.html', [
                'item' => $item
            ]);

        }
    }

    public function ratingAction() {
        $items = $this->getItems('rating');


        View::renderTemplate('Items/rating.html',[
            'items' => $items
        ]);
    }

    public function titlesAction() {
        $items = $this->getItems('name');

        // print_r($items) ;

        View::renderTemplate('Items/title.html',[
            'items' => $items
        ]);
    }

    public static function getItems($orderby)
    {
        if (isset($_SESSION['user_id'])) {

            return Item::findByUserID($_SESSION['user_id'],[
                "orderby" => $orderby
            ]);

        }
    }

    /**
     * Show an item
     *
     * @return void
     */
    public function showAction()
    {
        View::renderTemplate('Items/show.html');
    }
}
