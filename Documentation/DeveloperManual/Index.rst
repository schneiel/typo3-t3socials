.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _developer-manual:

Developer manual
================

Dieser Abschnitt soll einen kurzen Überblick über die technische Umsetzung
und mögliche Erweiterungen von T3 SOCIALS zeigen. Das soll am Beispiel
des implementierten automatischen Versands der News-Meldungen 
über das netzwerk Twitter erfolgen.

Um den Automatismus kümmert sich komplett T3 SOCIALS.
Neue Netwerke oder Trigger müssen dies nicht mit beachten!

Der Service wird von einem Trigger über eine neue Nachricht informiert.

Beispielsweise fängt der Trigger für News beim Speichern einer neuen
News diese ab, wandelt die News in eine genärische Messaage um und
gibt diese Message an den Service weiter. Dies geschieht nur,
wenn der Datensatz nicht gelöscht, nicht versteckt
und noch nicht über einen Account verteilt wurde.

Der Service nimmt die Message entgegen, bereited diese auf
und verteilt diese an alle Konfigurierten Netzwerke.
Dies sind alle Netzwerke deren ref:`accounts` für diesen Trigger defineirt sind.

Die Netzwerke nehmen die Message wiederum entgegen,
bauen die Statusmeldung zusammen
und geben diese an die entsprechenden Dienste weiter.

Das Zusammenbauen der Nachricht macht jedes Netzwerk
speziell für den verwendeten Dienst.
Für Twitter dar die Nachricht beispielsweise nur 160 Zeichen große sein.
Für die Umwandlung der nachricht verwendet die Netzwork-Instanz einen
Message-Builder. Mann kann per Konfiguration in dem Account
auch einen eigenen Message-Builder definieren.

Nach dem versand markiert der Service diesen Datensatz als versendet,
damit dieser automatisch nicht erneut verteilt werden kann.


Die Network-Instanz hat nun die Aufgabe,
die generische Message in eine konkrete,
für das jeweilige Netzwerk sinnvolle Nachricht zu übersetzen.
Die Twitter-Instanz wird also aus den Angaben einen 160 Zeichen langen String,
ggf. mit Link erstellen.
Die Instanz für pushd wird dagegen keine Links verschicken,
da dies für die Notifications nicht sinnvoll ist.

Für die Umwandlung der Nachrichten verwenden
die Network-Instanzen MessageBuilder.
Es wird immer ein Default-Bilder mitgeliefert,
man kann aber in speziellen Fällen weitere Builder per Konfiguration festlegen.
Beim obigen Beispiel des Livetickers  wurde ein spezieller Builder verwendet.
Dieser hat sogar die Möglichkeit den Versand einer Meldung noch zu unterbinden.
Die Möglichkeiten sind hier also sehr groß.

Wer nun Nachrichten für andere Datentypen absetzen will,
muss lediglich den News-Service als Vorlage nehmen.



.. toctree::
   :maxdepth: 5
   :titlesonly:
   :glob:

   Network
   Trigger
