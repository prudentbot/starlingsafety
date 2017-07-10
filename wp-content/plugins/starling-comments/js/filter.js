

var updateSearch = function(){
  var commentType = jQuery("#starling-comment-sort-type").val();

  var commentArea = jQuery("#starling-comment-sort-area").val();
  if(!commentType || commentType === "All"){
    if(!commentArea || commentArea === "All"){
      jQuery(".comment").show();
    }
    else{
      jQuery(".comment").each(function(){
        if(jQuery(this).data("area") === commentArea)
          jQuery(this).show();
        else
          jQuery(this).hide();
      });
    }
  }
  else{
    commentType = commentType.toLowerCase();
    if(!commentArea || commentArea === "All"){
      jQuery(".comment").not("." + commentType).hide();
      jQuery(".comment").filter("." + commentType).show();
    }
    else{
      jQuery(".comment").not("." + commentType).each(function(){
        jQuery(this).hide();
      });
      jQuery(".comment").filter("." + commentType).each(function(){
        if(jQuery(this).data("area") === commentArea)
          jQuery(this).show();
        else
          jQuery(this).hide();
      });
    }
  }

}


// Autocomplete for area text fields
jQuery( function() {
  var data = jQuery(".comment").data("area");
  var areas = [];
  jQuery(".comment").each(function(index){
    var area = jQuery(this).data("area");
    if (areas.indexOf(area) !== -1 || area.length === 0)
      return;

    areas.push(area);
  })

  areas = areas.sort()

  jQuery( "#starling-comment-area" ).autocomplete({
    source: areas
  });

  jQuery.each(areas, function (i, item) {
    jQuery('#starling-comment-sort-area').append(jQuery('<option>', {
        value: item,
        text : item
    }));
  });

  jQuery("#starling-comment-sort-type").change(updateSearch);
  jQuery("#starling-comment-sort-area").change(updateSearch);
  jQuery("#starling-comment-sort-other").change(sortBy);

  sortBy();

  // make all comment links open new tabs
  jQuery(".comment a").attr("target", "_blank");

  jQuery("#starling-comment-search").on("input", function(e) {
    var input = jQuery(e.target).val();
    if(input.length === 0){
      updateSearch();
      sortBy();
      return;
    }
    jQuery(".comment").each(function(){
      if(jQuery(this).text().toLowerCase().search(input.toLowerCase()) === -1){
        jQuery(this).hide();
      }
      else {
        jQuery(this).show();
      }
    });
  });
});

var sortBy = function () {
  var sortType = jQuery("#starling-comment-sort-other").val();
  if(sortType == "Verifications")
    sortByVerifications();
  else
    sortByDate();
}

var sortByVerifications = function () {
  var comments = jQuery('.comment.depth-1').sort(function(a,b) {
      var aData = jQuery(a).data('verifications');
      var bData = jQuery(b).data('verifications');

      var aDate = jQuery(a).data('date');
      var bDate = jQuery(b).data('date');

      if(aData < bData){
        return 1;
      }
      else if(aData > bData){
        return -1;
      }
      else{
        if(aDate < bDate){
          return 1;
        }
        else
          return -1;
      }
    });
    comments.detach().appendTo('.comment-list');
}

var sortByDate = function () {
  var comments = jQuery('.comment.depth-1').sort(function(a,b) {
      var aData = jQuery(a).data('date');
      var bData = jQuery(b).data('date');
      if(aData < bData){
        return 1;
      }
      else if(aData > bData){
        return -1;
      }
      else{
        return 0;
      }
    });
    comments.detach().appendTo('.comment-list');
}
