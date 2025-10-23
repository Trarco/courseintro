<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

/**
 * English language strings for block_courseintro
 *
 * @package   block_courseintro
 * @copyright Trarco
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Sicurezza
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Course Introduction';
// Capabilities
$string['courseintro:addinstance'] = 'Add a new Course Introduction block';
$string['courseintro:myaddinstance'] = 'Add a new Course Introduction block to Dashboard';
// Privacy
$string['privacy:metadata'] = 'The Course Introduction block does not store any personal data.';

// Input section names
$string['bannersection'] = 'Banner';
$string['coursedatesection'] = 'Course Dates';
$string['pillsection'] = 'Learning pills';
$string['contactsection'] = 'Contacts';
$string['directorsection'] = 'Scientific Director';
$string['committeesection'] = 'Scientific Committee';
$string['calendarsection'] = 'Calendar';

// Input labels and titles
$string['bannerimage'] = 'Banner image';
$string['usecoursebanner'] = 'Use course overview banner image';
$string['usecoursebanner_help'] = 'If checked, the block will display the first valid course overview image instead of the custom banner uploaded below.';

$string['startdate'] = 'Course start date';
$string['enddate'] = 'Course end date';
$string['startdate_help'] = 'The course start date can only be changed from the course settings page.';
$string['enddate_help'] = 'The course end date can only be changed from the course settings page.';
$string['showdates'] = 'Show course start and end dates';
$string['showdates_help'] = 'If checked, the course start and end dates will be displayed in the frontend.';

$string['pilllabel'] = 'Pill';
$string['pillstitle'] = 'Learning pills';
$string['contactemail'] = 'Email address';
$string['directorname'] = 'Name and surname';
$string['directordesc'] = 'Description';
$string['committeename'] = 'Name and surname';
$string['committeedesc'] = 'Description';
$string['calendardate'] = 'Date';
$string['calendarentries'] = 'Topics';
$string['calendarentries_help'] = 'Write one topic per line. Each line will be shown as a separate bullet point.';

// Titles for frontend display
$string['contactsheading'] = 'Contacts';
$string['directorheading'] = 'Scientific Director';
$string['committeeheading'] = 'Scientific Committee and Faculty';
$string['calendarheading'] = 'Calendar';

// Bottoni per aggiunta campi
$string['addpill'] = 'Add 5 pills';
$string['addcontact'] = 'Add another contact';
$string['addcommittee'] = 'Add another committee member';
$string['addcalendarentry'] = 'Add 5 calendar entries';
