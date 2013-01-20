# Meta-arviointi

## Repositoryn hakeminen omalle koneelle

	git clone git@github.com:Meta-arviointi/meta-arviointi.git

~~Muista myös tämän jälkeen vaihtaa devel haaraan mikäli se ei siellä vielä ole:~~

	git checkout devel

[Kehitysympäristön asennusohje wikissä](https://github.com/Meta-arviointi/meta-arviointi/wiki/Kehitysymp%C3%A4rist%C3%B6n-asentaminen)

## Login

meta_dump.sql tiedostossa on users-taulussa neljä käyttäjää, joiden kaikkien salasana on 'testi'. Peruspalvelutunnuksella ja salasanalla pääsee kirjautumaan. Esimerkki login: peruspalvelutunnus on "23456" ja salasana "testi".

**users-taulun INSERT-lauseet** (id, peruspalvelutunnus, sukunimi, etunimi, e-mail, is_admin, salasana ('testi')):

    INSERT INTO users VALUES (1, '12345', 'Opettaja', 'Vastuu', 'vastuuope@uta.fi', true, 'ce192a5d433c20a11b143e63319f960f3a2361b8');
    INSERT INTO users VALUES (2, '23456', 'Assistentti', 'Asseri', 'asseri.assistentti@uta.fi', false, 'ce192a5d433c20a11b143e63319f960f3a2361b8');
    INSERT INTO users VALUES (3, '34567', 'Assistentti', 'Testi', 'testi.assistentti@uta.fi', false, 'ce192a5d433c20a11b143e63319f960f3a2361b8');
    INSERT INTO users VALUES (4, '45678', 'Auttaja', 'Aapo', 'aapo.auttaja@uta.fi', false, 'ce192a5d433c20a11b143e63319f960f3a2361b8');

