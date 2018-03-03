<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'test')); ?>

<div id="primary">
    <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <div id="homepage-text"><p><?php echo $homepageText; ?></p></div>
    <?php endif; ?>
    
    <?php fire_plugin_hook('public_home', array('view' => $this)); ?>
</div><!-- end primary -->

<?php echo foot(); ?>
