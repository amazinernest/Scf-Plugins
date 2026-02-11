<?php
/**
 * Single CPT Template
 *
 * This template uses the WordPress loop to display the post content
 * and then automatically appends all registered SCF fields below the content.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<div id="primary" class="content-area scf-single-cpt">
	<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php
					// Display Featured Image
					if ( has_post_thumbnail() ) {
						echo '<div class="scf-featured-image" style="margin-bottom: 20px;">';
						the_post_thumbnail( 'large' );
						echo '</div>';
					}

					// Display Content
					the_content();

					// Display Custom Fields automatically
					$fields = get_option( 'scf_fields', [] );
					if ( ! empty( $fields ) ) {
						$post_type = get_post_type();
						echo '<div class="scf-fields-container" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">';
						echo '<h3>' . __( 'Additional Information', 'simple-cpt-fields' ) . '</h3>';
						echo '<dl class="scf-fields-list">';
						
						foreach ( $fields as $key => $field ) {
							if ( isset( $field['post_type'] ) && $field['post_type'] === $post_type ) {
								$value = get_post_meta( get_the_ID(), $key, true );
								if ( ! empty( $value ) ) {
									echo '<dt style="font-weight:bold; margin-top:10px;">' . esc_html( $field['label'] ) . '</dt>';
									
									if ( isset( $field['type'] ) && $field['type'] === 'image' ) {
										echo '<dd>' . wp_get_attachment_image( $value, 'medium' ) . '</dd>';
									} elseif ( isset( $field['type'] ) && $field['type'] === 'url' ) {
										echo '<dd><a href="' . esc_url( $value ) . '" target="_blank">' . esc_html( $value ) . '</a></dd>';
									} elseif ( isset( $field['type'] ) && $field['type'] === 'color' ) {
										echo '<dd><span style="display:inline-block; width:20px; height:20px; background-color:' . esc_attr( $value ) . ';"></span> ' . esc_html( $value ) . '</dd>';
									} else {
										echo '<dd>' . wp_kses_post( $value ) . '</dd>';
									}
								}
							}
						}
						echo '</dl>';
						echo '</div>';
					}
					?>
				</div><!-- .entry-content -->

			</article><!-- #post-<?php the_ID(); ?> -->

		<?php
		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
