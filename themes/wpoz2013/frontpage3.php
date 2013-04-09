<?php
/*
Template Name: Frontpage 3
*/
?>

<?php get_header() ?>

<section>

<div id="content">

<div id="frontpage-info">
<div id="banner">  				
	<div class="oneByOne_item oneByOne-slide-1">
	<div class="oneByOne-left" data-animate="bounceInLeft">
		<div class="slider-item-1" data-animate="bounceInLeft">Hello!</div>
		<div class="slider-item-2" data-animate="bounceInUp">Welcome to <?php bloginfo('name'); ?></div>
	</div>

		<div class="slider-item-3" data-animate="bounceInRight"><a href="register">Create profile</a></div>
		<div class="slider-item-4" data-animate="bounceInRight"><a href="members">Find friends</a></div>
		<div class="slider-item-5" data-animate="bounceInRight"><a href="groups">Post messages</a></div>
		<div class="slider-item-6" data-animate="bounceInRight">Make connections</div>		  			
	</div><!-- oneByOne_item -->


	<div class="oneByOne_item oneByOne-slide-2">
	<div class="oneByOne-a" data-animate="bounceInLeft">34.000 
Members</div>	  			
	</div><!-- oneByOne_item -->


	<div class="oneByOne_item oneByOne-slide-3">
	<div class="oneByOne-b" data-animate="bounceInUp">25 Active Groups</div>	  			
	</div><!-- oneByOne_item -->
	
</div> 
</div><!-- #frontpage-info -->

		<?php do_action( 'bp_after_header' ) ?>

		<?php do_action( 'bp_before_container' ) ?>
<div class="clear"> </div>

<div class="frontpage">

<div class="clear"> </div>


<div class="frontpage-bottom-left">

<div class="front-box widget">
<div class="front-box-title"><?php echo of_get_option('t-17', 'Active Members' ); ?></div>
<div class="front-box-child">

<?php if ( bp_has_members( 'type=active&max=8' ) ) : ?>			
			<?php while ( bp_members() ) : bp_the_member(); ?>						
				<a href="<?php bp_member_permalink() ?>" class="front-member-item"><?php bp_member_avatar('type=full&width=60&height=60') ?></a>
			<?php endwhile; ?>
<?php endif; ?>
		
</div><!--front-box-child ends-->
</div><!--front-box ends-->

<div class="clear"></div>

<div class="front-box widget">
<div class="front-box-title"><?php echo of_get_option('t-19', 'Popular Members' ); ?></div>
<div class="front-box-child">

<?php if ( bp_has_members( 'type=popular&max=8' ) ) : ?>			
			<?php while ( bp_members() ) : bp_the_member(); ?>						
				<a href="<?php bp_member_permalink() ?>" class="front-member-item"><?php bp_member_avatar('type=full&width=60&height=60') ?></a>
			<?php endwhile; ?>
<?php endif; ?>
		
</div><!--front-box-child ends-->
</div><!--front-box ends-->

</div> <!-- frontpage-bottom-left -->

<div class="frontpage-bottom-right">

<div class="front-box">
<div class="front-box-title"><?php echo of_get_option('t-3', 'On the Forums' ); ?></div>

<div class="front-box-child">

	<?php if ( bbp_has_topics( array( 'author' => 0, 'show_stickies' => false, 'order' => 'DESC', 'post_parent' => 'any', 'posts_per_page' => 5 ) ) ) : ?>
		<?php bbp_get_template_part( 'loop', 'mytopics' ); ?>
	<?php else : ?>
		<?php bbp_get_template_part( 'feedback', 'no-topics' ); ?>
	<?php endif; ?>

</div>
<div class="clear"></div>
</div>

</div> <!-- frontpage-bottom-left -->

</div><!-- .frontpage -->
<div class="clear"> </div>
</div><!-- #content -->

</section>

<div id="sidebar">
<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('sidebar-frontpage')) : ?><?php endif; ?>
</div><!-- #sidebar -->

<?php get_footer() ?>
