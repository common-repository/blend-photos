<?php
add_shortcode( 'wp_blend_photos', 'blp_generate_short_code_options');

function blp_generate_short_code_options( $atts ){
	$cover_photo = get_option('cover_photo');
	$root_paths = wp_upload_dir();
	
	if (!is_dir( $root_paths['basedir'].'/blend_photos')) {
	    mkdir($root_paths['basedir'].'/blend_photos');
	}
	if ( isset( $_FILES['photo_filter'] ) && !empty( $_FILES['photo_filter'] ) ) {

		$dir = BLENDPHOTO_BASE_URL."/images/";
		foreach (glob($dir."*") as $file) {
			if (filemtime($file) < time() - 8640) {
			    unlink($file);
			}
		}

		$allowed = array('jpg', 'jpeg', 'png','gif','JPG', 'JPEG', 'PNG', 'GIF');
		$result_image_name = "";
		$extension = wp_check_filetype_and_ext( $_FILES['photo_filter']['tmp_name'], $_FILES['photo_filter']['name'], $mimes = null );
		if(!in_array(strtolower($extension["ext"]), $allowed)){
			wp_redirect("http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."?format=wrong");
			exit;
		}
		/*Code added by azher for changing name of images. */
		if ( !empty( $_FILES['photo_filter']['name'] ) ) {
			$ext = $extension["ext"];
			$random_number = rand(0,1000000);
			$resume_name = time().$random_number.".".$ext;
			$_SESSION["filter_image"] = BLENDPHOTO_HOST_URL . "/images/".$resume_name;

			if( move_uploaded_file($_FILES['photo_filter']['tmp_name'], BLENDPHOTO_BASE_URL."/images/" . $resume_name) )
			{
				$path_to_image_directory = BLENDPHOTO_BASE_URL."/images/";
				if(preg_match('/[.](jpg)$/', $resume_name)) {
					$im = imagecreatefromjpeg($path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](gif)$/', $resume_name)) {
					$im = imagecreatefromgif($path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](png)$/', $resume_name)) {
					$im = imagecreatefrompng($path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](jpeg)$/', $resume_name)) {
					$im = imagecreatefromjpeg($path_to_image_directory . $resume_name);
				}
				if(preg_match('/[.](JPG)$/', $resume_name)) {
					$im = imagecreatefromjpeg($path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](GIF)$/', $resume_name)) {
					$im = imagecreatefromgif($path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](PNG)$/', $resume_name)) {
					$im = imagecreatefrompng($path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](JPEG)$/', $resume_name)) {
					$im = imagecreatefromjpeg($path_to_image_directory . $resume_name);
				}

				$ox = imagesx($im);
				$oy = imagesy($im);
				if($ox>$oy)
				{
					$new_ox=600;
					//original height / original width x new width = new height
					//1200 / 1600 x 400 = 300
					$ny = ($oy / $ox) * $new_ox;
					$nx = $new_ox;
				}
				if($oy>=$ox)
				{
					$new_oy=800;
					//original width / original height x new height = new width
					$nx = ($ox / $oy) * $new_oy;
					//1200 / 1600 x 400 = 300
					$ny = $new_oy;
				}
				$_SESSION['filter_image_height'] = $ny;
				$_SESSION['filter_image_width'] = $nx;
				$nm = imagecreatetruecolor($nx, $ny);
				//imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
				imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
				if(preg_match('/[.](jpg)$/', $resume_name)) {
					imagejpeg($nm, $path_to_image_directory . $resume_name);
					imagejpeg($nm, $path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](gif)$/', $resume_name)) {
					imagegif($nm, $path_to_image_directory . $resume_name);
					imagegif($nm, $path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](png)$/', $resume_name)) {
					imagepng($nm, $path_to_image_directory . $resume_name);
					imagepng($nm, $path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](jpeg)$/', $resume_name)) {
					imagejpeg($nm, $path_to_image_directory . $resume_name);
					imagejpeg($nm, $path_to_image_directory . $resume_name);
				}
				if(preg_match('/[.](JPG)$/', $resume_name)) {
					imagejpeg($nm, $path_to_image_directory . $resume_name);
					imagejpeg($nm, $path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](GIF)$/', $resume_name)) {
					imagegif($nm, $path_to_image_directory . $resume_name);
					imagegif($nm, $path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](PNG)$/', $resume_name)) {
					imagepng($nm, $path_to_image_directory . $resume_name);
					imagepng($nm, $path_to_image_directory . $resume_name);
				} else if (preg_match('/[.](JPEG)$/', $resume_name)) {
					imagejpeg($nm, $path_to_image_directory . $resume_name);
					imagejpeg($nm, $path_to_image_directory . $resume_name);
				}
			}

		}
	}

	if( isset( $_POST['blend_x'] ) && !empty( $_POST['blend_x'] ) && intval( $_POST['blend_x'] ) != 0 ){
		$targ_w = $targ_h = 200;
		$jpeg_quality = 90;
		$blend_x = sanitize_text_field( intval( $_POST['blend_x'] ) );
		$blend_y = sanitize_text_field( intval( $_POST['blend_y'] ) );
		$blend_w = sanitize_text_field( intval( $_POST['blend_w'] ) );
		$blend_h = sanitize_text_field( intval( $_POST['blend_h'] ) );
		$blend_image = sanitize_text_field( $_POST['blend_image'] );
		
		if(!isset($blend_x) || !is_numeric($blend_x)) {
		  die('Please select a crop area.');
		}

		$res_image = explode( "/", $blend_image );
		$src = $blend_image;
		$img_r = imagecreatefromjpeg( $blend_image );
		$dst_r = ImageCreateTrueColor($targ_w, $targ_h);

		imagecopyresampled( $dst_r, $img_r, 0, 0, $blend_x, $blend_y, $targ_w, $targ_h, $blend_w, $blend_h );
		imagejpeg($dst_r, $root_paths['basedir'].'/blend_photos/'.$res_image[ count( $res_image ) - 1 ], $jpeg_quality); // NULL will output the image directly


		$dest = imagecreatefromjpeg( $root_paths['basedir'].'/blend_photos/'.$res_image[ count( $res_image ) - 1 ] );
		$src = imagecreatefrompng( $cover_photo );
		imagealphablending($dest, true);
		imagesavealpha($dest, true);
		// Copy and merge
		imagecopy($dest, $src, 0, 0, 0, 0, 200, 200);
		//imagecopymerge($dest, $src, left Space, Top Space, 0, 0, Image Width, Image Width, Quality);
		$random_number = rand(0,1000000);
		$result_image = time().$random_number;
		// Output and free from memory
		imagepng($dest, $root_paths['basedir'].'/blend_photos/'.$res_image[ count( $res_image ) - 1 ], 0);

		imagedestroy($dest);
		imagedestroy($src);
		if ( isset( $_SESSION["filter_image"] ) ) {
			unset( $_SESSION["filter_image"] );
		}
		
		if ( isset( $_SESSION["result_image"] ) ) {
			unset( $_SESSION["result_image"] );
		}
		$_SESSION["result_image"] = $root_paths['baseurl'] .'/blend_photos/'. $res_image[ count( $res_image ) - 1 ];
	}

	
	$form = blp_generate_upload_form();
	return($form);
}

function bas_generate_widget_options( $atts ){

	$widget_div_id = "bas-search-widget-" . time() . rand(0,999999);
	$bas_placeholder = "Search For...";
	$bas_get_ajax_search_atts = "";
	$bas_get_ajax_search_category_atts = "";
	$bas_btn_text = "Search";
	if( $atts != '' ){
		if ( isset( $atts['bas_placeholder_text'] ) && !empty( $atts['bas_placeholder_text'] ) ) { $bas_placeholder = $atts['bas_placeholder_text']; }
		else{ $bas_placeholder = "Search For..."; }
		if ( isset( $atts['bas_button_text'] ) && !empty( $atts['bas_button_text'] ) ) { $bas_btn_text = $atts['bas_button_text']; }
		else{ $bas_btn_text = "Search"; }


		if ( isset( $atts['selectsearchcriteria'] ) ) {
			$bas_get_ajax_search_atts = implode("|", $atts['selectsearchcriteria']);
		}else{ 
			$bas_get_ajax_search_atts = "";
		}

		if ( isset( $atts['search_category_type'] ) ) {
			$bas_get_ajax_search_category_atts = implode("|", $atts['search_category_type']);
		}else{
			$bas_get_ajax_search_category_atts = "";
		}

		$bas_widget_button_css = "";
		if ( isset( $atts['bas_button_font_size'] ) && !empty( $atts['bas_button_font_size'] ) ) {
			if ( is_numeric( $atts['bas_button_font_size'] ) === true ) {
				$bas_widget_button_css .= "font-size:". $atts['bas_button_font_size'] ."px;";	
			}else{
				$bas_widget_button_css .= "font-size:". $atts['bas_button_font_size'] .";";	
			}
		}
		if ( isset( $atts['bas_button_font_color'] ) && !empty( $atts['bas_button_font_color'] ) ) {
			$bas_widget_button_css .= "color:". $atts['bas_button_font_color'] .";";
		}
		if ( isset( $atts['bas_button_back_color'] ) && !empty( $atts['bas_button_back_color'] ) ) {
			$bas_widget_button_css .= "background:". $atts['bas_button_back_color'] .";";
		}

		$bas_widget_text_css = "";
		if ( isset( $atts['bas_text_font_size'] ) && !empty( $atts['bas_text_font_size'] ) ) {
			if ( is_numeric( $atts['bas_text_font_size'] ) === true ) {
				$bas_widget_text_css .= "font-size:". $atts['bas_text_font_size'] ."px;";
			}else{
				$bas_widget_text_css .= "font-size:". $atts['bas_text_font_size'] .";";	
			}
		}
		if ( isset( $atts['bas_text_font_color'] ) && !empty( $atts['bas_text_font_color'] ) ) {
			$bas_widget_text_css .= "color:". $atts['bas_text_font_color'] .";";
		}
		if ( isset( $atts['bas_text_background'] ) && !empty( $atts['bas_text_background'] ) ) {
			$bas_widget_text_css .= "background:". $atts['bas_text_background'] .";";
		}
		if ( isset( $atts['bas_text_border'] ) && !empty( $atts['bas_text_border'] ) ) {
			$bas_widget_text_css .= "border:1px solid ". $atts['bas_text_border'] .";";
		}
		if ( isset( $atts['bas_text_vpadding'] ) && !empty( $atts['bas_text_vpadding'] ) ) {
			if ( is_numeric( $atts['bas_text_vpadding'] ) === true ) {
				$bas_widget_text_css .= "padding-top:". $atts['bas_text_vpadding'] ."px;padding-bottom:". $atts['bas_text_vpadding'] ."px;";	
			}else{
				$bas_widget_text_css .= "padding-top:". $atts['bas_text_vpadding'] .";padding-bottom:". $atts['bas_text_vpadding'] .";";
			}
		}
		if ( isset( $atts['bas_text_hpadding'] ) && !empty( $atts['bas_text_hpadding'] ) ) {
			if ( is_numeric( $atts['bas_text_hpadding'] ) === true ) {
				$bas_widget_text_css .= "padding-left:". $atts['bas_text_hpadding'] ."px;padding-right:". $atts['bas_text_hpadding'] ."px;";	
			}else{
				$bas_widget_text_css .= "padding-left:". $atts['bas_text_hpadding'] .";padding-right:". $atts['bas_text_hpadding'] .";";	
			}
		}
		bas_ajax_search_scripts( $bas_widget_button_css, $bas_widget_text_css, $widget_div_id );
	}
	
	$form = bas_generate_search_form($widget_div_id, $bas_placeholder, $bas_get_ajax_search_atts,$bas_get_ajax_search_category_atts, $bas_btn_text);
	return($form);
}

function blp_generate_upload_form(){
	$cover_photo = get_option('cover_photo');

	$form = '';
	$form .= "<form action='' id='image_uploader' name='image_uploader' method='POST' enctype='multipart/form-data'>";
		$form .= "<input type='file' name='photo_filter'/>";
		$form .= "<input type='submit' value='Upload Image'/>";
	$form .= "</form>";


	$form .= "<div id='blp-wrapper' class='blp-wrapper'>";
		if ( !isset( $_SESSION["result_image"] ) && isset( $_SESSION["filter_image"] ) ) {
			$form .= "<div id='form-container' >";
		      	$form .= "<form action='' id='cropimg' name='cropimg' method='POST' enctype='multipart/form-data'>";
		      	if ( isset( $_SESSION["filter_image"] ) ) {
		      		$form .= "<input type='text' id='blend_image' name='blend_image' value='". $_SESSION["filter_image"] ."' style='display:none;'/>";
		      	}
					$form .= "<input type='text' id='blend_x' name='blend_x' style='display:none;'>";
					$form .= "<input type='text' id='blend_y' name='blend_y' style='display:none;'>";
					$form .= "<input type='text' id='blend_w' name='blend_w' style='display:none;'>";
					$form .= "<input type='text' id='blend_h' name='blend_h' style='display:none;'>";
					$form .= "<input type='submit' id='submit' value='Crop Image!'>";
		      	$form .= "</form>";
		    $form .= "</div>";
		}
		
		
	    if ( isset( $_SESSION["filter_image"] ) ) {

	  		$form .= "<div class='blp-selection-box' >";
		    	$form .= "<img src='". esc_url( $_SESSION["filter_image"] ) ."' id='target'/>";
			$form .= "</div>";

			$form .= "<div id='blp-preview-pane' class='azhar' >";
	      		$form .= "<div class='blp-preview-container'>";
	      			// Cover Photo
	        		$form .= "<img src='". esc_url( $cover_photo ) ."' class='blp-frame' />";
	        		$form .= "<img src='". esc_url( $_SESSION["filter_image"] ) ."' class='blp-preview' alt='Preview'/>";
	      		$form .= "</div>";
	    	$form .= "</div>";
	    }
	    if ( isset( $_SESSION["result_image"] ) ) {
	    	$form .= "<img src='". esc_url( $_SESSION["result_image"] ) ."' />";
	    }
	$form .= "</div>";

	return $form;
}

?>