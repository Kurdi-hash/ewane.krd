# Ewane Automation & Security Research Tool (v3.0)

An advanced automation framework developed in PHP, leveraging Selenium WebDriver and deep learning models to analyze and bypass alphanumeric CAPTCHA systems. The tool features a dual-mode engine for both targeted and randomized authentication testing.

![Ewane Bot Preview](assets/preview.png)
---

## Technical Architecture

- **Language:** PHP 8.x  
- **Automation:** Selenium WebDriver (php-webdriver)  
- **OCR Engine:** Python-based ddddocr (Deep Learning)  
- **Image Processing:** GD Library (Bi-linear Resampling & Thresholding)  
- **Interface:** Command Line Interface (CLI) with ANSI color support  

---

## Core Functionality

### Dynamic Synchronization
Forced CAPTCHA refresh cycles to ensure proper alignment between OCR output and input fields.

### High-Precision Resampling
Real-time 2x image scaling to improve character recognition accuracy.

### Authentication Logic
Automated detection and differentiation between invalid credentials and invalid CAPTCHA responses.

### Multi-Mode Engine

- **Manual Mode:** Targeted authentication testing  
- **Auto Mode:** Brute-force simulation using randomized identifiers and birth-date pattern passwords  

---

## Installation & Prerequisites

### General Dependencies

- PHP 8.0 or higher with GD extension enabled  
- Composer for dependency management  
- Python 3.9 or higher  
- Selenium WebDriver (Standalone Server or ChromeDriver / EdgeDriver / GeckoDriver)  

---

### Python Setup

```bash
pip install ddddocr
```

---

### PHP Setup

```bash
composer require php-webdriver/webdriver
```

---

## Deployment Guide

### Windows

1. Add php.exe and python.exe to System PATH  
2. Enable GD extension in php.ini:
   ```
   extension=gd
   ```
3. Start Selenium server  
4. Run:

```powershell
php src/index.php
```

---

### Linux (Ubuntu / Debian / CentOS)

Install dependencies:

```bash
sudo apt-get install php8.x-gd
pip3 install ddddocr
chmod +x msedgedriver
```

Run the application:

```bash
php src/index.php
```

---

### macOS

Install dependencies:

```bash
brew install php
brew install python
```

Run:

```bash
php src/index.php
```

---

## Configuration

Edit the following file:

```
src/index.php
```

Update variables:

- `$serverUrl` → Selenium server URL  
- `$userDataDir` → Browser profile directory path  
- `$loginUrl` → Target authentication endpoint  

---

## Project Structure

```plaintext
.
├── src/
│   └── index.php
├── vendor/
├── composer.json
├── hits.txt
└── README.md
```

---

## Security & Ethics Disclaimer

This software is provided strictly for educational and security auditing purposes. Unauthorized use against systems without explicit permission is prohibited. The developer assumes no liability for misuse.

---

## License

Distributed under the MIT License. See LICENSE for more information.
