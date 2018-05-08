# Cherry X Interface Builder module

Module for building interfaces parts and UI elements.

## How to use:

1. Copy this module into your theme/plugin
2. Add path to `cherry-x-interface-builder.php` file to `CX_Loader` initialization.
3. Initialize module on `wp` hook or later, but earlier than `admin_enquque_scripts`, `wp_enqueue_scripts` with priority 10, Example:

```php
add_action( 'admin_enqueue_scripts', 'twentyseventeen_ui', 0 );
function twentyseventeen_ui() {
	global $twentyseventeen_loader;

	$builder_data = $twentyseventeen_loader->get_included_module_data( 'cherry-x-interface-builder.php' );

	$interface_builder = new CX_Interface_Builder( array(
		'path' => $builder_data['path'],
		'url'  => $builder_data['url'],
	) );
}
```

## Arguments:
`CX_Interface_Builder` accepts an array of options with next structure:
* `path` - path to module folder
* `url` - URL to module folder

This information moved to argumnets to get path and URL with apropriate functions for themes and plugins.

## Adding Elements

After initialization you need to register required interface elements to show. This need to be done not later than on hook `wp_enqueue_scripts` / `admin_enqueue_scripts` with priority 10.
In `CX_Interface_Builder` exists 6 different elemnts types:

### 1. Sections

Wrapper for large UI elemnts block, for example heading, form, footer

```php
add_action( 'admin_enquque_scripts', 'twentyseventeen_ui', 0 );
function twentyseventeen_ui() {

	global $twentyseventeen_loader;

	$builder_data = $twentyseventeen_loader->get_included_module_data( 'cherry-x-interface-builder.php' );

	$interface_builder = new CX_Interface_Builder( array(
		'path' => $builder_data['path'],
		'url'  => $builder_data['url'],
	) );

  $interface_builder->register_section(
		'options_section' => array(
			'type'        => 'section',
			'scroll'      => true,
			'title'       => __( 'Options Section', 'twentyseventeen' ),
			'description' => __( 'Options formed with Cherry X Interface Builder.', 'twentyseventeen' ),
		),
	);

}
```
### 2. Forms

HTML `<form>` element. All controls should be wrapped into this element. Example (all next examples are included int `twentyseventeen_ui` function context from previous eaxmple ):

```php
$interface_builder->register_form(
		'options_form' => array(
			'type'   => 'form',
			'parent' => 'options_section',
			'action' => admin_url( 'admin.php?action=process_my_form' ),
		),
);
```

Forms should be included into previously registered section. `parent` key in form arguments. The same for the other elements - them all must have a parent - section, form or settings.

### 3. Components

Interface components like a tabs, accordions etc.

```php
$interface_builder->register_component(
		'accordion' => array(
			'type'        => 'component-accordion',
			'parent'      => 'options_form',
			'title'       => esc_html__( 'Component accordion', 'twentyseventeen' ),
			'description' => esc_html__( 'Component Description', 'twentyseventeen' ),
		),
);
```
Allowed components types are:
* component-accordion
* component-toggle
* component-tab-vertical
* component-tab-horizontal

### 4. Settings

Wrapper for controls. Could be used as accordion, tab or toggle content or form canvas.

```php
$interface_builder->register_settings(
  array(
		'accordion_1' => array(
			'type'        => 'settings',
			'parent'      => 'accordion',
			'title'       => esc_html__( 'Accordion child #1', 'blank-plugin' ),
			'description' => esc_html__( 'First acordion child description.', 'blank-plugin' ),
		),
		'accordion_2' => array(
			'type'        => 'settings',
			'parent'      => 'accordion',
			'title'       => esc_html__( 'Accordion child #2', 'blank-plugin' ),
			'description' => esc_html__( 'Second acordion child description.', 'blank-plugin' ),
		),
  )
);
```

### 5. Controls

UI controls

```php
$interface_builder->register_controls(
  array(
		'checkbox_4' => array(
			'type'        => 'checkbox',
			'parent'      => 'accordion_1',
			'title'       => esc_html__( 'Checkbox', 'blank-plugin' ),
			'description' => esc_html__( 'Description checkbox.', 'blank-plugin' ),
			'class'       => '',
			'value'       => array(
				'checkbox' => 'true',
			),
			'options' => array(
				'checkbox' => esc_html__( 'Check Me', 'blank-plugin' ),
			),
		),
		'checkbox_multi_4' => array(
			'type'        => 'checkbox',
			'parent'      => 'accordion_1',
			'title'       => esc_html__( 'Multi Checkbox', 'blank-plugin' ),
			'description' => esc_html__( 'Description multi checkbox.', 'blank-plugin' ),
			'class'       => '',
			'value'       => array(
				'checkbox-0' => 'true',
				'checkbox-1' => 'false',
				'checkbox-2' => 'false',
				'checkbox-3' => 'true',
				'checkbox-4' => 'true',
			),
			'options' => array(
				'checkbox-0' => esc_html__( 'Check Me #1', 'blank-plugin' ),
				'checkbox-1' => esc_html__( 'Check Me #2', 'blank-plugin' ),
				'checkbox-2' => esc_html__( 'Check Me #3', 'blank-plugin' ),
				'checkbox-3' => esc_html__( 'Check Me #4', 'blank-plugin' ),
				'checkbox-4' => esc_html__( 'Check Me #5', 'blank-plugin' ),
			),
		),
		'colorpicker_4' => array(
			'type'        => 'colorpicker',
			'parent'      => 'accordion_1',
			'title'       => esc_html__( 'Color Picker', 'blank-plugin' ),
			'description' => esc_html__( 'Description color picker.', 'blank-plugin' ),
			'class'       => '',
			'value'       => '#3da3ce',
			'label'       => '',
		),
  )
);
```
### 6. HTML

Raw HTML code

```php
$cx_interface_builder->register_settings(
		array(
      'save_button' => array(
						'type'   => 'html',
						'parent' => 'settings_bottom',
						'class'  => 'cherry-control dialog-save',
						'html'   => '<button type="submit" class="button button-primary">' . esc_html__( 'Save', 'blank-plugin' ) . '</button>',
			),
    )
);
```
