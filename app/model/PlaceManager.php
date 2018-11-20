<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 20.11.2018
 * Time: 11:12
 */

namespace App\Model;


use Nette\Database\Context;

class PlaceManager extends BaseManager
{
    /**
     * @var Context
     */
    private $database;


    /**
     * PlaceManager constructor.
     * @param Context $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }


    public function addNewPlace($values) {
        $this->database->table(self::TABLE_PLACE)
            ->insert([
                self::PLACE_COLUMN_NAME => $values->name,
                self::PLACE_COLUMN_ADDRESS => $values->address,
                self::PLACE_COLUMN_GPS_LAT => $values->gpsLat,
                self::PLACE_COLUMN_GPS_LNG => $values->gpsLng,
                self::PLACE_COLUMN_ZIP_CODE => $values->zipCode,
                self::PLACE_COLUMN_CITY => $values->city
            ]);
    }


    public function getAllPlaces() {
        return $this->database->table(self::TABLE_PLACE)->fetchAll();
    }


    public function getPlaceById($idPlace) {
        return $this->database->table(self::TABLE_PLACE)
            ->where(self::PLACE_COLUMN_PLACE_ID, $idPlace)
            ->fetch();
    }


    public function updatePlace($values) {
        $this->database->table(self::TABLE_PLACE)
            ->where(self::PLACE_COLUMN_PLACE_ID, $values->idPlace)
            ->update([
                self::PLACE_COLUMN_NAME => $values->name,
                self::PLACE_COLUMN_ADDRESS => $values->address,
                self::PLACE_COLUMN_GPS_LAT => $values->gpsLat,
                self::PLACE_COLUMN_GPS_LNG => $values->gpsLng,
                self::PLACE_COLUMN_ZIP_CODE => $values->zipCode,
                self::PLACE_COLUMN_CITY => $values->city
            ]);
    }
}