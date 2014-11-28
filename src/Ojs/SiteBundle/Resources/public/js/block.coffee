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
          document.location.reload()
          return false

    return false;
  $(".block_order_updater").on 'change', ->
    value = $(this).val()
    id = $(this).data('block-id')
    field = $(this)
    $.ajax
      url: "/block/order/#{id}/#{value}"
      type: 'POST'
      dataType: 'json'
      success: (rd)->
        if rd.status
          document.location.reload()
          return false

    return false;
