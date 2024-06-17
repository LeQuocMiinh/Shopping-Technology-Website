<?php
function add_style_select_buttons($buttons)
{
    array_unshift($buttons, 'styleselect');
    return $buttons;
}
add_filter('mce_buttons_2', 'add_style_select_buttons');

add_filter('tiny_mce_before_init', 'boxes_plugin_that_add_style_formats_options', 20);

function boxes_plugin_that_add_style_formats_options($settings)
{

    /* Our Own Custom Options */
    $style_formats = array(
        array(
            'title'   => 'Button',
            'items' => array(
                array(
                    'title' => 'Button 1',
                    'block' => 'span',
                    'classes' => 'btn btn-sm animated-button victoria-one',
                    'wrapper' => true,
                ),
                array(
                    'title' => 'Button 2',
                    'block' => 'span',
                    'classes' => 'btn btn-sm animated-button victoria-two',
                    'wrapper' => true,
                ),
                array(
                    'title' => 'Button 3',
                    'block' => 'span',
                    'classes' => 'btn btn-sm animated-button victoria-three',
                    'wrapper' => true,
                ),
                array(
                    'title' => 'Button 4',
                    'block' => 'span',
                    'classes' => 'btn btn-sm animated-button victoria-four',
                    'wrapper' => true,
                ),
                array(
                    'title' => 'Button 5',
                    'block' => 'span',
                    'classes' => 'btn btn-sm animated-button victoria-four',
                    'wrapper' => true,
                ),
            ),
        ),
        array(
            'title'   => 'Line height',
            'items' => array(
                array(
                    'title' => '20',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '20px',
                    ),
                ),
                array(
                    'title' => '21',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '21px',
                    ),
                ),
                array(
                    'title' => '22',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '22px',
                    ),
                ),
                array(
                    'title' => '23',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '24px',
                    ),
                ),
                array(
                    'title' => '24',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '24px',
                    ),
                ),
                array(
                    'title' => '25',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '25px',
                    ),
                ),
                array(
                    'title' => '26',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '26px',
                    ),
                ),
                array(
                    'title' => '27',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '27px',
                    ),
                ),
                array(
                    'title' => '28',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '28px',
                    ),
                ),
                array(
                    'title' => '29',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '29px',
                    ),
                ),
                array(
                    'title' => '30',
                    'block' => 'span',
                    'styles' => array(
                        'line-height'         => '30px',
                    ),
                ),
            ),
        ),
        array(
            'title'   => 'Text',
            'items' => array(
                array(
                    'title' => 'Text Uppercase',
                    'block' => 'span',
                    'classes' => 'Text-Uppercase',
                    'wrapper' => true,
                ),
                array(
                    'title' => 'Text Capitalize',
                    'block' => 'span',
                    'classes' => 'Text-Capitalize',
                    'wrapper' => true,
                ),
                array(
                    'title' => 'Text Lowercase',
                    'block' => 'span',
                    'classes' => 'Text-Lowercase',
                    'wrapper' => true,
                ),
            ),
        ),
    );

    /* Add it in tinymce config as json data */
    $settings['style_formats'] = json_encode($style_formats);
    return $settings;
}
