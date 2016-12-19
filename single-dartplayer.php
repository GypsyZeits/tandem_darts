<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();


			// Include the single post content template.
			get_template_part( 'template-parts/content', 'single' );
			echo '<h3>Phone: '.get_post_meta( get_the_id(), '_phoneNumber', true ).'</h3>';
			echo '<h3>Email: '.get_post_meta( get_the_id(), '_emailAddress', true ).'</h3>';
			// If comments are open or we have at least one comment, load up the comment template.

			$args = array(
			
			//Type & Status Parameters
			'post_type'   => 'dartmatch',
			'post_status' => 'publish',

			
			//Order & Orderby Parameters
			'order'               => 'ASC',
			'orderby'             => 'meta_value',
			'meta_key'			  => '_matchDate',
			'meta_type'			  => 'DATE',
			
			//Pagination Parameters
			'nopaging'               => true,
			
			//Custom Field Parameters
			'meta_query'     => array(
				'relation' 	 => 'AND',
				array(
					'relation'	=> 'OR',
					array(
						'key' => '_player1',
						'value' => get_the_id(),
						'compare' => '='
					),
					array(
						'key' => '_player2',
						'value' => get_the_id(),
						'compare' => '='
					)
				),
				array(
					'key' => '_matchWinner',
					'compare' => 'NOT EXISTS'
				)

			));
			$query = new WP_Query( $args );
			//var_dump($query->have_posts());
			
			if ($query->have_posts()){
				while ($query->have_posts()){
					$query->the_post();
					$matchMeta = get_post_meta( get_the_ID() );
					echo the_title( '<p>', '</p>', false );
							
				}
				wp_reset_postdata();
			}

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			if ( is_singular( 'attachment' ) ) {
				// Parent post navigation.
				the_post_navigation( array(
					'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'twentysixteen' ),
				) );
			} elseif ( is_singular( 'post' ) ) {
				// Previous/next post navigation.
				the_post_navigation( array(
					'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentysixteen' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Next post:', 'twentysixteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentysixteen' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Previous post:', 'twentysixteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
				) );
			}

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
