<?php

namespace KameCode\Image\Http\Controllers;

use App\Http\Controllers\Controller;
use KameCode\Image\Http\Requests\ImageRequest;
use KameCode\Image\Jobs\CreateImagesSizes;
use KameCode\Image\Models\Image;
use Illuminate\Support\Str;
use KameCode\Image\Http\Resources\Image as ResourcesImage;
use KameCode\Image\KameimageConfig;

class ImageController extends Controller
{
    protected $config;

    public function __construct( )
    {
        $this->config = new KameimageConfig;
    }

    public function upload(ImageRequest $request)
    {
        $paths = $this->saveImages();

        $images = $this->insertImages($paths);

        CreateImagesSizes::dispatch($paths, $request->input('entity'))->onQueue($this->config->get('queue'));

        return [
            'success' => true,
            'images' => ResourcesImage::collection($images),
        ];
    }

    public function destroy(Image $image)
    {
        return ['success' => $image->delete()];
    }

    private function saveImages()
    {
        extract($this->config->get('storage'));

        $folder = request()->input($this->config->get('form.entity')) . '/'. $default_folder;
        if ($add_folder_day) $folder .= '/' . date('Y-m-d');

        return collect(request()->file($this->config->get('form.images')))->map(function ($file) use ($folder, $disk) {
            return $file->store($folder, $disk);
        });
    }

    private function insertImages($paths)
    {
        return $paths->map(function ($path) {
            return Image::create([
                'path' => $path,
                'entity' => request()->input($this->config->get('form.entity')),
                'name' => Str::afterLast($path, '/')
            ]);
        });
    }
}
