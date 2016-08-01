<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Category;

class ApiCategoryTest extends TestCase{
    use DatabaseTransactions;
    /**
     * @test
     * Retorna la lista de categorias correctamente
     *
     * @return void
     */
    public function list_categories()
    {

        factory(Category::class)->times(2)->create();
        $this->get('api/v1/categories')
            ->assertResponseOk()//200
            ->seeJsonEquals(Category::all()->toArray());
    }
}
