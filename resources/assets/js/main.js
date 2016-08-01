var Vue = require('vue');
Vue.use(require('vue-resource'));
//https://github.com/vuejs/vue-resource/tree/master/docs
function findById(items, id){
    for(var i in items){
        if(items[i].id == id){
            return items[i];
        }
    }
    return null;
};
var resource;
var resource_category;
/*https://daneden.github.io/animate.css/*/
//Cuando el elemento con clase bounce-out cambie use las cases de transacciones de abajo
Vue.transition('bounce-out',{
    enterClass: 'bounceInLeft',
    leaveClass: 'bounceOutRight'
});
Vue.transition('myFade',{
    enterClass: 'bounceInLeft',
    leaveClass: 'bounceOutRight'
});
Vue.filter('category', function(id){
    var category = findById(this.categories, id);
    return category!=null?category.name:'Categoria no encontrada';
});
Vue.component('select-category', {
    template: '#select_category_tpl',
    props: ['categories', 'id'],

});
Vue.component('note-row', require('./components/note-row.vue'));

var vm = new Vue({
    el: 'body',
    data: {
        new_note:{
            note:'',
            category_id:''
        },
        alert:{
            message: '',
            display: false
        },
        errors:[],
        notes:[],
        categories:[],
        error: ''
    },
    events: {

    },
    methods: {
        createNote: function(){
            this.errors = [];
            resource.save({}, this.new_note).then(function(respon){
                this.notes.push(respon.data.note);
            }, function(respon){
                this.errors = respon.data.errors;
            });
            this.new_note = {note:'',category_id:''};
        },
        updateNote: function(vm_component){
            resource.update({id: vm_component.note.id}, vm_component.draft).then(function(respon){
                for(var key in respon.data.note){
                    vm_component.note[key] = respon.data.note[key];
                }
                vm_component.editing = false;
            },function(respon){
                vm_component.errors = respon.data.errors;
            });
        },
        deleteNote: function(note){
            resource.delete({id: note.id}).then(function(respon){
                this.notes.$remove(note);
            });
        }
    },
    ready: function(){
        //https://github.com/vuejs/vue-resource/blob/master/docs/resource.md
        resource = this.$resource('api/v1/notes{/id}');//id es opcional
        resource_category = this.$resource('api/v1/categories');//id es opcional
        resource.get().then(function(respon){
            this.notes = respon.data;
        });
        resource_category.get().then(function(respon){
            this.categories = respon.data;
        });
        //https://github.com/vuejs/vue-resource/blob/master/docs/http.md
        Vue.http.interceptors.push(function(request, next){
            next(function(response){
                if(response.ok)
                    return response;
                this.alert.message = response.data.message;
                this.alert.display = true;
                setTimeout(function(){
                    this.alert.display = false;
                }.bind(this), 4000);
                return response;
            }.bind(this));
        }.bind(this));
    }
});
Vue.http.options.emulateJSON = true;