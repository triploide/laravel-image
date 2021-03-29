<?php

namespace KameCode\Image;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Kameimage
{
    protected $file;
    protected $entity;
    protected $config;
    protected $customTransformations = ['fitOnCanvas'];

    public function __construct($file, $entity)
    {
        $this->file = $file;
        $this->entity = $entity;
        $this->config = new KameimageConfig;
    }

    public function intervention($path, $sizes)
    {
        collect($sizes)->each(function ($option, $size) use ($path) {
            $image = Image::make($this->file);
            $this->applyTransformations($image, $option);
            $this->addDefualtImageIfNotExists($size, $option);
            $this->store($image, $path, $size);
        });
    }

    private function store($image, $path, $sizeFolder)
    {
        $path = Str::of($path)->replace($this->config->get('storage.default_folder'), $sizeFolder);

        Storage::disk($this->config->get('storage.disk'))->put($path, $image->encode(null, 80));
        Storage::disk($this->config->get('storage.disk'))->put($path->beforeLast('.')->append('.webp'), $image->encode('webp', 80));
    }

    
    private function addDefualtImageIfNotExists($size, $option)
    {
        $imageNotFoud = $this->config->getImageNotFound($this->entity);

        $storage = Storage::disk($this->config->get('disk'));
        if (!file_exists(public_path("vendor/kameimage/images/$imageNotFoud"))) {
            $image = Image::make(public_path("vendor/kameimage/images/$imageNotFoud"));
            $option = reset($option);
            $image = $this->fitOnCanvas($image, [$option[0], $option[1]]);

            $storage->put("{$this->entity}/$size/$imageNotFoud", $image->encode(null, 80));
        }
    }

    private function applyTransformations($image, $option)
    {
        foreach ($option as $method => $args) {
            if (!is_array($args)) $args = [$args];

            if (in_array($method, $this->customTransformations)) {
                $image = $this->$method($image, $args);
            } else {
                call_user_func_array([$image, $method], $args);
            }
        }
    }

    // ---Custom Transformations---
    public function fitOnCanvas($image, $args)
    {
        list($width, $height) = $args;
        if ($width && $height) $image->width() > $image->height() ? $height=null : $width=null;
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $width = $args[0] ?? $args[1];
        $height = $args[1] ?? $args[0];
        $canvas = Image::canvas($width, $height);
        $canvas->insert($image, 'center');
        return $canvas;
    }
}
