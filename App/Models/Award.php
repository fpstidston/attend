<?php

namespace App\Models;

use PDO;
use DateTime;
use \Core\View;

/**
 * User model
 *
 * PHP version 7.0
 */
class Award extends \Core\Model
{

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

    protected static function getAvailableAwards()
    {
        $sql = 'SELECT id, criteria FROM awards';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        while ($array[] = $stmt->fetch());

        return array_filter($array);
    }

    public static function getUserAwardIDs($user_id)
    {

        $sql = 'SELECT id FROM awarded WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        while ($array[] = $stmt->fetch()) {
            $array[count($array)-1] = $array[count($array)-1]->id;
        };

        return array_filter($array);

    }

        public static function getAwardsDetails($ids)
    {

        $in = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "SELECT * FROM awards WHERE id IN ($in)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute($ids);

        return $stmt->fetchAll();

    }

    protected static function save($id, $user_id) {

        echo " Saving ".$id." ".$user_id.". ";

        // send email

        $sql = 'INSERT INTO awarded (id, user_id) VALUES (:id, :user_id)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);

        return $stmt->execute();

    }

    public static function assignAwards($items)
    {
        echo "Assigning Awards";

        $awardsGiven = false;

        $availableAwards = self::getAvailableAwards();
        $usersAwards = (array) self::getUserAwardIDs($_SESSION['user_id']);

        $itemsByName = Item::getItems('name');
        $statsByName = Item::getStat($itemsByName);


        foreach ($availableAwards as $index => $object) {
            if ($object->criteria == 'subject-2') {
                 foreach ($statsByName['counts'] as $subject => $count) {
                     if (($count > 1) and !in_array($object->id,$usersAwards)) {
                        $awardsGiven = true;
                        self::save($object->id, $_SESSION['user_id']);
                        break;
                     }
                 }        
            } elseif ($object->criteria == 'subject-10') {
                 foreach ($statsByName['counts'] as $subject => $count) {
                     if (($count > 9) and !in_array($object->id,$usersAwards)) {
                        $awardsGiven = true;
                        self::save($object->id, $_SESSION['user_id']);
                        break;
                     }
                 }        
            }

        }

        return $awardsGiven;

    }

}
