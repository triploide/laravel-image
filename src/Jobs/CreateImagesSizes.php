<?php

namespace Kamecode\Image\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use KameCode\Image\Kameimage;
use Illuminate\Support\Facades\Storage;
use KameCode\Image\KameimageConfig;

class CreateImagesSizes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paths;
    protected $entity;
    protected $config;

    public function __construct($paths, $entity)
    {
        $this->paths = $paths;
        $this->entity = $entity;
        $this->config = new KameimageConfig;
    }

    public function handle()
    {
        $this->paths->each(function ($path) {
            $kameimage = new Kameimage(Storage::disk($this->config->get('storage.disk'))->get($path), $this->entity);
            $kameimage->intervention($path, $this->config->get("sizes.{$this->entity}"));
        });
    }
}
