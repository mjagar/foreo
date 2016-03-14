<?php
use DAO\Stock;

/**
 * Class ProcessFile
 */
class ProcessFile
{
    /**
     * @return bool|string
     */
    public static function uploadExcel()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return false;
        }

        $name     = $_FILES['file']['name'];
        $tmpName  = $_FILES['file']['tmp_name'];
        $error    = $_FILES['file']['error'];
        $size     = $_FILES['file']['size'];
        $ext      = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        $response = false;

        switch ($error) {
            case UPLOAD_ERR_OK:
                $valid = true;
                //validate file extensions
                if ( !in_array($ext, array('xlsx')) ) {
                    $valid = false;
                    $response = 'Invalid file extension, expecting xlsx.';
                }
                //validate file size
                if ( $size/1024/1024 > 2 ) {
                    $valid = false;
                    $response = 'File size is exceeding maximum allowed size of 1M.';
                }
                //upload file
                if ($valid === true) {
                    $targetPath =  $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR. 'uploads' . DIRECTORY_SEPARATOR. $name;
                    move_uploaded_file($tmpName, $targetPath);
                    $response = self::saveXlsxToDatabase($targetPath);
                }
                break;
            case UPLOAD_ERR_INI_SIZE:
                $response = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $response = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $response = 'The uploaded file was only partially uploaded.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $response = 'No file was uploaded.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $response = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $response = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
                break;
            case UPLOAD_ERR_EXTENSION:
                $response = 'File upload stopped by extension. Introduced in PHP 5.2.0.';
                break;
            default:
                $response = false;
                break;
        }

        return $response;
    }

    /**
     * @param string $fileName
     * @throws PHPExcel_Exception
     * @return bool
     */
    public static function saveXlsxToDatabase($fileName)
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/external/PHPOffice/PHPExcel/Classes/PHPExcel/IOFactory.php';

        try {
            $inputFileType = PHPExcel_IOFactory::identify($fileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($fileName);
        } catch(Exception $e) {
            return 'Error loading file "' . pathinfo($fileName, PATHINFO_BASENAME) . '": ' . $e->getMessage();
        }

        $sheet = $objPHPExcel->getSheet(0);

        $pdfContent = $sheet->rangeToArray('A2:F'. $sheet->getHighestRow());
        foreach ($pdfContent as $stockItem) {
            $stock = new Stock();
            $stock->id = $stockItem[0];
            $stock->productId = $stockItem[1];
            $stock->productName = $stockItem[2];
            $stock->quantity = $stockItem[3];
            $stock->type = $stockItem[4];
            $stock->created = $stockItem[5];
            $stock->save();
        }

        return true;
    }
}
