<?php

/** Create the Custom Post Type**/
add_action('init', 'prof_assignments_register');  
  
 
function prof_assignments_register() {  
    
    //Arguments to create post type.
    $args = array(  
        'label' => __('Assignments'),  
        'singular_label' => __('Assignment'),  
        'public' => true,  
        'show_ui' => true,  
        'capability_type' => 'post',  
        'hierarchical' => true,  
        'has_archive' => true,
        'supports' => array('title', 'editor', 'comments'),
        'rewrite' => array('slug' => 'assignments', 'with_front' => false),
       );  
  
  	//Register type and custom taxonomy for type.
    register_post_type( 'assignments' , $args );   
    //register_taxonomy("course", array("businesses"), array("hierarchical" => true, "label" => "Business Types", "singular_label" => "Business Type", "rewrite" => true, "slug" => 'business-type'));
}  
 


$assignments_meta_box = array(
    'id' => 'assignments-meta',
    'title' => __('Assignment Information'),
    'page' => 'assignments',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
		array(
            'name' => __('Due Date'),
            'desc' => __('When is it due?'),
            'id' => 'duedate',
            'type' => 'text',
            'std' => ""
        ),	
       array(
			'name'	=> __('Course'),
			'desc'	=> __('Select the course.'),
			'id'	=> 'course',
			'type'	=> 'post_list',
			'std' => ""
		),
    )
);

add_action('admin_menu', 'prof_assignments_meta');


// Add meta box
function prof_assignments_meta() {
    global $assignments_meta_box;
    
    add_meta_box($assignments_meta_box['id'], $assignments_meta_box['title'], 'prof_assignments_show_meta', $assignments_meta_box['page'], $assignments_meta_box['context'], $assignments_meta_box['priority']);
}

// Callback function to show fields in meta box
function prof_assignments_show_meta() {
    global $assignments_meta_box, $post;
    
    // Use nonce for verification
    echo '<input type="hidden" name="assignments_meta_box_nonce2" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
    echo '<table class="form-table">';

    foreach ($assignments_meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        
        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
                break;
            case 'post_list':  
			$items = get_posts( array (  
				'post_type' => 'courses',  
				'posts_per_page' => -1  
			));  
				echo '<select name="', $field['id'],'" id="'.$field['id'],'"> 
						<option value="">Select One</option>'; // Select One  
					foreach($items as $item) {  
						echo '<option value="'.$item->ID.'"', $meta == $item->ID ? ' selected' : '','> '.$item->post_title.'</option>';  
					} // end foreach  
				echo '</select><br />'.$field['desc'];  
			break; 
        }
        echo     '<td>',
            '</tr>';
    }
    
    echo '</table>';
}

// get current post meta data

add_action('save_post', 'prof_assignments_save_data');

// Save data from meta box
function prof_assignments_save_data($post_id) {
    global $assignments_meta_box;
    
    // verify nonce
    if (!wp_verify_nonce($_POST['assignments_meta_box_nonce2'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    
    foreach ($assignments_meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

// check autosave
if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
 return $post_id;
}




?>