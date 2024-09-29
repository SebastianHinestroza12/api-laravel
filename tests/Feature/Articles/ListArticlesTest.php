<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    //use RefreshDatabase;

    public function  test_can_fetch_all_articles(): void
    {
        $this->withoutExceptionHandling();
        Article::factory()->count(100)->create();
        $response = $this->getJson(route('api.v1.articles.index'));
        $response->assertStatus(200);
    }
}
