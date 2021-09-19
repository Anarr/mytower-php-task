<?php

namespace MyTower\App\Controllers;

use MyTower\Domain\Services\FileUploadService;

class MainController
{

    private $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function uploadForm()
    {
        if (!empty($_FILES)) {
            $file = $this->fileUploadService->setFile($_FILES);

            if ($file->valid()) {
                $uploaded = $file->move();

                if ($uploaded) {
                    var_dump('upload sucessfully');
                    return;
                }

                var_dump('upload fails');
            }

            foreach ($file->getErrors() as $error) {
                echo $error . PHP_EOL;
            }
        }

        require_once __DIR__ . '/../../resources/views/upload_form.php';
    }
}