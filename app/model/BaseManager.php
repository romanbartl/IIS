<?php

namespace App\Model;

use Nette;

class BaseManager
{
    const
        TABLE_USER = 'User',
        USER_COLUMN_ID = 'idUser',
        USER_COLUMN_NAME = 'name',
        USER_COLUMN_SURNAME = 'surname',
        USER_COLUMN_BIRTH = 'birth',
        USER_COLUMN_ADDRESS = 'address',
        USER_COLUMN_EMAIL = 'email',
        USER_COLUMN_PASSWORD_HASH = 'password',
        USER_COLUMN_ADMIN = 'admin',
        USER_COLUMN_CITY = 'idCity';

    const
        TABLE_INTERPRET = 'Interpret',
        INTERPRET_ID = 'idInterpret';

    const
        TABLE_ALBUM = 'Album',
        ALBUM_COLUMN_INTERPRET_ID = 'idInterpret',
        ALBUM_COLUMN_RELEASE = 'release';

    const
        TABLE_GENRE = 'Genre';

    const
        TABLE_ALBUM_GENRE = 'Album_has_Genre',
        ALBUM_GENRE_ALBUM_ID = 'idAlbum',
        ALBUM_GENRE_GENRE_ID = 'idGenre';
}