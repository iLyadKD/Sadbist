<?php

class PHPExcel_Writer_PDF
{

    /**
     * The wrapper for the requested PDF rendering engine
     *
     * @var PHPExcel_Writer_PDF_Core
     */
    private $_renderer = NULL;

    /**
     *  Instantiate a new renderer of the configured type within this container class
     *
     *  @param  PHPExcel   $phpExcel         PHPExcel object
     *  @throws PHPExcel_Writer_Exception    when PDF library is not configured
     */
    public function __construct(PHPExcel $phpExcel)
    {
        $pdfLibraryName = PHPExcel_Settings::getPdfRendererName();
        if (is_null($pdfLibraryName)) {
            throw new PHPExcel_Writer_Exception("PDF Rendering library has not been defined.");
        }

        $pdfLibraryPath = PHPExcel_Settings::getPdfRendererPath();
        if (is_null($pdfLibraryName)) {
            throw new PHPExcel_Writer_Exception("PDF Rendering library path has not been defined.");
        }
        $includePath = str_replace('\\', '/', get_include_path());
        $rendererPath = str_replace('\\', '/', $pdfLibraryPath);
        if (strpos($rendererPath, $includePath) === false) {
            set_include_path(get_include_path() . PATH_SEPARATOR . $pdfLibraryPath);
        }

        $rendererName = 'PHPExcel_Writer_PDF_' . $pdfLibraryName;
        $this->_renderer = new $rendererName($phpExcel);
    }


    /**
     *  Magic method to handle direct calls to the configured PDF renderer wrapper class.
     *
     *  @param   string   $name        Renderer library method name
     *  @param   mixed[]  $arguments   Array of arguments to pass to the renderer method
     *  @return  mixed    Returned data from the PDF renderer wrapper method
     */
    public function __call($name, $arguments)
    {
        if ($this->_renderer === NULL) {
            throw new PHPExcel_Writer_Exception("PDF Rendering library has not been defined.");
        }

        return call_user_func_array(array($this->_renderer, $name), $arguments);
    }

}
