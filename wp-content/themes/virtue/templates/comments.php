<?php
  if (post_password_required()) {
    return;
  }

 if (have_comments()) : ?>
  <section id="comments">
    <h3><?php printf(_n('One Response ', '%1$s Responses ', get_comments_number(), 'virtue'), number_format_i18n(get_comments_number()), get_the_title()); ?></h3>

    <ol class="media-list">
      <?php wp_list_comments(array('walker' => new Kadence_Walker_Comment)); ?>
    </ol>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
    <nav>
      <ul class="pager">
        <?php if (get_previous_comments_link()) : ?>
          <li class="previous"><?php previous_comments_link(__('&larr; Older comments', 'virtue')); ?></li>
        <?php endif; ?>
        <?php if (get_next_comments_link()) : ?>
          <li class="next"><?php next_comments_link(__('Newer comments &rarr;', 'virtue')); ?></li>
        <?php endif; ?>
      </ul>
    </nav>
    <?php endif; ?>

    <?php if (!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
    <?php global $virtue; if(isset($virtue['close_comments'])) {$show_closed_comment = $virtue['close_comments']; } else {$show_closed_comment = 1;}
    if($show_closed_comment == 1){ ?>
    <div class="alert">
      <?php _e('Comments are closed.', 'virtue'); ?>
    </div>
    <?php } else { } ?>
<?php endif; ?>
  </section><!-- /#comments -->
  <?php endif; ?>

<?php if (!have_comments() && !comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
  <?php global $virtue; if(isset($virtue['close_comments'])) {$show_closed_comment = $virtue['close_comments']; } else {$show_closed_comment = 1;}
    if($show_closed_comment == 1){ ?>
  <section id="comments">
    <div class="alert">
      <?php _e('Comments are closed.', 'virtue'); ?>
    </div>
  </section><!-- /#comments -->
  <?php } else { } ?>
<?php endif; ?>

<?php if (comments_open()) : ?>
  <section id="respond">
     <?php if ( did_action( 'jetpack_comments_loaded' ) ) : ?>
    <?php comment_form(); ?>
    <?php else: ?>
    <h3><?php comment_form_title(__('Leave a Reply', 'virtue'), __('Leave a Reply to %s', 'virtue')); ?></h3>
    <p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
    <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
      <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'virtue'), wp_login_url(get_permalink())); ?></p>
    <?php else : ?>
      <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
        <?php if (is_user_logged_in()) : ?>
          <p>
            <?php printf(__('Logged in as <a href="%s/wp-admin/profile.php">%s</a>.', 'virtue'), get_option('siteurl'), $user_identity); ?>
            <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php __('Log out of this account', 'virtue'); ?>"><?php _e('Log out &raquo;', 'virtue'); ?></a>
          </p>
        <?php else : ?>
        <div class="row">
        <?php $fields   =  array(
            'author' => '<div class="col-md-4">' . '<label for="author">' . __('Name', 'virtue') . ( $req ? ' <span class="comment-required">*</span>' : '' ) . '</label> ' .
                        '<input id="author" name="author" type="text" value="' . esc_attr( $comment_author ) . '" ' . ( $req ? 'aria-required="true"' : '') . ' /></div>',
            'email'  => '<div class="col-md-4"><label for="email">' . __( 'Email (will not be published)', 'virtue') . ( $req ? ' <span class="comment-required">*</span>' : '' ) . '</label> ' .
                        '<input type="email" class="text" name="email" id="email" value="' . esc_attr(  $comment_author_email ) . '" ' . ( $req ? 'aria-required="true"' : '') . ' /></div>',
            'url'    => '<div class="col-md-4"><label for="url">' . __( 'Website', 'virtue' ) . '</label> ' .
                        '<input id="url" name="url" type="url" value="' . esc_attr( $comment_author_url ) . '" /></div>',
          );
          $fields = apply_filters( 'comment_form_default_fields', $fields ); 
          do_action( 'comment_form_before_fields' );
          foreach ( $fields as $name => $field ) {
            echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
          }
          do_action( 'comment_form_after_fields' );?>
        </div>
        <?php endif; ?>
        <label for="comment"><?php _e('Comment', 'virtue'); ?></label>
        <textarea name="comment" id="comment" class="input-xlarge" rows="5" aria-required="true"></textarea>
        <p><input name="submit" class="kad-btn kad-btn-primary" type="submit" id="submit" value="<?php _e('Submit Comment', 'virtue'); ?>"></p>
        <?php comment_id_fields(); ?>
        <?php do_action('comment_form', $post->ID); ?>
      </form>
    <?php endif; ?>
    <?php endif; ?>
  </section><!-- /#respond -->
<?php endif; ?>
