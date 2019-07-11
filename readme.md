## Installation
* Voraussetzung: 
    - [Composer](https://getcomposer.org/download/)
    - Php >= 7.1.3

* composer install --optimize-autoloader --no-dev

* ggf. .env.example in .env umbennen. (Die Variablen in .env werden in die $_ENV Super-Global geladen)
* in .env APP_ENV=production, App_DEBUG=false, und DB-Verbindung konfigurieren, Admins eintragen

* Im Terminal:
    - php artisan key:generate (sonst funktioniert die Verschlüsselung nicht)
    - php artisan migrate
    - optional: php artisan route:cache

* Das Db-Passwort und der App-Schlüssel liegen dann natürlich einfach in Plaintext im Verzeichnis. In Produktion wäre es ggf sinnvoll die über das URZ-Verfahren(https://www.tu-chemnitz.de/urz/www/sectoken.html) verschlüsseln zu lassen. Allerdings muss dann der Code eventuell nochmal angepasst werden.

* Der Server braucht Scheibrecht für /storage/ und /bootstrap/cache
* Standardmäßig ist der public-Ordner im App-Root auch der document/web root vom Server. Wenn die web root woanders sein soll, muss in /bootstrapp/app.php in Zeile 21 der relative Pfad angepasst werden und in der .env-Datei das # vor public_path entfernt werden, bzw die Variable Variable public_path=public_html eingefügt werden. Die Daten in /public müssen dann natürlich ins neue Verzeichnis kopiert werden.

