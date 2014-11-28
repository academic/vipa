$(document).ready ->
  $(".blocklink_order_updater").on 'change', ->
    value = $(this).val()
    id = $(this).data('link-id')
    field = $(this)
    $.ajax
      url: "/block_link/order/#{id}/#{value}"
      type: 'POST'
      dataType: 'json'
      success: (rd)->
        if rd.status
          field.css
            border: '2px solid green'
          .delay 2000
          .css
              border: '2px inset'
          return false

    return false;
