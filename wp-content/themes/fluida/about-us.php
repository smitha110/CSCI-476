<?php /* Template Name: AboutUs */ ?>



<style type="text/css">
	div.about{
   			 float: right;
   			 max-width: 500px;
   			 margin: 0;
   			 padding: 0em;
				}

</style>



		<div id="container" class="<?php echo fluida_get_layout_class(); ?>">
			

		<main id="main" role="main" class="main">
			<?php cryout_before_content_hook(); ?>
			
			<?php get_template_part( 'content/content', 'page' ); ?>

			<?php cryout_after_content_hook(); ?>
		</main><!-- #main -->
		
		<?php fluida_get_sidebar(); ?>

	</div><!-- #container -->
	
	<?php get_footer(); ?>


