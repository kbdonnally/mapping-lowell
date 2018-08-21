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

<!-- head & header: -->
<?php echo head(array(
  'title' => $title,
  'bodyclass' => 'neatline show'
)); ?>

<!-- page title: -->
<h1><?php echo $title; ?></h1>

<!-- file import test: -->
<?php echo $testPartial; ?>

<!-- fullscreen: -->
<?php echo nl_getExhibitLink(
  null, 'fullscreen', __('View Fullscreen'), array('class' => 'nl-fullscreen')
); ?>

<!-- exhibit: -->
<?php echo nl_getExhibitMarkup(); ?>

<!-- narrative: -->
<?php echo nl_getNarrativeMarkup(); ?>

<!-- footer: -->
<?php echo foot(); ?>
