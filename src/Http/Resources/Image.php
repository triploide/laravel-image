<?php

namespace KameCode\Image\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Image extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
            'url' => $this->getUrl(),
            'entity' => $this->entity,
            'is_video' => $this->is_video,
            'urls' => $this->getUrls(),
            'sizes' => $this->config->getSizes($this->entity),
            'thumb' => $this->config->getThumb($this->entity)
        ];
    }
}
