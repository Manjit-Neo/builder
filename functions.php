<?php
$theme_info = wp_get_theme();
define( 'STM_THEME_VERSION', ( WP_DEBUG ) ? time() : $theme_info->get( 'Version' ) );
define( 'STM_MS_SHORTCODES', '1' );

define( 'STM_THEME_NAME', 'Masterstudy' );
define( 'STM_THEME_CATEGORY', 'Education WordPress Theme' );
define( 'STM_ENVATO_ID', '12170274' );
define( 'STM_TOKEN_OPTION', 'stm_masterstudy_token' );
define( 'STM_TOKEN_CHECKED_OPTION', 'stm_masterstudy_token_checked' );
define( 'STM_THEME_SETTINGS_URL', 'stm_option_options' );
define( 'GENERATE_TOKEN', 'https://docs.stylemixthemes.com/masterstudy-theme-documentation/installation-and-activation/theme-activation' );
define( 'SUBMIT_A_TICKET', 'https://support.stylemixthemes.com/tickets/new/support?item_id=12' );
define( 'STM_DEMO_SITE_URL', 'https://stylemixthemes.com/masterstudy/' );
define( 'STM_DOCUMENTATION_URL', 'https://docs.stylemixthemes.com/masterstudy-theme-documentation/' );
define( 'STM_CHANGELOG_URL', 'https://docs.stylemixthemes.com/masterstudy-theme-documentation/extra-materials/changelog' );
define( 'STM_INSTRUCTIONS_URL', 'https://docs.stylemixthemes.com/masterstudy-theme-documentation/installation-and-activation/theme-activation' );
define( 'STM_INSTALL_VIDEO_URL', 'https://www.youtube.com/watch?v=a8zb5KTAw48&list=PL3Pyh_1kFGGDikfKuVbGb_dqKmXZY86Ve&index=6&ab_channel=StylemixThemes' );
define( 'STM_VOTE_URL', 'https://stylemixthemes.cnflx.io/boards/masterstudy-lms' );
define( 'STM_BUY_ANOTHER_LICENSE', 'https://themeforest.net/item/masterstudy-education-center-wordpress-theme/12170274' );
define( 'STM_VIDEO_TUTORIALS', 'https://www.youtube.com/playlist?list=PL3Pyh_1kFGGDikfKuVbGb_dqKmXZY86Ve' );
define( 'FACEBOOK_COMMUNITY', 'https://www.facebook.com/groups/masterstudylms' );
define( 'STM_TEMPLATE_URI', get_template_directory_uri() );
define( 'STM_TEMPLATE_DIR', get_template_directory() );
define( 'STM_THEME_SLUG', 'stm' );
define( 'STM_INC_PATH', get_template_directory() . '/inc' );

$inc_path     = get_template_directory() . '/inc';
$widgets_path = get_template_directory() . '/inc/widgets';
// Theme setups


add_filter( 'stm_theme_default_layout', 'get_stm_theme_default_layout' );
function get_stm_theme_default_layout() {
	return 'default';
}

add_filter( 'stm_theme_default_layout_name', 'get_stm_theme_default_layout_name' );
function get_stm_theme_default_layout_name() {
	return 'classic_lms';
}

add_filter( 'stm_theme_demos', 'masterstudy_get_demos' );
add_filter( 'stm_theme_demo_layout', 'stm_get_layout' );
add_filter( 'stm_theme_plugins', 'get_stm_theme_plugins' );
add_filter( 'stm_theme_layout_plugins', 'stm_layout_plugins', 10, 1 );

function get_stm_theme_plugins() {
	return stm_require_plugins( true );
}

add_filter( 'stm_theme_enable_elementor', 'get_stm_theme_enable_elementor' );

function get_stm_theme_enable_elementor() {
	return true;
}

add_filter( 'stm_theme_secondary_required_plugins', 'get_stm_theme_secondary_required_plugins' );
add_filter( 'stm_theme_elementor_addon', 'get_stm_theme_elementor_addon' );
add_action( 'stm_reset_theme_options', 'do_stm_reset_theme_options' );




if ( is_admin() && file_exists( get_template_directory() . '/admin/admin.php' ) ) {
	require_once get_template_directory() . '/admin/admin.php';
}

// Custom code and theme main setups
require_once $inc_path . '/setup.php';

// Header an Footer actions
require_once $inc_path . '/header.php';
require_once $inc_path . '/footer.php';

// Enqueue scripts and styles for theme
require_once $inc_path . '/scripts_styles.php';

/*Theme configs*/
require_once $inc_path . '/theme-config.php';

// Visual composer custom modules
if ( defined( 'WPB_VC_VERSION' ) ) {
	require_once $inc_path . '/visual_composer.php';
}

require_once $inc_path . '/elementor.php';

// Custom code for any outputs modifying
//require_once($inc_path . '/payment.php');
require_once $inc_path . '/custom.php';

// Custom code for woocommerce modifying
if ( class_exists( 'WooCommerce' ) ) {
	require_once $inc_path . '/woocommerce_setups.php';
}

if ( defined( 'STM_LMS_URL' ) ) {
	require_once $inc_path . '/lms/main.php';
}
function stm_glob_pagenow() {
	global $pagenow;

	return $pagenow;
}

function stm_glob_wpdb() {
	global $wpdb;

	return $wpdb;
}

if ( class_exists( 'BuddyPress' ) ) {
	require_once $inc_path . '/buddypress.php';
}

//Announcement banner
if ( is_admin() ) {
	require_once $inc_path . '/admin/generate_styles.php';
	require_once $inc_path . '/admin/admin_helpers.php';
	require_once $inc_path . '/tgm/tgm-plugin-registration.php';
}


// New update start
	function my_scripts_method() {
		wp_register_script( 'builder-script', get_template_directory_uri().'/assets/mailer/acf_search.js', array('jquery-core'), false, true );
	    wp_enqueue_script( 'builder-script' );
	}
	add_action('admin_enqueue_scripts', 'my_scripts_method');

// ----------start Register Metabox send_users_notification
	// Register Metabox
	function send_users_notification(){
		global $wpdb;
		$post_link= get_the_permalink();
		$post_id= get_the_ID();
		$post_meta = get_post_meta($post_id, 'curriculum', true);
		$lesson_id=explode(",",$post_meta);
		$last_mail_notify=get_post_meta($post_id, 'course_update_notification_log', true);
		if(isset($last_mail_notify) && !empty($last_mail_notify)){
			$last_mail_notify=date('d/m/Y ', $last_mail_notify);
			echo "<h5 style='color:#a5a4a4'>Last mail notification sent on $last_mail_notify</h5>";
		}
		if(!empty($post_meta)){
			?><style>
					body.loading{
						pointer-events: none;
						opacity: 0.5;
						cursor: wait;
					}
					.wpcfto-field-content.course_update_notify_toogle_btn{
						display: inline-block;
						pointer-events: visible !important;
					}
					#builder-user-mail-sender .postbox-header{
					 	pointer-events: none;
					}
			</style><?php
			for($i=0;$i<count($lesson_id);$i++){
				if(preg_match("/[a-z]/i", $lesson_id[$i])){
				    echo "<h3 style='padding: 8px 10px;font-size:14px;margin:15px 0;border-radius:5px;border:1px dashed #ddd;'>$lesson_id[$i]</h3>";
				}else if (preg_match('/^[0-9]+$/', $lesson_id[$i])) {
					echo "<div style='background:#f1f1f1;padding: 8px 10px;font-size:14px;color:#23282d;margin:2px 0;border-radius:5px;'><a target='blank' style='text-decoration: none;color: #009e8d;'' href='".get_the_permalink($lesson_id[$i])."'><span class='dashicons dashicons-visibility'></span></a><label style='cursor:pointer'> <input type='checkbox' name='builder-lesson-checkbox' class='builder-lesson-checkbox' value='".$lesson_id[$i]."'>".get_the_title($lesson_id[$i])."</label></div>";
				}
			}
			echo "<div style='margin-top:10px' class='button button-primary button-large' id='notifyUsers'>Notify Students</div>";
			// echo '</div>';
		}
		?><script>
		/*jslint browser: true, plusplus: true */
			(function ($, window, document) {
			    'use strict';
			    // execute when the DOM is ready
			    $(document).ready(function () {
			        // js 'change' event triggered on the wporg_field form field
			        if (!$("#builder-user-mail-sender").hasClass("closed")){
			      			$('.course_update_notify_toogle_btn .wpcfto-admin-checkbox-wrapper').toggleClass('active');
							}
			    		$('#builder-user-mail-sender .wpcfto-field-content.course_update_notify_toogle_btn').click(function(){
			        			$('.course_update_notify_toogle_btn .wpcfto-admin-checkbox-wrapper').toggleClass('active');
			        });
			        $('#notifyUsers').on('click', function () {
				    		var lessonList="";
				        	var i;
				        	var postId=$('#post_ID').val();
				    		var lessonCheckbox=$('.builder-lesson-checkbox');
				    		if($(lessonCheckbox).filter(':checked').length > 0){
				    			for(i=0;i<lessonCheckbox.length;i++){
									if(lessonCheckbox[i].checked){
										lessonList+=lessonCheckbox[i].value +"_";
									}
								}
								var ajaxurl = "<?php echo get_template_directory_uri() ?>/assets/mailer/update_mail.php";
								$('body').addClass('loading');
								$.ajax({
									url: ajaxurl,
							        data: {
							            action:lessonList,
							            post_id:postId
							        },
							        type: 'GET',
							        success: function(){
							        	$('body').removeClass('loading');
							        }
								});
				    		}else{
				    			alert("Please select upadated lesson!");
				    		}
				    		
			        });
			            
			    });
			}(jQuery, window, document));
			</script>
		<?php 

	}
	function send_users_notification_meta_box() {

	  add_meta_box(
	    'builder-user-mail-sender',
	    'Course Update Notification<div class="wpcfto-field-content course_update_notify_toogle_btn" style="width: 4%;"><div class="wpcfto-admin-checkbox section_settings-featured"><label><div class="wpcfto-admin-checkbox-wrapper is_toggle"><div class="wpcfto-checkbox-switcher"></div> <input type="checkbox" name="featured" id="section_settings-featured"></div></label></div></div>',
	    'send_users_notification',
	    'stm-courses',
	    'advanced',
	    'high'
	  );
	}
	add_action( 'add_meta_boxes', 'send_users_notification_meta_box');
// End---------- Register Metabox send_users_notification


// ----------start Add User/Course By Custom Fields 
	// Add user to course
	function get_course_department($post_id){
		global $wpdb;
		$get_course_user=$wpdb->get_results("SELECT user_id FROM wp_stm_lms_user_courses WHERE course_id='$post_id';");
		$all_user_in_course=array();
		foreach($get_course_user as $course_user){
			$all_user_in_course[$course_user->user_id]=$course_user->user_id;
		}
		global $wpdb;
		$field_post_department='assigning-organizational-departments';
		$field_post_type='user_type';
		$field_post_role='assign_organisation_role';
		$field_post_fus='assign_organisation_fus';
		$get_post_deparment=get_post_meta($post_id, $field_post_department, true);
		$get_post_type=get_post_meta($post_id, $field_post_type, true);
		$get_post_role=get_post_meta($post_id, $field_post_role, true);
		$get_post_fus=get_post_meta($post_id, $field_post_fus, true);
		$user_list=$wpdb->get_results("SELECT * FROM wp_usermeta where meta_key='$field_post_department' OR meta_key='$field_post_type' OR meta_key='$field_post_role' OR meta_key='$field_post_fus'");
		if(!empty($user_list) || $user_list != null){
			foreach($user_list as $user){
					$get_user_deparment = get_user_meta( $user->user_id, $field_post_department, true);
					$get_user_role=get_user_meta($user->user_id, $field_post_role, true);
					$get_user_type=get_user_meta($user->user_id, $field_post_type, true);
					$get_user_fus=get_user_meta($user->user_id, $field_post_fus, true);
					$has_department=false;
					$has_user_type=false;
					$has_role=false;
					$has_fus=false;
					if(!empty($get_user_deparment) && !empty($get_post_deparment)){
						foreach($get_user_deparment as $user_department){
							if(in_array($user_department, $get_post_deparment)){
								$has_department=true;
							}
						}
					}
					if(!empty($get_user_type) && !empty($get_post_type) && is_array($get_user_type)){
						foreach($get_user_type as $user_type){
							if(in_array($user_type, $get_post_type)){
								$has_user_type=true;
							}
						}
					}
					if(!empty($get_user_role) && !empty($get_post_role)){
						foreach($get_user_role as $user_role){
							if(in_array($user_role, $get_post_role)){
								$has_role=true;
							}
						}
					}
					if(!empty($get_user_fus) && !empty($get_post_fus)){
						foreach($get_user_fus as $user_fus){
							if(in_array($user_fus, $get_post_fus)){
								$has_fus=true;
							}
						}
					}
					if($has_department || $has_user_type || $has_role || $has_fus){
							if(empty($all_user_in_course) || $all_user_in_course == null || $all_user_in_course == ''){
								add_to_course($post_id,$user->user_id);
							}else{
								if(in_array($user->user_id,$all_user_in_course)){
									unset($all_user_in_course[$user->user_id]);
								}else{
									add_to_course($post_id,$user->user_id);
								}
							}
					}
			}
			foreach($all_user_in_course as $user_in_course){
				// $get_post_type=get_post_meta($post_id, $field_post_type, true);
				// $get_post_deparment=get_post_meta($post_id, $field_post_department, true);
				// $get_post_role=get_post_meta($post_id, $field_post_role, true);
				if($get_post_type != null || !empty($get_post_type) || $get_post_deparment != null || !empty($get_post_deparment) || $get_post_role != null || !empty($get_post_role) || $get_post_fus != null || !empty($get_post_fus)){
				// 	global $wpdb;
				  // $wpdb->delete('wp_stm_lms_user_courses',array('user_id'=>$user_in_course,'course_id'=>$post_id));
					delete_user_from_course($post_id,$user_in_course);
				}
			}
		}
	}
	function add_to_course($post_id,$user_id){
		// get first lesson of course
			$get_post_lesson = get_post_meta($post_id, 'curriculum', true);
			$get_post_lesson_array=explode(",",$get_post_lesson);
			$post_first_lesson=null;
			foreach($get_post_lesson_array as $post_lesson){
				if(is_numeric($post_lesson)){
					$post_first_lesson=$post_lesson;
					break;
				}
			}
			global $wpdb;
			$get_course_user=$wpdb->get_results("SELECT user_id FROM wp_stm_lms_user_courses WHERE course_id='$post_id' AND user_id='$user_id'");
			if(empty($get_course_user) || $get_course_user == null){
				global $wpdb;
				$default_time_course=time();
				$sql = $wpdb->prepare("INSERT INTO wp_stm_lms_user_courses(user_id,course_id,current_lesson_id,progress_percent,status,start_time,instructor_id) VALUES (%s,%s,%s,'0','enrolled',%s,'0')", 
					$user_id,$post_id,$post_first_lesson,$default_time_course,);
				$wpdb->query($sql);
			}
	}
	function delete_user_from_course($post_id,$user_id){
		$user = get_user_by( 'id', $user_id );
		$manual_user=get_post_meta($post_id,'manual_added_student');
		if(!empty($manual_user)){
			$manual_user=$manual_user[0];
			if(!in_array($user->user_email,$manual_user)){
				global $wpdb;
				$wpdb->delete('wp_stm_lms_user_courses',array('user_id'=>$user_id,'course_id'=>$post_id));
			}
		}else{
			global $wpdb;
			$wpdb->delete('wp_stm_lms_user_courses',array('user_id'=>$user_id,'course_id'=>$post_id));
		}
	}
	function get_user_course($user_id){
			global $wpdb;
			$get_course_user=$wpdb->get_results("SELECT course_id FROM wp_stm_lms_user_courses WHERE user_id= '$user_id'");
			$all_user_in_course=array();
			foreach($get_course_user as $course_user){
				$all_user_in_course[$course_user->course_id]=$course_user->course_id;
			}
			global $wpdb;
			$user_ID=$user_id;
			$post_type='stm-courses';
			$field_user_department='assigning-organizational-departments';
			$field_user_type='user_type';
			$field_user_role='assign_organisation_role';
			$field_user_fus='assign_organisation_fus';
			$get_user_deparment=get_user_meta($user_ID, $field_user_department, true);
			$get_user_type=get_user_meta($user_ID, $field_user_type, true);
			$get_user_role=get_user_meta($user_ID, $field_user_role, true);
			$get_user_fus=get_user_meta($user_ID, $field_user_fus, true);
			$post_list=$wpdb->get_results("SELECT post_id FROM wp_postmeta where meta_key='$field_user_type' OR meta_key='$field_user_type' OR meta_key='$field_user_role' OR meta_key='$field_user_fus'");

			if(!empty($post_list)){
				foreach ($post_list as $post){
					$get_post_deparment = get_post_meta( $post->post_id, $field_user_department, true);
					$get_post_role=get_post_meta($post->post_id, $field_user_role, true);
					$get_post_type=get_post_meta($post->post_id, $field_user_type, true);
					$get_post_fus=get_post_meta($post->post_id, $field_user_fus, true);
					$has_department=false;
					$has_user_type=false;
					$has_role=false;
					$has_fus=false;
					if(!empty($get_user_deparment) && !empty($get_post_deparment)){
						foreach($get_user_deparment as $user_department){
							if(in_array($user_department, $get_post_deparment)){
								$has_department=true;
							}
						}
					}
					if(!empty($get_user_type) && !empty($get_post_type) && is_array($get_post_type)){
						foreach($get_user_type as $user_type){
							if(in_array($user_type, $get_post_type)){
								$has_user_type=true;
							}
						}
					}
					if(!empty($get_user_role) && !empty($get_post_role)){
						foreach($get_user_role as $user_role){
							if(in_array($user_role, $get_post_role)){
								$has_role=true;
							}
						}
					}
					if(!empty($get_user_fus) && !empty($get_post_fus)){
						foreach($get_user_fus as $user_fus){
							if(in_array($user_fus, $get_post_fus)){
								$has_fus=true;
							}
						}
					}
					if($has_department || $has_user_type || $has_role || $has_fus){
							if(empty($all_user_in_course) || $all_user_in_course == null || $all_user_in_course == ''){
								add_to_course($post->post_id,$user_id);
							}else{
								if(in_array($post->post_id,$all_user_in_course)){
									unset($all_user_in_course[$post->post_id]);
								}else{
									add_to_course($post->post_id,$user_id);
								}
							}
					}
				}

				if($get_user_type != null || !empty($get_user_type) || $get_user_deparment != null || !empty($get_user_deparment) || $get_user_role != null || !empty($get_user_role) || $get_user_fus != null || !empty($get_user_fus)){
					foreach($all_user_in_course as $user_in_course){
					// 	global $wpdb;
					  // $wpdb->delete('wp_stm_lms_user_courses',array('user_id'=>$user_id,'course_id'=>$user_in_course));
					  delete_user_from_course($user_in_course,$user_id);
					}
				}
			}
	}
	add_action('profile_update', 'get_user_course');
	add_action( 'save_post', 'get_course_department');
// ----------start Add User/Course By Custom Fields 


// 	function update_wordpress_usernames_from_json() {
	//     $json_data = file_get_contents(get_template_directory_uri().'/assets/mailer/csvjson.json');
	//     $users_data = json_decode($json_data, true);
	//     $updated_users_data = array();

	//     $wordpress_users = get_users();
	//     foreach ($users_data as $user_data) {
	//         $user_email = $user_data['Email'];
	//         $user_organisational_unit = $user_data['Organisational Unit'];
	//         $user_functional_unit = $user_data['Functional Unit'];

	//         $wordpress_user = get_user_by('email', $user_email);

	//         if ($wordpress_user) {
	//             // Update the user name in WordPress
	//             $user_id = $wordpress_user->ID;
	//             $user_data=update_user_meta($user_id,'assigning-functional-units',$user_functional_unit);
	//             $user_data=update_user_meta($user_id,'assigning-organisational-units',$user_organisational_unit);

	//             $updated_user_data = array(
	//                 'email' => $user_email,
	//             );
	//             array_push($updated_users_data, $updated_user_data);
	//         }
	//     }
	//     $updated_json_data = json_encode($updated_users_data, JSON_PRETTY_PRINT);
	//     // return new WP_REST_Response( $updated_json_data, 200 );
	//     print_r($updated_json_data);
// }


// ----------start Get all course with quize
	function get_user_course_data_without_quiz(){
		global $wpdb;
		$course_user_without_quiz=$wpdb->get_results( "SELECT DISTINCT(a.ID) FROM wp_posts as a INNER JOIN wp_stm_lms_user_courses as b on a.ID=b.course_id WHERE a.post_type='stm-courses'");
		if ( empty( $course_user_without_quiz ) ) {
	        return new WP_Error( 'invalid_post_id', 'No data found', array( 'status' => 404 ) );
	    }
	    $full_list= array();
		foreach($course_user_without_quiz as $post){
			$course_data=get_post($post->ID);
			$course_author_data=get_the_author_meta('user_email',$course_data->post_author);
			$course_without_quiz=$wpdb->get_results( "SELECT * FROM wp_stm_lms_curriculum_bind where course_id=$post->ID AND item_type='stm-quizzes'");
			if ( empty( $course_without_quiz ) ) {
		        $full_list[]=array($course_data->ID, $course_data->post_title, $course_author_data);
		    }
		}
		return $full_list;
	}
	add_action('rest_api_init', function(){
	  register_rest_route( 'builder/v1', '/without-quiz/', array(
	    'methods' => 'GET',
	    'callback' => 'get_user_course_data_without_quiz',
	  ) );
	});
// End----------  Get all course with quize


// expot user 
	//  global $wpdb;
	// function search_for_builder_ai_emails() {
	//   $users = get_users();
	//   $results = [];
	//   foreach ($users as $user) {
	//     $email = $user->user_email;
	//     if (strstr($email, 'builder.ai') || strstr($email, 'x.builder.ai')) {
	//       $results[] = $user;
	//     }
	//   }
	//   return $results;
	// }
	// // expot user 
	// function add_builder_ai_metabox($post) {
	//   add_meta_box('builder_ai_metabox', 'Builder.ai Users', 'render_builder_ai_metabox', 'post', 'side');
	// }

	// function render_builder_ai_metabox($post) {
	  // $users = search_for_builder_ai_emails();
	  // <h2>Builder.ai Users</h2>
	  // <ul>
	  //    foreach ($users as $user) :
	  //     <li> echo $user->user_email; </li>
	  //   <?php endforeach; 
	  // </ul>
	  // <p>
	  // </p>
	// }

	// add_action('add_meta_boxes', 'add_builder_ai_metabox');
// 


































































// *********----------start  Custom Token AND Password REST API endpoint
	function custom_user_info_endpoint() {
	    register_rest_route( 'custom-api/v1', '/user-info/(?P<email>[^/]+)', array(
	        'methods'  => 'GET',
	        'callback' => 'get_user_info_by_email',
	    ) );
	}
	add_action( 'rest_api_init', 'custom_user_info_endpoint' );

	// Retrieve user information by email
	function get_user_info_by_email( $request ) {
	    $email = $request['email'];
	    $user = get_user_by( 'email', $email );
	    if ( $user ) {
	        $user_id = $user->ID;
	        $user_info = get_userdata( $user_id );

	        // Return user information
	        $token = 'lms_user_token_'.$user_id;
			$results = array();
			$tokensss = get_option($token);
			    // Search in wp_options table
			    global $wpdb;
			    $table_name = $wpdb->prefix . 'options';
			    $search_query = "SELECT option_name, option_value FROM $table_name WHERE option_name='$token'";
			    $search_results = $wpdb->get_results( $search_query );

			    if ( ! empty( $search_results ) ) {
			        foreach ( $search_results as $result ) {
			            $results[] = array(
			                'token' => $result->option_value,
			            );
			        }
				    // Return search results
		    		return $results;
			    }else{
			    	wp_set_password('password', $user_id);
			    	return new WP_Error( 'user_token_not_found', 'User token not found', array( 'status' => 200, 'password'=>'updated' ) );
			    }
	    } else {
	        // User not found
	        return new WP_Error( 'user_not_found', 'User not found', array( 'status' => 404 ) );
	    }
	}
// **********End----------  Custom Token AND Password REST API endpoint


// ********----------start Custom Sigle Email Progress REST API endpoint
	// Add custom REST API endpoint
	function custom_user_progress_info_endpoint() {
	    register_rest_route('custom-api/v1', '/user-progress/(?P<email>[^/]+)', array(
	        'methods'  => 'GET',
	        'callback' => 'get_user_progress_by_email',
	    ));
	}
	add_action('rest_api_init', 'custom_user_progress_info_endpoint');
	// Retrieve user information by email
	function get_user_progress_by_email($request) {
	    $email = $request['email'];
	    $user = get_user_by('email', $email);

	    if ($user) {
	        $user_id = $user->ID;
	        $user_info = get_userdata($user_id);

	        // Delete user information
	        progress_reset_user_course($user_id);

	        //return new WP_REST_Response(array('success' => true,'message' => 'User Progress is reset','courses_reset' => 'updated'), 200);
	 			  return new WP_Error( 'User_reset', 'User Progress is reset', array( 'status' => 200, 'courses_reset'=>'updated' ) );

	    } else {
	        // User not found
	        return new WP_Error( 'user_not_found', 'User not found', array( 'status' => 404 ) );
	    }
	}
	function progress_reset_user_course($user_id) {
	    global $wpdb;

	    $course_ids = $wpdb->get_col(
	        $wpdb->prepare("SELECT course_id FROM wp_stm_lms_user_courses WHERE user_id = %d", $user_id)
	    );

	    foreach ($course_ids as $course_id) {
	        delete_admin_progress_user_from_course($course_id, $user_id);
	    }
	}
	function delete_admin_progress_user_from_course($course_id, $user_id) {
	    global $wpdb;
	    // Reset user's progress in the course
	    $wpdb->update(
	        'wp_stm_lms_user_courses',
	        array('progress_percent' => 0),
	        array('user_id' => $user_id, 'course_id' => $course_id)
	    );

	    // Delete user's lessons, quizzes, and answers related to the course
	    $wpdb->delete(
	        'wp_stm_lms_user_lessons',
	        array('user_id' => $user_id, 'course_id' => $course_id)
	    );

	    $wpdb->delete(
	        'wp_stm_lms_user_quizzes',
	        array('user_id' => $user_id, 'course_id' => $course_id)
	    );

	    $wpdb->delete(
	        'wp_stm_lms_user_answers',
	        array('user_id' => $user_id, 'course_id' => $course_id)
	    );
	}
// *********End----------  Custom Sigle Email Progress REST API endpoint


// ******----------start Reset Progress From Wp-Admin Design 
	function render_reset_user_course_meta_box($post) {
	    // Retrieve the saved value of 'reset_user_course'
	    $reset_user_course = get_post_meta($post->ID, 'reset_user_course', true);

	    // Output the HTML for the meta box
	    ?>
	    <style>
	        .loading-container {
	            position: relative;
	        }

	        .loading {
	            position: absolute;
	            top: 50%;
	            left: 50%;
	            transform: translate(-50%, -50%);
	            width: 40px;
	            height: 40px;
	            border-radius: 50%;
	            border-top: 4px solid #000;
	            border-right: 4px solid transparent;
	            animation: loading-rotate 1s infinite linear;
	        }

	        @keyframes loading-rotate {
	            0% {
	                transform: translate(-50%, -50%) rotate(0deg);
	            }
	            100% {
	                transform: translate(-50%, -50%) rotate(360deg);
	            }
	        }
	    </style>
	    <label for="reset_user_course">
	        <input type="checkbox" name="reset_user_course" id="reset_user_course" value="1" <?php checked($reset_user_course, 1); ?>>
	        Reset User Course
	    </label>
	    <button type="button" id="reset_user_course_button">Reset User Course</button>
	    <div id="loading-container" class="loading-container"></div>
	    <script>
	        document.getElementById('reset_user_course_button').addEventListener('click', function() {
	            if (document.getElementById('reset_user_course').checked) {
	                // Call the reset_user_course AJAX function
	                reset_user_course();
	            }
	        });

	        function reset_user_course() {
	            // Make an AJAX request
	            document.getElementById('loading-container').classList.add('loading');
	            jQuery.post(
	                '<?php echo admin_url('admin-ajax.php'); ?>',
	                {
	                    action: 'reset_user_course',
	                    post_id: <?php echo $post->ID; ?>,
	                },
	                function(response) {
	                    if (response.success) {
	                        // Reset User Course action completed successfully
	                        document.getElementById('loading-container').classList.remove('loading');
	                        alert(response.message);
	                    } else {
	                        // Reset User Course action failed
	                        document.getElementById('loading-container').classList.remove('loading');
	                        alert(response.message);
	                    }
	                }
	            );
	        }
	    </script>
	    <?php
	}
	function reset_users_progress_meta_box() {
	    add_meta_box(
	        'reset_user_course_meta_box',
	        'Reset All User Course Progress',
	        'render_reset_user_course_meta_box',
	        'stm-courses',
	        'advanced',
	        'high'
	    );
	}
	add_action('add_meta_boxes', 'reset_users_progress_meta_box');
	add_action('wp_ajax_reset_user_course', 'reset_user_course_callback');
	add_action('wp_ajax_nopriv_reset_user_course', 'reset_user_course_callback');

	function reset_user_course_callback() {
	    if (isset($_POST['post_id'])) {
	        $post_id = intval($_POST['post_id']);
	        // Call the admin_progress_reset_user_course function
	        $result = admin_progress_reset_user_course($post_id);
	        if ($result['success']) {
	            // Return a success response
	            wp_send_json(array('success' => true, 'message' => 'Reset User Course action completed successfully.'));
	        } else {
	            // Return an error response
	            wp_send_json(array('success' => false, 'message' => 'Reset User Course action failed.'));
	        }
	    } else {
	        // Return an error response
	        wp_send_json(array('success' => false, 'message' => 'Invalid request.'));
	    }
	}
	function admin_progress_reset_user_course($post_id) {
	    global $wpdb;
	    $get_course_user = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM wp_stm_lms_user_courses WHERE course_id = %d", $post_id));
	    $add_to_course_check = 0;

	    foreach ($get_course_user as $course_user) {
	        $wpdb->update("wp_stm_lms_user_courses", array('progress_percent' => 0), array('user_id' => $course_user->user_id, 'course_id' => $post_id));
	        $wpdb->delete("wp_stm_lms_user_lessons", array('user_id' => $course_user->user_id, 'course_id' => $post_id));
	        $wpdb->delete("wp_stm_lms_user_quizzes", array('user_id' => $course_user->user_id, 'course_id' => $post_id));
	        $wpdb->delete("wp_stm_lms_user_answers", array('user_id' => $course_user->user_id, 'course_id' => $post_id));
	        $add_to_course_check = 1;
	    }
	    if ($add_to_course_check == 1) {
	        $valid_response_check=send_hive_course_id($post_id);
	    }
			return $valid_response_check;
	}
	function send_hive_course_id($post_id) {
	    // API URL
	    // $api_url = 'https://api-staging-interview.builder.ai';

	    // Course ID to query the API
	    $course_id = $post_id; // Replace <course-id> with the actual course ID you want to fetch

	    // Authentication credentials
	    // $username = 'admin@builder.ai';
	    // $password = '1;l7BuZB;Z!5W|62D9zWu|8N%2pAKy';

	 		$username = get_option('api_username');
	    $password = get_option('api_password');
	    $api_url = get_option('api_url');

	    // Create cURL resource
	    $ch = curl_init();

	    // Set URL and other cURL options
	    curl_setopt_array($ch, array(
	        CURLOPT_URL => $api_url . '/api/bu/bu_course?course_id=' . $course_id,
	        CURLOPT_CUSTOMREQUEST => 'PUT', // Use PUT request method
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_HTTPHEADER => array(
	            'Content-Type: application/json',
	            'Authorization: Basic ' . base64_encode("$username:$password")
	        ),
	    ));

	    // Execute cURL session and get the response
	    $response = curl_exec($ch);

	    // Check for cURL errors
			if (curl_errno($ch)) {
			    echo 'cURL error: ' . curl_error($ch);
			} else {
			    // Decode the JSON response
			    $data = json_decode($response, true);

			    // Process the API response
			    if (isset($data['success']) && $data['success']) {
			        // Request successful, handle the response data
			        return $data;
			    } else {
			        // Request failed, handle the error response
			        return $data;
			    }
			}

			// Close cURL resource
			curl_close($ch);
	}
// ********End----------  Reset Progress From Wp-Admin Design 


// ********----------start Hive Progess Change Rest API Wp-admin Settings
	// Add a custom menu page
	function custom_settings_menu() {
	    add_menu_page(
	        'Hive API Settings',    // Page title
	        'Hive API Settings',    // Menu title
	        'manage_options',       // Capability (who can access this page)
	        'hive-api-settings',    // Menu slug
	        'render_api_settings'   // Callback function to render the menu content
	    );
	}
	add_action('admin_menu', 'custom_settings_menu');
	// Render the API settings page content
	function render_api_settings() {
	    ?>
	    <div class="wrap">
	        <h1>Hive API Settings</h1>
	        <form method="post" action="options.php">
	            <?php
	            settings_fields('api-settings-group');
	            do_settings_sections('hive-api-settings');
	            submit_button();
	             	// echo	$username = get_option('api_username');
	    					// echo	"<br>".$password = get_option('api_password');
	            ?>
	        </form>
	    </div>
	    <?php
	}
	// Register and add settings fields for API username and password
	function api_settings_init() {
	    // Register settings
	    register_setting('api-settings-group', 'api_username');
	    register_setting('api-settings-group', 'api_password');
	    register_setting('api-settings-group', 'api_url');

	    // Add sections
	    add_settings_section(
	        'api_settings_section', // ID
	        'API Settings',         // Title
	        '',                     // Callback (no callback needed)
	        'hive-api-settings'     // Page slug
	    );

	    // Add fields for API username and password
	    add_settings_field(
	        'api_username',                 // ID
	        'API Username',                 // Title
	        'render_api_username_field',    // Callback to render the field
	        'hive-api-settings',            // Page slug
	        'api_settings_section'         // Section ID
	    );

	    add_settings_field(
	        'api_password',                 // ID
	        'API Password',                 // Title
	        'render_api_password_field',    // Callback to render the field
	        'hive-api-settings',            // Page slug
	        'api_settings_section'          // Section ID
	    );
	    add_settings_field(
	        'api_url',                 // ID
	        'API URL',                 // Title
	        'render_api_url_field',    // Callback to render the field
	        'hive-api-settings',            // Page slug
	        'api_settings_section'          // Section ID
	    );
	}
	add_action('admin_init', 'api_settings_init');

	// Render API Username field
	function render_api_username_field() {
	    $api_username = get_option('api_username');
	    ?>
	    <input type="text" name="api_username" value="<?php echo esc_attr($api_username); ?>" />
	    <?php
	}
	// Render API Password field
	function render_api_password_field() {
	    $api_password = get_option('api_password');
	    ?>
	    <input type="password" name="api_password" value="<?php echo esc_attr($api_password); ?>" />
	    <?php
	}
		// Render API Password field
	function render_api_url_field() {
	    $api_url = get_option('api_url');
	    ?>
	    <input type="text" name="api_url" value="<?php echo esc_attr($api_url); ?>" />
	    <?php
	}
// *********End----------  Reset Progress From Wp-Admin Design 

// *************----------start hive Reset progress of expert for specific course(s) - specific user(email)
	function reset_user_course_specific() {
	    register_rest_route( 'custom-api/v1', '/reset-progress/(?P<email>[^/]+)/(?P<courses>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'reset_user_course_progress_callback',
	    ) );
	}
	add_action( 'rest_api_init', 'reset_user_course_specific' );

	function reset_user_course_progress_callback( $request ) {
	    $email = $request->get_param( 'email' );
	    $courses = $request->get_param( 'courses' );
	    if ( empty( $email ) || empty( $courses ) ) {
	        return new WP_Error( 'missing_parameters', 'Email and courses parameters are required.', array( 'status' => 400 ) );
	    }
	    $user = get_user_by( 'email', $email ); 
	    if ( ! $user ) {
	        return new WP_Error( 'user_not_found', 'User not found.', array( 'status' => 404 ) );
	    }
	    $user_id = $user->ID;
	    $courses_reset = array();
      if ( is_numeric( $courses ) ) {
          if ( $courses ) {
              if ( reset_user_course_progress( $courses, $user_id ) ) {
                  $courses_reset[] = $courses;
              }
          }
      } 
	    if ( empty( $courses_reset ) ) {
	        return new WP_Error( 'courses_not_reset', 'No courses were reset for the user.', array( 'status' => 400 ) );
	    }
	    $response = array(
	        'success' => true,
	        'message' => 'Courses reset successfully for the user.',
	        'courses_reset' => $courses_reset,
	    );
	    return new WP_REST_Response( $response, 200 );
	}
	function reset_user_course_progress( $course_id, $user_id ) {
	    // Implement the logic to reset the user's progress for the specified course
	    // This function should return true if the course progress was reset successfully, and false otherwise
	    global $wpdb;

	    $progress_reset = $wpdb->update("wp_stm_lms_user_courses", array( 'progress_percent' => 0 ), array( 'course_id' => $course_id, 'user_id' => $user_id ));
        $wpdb->delete("wp_stm_lms_user_lessons", array( 'course_id' => $course_id, 'user_id' => $user_id ));
        $wpdb->delete("wp_stm_lms_user_quizzes", array( 'course_id' => $course_id, 'user_id' => $user_id ));
        $wpdb->delete("wp_stm_lms_user_answers", array( 'course_id' => $course_id, 'user_id' => $user_id ));

	    return $progress_reset !== false;
	}
// ***********End----------  hive Reset progress of expert for specific course(s) - specific user(email)


// ----------start Get all User-Course By User ID,course  assign to user
	function get_user_course_data($request){
		// $user_id = $request->get_param( 'id' );
		// global $wpdb;
		// $course_user=$wpdb->get_results( "SELECT * FROM wp_stm_lms_user_courses WHERE user_id = $user_id" );
		// if ( empty( $user_id ) ) {
		// 	return new WP_Error( 'invalid_post_id', 'Invalid post ID', array( 'status' => 404 ) );
		// }
		// foreach($course_user as $post){
		// 	$course_data = get_post( $post->course_id ); // Corrected the function name and arguments
		// 	// print_r($course_data);
	  //   return new WP_REST_Response($course_data, 200);

		// }
		    $user_id = $request->get_param('id');
    global $wpdb;

    // $course_ids = $wpdb->get_col($wpdb->prepare(
    //     "SELECT course_id FROM wp_stm_lms_user_courses WHERE user_id = %d",
    //     $user_id
    // ));
    $course_ids = $wpdb->get_col(
		    $wpdb->prepare(
		        "SELECT uc.course_id
		        FROM wp_stm_lms_user_courses AS uc
		        INNER JOIN wp_posts AS p ON uc.course_id = p.ID
		        WHERE uc.user_id = %d
		        AND p.post_status = 'publish'",
		        $user_id
		    )
		);


    if (empty($course_ids)) {
        return new WP_Error('no_assigned_courses', 'No courses assigned to the user.', array('status' => 404));
    }

    $courses_data = array();

    foreach ($course_ids as $course_id) {
        $course_data = get_post($course_id);

        // Check if the course is active or published
        $course_status = get_post_status($course_id);
				$course_progress = get_user_course_progress($user_id, $course_id);

        if ($course_status === 'publish') {
            $courses_data[] = array($course_data,'course_progress'=>$course_progress);
        }
    }

    if (empty($courses_data)) {
        return new WP_Error('no_active_courses', 'No active courses found for the user.', array('status' => 404));
    }

    return new WP_REST_Response($courses_data, 200);
	}

	add_action('rest_api_init', function(){
		register_rest_route( 'custom-api/v1', '/user-course/(?P<id>\d+)', array(
			'methods' => 'GET',
			'callback' => 'get_user_course_data',
		) );
	});
// End----------  User-Course By User ID


// -----not-----start get all user assign to course By Course ID
	function get_course_user_data($request){
		$course_id = $request->get_param( 'id' );
		global $wpdb;
		$course_user=$wpdb->get_results( "SELECT * FROM wp_stm_lms_user_courses WHERE course_id = $course_id" );

		if ( empty( $course_user ) ) {
	        return new WP_Error( 'invalid_post_id', 'Invalid post ID', array( 'status' => 404 ) );
	    }
		foreach($course_user as $user){
			$user_data=get_user_by('id', $user->user_id);
			// print_r($user_data);
	    return new WP_REST_Response($user_data, 200);

		}

	}
	add_action('rest_api_init', function(){
	  register_rest_route( 'custom-api/v1', '/course-users/(?P<id>\d+)', array(
	    'methods' => 'GET',
	    'callback' => 'get_course_user_data',
	  ) );
	});
// End----------  course-users By Course ID


// ----------start signle course detail by course id
	// Register REST API endpoint to get course details by ID
	add_action('rest_api_init', function () {
	    register_rest_route('custom-api/v1', '/course-details/(?P<id>\d+)/(?P<user_id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'get_course_details',
	    ));
	});

	// Callback function to retrieve course details
	function get_course_details($request) {
	    $user_id = $request->get_param('user_id');
	    $course_id = $request->get_param('id');
	    
	    // You can customize this part to retrieve course details based on the provided course ID
	    $course_data = get_post($course_id);

	    if (!$course_data) {
	        return new WP_Error('course_not_found', 'Course not found', array('status' => 404));
	    }
			$course_level = get_post_meta($course_id, 'level', true);
			$course_progress = get_user_course_progress($user_id, $course_id);
			$course_categories_list = wp_get_post_terms($course_id, 'stm_lms_course_taxonomy'); // Change to your custom taxonomy slug
			$category_names = wp_list_pluck($course_categories_list, 'name');	    
	    // Calculate the total time for this course
	    $total_time=0;
			$course_ideal_time = get_post_meta($course_id, 'ideal_time', true);
	    $total_time += intval($course_ideal_time);
			$total_course_ideal_time=get_total_ideal_time_in_course($course_id);
			$total_time += intval($total_course_ideal_time);

	    // Create a response array with the course data
	    $response = array(
	        'course_id' => $course_data->ID,
	        'course_title' => $course_data->post_title,
	        'course_content' => $course_data->post_content,
	        'course_progress'=> $course_progress,
	        'course_level'=> $course_level,
	        'category_names' => $category_names,
	        'course_ideal_time'=>$total_time
	        // Add more course data as needed
	    );

	    return rest_ensure_response($response);
	}
// End----------  


// ----------start Show in progress course login user 
	// Add custom REST API endpoint /wp-json/custom-api/v1/course-progress/<user_id>/<course_id>    
	// /wp-json/custom-api/v1/course-progress/123/456
	function custom_course_progress_endpoint() {
	    register_rest_route('custom-api/v1', '/course-progress/(?P<user_id>\d+)/(?P<course_id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'get_course_progress_by_user',
	    ));
	}
	add_action('rest_api_init', 'custom_course_progress_endpoint');
	function get_course_progress_by_user($request) {
	    $user_id = $request['user_id'];
	    $course_id = $request['course_id'];

	    // Make sure the user exists
	    if (!get_user_by('ID', $user_id)) {
	        return new WP_Error('invalid_user', 'Invalid user ID.', array('status' => 404));
	    }

	    // Check if the course exists
	    if (!get_post($course_id) || get_post_type($course_id) !== 'stm-courses') {
	        return new WP_Error('invalid_course', 'Invalid course ID.', array('status' => 404));
	    }

	    // Get the user's progress for the course
	    $progress = get_user_course_progress($user_id, $course_id);

	    if ($progress !== null) {
	        return new WP_REST_Response($progress, 200);
	    } else {
	        return new WP_Error('progress_not_found', 'Course progress not found for the user.', array('status' => 404));
	    }
	}
	function get_user_course_progress($user_id, $course_id) {
	    global $wpdb;

	    // Retrieve the course progress
	    $progress = $wpdb->get_var($wpdb->prepare(
	        "SELECT progress_percent FROM wp_stm_lms_user_courses WHERE user_id = %d AND course_id = %d",
	        $user_id,
	        $course_id
	    ));

	    // Check if progress is found
	    if ($progress === null) {
	        // Progress not found, return 0
	        return 'Course Not Assign';
	    } elseif ($progress === '100') {
	        // Progress is 100%, return 'completed'
	        return $progress;
	    } elseif ($progress >= 0 && $progress < 100) {
	        // Progress is between 0 and 99, return the current progress
	        return $progress;
	    } else {
	        // Invalid progress value, return 0
	        return $progress;
	    }
	}
// End----------  Reset Show in progress course


// ----------start Show Completed course list login user 
	// Add custom REST API endpoint to get completed courses for a user
	//https://example.com/wp-json/custom-api/v1/completed-courses/123     
	//where 123 is the user ID for which you want to retrieve the completed courses.
	function custom_completed_courses_endpoint() {
	    register_rest_route('custom-api/v1', '/dashboard-courses/(?P<user_id>\d+)', array(
	        'methods'  => 'GET',
	        'callback' => 'get_completed_courses_by_user',
	    ));
	}
	add_action('rest_api_init', 'custom_completed_courses_endpoint');
	// Retrieve completed courses for a user
	function get_completed_courses_by_user($request) {
	    $user_id = $request['user_id'];
	    $completed_courses = array();
	    $total_assigned_courses = array(); 

	    // Perform query or any custom logic to get completed courses for the user
	    // For example, assuming the user's completed courses are stored in the wp_stm_lms_user_courses table
	    global $wpdb;
	    // $completed_courses_results = $wpdb->get_results($wpdb->prepare(
	    //     "SELECT course_id FROM wp_stm_lms_user_courses WHERE user_id = %d AND progress_percent = 100",  $user_id
	    // ));

	    $completed_courses_results = $wpdb->get_results($wpdb->prepare(
			    "SELECT uc.course_id
			    FROM wp_stm_lms_user_courses AS uc
			    INNER JOIN wp_posts AS p ON uc.course_id = p.ID
			    WHERE uc.user_id = %d
			    AND uc.progress_percent = 100
			    AND p.post_status = 'publish'",
			    $user_id
			));

	    // $all_courses_results = $wpdb->get_results($wpdb->prepare(
	    //     "SELECT course_id FROM wp_stm_lms_user_courses WHERE user_id = %d", $user_id
	    // ));

	    $all_courses_results = $wpdb->get_results($wpdb->prepare(
			    "SELECT uc.course_id
			    FROM wp_stm_lms_user_courses AS uc
			    INNER JOIN wp_posts AS p ON uc.course_id = p.ID
			    WHERE uc.user_id = %d
			    AND p.post_status = 'publish'",
			    $user_id
			));


	    // Process the query results and build the completed courses array
	    if ($completed_courses_results) {
	        foreach ($completed_courses_results as $course) {
	            $completed_courses[] = $course->course_id;
	        }
	    }
	    // Process the query results for total assigned courses
	    if ($all_courses_results) {
	        foreach ($all_courses_results as $course) {
	            $total_assigned_courses[] = get_course_details_by_id($course->course_id, $user_id);
	        }
	    }
	        $response_data = array(
        // 'completed_courses' => $completed_courses,
        'total_assigned_courses' => $total_assigned_courses,
    );

    // Return the response as a JSON response
    return new WP_REST_Response($response_data, 200);

	}

function get_course_details_by_id($course_id,$user_id) {
    $course = get_post($course_id);

    if (!$course || $course->post_type !== 'stm-courses' || $course->post_status !== 'publish') {
        return null; // Course not found or not published
    }
    $course_progress = get_user_course_progress($user_id, $course_id);
    $course_title = $course->post_title;
    $course_content = $course->post_content;
    $course_date = $course->post_date;
    $course_level = get_post_meta($course_id, 'level', true);
		$course_categories_list = wp_get_post_terms($course_id, 'stm_lms_course_taxonomy'); // Change to your custom taxonomy slug
		$category_names = wp_list_pluck($course_categories_list, 'name');
		$total_time=0;
		$course_ideal_time = get_post_meta($course_id, 'ideal_time', true);
			    $total_time += intval($course_ideal_time);
					$total_course_ideal_time=get_total_ideal_time_in_course($course_id);
					$total_time += intval($total_course_ideal_time);
    // You can add more fields here as needed
    // For example, retrieve additional custom fields

    $course_data = array(
        'course_id' => $course_id,
        'course_title' => $course_title,
        'course_content' => $course_content,
        'course_date' => $course_date,
        'course_level' => $course_level,
        'course_progress'=> $course_progress,
        'category_names' => $category_names,
		    'course_ideal_time'=>$total_time

        // Add more data here
    );

    return $course_data;
}

// End----------  Show Completed course list login user 


// ----------start Show all course list For user ID
	// https://example.com/wp-json/custom-api/v1/courses-with-progress-zero/123
	// where 123 is the user ID for which you want to retrieve the courses with progress 0 or not started.
	function custom_courses_with_progress_zero_endpoint() {
	    register_rest_route('custom-api/v1', '/courses-with-progress-zero/(?P<user_id>\d+)', array(
	        'methods'  => 'GET',
	        'callback' => 'get_courses_with_progress_zero_by_user',
	    ));
	}
	add_action('rest_api_init', 'custom_courses_with_progress_zero_endpoint');


	// Retrieve courses for a user with progress 0 or not started
	function get_courses_with_progress_zero_by_user($request) {
	    $user_id = $request['user_id'];
	    $courses_with_progress_zero = array();

	    // Perform query or any custom logic to get courses with progress 0 for the user
	    // For example, assuming the courses are stored in the wp_stm_lms_user_courses table
	    global $wpdb;
	    // $courses_results = $wpdb->get_results($wpdb->prepare(
	    //     "SELECT course_id FROM wp_stm_lms_user_courses WHERE user_id = %d AND progress_percent= 0",
	    //     $user_id
	    // ));

	    $courses_results = $wpdb->get_results($wpdb->prepare(
			    "SELECT uc.course_id
			    FROM wp_stm_lms_user_courses AS uc
			    INNER JOIN wp_posts AS p ON uc.course_id = p.ID
			    WHERE uc.user_id = %d
			    AND uc.progress_percent = 0
			    AND p.post_status = 'publish'",
			    $user_id
			));

	    // Process the query results and build the courses array
	    if ($courses_results) {
	        foreach ($courses_results as $course) {
	            $courses_with_progress_zero[] = $course->course_id;
	        }
	    }

	    // Return the courses with progress 0 or not started as a JSON response
	    // return rest_ensure_response($courses_with_progress_zero);
	    return new WP_REST_Response($courses_with_progress_zero, 200);

	}
// End----------  start Show all course list For user ID


// ----------start User info by Email id 
	// Add custom REST API endpoint
	function user_information() {
	    register_rest_route( 'custom-api/v1', '/login-user-info/(?P<email>[^/]+)', array(
	        'methods'  => 'GET',
	        'callback' => 'get_user_information_email',
	    ) );
	}
	add_action( 'rest_api_init', 'user_information' );

	// Retrieve user information by email
	function get_user_information_email( $request ) {
	    $email = $request['email'];
	    $users = get_user_by( 'email', $email ); 
	    $user_data= get_userdata( $users->ID  ); 

    $username = $user_data->user_login;
    $user_email = $user_data->user_email;
    $user_firstname = $user_data->first_name;
    $user_lastname = $user_data->last_name;
    $user_displayname = $user_data->display_name;
    $user_registered = $user_data->user_registered;
	     
	    // return rest_ensure_response($user);
	    return new WP_REST_Response($users, 200);


	}
// End----------  Reset Progress From Wp-Admin Design 


// ----------start User Learning path Progress 
	// Add custom REST API endpoint to calculate learning path progress for a user
	function custom_learning_path_progress_endpoint() {
	    register_rest_route('custom-api/v1', '/learning-path-progress/(?P<user_id>\d+)', array(
	        'methods'  => 'GET',
	        'callback' => 'calculate_learning_path_progress',
	    )); 
	}
	add_action('rest_api_init', 'custom_learning_path_progress_endpoint');
	
	function calculate_learning_path_progress($request) {
    $user_id = $request['user_id'];
    $learning_path_progress = 0;
    $total_assigned_courses = 0;
    $total_completed_courses = 0;

    // Get the progress of all assigned courses for the user
    // For example, assuming the courses and their progress are stored in the wp_stm_lms_user_courses table
    global $wpdb;
    // $courses_results = $wpdb->get_results($wpdb->prepare(
    //     "SELECT course_id, progress_percent FROM wp_stm_lms_user_courses WHERE user_id = %d",
    //     $user_id
    // ));
    $courses_results = $wpdb->get_results($wpdb->prepare(
		    "SELECT uc.course_id, uc.progress_percent
		    FROM wp_stm_lms_user_courses AS uc
		    INNER JOIN wp_posts AS p ON uc.course_id = p.ID
		    WHERE uc.user_id = %d
		    AND p.post_status = 'publish'",
		    $user_id
		));


    // Calculate the accumulative progress of all assigned courses
    foreach ($courses_results as $course) {
        $total_assigned_courses++;
        if ($course->progress_percent == 100) {
            $total_completed_courses++;
        }
    }

    // Calculate the learning path progress as a percentage
    if ($total_assigned_courses > 0) {
        $learning_path_progress = ($total_completed_courses / $total_assigned_courses) * 100;
    }

    // Create an associative array to return the data
    $response_data = array(
        'total_assigned_courses' => $total_assigned_courses,
        'total_completed_courses' => $total_completed_courses,
        'learning_path_progress' => $learning_path_progress,
    );

    // Return the response as a JSON response
    return new WP_REST_Response($response_data, 200);
	}
// End----------  Learning path Progress 


// ----------start User Learning path course exculde manull courses list 
	function register_courses_by_progress_endpoint() {
	    register_rest_route('custom-api/v1', '/courses-by-progress/(?P<user_id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'get_courses_by_progress',
	    ));
	}
	add_action('rest_api_init', 'register_courses_by_progress_endpoint');


	function get_courses_by_progress(WP_REST_Request $request) {
	    $user_id = $request->get_param('user_id');

	    if (empty($user_id) || !is_numeric($user_id)) {
	        return new WP_REST_Response(array('error' => 'Invalid user ID.'), 400);
	    }

	    $user_courses = get_user_courses_by_progress($user_id);

	    if (empty($user_courses['in_progress']) && empty($user_courses['not_started'])) {
	        return new WP_REST_Response(array('error' => 'No courses found for the user.'), 404);
	    }

	    return new WP_REST_Response($user_courses, 200);
	}


	function get_user_courses_by_progress($user_id) {
	    global $wpdb;
	    // $in_progress_courses = $wpdb->get_col(
	    //     $wpdb->prepare("SELECT course_id FROM wp_stm_lms_user_courses WHERE user_id = %d AND progress_percent > 0 AND progress_percent < 100", $user_id)
	    // );
	    $in_progress_courses = $wpdb->get_col(
			    $wpdb->prepare(
			        "SELECT uc.course_id
			        FROM wp_stm_lms_user_courses AS uc
			        INNER JOIN wp_posts AS p ON uc.course_id = p.ID
			        WHERE uc.user_id = %d
			        AND p.post_status = 'publish'
			        AND uc.progress_percent > 0
			        AND uc.progress_percent < 100",
			        $user_id
			    )
			);


	    // $not_started_courses = $wpdb->get_col(
	    //     $wpdb->prepare("SELECT course_id FROM wp_stm_lms_user_courses WHERE user_id = %d AND progress_percent = 0", $user_id)
	    // );
			$not_started_courses = $wpdb->get_col(
			    $wpdb->prepare(
			        "SELECT uc.course_id
			        FROM wp_stm_lms_user_courses AS uc
			        INNER JOIN wp_posts AS p ON uc.course_id = p.ID
			        WHERE uc.user_id = %d
			        AND p.post_status = 'publish'
			        AND uc.progress_percent = 0",
			        $user_id
			    )
			);

	    // Exclude manually added courses
	    $in_progress_courses = array_filter($in_progress_courses, function($course_id) use ($user_id) {
	        return !is_manually_added_course($course_id, $user_id);
	    });

	    $not_started_courses = array_filter($not_started_courses, function($course_id) use ($user_id) {
	        return !is_manually_added_course($course_id, $user_id);
	    });

	    return array(
	        'in_progress' => $in_progress_courses,
	        'not_started' => $not_started_courses,
	    );
	}

	function is_manually_added_course($course_id, $user_id) {
	    $course_meta_value = get_post_meta($course_id, 'manual_added_student', true);

	    if (!empty($course_meta_value) && is_array($course_meta_value)) {
	        // Assuming that the 'manual_added_student' contains user emails
	        $user = get_user_by('ID', $user_id);
	        $user_email = $user ? $user->user_email : '';

	        // Check if the user email exists in the 'manual_added_student' list
	        return in_array($user_email, $course_meta_value);
	    }

	    return false;
	}
// End----------  User Learning path course 


// ----------start Course curriculum API (Lessons and Quiz)
	add_action('rest_api_init', function () {
    register_rest_route('custom-api/v1', '/course-curriculum/(?P<user_id>\d+)/(?P<course_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_course_curriculum',
    ));
	});
	function get_course_curriculum($request) {
	    $course_id = $request->get_param('course_id');
	    $user_id = $request->get_param('user_id');

	    // Check if the course ID is valid
	    if (empty($course_id) || !is_numeric($course_id)) {
	        return new WP_Error('invalid_course_id', 'Invalid course ID', array('status' => 400));
	    }

	    // Get the course data using $course_id, you can use your own method to retrieve the data.
	    $course_data = get_post($course_id);

	    // Check if the course exists
	    if (empty($course_data) || $course_data->post_type !== 'stm-courses') {
	        return new WP_Error('course_not_found', 'Course not found', array('status' => 404));
	    }

	    // Explode the curriculum meta_value to get individual items
	    $curriculum = get_post_meta($course_id, 'curriculum', true);
	    $curriculum_items = explode(',', $curriculum);

	    // Prepare an array to store curriculum data
	    $curriculum_data = array(
	        'course_title' => $course_data->post_title,
	        'sections' => array(),
	    );

	    $current_section = null;

	    // print_r($curriculum_items);
	    foreach ($curriculum_items as $item) {
	        if (preg_match("/[a-z]/i", $item) || empty($item)) {
	            // This is a section title
	            if ($current_section) {
	                $curriculum_data['sections'][] = $current_section;
	            }
	            $current_section = array(
	                'title' => trim($item),
	                'items' => array(),
	            );
	        } else if (preg_match('/^[0-9]+$/', $item) && $current_section) {
	        	// $lesson_or_quiz_title = get_the_title($item);
            // $completed = is_lesson_completed($item,$user_id); // Change this to your own function to check completion status
		        $lesson_or_quiz = get_post($item);

		        if ($lesson_or_quiz) {
		            $lesson_or_quiz_title = $lesson_or_quiz->post_title;
		            $post_type = $lesson_or_quiz->post_type;
		            if ($post_type === 'stm-lessons') {
									$completed = STM_LMS_Lesson::is_lesson_completed($user_id, $course_id, $item);

									$lesson_type_video = get_post_meta($item, 'type', true);

									// var_dump($lesson_type_video);
									if(!empty($lesson_type_video) && $lesson_type_video =='video'){
											$lesson_type_video_url = get_post_meta($item, 'lesson_video_url', true);
											$lesson_video_poster_url = get_post_meta($item, 'lesson_video_poster', true);


											$lesson_type='Video';
											$lesson_video_url=$lesson_type_video_url;
											$lesson_video_poster=stm_lms_get_image_url($lesson_video_poster_url, 'full');

									}elseif(!empty($lesson_type_video) && $lesson_type_video =='text'){
										$lesson_type = 'Lesson';
									}

		            } elseif ($post_type === 'stm-quizzes') {
		            	  $quiz_question = get_post_meta( $item, 'questions', true );
								    $quiz_question_array=explode(',', $quiz_question);			
		                $completed = is_quizzes_completed($item, $user_id);
		                $lesson_type='Quiz';
		            } else {
		                // Handle other post types here
		                $completed = false;
		                $lesson_type='';

		            }
      					$total_ideal_time = 0;

								$lesson_ideal_time = get_post_meta($item, 'ideal_time', true);
								$total_ideal_time += intval($lesson_ideal_time); // Add lesson's ideal time to total
		            if ($lesson_or_quiz_title) {
		            		if($lesson_type=="Video"){
		            			if ($post_type === 'stm-quizzes') {
				                $current_section['items'][] = array(
				                    'id' => $item,
				                    'title' => $lesson_or_quiz_title,
				                    'completed' => $completed['completed'],
				                    'status'=> $completed['status'],
				                    'lesson_type'=>$lesson_type,
				                    'video_url'=>$lesson_video_url,
				                    'video_poster'=>$lesson_video_poster,
				                    'total_ideal_time'=>$total_ideal_time
				                );
				              }else{
				                $current_section['items'][] = array(
				                    'id' => $item,
				                    'title' => $lesson_or_quiz_title,
				                    'completed' => $completed,
				                    'lesson_type'=>$lesson_type,
				                    'video_url'=>$lesson_video_url,
				                    'video_poster'=>$lesson_video_poster,
				                    'total_ideal_time'=>$total_ideal_time

				                );
				              }
		            		}else{
		            			if ($post_type === 'stm-quizzes') {
				                $current_section['items'][] = array(
				                    'id' => $item,
				                    'title' => $lesson_or_quiz_title,
				                    'completed' => $completed['completed'],
				                    'status'=> $completed['status'],
				                    'Questions_count'=>count($quiz_question_array),

				                    'lesson_type'=>$lesson_type,

				                );
				              }else{
				              	$current_section['items'][] = array(
				                    'id' => $item,
				                    'title' => $lesson_or_quiz_title,
				                    'completed' => $completed,
				                    'lesson_type'=>$lesson_type,
				                    'total_ideal_time'=>$total_ideal_time

				                );
				              }
		                }
		            }
			      }
	    		}
			}
	    if ($current_section) {
	        $curriculum_data['sections'][] = $current_section;
	    }

	    // Return the curriculum data as a JSON response
	    return new WP_REST_Response($curriculum_data, 200);
	}

	function is_lesson_completed($lesson_id, $user_id) {
	    global $wpdb;
	    $table_name = $wpdb->prefix . 'stm_lms_user_lessons';

	    $completed_lessons =  $wpdb->get_col(
	        $wpdb->prepare("SELECT lesson_id FROM $table_name WHERE user_id = %d AND lesson_id = %d", $user_id, $lesson_id)
	    );

	    return !empty($completed_lessons);
	}

	function is_quizzes_completed($quiz_id, $user_id) {
	    global $wpdb;
	    $table_name = 'wp_stm_lms_user_quizzes';

	    // Get the user's most recent attempt for the specified quiz
	    $most_recent_attempt = $wpdb->get_row(
	        $wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d AND quiz_id = %d ORDER BY user_quiz_id DESC LIMIT 1", $user_id, $quiz_id)
	    );

	    if ($most_recent_attempt) {
	        $status = $most_recent_attempt->status;
	        return array(
	            'completed' => true,
	            'status' => $status,
	        );
	    }

	    return array(
	        'completed' => false,
	        'status' => null,
	    );

	}

// End----------  Course curriculum API (Lessons and Quiz)



// ----------start all courses
	function get_all_courses_api($request) {
    	$args = array(
	        'post_type' => 'stm-courses', // Change to your actual post type name
	        'posts_per_page' => -1, // Retrieve all posts
	        'post_status' => 'publish', // Retrieve only published posts
	    );
			$user_id = $request->get_param('user_id');

	    $courses = get_posts($args);

	    $course_data = array();
	    
	    foreach ($courses as $course) {
	        $course_id = $course->ID;
	        $course_title = $course->post_title;
	        $course_content = $course->post_content;
	        $course_date = $course->post_date;
					$total_time = 0;

					$course_ideal_time = get_post_meta($course_id, 'ideal_time', true);
			    $total_time += intval($course_ideal_time);
					$total_course_ideal_time=get_total_ideal_time_in_course($course_id);
					$total_time += intval($total_course_ideal_time);

	        // You can add more fields here as needed
	        // For example, get course description, custom fields, etc.
          $course_progress = get_user_course_progress($user_id, $course_id);
					$course_level = get_post_meta($course_id, 'level', true);
					$course_categories_list = wp_get_post_terms($course_id, 'stm_lms_course_taxonomy'); // Change to your custom taxonomy slug
					$category_names = wp_list_pluck($course_categories_list, 'name');

	        $course_data[] = array(
	            'course_id' => $course_id,
	            'course_title' => $course_title,
							'course_content'=>$course_content,
							'course_date'=>$course_date,
							'course_progress'=> $course_progress,
							'course_level'=> $course_level,
							'category_names' => $category_names,
							'course_ideal_time'=>$total_time
	            // Add more data here
	        );
	    }
	    if (empty($course_data)) {
	        return new WP_Error('no_courses', 'No courses found', array('status' => 404));
	    }
    return new WP_REST_Response($course_data, 200);

	}

	add_action('rest_api_init', function () {
	    register_rest_route('custom-api/v1',  '/courses/(?P<user_id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'get_all_courses_api',
	    ));
	});

// End----------  all courses


// ----------start all get quiz content
	function get_quizzes_content($request) {
	    $quizzes_id = $request->get_param('id');
	    
	    if (empty($quizzes_id)) {
	        return new WP_Error('invalid_quizzes_id', 'Invalid quizzes ID', array('status' => 404));
	    }
	    
	    $quizzes = get_post($quizzes_id);
	    
	    if (!$quizzes || $quizzes->post_type !== 'stm-quizzes') {
	        return new WP_Error('quizzes_not_found', 'Quizzes not found', array('status' => 404));
	    }
	    
	    $quizzes_content = $quizzes->post_content;
	    
	    $return_quiz= array(
	        'quizzes_id' => $quizzes_id,
	        'quizzes_content' => $lesson_content,
	    );

    return new WP_REST_Response($return_quiz, 200);

	}

	function register_quizzes_content_api() {
	    register_rest_route('custom-api/v1', '/quizzes/(?P<id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'get_quizzes_content',
	    ));
	}

	add_action('rest_api_init', 'register_quizzes_content_api');

// End----------  all get quiz content


// ----------start all get lesson content
	function get_lesson_content($request) {
	    $lesson_id = $request->get_param('id');
	    
	    if (empty($lesson_id)) {
	        return new WP_Error('invalid_lesson_id', 'Invalid lesson ID', array('status' => 404));
	    }
	    
	    $lesson = get_post($lesson_id);
	    
	    if (!$lesson || $lesson->post_type !== 'stm-lessons') {
	        return new WP_Error('lesson_not_found', 'Lesson not found', array('status' => 404));
	    }
	    
			$lesson_type = get_post_meta($item, 'type', true);
	    $lesson_type_video_url = get_post_meta($lesson_id, 'lesson_video_url', true);
			$lesson_video_poster_url = get_post_meta($lesson_id, 'lesson_video_poster', true);
			$lesson_video_poster=stm_lms_get_image_url($lesson_video_poster_url, 'full');
	    $lesson_content = $lesson->post_content;
	    
	    $return_value=array(
	        'lesson_id' => $lesson_id,
					'lesson_content' => $lesson_content,
					'lesson_type'=>$lesson_type,
					'video_url'=>$lesson_type_video_url,
					'video_poster'=>$lesson_video_poster,

	    );

    return new WP_REST_Response($return_value, 200);

	}

	function register_lesson_content_api() {
	    register_rest_route('custom-api/v1', '/lesson/(?P<id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'get_lesson_content',
	    ));
	}

	add_action('rest_api_init', 'register_lesson_content_api');
// End----------  all get lesson content


// ----------start  Register REST API endpoint for user login and registration
	add_action('rest_api_init', function () {
	    // User Login
	    register_rest_route('custom-api/v1', '/user_login_wp', array(
	        'methods' => 'POST',
	        'callback' => 'user_login_react',
	    ));

	    // User Registration
	    register_rest_route('custom-api/v1', '/user_registration_wp', array(
	        'methods' => 'POST',
	        'callback' => 'user_registration_react',
	    ));
	});

	// User Login Callback
	function user_login_react($request) {
	    $data = $request->get_json_params();
	    $username = sanitize_text_field($data['username']);

	    // Perform your custom user login logic here
	    // For example, you can use wp_signon() function
	    $user = get_user_by('login', $username);
	    if (!$user) {
	        $user = get_user_by('email', $username);
	        if (!$user) {
	            return new WP_Error('user_not_found', 'User not found', array('status' => 404));
	        }
	    }

	    // User found
	    $user_id = $user->ID;

	    $response = array(
	        'message' => 'User found',
	        'user_id' => $user_id,
	    );

	    return new WP_REST_Response($response, 200);

	     
	}

	// User Registration Callback
	function user_registration_react($request) {
	    $data = $request->get_json_params();
	    $username = sanitize_text_field($data['username']);
	    $email = sanitize_email($data['email']);
	    $password = sanitize_text_field($data['password']);

	    $first_name = sanitize_text_field($data['first_name']);
	    $last_name = sanitize_text_field($data['last_name']);
	    $role = sanitize_text_field($data['role']); // subscriber
	    $user_type= sanitize_text_field($data['user_type']); // capacity_network

	    // Perform your custom user registration logic here
	    // For example, you can use wp_create_user() function
	    
	    $user_id = wp_create_user($username, $password, $email);
	    if ($user_id) {
		    update_user_meta( $user_id, "user_type", $user_type );
		  }
	    if (is_wp_error($user_id)) {
	        return new WP_Error('registration_error', 'User registration failed', array('status' => 400));
	    }

	    // Update user meta with additional data
	    update_user_meta($user_id, 'first_name', $first_name);
	    update_user_meta($user_id, 'last_name', $last_name);

	    // Set user role
	    if (!empty($role)) {
	        wp_update_user(array('ID' => $user_id, 'role' => $role));
	    }

	    return array(
	        'message' => 'User registered successfully',
	    );
	}
// End----------  Register REST API endpoint for user login and registration


// ----------start wp default category
	add_action('rest_api_init', function () {
	    register_rest_route('custom-api/v1', '/categories', array(
	        'methods' => 'GET',
	        'callback' => 'get_categories_api',
	    ));
	});

	function get_categories_api() {
	    $categories = get_categories(); // Get all categories

	    $formatted_categories = array();

	    foreach ($categories as $category) {
	        $formatted_categories[] = array(
	            'id' => $category->term_id,
	            'name' => $category->name,
	            'slug' => $category->slug,
	            'description' => $category->description,
	            'count' => $category->count,
	            // You can include more fields as needed
	        );
	    }

	    return new WP_REST_Response($formatted_categories, 200);
	}
// End---------- 


// ----------start course-categories-list
	function get_course_categories_list() {
	    $course_categories = get_terms(array(
	        'taxonomy' => 'stm_lms_course_taxonomy', // Change to your custom taxonomy slug
	        'hide_empty' => false,
	        'parent' => 0, // This parameter retrieves only parent categories
	    ));

	    $formatted_terms = array();

	    foreach ($course_categories as $term) {
	        $course_categories_list[] = array(
	            'id' => $term->term_id,
	            'name' => $term->name,
	            'slug' => $term->slug,
	            'description' => $term->description,
	            'count' => $term->count,
	            // You can include more fields as needed
	        );
	    }

	    return new WP_REST_Response($course_categories_list, 200);
	}

	function register_course_categories_list_api() {
	    register_rest_route('custom-api/v1', '/course-categories-list', array(
	        'methods' => 'GET',
	        'callback' => 'get_course_categories_list',
	    ));
	}

	add_action('rest_api_init', 'register_course_categories_list_api');
// End---------- 


// ----------start category filter
	function get_courses_by_category_name($request) {
    $user_id = $request->get_param('user_id'); // Array of category names
    $category_names = $request->get_param('category_names'); // Array of category names
    $page = $request->get_param('page'); // Page number for pagination
    $per_page = $request->get_param('per_page'); // Number of courses per page

    // Set default values if not provided
    if (empty($page)) {
        $page = 1;
    }
    if (empty($per_page)) {
        $per_page = 10; // Default number of courses per page
    }

    // Prepare query arguments
    $args = array(
        'post_type' => 'stm-courses', // Change to your custom post type slug
        'posts_per_page' => $per_page,
        'paged' => $page,
    );

    // Check if category names are provided
    if (!empty($category_names)) {
        // Get the category IDs based on the category names
        $category_ids = array();
        foreach ($category_names as $category_name) {
            $category = get_term_by('slug', $category_name, 'stm_lms_course_taxonomy'); // Change to your custom taxonomy slug
            if ($category) {
                $category_ids[] = $category->term_id;
            }
        }

        // Add tax query for the category IDs
        if (!empty($category_ids)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'stm_lms_course_taxonomy', // Change to your custom taxonomy slug
                    'field' => 'term_id',
                    'terms' => $category_ids,
                ),
            );

        }else {
            // No category IDs found, return empty result
            return new WP_REST_Response(array(), 200);
        }
    } else {
        // No category names provided, return empty result
        return new WP_REST_Response(array(), 200);
    }
    // Get courses
    // print_r($args);
    // var_dump($args_count);
    // echo "---------------------------------------";

    $courses = get_posts($args);
    $total_courses_count = count($courses);

    // If no courses are found, return an empty result
    if (empty($courses)) {
        return new WP_REST_Response(array(), 200);
    }

    // Prepare the response with category name
    $response = array();
    $total_time=0;
    foreach ($courses as $course) {
        $course_categories = wp_get_post_terms($course->ID, 'stm_lms_course_taxonomy'); // Change to your custom taxonomy slug
        $category_names = wp_list_pluck($course_categories, 'name');
					$course_level = get_post_meta($course->ID, 'level', true);
					$course_categories_list = wp_get_post_terms($course->ID, 'stm_lms_course_taxonomy'); // Change to your custom taxonomy slug
					$category_names = wp_list_pluck($course_categories_list, 'name');
					
        $course_progress = get_user_course_progress($user_id, $course->ID);

        $course_ideal_time = get_post_meta($course->ID, 'ideal_time', true);
			    $total_time += intval($course_ideal_time);
					$total_course_ideal_time=get_total_ideal_time_in_course($course->ID);
					$total_time += intval($total_course_ideal_time);

        $response[] = array(
            'course_id' => $course->ID,
            'course_title' => $course->post_title,
            'course_content'=> $course->post_content,
            'category_names' => $category_names,
            'course_progress'=>$course_progress,
            'course_level'=>$course_level,
            'category_names' => $category_names,
            'course_ideal_time'=>$total_time
        );
    }

    $responses = array(
        'courses' => $response,
        'total_courses_count' => $total_courses_count,
    );

    return new WP_REST_Response($responses, 200);
	}

	function register_courses_by_category_name_api() {
	    register_rest_route('custom-api/v1', '/courses-by-category-name', array(
	        'methods' => 'POST',
	        'callback' => 'get_courses_by_category_name',
	    ));
	}

	add_action('rest_api_init', 'register_courses_by_category_name_api');
// End---------- 



// ----------start recommended-courses REST API
	add_action('rest_api_init', function () {
	    register_rest_route('custom-api/v1', '/recommended-courses', array(
	        'methods' => 'GET',
	        'callback' => 'get_recommended_courses',
	    ));
	});

	function get_recommended_courses($request) {
	    $course_ids = array(6347, 5033, 6129, 8602, 8613, 8620 ); // Static course IDs

	    $courses_data = array();
	    $total_time=0;
	    foreach ($course_ids as $course_id) {
					$course = get_post($course_id);
					$course_level = get_post_meta($course_id, 'level', true);
					$course_categories_list = wp_get_post_terms($course_id, 'stm_lms_course_taxonomy'); // Change to your custom taxonomy slug
					$category_names = wp_list_pluck($course_categories_list, 'name');

					$course_ideal_time = get_post_meta($course_id, 'ideal_time', true);
			    $total_time += intval($course_ideal_time);
					$total_course_ideal_time=get_total_ideal_time_in_course($course_id);
					$total_time += intval($total_course_ideal_time);

	        if ($course && $course->post_type === 'stm-courses') {
	            $course_data = array(
	                'course_id' => $course_id,
	                'course_title' => $course->post_title,
	                'course_content' => $course->post_content,
					        'course_level'=> $course_level,
					        'category_names' => $category_names,
					        'course_ideal_time'=>$total_time
	                // Add more fields as needed
	            );

	            $courses_data[] = $course_data;
	        }
	    }

	    return new WP_REST_Response($courses_data, 200);
	}
//-------end




// ----------start search_courses_lessons REST API
	// function search_courses_lessons($request) {
  //   $search_query = $request->get_param('search_query');
  //   $per_page = $request->get_param('per_page') ? intval($request->get_param('per_page')) : 10;
  //   $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;

  //   // Calculate offset for pagination
  //   $offset = ($page - 1) * $per_page;

  //   // Get courses that match the search query
  //       $sql = "
  //       SELECT ID, post_title, post_type
  //       FROM wp_posts
  //       WHERE post_type IN ('stm-courses') AND post_title LIKE %s
  //   ";

  //   // Prepare the SQL query with the search keyword
  //   $prepared_sql = $wpdb->prepare($sql, '%' . $wpdb->esc_like($search_query) . '%');
  //   $courses = $wpdb->get_results($prepared_sql);

  //   // $course_args = array(
  //   //     'post_type' => 'stm-courses', // Change to your custom course post type
  //   //     's' => $search_query,
  //   //      'exact' => true,
  //   //     'posts_per_page' => $per_page,
  //   //     'offset' => $offset,
  //   // );
  //   // $courses = get_posts($course_args);
  //   $courses_count = count($query_results);

    
  //   // Get lessons that match the search query
  //       // Get courses that match the search query
  //       $lesson_args = "
  //       SELECT ID, post_title, post_type
  //       FROM wp_posts
  //       WHERE post_type IN ('stm-lessons') AND post_title LIKE %s
  //   ";

  //   // Prepare the SQL query with the search keyword
  //   $prepared_sql_lesson = $wpdb->prepare($lesson_args, '%' . $wpdb->esc_like($search_query) . '%');
  //   $lessons = $wpdb->get_results($prepared_sql_lesson);
  //   // $lesson_args = array(
  //   //     'post_type' => 'stm-lessons', // Change to your custom lesson post type
  //   //     's' => $search_query,
  //   //      'exact' => true,
  //   //     'posts_per_page' => $per_page,
  //   //     'offset' => $offset,
  //   // );
  //   // $lessons = get_posts($lesson_args);
  //   $lessons_count = count($lessons;
    
  //   //total count
  //   $totl_count= $courses_count+$lessons_count;

  //   // Prepare the response
  //   $response = array(
  //       'courses' => array(),
  //       'courses_count' => $courses_count,
  //       'lessons' => array(),
  //       'lessons_count' => $lessons_count,
  //       'total_count' => $totl_count,
  //   );
    
  //   foreach ($courses as $course) {
  //       $response['courses'][] = array(
  //           'course_id' => $course->ID,
  //           'course_name' => $course->post_title,
  //           'course_content' => apply_filters('the_content', $course->post_content),
  //       );
  //   }
    
  //   foreach ($lessons as $lesson) {
  //       // Get the course(s) this lesson is assigned to from the stm_lms_curriculum_bind table
  //       global $wpdb;
  //       $assigned_courses = $wpdb->get_results(
  //           $wpdb->prepare("SELECT course_id FROM wp_stm_lms_curriculum_bind WHERE item_id = %d", $lesson->ID)
  //       );
        
  //       $course_info = array();
  //       foreach ($assigned_courses as $assigned_course) {
  //           $course = get_post($assigned_course->course_id);
  //           if ($course) {
  //               $course_info[] = array(
  //                   'course_id' => $course->ID,
  //                   'course_name' => $course->post_title,
  //               );
  //           }
  //       }
        
  //       $response['lessons'][] = array(
  //           'lesson_id' => $lesson->ID,
  //           'lesson_name' => $lesson->post_title,
  //           'assigned_courses' => $course_info,
  //       );
  //   }
    
  //   return new WP_REST_Response($response, 200);
	// }
function search_courses_lessons($request) {
    global $wpdb;

    $search_query = $request->get_param('search_query');
    $user_id = $request->get_param('user_id');
    $per_page = $request->get_param('per_page') ? intval($request->get_param('per_page')) : 10;
    $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;

    // Calculate offset for pagination
    $offset = ($page - 1) * $per_page;

    // Initialize the response data
    $response = array(
        'courses' => array(),
        'courses_count' => 0,
        'lessons' => array(),
        'lessons_count' => 0,
        'total_count' => 0,
    );

    // Search for courses
    $courses = $wpdb->get_results($wpdb->prepare(
        "SELECT ID, post_title, post_content
        FROM wp_posts
        WHERE post_type = 'stm-courses' AND post_title LIKE '%$search_query%' AND post_status = 'publish'
        LIMIT %d OFFSET %d",
        $per_page,
        $offset
    ));

    // Count the total number of courses
    $courses_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(ID)
        FROM wp_posts
        WHERE post_type = 'stm-courses' AND post_title LIKE '%$search_query%' AND post_status = 'publish'"
    ));

    // Search for lessons
    $lessons = $wpdb->get_results($wpdb->prepare(
        "SELECT ID, post_title
        FROM wp_posts
        WHERE post_type = 'stm-lessons' AND post_title LIKE '%$search_query%' AND post_status = 'publish'
        LIMIT %d OFFSET %d",
        $per_page,
        $offset
    ));

    // Count the total number of lessons
    $lessons_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(ID)
        FROM wp_posts
        WHERE post_type = 'stm-lessons' AND post_title LIKE '%$search_query%' AND post_status = 'publish'"
    ));

    // Calculate the total count
    $total_count = $courses_count + $lessons_count;
    // Prepare the response data
    foreach ($courses as $course) {

      $total_time=0;
			$course_ideal_time = get_post_meta($course->ID, 'ideal_time', true);
			$total_time += intval($course_ideal_time);
			$total_course_ideal_time=get_total_ideal_time_in_course($course->ID);
			$total_time += intval($total_course_ideal_time);

			$course_level = get_post_meta($course->ID, 'level', true);
			$course_progress = get_user_course_progress($user_id, $course->ID);
			$course_categories_list = wp_get_post_terms($course->ID, 'stm_lms_course_taxonomy'); // Change to your custom taxonomy slug
			$category_names = wp_list_pluck($course_categories_list, 'name');	    


        $response['courses'][] = array(
            'course_id' => $course->ID,
            'course_name' => $course->post_title,
            'course_content' => apply_filters('the_content', $course->post_content),
            'course_ideal_time'=> $total_time,
						'course_progress'=> $course_progress,
						'course_level'=> $course_level,
						'category_names' => $category_names,
        );
    }
		
	foreach ($lessons as $lesson) {
        // Get the course(s) this lesson is assigned to from the stm_lms_curriculum_bind table
        global $wpdb;
        $assigned_courses = $wpdb->get_results(
            $wpdb->prepare("SELECT course_id FROM wp_stm_lms_curriculum_bind WHERE item_id = %d", $lesson->ID)
        );
        
        $course_info = array();
        foreach ($assigned_courses as $assigned_course) {
            $course = get_post($assigned_course->course_id);
            if ($course) {
                $course_info[] = array(
                    'course_id' => $course->ID,
                    'course_name' => $course->post_title,
                );
            }
        }
        $total_ideal_time=0;
        $lesson_ideal_time = get_post_meta($lesson->ID, 'ideal_time', true);
        $total_ideal_time = intval($lesson_ideal_time); // Add lesson's ideal time to total
        
        $response['lessons'][] = array(
            'lesson_id' => $lesson->ID,
            'lesson_name' => $lesson->post_title,
            'assigned_courses' => $course_info,
            'total_ideal_time'=>$total_ideal_time,
        );
    }

    // Set the counts in the response
    $response['courses_count'] = $courses_count;
    $response['lessons_count'] = $lessons_count;
    $response['total_count'] = $total_count;

    // Return the response as a JSON response
    return new WP_REST_Response($response, 200);
	}

	function register_search_courses_lessons_api() {
	    register_rest_route('custom-api/v1', '/search-courses-lessons', array(
	        'methods' => 'POST',
	        'callback' => 'search_courses_lessons',
	    ));
	}

	add_action('rest_api_init', 'register_search_courses_lessons_api');

//--------end



// ----------start get_next_lesson_or_completed_item REST API
	function get_next_lesson_or_completed_item($request) {
    $user_id = $request->get_param('user_id');
    $course_id = $request->get_param('course_id');

    if (empty($user_id) || empty($course_id)) {
        return new WP_Error('missing_parameters', 'User ID and Course ID are required.', array('status' => 400));
    }

    // Get the curriculum for the course
    $curriculum = get_post_meta($course_id, 'curriculum', true);
    if (empty($curriculum)) {
        return new WP_Error('no_curriculum', 'Course curriculum not found.', array('status' => 404));
    }

    // Explode the curriculum meta_value to get individual items
    $curriculum_items = explode(',', $curriculum);
        echo "<pre>";

		print_r($curriculum_items);
    echo "</pre>";

    global $wpdb;
    $user_lessons_table = $wpdb->prefix . 'stm_lms_user_lessons';
    $user_quizzes_table = $wpdb->prefix . 'stm_lms_user_quizzes';

    // Get completed lesson IDs for the user in the given course
    $completed_lesson_ids = $wpdb->get_col(
        $wpdb->prepare("SELECT lesson_id FROM $user_lessons_table WHERE user_id = %d AND course_id = %d", $user_id, $course_id)
    );
    echo "<pre>";

		print_r($completed_lesson_ids);
    echo "</pre>";

    // Get completed quiz IDs for the user in the given course
    $completed_quiz_ids = $wpdb->get_col(
        $wpdb->prepare("SELECT quiz_id FROM $user_quizzes_table WHERE user_id = %d AND course_id = %d", $user_id, $course_id)
    );
    echo "<pre>";
		print_r($completed_quiz_ids);
    echo "</pre>";

    // Find the next item (lesson or quiz) based on curriculum order
    $next_item = null;
    $completed_item = null;

    foreach ($curriculum_items as $item) {
    	echo $item;
        $item_id = trim($item);
        if (is_numeric($item_id)) {
            if (!in_array($item_id, $completed_lesson_ids)) {
                $next_item = $item_id.'<br>next_item L ';
                break;
            } elseif (!in_array($item_id, $completed_quiz_ids)) {
                $next_item = $item_id.'<br>completed_quiz_ids L ';
                break;
            } else {
                $completed_item = $item_id.'<br>completed_item L ';
            }
        }
    }

    $response = array(
        'next_item' => $next_item,
        'completed_item' => $completed_item,
    );

    return new WP_REST_Response($response, 200);
	}

	function register_next_lesson_or_completed_item_api() {
	    register_rest_route('custom-api/v1', '/next-lesson-or-completed-item', array(
	        'methods' => 'GET',
	        'callback' => 'get_next_lesson_or_completed_item',
	    ));
	}

	add_action('rest_api_init', 'register_next_lesson_or_completed_item_api');
//---------end


// -------------Start MArk lesson complete REST API
	add_action('rest_api_init', function () {
	    register_rest_route('custom-api/v1', '/mark-lesson-complete', array(
	        'methods' => 'POST',
	        'callback' => 'mark_lesson_complete',
	    ));
	});



	function mark_lesson_complete($request) {
		    $user_id = $request->get_param('user_id');
	    $lesson_id = $request->get_param('lesson_id');
	    $course_id = $request->get_param('course_id');


	    // Validate the request parameters
	    if (empty($user_id) || empty($lesson_id)) {
	        return new WP_Error('invalid_params', 'Invalid request parameters', array('status' => 400));
	    }

	    // Check if the lesson exists
	    $lesson = get_post($lesson_id);

	    if (!$lesson || $lesson->post_type !== 'stm-lessons') {
	        return new WP_Error('lesson_not_found', 'Lesson not found', array('status' => 404));
	    }
	    $response_marked=mark_lesson_as_completed($course_id, $lesson_id, $user_id);

	    // Mark the lesson as complete for the user (update your database accordingly)
	    if ($response_marked) {
	        // Lesson marked as complete successfully
	        return new WP_REST_Response(array('message' => 'Lesson marked as complete'), 200);
	    } else {
	        // Failed to mark the lesson as complete
	        return new WP_Error('mark_failed', 'Failed to mark lesson as complete', array('status' => 500));
	    }
	}

	function mark_lesson_as_completed($course_id, $lesson_id, $user_id) {
			$data = [
			  'message' => esc_html__('Lesson Completed', 'stm-lms-api-domain')
			];

	    if (!STM_LMS_User::has_course_access($course_id, $user_id)) stm_lms_api_error('no_access', 'Course is not available from this account');

	    $curriculum = get_post_meta($course_id, 'curriculum', true);

	    if (empty($curriculum)) stm_lms_api_error('no_item', 'Course do not have this lesson', 404);

	    $curriculum = explode(',', $curriculum);

	    if (!in_array($lesson_id, $curriculum)) stm_lms_api_error('no_item', 'Course do not have this lesson', 404);

	    if (get_post_type($lesson_id) !== 'stm-lessons') stm_lms_api_error('not_lesson', 'Cheatin, hm?', 404);

	    if (STM_LMS_Lesson::is_lesson_completed($user_id, $course_id, $lesson_id)) {
	        wp_send_json($data);
	    };

	    $end_time = time();
	    $start_time = get_user_meta($user_id, "stm_lms_course_started_{$course_id}_{$lesson_id}", true);

	    stm_lms_add_user_lesson(compact('user_id', 'course_id', 'lesson_id', 'start_time', 'end_time'));
	    STM_LMS_Course::update_course_progress($user_id, $course_id);

	    do_action('stm_lms_lesson_passed', $user_id, $lesson_id);

	    delete_user_meta($user_id, "stm_lms_course_started_{$course_id}_{$lesson_id}");

	    return $data;


	}
// -------end of REST API




// -------------Start quiz-details page REST API
	function register_quiz_api_endpoint() {
	    register_rest_route('custom-api/v1', '/quiz-details/(?P<quiz_id>\d+)/(?P<user_id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'get_quiz_details',
	    ));
	}
	add_action('rest_api_init', 'register_quiz_api_endpoint');

	function get_quiz_details($request) {
	    $quiz_id = $request['quiz_id'];
	    $user_id = $request['user_id'];

	    // Implement logic to retrieve quiz details and user's attempt status
	    $quiz_details = get_quiz_details_from_database($quiz_id);
	    $user_attempt_status = get_user_quiz_attempt_statusdetail($user_id, $quiz_id);

	    if ($quiz_details === false) {
	        return new WP_Error('quiz_not_found', 'Quiz not found', array('status' => 404));
	    }

	    // Check if the user has taken the quiz and update the response accordingly
	    if ($user_attempt_status) {
	        $quiz_details['user_attempt_status'] = $user_attempt_status;
	    }

	    // Check if the user has taken the quiz and update the response accordingly
	    $rest_of_quiz_setting =  get_post_meta( $quiz_id, FALSE, TRUE);
	    if ($rest_of_quiz_setting) {
	        $quiz_details['quiz_setting'] = $rest_of_quiz_setting;
	    }
	    // $quiz_question = get_post_meta( $quiz_id, 'questions', true );
	    // $quiz_question_array=explode(',', $quiz_question);
	    // // var_dump($quiz_question_array);
	    // $quiz_postmeta = get_question_details_from_wp_postmeta($quiz_question_array);
	    // if ($quiz_postmeta) {
	    //     $quiz_details['Question_answers'] = $quiz_postmeta;
	    // }

	    // Return the response as a JSON object
	    return new WP_REST_Response($quiz_details, 200);
	}

	function get_quiz_details_from_database($quiz_id) {
	    global $wpdb;

	    // Query the wp_posts table to get quiz details
	    $quiz_query = $wpdb->prepare(
	        "SELECT post_title, post_content, post_date
	         FROM $wpdb->posts
	         WHERE ID = %d AND post_type = 'stm-quizzes'",
	        $quiz_id
	    );

	    $quiz_details = $wpdb->get_row($quiz_query, ARRAY_A);

	    // If quiz details are found, return them; otherwise, return false
	    if ($quiz_details) {
	        return $quiz_details;
	    } else {
	        return false;
	    }
	}


	function get_user_quiz_attempt_status($user_id, $quiz_id) {
	    global $wpdb;
	    $table_name = $wpdb->prefix . 'stm_lms_user_quizzes'; // Replace with your actual user quiz attempts table name

	    // Query the database to check if the user has attempted the quiz
	    $attempt_status = $wpdb->prepare(
	            "SELECT status,progress FROM $table_name WHERE user_id = %d AND quiz_id = %d",
	            $user_id,
	            $quiz_id
	    );
	    $attempt_status_details = $wpdb->get_row($attempt_status, ARRAY_A);
	    // $attempt_status_array=array();
	    // $attempt_status_array['quiz_stat'][] = array(
			// 				'status' => $attempt_status['status'],
			// 				'progress' => $attempt_status['progress']
	    //     );

	    if ($attempt_status_details) {
	        return $attempt_status_details; // Return the user's attempt status (e.g., 'pass' or 'fail')
	    }

	    return null; // User has not attempted the quiz
	}

		function get_user_quiz_attempt_statusdetail($user_id, $quiz_id) {
	    global $wpdb;
	    $table_name = $wpdb->prefix . 'stm_lms_user_quizzes'; // Replace with your actual user quiz attempts table name

	    // Query the database to check if the user has attempted the quiz
	    $attempt_status = $wpdb->prepare(
	            "SELECT status,progress FROM $table_name WHERE user_id = %d AND quiz_id = %d ORDER BY user_quiz_id DESC",
	            $user_id,
	            $quiz_id
	    );
	    $attempt_status_details = $wpdb->get_row($attempt_status, ARRAY_A);
	    // $attempt_status_array=array();
	    // $attempt_status_array['quiz_stat'][] = array(
			// 				'status' => $attempt_status['status'],
			// 				'progress' => $attempt_status['progress']
	    //     );

	    if ($attempt_status_details) {
	        return $attempt_status_details; // Return the user's attempt status (e.g., 'pass' or 'fail')
	    }

	    return null; // User has not attempted the quiz
	}
//--------------------end


// -------------Start quiz-details-questions page REST API
	function register_quiz_api_questions_endpoint() {
	    register_rest_route('custom-api/v1', '/quiz-details-questions/(?P<quiz_id>\d+)/(?P<user_id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'get_quiz_questions',
	    ));
	}
	add_action('rest_api_init', 'register_quiz_api_questions_endpoint');

	function get_quiz_questions($request) {
	    $quiz_id = $request['quiz_id'];
	    $user_id = $request['user_id'];

	    // Implement logic to retrieve quiz details and user's attempt status
	    $quiz_question_answer = get_quiz_details_from_database($quiz_id);
	    $user_attempt_result = get_user_quiz_attempt_status($user_id, $quiz_id);

	    if ($quiz_question_answer === false) {
	        return new WP_Error('quiz_not_found', 'Quiz not found', array('status' => 404));
	    }

	    // Check if the user has taken the quiz and update the response accordingly
	    if ($user_attempt_result) {
	        $quiz_question_answer['user_attempt_status'] = $user_attempt_result;
	    }

	    // Check if the user has taken the quiz and update the response accordingly
	    $quiz_question = get_post_meta( $quiz_id, 'questions', true );
	    $quiz_question_array=explode(',', $quiz_question);
	    // var_dump($quiz_question_array);
	    $quiz_postmeta = get_question_details_from_wp_postmeta($quiz_question_array);
	    if ($quiz_postmeta) {
	        $quiz_question_answer['Question_answers'] = $quiz_postmeta;
	    }

	    // Return the response as a JSON object
	    return new WP_REST_Response($quiz_question_answer, 200);
	}
	function get_question_details_from_wp_postmeta($question_ids) {
	    global $wpdb;

	    // Initialize an array to store question details
	    $question_details = array();
						// print_r($question_ids);
	    $question_details_array=array();
	    // Loop through each question ID
	    foreach ($question_ids as $question_id) {
	        // Query wp_postmeta to get question details
	        // Initialize an array to store the formatted question data
	        $formatted_question = array();
					$question_content = get_post($question_id);
	        $answer_in_detail = get_post_meta( $question_id, 'answers', true );
	        $rest_of_question_details =  get_post_meta( $question_id, FALSE, TRUE);

	        // Extract only the "text" values from the "answers" array
	        $answer_texts = array();
	        if (is_array($answer_in_detail)) {
	            foreach ($answer_in_detail as $answer) {
	            	// print_r($answers);
            // You can add more fields from the question metadata as needed
	                if(isset($answer['question_image'])){
	                	$image_data = isset($answer['question_image']) ? $answer['question_image'] : array();
            				$question_image = isset($image_data['url']) ? $image_data['url'] : '';		
	                	$answer_texts['question_image'][] = $question_image;
	                }
	                if(isset($answer['text_image'])){
	                	$image_data1 = isset($answer['text_image']) ? $answer['text_image'] : array();
            				$question_text_image = isset($image_data1['url']) ? $image_data1['url'] : '';		
	                	$answer_texts['answer_image'][] = $question_text_image;
	                }
	                if (isset($answer['text'])) {
	                	if ($rest_of_question_details['type'] !== "keywords") {
	                    $answer_texts['text_answer'][] = $answer['text'];
	                	}
	                }
	                if (isset($answer['question'])) {
	                    $answer_texts['option_question'][] = $answer['question'];
	                }
	            }
	        }

	        // Add the formatted question data to the question details array

	        $question_details[$question_id][] = array(
							'Questions' => $question_content,
							'questions_option' => $answer_texts,
							'type' => $rest_of_question_details['type'],
							'question' => $rest_of_question_details['question'],
							'question_explanation' => $rest_of_question_details['question_explanation'],
							'question_hint' => $rest_of_question_details['question_hint'],
							'question_view_type' => $rest_of_question_details['question_view_type'],
							'image' => $rest_of_question_details['image']
	        );

	        //$question_details_array[$question_id][]=$question_details;
	    }

	    return $question_details;
	}

//----------end




// -------------Start quiz-answers-list page REST API
	function register_quiz_api_answers_submite_endpoint() {
	    register_rest_route('custom-api/v1', '/quiz-details-answers/(?P<quiz_id>\d+)/(?P<user_id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'get_quiz_answers_submite',
	    ));
	}
	add_action('rest_api_init', 'register_quiz_api_answers_submite_endpoint');

	function get_quiz_answers_submite($request) {
	    $quiz_id = $request['quiz_id'];
	    $user_id = $request['user_id'];

	    // Implement logic to retrieve quiz details and user's attempt status
	    $quiz_question_answer = get_quiz_details_from_database($quiz_id);
	    $user_attempt_result = get_user_quiz_attempt_status($user_id, $quiz_id);

	    if ($quiz_question_answer === false) {
	        return new WP_Error('quiz_not_found', 'Quiz not found', array('status' => 404));
	    }

	    // Check if the user has taken the quiz and update the response accordingly
	    if ($user_attempt_result) {
	        $quiz_question_answer['user_attempt_status'] = $user_attempt_result;
	    }

	    // Check if the user has taken the quiz and update the response accordingly
	    $quiz_question = get_post_meta( $quiz_id, 'questions', true );
	    $quiz_question_array=explode(',', $quiz_question);
	    // var_dump($quiz_question_array);
	    $quiz_postmeta = get_question_answer_details_from_wp_postmeta($quiz_question_array);
	    if ($quiz_postmeta) {
	        $quiz_question_answer['Question_answers'] = $quiz_postmeta;
	    }

	    // Return the response as a JSON object
	    return new WP_REST_Response($quiz_question_answer, 200);
	}
	function get_question_answer_details_from_wp_postmeta($question_ids) {
	    global $wpdb;
	    // Initialize an array to store question details
	    $question_details = array();
	    $question_details_array=array();
	    // Loop through each question ID
	    foreach ($question_ids as $question_id) {
	        // Query wp_postmeta to get question details
	        // Initialize an array to store the formatted question data
	        $formatted_question = array();
					$question_content = get_post($question_id);
	        $answer_in_detail = get_post_meta( $question_id, 'answers', true );
	        $rest_of_question_details =  get_post_meta( $question_id, FALSE, TRUE);
	        // Add the formatted question data to the question details array

	        $question_details[$question_id][] = array(
							'Questions' => $question_content,
							'answers' => $answer_in_detail,
							'type' => $rest_of_question_details['type'],
							'question' => $rest_of_question_details['question'],
							'question_explanation' => $rest_of_question_details['question_explanation'],
							'question_hint' => $rest_of_question_details['question_hint'],
							'question_view_type' => $rest_of_question_details['question_view_type'],
							'image' => $rest_of_question_details['image']
	        );
	    }

	    return $question_details;
	}
//----------------------end


//-------------start quiz view result

	function register_quiz_api_view_result_endpoint() {
	    register_rest_route('custom-api/v1', '/quiz-view-result/(?P<user_id>\d+)/(?P<course_id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'get_quiz_view_result',
	    ));
	}
	add_action('rest_api_init', 'register_quiz_api_view_result_endpoint');



	function get_quiz_view_result($request) {

	    $user_id = $request['user_id'];
			$course_id= $request['course_id'];
	    if(empty($user_id)) {
	        return null;
        }

	    $data = array(
	        'course' => STM_LMS_Helpers::simplify_db_array(stm_lms_get_user_course($user_id, $course_id)),
            'curriculum' => array(),
            'course_completed' => false
        );

	    if((!empty($data['course']['progress_percent'])) && $data['course']['progress_percent'] > 100) $data['course']['progress_percent'] = 100;

	    /*Curriculum*/
        $curriculum = STM_LMS_Helpers::only_array_numbers(explode(',', get_post_meta($course_id, 'curriculum', true)));
        $curriculum_data = array();
        foreach($curriculum as $item_id) {
            $type = get_post_meta($item_id, 'type', true);
            if(empty($type)) $type = 'text';
            $lesson = STM_LMS_Lesson::get_lesson_info($curriculum, $item_id);
            $lesson['completed'] = STM_LMS_Lesson::is_lesson_completed($user_id, $course_id, $item_id);
            if($lesson['type'] === 'lesson') {
                $lesson_type = get_post_meta($item_id, 'type', true);
                if(empty($lesson_type)) $lesson_type = 'text';
                $lesson['lesson_type'] = $lesson_type;
            }
            $curriculum_data[] = $lesson;
        }

        foreach($curriculum_data as $item_data) {
            $type = ($item_data['type'] === 'lesson' && $item_data['lesson_type'] !== 'text') ? 'multimedia' : $item_data['type'];
            if(empty($data['curriculum'][$type])) $data['curriculum'][$type] = array(
                'total' => 0,
                'completed' => 0
            );

            $data['curriculum'][$type]['total']++;

            if($item_data['completed']) $data['curriculum'][$type]['completed']++;

        }

        $data['title'] = get_the_title($course_id);
        $data['url'] = get_permalink($course_id);

        if(empty($data['course'])) {
            $data['course'] = array(
                'progress_percent' => 0,
            );
            return $data;
        }

        /*Completed label*/
        $threshold = STM_LMS_Options::get_option('certificate_threshold', 70);
        $data['course_completed'] = intval($threshold) <= intval($data['course']['progress_percent']);
        $data['certificate_url'] = STM_LMS_Course::certificates_page_url($course_id);

	    return $data;

    }

//------------end


// -------------Start quiz-submite--- page REST API
  //{
		//   "6609": [
		//     "When ui-engine branch has incremental commit",
		//     "When code-engine branch has incremental commit"
		//   ],
		//   "6610": [
		//     "ui-engine branch",
		//     "code-engine branch"
		//   ],
		//   "6611": "False",
		//   "6612": "/src/packages/blocks/global_assets/",
		//   "6613": "[stm_lms_item_match]Account.uie.tsx[stm_lms_sep]Account.tsx[stm_lms_sep]Account_uie.tsx[stm_lms_sep]Account_ui_engine.tsx",
		//   "source": 0,
		//   "action": "stm_lms_user_answers",
		//   "quiz_id": 6608,
		//   "course_id": 6541,
		//   "user_id": 1
	// }

	function register_quiz_api_answers_submite_react_endpoint() {
	    register_rest_route('custom-api/v1', '/submit-quiz-answers', array(
	        'methods' => 'POST',
	        'callback' => 'user_answers_submite_react',
	    ));
	}
	add_action('rest_api_init', 'register_quiz_api_answers_submite_react_endpoint');

	function user_answers_submite_react($request){
    	 // Extract and process data from the POST request
   		 $data = $request->get_params();

         $source = (!empty($data['source'])) ? intval($data['source']) : '';
        $sequency = !empty($data['questions_sequency']) ? $data['questions_sequency'] : array();
         $sequency = json_encode($sequency);
        //$user = $data['user_id'];
        /*Checking Current User*/
         $user_id = $data['user_id'];
        $course_id = (!empty($data['course_id'])) ? intval($data['course_id']) : '';
         $course_id = apply_filters('user_answers__course_id', $course_id, $source);

        if (empty($course_id) or empty($data['quiz_id'])) die;
         $quiz_id = intval($data['quiz_id']);

        $progress = 0;
        $quiz_info = STM_LMS_Helpers::parse_meta_field($quiz_id);
        $total_questions = count(explode(',', $quiz_info['questions']));

        $questions = explode(',', $quiz_info['questions']);

        foreach ($questions as $question) {
            $type = get_post_meta($question, 'type', true);

            if ($type !== 'question_bank') continue;

            $answers = get_post_meta($question, 'answers', true);

            if (!empty($answers[0]) && !empty($answers[0]['categories']) && !empty($answers[0]['number'])) {
                $number = $answers[0]['number'];
                $categories = wp_list_pluck($answers[0]['categories'], 'slug');

                $questions = get_post_meta($quiz_id, 'questions', true);
                $questions = (!empty($questions)) ? explode(',', $questions) : array();

                $args = array(
                    'post_type' => 'stm-questions',
                    'posts_per_page' => $number,
                    'post__not_in' => $questions,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'stm_lms_question_taxonomy',
                            'field' => 'slug',
                            'terms' => $categories,
                        ),
                    )
                );

                $q = new WP_Query($args);

                if ($q->have_posts()) {

                    $total_in_bank = $q->found_posts - 1;
                    if($total_in_bank > $number) $total_in_bank = $number - 1;
                    $total_questions += $total_in_bank;
                    wp_reset_postdata();
                }
            }

        }


        $single_question_score_percent = 100 / $total_questions;
        $cutting_rate = (!empty($quiz_info['re_take_cut'])) ? (100 - $quiz_info['re_take_cut']) / 100 : 1;
        $passing_grade = (!empty($quiz_info['passing_grade'])) ? $quiz_info['passing_grade'] : 0;

        $user_quizzes = stm_lms_get_user_quizzes($user_id, $quiz_id, array('user_quiz_id', 'progress'));
        $attempt_number = count($user_quizzes) + 1;
        $prev_answers = ($attempt_number !== 1) ? stm_lms_get_user_answers($user_id, $quiz_id, $attempt_number - 1, true, array('question_id')) : array();

        foreach ($data as $question_id => $value) {
            if (is_numeric($question_id)) {
                $question_id = intval($question_id);

                if(is_array($value)) {
                    $answer = STM_LMS_Quiz::sanitize_answers($value);
                } else {
                    $answer = sanitize_text_field($value);
                }

                $user_answer = (is_array($answer)) ? implode(',', $answer) : $answer;

                $correct_answer = STM_LMS_Quiz::check_answer($question_id, $answer);

                if ($correct_answer) {
                    if ($attempt_number === 1 or STM_LMS_Helpers::in_array_r($question_id, $prev_answers)) {
                        $single_question_score = $single_question_score_percent;
                    } else {
                        $single_question_score = $single_question_score_percent * $cutting_rate;
                    }

                    $progress += $single_question_score;
                }

                $add_answer = compact('user_id', 'course_id', 'quiz_id', 'question_id', 'attempt_number', 'user_answer', 'correct_answer');
                stm_lms_add_user_answer($add_answer);
            }
        }

        /*Add user quiz*/
        $progress = round($progress, 5);
        $status = ($progress < $passing_grade) ? 'failed' : 'passed';
        $user_quiz = compact('user_id', 'course_id', 'quiz_id', 'progress', 'status', 'sequency');

        stm_lms_add_user_quiz($user_quiz);

        /*REMOVE TIMER*/
        stm_lms_get_delete_user_quiz_time($user_id, $quiz_id);

        if ($status == 'passed') STM_LMS_Course::update_course_progress($user_id, $course_id);
        $user_quiz['passed'] = $progress >= $passing_grade;
        $user_quiz['progress'] = round($user_quiz['progress'], 1);
        $user_quiz['url'] = '<a class="btn btn-default btn-close-quiz-modal-results" href="' . apply_filters('stm_lms_item_url_quiz_ended', STM_LMS_Course::item_url($course_id, $quiz_id)) . '">' . esc_html__('Close', 'masterstudy-lms-learning-management-system') . '</a>';
        $user_quiz['url'] = apply_filters('user_answers__course_url', $user_quiz['url'], $source);

        do_action('stm_lms_quiz_' . $status, $user_id, $quiz_id, $user_quiz['progress']);

        return new WP_REST_Response($user_quiz);
    }

//--------------end



//-------------start login-react with email and password 
	// Define the login endpoint
	function custom_login_api_login_endpoint($request) {
	     // Get user credentials from the request
	    $email = sanitize_email($request->get_param('email'));
	    $password = $request->get_param('password');

	    // Check if the email exists in the WordPress database
	    $user = get_user_by('email', $email);

	    if (!$user) {
	        // Email not found
	        return new WP_Error('email_not_found', 'Email not found.', array('status' => 404));
	    }

	    // Check if the provided password matches the user's password
	    if (!wp_check_password($password, $user->data->user_pass, $user->ID)) {
	        // Password is incorrect
	        return new WP_Error('password_incorrect', 'Password is incorrect.', array('status' => 401));
	    }

	    // User is authenticated, return user information
	    $user_info = array(
	        'user_id' => $user->ID,
	        'username' => $user->user_login,
	        'email' => $user->user_email,
	        // Add more user data as needed
	    );

	    return new WP_REST_Response($user_info, 200);
	}

	// Register the login endpoint
	function custom_login_api_register_endpoints() {
	    register_rest_route('custom-api/v1', '/login-react', array(
	        'methods' => 'POST',
	        'callback' => 'custom_login_api_login_endpoint',
	    ));
	}

	add_action('rest_api_init', 'custom_login_api_register_endpoints');

//--------------end






//-------------start quiz-average-score
	function custom_quiz_average_score_endpoint() {
	    register_rest_route('custom-api/v1', '/quiz-average-score/(?P<user_id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'calculate_quiz_average_score',
	    ));
	}
	add_action('rest_api_init', 'custom_quiz_average_score_endpoint');


	function calculate_quiz_average_score($request) {
	    $user_id = $request->get_param('user_id');
	    global $wpdb;

	    // Get all the courses assigned to the user
	    // $course_ids = $wpdb->get_col($wpdb->prepare(
	    //     "SELECT course_id FROM wp_stm_lms_user_courses WHERE user_id = %d",
	    //     $user_id
	    // ));
			$course_ids = $wpdb->get_col($wpdb->prepare(
			    "SELECT uc.course_id
			    FROM wp_stm_lms_user_courses AS uc
			    INNER JOIN wp_posts AS p ON uc.course_id = p.ID
			    WHERE uc.user_id = %d
			    AND p.post_status = 'publish'",
			    $user_id
			));

	    if (empty($course_ids)) {
	        return new WP_Error('no_assigned_courses', 'No courses assigned to the user.', array('status' => 404));
	    }

	    $totalScore = 0;
	    $quizCount = 0;

	    // Loop through each course
	    foreach ($course_ids as $course_id) {
	        // Check if the course has quizzes in its curriculum
	        $curriculum_items = get_post_meta($course_id, 'curriculum', true);
	        $course_ids_unique = explode(",", $curriculum_items);

	        foreach ($course_ids_unique as $item) {
	            if (preg_match('/^[0-9]+$/', $item)) {
	                // Check if the item is a quiz
	                $lesson_or_quiz = get_post($item);
	                if ($lesson_or_quiz) {
	                    $post_type = $lesson_or_quiz->post_type;
	                    if ($post_type === 'stm-quizzes') {
	                        // Check if the user has taken this quiz
	                        $hasTakenQuiz = $wpdb->get_var($wpdb->prepare(
	                            "SELECT COUNT(*) FROM wp_stm_lms_user_quizzes WHERE user_id = %d AND course_id = %d AND quiz_id = %d",
	                            $user_id,
	                            $course_id,
	                            $item
	                        ));

	                        if ($hasTakenQuiz > 0) {
	                            // Get the user's score for this quiz
	                            $quizScore = $wpdb->get_var($wpdb->prepare(
	                                "SELECT progress FROM wp_stm_lms_user_quizzes WHERE user_id = %d AND course_id = %d AND quiz_id = %d",
	                                $user_id,
	                                $course_id,
	                                $item
	                            ));
	                            $totalScore += $quizScore;
	                            $quizCount++;
	                        } else {
	                            // Default score (e.g., 0) for quizzes the user hasn't taken
	                            $totalScore += 0;
	                            $quizCount++;
	                        }
	                    }
	                }
	            }
	        }
	    }

	    if ($quizCount === 0) {
	        return new WP_Error('no_quiz_courses', 'No courses with quizzes found for the user.', array('status' => 404));
	    }

	    $averageScore = $totalScore / $quizCount;

	    return new WP_REST_Response(array(
	        'user_id' => $user_id,
	        'average_quiz_score' => round($averageScore, 2)
	    ), 200);
	}
//--------------end


//-------------start calculate_learning_path_and_quiz_average
	function calculate_learning_path_and_quiz_average($request) {
    $user_id = $request->get_param('user_id');
    global $wpdb;

    // Calculate learning path progress
    $learning_path_progress = 0;
    $total_assigned_courses = 0;
    $total_completed_courses = 0;

    // Get the progress of all assigned courses for the user
    // $courses_results = $wpdb->get_results($wpdb->prepare(
    //     "SELECT course_id, progress_percent FROM wp_stm_lms_user_courses WHERE user_id = %d",
    //     $user_id
    // ));
    $courses_results = $wpdb->get_results($wpdb->prepare(
			    "SELECT uc.course_id, uc.progress_percent
			    FROM wp_stm_lms_user_courses AS uc
			    INNER JOIN wp_posts AS p ON uc.course_id = p.ID
			    WHERE uc.user_id = %d
			    AND p.post_status = 'publish'",
			    $user_id
			));


    // Calculate the accumulative progress of all assigned courses
    foreach ($courses_results as $course) {
        $total_assigned_courses++;
        if ($course->progress_percent == 100) {
            $total_completed_courses++;
        }
    }

    // Calculate the learning path progress as a percentage
    if ($total_assigned_courses > 0) {
        $learning_path_progress = ($total_completed_courses / $total_assigned_courses) * 100;
    }

    // Calculate quiz average score
    $totalScore = 0;
    $quizCount = 0;

    // Get all the courses assigned to the user
    // $course_ids = $wpdb->get_col($wpdb->prepare(
    //     "SELECT course_id FROM wp_stm_lms_user_courses WHERE user_id = %d",
    //     $user_id
    // ));

    $course_ids = $wpdb->get_col($wpdb->prepare(
		    "SELECT uc.course_id
		    FROM wp_stm_lms_user_courses AS uc
		    INNER JOIN wp_posts AS p ON uc.course_id = p.ID
		    WHERE uc.user_id = %d
		    AND p.post_status = 'publish'",
		    $user_id
		));


    if (empty($course_ids)) {
        return new WP_Error('no_assigned_courses', 'No courses assigned to the user.', array('status' => 404));
    }

    // Loop through each course
    foreach ($course_ids as $course_id) {
        // Check if the course has quizzes in its curriculum
        $curriculum_items = get_post_meta($course_id, 'curriculum', true);
        $course_ids_unique = explode(",", $curriculum_items);

        foreach ($course_ids_unique as $item) {
            if (preg_match('/^[0-9]+$/', $item)) {
                // Check if the item is a quiz
                $lesson_or_quiz = get_post($item);
                if ($lesson_or_quiz) {
                    $post_type = $lesson_or_quiz->post_type;
                    if ($post_type === 'stm-quizzes') {
                        // Check if the user has taken this quiz
                        $hasTakenQuiz = $wpdb->get_var($wpdb->prepare(
                            "SELECT COUNT(*) FROM wp_stm_lms_user_quizzes WHERE user_id = %d AND course_id = %d AND quiz_id = %d",
                            $user_id,
                            $course_id,
                            $item
                        ));

                        if ($hasTakenQuiz > 0) {
                            // Get the user's score for this quiz
                            $quizScore = $wpdb->get_var($wpdb->prepare(
                                "SELECT progress FROM wp_stm_lms_user_quizzes WHERE user_id = %d AND course_id = %d AND quiz_id = %d",
                                $user_id,
                                $course_id,
                                $item
                            ));
                            $totalScore += $quizScore;
                            $quizCount++;
                        } else {
                            // Default score (e.g., 0) for quizzes the user hasn't taken
                            $totalScore += 0;
                            $quizCount++;
                        }
                    }
                }
            }
        }
    }

    if ($quizCount === 0) {
        //return new WP_Error('no_quiz_courses', 'No courses with quizzes found for the user.', array('status' => 404));
    	$averageScore=0;
    }else{

    	$averageScore = $totalScore / $quizCount;
		}
    // Create the response data
    $args_retrieve = array(
		    'post_type' => 'stm-courses', // Change to your actual post type name
		    'posts_per_page' => -1, // Retrieve all posts
		    'post_status' => 'publish', // Retrieve only published posts
		);

		$courses_retrieve = get_posts($args_retrieve);

		// Count the total number of published courses
		$total_published_courses = count($courses_retrieve);

    $response_data = array(
        'user_id' => $user_id,
         'total_assigned_courses' => $total_assigned_courses,
        'total_completed_courses' => $total_completed_courses,
        'learning_path_progress' => round($learning_path_progress, 2),
        'average_quiz_score' => round($averageScore, 2),
        'total_published_courses'=>$total_published_courses,
        'total_time_spent'=> intval(get_total_time_spent($user_id)),
    );

    // Return the response as a JSON response
    return new WP_REST_Response($response_data, 200);
	}

	// Register the combined API endpoint
	add_action('rest_api_init', function () {
	    register_rest_route('custom-api/v1', '/learning-path-progress-and-quiz-score/(?P<user_id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => 'calculate_learning_path_and_quiz_average',
	    ));
	});
//-------------end



// Manual assignment of courses from FE
	add_action('rest_api_init', function () {
	    register_rest_route('custom-api/v1', '/manual_assignment/(?P<course_id>\d+)/(?P<user_id>\d+)', array(
	        'methods' => 'POST',
	        'callback' => 'manual_assignment_course_function',
	    ));
	});

	function manual_assignment_course_function($request){
    $course_id = $request->get_param('course_id');
    $user_id = $request->get_param('user_id');

		  $get_post_lesson = get_post_meta($course_id, 'curriculum', true);
			$get_post_lesson_array=explode(",",$get_post_lesson);
			$post_first_lesson=null;
			if(!empty($get_post_lesson_array)){
				foreach($get_post_lesson_array as $post_lesson){
					if(is_numeric($post_lesson)){
						$post_first_lesson=$post_lesson;
						break;
					}
				}
			}else{
				return new WP_REST_Response("Course didn't have any lesson", 200);

			}
			global $wpdb;
			$get_course_user=$wpdb->get_results("SELECT user_id FROM wp_stm_lms_user_courses WHERE course_id='$course_id' AND user_id='$user_id'");
			if(empty($get_course_user) || $get_course_user == null){
				global $wpdb;
				$default_time_course=time();
				$sql = $wpdb->prepare("INSERT INTO wp_stm_lms_user_courses(user_id,course_id,current_lesson_id,progress_percent,status,start_time,instructor_id) VALUES (%s,%s,%s,'0','enrolled',%s,'0')", 
					$user_id,$course_id,$post_first_lesson,$default_time_course,);
				$wpdb->query($sql);

				return new WP_REST_Response("User added to course", 200);

			}else{
				return new WP_REST_Response("Already assigned", 200);
			}
	}


///8479/14758


function get_total_lessons_in_course($course_id) {
    $curriculum = get_post_meta($course_id, 'curriculum', true);

    // Check if curriculum data exists
    if (empty($curriculum)) {
        return 0; // No curriculum data found, so return 0 lessons.
    }

    $curriculum_items = explode(',', $curriculum);
    $lesson_count = 0;

    // Loop through curriculum items and count lessons
    foreach ($curriculum_items as $item) {
        if (is_numeric($item)) {
            $lesson_count++;
        }
    }

    return $lesson_count;
}


	add_action('rest_api_init', function () {
    register_rest_route('custom-api/v1', '/course-preview/(?P<user_id>\d+)/(?P<course_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_course_preview',
    ));
	});
	function get_course_preview($request) {
			global $wpdb;

	    $course_id = $request->get_param('course_id');
	    $user_id = $request->get_param('user_id');

	    // Check if the course ID is valid
	    if (empty($course_id) || !is_numeric($course_id)) {
	        return new WP_Error('invalid_course_id', 'Invalid course ID', array('status' => 400));
	    }

	    // Get the course data using $course_id, you can use your own method to retrieve the data.
	    $course_data = get_post($course_id);

	    // Check if the course exists
	    if (empty($course_data) || $course_data->post_type !== 'stm-courses') {
	        return new WP_Error('course_not_found', 'Course not found', array('status' => 404));
	    }
	    // Explode the curriculum meta_value to get individual items
	    $curriculum = get_post_meta($course_id, 'curriculum', true);
	    $curriculum_items = explode(',', $curriculum);

	    // Prepare an array to store curriculum data
	    $course_data_post_title=$course_data->post_title;
	    $course_data_post_content=$course_data->post_content;
	    $curriculum_data = array(
	        'course_title' =>$course_data_post_title,
	        'course_content' =>$course_data_post_content,
	        'sections' => array(),
	    );
	    $current_section = null;
	    $totalScore = 0;
	    $quizCount = 0;
	    // print_r($curriculum_items);
	    foreach ($curriculum_items as $item) {
	        if (preg_match("/[a-z]/i", $item) || empty($item)) {
	            // This is a section title
	            if ($current_section) {
	                $curriculum_data['sections'][] = $current_section;
	            }
	            $current_section = array(
	                'title' => trim($item),
	                'items' => array(),
	            );
	        } else if (preg_match('/^[0-9]+$/', $item) && $current_section) {
	        	// $lesson_or_quiz_title = get_the_title($item);
            // $completed = is_lesson_completed($item,$user_id); // Change this to your own function to check completion status
		        $lesson_or_quiz = get_post($item);

		        if ($lesson_or_quiz) {
		            $lesson_or_quiz_title = $lesson_or_quiz->post_title;
		            $post_type = $lesson_or_quiz->post_type;
		            if ($post_type === 'stm-lessons') {
									$completed = STM_LMS_Lesson::is_lesson_completed($user_id, $course_id, $item);

									$lesson_type_video = get_post_meta($item, 'type', true);

									// var_dump($lesson_type_video);
									if(!empty($lesson_type_video) && $lesson_type_video =='video'){
											$lesson_type_video_url = get_post_meta($item, 'lesson_video_url', true);
											$lesson_video_poster_url = get_post_meta($item, 'lesson_video_poster', true);


											$lesson_type='Video';
											$lesson_video_url=$lesson_type_video_url;
											$lesson_video_poster=stm_lms_get_image_url($lesson_video_poster_url, 'full');

									}elseif(!empty($lesson_type_video) && $lesson_type_video =='text'){
										$lesson_type = 'Lesson';
									}

		            } elseif ($post_type === 'stm-quizzes') {
		                $completed = is_quizzes_completed($item, $user_id);
		                $quiz_question = get_post_meta( $item, 'questions', true );
	    									$quiz_question_array=explode(',', $quiz_question);
		                	                    if ($post_type === 'stm-quizzes') {
	                        // Check if the user has taken this quiz
	                        $hasTakenQuiz = $wpdb->get_var($wpdb->prepare(
	                            "SELECT COUNT(*) FROM wp_stm_lms_user_quizzes WHERE user_id = %d AND course_id = %d AND quiz_id = %d",
	                            $user_id,
	                            $course_id,
	                            $item
	                        ));

	                        if ($hasTakenQuiz > 0) {
	                            // Get the user's score for this quiz
	                            $quizScore = $wpdb->get_var($wpdb->prepare(
	                                "SELECT progress FROM wp_stm_lms_user_quizzes WHERE user_id = %d AND course_id = %d AND quiz_id = %d",
	                                $user_id,
	                                $course_id,
	                                $item
	                            ));
	                            $totalScore += $quizScore;
	                            $quizCount++;
	                        } else {
	                            // Default score (e.g., 0) for quizzes the user hasn't taken
	                            $totalScore += 0;
	                            $quizCount++;
	                        }
	                    }
		                $lesson_type='Quiz';
		            } else {
		                // Handle other post types here
		                $completed = false;
		                $lesson_type='';

		            }
		            				
		            // if ($lesson_or_quiz_title) {
		            // 		if($lesson_type=="Video"){
			          //       $current_section['items'][] = array(
			          //           'id' => $item,
			          //           'title' => $lesson_or_quiz_title,
			          //           'completed' => $completed,
			          //           'lesson_type'=>$lesson_type,
			          //           'video_url'=>$lesson_video_url,
			          //           'video_poster'=>$lesson_video_poster,

			          //       );
		            // 		}else{
			          //       $current_section['items'][] = array(
			          //           'id' => $item,
			          //           'title' => $lesson_or_quiz_title,
			          //           'completed' => $completed,
			          //           'lesson_type'=>$lesson_type,
			          //       );
		            //    }
		            // }

		            	if ($lesson_or_quiz_title) {
		            		if($lesson_type=="Video"){
		            			if ($post_type === 'stm-quizzes') {
				                $current_section['items'][] = array(
				                    'id' => $item,
				                    'title' => $lesson_or_quiz_title,
				                    'completed' => $completed['completed'],
				                    'status'=> $completed['status'],
				                    'lesson_type'=>$lesson_type,
				                    'Questions_count'=>count($quiz_question_array),
				                    
				                    'video_url'=>$lesson_video_url,
				                    'video_poster'=>$lesson_video_poster,
				                    'total_ideal_time'=>$total_ideal_time
				                );
				              }else{
				                $current_section['items'][] = array(
				                    'id' => $item,
				                    'title' => $lesson_or_quiz_title,
				                    'completed' => $completed,
				                    'lesson_type'=>$lesson_type,
				                    'video_url'=>$lesson_video_url,
				                    'video_poster'=>$lesson_video_poster,
				                    'total_ideal_time'=>$total_ideal_time

				                );
				              }
		            		}else{
		            			if ($post_type === 'stm-quizzes') {
				                $current_section['items'][] = array(
				                    'id' => $item,
				                    'title' => $lesson_or_quiz_title,
				                    'completed' => $completed['completed'],
				                    'status'=> $completed['status'],
				                    'Questions_count'=>count($quiz_question_array),
				                    'lesson_type'=>$lesson_type,

				                );
				              }else{
				              	$current_section['items'][] = array(
				                    'id' => $item,
				                    'title' => $lesson_or_quiz_title,
				                    'completed' => $completed,
				                    'lesson_type'=>$lesson_type,
				                    'total_ideal_time'=>$total_ideal_time

				                );
				              }
		                }
		            }
			      }
	    		}
			}
	    if ($current_section) {
	        $curriculum_data['sections'][] = $current_section;
	    }

	    $course_progress = get_user_course_progress($user_id, $course_id);
	    $curriculum_data['course_progress'][] = $course_progress;
	    $course_level = get_post_meta($course_id, 'level', true);
	    $curriculum_data['course_level'][] = $course_level;
	    $query_enrolled_user = $wpdb->get_col(
			    $wpdb->prepare("SELECT user_id FROM wp_stm_lms_user_courses WHERE course_id = %d", $course_id)
			  );
	    $curriculum_data['enrolled_user'][] = count($query_enrolled_user);

	    $author_id = get_post_field('post_author', $course_id);
		  $author_name = get_the_author_meta('display_name', $author_id);
	    $curriculum_data['Course_author'][] = $author_name;
	    $total_lessons = get_total_lessons_in_course($course_id);

	    $curriculum_data['total_lessons'][] = $total_lessons;
	    if($totalScore){
				$averageScore = $totalScore / $quizCount;
			}else{
				$averageScore = 0;
			}
			$curriculum_data['averageScore'][] = round($averageScore, 2);
			$course_categories_list = wp_get_post_terms($course_id, 'stm_lms_course_taxonomy'); // Change to your custom taxonomy slug
			$category_names = wp_list_pluck($course_categories_list, 'name');
			$curriculum_data['category_names'][] =$category_names;
			$total_time=0;
			$course_ideal_time = get_post_meta($course_id, 'ideal_time', true);
	    $total_time += intval($course_ideal_time);
			$total_course_ideal_time=get_total_ideal_time_in_course($course_id);
			$total_time += intval($total_course_ideal_time);
			$curriculum_data['course_ideal_time'][] =$total_time;
	    $curriculum_data['time_spent'][]= get_post_meta($course_id, '_user_time_spent_' . $user_id, true);




	    // Return the curriculum data as a JSON response
	    return new WP_REST_Response($curriculum_data, 200);
	}











//------------ideal time

	// Calculate and save ideal_time based on post content
	function calculate_and_save_ideal_time($post_id) {
	    // Check if it's a 'stm-courses' or 'stm-lessons' post
	    $post_type = get_post_type($post_id);
	    if ($post_type === 'stm-courses' || $post_type === 'stm-lessons') {
	        // Get the post content
	        $post_content = get_post_field('post_content', $post_id);
	        
	        // Calculate the ideal_time based on content (example calculation)
	        $total_words = str_word_count(strip_tags($post_content));
	        $total_minutes = ceil($total_words / 25); // Assuming 100 words per minute

	        $lesson_type_video = get_post_meta($post_id, 'type', true);

					if(!empty($lesson_type_video) && $lesson_type_video =='video' && $post_type === 'stm-lessons'){
							$lesson_type_video_url = get_post_meta($post_id, 'lesson_video_url', true);
							$attachment_id = attachment_url_to_postid($lesson_type_video_url);
							if ($attachment_id) {
									$total_minutes += intval(get_video_length($attachment_id));
							} 
				  }
				  // Save the calculated ideal_time as post meta
	        update_post_meta($post_id, 'ideal_time', $total_minutes);//die();
	    }
	}

	function get_video_length($attachment_id) {
	    $attachment_metadata = wp_get_attachment_metadata($attachment_id);

	    if (isset($attachment_metadata['length_formatted'])) {
	        return $attachment_metadata['length_formatted'];
	    } else {
	        return '0';
	    }
	}


	add_action('save_post', 'calculate_and_save_ideal_time');


// Add a custom meta box to the edit screen for 'stm-courses' and 'stm-lessons'
function add_ideal_time_meta_box() {
    $post_types = array('stm-courses', 'stm-lessons');
    foreach ($post_types as $post_type) {
        add_meta_box(
            'ideal_time_meta_box',
            'Ideal Time',
            'render_ideal_time_meta_box',
            $post_type,
	        'advanced',
	        'high'
        );
    }
}
add_action('add_meta_boxes', 'add_ideal_time_meta_box');

// Render the content of the ideal_time meta box
function render_ideal_time_meta_box($post) {
    // Get the current ideal_time value
	  //var_dump($post);
    $ideal_time = get_post_meta($post->ID, 'ideal_time', true);
    
    // Output the input field for ideal_time
    ?>
    <label for="ideal_time">Ideal Time (minutes)</label>
    <input type="number" id="ideal_time" name="ideal_time" value="<?php echo esc_attr($ideal_time); ?>" style="width: 100%;">
    <?php
}

// // Save the ideal_time value when the post is updated
// function save_ideal_time_value($post_id) {
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
//     if (!current_user_can('edit_post', $post_id)) return;

//     if (isset($_POST['ideal_time'])) {
//         $ideal_time = sanitize_text_field($_POST['ideal_time']);
//         update_post_meta($post_id, 'ideal_time', $ideal_time);
//     }
// }
// add_action('save_post', 'save_ideal_time_value');

//----------end


function get_total_ideal_time_in_course($course_id) {
    $curriculum = get_post_meta($course_id, 'curriculum', true);

    // Check if curriculum data exists
    if (empty($curriculum)) {
        return 0; // No curriculum data found, so return 0 minutes.
    }

    $curriculum_items = explode(',', $curriculum);
    $total_ideal_time = 0;

    // Loop through curriculum items and calculate total ideal time
    foreach ($curriculum_items as $item) {
        if (is_numeric($item)) {
            // Assuming each item in the curriculum is a lesson ID
            $lesson_ideal_time = get_post_meta($item, 'ideal_time', true);
            $total_ideal_time += intval($lesson_ideal_time); // Add lesson's ideal time to total

        }
    }

    return $total_ideal_time;
}












//--------- start time-spent

	// Create a REST API endpoint to save time spent data
	add_action('rest_api_init', function () {
	    register_rest_route('custom-api/v1', '/save-time-spent', array(
	        'methods' => 'POST',
	        'callback' => 'save_time_spent_data',
	    ));
	});

	// Callback function to save time spent data
	function save_time_spent_data($request) {
	    $params = $request->get_params();

	    // Check if required parameters are present
	    if (empty($params['user_id']) || empty($params['course_id']) || empty($params['time_spent'])) {
	        return new WP_Error('missing_parameters', 'Required parameters are missing.', array('status' => 400));
	    }

	    $user_id = intval($params['user_id']);
	    $course_id = intval($params['course_id']);
	    $time_spent = floatval($params['time_spent']);

	    // Validate user and course IDs, and ensure time_spent is a positive value
	    if ($user_id <= 0 || $course_id <= 0 || $time_spent < 0) {
	        return new WP_Error('invalid_data', 'Invalid data provided.', array('status' => 400));
	    }

	    // Check if the post meta key exists
	    $current_time_spent = get_post_meta($course_id, '_user_time_spent_' . $user_id, true);

	    if ($current_time_spent === '') {
	        // If the key doesn't exist, update it with the new time spent value
	        update_post_meta($course_id, '_user_time_spent_' . $user_id, $time_spent);
	    } else {
	        // If the key exists, calculate the new time spent value by adding the current and new values
	        $new_time_spent = $current_time_spent + $time_spent;

	        // Update the post meta with the combined total
	        update_post_meta($course_id, '_user_time_spent_' . $user_id, $new_time_spent);
	    }


	    // Return a success response
	    return new WP_REST_Response(array('message' => 'Time spent data saved successfully.'), 200);
	}

	// Create a REST API endpoint to get total time spent by a user
	// add_action('rest_api_init', function () {
	//     register_rest_route('custom-api/v1', '/get-total-time-spent/(?P<user_id>\d+)', array(
	//         'methods' => 'GET',
	//         'callback' => 'get_total_time_spent',
	//     ));
	// });

	// Callback function to get total time spent by a user
	function get_total_time_spent($user_id) {
	    $user_id = intval($user_id);

	    if ($user_id <= 0) {
	        return new WP_Error('invalid_user_id', 'Invalid user ID.', array('status' => 400));
	    }

	    // Query all courses assigned to the user and calculate total time spent
	    global $wpdb;

	    $assigned_courses = $wpdb->get_results(
	        $wpdb->prepare("SELECT DISTINCT course_id FROM wp_stm_lms_user_courses WHERE user_id = %d", $user_id)
	    );

	    $total_time_spent = 0;

	    foreach ($assigned_courses as $assigned_course) {
	        $course_id = $assigned_course->course_id;
	        $user_time_spent = get_post_meta($course_id, '_user_time_spent_' . $user_id, true);

	        if (!empty($user_time_spent)) {
	            $total_time_spent += floatval($user_time_spent);
	        }
	    }

	    // Return the total time spent as a response
	    return $total_time_spent;
	}
// -------------end












// function custom_banner_shortcode() {


//     if (is_user_logged_in()) { 
//         // Get the current user's email address
//         $user = wp_get_current_user();
//         $email = $user->user_email;

//         // Extract the domain from the email
//         list($username, $domain) = explode('@', $email);

//         // Define the allowed email domains for displaying the banner
//         $allowed_domains = array('builder.ai', 'x.builder.ai'); // Add more domains as needed

//         // Check if the domain is in the allowed list
//         if (in_array($domain, $allowed_domains)) {
//             // Display the custom banner
//             echo '<div class="custom-banner">custom_banner_image</div>';
//         }
//     }

//     // If the user doesn't meet the criteria, return an empty string
//     return '';
// }
// add_shortcode('custom_banner', 'custom_banner_shortcode');




function save_user_feedback($request) {
    $parameters = $request->get_json_params();

    $user_id = $parameters['user_id'];
    $email = $parameters['email'];
    $feedback_comment = $parameters['feedback_comment'];
    $rating = $parameters['rating'];

    // Add user feedback to usermeta
    update_user_meta($user_id, 'feedback_email', $email);
    update_user_meta($user_id, 'feedback_comment', $feedback_comment);
    update_user_meta($user_id, 'feedback_rating', $rating);

    return new WP_REST_Response(array('message' => 'Feedback saved.'), 200);
}

function register_user_feedback_api() {
    register_rest_route('custom-api/v1', '/user-feedback', array(
        'methods' => 'POST',
        'callback' => 'save_user_feedback',
    ));
}
add_action('rest_api_init', 'register_user_feedback_api');

