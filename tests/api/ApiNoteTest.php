<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Category;
use App\Note;

class ApiNoteTest extends TestCase
{
    use DatabaseTransactions;

    protected $note = 'This is a note';

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_list_notes()
    {

        $category = factory(Category::class)->create();
        $notes = factory(Note::class)->times(2)->create([
            'category_id' => $category->id,
            'note' => 'Esto es una nota'
        ]);
        $this->get('api/v1/notes')
            ->assertResponseOk()//200
            ->seeJsonEquals(Note::all()->toArray());
    }

    public function test_can_create_a_note(){
        $category = factory(Category::class)->create();
        $this->post('api/v1/notes', [
            'note' => $this->note,
            'category_id' => $category->id
        ]);
        $this->seeInDatabase('notes', [
            'note' => $this->note,
            'category_id' => $category->id
        ]);
        $this->seeJsonEquals([
            'success' => true,
            'note' => Note::orderBy('id', 'desc')->first()->toArray(),
        ]);
    }

    public function test_can_create_a_note_whithout_category_id(){
        $this->post('api/v1/notes', [
            'note' => $this->note,
            'category_id' => ''
        ]);
        $this->seeInDatabase('notes', [
            'note' => $this->note,
            'category_id' => null
        ]);
        $this->seeJsonEquals([
            'success' => true,
            'note' => Note::orderBy('id', 'desc')->first()->toArray(),
        ]);
    }
    public function test_cant_create_a_note_with_category_id_string(){
        $this->post('api/v1/notes', [
            'note' => $this->note,
            'category_id' => 'a'
        ],['Accept' => 'application/json']);
        $this->assertResponseStatus(422)//Error de validation
        ->seeJsonEquals([
            'success' => false,
            'errors' => [
                "The category id must be a number."
            ]
        ]);
    }
    public function test_can_create_a_note_with_category_empty(){
        $this->post('api/v1/notes', [
            'note' => $this->note.$this->note,
            'category_id' => ''
        ],['Accept' => 'application/json']);

        $this->seeInDatabase('notes', [
            'note' => $this->note.$this->note,
            'category_id' => null
        ]);
        $this->seeJsonEquals([
            'success' => true,
            'note' => Note::orderBy('id', 'desc')->first()->toArray(),
        ]);


    }
    function test_validation_when_creating_a_note(){
        $this->post('api/v1/notes', [
            'note' => '',
            'category_id' => 100
        ],['Accept' => 'application/json']);
        $this->dontSeeInDatabase('notes', [
            'note' => ''
        ]);
        $this->seeJsonEquals([
            'success' => false,
            'errors' => [
                "The selected category id is invalid.", "The note field is required."
            ]
        ]);
    }
    public function test_can_update_a_note(){

        $new_text = 'Updated Note';

        $category = factory(Category::class)->create();
        $otherCategory = factory(Category::class)->create();
        $note = factory(Note::class)->make();

        $category->notes()->save($note);

        $this->put('api/v1/notes/'.$note->id, [
            'note' => $new_text,
            'category_id' => $otherCategory->id
        ]);
        $this->seeInDatabase('notes', [
            'note' => $new_text,
            'category_id' => $otherCategory->id
        ]);
        $this->seeJsonEquals([
            'success' => true,
            'note' => [
                'id' => $note->id,
                'note' => $new_text,
                'category_id' => $otherCategory->id
            ]
        ]);
    }

    function test_validation_when_updating_a_note(){
        $new_text = 'Updated Note';

        $category = factory(Category::class)->create();
        $otherCategory = factory(Category::class)->create();
        $note = factory(Note::class)->make();

        $category->notes()->save($note);

        $this->put('api/v1/notes/'.$note->id, [
            'note' => '',
            'category_id' => 100
        ],['Accept' => 'application/json']);
        $this->dontSeeInDatabase('notes', [
            'note' => ''
        ]);
        $this->seeJsonEquals([
            'success' => false,
            'errors' => [
                "The selected category id is invalid.", "The note field is required."
            ]
        ]);
    }
    function test_can_a_delete_a_note(){
        $note = factory(Note::class)->create();
        $this->delete('api/v1/notes/'.$note->id, [],['Accept' => 'application/json']);
        $this->dontSeeInDatabase('notes',[
            'id' => $note->id
        ]);
        $this->seeJsonEquals([
            "success" => true
        ]);
    }

}
