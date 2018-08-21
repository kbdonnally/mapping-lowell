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
	return get_view()->partial($filepath . '/' . $filename . '.php');
}

// variables
$title 		 = nl_getExhibitField('title');
$exhibit 	 = import_file('exhibit');
$narrative 	 = import_file('narrative');
?>

<!-- head & header -->
<?php echo head(array(
  'title' => $title,
  'bodyclass' => 'neatline show'
)); ?>

<!-- page title -->
<h1><?php echo $title; ?></h1>

<!-- fullscreen -->
<?php echo nl_getExhibitLink(
  null, 'fullscreen', __('View Fullscreen'), array('class' => 'nl-fullscreen')
); ?>

<!-- exhibit -->
<?php echo $exhibit; ?>

<!-- narrative -->
<?php echo $narrative; ?>

<!-- footer: -->
<?php echo foot(); ?>