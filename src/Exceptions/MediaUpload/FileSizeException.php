<?php
declare(strict_types=1);

namespace UdHuong\Mediable\Exceptions\MediaUpload;

use UdHuong\Mediable\Exceptions\MediaUploadException;

class FileSizeException extends MediaUploadException
{
    public static function fileIsTooBig(int $size, int $max): self
    {
        return new static("File is too big ({$size} bytes). Maximum upload size is {$max} bytes.");
    }
}
