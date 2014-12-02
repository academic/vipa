analytics =
    increase: (entity,id)->
      url = "/view/#{entity}/#{id}"
      this.request(url,'PUT')

    request: (url,type)->
      $.ajax
        url: '/api/analytics'+url
        dataType: 'json'
        data: 'page_url='+document.location.href
        type: type
        success: (rd)->
          if rd.id
            yes
          else
            no