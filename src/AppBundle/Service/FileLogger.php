<?php

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class FileLogger {
    /** @var string $pathToLog */
    private $pathToLog;
    /** @var Filesystem  */
    private $fs;

    public function __construct() {
        $this->pathToLog = __DIR__ . '/../../../web/logs/';
        $this->fs = new Filesystem();
        if (!$this->fs->exists($this->pathToLog)) {
            $this->fs->mkdir($this->pathToLog);
        }
    }

    public function logEvent($file, $event) {
        $now = new \DateTime();
        $event = '[' . $now->format('d-m-Y H:i:s') . '] ' . $event . PHP_EOL;
        $path = $this->getAbsolutePath($file);
        if ($this->fs->exists($path)) {
            $fileHandler = fopen($path, 'a+');
            fwrite($fileHandler, $event);
            fclose($fileHandler);
        } else {
            $this->fs->dumpFile($path, $event);
        }
    }

    private function getAbsolutePath($file) {
        return $this->pathToLog . $file;
    }
}