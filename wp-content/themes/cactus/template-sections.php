<?php
/**
 * Template Name: Front Page
 */
 get_header();
 ?>
<div class="page-wrap">
  <div class="container-fullwidth">
    <div class="page-inner row no-aside">
      <div class="col-main">
        <section class="post-main" role="main" id="content">
          <article class="post-entry text-left">
            <div class="cactus-sections-wrap">
              <?php do_action( 'cactus_before_sections' );?>
              <?php do_action( 'cactus_sections' );?>
              <?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/page/content' );

					the_posts_pagination( array(
					'prev_text' => '<i class="fa fa-arrow-left"></i><span class="screen-reader-text">' . __( 'Previous page', 'cactus' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'cactus' ) . '</span><i class="fa fa-arrow-right"></i>' ,
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'cactus' ) . ' </span>',
				) );

				endwhile; // End of the loop.
			?>
              <?php do_action( 'cactus_after_sections' );?>
            </div>
          </article>
        </section>
      </div>
    </div>
  </div>
</div>
<?php
 get_footer();
