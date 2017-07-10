
var updateSearch = function() {
  var sortType = jQuery("#starling-risk-index-sort").val();

  var rows = []
  if(sortType == "Rank"){
    rows = jQuery("#starling-risk-index tr").slice(1).sort(function(a, b){
      if(parseInt(a.children[0].innerText) > parseInt(b.children[0].innerText))
        return 1;
      if(parseInt(a.children[0].innerText) < parseInt(b.children[0].innerText))
        return -1;
      else
        return 0;
    });
  }
  if(sortType == "Alphabetical"){
    rows = jQuery("#starling-risk-index tr").slice(1).sort(function(a, b){
      if(a.children[1].innerText > b.children[1].innerText)
        return 1;
      if(a.children[1].innerText < b.children[1].innerText)
        return -1;
      else
        return 0;
    });
  }

  rows.detach().appendTo('#starling-risk-index tbody');
}

jQuery( function() {
  jQuery("#starling-risk-index-sort").change(updateSearch);
});
