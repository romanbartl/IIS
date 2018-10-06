INSERT INTO Interpret(name, label, founded)
VALUES ("Slipknot", "http://1000logos.net/wp-content/uploads/2017/06/Slipknot-Logo.png", "1993-01-01");

INSERT INTO Genre(name) VALUES ('Nu metal');
INSERT INTO Genre(name) VALUES ('Alternative metal');
INSERT INTO Genre(name) VALUES ('Hard rock');

SET @interpretId = (SELECT idInterpret FROM Interpret WHERE name = 'Slipknot');

INSERT INTO Album(name, `release`, label, idInterpret)
VALUES ('.5: The Gray Chapter', '2014-10-17', 'https://data.bontonland.cz/fotky/209/p-82664-full.jpg', @interpretId);

INSERT INTO Album(name, `release`, label, idInterpret)
VALUES ('All Hope Is Gone', '2008-08-20', 'https://www.fonodisco.es/228819/slipknot-all-hope-is-gone.jpg', @interpretId);

INSERT INTO Album(name, `release`, label, idInterpret)
VALUES ('Vol. 3: (The Subliminal Verses)', '2004-05-25', 'https://i.ebayimg.com/images/i/351407520120-0-1/s-l1000.jpg', @interpretId);

INSERT INTO Album_has_Genre(idAlbum, idGenre) VALUES (
  (SELECT idAlbum FROM Album WHERE name = 'All Hope Is Gone'),
  (SELECT idGenre FROM Genre WHERE name = 'Nu metal')
);

INSERT INTO Album_has_Genre(idAlbum, idGenre) VALUES (
  (SELECT idAlbum FROM Album WHERE name = '.5: The Gray Chapter'),
  (SELECT idGenre FROM Genre WHERE name = 'Nu metal')
);

INSERT INTO Album_has_Genre(idAlbum, idGenre) VALUES (
  (SELECT idAlbum FROM Album WHERE name = 'Vol. 3: (The Subliminal Verses)'),
  (SELECT idGenre FROM Genre WHERE name = 'Nu metal')
);

INSERT INTO Member(name, surname, birth) 
VALUES ('Corey', 'Taylor', '1973-12-08');

INSERT INTO Member(name, surname, birth)  
VALUES ('Sid', 'Wilson', '1977-01-20');

INSERT INTO Member(name, surname, birth) 
VALUES ('Jim', 'Root', '1971-10-02');

INSERT INTO Member(name, surname, birth)  
VALUES ('Craig', 'Jones', '1972-02-11');

INSERT INTO Member(name, surname, birth)  
VALUES ('Shawn', 'Crahan', '1969-09-24');

INSERT INTO Member(name, surname, birth)  
VALUES ('Mick', 'Thompson', '1973-11-03');

INSERT INTO Member(name, surname, birth)  
VALUES ('Alessandro', 'Venturella', '1978-03-07');

INSERT INTO Member(name, surname, birth)  
VALUES ('Jay', 'Weinberg', '1990-09-08');

INSERT INTO Member(name, surname, birth)  
VALUES ('Chris', 'Fehn', '1973-03-24');

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Chris' AND surname = 'Fehn'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Slipknot')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Corey' AND surname = 'Taylor'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Slipknot')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Mick' AND surname = 'Thompson'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Slipknot')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Jay' AND surname = 'Weinberg'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Slipknot')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Shawn' AND surname = 'Crahan'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Slipknot')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Jim' AND surname = 'Root'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Slipknot')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Sid' AND surname = 'Wilson'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Slipknot')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Craig' AND surname = 'Jones'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Slipknot')
);


INSERT INTO City(name) VALUES ('Praha');
INSERT INTO City(name) VALUES ('Vizovice');

INSERT INTO Place(name, address, GPS, info, zipCode, idCity) 
VALUES ('O2 arena', 'Ceskomoravska 2345/17', '50°06''17.8"N 14°29''37.7"E', '', '19000', (SELECT idCity FROM City WHERE name = 'Praha'));


INSERT INTO Interpret(name, label, founded) 
VALUES ('Suicidal Tendencies', 'http://logonoid.com/images/suicidal-tendencies-logo.jpg', '1981-01-01');

SET @interpretId = (SELECT idInterpret FROM Interpret WHERE name = 'Suicidal Tendencies');

INSERT INTO Member(name, surname, birth) 
VALUES ('Ra', 'Diaz', NULL);

INSERT INTO Member(name, surname, birth) 
VALUES ('Jeff', 'Pogan', '1991-09-18');

INSERT INTO Member(name, surname, birth)  
VALUES ('Dean', 'Pleasants', '1965-05-18');

INSERT INTO Member(name, surname, birth) 
VALUES ('Dave', 'Lombardo', '1965-02-16');

INSERT INTO Member(name, surname, birth) 
VALUES ('Mike', 'Muir', '1963-03-14');


INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Ra' AND surname = 'Diaz'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Suicidal Tendencies')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Jeff' AND surname = 'Pogan'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Suicidal Tendencies')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Dean' AND surname = 'Pleasants'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Suicidal Tendencies')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Dave' AND surname = 'Lombardo'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Suicidal Tendencies')
);

INSERT INTO Interpret_has_Member(idMember, idInterpret) VALUES (
  (SELECT idMember FROM Member WHERE name = 'Mike' AND surname = 'Muir'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Suicidal Tendencies')
);


INSERT INTO Concert(name, `date`, capacity, idPlace) VALUES (
    'Slipknot koncert', '2016-01-27', 1000,
    (SELECT idPlace FROM Place WHERE name = 'O2 arena')
);

INSERT INTO Concert_has_Interpret(idConcert, idInterpret, headliner) VALUES(
  (SELECT idConcert FROM Concert WHERE name = 'Slipknot koncert'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Slipknot'),
  1
);

INSERT INTO Concert_has_Interpret(idConcert, idInterpret, headliner) VALUES(
  (SELECT idConcert FROM Concert WHERE name = 'Slipknot koncert'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Suicidal Tendencies'),
  0
);


INSERT INTO Interpret(name, label, founded) 
VALUES ('Stone Sour', 'https://i.pinimg.com/originals/51/6f/b0/516fb0de69307cdd34525bc345a69a1a.jpg', '1992-01-01');

INSERT INTO Album(name, `release`, label, idInterpret)
VALUES ('Stone Sour', '2002-08-27', 'https://www.bontonland.cz/image.php?image=/fotky/64/p-21095-full.jpg&width=375&height=375',
(
	SELECT idInterpret FROM Interpret
	WHERE name = 'Stone Sour'
));

INSERT INTO Album_has_Genre(idAlbum, idGenre) VALUES
(
  (SELECT idAlbum FROM Album WHERE name = 'Stone Sour'),
  (SELECT idGenre FROM Genre WHERE name = 'Hard rock')
);

INSERT INTO Album(name, `release`, label, idInterpret)
VALUES ('Hydrogradr', '2017-06-30', 'https://i2.wp.com/www.metalinjection.net/wp-content/uploads/2017/07/STSBN013.jpg?fit=700%2C700',
(
	SELECT idInterpret FROM Interpret
        WHERE name = 'Stone Sour'
));

INSERT INTO Album_has_Genre(idAlbum, idGenre) VALUES
(
  (SELECT idAlbum FROM Album WHERE name = 'Hydrogradr'),
  (SELECT idGenre FROM Genre WHERE name = 'Alternative metal')
);

INSERT INTO Interpret_has_Member (idMember, idInterpret) 
VALUES (
  (SELECT idMember FROM Member WHERE name = 'Corey' AND surname = 'Taylor'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Stone Sour')
);

INSERT INTO Member(name, surname, birth) 
VALUES ('Josh', 'Rand', '1974-08-19');

INSERT INTO Interpret_has_Member(idMember, idInterpret) 
VALUES (
  (SELECT idMember FROM Member WHERE name = 'Josh' AND surname = 'Rand'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Stone Sour')
);

INSERT INTO Member(name, surname, birth) 
VALUES ('Johny', 'Chow', '1972-02-01');

INSERT INTO Interpret_has_Member(idMember, idInterpret) 
VALUES (
  (SELECT idMember FROM Member WHERE name = 'Johny' AND surname = 'Chow'),
  (SELECT idInterpret FROM Interpret WHERE name = 'Stone Sour')
);




/*
INSERT INTO Festival(name) 
VALUES ('Masters Of Rock');

INSERT INTO Place(name, address, gps, info, zipCode, idCity) VALUES (
  'Areál Likérky R. Jelínek', 'Razov 472', '49°13''02.2"N 17°50''26.2"E', '', '76312',
  (SELECT idCity FROM City WHERE name = 'Vizovice')
);

INSERT INTO ROCNIK(ID_rocniku, poradi, obdobi, datum_zahajeni, datum_ukonceni, FK_festival, FK_misto) VALUES
(
  ROCNIK_ID.nextval, 16, 'leto', TO_DATE('2018-07-12', 'yyyy-mm-dd'), TO_DATE('2018-07-15', 'yyyy-mm-dd'),
  (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock'),
  (SELECT ID_mista FROM MISTO WHERE nazev = 'Areál Likérky R. Jelínek')
);

INSERT INTO STAGE(ID_stage, nazev, kapacita_mist, kapacita_interpretu, plocha) VALUES (
    STAGE_ID.nextval, 'Ronnie James Dio', 5000, 20, 50
);

INSERT INTO INTERPRET_HRAJE_NA_STAGI(FK_stage, FK_interpreta, headliner, hraje_od, hraje_do) VALUES (
  (SELECT ID_stage FROM STAGE WHERE nazev = 'Ronnie James Dio'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Stone Sour'),
  1,
  TO_TIMESTAMP('2017-05-17 18:15', 'YYYY-MM-DD HH24:MI'),
  TO_TIMESTAMP('2017-05-17 19:30', 'YYYY-MM-DD HH24:MI')
);

INSERT INTO INTERPRET_HRAJE_NA_STAGI(FK_stage, FK_interpreta, headliner, hraje_od, hraje_do) VALUES (
  (SELECT ID_stage FROM STAGE WHERE nazev = 'Ronnie James Dio'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Suicidal Tendencies'),
  0,
  TO_TIMESTAMP('2017-05-17 17:45', 'YYYY-MM-DD HH24:MI'),
  TO_TIMESTAMP('2017-05-17 18:15', 'YYYY-MM-DD HH24:MI')
);

INSERT INTO INTERPRET_HRAJE_V_ROCNIKU(FK_rocnik, FK_interpret) VALUES (
  (SELECT ID_rocniku FROM ROCNIK WHERE FK_festival = (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock')
    AND poradi = 16 AND obdobi = 'leto'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Stone Sour')
);

INSERT INTO INTERPRET_HRAJE_V_ROCNIKU(FK_rocnik, FK_interpret) VALUES (
  (SELECT ID_rocniku FROM ROCNIK WHERE FK_festival = (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock')
    AND poradi = 16 AND obdobi = 'leto'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Suicidal Tendencies')
);

/*BEGIN
  FOR i IN 1..5 LOOP
    INSERT INTO VSTUPENKA(ID_vstupenky, cena, koupena, ID_typu_vstupenky, ID_osoby, ID_rocniku, ID_koncertu) VALUES (
      VSTUPENKA_ID.nextval, 2350, 0, (SELECT ID_typu FROM TYP_VSTUPENKY WHERE typ = 'stani'), NULL,
      (SELECT ID_rocniku FROM ROCNIK WHERE FK_festival = (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock')
        AND poradi = 16 AND obdobi = 'leto'), NULL
    );
  END LOOP;
END;

INSERT INTO INTERPRET(ID_interpreta, nazev, label, datum_vzniku) VALUES (
  INTERPRET_ID.nextval, 'Linkin Park', 'https://ih1.redbubble.net/image.495733991.5798/flat,800x800,075,f.jpg',
  TO_DATE('1981-01-01', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Rob', 'Bourdon', TO_DATE('1979-01-20', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Brad', 'Delson', TO_DATE('1977-12-01', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'David', 'Farrell', TO_DATE('1977-02-08', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Joseph', 'Hahn', TO_DATE('1977-03-15', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Michael', 'Shinoda', TO_DATE('1977-02-11', 'yyyy-mm-dd')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
    (SELECT ID_clena FROM CLEN WHERE jmeno = 'Rob' AND prijmeni = 'Bourdon'),
    (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Linkin Park')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
    (SELECT ID_clena FROM CLEN WHERE jmeno = 'Brad' AND prijmeni = 'Delson'),
    (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Linkin Park')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
    (SELECT ID_clena FROM CLEN WHERE jmeno = 'David' AND prijmeni = 'Farrell'),
    (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Linkin Park')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
    (SELECT ID_clena FROM CLEN WHERE jmeno = 'Joseph' AND prijmeni = 'Hahn'),
    (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Linkin Park')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
    (SELECT ID_clena FROM CLEN WHERE jmeno = 'Michael' AND prijmeni = 'Shinoda'),
    (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Linkin Park')
);

INSERT INTO ZANR(ID_zanru, nazev) VALUES (
    ZANR_ID.nextval, 'Elektropop'
);

INSERT INTO ALBUM(ID_alba, nazev, datum_vydani, label, FK_vydal) VALUES (
    ALBUM_ID.nextval, 'One More Light', TO_DATE('2017-05-19', 'yyyy-mm-dd'), 'https://data.bontonland.cz/fotky/376/p-148956-full.jpg',
    (SELECT ID_interpreta FROM INTERPRET WHERE INTERPRET.nazev = 'Linkin Park')
);

INSERT INTO ALBUM_JE_ZANRU(FK_alba, FK_zanru) VALUES (
  (SELECT ID_alba FROM ALBUM WHERE ALBUM.nazev = 'One More Light'),
  (SELECT ID_zanru FROM ZANR WHERE ZANR.nazev = 'Elektropop')
);

INSERT INTO ALBUM(ID_alba, nazev, datum_vydani, label, FK_vydal) VALUES (
    ALBUM_ID.nextval, 'Hybrid Theory', TO_DATE('2000-09-24', 'yyyy-mm-dd'), 'https://upload.wikimedia.org/wikipedia/en/thumb/c/c9/Linkin_park_hybrid_theory.jpg/220px-Linkin_park_hybrid_theory.jpg',
    (SELECT ID_interpreta FROM INTERPRET WHERE INTERPRET.nazev = 'Linkin Park')
);

INSERT INTO ALBUM_JE_ZANRU(FK_alba, FK_zanru) VALUES (
  (SELECT ID_alba FROM ALBUM WHERE ALBUM.nazev = 'Hybrid Theory'),
  (SELECT ID_zanru FROM ZANR WHERE ZANR.nazev = 'Nu metal')
);

INSERT INTO ZANR(ID_zanru, nazev) VALUES (
    ZANR_ID.nextval, 'Hardcore punk'
);

INSERT INTO ZANR(ID_zanru, nazev) VALUES (
    ZANR_ID.nextval, 'Crossover thrash'
);

INSERT INTO ALBUM(ID_alba, nazev, datum_vydani, label, FK_vydal) VALUES (
    ALBUM_ID.nextval, 'Suicidal Tendencies', TO_DATE('1983-07-05', 'yyyy-mm-dd'), 'https://upload.wikimedia.org/wikipedia/en/d/d1/SuicidalTendenciesAlbum.jpg',
    (SELECT ID_interpreta FROM INTERPRET WHERE INTERPRET.nazev = 'Suicidal Tendencies')
);

INSERT INTO ALBUM(ID_alba, nazev, datum_vydani, label, FK_vydal) VALUES (
    ALBUM_ID.nextval, 'Join the Army', TO_DATE('1987-06-09', 'yyyy-mm-dd'), 'https://upload.wikimedia.org/wikipedia/en/6/6c/Join_the_army_ST.jpg',
    (SELECT ID_interpreta FROM INTERPRET WHERE INTERPRET.nazev = 'Suicidal Tendencies')
);

INSERT INTO ALBUM_JE_ZANRU(FK_alba, FK_zanru) VALUES (
  (SELECT ID_alba FROM ALBUM WHERE ALBUM.nazev = 'Join the Army'),
  (SELECT ID_zanru FROM ZANR WHERE ZANR.nazev = 'Crossover thrash')
);

INSERT INTO ALBUM_JE_ZANRU(FK_alba, FK_zanru) VALUES (
  (SELECT ID_alba FROM ALBUM WHERE ALBUM.nazev = 'Suicidal Tendencies'),
  (SELECT ID_zanru FROM ZANR WHERE ZANR.nazev = 'Hardcore punk')
);

INSERT INTO KONCERT(ID_koncertu, nazev, datum, kapacita_mist, ID_mista) VALUES (
    KONCERT_ID.nextval, 'Linkin Park v Praze', TO_DATE('2003-09-03', 'yyyy-mm-dd'), 5000,
    (SELECT ID_mista FROM MISTO WHERE MISTO.nazev = 'O2 arena')
);

INSERT INTO INTERPRET_HRAJE_NA_KONCERTU(FK_koncertu, FK_interpreta, headliner) VALUES (
  (SELECT ID_koncertu FROM KONCERT WHERE nazev = 'Linkin Park v Praze'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Linkin Park'),
  1
);

INSERT INTO INTERPRET(nazev, label, datum_vzniku) VALUES (
    'Apocalyptica', 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Apocalyptica-logo.svg/2000px-Apocalyptica-logo.svg.png', TO_DATE('1993-01-01', 'yyyy-mm-dd')
);


INSERT INTO ROCNIK(ID_rocniku, poradi, obdobi, datum_zahajeni, datum_ukonceni, FK_festival, FK_misto) VALUES (
    ROCNIK_ID.nextval, 4, 'leto', TO_DATE('2006-07-14', 'yyyy-mm-dd'), TO_DATE('2006-07-17', 'yyyy-mm-dd'),
    (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock'),
    (SELECT ID_mista FROM MISTO WHERE nazev = 'Areál Likérky R. Jelínek')
);

INSERT INTO ROCNIK(ID_rocniku, poradi, obdobi, datum_zahajeni, datum_ukonceni, FK_festival, FK_misto) VALUES (
    ROCNIK_ID.nextval, 6, 'leto', TO_DATE('2008-07-10', 'yyyy-mm-dd'), TO_DATE('2008-07-13', 'yyyy-mm-dd'),
    (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock'),
    (SELECT ID_mista FROM MISTO WHERE nazev = 'Areál Likérky R. Jelínek')
);

INSERT INTO ROCNIK(ID_rocniku, poradi, obdobi, datum_zahajeni, datum_ukonceni, FK_festival, FK_misto) VALUES (
    ROCNIK_ID.nextval, 14, 'leto', TO_DATE('2016-07-14', 'yyyy-mm-dd'), TO_DATE('2016-07-17', 'yyyy-mm-dd'),
    (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock'),
    (SELECT ID_mista FROM MISTO WHERE nazev = 'Areál Likérky R. Jelínek')
);

INSERT INTO ROCNIK(ID_rocniku, poradi, obdobi, datum_zahajeni, datum_ukonceni, FK_festival, FK_misto) VALUES (
    ROCNIK_ID.nextval, 8, 'leto', TO_DATE('2010-07-15', 'yyyy-mm-dd'), TO_DATE('2010-07-18', 'yyyy-mm-dd'),
    (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock'),
    (SELECT ID_mista FROM MISTO WHERE nazev = 'Areál Likérky R. Jelínek')
);


INSERT INTO INTERPRET_HRAJE_V_ROCNIKU(FK_rocnik, FK_interpret) VALUES (
    (SELECT ID_rocniku FROM ROCNIK WHERE poradi = 4 AND FK_festival = (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock')),
    (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Apocalyptica')
);

INSERT INTO INTERPRET_HRAJE_V_ROCNIKU(FK_rocnik, FK_interpret) VALUES (
    (SELECT ID_rocniku FROM ROCNIK WHERE poradi = 6 AND FK_festival = (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock')),
    (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Apocalyptica')
);

INSERT INTO INTERPRET_HRAJE_V_ROCNIKU(FK_rocnik, FK_interpret) VALUES (
    (SELECT ID_rocniku FROM ROCNIK WHERE poradi = 14 AND FK_festival = (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock')),
    (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Apocalyptica')
);

INSERT INTO INTERPRET_HRAJE_V_ROCNIKU(FK_rocnik, FK_interpret) VALUES (
    (SELECT ID_rocniku FROM ROCNIK WHERE poradi = 6 AND FK_festival = (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock')),
    (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Sabaton')
);

INSERT INTO INTERPRET_HRAJE_V_ROCNIKU(FK_rocnik, FK_interpret) VALUES (
    (SELECT ID_rocniku FROM ROCNIK WHERE poradi = 8 AND FK_festival = (SELECT ID_festivalu FROM FESTIVAL WHERE nazev = 'Masters Of Rock')),
    (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Sabaton')
);*/
