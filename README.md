# Moodle Block: Course Introduction

The Course Introduction block displays key course information in a clean, responsive layout.

## Features

- Course banner with automatic reuse of the Moodle course overview image or a custom upload via file manager
- Start and end dates (read-only; taken from course settings)
- Learning pills (simple labels)
- Scientific Director (name + short description)
- Scientific Committee (name + short description, repeatable)
- Email contacts (repeatable)
- Calendar with dates and topics (repeatable; two-column responsive layout)

## Supported Languages

- English (`lang/en`)
- Italian (`lang/it`)

## Requirements

- Moodle 4.0 or later

## Installation

1. Copy this folder to `moodle/blocks/courseintro`.
2. Log in as administrator and complete the installation from the Notifications page.

Alternatively, install from a ZIP package via Site administration → Plugins → Install plugins.

## Configuration

- Add the block to a course page, then configure it from the block’s Actions menu.
- The **Use course banner** toggle reuses the course overview image by default; disable it to manage a dedicated banner through the file manager.
- Course dates are read from course settings and cannot be edited in the block.
- Fields accept plain text (no HTML).
- Content sections render only when data is provided.

## Upgrade Notes

- Upgrades from versions prior to 1.0.2 automatically migrate legacy configuration keys
  (`config_contacts`, `config_committee`, `config_calendar`) to the new structure while
  keeping existing data intact.
- Legacy serialised data is normalised during upgrade to avoid issues with
  `__PHP_Incomplete_Class` objects when reading old configuration values.

## Capabilities

- `block/courseintro:addinstance`: add the block to a course
- `block/courseintro:myaddinstance`: add the block to Dashboard

## Privacy

This plugin stores no personal data and implements Moodle’s Privacy API as a null provider.

## Development

- PHP following Moodle coding guidelines
- Mustache templates for rendering output in `templates/`
- Styles auto-loaded from `styles.css`
- Compatible with Boost/Bootstrap 5

Run the lightweight regression check to verify the upgrade step:

```bash
php tests/upgrade_test.php
```

The script exercises the legacy-configuration migration logic without requiring a full
Moodle instance.

## License

GNU GPL v3 or later
