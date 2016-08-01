@extends('layout')
@section('content')
    <div class="container">
        <div class="row">

            <h1>Curso de VueJS | Styde.net | Avanzado</h1>
            <div class="alert-container">
                <p v-show="alert.display" class="alert alert-danger animated" id="error-message" transition="myFade">
                    @{{alert.message}}
                </p>
            </div>
            <a href="https://github.com/oscarricardosan/laravel_con_vuejs/blob/master/public/js/notes_avanzado.js" target="_blank">
                https://github.com/oscarricardosan/laravel_con_vuejs/blob/master/public/js/notes_avanzado.js
            </a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Nota</th>
                    <th width="50"></th>
                </tr>
                <thead>
                <tbody>
                <tr
                    v-for="note in notes"
                    is="note-row"
                    :note.sync="note"
                    :categories="categories"
                    @@update-note="updateNote"
                    @@delete-note="deleteNote"
                >
                </tr>
                <tr>
                    <td>
                        <select-category :categories="categories" :id.sync="new_note.category_id"/>
                    </td>
                    <td>
                        <input type="text" v-model="new_note.note" class="form-control">
                        <ul v-if="erros && errors.length" class="text-danger">
                            <li v-for="error in errors">@{{error}}</li>
                        </ul>
                    </td>
                    <td>
                        <a href="#" @@click.prevent="createNote()">
                            <span class="glyphicon glyphicon-ok" arial-hidden="true"></span>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <pre>@{{$data | json}}</pre>
@endsection()

@section('scripts')
    @verbatim
    <template id="select_category_tpl">
        <select v-model="id" class="form-control">
            <option value="">Seleccione Categoria</option>
            <option v-for="cotegory in categories" :value="cotegory.id">
                {{cotegory.name}}
            </option>
        </select>
    </template>
    <template id="note_row_tpl">
        <tr class="animated" transition="bounce-out">
            <template v-if="!editing">
                <td>{{note.category_id | category}}</td>
                <td>{{note.note}}</td>
                <td>
                    <a href="#" @click.prevent="edit()">
                        <span class="glyphicon glyphicon-pencil" arial-hidden="true"></span>
                    </a>
                    <a v-show="note.category_id != 3" href="#" @click.prevent="remove()">
                        <span class="glyphicon glyphicon-trash" arial-hidden="true"></span>
                    </a>
                </td>
            </template>
            <template v-else>
                <td><select-category :categories="categories" :id.sync="draft.category_id"/></td>
                <td>
                    <input type="text" v-model="draft.note" class="form-control">
                    <ul v-if="errors.length" class="text-danger">
                        <li v-for="error in errors">{{error}}</li>
                    </ul>
                </td>
                <td>
                    <a href="#"  @click.prevent="update()">
                        <span class="glyphicon glyphicon-ok" arial-hidden="true"></span>
                    </a>
                    <a href="#" @click.prevent="cancel()">
                        <span class="glyphicon glyphicon-remove" arial-hidden="true"></span>
                    </a>
                </td>
            </template>
        </tr>
    </template>
    @endverbatim
    <style>
        .alert-container{
            height: 65px;
        }
    </style>
    <script   src="http://code.jquery.com/jquery-3.1.0.js"
              integrity="sha256-slogkvB1K3VOkzAI8QITxV3VzpOnkeNVsKvtkYLMjfk="
              crossorigin="anonymous"></script>
    <script src="{{url('js/vue.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.9.3/vue-resource.js"></script>
    <script src="{{url('js/notes_avanzado.js')}}"></script>
    <link rel="stylesheet" href="{{url('css/animate.css')}}">
@endsection()
