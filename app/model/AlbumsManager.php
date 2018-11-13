<?php
/**
 * Created by PhpStorm.
 * User: Roxem Wincle
 * Date: 14.10.2018
 * Time: 12:52
 */

namespace App\Model;

use Nette;

class AlbumsManager extends BaseManager
{

    /**
     * @var Nette\Database\Context
     */
    private $database;


    /**
     * @var GenreManager
     */
    private $genreManager;


    /**
     * AlbumsManager constructor.
     * @param Nette\Database\Context $database
     * @param GenreManager $genreManager
     */
    public function __construct(Nette\Database\Context $database, GenreManager $genreManager)
    {
        $this->database = $database;
        $this->genreManager = $genreManager;
    }


    /**
     * @param $interpretId
     * @return array
     */
    public function getAlbumsByInterpretId($interpretId)
    {
        $returnAlbums = array();
        $albums = $this->database->table(self::TABLE_ALBUM)->where(self::ALBUM_COLUMN_INTERPRET_ID, $interpretId)->order(self::ALBUM_COLUMN_RELEASE . ' DESC');

        foreach ($albums as $album) {
            $genres = $this->genreManager->getGenresByAlbumId($album->idAlbum);
            $returnAlbums[] = (object) ['idAlbum' => $album->idAlbum,
                'name' => $album->name, 'label' => $album->label, 'release' => $album->release, 'genres' => $genres];
        }

        return $returnAlbums;
    }


    public function getAlbum($idAlbum) {
        return $this->database->table(self::TABLE_ALBUM)
            ->where(self::ALBUM_COLUMN_ID, $idAlbum)->fetch();
    }


    public function editAlbum($values) {
        $this->database->table(self::TABLE_ALBUM)
            ->where(self::ALBUM_COLUMN_ID, $values->idAlbum)
            ->update([
                self::ALBUM_COLUMN_NAME => $values->name,
                self::ALBUM_COLUMN_LABEL => $values->label,
                self::ALBUM_COLUMN_RELEASE => $values->release
            ]);
    }


    public function deleteAlbum($idAlbum) {
        $this->database->table(self::TABLE_ALBUM_GENRE)
            ->where(self::ALBUM_GENRE_ALBUM_ID, $idAlbum)
            ->delete();
        $this->database->table(self::TABLE_ALBUM)
            ->where(self::ALBUM_COLUMN_ID, $idAlbum)
            ->delete();
    }


    public function addAlbum($values) {
        $this->database->table(self::TABLE_ALBUM)
            ->insert([
                self::ALBUM_COLUMN_NAME => $values->name,
                self::ALBUM_COLUMN_LABEL => $values->label,
                self::ALBUM_COLUMN_RELEASE => $values->release,
                self::ALBUM_COLUMN_INTERPRET_ID => $values->idInterpret
            ]);
    }
}