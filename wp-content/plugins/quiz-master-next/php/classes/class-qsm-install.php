<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class that handles installation, updates, and plugin row meta
 *
 * @since 4.7.1
 */
class QSM_Install {

  /**
   * Main Constructor
   *
   * @uses QSM_Install::add_hooks
   * @since 4.7.1
   */
  function __construct() {
    $this->add_hooks();
  }

  /**
   * Adds the various class functions to hooks and filters
   *
   * @since 4.7.1
   */
  public function add_hooks() {
    add_action( 'admin_init', array( $this, 'update' ) );
    add_action( 'admin_init', array( $this, 'update' ) );
    add_filter( 'plugin_action_links_' . QSM_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
    add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
    add_action( 'plugins_loaded', array( $this, 'register_default_settings' ) );
  }

  /**
   * Adds the default quiz settings
   *
   * @since 5.0.0
   */
  public function register_default_settings() {

    global $mlwQuizMasterNext;
    
    // Registers system setting
    $field_array = array(
      'id' => 'form_type',
      'label' => __('Quiz Type', 'quiz-master-next'),
      'type' => 'select',
      'options' => array(
        array(
          'label' => __('Quiz', 'quiz-master-next'),
          'value' => 0
        ),
        array(
          'label' => __('Survey', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('Simple Form', 'quiz-master-next'),
          'value' => 2
        )
      ),
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    // Registers system setting
    $field_array = array(
      'id' => 'system',
      'label' => __('Grading System', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('Correct/Incorrect', 'quiz-master-next'),
          'value' => 0
        ),
        array(
          'label' => __('Points', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('Both', 'quiz-master-next'),
          'value' => 3
        )
      ),
      'default' => 0,      
      'help' => __('Select the system for grading the quiz.', 'quiz-master-next'),
      'tooltip' => __('To know more about our grading systems please ', 'quiz-master-next') . '<a target="_blank" href="https://quizandsurveymaster.com/docs/">'. __('read the documentation.', 'quiz-master-next') .'</a>',
      'show_option' => 'form_type_0'
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
	
	// Registers progress_bar setting
    $field_array = array(
		'id' => 'progress_bar',
		'label' => __( 'Show progress bar', 'quiz-master-next' ),
		'type' => 'radio',
		'options' => array(
		  array(
			'label' => __( 'Yes', 'quiz-master-next' ),
			'value' => 1
		  ),
		  array(
			'label' => __( 'No', 'quiz-master-next' ),
			'value' => 0
		  )
		),
		'default' => 0
	);
	$mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers require_log_in setting
    $field_array = array(
      'id' => 'require_log_in',
      'label' => __('Require User Login', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 0
        )
      ),
      'default' => 0,
      'help' => __('Enabling this allows only logged in users to take the quiz','quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );        

    // Registers pagination setting
    $field_array = array(
      'id' => 'pagination',
      'label' => __('Questions Per Page', 'quiz-master-next'),
      'type' => 'number',
      'options' => array(

      ),
      'default' => 0,
      'help' => __('Override the default pagination created on questions tab','quiz-master-next')  
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers timer_limit setting
    $field_array = array(
      'id' => 'timer_limit',
      'label' => __('Time Limit', 'quiz-master-next'),
      'type' => 'number',
      'options' => array(

      ),
      'default' => 0,
      'help' => __('Leave 0 for no time limit','quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    // Settings for quick result
    $field_array = array(
      'id' => 'enable_result_after_timer_end',
      'label' => __('Force submit after timer expiry', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(        
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 0
        )
      ),
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers total_user_tries setting
    $field_array = array(
      'id' => 'total_user_tries',
      'label' => __('Limit Attempts', 'quiz-master-next'),
      'type' => 'number',
      'options' => array(

      ),
      'default' => 0,
      'help' => __('Leave 0 for unlimited attempts','quiz-master-next'),
      'tooltip' => __('Limits how many times a user can take the quiz','quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers limit_total_entries setting
    $field_array = array(
      'id' => 'limit_total_entries',
      'label' => __('Limit Entries', 'quiz-master-next'),
      'type' => 'number',
      'options' => array(

      ),
      'default' => 0,
      'help' => __('Leave 0 for unlimited entries','quiz-master-next'),
      'tooltip' => __('Limits how many users can take the quiz.','quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers question_from_total setting
    $field_array = array(
      'id' => 'question_from_total',
      'label' => __('Limit number of Questions', 'quiz-master-next'),
      'type' => 'number',
      'options' => array(

      ),
      'default' => 0,
      'help' => __('Leave 0 to load all questions','quiz-master-next'),
      'tooltip' => __('Show only limited number of questions from your quiz.','quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers scheduled_time_start setting
    $field_array = array(
      'id' => 'scheduled_time_start',
      'label' => __('Quiz Dates', 'quiz-master-next'),
      'type' => 'date',
      'options' => array(

      ),
      'default' => '',
      'help' => '',
      'ph_text' => __('Start Date','quiz-master-next'),
      'help' => __('If set, Quiz will be accessible only after this date', 'quiz-master-next'),
      'tooltip' => __( 'Leave blank for no date limit', 'quiz-master-next' )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers scheduled_time_end setting
    $field_array = array(
      'id' => 'scheduled_time_end',
      'label' => '',
      'type' => 'date',
      'options' => array(

      ),
      'default' => '',
      'help' => __(' If set, Quiz will not be accessible after this date','quiz-master-next'),
      'ph_text' => __('End Date','quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers randomness_order setting
    $field_array = array(
      'id' => 'randomness_order',
      'label' => __('Random Questions', 'quiz-master-next'),
      'type' => 'select',
      'options' => array(
        array(
          'label' => __('Random Questions', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('Random Questions And Answers', 'quiz-master-next'),
          'value' => 2
        ),
        array(
          'label' => __('Random Answers', 'quiz-master-next'),
          'value' => 3
        ),
        array(
          'label' => __('Disabled', 'quiz-master-next'),
          'value' => 0
        )
      ),
      'default' => 0,
      'tooltip' => __('Randomize the order of questions or answers every time a quiz loads', 'quiz')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
	
	// Registers category setting
    $field_array = array(
		  'id' => 'randon_category',
		  'label' => __('Random Questions Categories', 'quiz-master-next'),
		  'type' => 'category',
           'default' => '',
        'help' => __('Questions will load only from selected categories', 'quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
	
    // Registers contact_info_location setting
    $field_array = array(
      'id' => 'contact_info_location',
      'label' => __('Contact form position', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('Show before quiz begins', 'quiz-master-next'),
          'value' => 0
        ),
        array(
          'label' => __('Show after the quiz ends', 'quiz-master-next'),
          'value' => 1
        )
      ),
      'default' => 0,
      'help' => __('Select when to display the contact form','quiz-master-next'),
      'tooltip' => __('The form can be configured in Contact tab','quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers loggedin_user_contact setting
    $field_array = array(
      'id' => 'loggedin_user_contact',
      'label' => __('Show contact form to logged in users', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 0
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 1
        )
      ),
      'default' => 0,
      'help' => __('Logged in users can edit their contact information','quiz-master-next'),
      'tooltip' => __('The information will still get saved if this option is disabled','quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers comment_section setting
    $field_array = array(
      'id' => 'comment_section',
      'label' => __('Enable comments', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 0
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 1
        )
      ),
      'default' => 1,
      'help' => __('Allow users to enter their comments after the quiz','quiz-master-next'),      
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers question_numbering setting
    $field_array = array(
      'id' => 'question_numbering',
      'label' => __('Show question numbers', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 0
        )
      ),
      'default' => 0
    );
	$mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
	
	// Registers store_responses setting
    $field_array = array(
		'id' => 'store_responses',
            'label' => __( 'Save Responses', 'quiz-master-next' ),
		'type' => 'radio',
		'options' => array(
		  array(
			'label' => __( 'Yes', 'quiz-master-next' ),
			'value' => 1
		  ),
		  array(
			'label' => __( 'No', 'quiz-master-next' ),
			'value' => 0
		  )
		),
            'default' => 1,
            'help' => __('The results will be permanently stored in a database', 'quiz-master-next')
	);
	$mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers disable_answer_onselect setting
    $field_array = array(
      'id' => 'disable_answer_onselect',
      'label' => __('Disable change of answers', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 0
        )
      ),
      'default' => 0,
        'help' => __('Works with multiple choice questions only','quiz-master-next'),
        'tooltip' => __('The question will be disabled once an answer is selected','quiz-master-next'),
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers ajax_show_correct setting
    $field_array = array(
      'id' => 'ajax_show_correct',
      'label' => __('Add class for correct/incorrect answers', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 0
        )
      ),
      'default' => 0,
        'help' => __('Works with multiple choice questions only','quiz-master-next'),
        'tooltip' => __('Dynamically add class for incorrect/correct answer after user selects answer.','quiz-master-next'),
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    // Registers hide_auto fill setting
    $field_array = array(
      'id' => 'contact_disable_autofill',
      'label' => __('Disable auto fill for contact input', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(        
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
            'label' => __('No', 'quiz-master-next'),
            'value' => 0
        ),
      ),
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    // Registers show category on front setting
    $field_array = array(
      'id' => 'show_category_on_front',
      'label' => __('Display category name on front end', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(        
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 0
        ),  
      ),
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    // Settings for quick result
    $field_array = array(
      'id' => 'enable_quick_result_mc',
      'label' => __('Show results inline', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(        
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 0
        )  
      ),
      'default' => 0,
      'help' => __('Instantly displays the result for each question','quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    //Setting for retake quiz
    $field_array = array(
      'id' => 'enable_retake_quiz_button',
      'label' => __('Retake Quiz', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(        
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
            'label' => __('No', 'quiz-master-next'),
            'value' => 0
        ),
      ),
      'default' => 0,
        'help' => __('Show a button on result page to retake the quiz', 'quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    //Setting for pagination of quiz
    $field_array = array(
      'id' => 'enable_pagination_quiz',
      'label' => __('Show current page number', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(        
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 0
        ),
      ),
      'default' => 0,      
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    //Setting for pagination of quiz
    $field_array = array(
      'id' => 'enable_deselect_option',
      'label' => __('Deselect Answer', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(        
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 0
        ),
      ),
      'default' => 0,
      'help' => __('Users are able deselect an answer and leave it blank. Works with Multiple Choice and Horizintal Multiple Choice questions only','quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    //Setting for animation
    $field_array = array(
      'id' => 'quiz_animation',
      'label' => __('Quiz Animation', 'quiz-master-next'),
      'type' => 'select',
      'options' => array(
        array(
          'label' => __('bounce', 'quiz-master-next'),
          'value' => 'bounce'
        ),
        array(
          'label' => __('flash', 'quiz-master-next'),
          'value' => 'flash'
        ),
        array(
          'label' => __('pulse', 'quiz-master-next'),
          'value' => 'pulse'
        ),
        array(
          'label' => __('rubberBand', 'quiz-master-next'),
          'value' => 'rubberBand'
        ),
        array(
          'label' => __('shake', 'quiz-master-next'),
          'value' => 'shake'
        ),
        array(
          'label' => __('swing', 'quiz-master-next'),
          'value' => 'swing'
        ),
        array(
          'label' => __('tada', 'quiz-master-next'),
          'value' => 'tada'
        ),
        array(
          'label' => __('wobble', 'quiz-master-next'),
          'value' => 'wobble'
        ),
        array(
          'label' => __('jello', 'quiz-master-next'),
          'value' => 'jello'
        ),
        array(
          'label' => __('heartBeat', 'quiz-master-next'),
          'value' => 'heartBeat'
        ),
        array(
          'label' => __('No animation', 'quiz-master-next'),
          'value' => ''
        )
      ),
      'default' => ''
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    // result page for sharing
    $field_array = array(
      'id' => 'result_page_fb_image',
      'label' => __('Logo URL', 'quiz-master-next'),
      'type' => 'text',
      'default' => QSM_PLUGIN_URL . 'assets/icon-200x200.png',
      'tooltip' => __('Enter the url of an image which will be used as logo while sharing on facebook.', 'quiz-master-next'),
      'help' => __('If left blank, this will default to QSM logo', 'quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    //Setting for animation
    $field_array = array(
      'id' => 'legacy_options',
      'label' => __('Show Legacy Options', 'quiz-master-next'),
      'type' => 'hide_show',
      'default' => '',
       'help' => __('All the legacy options are deprecated and will be removed in upcoming version', 'quiz-master-next' ),
       //'tooltip' => __('All the legacy options are deprecated and will be removed in upcoming version', 'quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    // Registers social_media setting
    $field_array = array(
      'id' => 'social_media',
      'label' => __('Social Sharing Buttons', 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 1
        ),
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 0
        )
      ),
      'default' => 0,
       'tooltip' => __('Please use the new template variables instead.%FACEBOOK_SHARE% %TWITTER_SHARE%', 'quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    // Registers user_name setting
    $field_array = array(
      'id' => 'user_name',
      'label' => __("Ask user's name", 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 2
        ),  
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 0
        ),
        array(
          'label' => __('Require', 'quiz-master-next'),
          'value' => 1
        ),
        
      ),
      'default' => 2
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers user_comp setting
    $field_array = array(
      'id' => 'user_comp',
      'label' => __("Ask user's business", 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 2
        ),  
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 0
        ),
        array(
          'label' => __('Require', 'quiz-master-next'),
          'value' => 1
        )
      ),
      'default' => 2
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers user_email setting
    $field_array = array(
      'id' => 'user_email',
      'label' => __("Ask user's email", 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 2
        ),
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 0
        ),
        array(
          'label' => __('Require', 'quiz-master-next'),
          'value' => 1
        )
      ),
      'default' => 2
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );

    // Registers user_phone setting
    $field_array = array(
      'id' => 'user_phone',
      'label' => __("Ask user's phone", 'quiz-master-next'),
      'type' => 'radio',
      'options' => array(
        array(
          'label' => __('No', 'quiz-master-next'),
          'value' => 2
        ),
        array(
          'label' => __('Yes', 'quiz-master-next'),
          'value' => 0
        ),
        array(
          'label' => __('Require', 'quiz-master-next'),
          'value' => 1
        )
      ),
      'default' => 2
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_options' );
    
    // Registers message_before setting
    $field_array = array(
      'id' => 'message_before',
      'label' =>  __("Message Displayed Before Quiz", 'quiz-master-next'),
      'type' => 'editor',
      'default' => 0,
      'variables' => array(
        '%QUIZ_NAME%',
        '%CURRENT_DATE%'
      )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers message_comment setting
    $field_array = array(
      'id' => 'message_comment',
      'label' =>  __("Message Displayed Before Comments Box If Enabled", 'quiz-master-next'),
      'type' => 'editor',
      'default' => 0,
      'variables' => array(
        '%QUIZ_NAME%',
        '%CURRENT_DATE%'
      )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers message_end_template setting
    $field_array = array(
      'id' => 'message_end_template',
      'label' =>  __("Message Displayed At End Of Quiz (Leave Blank To Omit Text Section)", 'quiz-master-next'),
      'type' => 'editor',
      'default' => 0,
      'variables' => array(
        '%QUIZ_NAME%',
        '%CURRENT_DATE%'
      )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers total_user_tries_text setting
    $field_array = array(
      'id' => 'total_user_tries_text',
      'label' =>  __("Message Displayed If User Has Tried Quiz Too Many Times", 'quiz-master-next'),
      'type' => 'editor',
      'default' => 0,
      'variables' => array(
        '%QUIZ_NAME%',
        '%CURRENT_DATE%'
      )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers require_log_in_text setting
    $field_array = array(
      'id' => 'require_log_in_text',
      'label' =>  __("Message Displayed If User Is Not Logged In And Quiz Requires Users To Be Logged In", 'quiz-master-next'),
      'type' => 'editor',
      'default' => 0,
      'variables' => array(
        '%QUIZ_NAME%',
        '%CURRENT_DATE%'
      )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers scheduled_timeframe_text setting
    $field_array = array(
      'id' => 'scheduled_timeframe_text',
      'label' =>  __("Message Displayed If Date Is Outside Scheduled Timeframe", 'quiz-master-next'),
      'type' => 'editor',
      'default' => 0,
      'variables' => array(
        '%QUIZ_NAME%',
        '%CURRENT_DATE%'
      )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers limit_total_entries_text setting
    $field_array = array(
      'id' => 'limit_total_entries_text',
      'label' =>  __("Message Displayed If The Limit Of Total Entries Has Been Reached", 'quiz-master-next'),
      'type' => 'editor',
      'default' => 0,
      'variables' => array(
        '%QUIZ_NAME%',
        '%CURRENT_DATE%'
      )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers question_answer_template setting
    $field_array = array(
      'id' => 'question_answer_template',
      'label' =>  __("%QUESTIONS_ANSWERS% Text", 'quiz-master-next'),
      'type' => 'editor',
      'default' => 0,
      'variables' => array(
        '%QUESTION%',
        '%USER_ANSWER%',
        '%CORRECT_ANSWER%',
        '%USER_COMMENTS%',
        '%CORRECT_ANSWER_INFO%'
      )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers twitter_sharing_text setting
    $field_array = array(
      'id' => 'twitter_sharing_text',
      'label' =>  __("Twitter Sharing Text", 'quiz-master-next'),
      'type' => 'editor',
      'default' => 0,
      'variables' => array(
        '%POINT_SCORE%',
        '%AVERAGE_POINT%',
        '%AMOUNT_CORRECT%',
        '%TOTAL_QUESTIONS%',
        '%CORRECT_SCORE%',
        '%QUIZ_NAME%',
        '%TIMER%',
        '%CURRENT_DATE%'
      )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers facebook_sharing_text setting
    $field_array = array(
      'id' => 'facebook_sharing_text',
      'label' =>  __("Facebook Sharing Text", 'quiz-master-next'),
      'type' => 'editor',
      'default' => 0,
      'variables' => array(
        '%POINT_SCORE%',
        '%AVERAGE_POINT%',
        '%AMOUNT_CORRECT%',
        '%TOTAL_QUESTIONS%',
        '%CORRECT_SCORE%',
        '%QUIZ_NAME%',
        '%TIMER%',
        '%CURRENT_DATE%'
      )
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers submit_button_text setting
    $field_array = array(
      'id' => 'button_section',
      'label' => __('Buttons', 'quiz-master-next'),
      'type' => 'section_heading',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );
    
    $field_array = array(
      'id' => 'submit_button_text',
      'label' => __('Submit Button', 'quiz-master-next'),
      'type' => 'text',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );
    
    // Registers previous_button_text setting
    $field_array = array(
      'id' => 'previous_button_text',
      'label' => __('Previous button', 'quiz-master-next'),
      'type' => 'text',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers next_button_text setting
    $field_array = array(
      'id' => 'next_button_text',
      'label' => __('Next button', 'quiz-master-next'),
      'type' => 'text',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );    
    
    // Registers submit_button_text setting
    $field_array = array(
      'id' => 'validation_text_section',
      'label' => __('Validation Messages', 'quiz-master-next'),
      'type' => 'section_heading',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );
    
    // Registers empty_error_text setting
    $field_array = array(
      'id' => 'empty_error_text',
      'label' => __('All required fields', 'quiz-master-next'),
      'type' => 'text',
      'default' => 'Please complete all required fields!'
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers email_error_text setting
    $field_array = array(
      'id' => 'email_error_text',
      'label' => __('Invalid email', 'quiz-master-next'),
      'type' => 'text',
      'default' => 'Not a valid e-mail address!'
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers number_error_text setting
    $field_array = array(
      'id' => 'number_error_text',
      'label' => __('Invalid number', 'quiz-master-next'),
      'type' => 'text',
      'default' => 'This field must be a number!'
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers incorrect_error_text setting
    $field_array = array(
      'id' => 'incorrect_error_text',
      'label' => __('Invalid Captcha', 'quiz-master-next'),
      'type' => 'text',
      'default' => 'The entered text is not correct!'
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );        
    
    // Registers submit_button_text setting
    $field_array = array(
      'id' => 'other_text_section',
      'label' => __('Other', 'quiz-master-next'),
      'type' => 'section_heading',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );
    
    // Registers comment_field_text setting
    $field_array = array(
      'id' => 'comment_field_text',
      'label' => __('Comments field', 'quiz-master-next'),
      'type' => 'text',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );
    
    // Registers hint_text setting
    $field_array = array(
      'id' => 'hint_text',
      'label' => __('Hint Text', 'quiz-master-next'),
      'type' => 'text',
      'default' => 'Hint'
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );
           
    //Setting for animation
    $field_array = array(
      'id' => 'legacy_options',
      'label' => __('Show Legacy Options', 'quiz-master-next'),
      'type' => 'hide_show',
      'default' => '',
       'help' => __('All the legacy options are deprecated and will be removed in upcoming version', 'quiz-master-next' ),
       //'tooltip' => __('All the legacy options are deprecated and will be removed in upcoming version', 'quiz-master-next')
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );
    
    // Registers name_field_text setting
    $field_array = array(
      'id' => 'name_field_text',
      'label' => __('Name field', 'quiz-master-next'),
      'type' => 'text',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers business_field_text setting
    $field_array = array(
      'id' => 'business_field_text',
      'label' => __('Business field', 'quiz-master-next'),
      'type' => 'text',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers email_field_text setting
    $field_array = array(
      'id' => 'email_field_text',
      'label' => __('Email field', 'quiz-master-next'),
      'type' => 'text',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );

    // Registers phone_field_text setting
    $field_array = array(
      'id' => 'phone_field_text',
      'label' => __('Phone number field', 'quiz-master-next'),
      'type' => 'text',
      'default' => 0
    );
    $mlwQuizMasterNext->pluginHelper->register_quiz_setting( $field_array, 'quiz_text' );
   
  }

  /**
   * Installs the plugin and its database tables
   *
   * @since 4.7.1
   */
  public static function install() {

    global $wpdb;
  	$charset_collate = $wpdb->get_charset_collate();

  	$quiz_table_name = $wpdb->prefix . "mlw_quizzes";
  	$question_table_name = $wpdb->prefix . "mlw_questions";
  	$results_table_name = $wpdb->prefix . "mlw_results";
  	$audit_table_name = $wpdb->prefix . "mlw_qm_audit_trail";

  	if( $wpdb->get_var( "SHOW TABLES LIKE '$quiz_table_name'" ) != $quiz_table_name ) {
  		$sql = "CREATE TABLE $quiz_table_name (
  			quiz_id mediumint(9) NOT NULL AUTO_INCREMENT,
  			quiz_name TEXT NOT NULL,
  			message_before TEXT NOT NULL,
  			message_after TEXT NOT NULL,
  			message_comment TEXT NOT NULL,
  			message_end_template TEXT NOT NULL,
  			user_email_template TEXT NOT NULL,
  			admin_email_template TEXT NOT NULL,
  			submit_button_text TEXT NOT NULL,
  			name_field_text TEXT NOT NULL,
  			business_field_text TEXT NOT NULL,
  			email_field_text TEXT NOT NULL,
  			phone_field_text TEXT NOT NULL,
  			comment_field_text TEXT NOT NULL,
  			email_from_text TEXT NOT NULL,
  			question_answer_template TEXT NOT NULL,
  			leaderboard_template TEXT NOT NULL,
  			quiz_system INT NOT NULL,
  			randomness_order INT NOT NULL,
  			loggedin_user_contact INT NOT NULL,
  			show_score INT NOT NULL,
  			send_user_email INT NOT NULL,
  			send_admin_email INT NOT NULL,
  			contact_info_location INT NOT NULL,
  			user_name INT NOT NULL,
  			user_comp INT NOT NULL,
  			user_email INT NOT NULL,
  			user_phone INT NOT NULL,
  			admin_email TEXT NOT NULL,
  			comment_section INT NOT NULL,
  			question_from_total INT NOT NULL,
  			total_user_tries INT NOT NULL,
  			total_user_tries_text TEXT NOT NULL,
  			certificate_template TEXT NOT NULL,
  			social_media INT NOT NULL,
  			social_media_text TEXT NOT NULL,
  			pagination INT NOT NULL,
  			pagination_text TEXT NOT NULL,
  			timer_limit INT NOT NULL,
  			quiz_stye TEXT NOT NULL,
  			question_numbering INT NOT NULL,
  			quiz_settings TEXT NOT NULL,
  			theme_selected TEXT NOT NULL,
  			last_activity DATETIME NOT NULL,
  			require_log_in INT NOT NULL,
  			require_log_in_text TEXT NOT NULL,
  			limit_total_entries INT NOT NULL,
  			limit_total_entries_text TEXT NOT NULL,
  			scheduled_timeframe TEXT NOT NULL,
  			scheduled_timeframe_text TEXT NOT NULL,
  			disable_answer_onselect INT NOT NULL,
  			ajax_show_correct INT NOT NULL,
  			quiz_views INT NOT NULL,
  			quiz_taken INT NOT NULL,
  			deleted INT NOT NULL,
                        quiz_author_id INT NOT NULL,
  			PRIMARY KEY  (quiz_id)
  		) $charset_collate;";

  		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  		dbDelta( $sql );
  	}

  	if( $wpdb->get_var( "SHOW TABLES LIKE '$question_table_name'" ) != $question_table_name ) {
  		$sql = "CREATE TABLE $question_table_name (
  			question_id mediumint(9) NOT NULL AUTO_INCREMENT,
  			quiz_id INT NOT NULL,
  			question_name TEXT NOT NULL,
  			answer_array TEXT NOT NULL,
  			answer_one TEXT NOT NULL,
  			answer_one_points INT NOT NULL,
  			answer_two TEXT NOT NULL,
  			answer_two_points INT NOT NULL,
  			answer_three TEXT NOT NULL,
  			answer_three_points INT NOT NULL,
  			answer_four TEXT NOT NULL,
  			answer_four_points INT NOT NULL,
  			answer_five TEXT NOT NULL,
  			answer_five_points INT NOT NULL,
  			answer_six TEXT NOT NULL,
  			answer_six_points INT NOT NULL,
  			correct_answer INT NOT NULL,
  			question_answer_info TEXT NOT NULL,
  			comments INT NOT NULL,
  			hints TEXT NOT NULL,
  			question_order INT NOT NULL,
  			question_type INT NOT NULL,
  			question_type_new TEXT NOT NULL,
  			question_settings TEXT NOT NULL,
  			category TEXT NOT NULL,
  			deleted INT NOT NULL,
  			PRIMARY KEY  (question_id)
  		) $charset_collate;";

  		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  		dbDelta( $sql );
  	}

  	if( $wpdb->get_var( "SHOW TABLES LIKE '$results_table_name'" ) != $results_table_name ) {
  		$sql = "CREATE TABLE $results_table_name (
  			result_id mediumint(9) NOT NULL AUTO_INCREMENT,
  			quiz_id INT NOT NULL,
  			quiz_name TEXT NOT NULL,
  			quiz_system INT NOT NULL,
  			point_score INT NOT NULL,
  			correct_score INT NOT NULL,
  			correct INT NOT NULL,
  			total INT NOT NULL,
  			name TEXT NOT NULL,
  			business TEXT NOT NULL,
  			email TEXT NOT NULL,
  			phone TEXT NOT NULL,
  			user INT NOT NULL,
  			user_ip TEXT NOT NULL,
  			time_taken TEXT NOT NULL,
  			time_taken_real DATETIME NOT NULL,
  			quiz_results MEDIUMTEXT NOT NULL,
  			deleted INT NOT NULL,
                        unique_id varchar(255) NOT NULL,
                        form_type INT NOT NULL,
  			PRIMARY KEY  (result_id)
  		) $charset_collate;";

  		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  		dbDelta( $sql );
  	}

  	if( $wpdb->get_var( "SHOW TABLES LIKE '$audit_table_name'" ) != $audit_table_name ) {
  		$sql = "CREATE TABLE $audit_table_name (
  			trail_id mediumint(9) NOT NULL AUTO_INCREMENT,
  			action_user TEXT NOT NULL,
  			action TEXT NOT NULL,
  			time TEXT NOT NULL,
  			PRIMARY KEY  (trail_id)
  		) $charset_collate;";

  		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  		dbDelta( $sql );
	  }
	
	global $mlwQuizMasterNext;
	$mlwQuizMasterNext->register_quiz_post_types();
	flush_rewrite_rules();
  }

  /**
   * Updates the plugin
   *
   * @since 4.7.1
   */
  public function update() {
    global $mlwQuizMasterNext;
  	$data = $mlwQuizMasterNext->version;
  	if ( ! get_option( 'qmn_original_version' ) ) {
  		add_option( 'qmn_original_version', $data );
    }
  	if ( get_option( 'mlw_quiz_master_version' ) != $data ) {
  		global $wpdb;
  		$table_name = $wpdb->prefix . "mlw_quizzes";
  		//Update 0.5
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'comment_section'") != "comment_section")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD comment_field_text TEXT NOT NULL AFTER phone_field_text";
  			$results = $wpdb->query( $sql );
  			$sql = "ALTER TABLE ".$table_name." ADD comment_section INT NOT NULL AFTER admin_email";
  			$results = $wpdb->query( $sql );
  			$sql = "ALTER TABLE ".$table_name." ADD message_comment TEXT NOT NULL AFTER message_after";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET comment_field_text='Comments', comment_section=1, message_comment='Enter You Text Here'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 0.9.4
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'randomness_order'") != "randomness_order")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD randomness_order INT NOT NULL AFTER system";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET randomness_order=0";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 0.9.5
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_answer_template'") != "question_answer_template")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD question_answer_template TEXT NOT NULL AFTER comment_field_text";
  			$results = $wpdb->query( $sql );
  			$mlw_question_answer_default = "%QUESTION%<br /> Answer Provided: %USER_ANSWER%<br /> Correct Answer: %CORRECT_ANSWER%<br /> Comments Entered: %USER_COMMENTS%<br />";
  			$update_sql = "UPDATE ".$table_name." SET question_answer_template='".$mlw_question_answer_default."'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 0.9.6
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'contact_info_location'") != "contact_info_location")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD contact_info_location INT NOT NULL AFTER send_admin_email";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET contact_info_location=0";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 1.0
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'email_from_text'") != "email_from_text")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD email_from_text TEXT NOT NULL AFTER comment_field_text";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET email_from_text='Wordpress'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 1.3.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'loggedin_user_contact'") != "loggedin_user_contact")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD loggedin_user_contact INT NOT NULL AFTER randomness_order";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET loggedin_user_contact=0";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 1.5.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_from_total'") != "question_from_total")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD question_from_total INT NOT NULL AFTER comment_section";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET question_from_total=0";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 1.6.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'total_user_tries'") != "total_user_tries")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD total_user_tries INT NOT NULL AFTER question_from_total";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET total_user_tries=0";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'total_user_tries_text'") != "total_user_tries_text")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD total_user_tries_text TEXT NOT NULL AFTER total_user_tries";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET total_user_tries_text='Enter Your Text Here'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 1.8.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'message_end_template'") != "message_end_template")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD message_end_template TEXT NOT NULL AFTER message_comment";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET message_end_template=''";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'certificate_template'") != "certificate_template")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD certificate_template TEXT NOT NULL AFTER total_user_tries_text";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET certificate_template='Enter your text here!'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 1.9.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'social_media'") != "social_media")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD social_media INT NOT NULL AFTER certificate_template";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET social_media='0'";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'social_media_text'") != "social_media_text")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD social_media_text TEXT NOT NULL AFTER social_media";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET social_media_text='I just score a %CORRECT_SCORE%% on %QUIZ_NAME%!'";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'pagination'") != "pagination")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD pagination INT NOT NULL AFTER social_media_text";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET pagination=0";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'pagination_text'") != "pagination_text")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD pagination_text TEXT NOT NULL AFTER pagination";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET pagination_text='Next'";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'timer_limit'") != "timer_limit")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD timer_limit INT NOT NULL AFTER pagination_text";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET timer_limit=0";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 2.1.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'quiz_stye'") != "quiz_stye")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD quiz_stye TEXT NOT NULL AFTER timer_limit";
  			$results = $wpdb->query( $sql );
  			$mlw_style_default = "
  				div.mlw_qmn_quiz input[type=radio],
  				div.mlw_qmn_quiz input[type=submit],
  				div.mlw_qmn_quiz label {
  					cursor: pointer;
  				}
  				div.mlw_qmn_quiz input:not([type=submit]):focus,
  				div.mlw_qmn_quiz textarea:focus {
  					background: #eaeaea;
  				}
  				div.mlw_qmn_quiz {
  					text-align: left;
  				}
  				div.quiz_section {

  				}
  				div.mlw_qmn_timer {
  					position:fixed;
  					top:200px;
  					right:0px;
  					width:130px;
  					color:#00CCFF;
  					border-radius: 15px;
  					background:#000000;
  					text-align: center;
  					padding: 15px 15px 15px 15px
  				}
  				div.mlw_qmn_quiz input[type=submit],
  				a.mlw_qmn_quiz_link
  				{
  					    border-radius: 4px;
  					    position: relative;
  					    background-image: linear-gradient(#fff,#dedede);
  						background-color: #eee;
  						border: #ccc solid 1px;
  						color: #333;
  						text-shadow: 0 1px 0 rgba(255,255,255,.5);
  						box-sizing: border-box;
  					    display: inline-block;
  					    padding: 5px 5px 5px 5px;
     						margin: auto;
  				}";
  			$update_sql = "UPDATE ".$table_name." SET quiz_stye='".$mlw_style_default."'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 2.2.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_numbering'") != "question_numbering")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD question_numbering INT NOT NULL AFTER quiz_stye";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET question_numbering='0'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 2.8.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'quiz_settings'") != "quiz_settings")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD quiz_settings TEXT NOT NULL AFTER question_numbering";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET quiz_settings=''";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 3.0.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'theme_selected'") != "theme_selected")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD theme_selected TEXT NOT NULL AFTER quiz_settings";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET theme_selected='default'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 3.3.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'last_activity'") != "last_activity")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD last_activity DATETIME NOT NULL AFTER theme_selected";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET last_activity='".date("Y-m-d H:i:s")."'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 3.5.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'require_log_in'") != "require_log_in")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD require_log_in INT NOT NULL AFTER last_activity";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET require_log_in='0'";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'require_log_in_text'") != "require_log_in_text")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD require_log_in_text TEXT NOT NULL AFTER require_log_in";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET require_log_in_text='Enter Text Here'";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'limit_total_entries'") != "limit_total_entries")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD limit_total_entries INT NOT NULL AFTER require_log_in_text";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET limit_total_entries='0'";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'limit_total_entries_text'") != "limit_total_entries_text")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD limit_total_entries_text TEXT NOT NULL AFTER limit_total_entries";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET limit_total_entries_text='Enter Text Here'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 3.7.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'scheduled_timeframe'") != "scheduled_timeframe")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD scheduled_timeframe TEXT NOT NULL AFTER limit_total_entries_text";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET scheduled_timeframe=''";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'scheduled_timeframe_text'") != "scheduled_timeframe_text")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD scheduled_timeframe_text TEXT NOT NULL AFTER scheduled_timeframe";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET scheduled_timeframe_text='Enter Text Here'";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 4.3.0
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'disable_answer_onselect'") != "disable_answer_onselect")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD disable_answer_onselect INT NOT NULL AFTER scheduled_timeframe_text";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET disable_answer_onselect=0";
  			$results = $wpdb->query( $update_sql );
  		}
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'ajax_show_correct'") != "ajax_show_correct")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD ajax_show_correct INT NOT NULL AFTER disable_answer_onselect";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET ajax_show_correct=0";
  			$results = $wpdb->query( $update_sql );
  		}

  		global $wpdb;
  		$table_name = $wpdb->prefix . "mlw_questions";
  		//Update 0.5
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'comments'") != "comments")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD comments INT NOT NULL AFTER correct_answer";
  			$results = $wpdb->query( $sql );
  			$sql = "ALTER TABLE ".$table_name." ADD hints TEXT NOT NULL AFTER comments";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET comments=1, hints=''";
  			$results = $wpdb->query( $update_sql );
  		}
  		//Update 0.8
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_order'") != "question_order")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD question_order INT NOT NULL AFTER hints";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET question_order=0";
  			$results = $wpdb->query( $update_sql );
  		}

  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_type'") != "question_type")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD question_type INT NOT NULL AFTER question_order";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET question_type=0";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 1.1.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_answer_info'") != "question_answer_info")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD question_answer_info TEXT NOT NULL AFTER correct_answer";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET question_answer_info=''";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 2.5.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'answer_array'") != "answer_array")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD answer_array TEXT NOT NULL AFTER question_name";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET answer_array=''";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 3.1.1
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_settings'") != "question_settings")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD question_settings TEXT NOT NULL AFTER question_type";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET question_settings=''";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 4.0.0
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'category'") != "category")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD category TEXT NOT NULL AFTER question_settings";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET category=''";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 4.0.0
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'question_type_new'") != "question_type_new")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD question_type_new TEXT NOT NULL AFTER question_type";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET question_type_new=question_type";
  			$results = $wpdb->query( $update_sql );
  		}

  		//Update 2.6.1
  		$results = $wpdb->query( "ALTER TABLE ".$wpdb->prefix . "mlw_qm_audit_trail CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;" );
  		$results = $wpdb->query( "ALTER TABLE ".$wpdb->prefix . "mlw_questions CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci" );
  		$results = $wpdb->query( "ALTER TABLE ".$wpdb->prefix . "mlw_quizzes CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci" );
  		$results = $wpdb->query( "ALTER TABLE ".$wpdb->prefix . "mlw_results CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci" );


  		global $wpdb;
  		$table_name = $wpdb->prefix . "mlw_results";
  		//Update 2.6.4
  		if($wpdb->get_var("SHOW COLUMNS FROM ".$table_name." LIKE 'user'") != "user")
  		{
  			$sql = "ALTER TABLE ".$table_name." ADD user INT NOT NULL AFTER phone";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE ".$table_name." SET user=0";
  			$results = $wpdb->query( $update_sql );
  		}

  		// Update 4.7.0
  		if( $wpdb->get_var( "SHOW COLUMNS FROM $table_name LIKE 'user_ip'" ) != "user_ip" ) {
  			$sql = "ALTER TABLE $table_name ADD user_ip TEXT NOT NULL AFTER user";
  			$results = $wpdb->query( $sql );
  			$update_sql = "UPDATE $table_name SET user_ip='Unknown'";
  			$results = $wpdb->query( $update_sql );
  		}

      // Update 5.0.0
      $settings = (array) get_option( 'qmn-settings', array() );
      if ( ! isset( $settings['results_details_template'] ) ) {
        $settings['results_details_template'] = "<h2>Quiz Results for %QUIZ_NAME%</h2>
     		<p>%CONTACT_ALL%</p>
     		<p>Name Provided: %USER_NAME%</p>
     		<p>Business Provided: %USER_BUSINESS%</p>
     		<p>Phone Provided: %USER_PHONE%</p>
     		<p>Email Provided: %USER_EMAIL%</p>
     		<p>Score Received: %AMOUNT_CORRECT%/%TOTAL_QUESTIONS% or %CORRECT_SCORE%% or %POINT_SCORE% points</p>
     		<h2>Answers Provided:</h2>
     		<p>The user took %TIMER% to complete quiz.</p>
     		<p>Comments entered were: %COMMENT_SECTION%</p>
     		<p>The answers were as follows:</p>
         %QUESTIONS_ANSWERS%";
         update_option( 'qmn-settings' , $settings );
      }
      
  		update_option('mlw_quiz_master_version' , $data);
  	}
  	if ( ! get_option('mlw_advert_shows') ) {
  		add_option( 'mlw_advert_shows' , 'true' );
  	}
  }

  /**
   * Adds new links to the plugin action links
   *
   * @since 4.7.1
   */
  public function plugin_action_links( $links ) {
    $action_links = array(
      'settings' => '<a href="' . admin_url( 'admin.php?page=mlw_quiz_list' ) . '" title="' . esc_attr( __( 'Quizzes/Surveys', 'quiz-master-next' ) ) . '">' . __( 'Quizzes/Surveys', 'quiz-master-next' ) . '</a>',
    );
    return array_merge( $action_links, $links );
  }

  /**
   * Adds new links to the plugin row meta
   *
   * @since 4.7.1
   */
  public function plugin_row_meta( $links, $file ) {
    if ( $file == QSM_PLUGIN_BASENAME ) {
      $row_meta = array(
        'docs'    => '<a href="' . esc_url( 'https://quizandsurveymaster.com/docs/' ) . '" title="' . esc_attr( __( 'View Documentation', 'quiz-master-next' ) ) . '">' . __( 'Documentation', 'quiz-master-next' ) . '</a>',
        'support' => '<a href="' . admin_url( 'admin.php?page=qsm_quiz_help' ) . '" title="' . esc_attr( __( 'Create Support Ticket', 'quiz-master-next' ) ) . '">' . __( 'Support', 'quiz-master-next' ) . '</a>',
      );
      return array_merge( $links, $row_meta );
    }

    return (array) $links;

  }
}

$qsm_install = new QSM_Install();

?>