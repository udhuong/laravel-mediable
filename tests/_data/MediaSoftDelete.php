<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use UdHuong\Mediable\Media;

class MediaSoftDelete extends Media
{
    use SoftDeletes;

    protected $table = 'media';
}
