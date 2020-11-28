<?php
declare(strict_types=1);

namespace UdHuong\Mediable\Exceptions\MediaUpload;

use UdHuong\Mediable\Exceptions\MediaUploadException;

class FileExistsException extends MediaUploadException
{
    public static function fileExists(string $path): self
    {
        return new static("A file already exists at `{$path}`.");
    }
}
