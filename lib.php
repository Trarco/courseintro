<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Serve files from the block_courseintro file areas.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool false if file not found, does not return if found (sends file)
 */
function block_courseintro_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = [])
{
    if ($context->contextlevel !== CONTEXT_BLOCK) {
        return false;
    }

    if ($filearea !== 'bannerimage') {
        return false;
    }

    $itemid = array_shift($args);
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'block_courseintro', 'bannerimage', $itemid, $filepath, $filename);

    if (!$file || $file->is_directory()) {
        return false;
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}
