<?php
/*
Plugin Name: The Professor
Plugin URI: (forthcoming)
Description: Adds assignments, courses, and office hours to a WordPress site
Author: Joe Casabona
Author URI: http://casabona.org
Version: 0.5
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**For Widgets**/

require_once('prof-theme-options.php');
require_once('prof-office-widget.php');
require_once('prof-contact-widget.php');


/***GET CUSTOM POST TYPES***/

require_once('prof-courses.php');
require_once('prof-assignments.php');


/**Misc Functions**/


/**Accepts 1 argument: $post-ID**/
function prof_print_assn_info($pid){
?>
		<h3>Assignment Information</h3>
				<?php $assn_info = get_post_custom($pid); ?>
				<dl>
					<dt>Due Date</dt>
						<dd><?php print $assn_info['duedate'][0]; ?></dd>
						
					<dt>Course</dt>
						<dd><a href="<?php print get_permalink($assn_info['course'][0]); ?>"><?php print get_the_title($assn_info['course'][0]); ?></a></dd>
				</dl>
<?php
}



/**Accepts 1 argument: $post-ID**/
function prof_print_assn_files($pid){
	$args = array(
		'post_type' => 'attachment',
		'numberposts' => null,
		'post_status' => null,
		'post_parent' => $pid
	);
	 
	$attachments = get_posts($args);
	if ($attachments) {
		print "<ul>";
		foreach ($attachments as $attachment) {
			if(strpos(strtolower($attachment->post_mime_type), 'image') === false){
				print "<li><a href=\"". wp_get_attachment_url($attachment->ID) ."\">{$attachment->post_title}</a></li>";
			}
		}
		print "</ul>";
	}
}

?>
