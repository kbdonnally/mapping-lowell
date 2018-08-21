<?php

/**
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2014 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

?>

<?php
/* Functions: */
function import_file($filename, $filepath = 'exhibits/partials')
{
	return get_view()->partial($filepath . '/' . $filename . '.php');
}

/* Variables: */
$title 		 = nl_getExhibitField('title');
$testPartial = import_file('test');
?>

<?php echo head(array(
  'title' => $title,
  'bodyclass' => 'neatline show'
)); ?>

<!-- Exhibit title: -->
<h1><?php echo $title; ?></h1>

<!-- test partial theory: -->
<?php echo $testPartial; ?>

<!-- "View Fullscreen" link: -->
<?php echo nl_getExhibitLink(
  null, 'fullscreen', __('View Fullscreen'), array('class' => 'nl-fullscreen')
); ?>

<!-- Exhibit and description: -->

<!-- lowell/plugins/Neatline/views/shared/exhibits/partials/[exhibit|narrative].php -->
<?php echo nl_getExhibitMarkup(); ?>
<?php echo nl_getNarrativeMarkup(); ?>

<!-- Footer: 
	 lowell/themes/maplowell/common/footer.php -->
<?php echo foot(); ?>
