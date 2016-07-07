<?php

//registering shortcode for counter output
function plain_counter_shortcode() {
  /* Turn on buffering */
	ob_start();

  $layout_group = get_option('grintender_pl_cell_layout');

  $icon_show = ($layout_group && in_array('icon', $layout_group)) ? "nah mean" : "aint-show";
  $divider_show = ($layout_group && in_array('divider', $layout_group)) ? "nah mean" : "aint-show";
  $text_show = ($layout_group && in_array('text', $layout_group)) ? "nah mean" : "aint-show";

?>

 <div class="fullWidth border-outer <?php echo get_option('grintender_pl_mobile-display') ? "mobile-aint-show" : "nah mean ";?>"
    style="">
        <div class="cellsWrap blue-grey <?php echo get_option('grintender_pl_bg_theme')?>">

            <?php $number = get_option('grintender_pl_n_cells');
              for ($i = 1; $i <= $number; $i++) {
            ?>
                <div class="item border-item" style="
                                    font-size: <?php echo  get_option('grintender_pl_elements_size')?>px;
                                    line-height: <?php echo  get_option('grintender_pl_elements_size')?>px;
                                    color: <?php echo  get_option('grintender_pl_elements_color')?>;
                                         ">


                    <!-- fontawesome icon line -->

                    <i class="fa <?php echo $icon_show . " fa-" . get_option('grintender_pl_icon_' . $i) . " fa-" . get_option('grintender_pl_icon_size') . "x"?>"></i>
                    <!-- fontawesome icon line ends -->

                    <br>

                    <!-- number line -->
                    <span id="<?php echo "number" . $i ?>"> <?php echo get_option( 'grintender_pl_js_fallback_value_' . $i)?></span>
                    <!-- number line ends -->

                    <br>

                    <!-- divider line -->
                     <span class="divider-display <?php echo $divider_show ?>"
                           style="background: <?php echo get_option('grintender_pl_elements_color')?>"></span>
                    <!-- divider line ends-->

                    <!-- text line -->
                    <span class="caption <?php echo $text_show ?>"><?php echo get_option( 'grintender_pl_caption_' . $i)?></span>
                    <!-- text line ends -->

                </div>

              <?php }?>
        </div>

  </div>

<!-- styles that rely on php settings -->
<style>


    <?php

    //borders
    if (get_option('grintender_pl_borders') == "outer") {
            echo ".border-outer {
            border: " . get_option( 'grintender_pl_border_width') . 'px ' . get_option( 'grintender_pl_borders_style') . get_option( 'grintender_pl_border_color') . "}" ;
            } elseif (get_option('grintender_pl_borders') == "each") {
               echo ".border-item {
                border: " . get_option( 'grintender_pl_border_width') . 'px ' . get_option( 'grintender_pl_borders_style') . get_option( 'grintender_pl_border_color') . "; border-left:none;} .border-item:nth-child(1) {border: " . get_option( 'grintender_pl_border_width') . 'px ' . get_option( 'grintender_pl_borders_style') . get_option( 'grintender_pl_border_color') . "}";
              } else { echo ".border-outer, .border-item {border: none;}"; };
    //background
    if (get_option('grintender_pl_is_bg_solid')) {

        echo ".cellsWrap .item {background: " . get_option('grintender_pl_bg_solid') . " !important}";
    };

    
    if (get_option('grintender_pl_responsive_type') == "row") {
            echo ".cellsWrap .item {flex: 1}"; 
            } else if (get_option('grintender_pl_responsive_type') == "responsive") {
            echo ".cellsWrap .item {flex: 1 1 auto}";
            };
    
    echo get_option('grintender_pl_custom_css')
   
    ?>


</style>

  <?php
    /* Get the buffered content into a var */
	  $output = ob_get_contents();

    /* Clean buffer */

    ob_end_clean();
    //ob_end_flush();
    return $output;
}


function plain_counter_register_shortcode() {
    add_shortcode( 'plain_counter', 'plain_counter_shortcode' );
}

add_action( 'init', 'plain_counter_register_shortcode' );



/*
** BRANDED HEADER SHORTCODE
*/


function plain_counter_branded_shortcode() {
  /* Turn on buffering */
	ob_start();

?>

<div class="branded-header-container" >


    <div class="coming-updates item2">

        <ul>
        <strong> Coming update ## </strong>

            <li>1. multiple counters</li>
            <li>2. styling templates</li>
            <li>3. fixes for non-critical bugs reported</li>
            <li>4. enhanced style settings</li>
        </ul>

    </div>




    <div class="email-form item2">

 <!-- Begin MailChimp Signup Form -->
<div id="mc_embed_signup">
 <!--<link href="//cdn-images.mailchimp.com/embedcode/slim-10_7.css" rel="stylesheet" type="text/css">-->

<form action="//the.us11.list-manage.com/subscribe/post?u=c256ae3d1fd31b98f1f515c17&amp;id=cced2f1a72" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
    <div id="mc_embed_signup_scroll">
	<label for="mce-EMAIL">Few times a year we release things. Wanna be notified?</label>
	<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_c256ae3d1fd31b98f1f515c17_cced2f1a72" tabindex="-1" value=""></div>
    <div class="clear"><input type="submit" value="yep" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
    </div>
</form>
</div>

<!--End mc_embed_signup-->

    </div>


     <div class="contact item2">
         whether you have any questions, bug to report or just wanna chat, drop a line or two at <br><br> <strong>editor@the.gt </strong>
    </div>


</div>

  <?php
    /* Get the buffered content into a var */
	  $branded = ob_get_contents();

    /* Clean buffer */

    ob_end_clean();
    //ob_end_flush();
    return $branded;
}


function plain_counter_register_branded_shortcode() {
    add_shortcode( 'plain_counter_branded', 'plain_counter_branded_shortcode' );
}

add_action( 'init', 'plain_counter_register_branded_shortcode' );
