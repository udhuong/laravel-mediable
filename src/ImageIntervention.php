<?php
/**
 * Created by PhpStorm.
 * User: dinhhuong
 * Date: 11/28/20
 * Time: 1:44 PM
 */

namespace UdHuong\Mediable;

use Intervention\Image\ImageManager;

class ImageIntervention
{
    /** @var ImageManager */
    protected $image;

    /** @var string */
    protected $filePath;

    /** @var array */
    protected $config;

    /** @var string */
    protected $pathSave;

    /** @var string */
    protected $fileName;

    /**
     * Create a new uploader instance.
     *
     * @param mixed $file
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->setFile($file);
        $this->config = config('intervention_image');
        $this->getPathSaveToday();
    }

    /**
     * @param mixed $file
     *
     * @return ImageIntervention
     */
    public static function fromSource($file)
    {
        return new static($file);
    }

    /**
     * Set the file to be uploaded.
     *
     * @param mixed $file
     *
     * @return ImageIntervention
     */
    public function setFile($file)
    {
        $manager        = new ImageManager();
        $this->image    = $manager->make($file);
        $this->fileName = $this->image->basename;

        return $this;
    }

    private function getPathSaveToday()
    {
        $path = date('Y') . '/' . date('m') . '/' . date('d');
        $path = $this->config['folder_upload'] . '/' . $path;
        if (!file_exists($path))
        {
            @mkdir($path, 0777, true);
        }
        $this->pathSave = $path;

        return $this;
    }

    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    public function setPathSave(string $path = null)
    {
        if (!file_exists($path))
        {
            @mkdir($path, 0777, true);
            $this->pathSave = $path;
        }

        return $this;
    }

    /*
     * Resize image too large
     */
    public function resizeIfTooLarge()
    {
        $maxWidth   = $this->config['max_width'];
        $imageWidth = $this->image->width();
        if ($imageWidth > $maxWidth)
        {
            $this->image->resize($maxWidth, null, function ($constraint)
            {
                $constraint->aspectRatio();
            });
        }

        return $this;
    }

    public function addWatermark()
    {
        $this->image->insert($this->config['watermark'], 'top-left', 10, 10);

        return $this;
    }

    private function getPathThumb($path)
    {
        if (!file_exists($path))
        {
            @mkdir($path, 0777, true);
            @chmod($path, 0777);
        }

        return $path;
    }

    public function createThumb(array $thumbs = [])
    {
        $thumbs   = $thumbs ?: $this->config['thumbs'];
        $path     = $this->pathSave . '/thumbs';
        $pathSave = $this->getPathThumb($path);
        foreach ($thumbs as $key => $item)
        {
            $save = $pathSave . '/' . $key . '_' . $this->fileName;
            $this->image->backup();
            $this->image->resize($item['width'], $item['height'], function ($constraint)
            {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($save);
            $this->image->reset();
        }

        return $this;
    }

    public function save()
    {
        $path = $this->pathSave . '/' . $this->fileName;
        $this->image->save($path);
    }
}

