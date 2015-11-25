!function($) {
  "use strict"; // jshint ;_;

  var todo = function(element, options) {
    this.$element = $(element);
    this.$toggle_all = this.$element.find('.toggle-all');
    this.$new_todo = this.$element.find('.new-todo');
    this.$todo_list = this.$element.find('.todo-list');
    this.$clear_completed = this.$element.find('.clear-completed');
    this.$todo_count = this.$element.find('.todo-count strong');

    this.items = this.$todo_list.find('li').length;
    this.items_left = this.items;

    this.set_complete_relations();
    this.listen();
    this.current_filter = 'all';

    this.$todo_list.find('li .toggle:checked').trigger('change');
  };

  todo.prototype = {
    constructor: todo,
    listen: function() {
      this.$toggle_all.on('change', $.proxy(this.toggle_all, this));
      this.$new_todo.on('keypress', $.proxy(this.new_keyup, this));
      this.$todo_list.on('change', '.toggle',  $.proxy(this.item_toggle, this))
                     .on('click',  '.destroy', $.proxy(this.destroy_click, this));
      this.$clear_completed.on('click', $.proxy(this.clear_completed_click, this));
      this.$element.on('click', '.filters li a', $.proxy(this.filter, this));
    },

    toggle_all: function(e) {
      var $lis = this.$todo_list.find('.toggle').prop('checked', e.target.checked).closest('li');
      if (e.target.checked) {
        $lis.addClass('completed');
        this.items_left = 0;
      } else {
        $lis.removeClass('completed');
        this.items_left = this.items;
      }
      this.set_complete_relations();
    },
    new_keyup: function(e) {
      if (e.keyCode == 9) {
        e.target.value = '';
      } else if (e.keyCode == 13) {
        this.build_item(e.target.value);
        e.target.value = '';
        e.stopPropagation();
        e.preventDefault();
      }
    },
    item_toggle: function(e) {
      if (e.target.checked) {
        $(e.target).closest('li').addClass('completed');
        this.items_left --;
      } else {
        $(e.target).closest('li').removeClass('completed');
        this.items_left ++;
      }
      this.set_complete_relations();
    },
    destroy_click: function(e) {
      var $li = $(e.target).closest('li');
      if (!$li.length) { return; }
      $li.remove();
      if (!$li.hasClass('completed')) {
        this.items_left --;
      }
      this.items --;
      this.set_complete_relations();
    },
    clear_completed_click: function(e) {
      this.$todo_list.find('li.completed .destroy').trigger('click');
    },
    filter: function(e) {
      var filter = e.target.getAttribute('data-type');
      this.current_filter = filter;
      this.$todo_list.find('li').removeClass('hidden');
      if (filter == 'active') {
        this.$todo_list.find('li.completed').addClass('hidden');
      } else if (filter == 'completed') {
        this.$todo_list.find('li:not(.completed)').addClass('hidden');
      }
      var $target = $(e.target);
      $target.closest('.filters').find('li a').removeClass('selected');
      $target.addClass('selected');
      return false;
    },

    build_item: function(name) {
      if (!name) { return; }
      name= name.trim();
      if (!name) { return; }
      var html = '<li' + (this.current_filter == 'completed' ? ' class="hidden"' : '') + '>';
      html += '<div class="view">';
      html += '<input class="toggle" type="hidden" value="0" name="items[][completed]">';
      html += '<input class="toggle" type="checkbox" value="1" name="items[][completed]">';
      html += '<label>' + name + '</label>';
      html += '<button class="destroy"></button>';
      html += '</div>';
      html += '<input class="edit" value="' + name + '" name="items[][name]">';
      html += '</li>';
      this.$todo_list.prepend(html);
      this.items ++;
      this.items_left ++;
      this.set_complete_relations();
    },
    set_complete_relations: function() {
      if (this.items > this.items_left) {
        this.$clear_completed.show();
      } else {
        this.$clear_completed.hide();
      }
      this.$todo_count.html(this.items_left);
      this.$toggle_all.prop('checked', this.items_left == 0);
    }
  };

  var old = $.fn.todo;

  $.fn.todo = function(option) {
    return this.each(function() {
      var $this = $(this)
        , data = $this.data('todo');
      if (!data) { $this.data('todo', (data = new todo(this))); }
      if (typeof option == 'string') { data[option](); }
    })
  };

  $.fn.todo.Constructor = todo;

} (window.jQuery);