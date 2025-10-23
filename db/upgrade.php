<?php

defined('MOODLE_INTERNAL') || die();

if (!function_exists('block_courseintro_normalize_legacy_value')) {
    /**
     * Normalizes legacy configuration values by converting any __PHP_Incomplete_Class
     * instances into arrays/stdClass objects so that property access works reliably.
     *
     * @param mixed $value The value to normalise.
     * @return mixed The normalised value.
     */
    function block_courseintro_normalize_legacy_value($value)
    {
        if (is_array($value)) {
            $normalised = [];

            foreach ($value as $key => $item) {
                if (is_string($key)) {
                    $key = preg_replace('/^\0.+\0/', '', $key);
                }

                $normalised[$key] = block_courseintro_normalize_legacy_value($item);
            }

            return $normalised;
        }

        if (is_object($value) && get_class($value) === '__PHP_Incomplete_Class') {
            $value = block_courseintro_normalize_legacy_value((array)$value);

            return (object)$value;
        }

        return $value;
    }
}

/**
 * Upgrade steps for the Course intro block.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_courseintro_upgrade($oldversion)
{
    global $DB;

    if ($oldversion < 2025091001) {
        $instances = $DB->get_records('block_instances', ['blockname' => 'courseintro']);

        foreach ($instances as $instance) {
            if (empty($instance->configdata)) {
                continue;
            }

            $decoded = base64_decode($instance->configdata, true);
            if ($decoded === false) {
                continue;
            }

            $config = @unserialize($decoded, ['allowed_classes' => ['stdClass']]);
            if ($config === false && $decoded !== 'b:0;') {
                continue;
            }

            $config = block_courseintro_normalize_legacy_value($config);

            if (is_array($config)) {
                $config = (object)$config;
            } else if (!is_object($config)) {
                $config = new stdClass();
            }

            $updated = false;

            if (!isset($config->contacts) && !empty($config->config_contacts)) {
                $legacy = $config->config_contacts;
                $contacts = [];

                if (is_string($legacy)) {
                    $legacy = preg_split('/[\r\n,]+/', $legacy, -1, PREG_SPLIT_NO_EMPTY);
                }

                if (is_array($legacy)) {
                    foreach ($legacy as $email) {
                        $email = trim((string)$email);
                        if ($email !== '') {
                            $contacts[] = $email;
                        }
                    }
                }

                $config->contacts = $contacts;
                $updated = true;
            }

            if (!isset($config->committee) && !empty($config->config_committee) && is_array($config->config_committee)) {
                $committee = [];

                foreach ($config->config_committee as $entry) {
                    if (is_object($entry)) {
                        $entry = (array)$entry;
                    }

                    if (!is_array($entry)) {
                        continue;
                    }

                    $name = trim((string)($entry['name'] ?? ''));
                    $desc = trim((string)($entry['desc'] ?? ''));

                    if ($name !== '' || $desc !== '') {
                        $committee[] = [
                            'name' => $name,
                            'desc' => $desc,
                        ];
                    }
                }

                $config->committee = $committee;
                $updated = true;
            }

            if (!isset($config->calendar) && !empty($config->config_calendar) && is_array($config->config_calendar)) {
                $calendar = [];

                foreach ($config->config_calendar as $entry) {
                    if (is_object($entry)) {
                        $entry = (array)$entry;
                    }

                    if (!is_array($entry)) {
                        continue;
                    }

                    $timestamp = isset($entry['date']) ? (int)$entry['date'] : 0;
                    $rawentries = $entry['entries'] ?? [];

                    if (is_string($rawentries)) {
                        $rawentries = preg_split('/[\r\n]+/', $rawentries, -1, PREG_SPLIT_NO_EMPTY);
                    }

                    if (is_array($rawentries)) {
                        $entries = [];
                        foreach ($rawentries as $line) {
                            $line = trim((string)$line);
                            if ($line !== '') {
                                $entries[] = $line;
                            }
                        }
                    } else {
                        $entries = [];
                    }

                    if ($timestamp > 0 && !empty($entries)) {
                        $calendar[] = [
                            'date' => $timestamp,
                            'entries' => $entries,
                        ];
                    }
                }

                $config->calendar = $calendar;
                $updated = true;
            }

            foreach (['config_contacts', 'config_committee', 'config_calendar'] as $legacykey) {
                if (property_exists($config, $legacykey)) {
                    unset($config->{$legacykey});
                    $updated = true;
                }
            }

            if ($updated) {
                $instance->configdata = base64_encode(serialize($config));
                $DB->update_record('block_instances', $instance);
            }
        }

        upgrade_block_savepoint(true, 2025091001, 'courseintro');
    }

    return true;
}
