<?php
Route::resource('notes', 'NoteController',[
    'parameters' => [
        'notes' => 'note'
    ]
]);
Route::resource('categories', 'CategoryController');