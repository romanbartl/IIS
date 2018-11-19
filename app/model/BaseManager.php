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
        INTERPRET_ID = 'idInterpret',
        INTERPRET_NAME = 'name',
        INTERPRET_LABEL = 'label',
        INTERPRET_FOUNDED = 'founded';

    const
        TABLE_ALBUM = 'Album',
        ALBUM_COLUMN_ID = 'idAlbum',
        ALBUM_COLUMN_NAME = 'name',
        ALBUM_COLUMN_LABEL = 'label',
        ALBUM_COLUMN_INTERPRET_ID = 'idInterpret',
        ALBUM_COLUMN_RELEASE = 'release';

    const
        TABLE_GENRE = 'Genre',
        GENRE_COLUMN_ID = 'idGenre',
        GENRE_COLUMN_NAME = 'name';

    const
        TABLE_ALBUM_GENRE = 'Album_has_Genre',
        ALBUM_GENRE_ALBUM_ID = 'idAlbum',
        ALBUM_GENRE_GENRE_ID = 'idGenre';

    const
        TABLE_USER_INTERPRET = 'User_has_Interpret',
        USER_INTERPRET_USER_ID = 'idUSer',
        USER_INTERPRET_INTERPRET_ID = 'idInterpret';

    const
        TABLE_MEMBER = 'Member',
        MEMBER_COLUMN_ID = 'idMember',
        MEMBER_COLUMN_NAME = 'name',
        MEMBER_COLUMN_SURNAME = 'surname',
        MEMBER_COLUMN_BIRTH ='birth';

    const
        TABLE_INTERPRET_MEMBER = 'Interpret_has_Member',
        INTERPRET_MEMBER_INTERPRET_ID = 'idInterpret',
        INTERPRET_MEMBER_MEMBER_ID = 'idMember';
}