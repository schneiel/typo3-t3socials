.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _developer-manual:

Developer manual
================

Dieser Abschnitt soll einen kurzen Überblick über die technische Umsetzung
und mögliche Erweiterungen von T3socials zeigen. Das soll am Beispiel
des implementierten automatischen Versands der News-Meldungen erfolgen.

Der Automatismus startet wie üblich mit einem TCE-Hook.
Dieser wird von der Klasse tx_t3socials_hooks_TCEHook bereitgestellt.
Im Hook wird zunächst lediglich geprüft, ob die News die grundsätzlichen
Anforderungen für den Trigger erfüllt. Falls ja wird der Newsdatensatz
an die News-Service-Klasse tx_t3socials_srv_News übergeben.

Dieser Service prüft zuerst, ob die News bereits verschickt wurde.
Dafür stellt T3socials eine generische Funkion bereit,
die auch von anderen Services genutzt werden kann.

Wenn die Nachricht nun verschickt werden muss,
dann fragt der News-Service zunächst beim Networkservice
nach registrierten Accounts für den Event news an.
Dabei handelt es sich genau um den String, der im Account-Datensatz
als Trigger eingetragen werden muss.

Sollten Accounts gefunden werden, dann wird vom News-Service
eine generische Message der Klasse tx_t3socials_models_Message instanziiert
und mit den relevanten Daten der News gefüttert.
Diese Message wird dann in einer Schleife für alle Accounts abgearbeitet.
Für jeden Account wird eine Network-Instanz abgerufen
und dieser wird die Message für den Versand übergeben.
Als letzte Aktion markiert der News-Service den News-Datensatz
als verschickt und ist mit seiner Arbeit fertig.

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