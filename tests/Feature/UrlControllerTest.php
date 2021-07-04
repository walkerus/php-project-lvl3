<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('urls')->insert([
            [
                'id' => 2,
                'name' => 'http://test.ru'
            ],
        ]);
    }

    public function testIndex()
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testStoreBadRequest()
    {
        $response = $this->post(route('urls.index'), []);
        $response->assertSessionHasErrors(['name' => 'name обязательное поле.']);
        $response->assertRedirect('/');

        $response = $this->post(route('urls.store'), ['name' => 'example']);
        $response->assertSessionHasErrors(['name' => 'name должен быть в формате url.']);
        $response->assertRedirect('/');

        $this->assertDatabaseMissing('urls', ['name' => 'example']);
    }

    public function testStore()
    {
        $urlData = ['name' => 'http://abc'];
        $response = $this->post(route('urls.store'), $urlData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('urls', $urlData);
    }

    public function testShowNotFound()
    {
        $response = $this->get(route('urls.show', ['url' => 1]));
        $response->assertNotFound();
    }

    public function testShow()
    {
        $response = $this->get(route('urls.show', ['url' => 2]));
        $response->assertOk();
    }
}
