<?php

interface PHPExcel_Writer_IWriter
{
    /**
     *  Save PHPExcel to file
     *
     *  @param   string       $pFilename  Name of the file to save
     *  @throws  PHPExcel_Writer_Exception
     */
    public function save($pFilename = NULL);

}
