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
        global $COURSE, $OUTPUT, $CFG;

        require_once($CFG->dirroot . '/course/lib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        if (!isset($this->config) || !is_object($this->config)) {
            $this->config = new stdClass();
        }

        $data = new stdClass();

        $data->showdates = !empty($this->config->showdates);

        if ($data->showdates) {
            $datefmt = get_string('strftimedaydate', 'langconfig');
            $data->startdate = userdate($COURSE->startdate, $datefmt);
            if (!empty($COURSE->enddate)) {
                $data->enddate = userdate($COURSE->enddate, $datefmt);
            }
        }

        $data->coursename = $COURSE->fullname;
        $data->bannerurl = '';
        $usecoursebanner = isset($this->config->usecoursebanner)
            ? (bool)$this->config->usecoursebanner
            : true;

        if ($usecoursebanner) {
            if (function_exists('course_get_course_overviewfiles')) {
                // Moodle <= 4.0
                $overviewfiles = course_get_course_overviewfiles($COURSE);
                foreach ($overviewfiles as $file) {
                    if ($file->is_valid_image()) {
                        $data->bannerurl = moodle_url::make_pluginfile_url(
                            $file->get_contextid(),
                            $file->get_component(),
                            $file->get_filearea(),
                            $file->get_itemid(),
                            $file->get_filepath(),
                            $file->get_filename()
                        )->out(false);
                        break;
                    }
                }
            } else if (class_exists('\core_course\external\course_summary_exporter')) {
                // Moodle >= 4.1
                $course = get_course($COURSE->id);
                $imageurl = \core_course\external\course_summary_exporter::get_course_image($course);
                if (!empty($imageurl)) {
                    $data->bannerurl = $imageurl;
                }
            }
        } else {
            $itemid = !empty($this->config->bannerimage) ? (int)$this->config->bannerimage : (int)$this->instance->id;
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
                $data->bannerurl = moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                )->out(false);
            }
        }

        // === PILLOLE ===
        if (!empty($this->config->pills)) {
            $data->pills = is_array($this->config->pills) ? $this->config->pills : [$this->config->pills];
        } else {
            $data->pills = [];
        }

        // === CONTATTI ===
        $data->contacts = !empty($this->config->contacts) ? (array)$this->config->contacts : [];
        if (empty($data->contacts) && !empty($this->config->config_contacts)) {
            $data->contacts = (array)$this->config->config_contacts; // Back-compat.
        }


        // === DIRETTORE SCIENTIFICO ===
        $data->directorname = !empty($this->config->directorname) ? $this->config->directorname : '';
        $data->directordesc = !empty($this->config->directordesc) ? $this->config->directordesc : '';

        // === COMITATO SCIENTIFICO ===
        $data->committee = [];
        $committeeSource = [];
        if (!empty($this->config->committee) && is_array($this->config->committee)) {
            $committeeSource = $this->config->committee;
        } else if (!empty($this->config->config_committee) && is_array($this->config->config_committee)) {
            $committeeSource = $this->config->config_committee; // Back-compat.
        }
        if ($committeeSource) {
            foreach ($committeeSource as $entry) {
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

        $calendarSource = [];
        if (!empty($this->config->calendar) && is_array($this->config->calendar)) {
            $calendarSource = $this->config->calendar;
        } else if (!empty($this->config->config_calendar) && is_array($this->config->config_calendar)) {
            $calendarSource = $this->config->config_calendar; // Back-compat.
        }
        if ($calendarSource) {
            $data->calendar = [];
            $datefmt = get_string('strftimedaydate', 'langconfig');
            foreach ($calendarSource as $entry) {
                $formatted = userdate((int)($entry['date'] ?? 0), $datefmt);
                $entries = !empty($entry['entries']) && is_array($entry['entries']) ? $entry['entries'] : [];
                if ($formatted && $entries) {
                    $data->calendar[] = [
                        'date' => $formatted,
                        'entries' => $entries
                    ];
                }
            }
        }

        $this->content = new stdClass();
        $this->content->text = $OUTPUT->render_from_template('block_courseintro/content', $data);
        $this->content->footer = '';

        return $this->content;
    }


    public function instance_config_save($data, $nolongerused = false)
    {
        // Clean and normalize config data to Moodle standards.

        // === Gestione Banner ===
        $draftitemid = 0;
        if (!empty($data->config_bannerimage)) {
            $draftitemid = (int)$data->config_bannerimage;
        } else if (!empty($data->bannerimage)) {
            // Fallback if parent already stripped the prefix.
            $draftitemid = (int)$data->bannerimage;
        }

        if ($draftitemid) {
            $permanentitemid = (int)$this->instance->id;
            file_save_draft_area_files(
                $draftitemid,
                $this->context->id,
                'block_courseintro',
                'bannerimage',
                $permanentitemid,
                ['subdirs' => 0, 'maxbytes' => 0, 'accepted_types' => ['.jpg', '.jpeg', '.png', '.gif']]
            );
            unset($data->bannerimage);
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
        $data->calendar = $calendar;
        unset($data->config_pills, $data->config_contacts, $data->config_calendardate, $data->config_calendarentries);

        parent::instance_config_save($data, $nolongerused);
    }
}
