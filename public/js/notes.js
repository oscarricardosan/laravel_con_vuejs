function findById(items, id){
	for(var i in items){
		if(items[i].id == id){
			return items[i];
		}	
	}
	return null;
}
Vue.filter('category', function(id){
	var category = findById(this.categories, id);
	return category!=null?category.name:'Categoria no encontrada'; 
});
Vue.component('select-category', {
	template: '#select_category_tpl',
	props: ['categories', 'id'],
	
});
Vue.component('note-row', {
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
			this.draft = $.extend({}, this.note);
			this.editing = true;
		},
		update: function(){
			this.errors = [];
			$.ajax({
				url: 'api/v1/notes/'+this.note.id,
				method: 'PUT',
				data: this.draft,
				dataType: 'json',
				success:function(data){
					this.$parent.notes.$set(this.$parent.notes.indexOf(this.note), data.note);
					this.editing = false;
				}.bind(this),
				error:function(jqXHR){
					this.errors = jqXHR.responseJSON.errors;
					this.editing = true;
				}.bind(this)
			});
		},
		remove: function(){
			this.errors = [];
			$.ajax({
				url: 'api/v1/notes/'+this.note.id,
				method: 'DELETE',
				data: this.draft,
				dataType: 'json',
				success:function(data){
					this.$parent.notes.$remove(this.note);
				}.bind(this)
			});
		},
	},
	filters: {}
});

var vm = new Vue({
	el: 'body',
	data: {
		new_note:{
			note:'',
			category_id:''
		},
		errors:[],
		notes:[],
		categories:[],
		error: ''
	},
	methods: {
		createNote: function(){
			this.errors = [];
			$.ajax({
				url: 'api/v1/notes',
				method: 'POST',
				data: this.new_note,
				dataType: 'json',
				success:function(data){
					this.notes.push(data.note);
				}.bind(this),
				error:function(jqXHR){
					this.errors = jqXHR.responseJSON.errors;
				}.bind(this)
			});
			this.new_note = {note:'',category_id:''};
		}
	},
	ready: function(){
		$.getJSON('api/v1/notes', [], function(data){
			vm.notes = data;
		});
		$.getJSON('api/v1/categories', [], function(data){
			vm.categories = data;
		});
		$(document).ajaxError(function(event, jqXHR){
			this.error = jqXHR.responseJSON.message;
			$('#error-message').delay(3000).fadeOut(1000, function(){
				this.error = '';
			}.bind(this));
		}.bind(this));
	}
});