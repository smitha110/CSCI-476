<?php /* Template Name: AboutUs */ ?>



<?php get_header(); ?>
<style type="text/css">
	div.about{
   			 float: right;
   			 max-width: 500px;
   			 margin: 0;
   			 padding: 0em;
				}
				
	div.about-pic {
   			 float: left;
   			 max-width: 360px;
   			 margin: 0;
   			 padding: 0em;
				}
				
	div.pastor{
   			 float: right;
   			 max-width: 500px;
   			 margin: 0;
   			 padding: 0em;
				}
				
	div.pastor-pic {
   			 float: left;
   			 max-width: 360px;
   			 margin: 0;
   			 padding: 0em;
				}
				
	div.officers {
   	
   			 float:left;
   			 margin: 0;
   			 padding: 3em;
				}
</style>

		<head>
			<center>About Us</center>
		</head>
		<main id="main" class="site-main" role="main">
			
			<div class = "about">
			
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>
			
			^^^^^^^^^^^^ This is where we can write all about the church ^^^^^^^^^^^^
			
			</div>
			
		
			<div class = "about-pic">
				<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>
			
			Picture of the church ^
			
			</div>
			
				<div class = "pastor">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>
			
			^^^^^^^^^^^^ This is where we can write all about the pastor ^^^^^^^^^^^^
			</div>
			
		
			<div class = "pastor-pic">
				<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>
			
			Picture of the pastor ^
			</div>

			<div class = "officers">
				<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>
			
			This is for the officers section. I'm trying to figure out how to write this php/html stuff so i'm just kind of messing around with it here. Don't judge it. Bear with me.
			Also trying to make each section independently editable. Dunno how
			
			</div>

		</main><!-- #main -->
	
	


<?php get_sidebar(); ?>
<?php get_footer(); ?>
