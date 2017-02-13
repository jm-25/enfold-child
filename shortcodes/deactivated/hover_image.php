<?php
/**
 * Textblock
 * Shortcode which creates a text element wrapped in a div
 */
include_once 'aq_resizer.php';


function hover_image_js() {
    wp_enqueue_script( 'hoverdir', get_stylesheet_directory_uri() . '/shortcodes/js/jquery.hoverdir.js', array( 'jquery' ), '1.0', true );

}

add_action('wp_enqueue_scripts', 'hover_image_js');


if ( !class_exists( 'avia_sc_hover_image' ) )
{
	class avia_sc_hover_image extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['name']			= __('Hover Image', 'avia_framework' );
				$this->config['tab']			= __('Custom Elements', 'avia_framework' );
				$this->config['icon']			= get_stylesheet_directory_uri()."/shortcodes/images/sc-hover_image.png";
				$this->config['order']			= 100;
				$this->config['shortcode'] 		= 'av_hover_image';
				$this->config['tooltip'] 	    = __('Image with hover effect, text and button	', 'avia_framework' );
			}

			/**
			 * Popup Elements
			 *
			 * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
			 * opens a modal window that allows to edit the element properties
			 *
			 * @return void
			 */
			function popup_elements()
			{
				$this->elements = array(
					
					
					array(
							"name" 	=> __("Choose Image",'avia_framework' ),
							"desc" 	=> __("Min size 850 x 500 px",'avia_framework' ),
							"id" 	=> "src",
							"type" 	=> "image",
							"title" => __("Insert Image",'avia_framework' ),
							"button" => __("Insert",'avia_framework' ),
							"std" 	=> AviaBuilder::$path['imagesURL']."placeholder.jpg"),

					array(	
						"name" 	=> __("Heading Text", 'avia_framework' ),
						"desc" 	=> __("Add your heading here",'avia_framework' ),
						"id" 	=> "heading",
						"std" 	=> "HEADING",
						"type" 	=> "input"),

					array(
						"name" 	=> __("Subheading Text",'avia_framework' ),
						"desc" 	=> __("Add your subheading here (dont leave this empty)",'avia_framework' ),
						"id" 	=> "subheading",
						"type" 	=> "input",
						"std" 	=> "SUBHEADING"),   

/*
					array(	
						"name" 	=> __("Button Label", 'avia_framework' ),
						"desc" 	=> __("This is the text that appears on your button.", 'avia_framework' ),
            "id" 	=> "button",
            "type" 	=> "input",
            "std" => __("Click me", 'avia_framework' )),
*/
				            
			    array(	
						"name" 	=> __("Link?", 'avia_framework' ),
						"desc" 	=> __("Where should your button link to?", 'avia_framework' ),
						"id" 	=> "link",
						"type" 	=> "linkpicker",
						"fetchTMPL"	=> true,
						"subtype" => array(	
											__('Set Manually', 'avia_framework' ) =>'manually',
											__('Single Entry', 'avia_framework' ) =>'single',
											__('Taxonomy Overview Page',  'avia_framework' )=>'taxonomy',
											),
						"std" 	=> ""),



										
				);	
				
				
				
									
			}

			/**
			 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
			 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
			 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
			 *
			 *
			 * @param array $params this array holds the default values for $content and $args.
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_element($params)
			{
				$template = $this->update_template("src", "<img src='{{src}}' alt=''/>");
				//$img = wp_get_attachment_image( $params['args']['attachment'], 'portfolio' );
				$heading = $params['args']['heading'];
				$subheading = $params['args']['subheading'];


				$img	  = "";
				
				if(!empty($params['args']['attachment']))
				{
					$img = wp_get_attachment_image( $params['args']['attachment'], 'portfolio' );
				}
				else if(!empty($params['args']['src']))
				{
					$img = "<img src='".$params['args']['src']."' alt=''  />";
				}




				$params['innerHtml']  = "<div class='avia_image avia_image_style avia_hidden_bg_box'>";
				$params['innerHtml'] .= "<div ".$this->class_by_arguments('align' ,$params['args']).">";
				$params['innerHtml'] .= "<div class='avia_image_container' {$template}>{$img}</div>";
				$params['innerHtml'] .= "<div class='avia_helloworld_text'><strong>{$heading}</strong></div>";
				$params['innerHtml'] .= "<div class='avia_helloworld_text'>{$subheading}</div>";
				$params['innerHtml'] .= "</div>";
				$params['innerHtml'] .= "</div>";
				$params['class'] = "";

				return $params;
			}

			/**
			 * Frontend Shortcode Handler
			 *
			 * @param array $atts array of attributes
			 * @param string $content text within enclosing form of shortcode element
			 * @param string $shortcodename the shortcode found, when == callback name
			 * @return string $output returns the modified html string
			 */
			function shortcode_handler($atts, $content = "", $shortcodename = "", $meta = "")
			{
				$output = "";
				$class  = "";
				$alt 	= "";
				$title 	= "";

				$atts = shortcode_atts(
						array(	
								'src'=>'',
								'attachment'=>'',
								'attachment_size'=>'',					 
								'align' => 'center',
								'heading' => '',
								'subheading' => 'nosub',
								'button' => '',
								'link' => ''								
							), $atts, $this->config['shortcode']);
				
				extract($atts);
				

				$link = aviaHelper::get_url($link, $attachment);
				$src = wp_get_attachment_url( $attachment );
				$src = aq_resize($src,'600','600',true);
				
				ob_start(); ?>
				
					<div class="hover-image">
						<img class="avia_image " src="<?php echo $src ?>">
						<div class="overlay">	
							<div class="overlay-inner">
							<a href="<?php echo $link ?>">
								<h3 class="heading"><?php echo $heading ?></h3>
							</a>
							<p class="subheading" <?php echo ($subheading ? '':' style="opacity:0"') ?>><?php echo ($subheading ? $subheading:'-') ?></p>
							</div>
						</div>								
					</div>	
				<?php
				$output = ob_get_contents();
				ob_end_clean();
    
				return $output;
			}


	}
}








