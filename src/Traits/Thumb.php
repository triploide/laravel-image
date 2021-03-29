<?php

namespace KameCode\Image\Traits;

use KameCode\Image\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

trait Thumb
{
    public function images()
    {
        // TODO: resolver el tema del order by
        return $this->morphMany(Image::class, 'imageable');
    }

    public function thumb()
    {
        // TODO: resolver el tema del order by
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getImage()
    {
        return $this->images->first();
    }

    public function deleteImages()
    {
        $table = $this->getTable();
        $folders = config("image.$table", []);

        $this->images->each(function ($image) use ($folders, $table) {
            foreach ($folders as $folder => $config) {
                try {
                    Storage::delete("$table/$folder/{$image->src}");
                    Storage::delete("$table/$folder/{$image->webp()}");
                } catch (\Exception $e) {
                    Log::error("Ocurrió un error al tratar de borrar la imagen $table/$folder/{$image->src}");
                }
            }
        });
    }
}
