<template id="note_row_tpl">
    <tr class="animated" transition="bounce-out">
        <div v-if="!editing">
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
        </div>
        <div v-else>
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
        </div>
    </tr>
</template>
<script>
    export default {
        template: '#note_row_tpl',
        props: ['note', 'categories'],
        data: function(){
            return {
                editing: false,
                errors: [],
                draft:{}
            };
        },
        methods: {
            cancel: function () {
                this.editing = false;
            },
            edit: function(){
                this.errors = [];
                this.draft = JSON.parse(JSON.stringify(this.note));
                this.editing = true;
            },
            update: function(){
                this.errors = [];
                this.$dispatch('update-note', this);
            },
            remove: function(){
                this.$dispatch('delete-note', this.note);
            }
        },
        filters: {}
    }
</script>