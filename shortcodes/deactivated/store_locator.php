<?php
/**
 * Slider
 * Shortcode that allows to display a simple slideshow
 */
function print_r2($val){
        echo '<pre>';
        print_r($val);
        echo  '</pre>';
}



				
function custom_gmaps_scripts() {
    wp_enqueue_script( 'google-maps', 'http://maps.google.com/maps/api/js?key='.avia_get_option('gmap_api'), array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'custom_gmaps_scripts' );			



if ( !class_exists( 'custom_gmaps' ) ) 
{
	class custom_gmaps extends aviaShortcodeTemplate
	{
			static $map_count = 0;
			static $js_vars   = array();
			
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['name']			= __('Store Locator', 'avia_framework' );
				$this->config['tab']			= __('Custom Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-maps.png";
				$this->config['order']			= 5;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'custom_google_map';
				$this->config['shortcode_nested'] = array('av_gmap_location');
				$this->config['tooltip'] 	    = __('Display a google map with one or multiple locations', 'avia_framework' );
				$this->config['drag-level'] 	= 3;
			}
			
			
			function extra_assets()
			{
				if(is_admin() && isset($_POST['action']) && $_POST['action'] == "avia_ajax_av_google_map" )
				{
					$prefix  = is_ssl() ? "https" : "http";
		            $api_key = avia_get_option('gmap_api');
		            $api_url = $prefix.'://maps.google.com/maps/api/js?v=3.24';
		            
		            if($api_key != ""){
			           $api_url .= "&key=" .$api_key;
		            }
		            
		            wp_register_script( 'avia-google-maps-api', $api_url, array('jquery'), NULL, true);
					
					$load_google_map_api = apply_filters('avf_load_google_map_api', true, 'av_google_map');
					            
					if($load_google_map_api) wp_enqueue_script(  'avia-google-maps-api' );
					
					$args = array(
		                'toomanyrequests'	=> __("Too many requests at once, please wait a few seconds before requesting coordinates again",'avia_framework'),
		                'notfound'			=> __("Address couldn't be found by Google, please add it manually",'avia_framework'),
		                'insertaddress' 	=> __("Please insert a valid address in the fields above",'avia_framework')
		            );
	
		            if($load_google_map_api) wp_localize_script( 'avia-google-maps-api', 'avia_gmaps_L10n', $args );
				}
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
							"name" => __("Add/Edit Map Locations", 'avia_framework' ),
							"desc" => __("Here you can add, remove and edit the map locations for your Google Map.", 'avia_framework' )."<br/>",
							"type" 			=> "modal_group",
							"id" 			=> "content",
							"modal_title" 	=> __("Edit Location", 'avia_framework' ),
							"std"			=> array( array('address'=>"", 'type'=>'text', 'check'=>'is_empty'), ),
							'subelements' 	=> array(
								
									array(
									"name" 	=> __("Title", 'avia_framework' ),
									"desc" 	=> __("Enter the title for this marker", 'avia_framework' ),
									"id" 	=> "title",
									"std" 	=> "",
									"type" 	=> "input"),

									array(
									"name" 	=> __("Full Adress", 'avia_framework' ),
									"desc" 	=> __("Enter the Address, then hit the 'Fetch Coordinates' Button. If the address was found the coordinates will be displayed", 'avia_framework' ),
									"id" 	=> "address",
									"std" 	=> "",
									"type" 	=> "gmap_adress"),
									
									 array(
                  "name" 	=> __("Marker Tooltip", 'avia_framework' ),
                  "desc" 	=> __("Enter some text here. If the user clicks on the marker the text will be displayed", 'avia_framework' ) ,
                  "id" 	=> "content",
                  "type" 	=> "textarea",
                  "std" 	=> "",
									),
			                        
/*
			            array(	
									"name" 	=> __("Display Tooltip by default", 'avia_framework' ),
									"desc" 	=> __("Check to display the tooltip by default. If unchecked user must click the marker to show the tooltip", 'avia_framework' ) ,
									"id" 	=> "tooltip_display",
									"std" 	=> "",
                          			"required" 	=> array('content', 'not', ''),
									"type" 	=> "checkbox"),
*/
		
/*
									array(
									"name" 	=> __("Custom Map Marker Image",'avia_framework' ),
									"desc" 	=> __("Use a custom Image as marker. (make sure that you use a square image, otherwise it will be cropped)",'avia_framework' )."<br/><small>".__("Leave empty if you want to use the default marker",'avia_framework' )."</small>",
									"id" 	=> "marker",
									"fetch" => 'id',
									"type" 	=> "image",
									"title" => __("Insert Marker Image",'avia_framework' ),
									"button" => __("Insert",'avia_framework' ),
									"std" 	=> ""),
									
									array(
									"name" 	=> __("Custom Map Marker Image Size", 'avia_framework' ),
									"desc" 	=> __("How big should the marker image be displayed in height and width. ", 'avia_framework' ),
									"id" 	=> "imagesize",
									"type" 	=> "select",
									"std" 	=> "40",
                            		"required" 	=> array('marker', 'not', ''),
									"subtype" => array(
									
										__('20px * 20px',  'avia_framework' ) =>'20',
										__('30px * 30px',  'avia_framework' ) =>'30',
										__('40px * 40px',  'avia_framework' ) =>'40',
										__('50px * 50px',  'avia_framework' ) =>'50',
										__('60px * 60px',  'avia_framework' ) =>'60',
										__('70px * 70px',  'avia_framework' ) =>'70',
										__('80px * 80px',  'avia_framework' ) =>'80',
									
									),),
*/
									
								),
						
						),
						
						array(
							"name" 	=> __("Map height", 'avia_framework' ),
							"desc" 	=> __("You can either define a fixed height in pixel like '300px' or enter a width/height ratio like 16:9", 'avia_framework' ),
							"id" 	=> "height",
							"type" 	=> "input",
							"std" 	=> "400px",
						),
						

/*
						array(
						"name" 	=> __("Zoom Level", 'avia_framework' ),
						"desc" 	=> __("Choose the zoom of the map on a scale from  1 (very far away) to 19 (very close)", 'avia_framework' ),
						"id" 	=> "zoom",
						"type" 	=> "select",
						"std" 	=> "16",
						"subtype" => AviaHtmlHelper::number_array(1,19,1,array(__("Set Zoom level automatically to show all markers", 'avia_framework' ) => 'auto' ))),
						
						
						array(
						"name" 	=> __("Color Saturation", 'avia_framework' ),
						"desc" 	=> __("Choose the saturation of your map", 'avia_framework' ),
						"id" 	=> "saturation",
						"type" 	=> "select",
						"std" 	=> "",
						"subtype" => array(
						
							__('Full color fill',  'avia_framework' ) =>'fill',
							__('Oversaturated',  'avia_framework' ) =>'100',
							__('Slightly oversaturated',  'avia_framework' ) =>'50',
							__('Normal Saturation',   'avia_framework' ) =>'',
							__('Muted colors',  'avia_framework' ) =>'-50',
							__('Greyscale',  'avia_framework' ) =>'-100'),
						
						),
						
						array(
							"name" 	=> __("Custom Overlay Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom color for your Map here. The map will be tinted with that color. Leave empty if you want to use the default map color", 'avia_framework' ),
							"id" 	=> "hue",
							"type" 	=> "colorpicker",
							"std" 	=> "",
						),
						
						array(	
							"name" 	=> __("Display Zoom Control?", 'avia_framework' ),
							"desc" 	=> __("Check to display the controls at the left side of the map", 'avia_framework' ) ,
							"id" 	=> "zoom_control",
							"std" 	=> "active",
							"type" 	=> "checkbox"),
							
						array(	
							"name" 	=> __("Display Pan Control?", 'avia_framework' ),
							"desc" 	=> __("Check to display the Pan control wheel at the top left side of the map", 'avia_framework' )  ,
							"id" 	=> "pan_control",
							"std" 	=> "",
							"type" 	=> "checkbox"),
							
						array(	
							"name" 	=> __("Map dragging on mobile", 'avia_framework' ),
							"desc" 	=> __("Check to disable the users ability to drag the map on mobile devices. This ensures that the user can scroll down the page, even if the map fills the whole viewport of the mobile device", 'avia_framework' )  ,
							"id" 	=> "mobile_drag_control",
							"std" 	=> "",
							"type" 	=> "checkbox"),
*/

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
				$params['innerHtml'] = "<img src='".$this->config['icon']."' title='".$this->config['name']."' />";
				$params['innerHtml'].= "<div class='avia-element-label'>".$this->config['name']."</div>";
				
				$params['innerHtml'].= "<div class='avia-flex-element'>"; 
				$params['innerHtml'].= 		__('This element will stretch across the whole screen by default.','avia_framework')."<br/>";
				$params['innerHtml'].= 		__('If you put it inside a color section or column it will only take up the available space','avia_framework');
				$params['innerHtml'].= "	<div class='avia-flex-element-2nd'>".__('Currently:','avia_framework');
				$params['innerHtml'].= "	<span class='avia-flex-element-stretched'>&laquo; ".__('Stretch fullwidth','avia_framework')." &raquo;</span>";
				$params['innerHtml'].= "	<span class='avia-flex-element-content'>| ".__('Adjust to content width','avia_framework')." |</span>";
				$params['innerHtml'].= "</div></div>";
				
				return $params;
			}
			
			/**
			 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
			 * Works in the same way as Editor Element
			 * @param array $params this array holds the default values for $content and $args. 
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_sub_element($params)
			{
				$template = $this->update_template("address", __("Address", 'avia_framework' ). ": {{address}}");

				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container' {$template}>".__("Address", 'avia_framework' ).": ".$params['args']['address']."</div>";

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
				$atts = shortcode_atts(array(
				'id'    	 	=> '',
				'height'		=> '',
				'handle'		=> $shortcodename,
				'content'		=> ShortcodeHelper::shortcode2array($content, 1)
				
				), $atts, $this->config['shortcode']);

				
				extract($atts);
				$output  		= "";
			  $class 			= "";
				$skipSecond 	= false;
				custom_gmaps::$map_count++;
				
				$params['class'] 							= "avia-google-maps avia-google-maps-section main_color ".$meta['el_class'].$class;
				$params['open_structure'] 					= false;
				$params['id'] 								= empty($id) ? "avia-google-map-nr-".custom_gmaps::$map_count : $id;

				
				//we dont need a closing structure if the element is the first one or if a previous fullwidth element was displayed before
				if(isset($meta['index']) && $meta['index'] == 0) $params['close'] = false;
				if(!empty($meta['siblings']['prev']['tag']) && in_array($meta['siblings']['prev']['tag'], AviaBuilder::$full_el_no_section )) $params['close'] = false;


				//create the map div that will be used to insert the google map
				//$map = "<div id='av_gmap_".custom_gmaps::$map_count."' class='' data-mapid='".custom_gmaps::$map_count."' ".$this->define_height($height).">map ges here!!!</div>";
				
				
				ob_start();?>
				
				
				<style>
				#listdata{
				  table-layout: fixed;
				  width: 100%;
				  white-space: nowrap;
				}
				
				#listdata td{
				  width: 33%;   
				}
				
				.linkage{
				  cursor: pointer;    
				}
				</style>
				<div class="c-store-locator">
					<div class="acf-map" <?php echo $this->define_height($height) ?>></div>
					<table id="listdata"></table>
					<div id="newdiv" style="display: none">
						
					<?php foreach($content as $key => $item): ?>
						<?php	$marker= (object) $item['attr']; ?>
						<div class="marker" data-lat="<?php	echo $marker->lat ?>" data-lng="<?php	echo $marker->long ?>">
							<h5 class="title" style="margin-bottom: 0px"><strong><?php	echo $marker->title ?></strong></h5>
							<div class="description"><?php	echo $item['content']?></div>
							<div class="address"><?php	echo $marker->address?>, <?php	echo $marker->city?>, <?php	echo $marker->country?></div>
						</div>	
						
					<?php endforeach; ?>
					    
					</div>
				</div>
				<script>
				/* Google Maps */
				(function($) {
				
				function render_map( $el ) {
				    
				    // var
				    var $markers = $(document).find('.marker');
						
				    
				    // vars
				    var args = {
				        zoom        : 16,
				        center      : new google.maps.LatLng(0, 0),
				        mapTypeId   : google.maps.MapTypeId.ROADMAP,
				        scrollwheel: false,
				        mapTypeControlOptions: {
				          mapTypeIds: [google.maps.MapTypeId.ROADMAP]
				        }
				    };
				
				    // create map               
				    var map = new google.maps.Map( $el[0], args);
				
				    // add a markers reference
				    map.markers = [];
				    // add markers
				    index=0;
				    $markers.each(function(){
				        add_marker( $(this), map, index);
				        index++;
				    });
				   
				    // center map
				    center_map( map );
				
				    }
				    
				function add_marker( $marker, map, index ) {
				
				    // var
				    var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
				    var image = 'http://79.170.40.181/cranes.co.uk/wp-content/uploads/2014/10/pin.png';
				
				    // create marker
				    var marker = new google.maps.Marker({
				        position    : latlng,
				        map         : map,
				        //icon        : image
				    });
				
				    // add to array
				    map.markers.push( marker );
				    
				    
				    // if marker contains HTML, add it to an infoWindow
				    if( $marker.html() )
				    {
				        $('#listdata').append('<tr class="linkage" id="p'+index+'" width="100%"><td>'+$marker.find('.title').html()+'</td><td>'+$marker.find('.address').html()+'</td><td>'+'<a class="directions-link" href="https://www.google.com/maps/dir/Current+Location/'+$marker.attr('data-lat')+','+$marker.attr('data-lng')+'" target="_blank">Directions</a>'+'</tr>'); // change html here if you want but eave id intact!!
				         
				        $(document).on('click', '#p'+index, function(){
				            infowindow.open(map, marker);
				            setTimeout(function () { infowindow.close(); }, 5000);
				        });
				      
				        // create info window
				        var infowindow = new google.maps.InfoWindow({
				            content     : $marker.html(),
				        });
				        
				      
				          
				
				        // show info window when marker is clicked
				        google.maps.event.addListener(marker, 'click', function() {
				
				            infowindow.open( map, marker );
				
				        });
				
				    }
				
				    }
				
				
				function center_map( map ) {
				
				    // vars
				    var bounds = new google.maps.LatLngBounds();
				
				    // loop through all markers and create bounds
				    $.each( map.markers, function( i, marker ){
				
				        var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
				
				        bounds.extend( latlng );
				
				    });
				
				    // only 1 marker?
				    if( map.markers.length == 1 )
				    {
				        // set center of map
				        alert(bounds);
				        map.setCenter( bounds.getCenter() );
				        map.setZoom( 16 );
				    }
				    else
				    {
				        // fit to bounds
				        map.fitBounds( bounds );
				    }
				
				    }
				
				// Call it
				
				
				  $(document).ready(function(){
				
				    $('.acf-map').each(function(){
				
				        render_map( $(this) );
				
				    });
				
				});
				
				
				
				})(jQuery);	
				
				</script>
				
				
				<?php
				$map =	ob_get_contents();
				ob_end_clean();			
				
				//if the element is nested within a section or a column dont create the section shortcode around it
				if(!ShortcodeHelper::is_top_level()) return $map;
				
				$output .=  avia_new_section($params);
				$output .= $map;
				$output .= "</div>"; //close section
				
				//if the next tag is a section dont create a new section from this shortcode
				if(!empty($meta['siblings']['next']['tag']) && in_array($meta['siblings']['next']['tag'],  AviaBuilder::$full_el ))
				{
				    $skipSecond = true;
				}

				//if there is no next element dont create a new section.
				if(empty($meta['siblings']['next']['tag']))
				{
				    $skipSecond = true;
				}
				
				if(empty($skipSecond)) {
				
					$output .= avia_new_section(array('close'=>false, 'id' => "after_full_slider_".custom_gmaps::$map_count));
				}
				
				return $output;

			}
			
			function define_height($height)
			{	
				$style = "";
				
				//apply a ratio via bottom padding
				if(strpos($height, ':') !== false)
				{
					$height = explode(':', $height);
					$height = (100 / (int) $height[0]) * $height[1];
					$style = "style='padding-bottom: {$height}%;'";
				}
				else // set a fixed height
				{
					$height = (int) $height;
					$style = "style='height: {$height}px;width:100%'";
				}
				
				return $style;
			}					
			
	}
}



