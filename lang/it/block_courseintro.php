<?php

// Questo file fa parte di Moodle - http://moodle.org/
//
// Moodle è un software libero: puoi redistribuirlo e/o modificarlo
// secondo i termini della GNU General Public License come pubblicata dalla
// Free Software Foundation, sia la versione 3 della licenza, sia (a tua scelta) una versione successiva.

/**
 * File di lingua italiano per il blocco block_courseintro
 *
 * @package   block_courseintro
 * @copyright Trarco
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 o successiva
 */

defined('MOODLE_INTERNAL') || die();

// Nome del plugin
$string['pluginname'] = 'Introduzione al corso';
// Capacità
$string['courseintro:addinstance'] = 'Aggiungere un nuovo blocco Introduzione al corso';
$string['courseintro:myaddinstance'] = 'Aggiungere un nuovo blocco Introduzione al corso alla Dashboard';
// Privacy
$string['privacy:metadata'] = 'Il blocco Introduzione al corso non memorizza dati personali.';

// Nomi delle sezioni del form
$string['bannersection'] = 'Banner';
$string['coursedatesection'] = 'Date del corso';
$string['pillsection'] = 'Il corso in pillole';
$string['contactsection'] = 'Contatti';
$string['directorsection'] = 'Direttore scientifico';
$string['committeesection'] = 'Comitato scientifico';
$string['calendarsection'] = 'Calendario';

// Etichette e titoli dei campi del form
$string['bannerimage'] = 'Immagine del banner';
$string['usecoursebanner'] = 'Usa l\'immagine panoramica del corso';
$string['usecoursebanner_help'] = 'Se selezionato, il blocco mostrerà la prima immagine panoramica valida del corso invece del banner personalizzato caricato qui sotto.';

$string['startdate'] = 'Data inizio corso';
$string['enddate'] = 'Data fine corso';
$string['startdate_help'] = 'La data di inizio corso può essere modificata solo dalle impostazioni del corso.';
$string['enddate_help'] = 'La data di fine corso può essere modificata solo dalle impostazioni del corso.';
$string['showdates'] = 'Mostra date di inizio e fine corso';
$string['showdates_help'] = 'Se selezionato, le date di inizio e fine corso saranno visibili nel frontend.';

$string['pilllabel'] = 'Pillola';
$string['pillstitle'] = 'Il nostro corso in pillole';

$string['contactemail'] = 'Indirizzo email';

$string['directorname'] = 'Nome e cognome';
$string['directordesc'] = 'Descrizione';

$string['committeename'] = 'Nome e cognome';
$string['committeedesc'] = 'Descrizione';

$string['calendardate'] = 'Data';
$string['calendarentries'] = 'Argomenti';
$string['calendarentries_help'] = 'Scrivi un argomento per riga. Ogni riga sarà mostrata come un punto elenco separato.';

// Titoli per la visualizzazione frontend
$string['contactsheading'] = 'Contatti';
$string['directorheading'] = 'Direttore scientifico';
$string['committeeheading'] = 'Comitato scientifico e docenti';
$string['calendarheading'] = 'Calendario';

// Bottoni per aggiunta campi
$string['addpill'] = 'Aggiungi 5 pillole';
$string['addcontact'] = 'Aggiungi un altro contatto';
$string['addcommittee'] = 'Aggiungi un altro membro del comitato';
$string['addcalendarentry'] = 'Aggiungi 5 voci al calendario';

// Correzione encoding (sovrascrive le stringhe con caratteri corretti)
$string['startdate_help'] = 'La data di inizio corso può essere modificata solo dalle impostazioni del corso.';
$string['enddate_help'] = 'La data di fine corso può essere modificata solo dalle impostazioni del corso.';
$string['calendarentries_help'] = 'Scrivi un argomento per riga. Ogni riga sarà mostrata come un punto elenco separato.';
