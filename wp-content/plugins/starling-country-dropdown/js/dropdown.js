jQuery(function(){

  // bind change event to select
  jQuery('#starling-sidebar-country-dropdown').on('change', function () {
      var url = jQuery(this).val(); // get selected value
      if (url) { // require a URL
          window.location = url; // redirect
      }
      return false;
  });
});
