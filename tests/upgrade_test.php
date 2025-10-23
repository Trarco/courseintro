<?php

declare(strict_types=1);

define('MOODLE_INTERNAL', true);

require_once __DIR__ . '/../db/upgrade.php';

class block_courseintro_mockdb
{
    /** @var array<int, stdClass> */
    public $records = [];

    /**
     * @param stdClass[] $records
     */
    public function __construct(array $records)
    {
        foreach ($records as $record) {
            $this->records[$record->id] = $record;
        }
    }

    public function get_records(string $table, array $conditions): array
    {
        if ($table !== 'block_instances') {
            return [];
        }

        $matches = [];
        foreach ($this->records as $record) {
            $match = true;
            foreach ($conditions as $field => $value) {
                if (!property_exists($record, $field) || $record->{$field} !== $value) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                $matches[$record->id] = clone $record;
            }
        }

        return $matches;
    }

    public function update_record(string $table, stdClass $record): void
    {
        if ($table !== 'block_instances') {
            throw new RuntimeException('Unexpected table update.');
        }

        if (!isset($this->records[$record->id])) {
            throw new RuntimeException('Record not found: ' . $record->id);
        }

        $this->records[$record->id]->configdata = $record->configdata;
    }
}

$legacyconfig = (object)[
    'config_contacts' => "alice@example.com\n\n bob@example.com ",
    'config_committee' => [
        (object)['name' => 'Chair', 'desc' => 'Leads meetings'],
        (object)['name' => 'Secretary', 'desc' => 'Records notes'],
    ],
    'config_calendar' => [
        (object)['date' => 1700000000, 'entries' => "One\nTwo"],
        (object)['date' => 0, 'entries' => ''],
    ],
];

$legacyinstance = (object)[
    'id' => 1,
    'blockname' => 'courseintro',
    'configdata' => base64_encode(serialize($legacyconfig)),
];

$moderninstance = (object)[
    'id' => 2,
    'blockname' => 'courseintro',
    'configdata' => '',
];

$DB = new block_courseintro_mockdb([$legacyinstance, $moderninstance]);

function upgrade_block_savepoint(bool $result, int $version, string $component): void
{
    if ($component !== 'courseintro') {
        throw new RuntimeException('Unexpected component: ' . $component);
    }
}

xmldb_block_courseintro_upgrade(2024091000);

if (!isset($DB->records[1])) {
    throw new RuntimeException('Legacy instance missing after upgrade.');
}

$updated = $DB->records[1];
$decoded = base64_decode($updated->configdata, true);
if ($decoded === false) {
    throw new RuntimeException('Failed to decode configdata.');
}

$config = unserialize($decoded, ['allowed_classes' => true]);
if (!($config instanceof stdClass)) {
    throw new RuntimeException('Config is not an object.');
}

if ($config->contacts !== ['alice@example.com', 'bob@example.com']) {
    throw new RuntimeException('Contacts were not migrated correctly.');
}

if ($config->committee !== [
    ['name' => 'Chair', 'desc' => 'Leads meetings'],
    ['name' => 'Secretary', 'desc' => 'Records notes'],
]) {
    throw new RuntimeException('Committee was not migrated correctly.');
}

if ($config->calendar !== [
    ['date' => 1700000000, 'entries' => ['One', 'Two']],
]) {
    throw new RuntimeException('Calendar was not migrated correctly.');
}

foreach (['config_contacts', 'config_committee', 'config_calendar'] as $legacykey) {
    if (property_exists($config, $legacykey)) {
        throw new RuntimeException('Legacy key still present: ' . $legacykey);
    }
}

echo "Upgrade migration test passed.\n";
