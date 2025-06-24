# FireDash

![FireDash Logo](https://firedash.mapsil.com/logo.png)

FireDash is an open-source, web-based info screen system designed for internal communication. It provides an administrative interface for creating and managing dynamic content, which is then displayed on a central info screen. The system supports various slide types, including text, images, birthday announcements, and embedded PDFs. Content is retrieved from a centralized MySQL database, ensuring dynamic and up-to-date information dissemination. FireDash is designed to operate efficiently within a standard web hosting environment.

## Features

* **Intuitive Content Editor:** `editor.php` offers a user-friendly interface for managing slides and a global message.
* **Diverse Slide Types:** Supports four distinct content formats:
    * **Image:** Display images with an optional title, supporting both direct URL linking and file uploads.
    * **Text:** Present custom headings and body text.
    * **Birthday:** Announce birthdays with customizable name lists.
    * **PDF:** Embed external PDF documents via URL.
* **Configurable Display Durations:** Each slide can be assigned a specific display duration in seconds.
* **Content Visibility Toggle:** Slides can be marked as 'hidden' or 'visible' without permanent deletion, allowing for flexible content scheduling.
* **Persistent Global Message:** A customizable message can be displayed consistently at the top of the info screen.
* **Automated Slide Cycling:** Slides rotate automatically based on their configured display times.
* **Integrated Time Display:** The info screen includes both digital and analog clocks, along with the current date and day.
* **Web Hosting Compatibility:** Designed for straightforward deployment on a standard PHP/MySQL web hosting platform.

## Deployment

These instructions detail the process of deploying FireDash on your web server.

### Prerequisites

* A web server (e.g., Apache, Nginx, or equivalent)
* PHP 7.4 or higher
* MySQL or MariaDB database server

### Installation

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/abcamdk/firedash.git](https://github.com/abcamdk/firedash.git)
    cd firedash
    ```

2.  **Database Configuration:**
    * Create a new MySQL/MariaDB database (e.g., `main_db`).
    * Execute the following SQL schema to create the necessary tables:

    ```sql
    CREATE DATABASE IF NOT EXISTS main_db;

    USE main_db;

    CREATE TABLE IF NOT EXISTS slides (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(50) NOT NULL,
        billede_url VARCHAR(255),
        billede_filnavn VARCHAR(255),
        billede_overskrift VARCHAR(255),
        overskrift VARCHAR(255),
        tekst TEXT,
        visningstid INT DEFAULT 10,
        rækkefølge INT DEFAULT 0,
        fødselsdag_navne TEXT,
        pdf_url VARCHAR(255),
        skjult BOOLEAN DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS konfiguration (
        id INT AUTO_INCREMENT PRIMARY KEY,
        besked TEXT
    );

    -- Insert a default row into the konfiguration table if it's empty
    INSERT INTO konfiguration (id, besked) VALUES (1, 'Welcome to FireDash!') ON DUPLICATE KEY UPDATE besked=besked;
    ```
    * **Crucial:** Update the database connection parameters (`$servername`, `$username`, `$password`, `$dbname`) in `editor.php`, `get_slides.php`, `get_besked.php`, and `upload.php` to reflect your database credentials. Note that `editor.php` uses `main_db` by default, while `get_slides.php`, `get_besked.php`, and `upload.php` use `db`.

3.  **File Placement and Permissions:**
    * Deploy all project files to your web server's document root (e.g., `htdocs`, `www`, or public-facing directory).
    * Ensure the `uploads/` directory exists and is writable by your web server process. The `editor.php` script includes logic to create this directory if it's missing, with `0755` permissions.

## Usage

1.  **Access the Editor:**
    * Navigate to `http://your-domain.com/editor.php` (adjust path as needed).
    * From this interface, you can:
        * **Add New Slide:** Create new content elements of various types.
        * **Manage Slides and Message:** Oversee existing slides, toggle their visibility, delete them, and modify the global info screen message.

2.  **Access the Info Screen:**
    * Open `http://your-domain.com/display.php` in a browser.
    * This page will automatically cycle through your active slides.

3.  **Main Entry Point:**
    * `index.php` provides a landing page with navigation options to the editor, info screen, and a help guide.

## Development Considerations

### Troubleshooting

* **Database Connection Failures:** Verify database connection strings (hostname, username, password, database name) across `editor.php`, `get_slides.php`, `get_besked.php`, and `upload.php`.
* **Image Upload Issues:** Confirm that the `uploads/` directory has appropriate write permissions for your web server (e.g., `chmod 755 uploads/` or `chown www-data:www-data uploads/`).
* **X-Frame-Bypass Functionality:** `x-frame-bypass.js` is included to attempt circumvention of `X-Frame-Options` headers, which can facilitate embedding content from certain external websites (e.g., PDFs). Note that this is not a universally effective solution, as some sites may employ more robust anti-embedding measures.

### Security Best Practices

* For production environments, **disable PHP error reporting** (`display_errors`, `display_startup_errors`) in `editor.php`.
* The default database credentials (`root`/`root`) are for development only and **must be changed** to strong, unique credentials for production deployments.
* Always practice input validation and sanitization to mitigate risks such as SQL injection and Cross-Site Scripting (XSS). Prepared statements are widely used within FireDash, but ongoing vigilance for potential vulnerabilities is recommended.

## Contributing

Contributions are welcome! Please feel free to open an issue or submit a pull request for enhancements, new features, or bug fixes.

## License

This project is open source. And released by Mapsil

---
