<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Award;
use \App\Auth;


/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class Awards extends Authenticated
{

    /**
     * Items index
     *
     * @return void
     */
    public function indexAction()
    {

        $awardIDs = Award::getUserAwardIDs($_SESSION['user_id']);

        if (!empty($awardIDs)) {
            $awards = Award::getAwardsDetails($awardIDs);
        } else {
            $awards = [];
        };

        View::renderTemplate('Awards/index.html',[
            'awards' => $awards
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
