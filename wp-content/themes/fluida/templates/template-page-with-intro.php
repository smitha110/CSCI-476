<?php
/**
 * Template Name: Category page with intro
  *
 * A custom page template for showing posts on a chosen category.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package Fluida
 */

get_header(); ?>

<div id="container" class="<?php echo fluida_get_layout_class(); ?>">
	<main id="main" role="main" class="main">

	 <?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'pad-container' ); ?>>
			<header>
				<?php the_title( '<h1 class="entry-title" ' . cryout_schema_microdata( 'entry-title', 0 ) . '>', '</h1>' ); ?>

				<span class="entry-meta" >
					<?php edit_post_link( __( 'Edit', 'fluida' ), '<span class="edit-link"><i class="icon-edit"></i> ', '</span>' ); ?>
				</span>
			</header>

			<div class="entry-content">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'fluida' ), 'after' => '</div>' ) ); ?>
			</div>
		</article>

		<?php
		$single = true;
		$slug = basename( esc_url( get_permalink() ) );
		$meta_slug = get_post_meta( get_the_ID(), "slug", $single ); // slug custom field
		$meta_catid = get_post_meta( get_the_ID(), "catid", $single ); // category_id custom field
		$key = get_post_meta( get_the_ID(), "key", $single ); // either slug or category_id custom field
		$slug = ( $key ? $key : ( $meta_catid ? $meta_catid : ( $meta_slug ? $meta_slug : ( $slug ? $slug : 0 ) ) ) ); // select one value out of the custom fields
		?>
	<?php endwhile; ?>

		<div id="content-masonry" <?php cryout_schema_microdata( 'blog' ); ?>>
			<?php
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			if ( is_numeric( $slug ) && ( $slug > 0 ) ) :
				$the_query = new WP_Query( 'cat=' . $slug . '&post_status=publish&orderby=date&order=desc&posts_per_page=' . get_option( 'posts_per_page' ) . '&paged=' . $paged );
			else:
				$the_query = new WP_Query( 'category_name=' . $slug . '&post_status=publish&orderby=date&order=desc&posts_per_page=' . get_option( 'posts_per_page' ) . '&paged=' . $paged );
			endif;
			/* Start the Loop */
			while ( $the_query->have_posts() ) : $the_query->the_post();
				global $more; $more=0; // more gets lost inside page templates
				get_template_part( 'content/content', get_post_format() );
			endwhile;
			fluida_pagination();
			?>
		</div><!-- #content-masonry -->

	</main><!-- #main -->

	<?php fluida_get_sidebar(); ?>
</div><!-- #container -->

<?php get_footer(); ?>
