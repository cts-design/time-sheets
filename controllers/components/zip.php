<?php
/**
 * Zip Component
 *
 * CakePHP component for the native ZipArchive extension
 * This component requires PHP 5.2.0+ compiled with zlib
 *
 * @package AtlasV3
 * @author Brandon Cordell
 * @copyright 2012 Complete Technology Solutions
 */
class ZipComponent extends Object {
    protected $archive;
    protected $settings = array();

    public function initialize(&$Controller, $settings = array()) {
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * Open zip archive
     *
     * Opens a new zip archive for reading, writing, or modifying
     *
     * @return bool
     * @param string $filename
     * @param integer $mode defaults to ZIPARCHIVE::OVERWRITE
     */
    public function open($filename, $mode = ZIPARCHIVE::OVERWRITE) {
        $this->archive = new ZipArchive();
        $res = $this->archive->open($filename, $mode);

        if ($res !== true) {
            $this->log("ZipComponent - Line #34\nZipComponent failed to open zip archive `$filename` with error $res", 'error');
            return false;
        }

        return true;
    }

    /**
     * Add an empty directory to the zip archive
     *
     * @return bool
     * @param string $name
     */
    public function addDirectory($name) {
        if (!$this->archive->addEmptyDir($name)) {
            $this->log("ZipComponent - Line #50\nZipComponent failed to add directory `$name`", 'error');
            return false;
        }

        return true;
    }

    /**
     * Add a file to the zip archive
     *
     * @return bool
     * @param string $filename The path of the file to add
     * @param string $newName Rename the added file in the zip archive
     */
    public function addFile($filename, $newName = null) {
        if (!$this->archive->addFile($filename, $newName)) {
            $this->log("ZipComponent - Line #66\nZipComponent failed to add file `$filename`", 'error');
            return false;
        }

        return true;
    }

    /**
     * Close the zip archive and save
     *
     * @return bool
     */
    public function close() {
        if ($this->archive->close()) {
            return true;
        }

        return false;
    }
}

