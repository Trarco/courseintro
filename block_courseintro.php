<?php

defined('MOODLE_INTERNAL') || die();

class block_courseintro extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_courseintro');
    }

    public function applicable_formats()
    {
        return ['course-view' => true, 'site' => false, 'my' => false];
    }

    public function instance_allow_multiple()
    {
        return false;
    }

    public function has_config()
    {
        return false;
    }

    public function get_content()
    {
        global $COURSE, $OUTPUT, $PAGE;

        if ($this->content !== null) {
            return $this->content;
        }

        $PAGE->requires->css(new moodle_url('/blocks/courseintro/style.css'));

        if (!isset($this->config) || !is_object($this->config)) {
            $this->config = new stdClass();
        }

        $data = new stdClass();
        $fmt = new IntlDateFormatter(current_language(), IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $data->startdate = ucfirst($fmt->format($COURSE->startdate));
        $data->enddate = ucfirst($fmt->format($COURSE->enddate));
        $data->coursename = $COURSE->fullname;
        $data->bannerurl = '';


        debugging('[BLOCK] bannerimage config: ' . print_r($this->config->bannerimage, true), DEBUG_DEVELOPER);

        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $this->context->id,
            'block_courseintro',
            'bannerimage',
            0,
            'itemid, filepath, filename',
            false
        );

        debugging('[BLOCK] file count in bannerimage area: ' . count($files), DEBUG_DEVELOPER);

        if (!empty($this->config->bannerimage)) {
            $itemid = $this->config->bannerimage;

            $fs = get_file_storage();
            $files = $fs->get_area_files(
                $this->context->id,
                'block_courseintro',
                'bannerimage',
                $itemid,
                'itemid, filepath, filename',
                false
            );
            if (!empty($files)) {
                $file = reset($files);
                debugging('[BLOCK] found file: ' . $file->get_filename(), DEBUG_DEVELOPER);

                $data->bannerurl = moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                )->out(false);
                debugging('[BLOCK] banner URL generated: ' . $data->bannerurl, DEBUG_DEVELOPER);
            }
        }

        // === PILLOLE ===
        if (!empty($this->config->pills)) {
            $data->pills = is_array($this->config->pills) ? $this->config->pills : [$this->config->pills];
        } else {
            $data->pills = [];
        }
        debugging('[BLOCK] pills: ' . print_r($data->pills, true), DEBUG_DEVELOPER);

        // === CONTATTI ===
        $data->contacts = !empty($this->config->config_contacts) ? (array)$this->config->config_contacts : [];


        // === DIRETTORE SCIENTIFICO ===
        $data->directorname = !empty($this->config->directorname) ? $this->config->directorname : '';
        $data->directordesc = !empty($this->config->directordesc) ? $this->config->directordesc : '';
        debugging('[BLOCK] director name: ' . $data->directorname, DEBUG_DEVELOPER);
        debugging('[BLOCK] director desc: ' . $data->directordesc, DEBUG_DEVELOPER);

        // === COMITATO SCIENTIFICO ===
        $data->committee = [];
        if (!empty($this->config->config_committee)) {
            $committeeData = $this->config->config_committee;
            debugging('[BLOCK] committee in config_committee: ' . print_r($committeeData, true), DEBUG_DEVELOPER);

            if (is_array($committeeData)) {
                foreach ($committeeData as $entry) {
                    $name = trim($entry['name'] ?? '');
                    $desc = trim($entry['desc'] ?? '');
                    if ($name !== '' || $desc !== '') {
                        $data->committee[] = [
                            'membername' => (string)$name,
                            'memberdesc' => (string)$desc
                        ];
                    }
                }
            }
        }


        debugging('[BLOCK] committee: ' . print_r($data->committee, true), DEBUG_DEVELOPER);


        if (!empty($this->config->config_calendar) && is_array($this->config->config_calendar)) {
            $fmtcalendar = new IntlDateFormatter(current_language(), IntlDateFormatter::NONE, IntlDateFormatter::NONE);
            $fmtcalendar->setPattern('d MMMM yyyy'); // Es. 4 luglio 2025

            foreach ($this->config->config_calendar as $entry) {
                $formatted = $fmtcalendar->format((int)$entry['date']);
                $entries = is_array($entry['entries']) ? $entry['entries'] : [];

                $data->calendar[] = [
                    'date' => ucfirst($formatted),
                    'entries' => $entries
                ];
            }
        }

        debugging('[BLOCK] calendar: ' . print_r($data->calendar, true), DEBUG_DEVELOPER);

        $this->content = new stdClass();
        $this->content->text = $OUTPUT->render_from_template('block_courseintro/content', $data);
        $this->content->footer = '';

        return $this->content;
    }


    public function instance_config_save($data, $nolongerused = false)
    {
        global $CFG;

        // === Gestione Banner ===
        if (!empty($data->bannerimage)) {
            $draftitemid = $data->bannerimage;

            file_save_draft_area_files(
                $draftitemid,
                $this->context->id,
                'block_courseintro',
                'bannerimage',
                $draftitemid, // USA itemid = draftitemid
                ['subdirs' => 0, 'maxbytes' => 0, 'accepted_types' => ['.jpg', '.jpeg', '.png', '.gif']]
            );

            // SALVA itemid per recupero successivo
            $data->bannerimage = $draftitemid;
        }

        // === Gestione Pills ===
        $pills = [];

        if (!empty($data->pills) && is_array($data->pills)) {
            foreach ($data->pills as $pill) {
                $pill = trim($pill);
                if (!empty($pill)) {
                    $pills[] = $pill;
                }
            }
        }

        $data->pills = $pills;
        $data->config_contacts = $pills;

        // === Gestione Contatti Email ===
        $contacts = [];

        if (!empty($data->contacts) && is_array($data->contacts)) {
            foreach ($data->contacts as $email) {
                $email = trim($email);
                if (!empty($email) && validate_email($email)) {
                    $contacts[] = $email;
                }
            }
        }

        $data->contacts = $contacts;
        $data->config_contacts = $contacts;

        // === Gestione Comitato Scientifico ===
        $committee = [];

        if (!empty($data->committeename) && is_array($data->committeename)) {
            foreach ($data->committeename as $index => $name) {
                $name = trim($name);
                $desc = trim($data->committeedesc[$index] ?? '');

                if ($name !== '' || $desc !== '') {
                    $committee[] = [
                        'name' => $name,
                        'desc' => $desc
                    ];
                }
            }
        }

        $data->committee = $committee;
        $data->config_committee = $committee;

        debugging('[BLOCK SAVE] $data->committee: ' . print_r($data->committee, true), DEBUG_DEVELOPER);
        debugging('[BLOCK SAVE] Dati completi: ' . print_r($data, true), DEBUG_DEVELOPER);

        unset($data->config_committeename, $data->config_committeedesc);

        // === Gestione Calendario ===
        $calendar = [];

        if (!empty($data->calendardate) && is_array($data->calendardate)) {
            foreach ($data->calendardate as $index => $timestamp) {
                $rawentries = trim($data->calendarentries[$index] ?? '');
                $entries = array_filter(array_map('trim', explode("\n", $rawentries)));

                if ($timestamp && !empty($entries)) {
                    $calendar[] = [
                        'date' => (int)$timestamp,
                        'entries' => $entries
                    ];
                }
            }
        }
        $data->config_calendar = $calendar;
        unset($data->calendar);

        file_put_contents($CFG->dataroot . '/temp/calendar_debug.log', print_r($data, true), FILE_APPEND);

        parent::instance_config_save($data, $nolongerused);
    }
}
