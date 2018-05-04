<?php

GFForms::include_addon_framework();

class GFCustomStyle extends GFAddOn
{
	protected $_version = GF_CUSTOMSTYLE_VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'gf-custom-style';
	protected $_path = 'gf-custom-style/gf-custom-style.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Float Labels Add-On';
	protected $_short_title = 'Custom Style';

	private static $_instance = null;

	public static function get_instance()
	{
		if ( self::$_instance === null ) {
			self::$_instance = new GFCustomStyle();
		}

		return self::$_instance;
	}

	public function scripts()
	{
		if ( is_admin() ) {
			parent::scripts();
		}

		$scripts = array(
			array(
				'handle'    => 'gf_customstyle_js',
				'src'       => $this->get_base_url() . '/js/gf_customstyle.js',
				'version'   => filemtime( $this->get_base_path() . '/js/gf_customstyle.js' ),
				'deps'      => array( 'jquery', 'wp-color-picker' ),
				'enqueue'   => array( array() ),
				'in_footer' => true,
			),
		);

		return array_merge( parent::scripts(), $scripts );
	}

	public function styles()
	{
		$styles = array(
			array(
				'handle'  => 'gf_customstyle_css',
				'src'     => $this->get_base_url() . '/css/gf_customstyle.css',
				'version' => $this->_version,
				'deps'    => array( 'wp-color-picker' ),
				'enqueue' => array( array() ),
			),
		);

		return array_merge( parent::styles(), $styles );
	}

	public function init()
	{
		parent::init();
		add_filter( 'gform_field_css_class', array( $this, 'add_classes' ), 10, 3 );
		add_shortcode( 'gform_field', array( $this, 'gform_field_shortcode' ) );
	}

	public function add_classes( $classes, $field, $form )
	{
		if ( $this->is_enabled( $form, $field ) ) {
			$entry = GFFormsModel::get_current_lead();

			$classes .= ' gfield_customstyle';
			$classes .= " gfield_type_{$field->type}";

			if ( empty( rgar( $entry, $field->id ) ) ) {
				return $classes;
			}

			$classes .= ' gfield_filled';
		}

		return $classes;
	}

	function gfcustom_field_settings($position, $form_id){

		// Get the form
		$form = GFAPI::get_form($form_id);

		// Get form settings
		$settings = $this->get_form_settings($form);

		// Show below everything else
		if($position == -1){ ?>

			<li class="list_setting">
				gform List
				<br>
				<input type="checkbox" id="field_list_value" onclick="SetFieldProperty('stickylistField', this.checked);" /><label class="inline" for="field_list_value"><?php _e('Show in list', 'sticky-list'); ?> <?php gform_tooltip("form_field_list_value") ?></label>
				<br>
				<input type="checkbox" id="field_nowrap_value" onclick="SetFieldProperty('stickylistFieldNoWrap', this.checked);" /><label class="inline" for="field_nowrap_value"><?php _e('Don\'t wrap text from this field', 'sticky-list'); ?> <?php gform_tooltip("form_field_nowrap_value") ?></label>
				<br>
				<input type="checkbox" id="field_nopopulate_value" onclick="SetFieldProperty('stickylistFieldNoPopulate', this.checked);" /><label class="inline" for="field_nopopulate_value"><?php _e('Don\'t populate this field on edit', 'sticky-list'); ?> <?php gform_tooltip("form_field_nopopulate_value") ?></label>
				<br>
				<label class="inline" for="field_list_text_value"><?php _e('Column label', 'sticky-list'); ?> <?php gform_tooltip("form_field_text_value") ?></label><br><input class="fieldwidth-3" type="text" id="field_list_text_value" onkeyup="SetFieldProperty('stickylistFieldLabel', this.value);" />
			</li>

			<?php
		}
	}

	function gform_field_shortcode( $atts ) {
		$shortcode_id = shortcode_atts( array(
			'id'            => '1',
			'user'          => '',
			'showto'        => '',
			'field'         => '',
			'value'         => '',
			'test'          => ''
		), $atts );

		// Get the form ID from shortcode
		$form_id = $shortcode_id['id'];
	}

	public function form_settings_fields( $form )
	{
		return array(
			array(
				'title'  => esc_html__( 'Custom Style Settings', 'gf-custom-style' ),
				'fields' => array(
				),
			),

			// Configuracao dos input do formulario
			array(
				'title'  => esc_html__( 'Input', 'gf-custom-style' ),
				'fields' => array(
					array(
						'label'   => esc_html__( 'background-color', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'background-color',
						'id'      => 'input-bg-color',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'focus', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'focus',
						'id'      => 'input-focus',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'height input', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'height-input',
						'id'      => 'input-height',
						'class'   => 'cs-size',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),

					array(
						'label'   => esc_html__( 'height textarea', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'height-textarea',
						'id'      => 'textarea-height',
						'class'   => 'cs-size',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),

					array(
						'label'   => esc_html__( 'border-color', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'border-color',
						'id'      => 'input-border-color',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'border-radius', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'border-radius',
						'id'      => 'input-border-radius',
						'class'   => 'cs-size',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),

					array(
						'label'   => esc_html__( 'border-width', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'border-width',
						'id'      => 'input-border-width',
						'class'   => 'cs-size',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),
				),
			),

			// Configuracao das labels do formulario
			array(
				'title'  => esc_html__( 'Label', 'gf-custom-style' ),
				'fields' => array(
					array(
						'label'   => esc_html__( 'color', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'label-color',
						'id'      => 'label-color',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'font-size', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'label-font-size',
						'id'      => 'label-size',
						'class'   => 'cs-size',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),

					array(
						'label'   => esc_html__( 'text-tranform', 'gf-custom-style' ),
						'type'    => 'select',
						'name'    => 'label-text-tranform',
						'id'      => 'label-text-tranform',
						'choices' => array(
							array(
								'label' => esc_html__( 'initial', 'gf-custom-style' ),
								'name'  => 'select-label-initial',
							),
							array(
								'label' => esc_html__( 'capitalize', 'gf-custom-style' ),
								'name'  => 'select-label-capitalize',
							),
							array(
								'label' => esc_html__( 'lowercase', 'gf-custom-style' ),
								'name'  => 'select-label-lowercase',
							),
							array(
								'label' => esc_html__( 'uppercase', 'gf-custom-style' ),
								'name'  => 'select-label-uppercase',
							),
						),
					),

					array(
						'type'    => 'checkbox',
						'name'    => 'label-checkbox',
						'id'      => 'label-disable',
						'choices' => array(
							array(
								'label' => esc_html__( 'disable', 'gf-custom-style' ),
								'name'  => 'check-disable',
							),
						),
					),
				),
			),

			// Configuracao dos textos do formulario
			array(
				'title'  => esc_html__( 'Text', 'gf-custom-style' ),
				'fields' => array(
					array(
						'label'   => esc_html__( 'color', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'text-color',
						'id'      => 'text-color',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'placeholder', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'text-placeholder',
						'id'      => 'text-placeholder',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'font-size', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'text-font-size',
						'id'      => 'text-size',
						'class'   => 'cs-size',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),

					array(
						'label'   => esc_html__( 'text-tranform', 'gf-custom-style' ),
						'type'    => 'select',
						'name'    => 'text-text-tranform',
						'id'      => 'text-text-tranform',
						'choices' => array(
							array(
								'label' => esc_html__( 'initial', 'gf-custom-style' ),
								'name'  => 'text-text-initial',
							),
							array(
								'label' => esc_html__( 'capitalize', 'gf-custom-style' ),
								'name'  => 'text-text-capitalize',
							),
							array(
								'label' => esc_html__( 'lowercase', 'gf-custom-style' ),
								'name'  => 'text-text-lowercase',
							),
							array(
								'label' => esc_html__( 'uppercase', 'gf-custom-style' ),
								'name'  => 'text-text-uppercase',
							),
						),
					),
				),
			),

			// Configuracao do botão do formulario
			array(
				'title'  => esc_html__( 'BUTTON', 'gf-custom-style' ),
				'fields' => array(
					array(
						'label'   => esc_html__( 'background-color', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'btn-background-color',
						'id'      => 'btn-bg-color',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'background-color ::hover', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'btn-background-colo-hover',
						'id'      => 'btn-bg-color-hover',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'color', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'btn-text-color',
						'id'      => 'btn-text-color',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'color ::hover', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'btn-text-color-hover',
						'id'      => 'btn-text-color-hover',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'font-size', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'btn-font-size',
						'id'      => 'btn-size',
						'class'   => 'cs-size',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),

					array(
						'label'   => esc_html__( 'padding', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'btn-padding',
						'id'      => 'btn-padding',
						'class'   => 'cs-size',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),

					array(
						'label'   => esc_html__( 'width', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'btn-width',
						'id'      => 'btn-width',
						'class'   => 'cs-size',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),

					array(
						'label'   => esc_html__( 'position', 'gf-custom-style' ),
						'type'    => 'radio',
						'name'    => 'btn-position',
						'id'      => 'btn-padding',
						'class'   => 'cs-size',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'left', 'gf-custom-style' ),
								'id'    => 'btn-position-left',
								'name'  => 'btn-position-left',
							),
							array(
								'label' => esc_html__( 'center', 'gf-custom-style' ),
								'id'    => 'btn-position-center',
								'name'  => 'btn-position-center',
							),
							array(
								'label' => esc_html__( 'right', 'gf-custom-style' ),
								'id'    => 'btn-position-right',
								'name'  => 'btn-position-right',
							),
						),
					),

					array(
						'label'   => esc_html__( 'text-tranform', 'gf-custom-style' ),
						'type'    => 'select',
						'name'    => 'btn-text-tranform',
						'id'      => 'btn-text-tranform',
						'choices' => array(
							array(
								'label' => esc_html__( 'initial', 'gf-custom-style' ),
								'name'  => 'btn-text-initial',
							),
							array(
								'label' => esc_html__( 'capitalize', 'gf-custom-style' ),
								'name'  => 'btn-text-capitalize',
							),
							array(
								'label' => esc_html__( 'lowercase', 'gf-custom-style' ),
								'name'  => 'btn-text-lowercase',
							),
							array(
								'label' => esc_html__( 'uppercase', 'gf-custom-style' ),
								'name'  => 'btn-text-uppercase',
							),
						),
					),
				),
			),

			// Configuracao quando ocorre erro ou sucesso do formulario
			array(
				'title'  => esc_html__( 'Notifications', 'gf-custom-style' ),
				'fields' => array(
					array(
						'label'   => esc_html__( 'color error', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'validation-error',
						'id'      => 'validation-error',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-custom-style' ),
								'name'  => 'enabled',
							),
						),
					),

					array(
						'label'   => esc_html__( 'color sucess', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'validation-sucess',
						'id'      => 'validation-sucess',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),

					array(
						'label'   => esc_html__( 'input background error', 'gf-custom-style' ),
						'type'    => 'text',
						'name'    => 'validation-input-error',
						'id'      => 'validation-input-error',
						'class'   => 'colorpicker',
						'tooltip' => esc_html__( 'You can enable this funcionality for each form.', 'gf-custom-style' ),
					),
				),
			),
		);
	}

	public function is_enabled_form( $form )
	{
		$settings = $this->get_form_settings( $form );
		return ( isset( $settings['enabled'] ) && $settings['enabled'] );
	}

	public function is_enabled_field( $field )
	{
		return ! in_array( $field->type, array(
			'select',
			'multiselect',
			'checkbox',
			'radio',
			'hidden',
			'html',
			'section',
			'name',
			'time',
			'address',
			'fileupload',
			'captcha',
			'list',
		), true );
	}

	public function is_enabled( $form, $field )
	{
		return ( $this->is_enabled_form( $form ) && $this->is_enabled_field( $field ) );
	}
}
