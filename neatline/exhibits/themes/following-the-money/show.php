<?php

/**
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2014 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

?>

<?php
// functions
function import_file($filename, $filepath = 'exhibits/partials')
{
	return get_view()->partial($filepath . '/' . $filename);
}

// variables
$title 		 = nl_getExhibitField('title');
$exhibit 	 = import_file('exhibit.php');
$narrative 	 = import_file('narrative.php');
$js 		 = import_file('script.js', 'exhibits/themes/following-the-money/assets/js');
?>

<!-- head & header -->
<?php echo head(array(
  'title' => $title,
  'bodyclass' => 'neatline show'
)); ?>

<div class="exhibit-wrapper">
	<div class="exhibit-landing">
		<!-- page title -->
		<h1 class="exhibit-landing__title"><?php echo $title; ?></h1>

		<!-- fullscreen -->
		<?php echo nl_getExhibitLink(
		  null, 'fullscreen', __('View Fullscreen'), array('class' => 'nl-fullscreen')
		); ?>
	</div> <!-- end intro -->

	<div class="exhibit-main-content">
		<section class="exhibit__map">
			<?php echo $exhibit; ?>
		</section>

		<section class="exhibit__narrative">
			<?php echo $narrative; ?>	
		</section>
	</div> <!-- end main content -->
</div> <!-- end wrapper -->

<script type="text/javascript"><?php echo $js; ?></script>

<!-- footer: -->
<?php echo foot(); ?>