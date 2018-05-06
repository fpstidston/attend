<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Item;
use \App\Models\Award;
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
        $items = Item::getItems('id');

        $stat = Item::getStat($items);

        View::renderTemplate('Items/index.html',[
            'items' => $items,
            'stat' => $stat
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

            $items = Item::getItems('id');

            if (Award::assignAwards($items)) {
                Flash::addMessage('Award recieved!', Flash::INFO);
            };

            $this->redirect('/items/index');

        } else {

            Flash::addMessage('Save unsuccessful, please try again', Flash::WARNING);

            View::renderTemplate('Items/new.html', [
                'item' => $item
            ]);

        }
    }

    public function ratingAction() {
        $items = Item::getItems('rating');

        $stat = Item::getStat($items);

        View::renderTemplate('Items/rating.html',[
            'items' => $items,
            'stat' => $stat
        ]);
    }

    public function titlesAction() {
        $items = Item::getItems('name');

        $stat = Item::getStat($items);

        View::renderTemplate('Items/title.html',[
            'items' => $items,
            'stat' => $stat
        ]);
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
