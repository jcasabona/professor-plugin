<?php

/** Create the Custom Post Type**/
add_action('init', 'prof_courses_register');  
  
 
function prof_courses_register() {  
    
    //Arguments to create post type.
    $args = array(  
        'label' => __('Courses'),  
        'singular_label' => __('Course'),  
        'public' => true,  
        'show_ui' => true,  
        'capability_type' => 'post',  
        'hierarchical' => true,  
        'has_archive' => true,
        'supports' => array('title', 'editor'),
        'rewrite' => array('slug' => 'courses', 'with_front' => false),
       );  
  
  	//Register type and custom taxonomy for type.
    register_post_type( 'courses' , $args );   
}  
 


$courses_meta_box = array(
    'id' => 'courses-meta',
    'title' => __('Course Information'),
    'page' => 'courses',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
		array(
            'name' => __('Meeting Times'),
            'desc' => __('When the class meets'),
            'id' => 'meetingtimes',
            'type' => 'textarea',
            'std' => ""
        ),	
       array(
            'name' => __('Classroom'),
            'desc' => __('Where the class meets'),
            'id' => 'classroom',
            'type' => 'text',
            'std' => ""
        ),
        
         array(
            'name' => __('Course ID'),
            'desc' => __('ID number of course'),
            'id' => 'courseid',
            'type' => 'text',
            'std' => ""
        ),
    )
);

add_action('admin_menu', 'prof_courses_meta');


// Add meta box
function prof_courses_meta() {
    global $courses_meta_box;
    
    add_meta_box($courses_meta_box['id'], $courses_meta_box['title'], 'prof_course_show_meta', $courses_meta_box['page'], $courses_meta_box['context'], $courses_meta_box['priority']);
}

// Callback function to show fields in meta box
function prof_course_show_meta() {
    global $courses_meta_box, $post;
    
    // Use nonce for verification
    echo '<input type="hidden" name="courses_meta_box_nonce2" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
    echo '<table class="form-table">';

    foreach ($courses_meta_box['fields'] as $field) {
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
        }
        echo     '<td>',
            '</tr>';
    }
    
    echo '</table>';
}

// get current post meta data

add_action('save_post', 'prof_courses_save_data');

// Save data from meta box
function prof_courses_save_data($post_id) {
    global $courses_meta_box;
    
    // verify nonce
    if (!wp_verify_nonce($_POST['courses_meta_box_nonce2'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    
    foreach ($courses_meta_box['fields'] as $field) {
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




//add_filter("manage_edit-businesses_columns", "business_manager_edit_columns");   

function business_manager_edit_columns($columns){
        $columns = array(
            "cb" => "<input type=\"checkbox\" />",
            "title" => "Business Name",
            "description" => "Description",
            "address" => "Address", 
            "phone" => "Phone",
            "email" => "Email",
            "website" => "Website",
            "cat" => "Category",
        );  

        return $columns;
}  

//add_action("manage_businesses_posts_custom_column",  "business_manager_custom_columns"); 

function business_manager_custom_columns($column){
        global $post;
        $custom = get_post_custom();
        switch ($column)
        {
                        
            case "description":
                the_excerpt();
                break;
            case "address":
            	$address= $custom["address"][0].'<br/>';
            	if($custom["address_two"][0] != "") $address.= $custom["address_two"][0].'<br/>';
            	$address.= $custom["city"][0].', '.$custom["state"][0].' '.$custom["zip"][0];
            	
            	echo $address;
            	break;
            case "phone":
            	echo $custom["phone"][0];
            	break;
            case "email":
            	echo $custom["email"][0];
            	break;
            case "website":
                echo $custom["website"][0];
                break;
            case "cat":
                echo get_the_term_list($post->ID, 'business-type', '', ', ','');
                break;
        }
}  




?>