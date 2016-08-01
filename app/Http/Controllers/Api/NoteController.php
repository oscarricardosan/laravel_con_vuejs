<?php

namespace App\Http\Controllers\Api;

use App\Note;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Note::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'note' => 'required',
            'category_id' => 'numeric|exists:categories,id',
        ]);
        $data = $request->only(['note', 'category_id']);
        if($request->category_id=='') $data['category_id'] = null;

        $note = Note::create($data);
        return [
            'success' => true,
            'note' => $note
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        $this->validate($request, [
            'note' => 'required',
            'category_id' => 'exists:categories,id'
        ]);
        $note->fill($request->all());
        $note->save();
        return [
          'success' => true,
          'note' => $note,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        abort_if($note->category_id==3, 403, 'No tienes permiso para eliminar nota.');
        $note->delete();
        return ['success'=>true];
    }
}
