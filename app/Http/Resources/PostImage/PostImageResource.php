<?php

namespace App\Http\Resources\PostImage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'url' => $this->url,
        ];
    }

}
