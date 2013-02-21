jQuery(document).ready(function($)
{
    if ($('.close').size()) {
      $('.close').bind('click', function(){
        $(this).parent().fadeOut('slow', function() {
            $(this).remove();
        });
        return false;
      });
    }

    if ($('table.sortTable').size()) {
      $("table.sortTable").tablesorter({ sortList: [[0,1]] });
    }

    if($('.blank').size()) {
      $('.blank').attr('target', '_blank').removeClass('blank');
    }

    if($('.confirm').size()) {
      $('.confirm').bind('click', function(){
        return confirm("Valider l'action ?");
      });
    }

    $(".fancy").fancybox({'type' : 'image'});

    $("#stars").children().not(":input").hide();
    $("#stars").stars({
      cancelShow: false,
      callback: function(ui, type, value){
        $('input:hidden').attr('disabled', false);
      }
    });

    if($('#sub_category_text').size()) {
      $("#sub_category_text").autocomplete({
        source: function(request, response) {
          $.ajax({
            url: BASE_URL + 'category/options',
            dataType: 'json',
            data: {
              term: request.term
            },
            success: function(data) {
              response($.map(data, function(item) {
                var name = item.name;
                $('#sub_category_id').val(item.id);
                return {
                  label: name,
                  value: name,
                  id: item.id
                }
              }));
            }
          });
        }
      });
    }
});