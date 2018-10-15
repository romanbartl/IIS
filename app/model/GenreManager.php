<?php
/**
 * Created by PhpStorm.
 * User: Roxem Wincle
 * Date: 15.10.2018
 * Time: 9:29
 */

namespace App\Model;

use Nette;

class GenreManager extends BaseManager
{
    /**
     * @var Nette\Database\Context
     */
    private $database;


    /**
     * AlbumsManager constructor.
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    /**
     * @param $albumId
     * @return Nette\Database\ResultSet
     */
    public function getGenresByAlbumId($albumId)
    {
        return $this->database->query('SELECT g.name FROM Genre AS g LEFT JOIN Album_has_Genre AS ahg ON ahg.idGenre = g.idGenre WHERE ahg.idAlbum = ?', $albumId);
    }
}