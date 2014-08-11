<?php
/*
Plugin Name: The Professor
Plugin URI: (forthcoming)
Description: Adds assignments, courses, and office hours to a WordPress site
Author: Joe Casabona
Author URI: http://casabona.org
Version: 0.95
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**For Widgets**/

require_once('prof-theme-options.php');
require_once('prof-office-widget.php');
require_once('prof-contact-widget.php');


/***GET CUSTOM POST TYPES***/

require_once('class.Professor.php');
require_once('class.Courses.php');
require_once('class.Assignments.php');

$prof= new Professor();
$prof_courses= new Prof_Course();
$prof_assignments= new Prof_Assignment();


function prof_get_assns(){
	global $prof;
	return $prof->get_all_assignments();
}

function prof_is_assn($type){
	global $prof_assignments;
	return $prof_assignments->is_assn($type);
}


?>
