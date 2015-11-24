$(document).ready(function() {
  $(document.body).on('click', 'a[data-confirm]', function(e) {
    if (!confirm(this.getAttribute('data-confirm'))) {
      e.stopPropagation();
      return false;
    }
  });

  $(document.body).on('click', 'form[data-remote] input[type=submit], a[data-remote]', function() {
    $(document.body).data('ajax_emmiter', $(this));
  });

  $(document.body).on('click', 'a[data-remote]', function() {
    var method = this.getAttribute('data-method') || 'get';
    $.ajax({ type: method, url: this.href });
    return false;
  });
  $(document.body).on('click', 'form[data-remote]', function() {
    var method = this.getAttribute('method') || 'get';
    $.ajax({ type: method, url: this.action, data: $(this).serialize() });
    return false;
  });

  $(document.body).on('click', 'a[data-method]:not([data-remote])', function() {
    var method = this.getAttribute('data-method');
    if (this.getAttribute('data-submitted') || (method == 'delete' && !confirm('Вы уверены ?'))) { return false; }
    var $form = $("<form action='" + this.href + "' method='POST' style='display:none'><input type='hidden' name='_method' value='" + method + "'></form>");
    $(document.body).append($form);
    $form.submit();
    this.setAttribute('data-submitted', 1);
    return false;
  });

  $(document).ajaxStart(function() {
    obj = $(document.body).data('ajax_emmiter');
    if (obj) {
      obj.after('<div class="fa fa-cog fa-spin loader" style="width: ' + obj.outerWidth().toString() + 'px; height: ' + obj.outerHeight().toString() + 'px;"></div>');
      obj.addClass('ajax_hidden_emmiter');
      obj.hide();
    }
    $(document.body).removeData('ajax_emmiter');
  });

  $(document).ajaxStop(function () {
    $("div.loader:empty").remove();
    $('.ajax_hidden_emmiter').show();
  });
});