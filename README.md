# Moodle Block: Course Introduction

The **Course Introduction** block provides a structured and accessible way to display key information about a Moodle course, enhancing the user experience with a clean and responsive interface.

## Key Features

* Displays:

  * **Course banner**
  * **Start and end dates** (read-only)
  * **Learning pills** (customizable labels)
  * **Scientific Director**
  * **Scientific Committee**
  * **Email contacts**
  * **Calendar** with dates and topics, shown in a responsive two-column layout

* **User-friendly interface** optimized for accessibility and usability

* **Compatible with Moodle 4.x+**

## Screenshot

![Interface Example](docs/screenshot.png)

## Installation

1. Clone or download the repository:

   ```bash
   git clone https://github.com/your-username/block_courseintro.git
   ```

2. Move the folder to:
   `your-moodle-dir/blocks/courseintro`

3. Log in to Moodle as an administrator and complete the installation from the notifications page.

## Supported Languages

* Italian
* English

## Configuration

Once added to a course, the block can be fully customized through the graphical interface.
Start and end dates are synced with the Moodle course settings and **cannot be edited from the block**.

For each section, you can:

* Add dynamic elements (pills, contacts, calendar entries)
* Display content only when provided
* Insert HTML in description fields (filtered by Moodle's text filters)

## Development

Technologies used:

* PHP (Moodle coding standards compliant)
* Mustache Templates
* Custom CSS (automatically loaded by the block)
* Bootstrap 5 (compatible with Boost theme)

## License

This plugin is released under the [GNU GPL v3 License](http://www.gnu.org/licenses/gpl-3.0.html).

---

### Author

Developed by Trarco
