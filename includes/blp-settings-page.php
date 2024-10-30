<?php

//Create a menu in settings tab
add_action( 'admin_menu', 'blp_setting_menu' );
function blp_setting_menu() {
    add_options_page(
        'WP Blend Photo',
        'WP Blend Photo',
        'manage_options',
        'photoblend-plugin',
        'blp_photoblend_options_page'
    );
}


//add_action('admin_init', array($this, 'admin_init'));
function blp_photoblend_options_page() {
    ?>
    <div class="wrap">
        <h2>WP Blend Photo Plugin Options</h2>
        <div class="blp-tab-divs" id="blp-general">
            <form method="post" action="options.php">
                <?php
                    settings_fields("blp-field-section");
                    do_settings_sections("blp-options");      
                    submit_button(); 
                    blp_short_code();
                ?>          
            </form>
        </div>
    </div>
    <?php
}


function blp_layout_elements()
{
    ?>
    <img class="cover_photo" src="<?php echo get_option('cover_photo'); ?>" height="100" width="100" style="<?php if ( get_option('cover_photo') == "" ) { echo 'display:none'; }?>"/><br>
    <input class="cover_photo_url" type="text" name="cover_photo" value="<?php echo get_option('cover_photo'); ?>">
    <a href="#" class="cover_photo_upload">Upload</a><br>
    <span class="blp_note" >Add Transparent PNG ( 200 X 200 ) Photo Frame which will be blended over users photos.</span>
    <?php
}

function blp_short_code(){
?>
    Use this shortcode to add blend photo for in your page
    <pre class="blp_shortcode_section">[wp_blend_photos]</pre>

    <div class="blp-paid-info">
        <h1>Paid Version</h1>

        Buy our plugin in just <span class="blp-money">$10</span> you will get following features.<br>
        - Customised transparent PNG frame ( n ), "n" means whatever size you want.<br>
        - Options to set height and width of the output image.<br>
        - Options to add multiple frames so that users can have multiple options to create there pic.<br>
        - Users can directly share the output image on the social media like (Facebook).<br>

        To purchase this plugin please contact us at <a href="mailto:mazharahmedkhan010@gmail.com">mazharahmedkhan010@gmail.com</a>

    </div>
<?php
}


function blp_panel_fields()
{
    add_settings_section("blp-field-section", "", null, "blp-options");
    add_settings_field("cover_photo", "Add Cover Photo", "blp_layout_elements", "blp-options", "blp-field-section");

    register_setting("blp-field-section", "cover_photo");
}

add_action("admin_init", "blp_panel_fields");
?>