
var maybeShowStartEndDate = function (){
  var commentType = jQuery("#starling-comment-type").val();
  if(commentType == "Event"){
    jQuery(".comment-form-start-date, .comment-form-end-date").show();
  }
  else {
    jQuery(".comment-form-start-date, .comment-form-end-date").hide();
    jQuery("#starling-comment-start-date, #starling-comment-end-date").val('');
  }
}

jQuery( function() {
  jQuery("#commentform").attr("data-parsly-validate", " ");

  jQuery("#starling-comment-start-date, #starling-comment-end-date").datepicker({
      changeMonth: true,
      changeYear: true
    });

  jQuery(".comment-form-start-date, .comment-form-end-date").hide();

  jQuery("#commentform").parsley();

  jQuery(".comment-meta-header .comment-reply-link").click(function(){
    jQuery("#commentform .comment-form-area, #commentform .comment-form-type").hide();
    jQuery("#starling-comment-area, #starling-comment-type").removeAttr("required");
    jQuery(".comment-form-start-date, .comment-form-end-date").hide();
    jQuery("#starling-comment-start-date, #starling-comment-end-date").val('');

  });

  jQuery("#cancel-comment-reply-link").click(function(){
    jQuery("#commentform .comment-form-area, #commentform .comment-form-type").show();
    jQuery("#starling-comment-area, #starling-comment-type").attr("required", " ");
    maybeShowStartEndDate();
  });

  jQuery("#starling-comment-type").change(maybeShowStartEndDate);

});
