<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //Los Laravel Resources automaticamente encierran la respuesta en una propierda data
        return [
            'type' => 'article',
            'id' => (string) $this->resource->getRouteKey(),
            'atributes' => [
                'title' => $this->resource->title,
                'slug' => $this->resource->slug,
                'content' => $this->resource->content
            ],
            'links' => [
                'self' => route('api.v1.articles.show', $this->resource)
            ]
        ];
    }
}
