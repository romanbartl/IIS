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
        return $this->database->query('SELECT g.name, g.idGenre FROM Genre AS g LEFT JOIN Album_has_Genre AS ahg ON ahg.idGenre = g.idGenre WHERE ahg.idAlbum = ?', $albumId);
    }

    public function getAllGenres() {
        return $this->database->table(self::TABLE_GENRE)
            ->fetchAll();
    }


    public function connectGenreWithAlbum($idAlbum, $idGenre) {
        try {
            $this->database->table(self::TABLE_ALBUM_GENRE)
                ->insert([
                    self::ALBUM_GENRE_ALBUM_ID => $idAlbum,
                    self::ALBUM_GENRE_GENRE_ID => $idGenre
                ]);
        }
        catch (Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }


    public function addGenre($name) {
        $this->database->table(self::TABLE_GENRE)
            ->insert([
                self::GENRE_COLUMN_NAME => $name
            ]);
    }


    public function deleteGenreFromAlbum($idAlbum, $idGenre) {
        $this->database->table(self::TABLE_ALBUM_GENRE)
            ->where(self::ALBUM_GENRE_ALBUM_ID, $idAlbum)
            ->where(self::ALBUM_GENRE_GENRE_ID, $idGenre)
            ->delete();
    }
}