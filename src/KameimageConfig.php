<?php

namespace KameCode\Image;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KameimageConfig
{
    protected $config;

    public function __construct()
    {
        $this->config = collect((array)config('kameimage'));
    }

    public function get($key, $defualt=null)
    {
        return \Arr::get($this->config, $key, $defualt);
    }

    public function getSizes($entity)
    {
        $sizes = $this->get("sizes.$entity", []);

        return collect($sizes)->keys();
    }

    public function getThumb($entity)
    {
        return $this->get("thumb.$entity", $this->get('thumb.default'));
    }

    public function getImageNotFound($entity)
    {
        $this->get("image_not_found.$entity", $this->get('image_not_found.default'));
    }
}
