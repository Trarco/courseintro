<?php

namespace block_courseintro\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use core_privacy\local\metadata\null_provider;

/**
 * Privacy provider for block_courseintro.
 */
class provider implements null_provider {
    /**
     * Get the language string identifier explaining why this plugin stores no personal data.
     *
     * @return string
     */
    public static function get_reason(): string {
        return 'privacy:metadata';
    }
}

