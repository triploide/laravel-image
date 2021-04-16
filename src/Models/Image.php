<?php

namespace KameCode\Image\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use KameCode\Image\Http\Resources\Image as ResourcesImage;
use KameCode\Image\KameimageConfig;

class Image extends Model
{
    // ---Config---
    protected $fillable = [
        'path',
        'name',
        'entity',
        'imageable_id',
        'imageable_type',
        'is_video',
        'pending',
    ];

    protected $casts = [
        'is_video' => 'boolean',
    ];

    public $config;

    // ---Constructor---
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->config = new KameimageConfig;
    }

    // ---Magic Methods---
    public function __toString()
    {
        $thumb = $this->config->getThumb($this->entity);
        
        return $this->getUrl($thumb);
    }
    
    // TODO: pensar solución para los nombres de sizes del tipo palabra1-palabra2
    public function __get($attr)
    {
        $prop = null;

        if (parent::__get($attr) !== null) {
            $prop = parent::__get($attr);
        } elseif (isset($sizes[$attr])) {
            $prop = $this->getUrl($sizes[$attr]);
        }

        return $prop;
    }

    public function toArray()
    {
        return new ResourcesImage($this);
    }

    // ---Methods---
    public function getUrl($size=null)
    {
        $defaultFolder = $this->config->get('storage.default_folder');
        $size = $size ?? $defaultFolder;
        $disk = $this->config->get('storage.disk');

        if ($this->path) {
            $path = str_replace("/$defaultFolder/", "/$size/", $this->path);
        } else {
            $path = "{$this->entity}/{$size}/" . $this->config->get("'image_not_found.{$this->entity}", $this->config->get('image_not_found.default'));
        }

        return Storage::disk($disk)->url($path);
    }

    public function getUrls($sizes=null)
    {
        if (!$sizes) $sizes = $this->config->getSizes($this->entity);

        return collect($sizes)->mapWithKeys(function ($size) {
            return [$size => $this->getUrl($size)];
        });
    }

    public static function nextId()
    {
        if (!$imagen = self::select('id')->orderBy('id', 'desc')->first()) {
            $imagen = new self;
            $imagen->id = 0;
        }
        return $imagen->id + 1;
    }

    //TODO: pensar esto un poco en función de los nuevos agregados en el config de kameimage (breakpoints)
    public function webp()
    {
        return preg_replace('/\.[a-zA-Z]{3,4}$/', '.webp', $this->src);
    }

    public function mobile($folder)
    {
        return "mobile-$folder/{$this->src}";
    }

    public function pictureTag($folder, $alt='')
    {
        $table = $this->entity;

        $folder = trim($folder, '/');
        $desktop = "/storage/$table/$folder/";
        $mobile = "/storage/$table/mobile-$folder/";

        return '
             <picture class="picture-'.$table.'-'.$folder.'">
                <source media="(max-width:649px)" srcset="'. $mobile . $this->webp() .'">
                <source media="(max-width:649px)" srcset="'. $mobile . $this->src .'">
                <source media="(min-width:650px)" srcset="'. $desktop . $this->webp() .'">
                <source media="(min-width:650px)" srcset="'. $desktop . $this->src .'">
                <img src="'. $desktop . $this->src .'" alt="'. $alt .'">
            </picture> 
        ';
    }
}
