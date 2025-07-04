# Moodle Block: Course Introduction

Il blocco **Course Introduction** permette di visualizzare in modo strutturato e accessibile le informazioni essenziali di un corso Moodle, migliorando l’esperienza dell’utente grazie a un’interfaccia chiara e responsive.

## Funzionalità principali

* Visualizzazione di:

  * **Banner del corso**
  * **Date di inizio e fine** (solo lettura)
  * **Learning pills** (etichettate e personalizzabili)
  * **Direttore scientifico**
  * **Comitato scientifico**
  * **Contatti email**
  * **Calendario** con date e argomenti, in layout responsive a due colonne

* **Interfaccia utente ottimizzata** per accessibilità e usabilità

* **Compatibile con Moodle 4.x+**

## Screenshot

![Esempio interfaccia](docs/screenshot.png)

## Installazione

1. Clona o scarica il repository:

   ```bash
   git clone https://github.com/tuo-utente/block_courseintro.git
   ```

2. Copia la cartella nel path:
   `your-moodle-dir/blocks/courseintro`

3. Accedi a Moodle come amministratore e completa l'installazione dal pannello notifiche.

## Traduzioni supportate

* Italiano
* Inglese

## Configurazione

Una volta aggiunto il blocco a un corso, potrai personalizzarne il contenuto tramite l'interfaccia grafica.
Le date di inizio/fine sono sincronizzate con le impostazioni del corso Moodle e non possono essere modificate dal blocco.

Per ogni sezione potrai:

* Aggiungere elementi dinamici (pillole, contatti, voci di calendario)
* Visualizzare contenuti solo se valorizzati
* Inserire HTML nei campi descrittivi (con filtri Moodle attivi)

## Sviluppo

Tecnologie utilizzate:

* PHP (conforme allo standard Moodle)
* Mustache Templates
* CSS personalizzato (caricato automaticamente dal blocco)
* Bootstrap 5 (compatibile con il tema Boost)

## Licenza

Questo plugin è distribuito sotto licenza [GNU GPL v3](http://www.gnu.org/licenses/gpl-3.0.html).

---

### Autore

Sviluppato da \[Tuo Nome o Azienda]
Per supporto o richieste: \[[email@example.com](mailto:email@example.com)]
