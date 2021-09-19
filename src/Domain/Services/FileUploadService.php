<?php

namespace MyTower\Domain\Services;

class FileUploadService
{
    const ALLOWED_TYPES = ['application/vnd.ms-excel','text/plain','text/csv','text/tsv'];
    const MAX_UPLOAD_SIZE = 2097152; //2MB
    const ERR_INVALID_FILE_FORMAT = 'file format not allowed';
    const ERR_INVALID_FILE_SIZE = 'file size can not be great';

    /**
     * keeps uploaded file instance
     * @var $file
     */
    private $file;
    private $errors = [];

    public function setFile(array $file)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile() : array
    {
        return $this->file;
    }

    /**
     * check uploaded file valid format
     * @param array $file
     * @return bool
     */
    public function valid() : bool
    {
        return $this->isValidSize() && $this->isValidFormat();
    }

    private function isValidFormat()
    {
        $file = $this->getFile();
        if (in_array($file['file']['type'], self::ALLOWED_TYPES)) {
            return true;
        }

        $this->setErrors(self::ERR_INVALID_FILE_FORMAT);
        return false;
    }

    private function isValidSize()
    {
        $file = $this->getFile();
        if ($file['file']['size'] <= self::MAX_UPLOAD_SIZE) {
            return true;
        }

        $this->setErrors(sprintf('%s %s', self::ERR_INVALID_FILE_SIZE, self::MAX_UPLOAD_SIZE/1024/1024));
        return false;
    }

    private function setErrors(string $error)
    {
        array_push($this->errors, $error);
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * move file to uploads directory
     * @param $path
     * @return bool
     */
    public function move(string $path = '') : bool
    {
        $file = $this->getFile();
        try {
            return move_uploaded_file($file['file']['tmp_name'], __DIR__.'/../../static/uploads/'.$file['file']['name']);
        } catch (\Exception $e) {
            return false;
        }
    }
}