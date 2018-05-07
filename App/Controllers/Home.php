<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Item;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
    	$items = (array) Item::getItems('id');

    	if (isset($_SESSION['user_id']) and (count($items)  > 2))	 {

    		$itemsByDate = Item::getItems('id');
	        $dateStats = Item::getStat($itemsByDate);
	        $insight[] = Item::getBestDay($dateStats['totals']);

	        $itemsBySubject = Item::getItems('name');
	        $subjectStats = Item::getStat($itemsBySubject);
	        $insight[] = Item::getBestSubject($subjectStats['averages']);

	        $insight[] = Item::getRegularestSubject($subjectStats['counts'],$subjectStats['averages']);

	        View::renderTemplate('Home/index.html',[
        		'insights' => $insight
        	]);

    	} else {

    		View::renderTemplate('Home/index.html');

    	}

        
    }
}
