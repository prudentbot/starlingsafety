function cld_setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function cld_getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

jQuery(document).ready(function ($) {
    var ajax_flag = 0;
    $('.cld-like-dislike-trigger').click(function () {
        if (ajax_flag == 0) {
            var restriction = $(this).data('restriction');
            var comment_id = $(this).data('comment-id');
            var trigger_type = $(this).data('trigger-type');
            var selector = $(this);
            var cld_cookie = cld_getCookie('cld_' + comment_id);
            var current_count = selector.closest('.cld-common-wrap').find('.cld-count-wrap').html();
            var user_ip = $(this).data('user-ip');
            var ip_check = $(this).data('ip-check');
            var user_id = $(this).data('user-id');
            var id_check = $(this).data('id-check');
            var like_dislike_flag = 1;
            if (restriction == 'cookie' && cld_cookie != '') {
                like_dislike_flag = 0;
            }
            if (restriction == 'ip' && ip_check == '1') {
                like_dislike_flag = 0;
            }
            // eventually change to option?
            if (1 && id_check == '1') {
              var new_count = parseInt(current_count) - 1;
            }
            else{
              var new_count = parseInt(current_count) + 1;
            }
            if (like_dislike_flag == 1) {
                $.ajax({
                    type: 'post',
                    url: cld_js_object.admin_ajax_url,
                    data: {
                        comment_id: comment_id,
                        action: 'cld_comment_ajax_action',
                        type: trigger_type,
                        is_revert: id_check,
                        _wpnonce: cld_js_object.admin_ajax_nonce,
                        user_ip: user_ip
                    },
                    beforeSend: function (xhr) {
                        ajax_flag = 1;
                        if(trigger_type == 'like')
                          selector.closest('.cld-common-wrap').find('.cld-count-wrap').html(new_count);
                        //changeme
                        if(id_check == '1'){
                          selector.find('i').css("color", "grey")
                          selector.data("id-check", "0");
                          if(trigger_type == 'like'){
                            selector.attr("title", "Verify");
                          }
                          else {
                            selector.attr("title", "Flag Comment for Moderation");
                            // selector.closest(".comment").replaceWith("<li>This comment has been flagged for moderation.</li>")
                          }
                        }
                        else {
                          selector.find('i').css("color", "");
                          selector.data("id-check", "1");

                          if(trigger_type == 'like'){
                            selector.attr("title", "Unverify");
                          }
                          else{
                            selector.attr("title", "Unflag");
                          }
                        }
                    },
                    success: function (res) {
                        ajax_flag = 0;
                        res = $.parseJSON(res);
                        if (res.success) {
                            if(restriction == 'ip'){
                                selector.data('ip-check',1);
                            }
                            var cookie_name = 'cld_' + comment_id;
                            cld_setCookie(cookie_name, 1, 365);
                            var latest_count = res.latest_count;
                            if(trigger_type == 'like')
                              selector.closest('.cld-common-wrap').find('.cld-count-wrap').html(latest_count);
                        }
                    },
                    error: function (res) {
                      console.log("comment like/dislike ajax failure, printing response:");
                      console.log(res);
                    }

                });
            }
        }
    });


    $('.cld-like-dislike-wrap br,.cld-like-dislike-wrap p').remove();
    $( document ).tooltip();


});
