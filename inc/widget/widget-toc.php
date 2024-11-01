<?php
defined( 'ABSPATH' ) || exit;
/**
 * Widget
 *
 * @package Table of contents Maker
 */


class Toc_Maker_Widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
			'toc_maker_widget', // Base ID
			esc_html__( '[Table of contents Maker] Table of contents', 'toc-maker' ), // Name
			array( 'description' => esc_html__( 'Support table of contents for Widget', 'toc-maker' ), ) // Args
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'toc_title'    => '',
			'sticky'    => false,
		);

		return $defaults;
	}

	public function widget( $args, $instance ) {

		$toc_set_query_var = get_query_var('toc_set_query_var');

		if ($toc_set_query_var === '') return;

		$settings = wp_parse_args( $instance, $this->default_settings() );

		
			
		
		echo $args['before_widget'];
		

		if ( $settings['toc_title'] ) {
			echo $args['before_title'] . esc_html($settings['toc_title']) . $args['after_title'];
		}

		echo '<div class="toc_maker_widget">';

  		$load_setting = get_option('toc_maker_settings');

  		if($load_setting['hierarchy']){
    		
    		toc_maker_widget_hierarchy($toc_set_query_var['the_content'],$toc_set_query_var['heading'],$load_setting['numerical'],$toc_set_query_var['toc_data'] );
  		}else{
    		
    		toc_maker_widget_non_hierarchy($toc_set_query_var['the_content'],$toc_set_query_var['heading'],$load_setting['numerical'],$toc_set_query_var['toc_data'] );
  		}
		echo '</div>';


		echo $args['after_widget'];

	}
	public function form( $instance ) {

		$settings = wp_parse_args( $instance, $this->default_settings() );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'toc_title' ) ); ?>"><?php esc_html_e( 'Title', 'toc-maker' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'toc_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'toc_title' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['toc_title'] ); ?>">
		</p>
<?php
		
			
			
				
			
		
		

	}

	public function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['toc_title'] = ! empty( $new_instance['toc_title'] ) ? sanitize_text_field( $new_instance['toc_title'] ) : '';
		$instance['sticky'] = ! empty( $new_instance['sticky'] ) ? (bool) $new_instance['sticky']  : false;
		return $instance;

	}

} // class toc_maker_widget

function toc_maker_register_widget(){
	register_widget( 'Toc_Maker_Widget' );
}

add_action( 'widgets_init', 'toc_maker_register_widget' );
