INSERT INTO Interpret(name, label, founded)
VALUES (INTERPRET_ID.nextval, "Slipknot", "http://1000logos.net/wp-content/uploads/2017/06/Slipknot-Logo.png", "1993-01-01");

INSERT INTO Genre(name) VALUES ('Nu metal');
INSERT INTO Genre(name) VALUES ('Alternative metal');
INSERT INTO Genre(name) VALUES ('Hard rock');

SET @interpretId = (SELECT idInterpret FROM Interpret WHERE Interpret.nazev = 'Slipknot');

INSERT INTO Album(name, release, label, idInterpret)
VALUES ('.5: The Gray Chapter', '2014-10-17', 'https://data.bontonland.cz/fotky/209/p-82664-full.jpg', @interpretId);

INSERT INTO Album(name, release, label, idInterpret)
VALUES ('All Hope Is Gone', '2008-08-20', 'https://www.fonodisco.es/228819/slipknot-all-hope-is-gone.jpg', @interpretId);

INSERT INTO ALBUM(nazev, datum_vydani, label, FK_vydal)
VALUES ('Vol. 3: (The Subliminal Verses)', '2004-05-25', 'https://i.ebayimg.com/images/i/351407520120-0-1/s-l1000.jpg', @interpretId);

INSERT INTO Album_has_Genre(idAlbum, idGenre) VALUES (
  (SELECT idAlbum FROM Ablum WHERE name = 'All Hope Is Gone'),
  (SELECT idGenre FROM Genre WHERE name = 'Nu metal')
);

INSERT INTO Album_has_Genre(idAlbum, idGenre) VALUES (
  (SELECT idAlbum FROM Ablum WHERE name = '.5: The Gray Chapter'),
  (SELECT idGenre FROM Genre WHERE name = 'Nu metal')
);

INSERT INTO Album_has_Genre(idAlbum, idGenre) VALUES (
  (SELECT idAlbum FROM Ablum WHERE name = 'Vol. 3: (The Subliminal Verses)'),
  (SELECT idGenre FROM Genre WHERE name = 'Nu metal')
);

INSERT INTO Member(name, surname, birth) 
VALUES ('Corey', 'Taylor', '1973-12-08');

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Sid', 'Wilson', TO_DATE('1977-01-20', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Jim', 'Root', TO_DATE('1971-10-02', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Craig', 'Jones', TO_DATE('1972-02-11', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Shawn', 'Crahan', TO_DATE('1969-09-24', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Mick', 'Thompson', TO_DATE('1973-11-03', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Alessandro', 'Venturella', TO_DATE('1978-03-07', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Jay', 'Weinberg', TO_DATE('1990-09-08', 'yyyy-mm-dd')
);

INSERT INTO CLEN(ID_clena, jmeno, prijmeni, datum_narozeni) VALUES (
    CLEN_ID.nextval, 'Chris', 'Fehn', TO_DATE('1973-03-24', 'yyyy-mm-dd')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
  (SELECT ID_clena FROM CLEN WHERE jmeno = 'Chris' AND prijmeni = 'Fehn'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Slipknot')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
  (SELECT ID_clena FROM CLEN WHERE jmeno = 'Corey' AND prijmeni = 'Taylor'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Slipknot')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
  (SELECT ID_clena FROM CLEN WHERE jmeno = 'Mick' AND prijmeni = 'Thompson'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Slipknot')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
  (SELECT ID_clena FROM CLEN WHERE jmeno = 'Jay' AND prijmeni = 'Weinberg'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Slipknot')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
  (SELECT ID_clena FROM CLEN WHERE jmeno = 'Shawn' AND prijmeni = 'Crahan'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Slipknot')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
  (SELECT ID_clena FROM CLEN WHERE jmeno = 'Jim' AND prijmeni = 'Root'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Slipknot')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
  (SELECT ID_clena FROM CLEN WHERE jmeno = 'Sid' AND prijmeni = 'Wilson'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Slipknot')
);

INSERT INTO JE_CLEN(FK_clen, FK_interpret) VALUES (
  (SELECT ID_clena FROM CLEN WHERE jmeno = 'Craig' AND prijmeni = 'Jones'),
  (SELECT ID_interpreta FROM INTERPRET WHERE nazev = 'Slipknot')
);
