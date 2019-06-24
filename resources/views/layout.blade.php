<!DOCTYPE html>
<html lang="de">
<head>
<title>Studienleistungen</title>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="author" content="Malte Thormählen">
<meta name="dcterms.coverage" content="Worldwide">
<meta name="dcterms.modified" content="2019-06-21">
<meta name="dcterms.rightsHolder" content="Technische Universit&auml;t Chemnitz">
<meta name="description" content="Professur Datenverwaltungssysteme">
<meta name="geo.country" content="de">
<meta name="keywords" content="TU Chemnitz, Technische Universit&auml;t Chemnitz, Fakultät für Informatik, Professur Datenverwaltungssysteme">
<meta name="robots" content="index, follow">
<meta name="theme-color" content="#005e58">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1">
<link rel="shortcut icon" href="/tucal4/img/tuc.png">
<link rel="stylesheet" type="text/css" href="/tucal4/css/tucal4.css?201905241355">
<noscript><link rel="stylesheet" type="text/css" href="/tucal4/css/noscript.css"></noscript>
<script src="https://www.tu-chemnitz.de/static/jquery/1.11/jquery.min.js"></script>
<script src="https://www.tu-chemnitz.de/static/jquery-ui/1.11/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://www.tu-chemnitz.de/static/jquery-ui/1.11/themes/smoothness/jquery-ui.min.css">
<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
</head>
<body class="class-tuc">
<header>
  <h1 class="sr-only" id="header">Navigation</h1>
  <a id="skip-to-content" href="#top" class="sr-only sr-only-focusable">Springe zum Hauptinhalt</a>
  <div id="tucal-search" class="collapse">
    <div class="container">
      <nav>
        <div class="row">
          <div id="tucal-topbar" class="col-sm-8 clearfix">
            <h2 class="sr-only">Direktlinks</h2>
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a class="dropdown-toggle tucal-searchbar" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Direktlinks <span class="caret"></span></a>
                <ul id="tucal-shortcuts" class="dropdown-menu"><li><a href="//www.tu-chemnitz.de/tu/">Universit&auml;t</a><ul>
                  <li><a href="//www.tu-chemnitz.de/tu/lageplan/campusfinder/">Campusfinder</a></li>
                  <li><a href="//www.tu-chemnitz.de/tu/personen.html">Personen: Telefon, E-Mail</a></li>
                  <li class="extern"><a href="https://www.swcz.de/bilderspeiseplan/">Mensa-Speiseplan</a></li>
                </ul></li><li><a href="//www.tu-chemnitz.de/studium/">Studium</a><ul>
                  <li><a href="//www.tu-chemnitz.de/studentenservice/">Studentenservice</a></li>
                  <li><a href="//www.tu-chemnitz.de/studentenservice/zpa/">Zentrales Prüfungsamt</a></li>
                  <li><a href="//www.tu-chemnitz.de/beratung">Beratung und Betreuung</a></li>
                  <li><a href="//www.tu-chemnitz.de/verwaltung/vlvz/">Vorlesungsverzeichnis</a></li>
                  <li><a href="//www.tu-chemnitz.de/e-learning/">E-Learning</a></li>
                  <li class="extern"><a href="https://opal.sachsen.de/TUC">OPAL-Lernplattform</a></li>
                  <li><a href="https://www.stura.tu-chemnitz.de/">Student_innenrat</a></li>
                  <li class="extern"><a href="https://www.swcz.de/">Studentenwerk</a></li>
                  <li><a href="//www.tu-chemnitz.de/career-service/jobboerse/">Jobb&ouml;rse</a></li>
                </ul></li><li><a href="//www.tu-chemnitz.de/urz/dienste.php?group=Kommunikation">Netzdienste</a><ul>
                  <li><a href="https://mail.tu-chemnitz.de/">Webmail</a></li>
                  <li><a href="https://dict.tu-chemnitz.de/">BEOLINGUS-W&ouml;rterbuch</a></li>
                  <li><a href="//www.tu-chemnitz.de/urz/network/access/wlan.html">WLAN</a></li>
                </ul></li></ul>
              </li>
              <li><a href="https://idm.hrz.tu-chemnitz.de/user/view/" title="">Mein Profil</a></li>
              <li><a class="tucal-searchbar" href="//www.tu-chemnitz.de/tu/kontakt.php" accesskey="k">Kontakt</a></li>
            </ul>
          </div>
          <div id="tucal-searchxs" class="col-sm-4 no-spacing">
            <div id="tucal-searchfield">
              <h2 class="sr-only">Suchen</h2>
              <form id="tucal-searchform" class="navbar-form navbar-left navbar-collapse" role="search" autocomplete="off" action="https://www.google.de/search" onsubmit="return tuc_search( 'tucal-searchtype', 'tucal-searchword', 'de', false);" method="get">
                <input type="hidden" name="oe" value="UTF-8">
                <input type="hidden" name="as_sitesearch" value=".tu-chemnitz.de">
                <input type="hidden" name="hl" value="de">
                <input type="hidden" name="lr" value="lang_de">
                <div class="input-group">
                  <input id="tucal-searchword" type="text" class="form-control" aria-label="Suchwort" placeholder="Suchwort" name="as_q">
                  <div class="input-group-btn">
                    <noscript>
                      <span class="btn btn-default">Suche (via Google)</span>
                    </noscript>
                    <a id="tucal-search-option" class="btn btn-default" href="#" role="button" aria-haspopup="listbox">Suche in&hellip; <span class="caret"></span></a>
                    <ul id="tucal-searchtype" class="dropdown-menu" role="listbox" tabindex="-1" aria-labelledby="tucal-search-option">
                      <li id="tucal-st-1" role="option" data-value="text" aria-selected="true">TU-Webseiten (via Google)</li>
                      <li id="tucal-st-2" role="option" data-value="pers">Personenverzeichnis</li>
                      <li id="tucal-st-3" role="option" data-value="uakt">Uni aktuell</li>
                      <li id="tucal-st-4" role="option" data-value="opac">Bibliothekskatalog</li>
                      <li id="tucal-st-5" role="option" data-value="beol">Beolingus-W&ouml;rterbuch</li>
                      <li id="tucal-st-6" role="option" data-value="room">Raumverzeichnis</li>
                    </ul>
                  </div>
                </div>
                <button class="btn btn-default" type="submit" name="s">
                  <span class="sr-only">Suchen</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </nav>
    </div>
  </div>
  <div id="tucal-printhead" class="visible-print">
    <div>
      <div>
        <div class="tucal-printlogo">
          <img src="/tucal4/img/logo.svg" title="Technische Universit&auml;t Chemnitz" alt="">
        </div>
        <div class="tucal-printtitle">
          <strong></strong><br>Professur Datenverwaltungssysteme
        </div>
      </div>
    </div>
  </div>
  <div id="tucal-head" class="hidden-print">
    <nav class="navbar navbar-default">
      <h2 class="sr-only">Hauptnavigation</h2>
      <div class="container">
        <div id="tucal-headlogoplaceholder" class="navbar-header">
          <a id="tucal-headbutton" class="navbar-toggle" href="#" role="button" data-toggle="collapse" aria-expanded="false" aria-label="Schalte Hauptnavigation um" data-target="#tucal-headnavigation, #tucal-search">
            <div>
              <div>
                <div> </div>
                <div> </div>
                <div> </div>
              </div>
            </div>
          </a>
          <div id="tucal-headlogoborder" class="col-sm-3">
            <a href="//www.tu-chemnitz.de/" accesskey="h"><span class="sr-only">Link zur Startseite</span><img src="/tucal4/img/logo.png" id="tucal-headlogo" alt="Logo: TU Chemnitz"></a>
          </div>
          <div id="tucal-headnavigation" class="col-sm-9 collapse navbar-collapse">
            <ol class="nav navbar-nav"><li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false">Universit&auml;t</a><ul class="dropdown-menu">
              <li><a href="//www.tu-chemnitz.de/tu/struktur.php">Organisation</a></li>
              <li><a href="//www.tu-chemnitz.de/tu/lageplan/">Campusplan</a></li>
              <li><a href="//www.tu-chemnitz.de/rektorat/">Rektorat</a></li>
              <li><a href="//www.tu-chemnitz.de/tu/stellen.html">Stellenausschreibungen</a></li>
              <li><a href="//www.tu-chemnitz.de/tu/pressestelle/">Pressestelle und Crossmedia-Redaktion</a></li>
              <li><a href="//www.tu-chemnitz.de/gleichstellung/">Gleichstellung und Familie</a></li>
              <li><a href="//www.tu-chemnitz.de/tu/vome/">Veranstaltungsorganisation und Merchandising</a></li>
              <li><a href="//www.tu-chemnitz.de/tu/inklusion/">Inklusion und Barrierefreiheit</a></li>
              <li><a href="//www.tu-chemnitz.de/verwaltung/">Zentrale Verwaltung</a></li>
              <li><a href="//www.tu-chemnitz.de/verwaltung/gremien/">Organe, Gremien, Vertretungen und Beauftragte</a></li>
            </ul></li><li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown"  aria-expanded="false" role="button">Fakult&auml;ten</a><ul class="dropdown-menu">
              <li><a href="//www.tu-chemnitz.de/naturwissenschaften/">Naturwissenschaften</a></li>
              <li><a href="//www.tu-chemnitz.de/informatik/">Informatik</a></li>
              <li><a href="//www.tu-chemnitz.de/mathematik/">Mathematik</a></li>
              <li><a href="//www.tu-chemnitz.de/wirtschaft/">Wirtschaftswissenschaften</a></li>
              <li><a href="//www.tu-chemnitz.de/mb/">Maschinenbau</a></li>
              <li><a href="//www.tu-chemnitz.de/phil/">Philosophische Fakult&auml;t</a></li>
              <li><a href="//www.tu-chemnitz.de/etit/">Elektrotechnik und Informationstechnik</a></li>
              <li><a href="//www.tu-chemnitz.de/hsw/">Human- und Sozialwissenschaften</a></li>
            </ul></li><li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown"  aria-expanded="false" role="button">Zentrale Einrichtungen</a><ul class="dropdown-menu">
              <li><a href="//www.tu-chemnitz.de/MERGE/">Exzellenzcluster MERGE</a></li>
              <li><a href="//www.tu-chemnitz.de/hds/">Hochschuldidaktisches Zentrum Sachsen</a></li>
              <li><a href="//www.tu-chemnitz.de/international/">Internationales Universit&auml;tszentrum</a></li>
              <li><a href="//www.tu-chemnitz.de/ub/">Universit&auml;tsbibliothek</a></li>
              <li><a href="//www.tu-chemnitz.de/urz/">Universit&auml;tsrechenzentrum</a></li>
              <li><a href="//www.tu-chemnitz.de/sprachenzentrum/">Zentrum f&uuml;r Fremdsprachen</a></li>
              <li><a href="//www.tu-chemnitz.de/zlb/">Zentrum f&uuml;r Lehrerbildung</a></li>
              <li><a href="//www.tu-chemnitz.de/zfwn/">Zentrum f&uuml;r den wissenschaftlichen Nachwuchs</a></li>
              <li><a href="//www.tu-chemnitz.de/transfer/zwt/">Zentrum f&uuml;r Wissens- und Technologietransfer</a></li>
              <li><a href="//www.tu-chemnitz.de/usz/">Zentrum f&uuml;r Sport und Gesundheitsf&ouml;rderung</a></li>
            </ul></li><li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown"  aria-expanded="false" role="button">Studium</a><ul class="dropdown-menu">
              <li><a href="//www.tu-chemnitz.de/studentenservice/zsb/schuelerbewerber.php">Sch&uuml;ler &amp; Bewerber</a></li>
              <li><a href="//www.tu-chemnitz.de/studentenservice/zsb/studiengaenge/">Studienm&ouml;glichkeiten</a></li>
              <li><a href="//www.tu-chemnitz.de/studentenservice/zsb/studierende.php">Studierende</a></li>
              <li><a href="//www.tu-chemnitz.de/studentenservice/">Studentenservice</a></li>
              <li><a href="//www.tu-chemnitz.de/transfer/wissen/weiterbildung.php">Weiterbildungs&shy;interessierte</a></li>
              <li><a href="//www.tu-chemnitz.de/career-service/">Career Service</a></li>
              <li><a href="//www.tu-chemnitz.de/qpl/">Qualit&auml;tspakt Lehre</a></li>
              <li><a href="//www.tu-chemnitz.de/academic-integration/">Akademische Integration</a></li>
              <li><a href="//www.tu-chemnitz.de/tu/studentisches-engagement/">Studentisches Engagement</a></li>
              <li><a href="//www.tu-chemnitz.de/studienerfolg-pro/">TUCpanel</a></li>
            </ul></li><li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown"  aria-expanded="false" role="button">International</a><ul class="dropdown-menu">
              <li><a href="//www.tu-chemnitz.de/international/vernetzung/">Networking</a></li>
              <li><a href="//www.tu-chemnitz.de/international/outgoing/">Outgoing</a></li>
              <li><a href="//www.tu-chemnitz.de/international/incoming/">Incoming</a></li>
              <li><a href="//www.tu-chemnitz.de/international/wissenschaftler/welcome-center/">Gastwissenschaftler</a></li>
            </ul></li></ol>
            <div id="tucal-orgtitle" class="row">
              <div class="col-xs-12 no-spacing">
                <div></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</header>
<strong class="invisible">Professur Datenverwaltungssysteme | Fakultät für Informatik | TU Chemnitz</strong>
<nav id="tucal-breadcrumbs" class="hidden-print"><h2 class="sr-only">Brotkr&uuml;melnavigation</h2>
  <div class="container">
    <div id="tucal-breadcrumbrow" class="row">
      <div class="col-xs-12">
        <ol class="tucal-breadcrumb"><li><a href="//www.tu-chemnitz.de/" title="Homepage">TU Chemnitz</a></li><li><a href="https://www.tu-chemnitz.de/informatik/">Fakultät für Informatik</a></li><li><a href="https://www.tu-chemnitz.de/informatik/DVS/">Professur Datenverwaltungssysteme</a></li>
      </div>
    </div>
  </div>
</nav>
<div id="tucal-content">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 tucal-canvas" id="top">
        <div class="row">
          <main class="col-xs-12 page-content">
            @yield ('content')
            @yield ('pagination')

          </main>
        </div>
        <span id="bottom"></span>
      </div>
    </div>
  </div>
</div>
<footer>
  <h1 id="footer" class="sr-only">Footer</h1>
  <section id="tucal-unilinks" class="hidden-print">
    <h2 class="sr-only">Links</h2>
    <div class="container">
      <div class="row">

        <div class="col-sm-offset-6 col-sm-6 col-md-offset-3 col-md-9 col-xs-12 column-right">

          <div>
            <div class="tucal-footmenuitems"><a href="//www.tu-chemnitz.de/verwaltung/bfau/notfall.php">Notfall</a></div>
            <div class="tucal-footmenuitems"><a href="//www.tu-chemnitz.de/tu/kontakt.php">Kontakt</a></div>
            <div class="tucal-footmenuitems"><a href="//www.tu-chemnitz.de/tu/lageplan/">Campusplan</a></div>
            <div class="tucal-footmenuitems"><a href="//www.tu-chemnitz.de/tu/">Universit&auml;t</a></div>
            <div class="tucal-footmenuitems"><a class="linkextern" href="https://www.chemnitz.de/" title="chemnitz.de">Chemnitz</a></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div id="tucal-printfoot" class="visible-print">
    <hr>
    <div class="text">
      &copy; 2019 Technische Universit&auml;t Chemnitz<br>
      https://www-user.tu-chemnitz.de/~malth/test.html<br>
      Malte Thormählen, 21.&nbsp;Juni 2019
    </div>
  </div>
  <section id="tucal-foot" class="hidden-print"><h2 class="sr-only">Footer</h2>
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-md-9 col-xs-12 column-right">
          <div class="tucal-footmenuitems"><a href="#">Autoren</a></div>
          <div class="tucal-footmenuitems"><a href="#">Mediadaten</a></div>
          <div class="tucal-footmenuitems"><a href="#">Impressum</a></div>
          <div class="tucal-footmenuitems"><a href="#">Datenschutz</a></div>
        </div>
        <div class="col-sm-6 col-md-3 col-xs-12 column-left">
          <div>
            &copy; 2019 Technische Universit&auml;t Chemnitz<br>
            <div class="hs-modal"><a href="//www.tu-chemnitz.de/urz/mail/adr.php?1bWFsdGhvckBtYWlsYm94Lm9yZw==" data-height="157" data-toggle="modal" data-target="#hs-modal" data-remote-frame="//www.tu-chemnitz.de/urz/mail/adrx.html?1bWFsdGhvckBtYWlsYm94Lm9yZw==" data-title="Spam-Schutz f&uuml;r E-Mail-Adressen">Malte Thormählen</a></div> | 21.&nbsp;Juni 2019
          </div>
        </div>
      </div>
    </div>
  </section>
  <div id="tucal-top" class="hidden-print" data-vis="hide"></div>
</footer>
<script src="/tucal4/js/bootstrap.min.js"></script>
<script src="/tucal4/js/tucal.js?201905241355/de"></script>
<script src="/tucal4/js/jquery.mobile.custom.min.js"></script>
<script src="{{ URL::asset('js/ajax.js') }}"></script>
</body>
</html>
