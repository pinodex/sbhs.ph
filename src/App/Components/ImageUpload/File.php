<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Components\ImageUpload;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;
use GuzzleHttp\Client;

class File {

    private $file, $fileName, $fileExt, $image;

    public function __construct($file, $maxSize = 524288) {
        if (!$file instanceof UploadedFile) {
            if (!$file || !isset($file['error']) || is_array($file['error'])) {
                throw new \RuntimeException('Invalid parameters.');
            }

            if ($file['size'] > $maxSize) {
                throw new \RuntimeException('Filesize limit exceeded.');
            }   
        }

        // Workaround to Symfony's UploadedFile
        if ($file instanceof UploadedFile) {
            $file = array(
                'tmp_name' => $file->getRealPath()
            );
        }

        $allowedTypes = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        );

        $finfo = new \finfo(FILEINFO_MIME_TYPE);

        if (false === $this->fileExt = array_search($finfo->file($file['tmp_name']), $allowedTypes, true)) {
            throw new \RuntimeException('Invalid file format.');
        }

        $this->file = $file;
        $this->fileName = sprintf('%s-%s', date('Y-m-d-H-i-s'), substr(hash('sha1', time() + rand(0, 999)), 0, 10));
    }

    public function getImage() {
        return $this->image;
    }

    public function createOutput() {
        $fileName = $this->getGeneratedFileName('image');
        $image = Image::make($this->file['tmp_name']);

        $image->resize(800, null, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->insert(SRC . '/files/watermark.png', 'bottom-right');
        
        $this->dump($image, $fileName);
        return $fileName;
    }

    public function createBanner() {
        $fileName = $this->getGeneratedFileName('image');
        $image = Image::make($this->file['tmp_name']);

        $image->resize(null, 300, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->insert(SRC . '/files/watermark.png', 'bottom-right');

        $this->dump($image, $fileName);
        return $fileName;
    }

    public function createThumb() {
        $fileName = $this->getGeneratedFileName('thumb');
        $image = Image::make($this->file['tmp_name']);

        $image->resize(null, 150, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $this->dump($image, $fileName);
        return $fileName;
    }

    public function dump($image, $fileName) {
        // $image->save(PUB . '/uploads/' . $fileName);
        $tmpname = tempnam(sys_get_temp_dir(), 'php');
        $image->save($tmpname);

        $client = new Client();
        $client->post('http://sbhs-cdn.appspot.com/_files/upload/__p1n0d3xXXXXX', [
            'multipart' => array(
                array(
                    'name' => 'type',
                    'contents' => 'image/' . $this->fileExt,
                ),
                array(
                    'name' => 'name',
                    'contents' => $fileName,
                ),
                array(
                    'name' => 'file',
                    'contents' => fopen($tmpname, 'r')
                )
            )
        ]);
    }

    public function getGeneratedFileName($suffix = 'file') {
        return sprintf('%s-%s.%s', $this->fileName, $suffix, $this->fileExt);
    }

}