<?php


if (!defined('PCLZIP_TEMPORARY_DIR')) {
	define('PCLZIP_TEMPORARY_DIR', PHPExcel_Shared_File::sys_get_temp_dir());
}
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/PCLZip/pclzip.lib.php';


class PHPExcel_Shared_ZipArchive
{

	/**	constants */
	const OVERWRITE		= 'OVERWRITE';
	const CREATE		= 'CREATE';


	/**
	 * Temporary storage directory
	 *
	 * @var string
	 */
	private $_tempDir;

	/**
	 * Zip Archive Stream Handle
	 *
	 * @var string
	 */
	private $_zip;


    /**
	 * Open a new zip archive
	 *
	 * @param	string	$fileName	Filename for the zip archive
	 * @return	boolean
     */
	public function open($fileName)
	{
		$this->_tempDir = PHPExcel_Shared_File::sys_get_temp_dir();

		$this->_zip = new PclZip($fileName);

		return true;
	}


    /**
	 * Close this zip archive
	 *
     */
	public function close()
	{
	}


    /**
	 * Add a new file to the zip archive from a string of raw data.
	 *
	 * @param	string	$localname		Directory/Name of the file to add to the zip archive
	 * @param	string	$contents		String of data to add to the zip archive
     */
	public function addFromString($localname, $contents)
	{
		$filenameParts = pathinfo($localname);

		$handle = fopen($this->_tempDir.'/'.$filenameParts["basename"], "wb");
		fwrite($handle, $contents);
		fclose($handle);

		$res = $this->_zip->add($this->_tempDir.'/'.$filenameParts["basename"],
								PCLZIP_OPT_REMOVE_PATH, $this->_tempDir,
								PCLZIP_OPT_ADD_PATH, $filenameParts["dirname"]
							   );
		if ($res == 0) {
			throw new PHPExcel_Writer_Exception("Error zipping files : " . $this->_zip->errorInfo(true));
		}

		unlink($this->_tempDir.'/'.$filenameParts["basename"]);
	}

}
