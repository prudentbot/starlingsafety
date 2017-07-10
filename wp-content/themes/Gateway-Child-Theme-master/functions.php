<?php

function gateway_enqueue_parent_theme_style() {
    wp_enqueue_style( 'gateway-parent-style', get_template_directory_uri().'/style.css' );
    wp_dequeue_style( 'gateway-style' );
    wp_enqueue_style( 'gateway-child-style', get_stylesheet_directory_uri().'/style.css' );
}
add_action( 'wp_enqueue_scripts', 'gateway_enqueue_parent_theme_style', 99 );

add_filter('comment_flood_filter', '__return_false');

// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');

add_filter( 'kdmfi_featured_images', function( $featured_images ) {
    $args = array(
        'id' => 'sidebar-image',
        'desc' => '',
        'label_name' => 'Sidebar Image',
        'label_set' => 'Set Sidebar Image',
        'label_remove' => 'Remove Sidebar Image',
        'label_use' => 'Set Sidebar Image',
        'post_type' => array( 'page' ),
    );

    $featured_images[] = $args;

    return $featured_images;
});


/**
 * Template for comments and pingbacks.  Overwrites function in inc/template-tags.php in
 * parent theme.
 */
function gateway_comments( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
            case '' :
        ?>

        <li <?php comment_class(array(strtolower(get_comment_meta(get_comment_ID(), 'starling-comment-type', true)), ' clearfix')); ?>
          data-verifications="<?php $verifications = get_comment_meta(get_comment_ID(), 'cld_like_count', true); if(!$verifications){$verifications = 0;} echo $verifications; ?>" data-area="<?php echo get_comment_meta(get_comment_ID(), 'area', true);?>" id="li-comment-<?php comment_ID() ?>"
          data-date="<?php comment_date('U');?>">
          <?php //$like_count = get_comment_meta( $comment_id, 'cld_like_count', true ); ?>

            <div id="comment-<?php comment_ID(); ?>" class=" clearfix">

                <div class="comment-content">

                    <div class="comment-text">

                        <p class='comment-meta-header'>
                            <?php if(get_comment_meta(get_comment_ID(), 'anonymous', true)) : ?>
                              <cite class="fn">Anonymous</cite>
                            <?php else: ?>
                              <cite class="fn"><?php echo get_comment_author_link() ?></cite>
                            <?php endif;?>
                            <span class="comment-meta commentmetadata"><?php comment_date(get_option('date_format')); ?></span>

                            <?php if(get_user_meta(wp_get_current_user()->ID, "is_approved", true)){ comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));}?>
                            <!--<span class="comment-verification"> 1 Verification </span> -->

                            <?php
                              $area = get_comment_meta(get_comment_ID(), 'area', true);
                              $type = get_comment_meta(get_comment_ID(), 'starling-comment-type', true);
                              if($area && $type):
                            ?>
                                <p class="comment-meta-header">
                                  <cite class="comment-type-starling"> <?php echo $type; ?> </cite>
                                  <span class="comment-area"> for <?php echo $area; ?> area</span>
                                </p>
                              <?php
                                $start = get_comment_meta(get_comment_ID(), 'starling-comment-start-date', true);
                                $end = get_comment_meta(get_comment_ID(), 'starling-comment-end-date', true);
                                if($start && $end) : ?>
                                  <p class="comment-meta-header">
                                    <cite class="comment-type-starling">  <?php echo $start . ' - ' . $end; ?></cite>
                                  </p>
                                <?php endif;
                              endif;
                              ?>


                        </p><!-- .comment-meta-header -->

                        <?php if ($comment->comment_approved == '0') : ?><p class="moderated"><?php _e('Your comment is awaiting moderation.','gateway'); ?></p><?php endif; ?>

                        <div class="comment_content">

                        <?php comment_text() ?>

                        </div><!-- .comment_content -->

                    </div><!-- .comment-text-->

                </div><!-- .comment-content -->

            </div><!-- .comment-<?php comment_ID(); ?> -->

        <?php
            break;
            case 'pingback'  :
            case 'trackback' :
        ?>
            <li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">
            <div id="comment-<?php comment_ID(); ?>" class="clearfix">
                    <?php echo "<div class='author'><em>" . __('Trackback:','gateway') . "</em> ".get_comment_author_link()."</div>"; ?>
                    <?php echo strip_tags(substr(get_comment_text(),0, 110)) . "..."; ?>
                    <?php comment_author_url_link('', '<small>', '</small>'); ?>
             </div>
            <?php
            break;
        endswitch;
    }
