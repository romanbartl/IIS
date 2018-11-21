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
        USER_INTERPRET_INTERPRET_ID = 'idInterpret',
        USER_INTERPRET_IS_NEW = 'isNew';

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

    const
        TABLE_PLACE = 'Place',
        PLACE_COLUMN_PLACE_ID = 'idPlace',
        PLACE_COLUMN_NAME = 'name',
        PLACE_COLUMN_ADDRESS = 'address',
        PLACE_COLUMN_GPS_LAT = 'gpsLat',
        PLACE_COLUMN_GPS_LNG = 'gpsLng',
        PLACE_COLUMN_ZIP_CODE = 'zipCode',
        PLACE_COLUMN_CITY = 'city';

    const
        TABLE_CONCERT = 'Concert',
        CONCERT_COLUMN_CONCERT_ID = 'idConcert',
        CONCERT_COLUMN_NAME = 'name',
        CONCERT_COLUMN_DATE = 'date',
        CONCERT_COLUMN_CAPACITY = 'capacity',
        CONCERT_COLUMN_INFO = 'info',
        CONCERT_COLUMN_PLACE_ID = 'idPlace';

    const
        TABLE_CONCERT_INTERPRET = 'Concert_has_Interpret',
        CONCERT_INTERPRET_CONCERT_ID = 'idConcert',
        CONCERT_INTERPRET_INTERPRET_ID = 'idInterpret',
        CONCERT_INTERPRET_HEADLINER = 'headliner';

    const
        TABLE_TICKET = 'Ticket',
        TICKET_COLUMN_TICKET_ID = 'idTicket',
        TICKET_COLUMN_PRICE = 'price',
        TICKET_COLUMN_BOUGHT = 'bought',
        TICKET_COLUMN_IN_CART = 'inCart',
        TICKET_COLUMN_ID_YEAR = 'idYear',
        TICKET_COLUMN_ID_CONCERT = 'idConcert',
        TICKET_COLUMN_ID_USER = 'idUser',
        TICKET_COLUMN_TYPE = 'type';
}