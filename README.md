![podflow - Design your podcast!](https://podflow.de/wp-content/uploads/Banner_&_Logo/podflow_Logo_v2c-e1534852020681.png)

[![Version](https://img.shields.io/badge/release-1.2.0-blue.svg)](https://podflow.de)

# podflow - Design your podcast!

podflow! wurde für kollaborative Podcasts mit 2 + x Teilnehmer konzipiert, die Ihre Themen und Beiträge unabhängig voneinander erfassen möchten. Am Ende einer Aufnahme werden aus den gesammelten Beiträgen strukturierte Shownotes erzeugt.

Dazu wird ein Podcast mittels sog. Kategorien grob untergliedert und den Teilnehmern die Möglichkeit gegeben, zu diesen Kategorien individuelle Themen und Beiträge zu erfassen.

Während der Aufnahme können diese Themen und Beiträge abgehakt werden. Das Abhaken bestimmt letztendlich deren Reihenfolge beim Export der Shownotes.

Dazu bietet podflow! eine Reihe von Features:

- Responsive Design für mobile Endgeräte.
- Die Verwaltung multipler Benutzer, Podcasts und Episoden.
- Podcasts und Episoden werden in Kategorien („Rubriken“) untergliedert.
- Beiträge bestimmter Kategorien können vor anderen Benutzern versteckt werden.
- Die Anzahl von Beiträgen einer Kategorie kann eingeschränkt werden.
- Kategorien können in Themen untergliedert werden, die von Benutzern erfasst und mit Beiträgen befüllt werden.
- Zu jedem Thema und jedem Beitrag können Notizen erfasst werden.
- Themen und Beiträge können live während der Aufnahme abgehakt werden.
- Das Abhaken bestimmt die Reihenfolge, in der die Themen und Beiträge am Ende exportiert werden.
- Nach dem Abschluss einer Episode kann die Reihenfolge der Themen und Beiträge reorganisiert werden.
- Abgehakte, reorganisierte Themen und Beiträge können in HTML oder Plain Text exportiert werden.
- Nicht exportierte Themen und Beiträge können verworfen oder in die nächste Episode übernommen werden. 

# Voraussetzungen

podflow! wurde zunächst für Podcaster entwickelt, die ihren Podcast selbst hosten bzw. administrativen (FTP-) Zugriff auf ihren Webspace haben. Wenn du zum Beispiel in der Lage bist, WordPress selbst zu installieren, dürfte dir die Installation von podflow! keine Probleme bereiten. Vielleicht kennst du aber auch jemanden, der die Installation und/oder das Hosting einer podflow! Instanz für dich übernehmen kann.

Folgende Voraussetzung müssen erfüllt sein, damit podflow!  installiert werden kann:

- FTP-Zugang mit Schreibzugriff auf deinen Webspace
- Eine MySQL- Datenbank in der Version 5.1 oder höher
- MySQLi-Unterstützung 
- PHP in der Version 7.0 oder höher
- Unterstützung von Sessions 

# Wie installiere ich podflow?

- Kopiere die Dateien aus dem Archiv auf deinem Webspace
- rufe deinedomain.de/setup/install.php auf
- Folge den Answeisungen

Es muss bei der Installation bereits ein Benutzer und ein Podcast angegeben werden.

# Wie update ich podflow?

- Mache dir ein Backup des Installations-Ordner und der Datenbank (z.B. mit phpmyadmin)
- Entferne alle Dateien und Ordner **AUßER** dem config-Verzeichnis von deinem FTP-Server
- Lade alle Dateien und Ordner **AUßER** dem config-Verzeichnis auf deinen FTP-Server
- rufe deinedomain.de/setup/update.php auf
- Folge den Anweisungen

# Wie benutze ich podflow?

Eine ausführliche Anleitung zur Nutzung von podflow! findest du unter [https://www.podflow.de/](https://www.podflow.de/)

# Lizenz

[![GPL3](https://img.shields.io/badge/licence-GPL3-green.svg)](https://www.gnu.org/licenses/gpl-3.0.de.html)
