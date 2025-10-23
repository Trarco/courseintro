# Changelog

All notable changes to this project are documented in this file.

## [1.0.4] – 2025-10-23
### Added
- Toggle to reuse the Moodle course overview image as the block banner or switch back to a custom upload.

### Fixed
- Restored the default visibility of the course image when no custom banner is provided.
- Ensured the "Use course banner" checkbox behaves correctly and keeps the manual upload field disabled when active.

## [1.0.3] – 2025-10-15
### Added
- Documented supported languages and upgrade expectations in the README.

### Changed
- Clarified developer notes with quick-start testing instructions in the README.

## [1.0.2] – 2025-09-10
### Added
- GDPR Privacy API null provider and related language strings.

### Changed
- Date formatting switched to `userdate()` with `langconfig` format for better internationalisation.
- CSS moved to `styles.css` for Moodle auto-loading (removed manual inclusion).
- Normalised configuration keys to store values without the `config_` prefix; added backward compatibility when reading old configs.

### Removed
- Debug file writes to dataroot (non-compliant with coding guidelines).

## [1.0.1] – 2025-08-01
### Changed
- Switched banner image input from `filepicker` to `filemanager` in the block configuration form, enabling file preview, drag & drop, and deletion.

### Removed
- Legacy automatic migration logic for `itemid = 0` banner files. Manual reupload required for pre-existing blocks.

## [1.0.0] – 2025-08-01
### Added
- Initial release of the Course Introduction block for Moodle.
- Display of course banner, start/end dates, learning pills, scientific director, scientific committee, email contacts, and calendar with topics.
- Graphical configuration via Moodle block settings form.
- Full Mustache templating for frontend rendering.
- Italian and English language support.

### Fixed
- Prevented display of end date if not defined in Moodle course settings.
- Correct handling of `config_showdates` toggle in block configuration.