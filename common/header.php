<!DOCTYPE html>
<html class="<?php echo get_theme_option('Style Sheet'); ?>" lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes">
    <?php if ($description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>">
    <?php endif; ?>

    <title><?php echo option('site_title'); echo isset($title) ? ' | ' . strip_formatting($title) : ''; ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <?php fire_plugin_hook('public_head',array('view'=>$this)); ?>
    <!-- Stylesheets -->
    <?php
    queue_css_file(array('style'));
    queue_css_url('https://fonts.googleapis.com/icon?family=Raleway:200,300,400,400i,700,900|Montserrat:200,400,700|Taviraj:300,300i,400,400i,600,700,900|Playfair+Display:400,400i,700,900');
    echo head_css();
    ?>
    <!-- JavaScripts -->
    <?php queue_js_file('vendor/modernizr'); ?>
    <?php queue_js_file('vendor/selectivizr', 'javascripts', array('conditional' => '(gte IE 6)&(lte IE 8)')); ?>
    <?php queue_js_file('vendor/respond'); ?>
    <?php queue_js_file('globals'); ?>
    <?php echo head_js(); ?>
</head>
<!-- start body -->
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
<?php fire_plugin_hook('public_body', array('view'=>$this)); ?>

    <!-- navbar -->
    <header class="navbar-wrapper">
        <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>
        <div class="navbar__home-icon">
            <?php
            $iconSrc  = img("theme-files/internet.svg", "img");
            $homeLink = link_to_home_page('<img src="' . $iconSrc . '" alt="Mapping Lowell" />');
            ?>
            <?php echo $homeLink; ?>    
        </div>
        <div class="navbar__links">
            <?php echo public_nav_main(); ?>
        </div>
    </header>

    <!--header class="navbar-wrapper">
        <div class="navbar__home-icon">
            <a href="http://mappinglowell.net/" ><img src='<?php // echo img("theme-files/internet.svg", "img"); ?>' alt="Mapping Lowell" /></a>
        </div>

        <div class="navbar__links">
            <ul>
                <li><a href="http://mappinglowell.net/">Mapmakers</a></li>
                <li><a href="http://mappinglowell.net/">Comments</a></li>
                <li><a href="http://mappinglowell.net/about">Contacts</a></li>
            </ul>
        </div>
    </header-->    
  
    <div id="mobile-nav">
        <?php echo public_nav_main(); ?>
    </div>

    <!-- start main content -->                  
    <div id="main-content">

    <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>