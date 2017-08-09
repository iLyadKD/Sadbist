<?php

if (!defined('DATE_W3C')) {
  define('DATE_W3C', 'Y-m-d\TH:i:sP');
}

if (!defined('DEBUGMODE_ENABLED')) {
  define('DEBUGMODE_ENABLED', false);
}


class PHPExcel_Shared_XMLWriter extends XMLWriter {
	/** Temporary storage method */
	const STORAGE_MEMORY	= 1;
	const STORAGE_DISK		= 2;

	/**
	 * Temporary filename
	 *
	 * @var string
	 */
	private $_tempFileName = '';

	/**
	 * Create a new PHPExcel_Shared_XMLWriter instance
	 *
	 * @param int		$pTemporaryStorage			Temporary storage location
	 * @param string	$pTemporaryStorageFolder	Temporary storage folder
	 */
	public function __construct($pTemporaryStorage = self::STORAGE_MEMORY, $pTemporaryStorageFolder = NULL) {
		// Open temporary storage
		if ($pTemporaryStorage == self::STORAGE_MEMORY) {
			$this->openMemory();
		} else {
			// Create temporary filename
			if ($pTemporaryStorageFolder === NULL)
				$pTemporaryStorageFolder = PHPExcel_Shared_File::sys_get_temp_dir();
			$this->_tempFileName = @tempnam($pTemporaryStorageFolder, 'xml');

			// Open storage
			if ($this->openUri($this->_tempFileName) === false) {
				// Fallback to memory...
				$this->openMemory();
			}
		}

		// Set default values
		if (DEBUGMODE_ENABLED) {
			$this->setIndent(true);
		}
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		// Unlink temporary files
		if ($this->_tempFileName != '') {
			@unlink($this->_tempFileName);
		}
	}

	/**
	 * Get written data
	 *
	 * @return $data
	 */
	public function getData() {
		if ($this->_tempFileName == '') {
			return $this->outputMemory(true);
		} else {
			$this->flush();
			return file_get_contents($this->_tempFileName);
		}
	}

	/**
	 * Fallback method for writeRaw, introduced in PHP 5.2
	 *
	 * @param string $text
	 * @return string
	 */
	public function writeRawData($text)
	{
		if (is_array($text)) {
			$text = implode("\n",$text);
		}

		if (method_exists($this, 'writeRaw')) {
			return $this->writeRaw(htmlspecialchars($text));
		}

		return $this->text($text);
	}
}
