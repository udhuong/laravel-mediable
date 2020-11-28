<?php
declare(strict_types=1);

namespace UdHuong\Mediable\Exceptions\MediaUpload;

use UdHuong\Mediable\Exceptions\MediaUploadException;

class FileNotFoundException extends MediaUploadException
{
    public static function fileNotFound(string $path): self
    {
        return new static("File `{$path}` does not exist.");
    }
}
