jQuery( function() {
  var areas = [];

  jQuery("#starling-country-search-wrap ul a").each(function(index){
    var area = jQuery(this).text();
    if (area.length === 0)
      return;

    areas.push(area);
  });

  jQuery( "#starling-country-search" ).autocomplete({
    options: {
     renderItem: null,
     renderMenu: null
    },
    source: areas,
    _renderMenu: function(){
      if ( $.isFunction( this.options.renderMenu ) ) {

        this.options.renderMenu( ul, items );

      }

      this._super( ul, items );
    },
    response: function(event, ui){
      jQuery("#starling-country-search-wrap").children().hide().children().hide();
      jQuery("#starling-country-search-wrap ul a").each(function(){
        var link = jQuery(this);

        //O(n^2) for life mofos
        var count=ui.content.length;
        for(var i=0;i<count;i++)
        {
          if(ui.content[i].value===link.text()){
            link.closest("ul").show();
            link.closest("li").show();
          }
        }
      });
    },
    create: function() {
      jQuery(this).data('ui-autocomplete')._renderMenu  = function (ul, item) {
        //do nothing!
      };
    }
  }).on("input", function(e) {
    var input = jQuery(e.target).val();
    if(input.length === 0){
      jQuery("#starling-country-search-wrap").children().show().children().show();
    }
  });

});
