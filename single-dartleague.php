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
			//echo "testingwhatwhat";
			// Include the single post content template.
			
			get_template_part( 'template-parts/content', 'single' );

			//for the league, get all matches that have winners, and count their points
			$leagueID =get_the_ID();
			global $wpdb;
			$winnerID = $wpdb->get_results("SELECT DISTINCT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = '_matchWinner' AND `post_id` IN (SELECT `post_id` FROM `wp_postmeta` WHERE `meta_key` = '_leagueID' AND `meta_value` = '$leagueID')");
			//var_dump($winnerID);

			$standings = array();
			foreach ($winnerID as $key => $value) {
				//var_dump($value->meta_value);
				$score = $wpdb->get_var("
				SELECT 
					COUNT(*) 
				FROM 
					`wp_postmeta` 
				WHERE 
					`meta_key` = '_matchWinner' 
					AND `meta_value` = '$value->meta_value' 
					AND `post_id` IN (
						SELECT 
							`post_id` 
						FROM 
							`wp_postmeta` 
						WHERE 
							`meta_key` = '_leagueID' 
							AND `meta_value` = '$leagueID')
				");
				//var_dump($score);
				$standings[$value->meta_value] = (int) $score;
			}
			arsort($standings);

			echo '<h2>Point Standings</h2>';
			foreach ($standings as $key => $value) {
				echo '<p>'.get_the_title( $key ).' : '.$value.' Points</p>';
			}

			$league_meta = get_post_meta( get_the_ID() );

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
					'key' => '_leagueID',
					'value' => get_the_ID(),
					'type' => 'NUM',
					'compare' => '='
					))
			
			);

		$query = new WP_Query( $args );

		if ($query->have_posts()){
			?><h3>schedule of matches</h3>
			<?php
			while ($query->have_posts()){
				$query->the_post();
				$matchMeta = get_post_meta( get_the_ID() );

				echo the_title( '<p>', '</p>', false );

			}

			wp_reset_postdata();
		}

			// If comments are open or we have at least one comment, load up the comment template.
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

	<?php //get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
