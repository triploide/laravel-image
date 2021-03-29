<?php

namespace KameCode\Image\Traits;

use KameCode\Image\Models\Image;

trait Imageuploader
{
    protected function addImages($model)
    {
        if (request()->has(config('kameimage.form.images'))) {
            $ids = request()->has(config('kameimage.form.images'));

            if (!is_array($ids)) {
                $model->images()->delete();
                $ids = [$ids];
            }

            $images = Image::whereIn('id', $ids);
            $model->images()->saveMany($images->get());
            $images->update(['pending' => 0]);
        }
    }
}
