<?php

namespace App\Models;

use PDO;
use DateTime;
use \App\Token;
use \Core\View;

/**
 * User model
 *
 * PHP version 7.0
 */
class Item extends \Core\Model
{

    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $sql = 'INSERT INTO items (user_id, name, rating, review, date)
                    VALUES (:user_id, :name, :rating, :review, :date)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':rating', $this->rating, PDO::PARAM_STR);
            $stmt->bindValue(':review', $this->review, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Name
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }

        // review
        if ($this->review == '') {
            $this->errors[] = 'Review is required';
        }

        // date
        if (!DateTime::createFromFormat('Y/m/d', $this->date)) {

            $this->errors[] = 'Date is required in the format Y/m/d';

        }
    }

       /**
     * Find an item model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed Item object if found, false otherwise
     */
    public static function findByUserID($id, $args)
    {

        $orderby = $args["orderby"];


        $sql = 'SELECT * FROM items WHERE user_id = :id ORDER BY '.$orderby.' DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $items = [];

        while ($items[] = $stmt->fetch());

        $items = array_filter($items);



        if ($orderby == 'id') {
            return Item::groupByDates($items);
        } elseif ($orderby == 'rating') {
            return Item::groupByRating($items);
        } elseif ($orderby == 'name') {
            return Item::groupByTitles($items);
        }
        
    }

    protected static function groupByDates($items)
    {

        $groupedByDates = [];

        foreach ($items as $key => $value) {

            $groupedByDates[$value->date][] = $value;

        }

        return $groupedByDates;
    }

    protected static function groupByRating($items)
    {

        $groupedByRating = [];

        foreach ($items as $key => $value) {

            $groupedByRating[$value->rating][] = $value;

        }

        return $groupedByRating;
    }

    protected static function groupByTitles($items)
    {

        $groupedByTitles = [];

        foreach ($items as $key => $value) {

            $groupedByTitles[$value->name][] = $value;

        }

        // print_r ($groupedByTitles);

        $frequencyOfTitles = [];

        foreach ($groupedByTitles as $key => $value) {

            $frequencyOfTitles[$key] = count($groupedByTitles[$key]);

        }

        arsort($frequencyOfTitles);

        $sorted = array_replace($frequencyOfTitles, $groupedByTitles);

        // for each items
        //      for each 
        // if frequencyoftitles[$] i >

        return $sorted;
    }

}
