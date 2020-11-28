<?php

use Illuminate\Database\Eloquent\Model;
use UdHuong\Mediable\Mediable;

class SampleMediable extends Model
{
    use Mediable;

    public $rehydrates_media = true;
}
