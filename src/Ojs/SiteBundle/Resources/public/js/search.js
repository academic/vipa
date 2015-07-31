var OJSAdvancedSearch = {
    searchQueryInput: $('#searchQueryInput'),
    searchFieldsArea: $('#advanced-search-fields-area'),
    searchFieldTemplate: $('#search-field-template').html(),
    init: function(){
        console.log('OJS Advanced Search System Starts!');
        this.insertFirstFieldItem();
        this.addFieldItem();
        this.watchFieldTypes();
    },
    watchFieldTypes: function(){
        var that=this;
        $(document).on("change", ".item-search-field", function(){
            var type = $('option:selected',this).attr('data-type');
            switch(type){
                case 'string':
                    that.changeInputType($(this),'text');
                    break;
                case 'long':
                    that.changeInputType($(this),'number');
                    break;
                case 'date':
                    that.changeInputType($(this),'date');
                    break;
                case 'boolean':
                    that.changeInputType($(this),'checkbox');
                    break;
                default:
                    that.changeInputType($(this),'text');
            }
        });
    },
    changeInputType: function(el,type){
        var item = el.parents('.search-field-items').find('.field-item-value');
        item.attr('type',type);
        return this;
    },
    getFirstFieldItem: function(){
        return this.searchFieldsArea.find('.search-field-items').eq(0);
    },
    getLastFieldItem: function(){
        return this.searchFieldsArea.find('.search-field-items').last();
    },
    searchQueryValue: function(){
        return this.searchQueryInput.val();
    },
    addFieldItem: function(){
        this.getLastFieldItem().find('.add-field-item').addClass('hidden');
        this.searchFieldsArea.append(this.searchFieldTemplate);
        return this;
    },
    removeFieldItem: function(item){
        var getItem = $(item).closest('.search-field-items');
        if(getItem.is(':first-child')){
            this.insertFirstFieldItem();
        }
        if(getItem.is(':last-child')){
            this.addFieldItem();
        }
        getItem.remove();
        this.updateInputQuery();
        return this;
    },
    insertFirstFieldItem: function () {
        this.searchFieldsArea.prepend(this.searchFieldTemplate);
        this.getFirstFieldItem().find('.condition-form').addClass('hidden');
        this.getFirstFieldItem().find('.add-field-item').addClass('hidden');
        return this;
    },
    updateInputQuery: function(){
        var searchQuery,firstItem,fieldItemValue,itemSearchField,itemCondition;
        searchQuery = '';
        firstItem = false;
        $.each(this.searchFieldsArea.find('.search-field-items'), function( index, item){
            fieldItemValue = $(item).find('.field-item-value').val();
            itemSearchField = $(item).find('.item-search-field').val();
            itemCondition = $(item).find('.item-condition').val();
            if(fieldItemValue.length>0){
                if(!firstItem){
                    searchQuery = '('+fieldItemValue+'['+itemSearchField+'])';
                    firstItem = true;
                }else{
                    searchQuery = '('+searchQuery+' '+itemCondition+' '+fieldItemValue+'['+itemSearchField+'])';
                }
            }
        });
        $('#searchQueryInput').val(searchQuery);
        return this;
    },
    redirectToSearch: function(){
        var searchQuery = $('#searchQueryInput').val();
        if(searchQuery.length>0){
            window.location = '/search?q='+searchQuery+'&type=advanced';
        }
    }
};
OJSAdvancedSearch.init();


