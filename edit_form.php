<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/courseintro/lib.php');

class block_courseintro_edit_form extends block_edit_form
{

    protected function specific_definition($mform)
    {
        global $COURSE;

        $mform->addElement('header', 'configheader', get_string('pluginname', 'block_courseintro'));

        // === BANNER ===
        $mform->addElement('static', 'bannersection', get_string('bannersection', 'block_courseintro'));
        $mform->addElement('advcheckbox', 'config_usecoursebanner', get_string('usecoursebanner', 'block_courseintro'));
        $mform->addHelpButton('config_usecoursebanner', 'usecoursebanner', 'block_courseintro');
        $mform->setDefault('config_usecoursebanner', 1);
        $mform->setType('config_usecoursebanner', PARAM_INT);

        $draftitemid = file_get_submitted_draft_itemid('config_bannerimage');
        // Use a stable itemid for the permanent area. If an older config stored
        // a custom itemid, keep using it to show existing files; otherwise fall back
        // to the instance id so subsequent edits always preload correctly.
        $existingitemid = !empty($this->block->config->bannerimage)
            ? (int)$this->block->config->bannerimage
            : (int)$this->block->instance->id;

        file_prepare_draft_area(
            $draftitemid,
            $this->block->context->id,
            'block_courseintro',
            'bannerimage',
            $existingitemid,
            ['subdirs' => 0]
        );

        $mform->addElement('filemanager', 'config_bannerimage', get_string('bannerimage', 'block_courseintro'), null, [
            'subdirs' => 0,
            'maxbytes' => 0,
            'maxfiles' => 1,
            'accepted_types' => ['.jpg', '.jpeg', '.png', '.gif']
        ]);

        $mform->disabledIf('config_bannerimage', 'config_usecoursebanner', 'checked');
        $mform->hideIf('config_bannerimage', 'config_usecoursebanner', 'checked');

        $mform->setDefault('config_bannerimage', $draftitemid);

        // === DATE (SOLO LETTURA) ===
        $mform->addElement('header', 'datesection', get_string('coursedatesection', 'block_courseintro'));

        $mform->addElement('static', 'startdate', get_string('startdate', 'block_courseintro'), userdate($COURSE->startdate));
        $mform->addHelpButton('startdate', 'startdate', 'block_courseintro');

        $mform->addElement('static', 'enddate', get_string('enddate', 'block_courseintro'), userdate($COURSE->enddate));
        $mform->addHelpButton('enddate', 'enddate', 'block_courseintro');

        $mform->addElement('advcheckbox', 'config_showdates', get_string('showdates', 'block_courseintro'));
        $mform->addHelpButton('config_showdates', 'showdates', 'block_courseintro');
        $mform->setDefault('config_showdates', 1);


        // === PILLOLE ===
        $mform->addElement('header', 'pillsection', get_string('pillsection', 'block_courseintro'));

        $repeatarray = [];
        $repeatarray[] = $mform->createElement('text', 'config_pills', get_string('pilllabel', 'block_courseintro'));
        $mform->setType('config_pills', PARAM_TEXT);

        $repeateloptions = [];
        $repeateloptions['config_pills']['default'] = '';
        $repeateloptions['config_pills']['type'] = PARAM_TEXT;

        // Verifica se esistono pillole salvate, e usa quel numero per impostare le ripetizioni
        $defaultpills = isset($this->block->config->pills) ? (array)$this->block->config->pills : [];
        $repeatno = max(3, count($defaultpills)); // almeno 3

        $this->repeat_elements(
            $repeatarray,
            $repeatno,
            $repeateloptions,
            'config_pills_repeats',
            'config_pills_add_fields',
            5,
            get_string('addpill', 'block_courseintro'),
            true
        );

        // Imposta i valori salvati come default
        foreach ($defaultpills as $i => $pilltext) {
            $mform->setDefault("config_pills[$i]", $pilltext);
        }

        // === CONTATTI ===
        $mform->addElement('header', 'contactsection', get_string('contactsection', 'block_courseintro'));
        $repeatcontacts = [];
        $repeatcontacts[] = $mform->createElement('text', 'config_contacts', get_string('contactemail', 'block_courseintro'));
        $mform->setType('config_contacts', PARAM_EMAIL);

        $repeateloptions = [];
        $repeateloptions['config_contacts']['type'] = PARAM_EMAIL;

        // Valori salvati
        $defaultcontacts = isset($this->block->config->contacts) ? (array)$this->block->config->contacts : [];
        $repeatno = max(1, count($defaultcontacts));

        $this->repeat_elements(
            $repeatcontacts,
            $repeatno,
            $repeateloptions,
            'config_contacts_repeats',
            'config_contacts_add_fields',
            1,
            get_string('addcontact', 'block_courseintro'),
            true
        );

        // Default
        foreach ($defaultcontacts as $i => $email) {
            $mform->setDefault("config_contacts[$i]", $email);
        }


        // === DIRETTORE SCIENTIFICO ===
        $mform->addElement('header', 'directorsection', get_string('directorsection', 'block_courseintro'));
        // Campo: Nome e Cognome
        $mform->addElement('text', 'config_directorname', get_string('directorname', 'block_courseintro'));
        $mform->setType('config_directorname', PARAM_TEXT);
        $mform->setDefault('config_directorname', '');

        // Campo: Descrizione
        $mform->addElement('textarea', 'config_directordesc', get_string('directordesc', 'block_courseintro'), 'wrap="virtual" rows="5" cols="50"');
        $mform->setType('config_directordesc', PARAM_TEXT);
        $mform->setDefault('config_directordesc', '');

        // === COMITATO SCIENTIFICO ===
        $mform->addElement('header', 'committeesection', get_string('committeesection', 'block_courseintro'));
        // Definizione dei campi ripetibili: nome + descrizione
        $repeatcommittee = [];
        $repeatcommittee[] = $mform->createElement('text', 'config_committeename', get_string('committeename', 'block_courseintro'));
        $repeatcommittee[] = $mform->createElement('textarea', 'config_committeedesc', get_string('committeedesc', 'block_courseintro'), 'wrap="virtual" rows="4" cols="50"');

        // Tipo dei campi
        $mform->setType('config_committeename', PARAM_TEXT);
        $mform->setType('config_committeedesc', PARAM_TEXT);

        // Element options
        $repeateloptions = [];
        $repeateloptions['config_committeename']['type'] = PARAM_TEXT;
        $repeateloptions['config_committeedesc']['type'] = PARAM_TEXT;

        // Recupera dati salvati
        $defaultcommittee = isset($this->block->config->committee) && is_array($this->block->config->committee)
            ? $this->block->config->committee
            : [];

        $repeatno = max(3, count($defaultcommittee));

        $this->repeat_elements(
            $repeatcommittee,
            $repeatno,
            $repeateloptions,
            'config_committee_repeats',
            'config_committee_add_fields',
            1,
            get_string('addcommittee', 'block_courseintro'),
            true
        );

        // Popola i dati esistenti
        foreach ($defaultcommittee as $i => $member) {
            if (isset($member['name'])) {
                $mform->setDefault("config_committeename[$i]", $member['name']);
            }
            if (isset($member['desc'])) {
                $mform->setDefault("config_committeedesc[$i]", $member['desc']);
            }
        }

        // === CALENDARIO CON ARGOMENTI ===
        $mform->addElement('header', 'calendarsection', get_string('calendarsection', 'block_courseintro'));
        // Recupera valori salvati
        $defaultcalendar = isset($this->block->config->calendar) && is_array($this->block->config->calendar)
            ? $this->block->config->calendar
            : [];

        $repeatno = max(3, count($defaultcalendar)); // almeno 3 giorno

        // Elementi per ogni giornata: data + argomenti
        $repeatcalendar = [];
        $repeatcalendar[] = $mform->createElement('date_selector', 'config_calendardate', get_string('calendardate', 'block_courseintro'));
        $repeatcalendar[] = $mform->createElement('textarea', 'config_calendarentries', get_string('calendarentries', 'block_courseintro'), 'wrap="virtual" rows="4" cols="50"');

        // Tipi
        $mform->setType('config_calendarentries', PARAM_TEXT);

        // Opzioni
        $repeateloptions = [];
        $repeateloptions['config_calendardate']['type'] = PARAM_INT;
        $repeateloptions['config_calendarentries']['type'] = PARAM_TEXT;
        $repeateloptions['config_calendarentries']['helpbutton'] = ['calendarentries', 'block_courseintro'];


        // Repeat
        $this->repeat_elements(
            $repeatcalendar,
            $repeatno,
            $repeateloptions,
            'config_calendar_repeats',
            'config_calendar_add_fields',
            5,
            get_string('addcalendarentry', 'block_courseintro'),
            true
        );

        // Popola default
        foreach ($defaultcalendar as $i => $entry) {
            if (!empty($entry['date'])) {
                $mform->setDefault("config_calendardate[$i]", $entry['date']);
            }
            if (!empty($entry['entries'])) {
                $mform->setDefault("config_calendarentries[$i]", implode("\n", $entry['entries']));
            }
        }
    }
}
