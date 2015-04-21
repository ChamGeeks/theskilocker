	<div id="pageheader" class="titleclass">
		<div class="container">
			<?php get_template_part('templates/page', 'header'); ?>
		</div><!--container-->
	</div><!--titleclass-->
	
    <div id="content" class="container">
   		<div class="row">
	      	<div class="main <?php echo kadence_main_class(); ?>" role="main">
		      	<?php echo category_description(); ?> 
		      	<?php if (!have_posts()) : ?>
				  <div class="alert">
				    <?php _e('Sorry, no results were found.', 'virtue'); ?>
				  </div>
				  <?php get_search_form();
				endif;
				$itemsize 		= 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';
				$slidewidth 	= 366; 
				$slideheight 	= 366; 
				?>
				<div id="portfoliowrapper" class="rowtight">
				<?php while (have_posts()) : the_post(); ?>
					<div class="<?php echo esc_attr($itemsize);?>">
		                <div class="grid_item portfolio_item postclass">
							<?php global $post;
								if (has_post_thumbnail( $post->ID ) ) {
									$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); 
									$thumbnailURL = $image_url[0]; 
									$image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true);
									if(empty($image)) {$image = $thumbnailURL;} ?>
										<div class="imghoverclass">
			                                <a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
			                                   	<img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>" class="lightboxhover" style="display: block;">
			                                </a> 
			                            </div>
		                           				<?php $image = null; $thumbnailURL = null;?>
		                           <?php } ?>
					              	<a href="<?php the_permalink() ?>" class="portfoliolink">
					              		<div class="piteminfo">   
					                          <h5><?php the_title();?></h5>
					                    </div>
					                </a>
		                </div>
		            </div>
				<?php endwhile; ?>
		        </div> <!--portfoliowrapper-->
		        
		        <?php  	if ($wp_query->max_num_pages > 1) :
					        virtue_wp_pagenav();
					    endif; 

		                $wp_query = null; 
		                wp_reset_query(); ?>
			</div><!-- /.main -->