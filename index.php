<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'test')); ?>

<div class="home-landing-wrapper">
    <div class="home-landing-grid">
        <div class="home-landing__page-title">
            <h1 class="home-title__text"><span>The<br/>Historical Atlas<br/>of Lowell</span></h1>
        </div>
        <div class="home-landing__subtitle">
        	<?php if ((get_theme_option('header_image_text') !== '')): ?>
        	    <span><?php echo get_theme_option('header_image_text'); ?></span>
        	<?php endif; ?>
        </div>
    </div>
</div>

<div class="home-main-content">
    <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    	<?php echo $homepageText; ?>
    <?php endif; ?>
    
    <?php fire_plugin_hook('public_home', array('view' => $this)); ?>
</div><!-- end main content -->

<?php echo foot(); ?>
