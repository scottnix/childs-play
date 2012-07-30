<?php

//
//  Child's Play (a child theme for Thematic) Functions
//



// recreates the doctype section, html5boilerplate.com style with conditional classes
// http://scottnix.com/html5-header-with-thematic/
function childtheme_create_doctype() {
    $content = "<!doctype html>" . "\n";
    $content .= '<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->' . "\n";
    $content .= '<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->'. "\n";
    $content .= '<!--[if IE 8]> <html class="no-js lt-ie9" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->' . "\n";
    $content .= "<!--[if gt IE 8]><!-->" . "\n";
    $content .= "<html class=\"no-js\"";
    return $content;
}
add_filter('thematic_create_doctype', 'childtheme_create_doctype');

// creates the head, meta charset and viewport tags
function childtheme_head_profile() {
    $content = "<!--<![endif]-->";
    $content .= "\n" . "<head>" . "\n";
    $content .= "<meta charset=\"utf-8\" />" . "\n";
    $content .= "<meta name=\"viewport\" content=\"width=device-width\" />" . "\n";
    return $content;
}
add_filter('thematic_head_profile', 'childtheme_head_profile');

// remove meta charset tag, now in the above function
function childtheme_create_contenttype() {
    // silence
}
add_filter('thematic_create_contenttype', 'childtheme_create_contenttype');



// remove the index and follow tags from header since it is browser default.
// http://scottnix.com/polishing-thematics-head/
function childtheme_create_robots($content) {
    global $paged;
    if (thematic_seo()) {
        if((is_home() && ($paged < 2 )) || is_front_page() || is_single() || is_page() || is_attachment())
        {
            $content = "";
        } elseif (is_search()) {
            $content = "\t";
            $content .= "<meta name=\"robots\" content=\"noindex,nofollow\" />";
            $content .= "\n\n";
        } else {
            $content = "\t";
            $content .= "<meta name=\"robots\" content=\"noindex,follow\" />";
            $content .= "\n\n";
        }
    return $content;
    }
}
add_filter('thematic_create_robots', 'childtheme_create_robots');



// clear useless garbage for a polished head
// remove really simple discovery
remove_action('wp_head', 'rsd_link');
// remove windows live writer xml
remove_action('wp_head', 'wlwmanifest_link');
// remove index relational link
remove_action('wp_head', 'index_rel_link');
// remove parent relational link
remove_action('wp_head', 'parent_post_rel_link');
// remove start relational link
remove_action('wp_head', 'start_post_rel_link');
// remove prev/next relational link
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');



// kills the 4 scripts for the drop downs, combined and reloaded by the script manager (dropdowns-js)
function childtheme_override_head_scripts() {
    // silence
}



// script manager template for registering and enqueuing files
function childtheme_script_manager() {
    // wp_register_script template ( $handle, $src, $deps, $ver, $in_footer );
    // registers modernizr script, stylesheet local path, no dependency, no version, loads in header
    wp_register_script('modernizr-js', get_stylesheet_directory_uri() . '/js/modernizr.js', false, false, false);
    // registers dropdowns script, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('dropdowns-js', get_bloginfo('stylesheet_directory') . '/js/superfish-dropdowns.js', array('jquery'), false, true);
    // registers fitvids script, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('fitvids-js', get_stylesheet_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), false, true);
    // registers misc custom script, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), false, true);
    // registers flexslider script, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('flexslider-js', get_stylesheet_directory_uri() . '/flexslider/jquery.flexslider-min.js', array('jquery'), false, true);
    // registers flexslider styles, local stylesheet path
    wp_register_style('flexslider-css', get_stylesheet_directory_uri() . '/flexslider/flexslider.css');

    // enqueue the scripts for use in theme
    wp_enqueue_script ('modernizr-js');
    wp_enqueue_script ('fitvids-js');
    wp_enqueue_script ('dropdowns-js');

        if ( is_front_page() ) {
            wp_enqueue_script ('flexslider-js');
            wp_enqueue_style ('flexslider-css');
        }

    //always enqueue this last, helps with conflicts
    wp_enqueue_script ('custom-js');

}
add_action('wp_enqueue_scripts', 'childtheme_script_manager');



// had to add this to get a div around the titles, mostly for correct scaling on em paddings.
// also beefed up to add more robust style options with spans
function childtheme_override_page_title() {
    global $post;
        $content = "\t\t\t\t";
        $content .= '<div class="title-wrap">';
        if (is_attachment()) {
                $content .= '<h2 class="page-title"><span><a href="';
                $content .= apply_filters('the_permalink',get_permalink($post->post_parent));
                $content .= '" rev="attachment"><span class="meta-nav">&laquo; </span><span>';
                $content .= get_the_title($post->post_parent);
                $content .= '</span></a></span></h2>';
        } elseif (is_author()) {
                $content .= '<h1 class="page-title author"><span>';
                $author = get_the_author_meta( 'display_name', $post->post_author );
                $content .= __('Author Archives:', 'thematic');
                $content .= ' <span>';
                $content .= $author;
                $content .= '</span></span></h1>';
        } elseif (is_category()) {
                $content .= '<h1 class="page-title"><span>';
                $content .= __('Category Archives:', 'thematic');
                $content .= ' <span>';
                $content .= single_cat_title('', FALSE);
                $content .= '</span></span></h1>' . "\n";
                $content .= "\n\t\t\t\t" . '<div class="archive-meta">';
                if ( !(''== category_description()) ) : $content .= apply_filters('archive_meta', category_description()); endif;
                $content .= '</div>';
        } elseif (is_search()) {
                $content .= '<h1 class="page-title"><span>';
                $content .= __('Search Results for:', 'thematic');
                $content .= ' <span id="search-terms">';
                $content .= get_search_query();
                $content .= '</span></span></h1>';
        } elseif (is_tag()) {
                $content .= '<h1 class="page-title"><span>';
                $content .= __('Tag Archives:', 'thematic');
                $content .= ' <span>';
                $content .= ( single_tag_title( '', false ));
                $content .= '</span></span></h1>';
        } elseif (is_tax()) {
                global $taxonomy;
                $content .= '<h1 class="page-title"><span>';
                $tax = get_taxonomy($taxonomy);
                $content .= $tax->labels->singular_name . ' ';
                $content .= __('Archives:', 'thematic');
                $content .= ' <span>';
                $content .= thematic_get_term_name();
                $content .= '</span></span></h1>';
        } elseif (is_post_type_archive() && is_archive() ) {
                $content .= '<h1 class="page-title"><span>';
                $post_type_obj = get_post_type_object( get_post_type() );
                $post_type_name = $post_type_obj->labels->singular_name;
                $content .= __('Archives:', 'thematic');
                $content .= ' <span>';
                $content .= $post_type_name;
                $content .= '</span></span></h1>';
        } elseif (is_day()) {
                $content .= '<h1 class="page-title"><span>';
                $content .= sprintf(__('Daily Archives: <span>%s</span>', 'thematic'), get_the_time(get_option('date_format')));
                $content .= '</span></h1>';
        } elseif (is_month()) {
                $content .= '<h1 class="page-title"><span>';
                $content .= sprintf(__('Monthly Archives: <span>%s</span>', 'thematic'), get_the_time('F Y'));
                $content .= '</span></h1>';
        } elseif (is_year()) {
                $content .= '<h1 class="page-title"><span>';
                $content .= sprintf(__('Yearly Archives: <span>%s</span>', 'thematic'), get_the_time('Y'));
                $content .= '</span></h1>';
        }
        $content .= "\n";
        $content .= "</div> <!-- .title-wrap -->";
    echo apply_filters('thematic_page_title', $content);
}



// add favicon to site, add 16x16 "favicon.ico" image to child themes main folder
function childtheme_add_favicon() { ?>
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" />
<?php }
add_action('wp_head', 'childtheme_add_favicon');



// register two additional custom menu slots
function childtheme_register_menus() {
    if ( function_exists( 'register_nav_menu' )) {
        register_nav_menu( 'secondary-menu', 'Secondary Menu' );
        register_nav_menu( 'tertiary-menu', 'Tertiary Menu' );
    }
}
add_action('init', 'childtheme_register_menus');



// completely remove nav above functionality
function childtheme_override_nav_above() {
    // silence
}



// add a header aside widget, currently set up to be inside the #branding div
function childtheme_add_header_widget($content) {
    $content['Header Aside Widget'] = array(
        'admin_menu_order' => 2,
        'args' => array (
        'name' => 'Header Aside',
        'id' => 'header-aside-widget',
        'description' => __('The widget area in the header.', 'thematic'),
        'before_widget' => thematic_before_widget(),
        'after_widget' => thematic_after_widget(),
        'before_title' => thematic_before_title(),
        'after_title' => thematic_after_title(),
            ),
        'action_hook'   => 'thematic_header',
        'function'      => 'childtheme_header_aside_widget',
        'priority'      => 6
        );
    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_add_header_widget');

// set structure for the header aside widget
function childtheme_header_aside_widget() {
    if ( is_active_sidebar('header-aside-widget') ) {
        echo "\n".'<div id="header-widget" class="aside header-aside">' . "\n" . "\t" . '<ul class="xoxo">' . "\n";
        dynamic_sidebar('header-aside-widget');
        echo "\n" . "\t" . '</ul>' ."\n" . '</div><!-- #header-widget .header-aside -->' . "\n";
    }
}



// add 4th subsidiary aside widget, currently set up to be a footer widget underneath the 3 subs
function childtheme_add_subsidiary($content) {
    $content['Footer Widget Aside'] = array(
        'admin_menu_order' => 550,
        'args' => array (
        'name' => 'Footer Aside',
        'id' => '4th-subsidiary-aside',
        'description' => __('The 4th bottom widget area in the footer.', 'thematic'),
        'before_widget' => thematic_before_widget(),
        'after_widget' => thematic_after_widget(),
        'before_title' => thematic_before_title(),
        'after_title' => thematic_after_title(),
            ),
        'action_hook'   => 'widget_area_subsidiaries',
        'function'      => 'childtheme_4th_subsidiary_aside',
        'priority'      => 90
        );
    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_add_subsidiary', 50);

// set structure for the 4th subsidiary aside
// this is modified from the original by adding the .sub-wrapper, super hacky!
function childtheme_4th_subsidiary_aside() {
    if ( is_active_sidebar('4th-subsidiary-aside') ) {
        echo "\n".'<div id="fourth" class="aside footer-aside">' . "\n" . "\t" . '<ul class="xoxo">' . "\n";
        dynamic_sidebar('4th-subsidiary-aside');
        echo "\n" . "\t" . '</ul>' ."\n" . '</div><!-- #fourth .footer-aside -->' . "\n";
    }
    echo "\n" . '</div><!-- .sub-wrapper -->' . "\n";
}
// open the sub-wrapper, super hacky!
function childtheme_subsidiary_wrapper_div () { ?>
    <div class="sub-wrapper">
<?php }
add_action('thematic_footer', 'childtheme_subsidiary_wrapper_div');



// hide unused widget areas inside the WordPress admin
function childtheme_hide_areas($content) {
    unset($content['Index Top']);
    unset($content['Index Insert']);
    unset($content['Index Bottom']);
    unset($content['Single Top']);
    unset($content['Single Insert']);
    unset($content['Single Bottom']);
    unset($content['Page Top']);
    unset($content['Page Bottom']);
    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_hide_areas');



// cuts the default size of the search input field down to cut overlap
// css sizes this fine, but it could be placed in things other than aside, this is back up. ;)
function childtheme_thematic_search_form_length() {
    return "16";
}
add_filter('thematic_search_form_length', 'childtheme_thematic_search_form_length');



// change the default search box text
function childtheme_search_field_value() {
    return "Search";
}
add_filter('search_field_value', 'childtheme_search_field_value');



// featured image size (on anyting with excerpt?)
function childtheme_post_thumb_size($size) {
    $size = array(200,200);
    return $size;
}
add_filter('thematic_post_thumb_size', 'childtheme_post_thumb_size');



/*
// load google analytics
// optimized version http://mathiasbynens.be/notes/async-analytics-snippet
function snix_google_analytics(){ ?>
<script>var _gaq=[['_setAccount','UA-xxxxxxx-x'],['_trackPageview']];(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.src='//www.google-analytics.com/ga.js';s.parentNode.insertBefore(g,s)}(document,'script'))</script>
<?php }
add_action('wp_footer', 'snix_google_analytics');
*/


// example of changing up the display of the entry-utility for a different look
function childtheme_override_postfooter() {

        $post_type = get_post_type();
        $post_type_obj = get_post_type_object($post_type);

        // Check for "Page" post-type and logged in user to show edit link
        if ( $post_type == 'page' && current_user_can('edit_posts') ) {
            $postfooter = '';
        // Display nothing for logged out users on a "Page" post-type
        } elseif ( $post_type == 'page' ) {
            $postfooter = '';
        // For post-types other than "Pages" press on
        } else {
            $postfooter = '<div class="entry-utility cf">';
            $postfooter .= '<ul class="main-utilities">';
            $postfooter .= '<li>' . thematic_postmeta_authorlink() . '</li>';
            $postfooter .= '<li>' . thematic_postmeta_entrydate() . '</li>';
            $postfooter .= '<li>' . thematic_postfooter_postcomments() . '</li>';
            $postfooter .= '</ul>';
            $postfooter .= '<ul class="sub-utilities">';
            $postfooter .= '<li>' . thematic_postfooter_postcategory() . '</li>';
            $postfooter .= '<li>' . thematic_postfooter_posttags() . '</li>';
                if ( is_user_logged_in() ) {
                $postfooter .= '<li>' . thematic_postfooter_posteditlink() . '</li>';
                }
            $postfooter .= '</ul>';
            $postfooter .= "\n\n\t\t\t\t\t</div><!-- .entry-utility -->\n";
        }
        // Put it on the screen
        echo apply_filters( 'thematic_postfooter', $postfooter ); // Filter to override default post footer
    }

function childtheme_override_postheader_postmeta() {
    // silence!
}



// remove unneeded code from posttags
function childtheme_override_postfooter_postcategory() {
    $postcategory = "\n\n\t\t\t\t\t\t" . '<span class="cat-links">';
    if (is_single()) {
        $postcategory .= __('Categories ', 'thematic') . get_the_category_list(', ');
        $postcategory .= '</span>';
        $posttags = get_the_tags();
        if ( !$posttags ) {
            $postcategory .= '';
        }
    } elseif ( is_category() && $cats_meow = thematic_cats_meow(', ') ) {
        $postcategory .= __('Also posted in ', 'thematic') . $cats_meow;
        $postcategory .= '</span>' . "\n\n\t\t\t\t\t\t";
    } else {
        $postcategory .= __('Posted in ', 'thematic') . get_the_category_list(', ');
        $postcategory .= '</span>' . "\n\n\t\t\t\t\t\t";
    }
    return apply_filters('thematic_postfooter_postcategory',$postcategory);
}



// remove unneeded code from posttags
function childtheme_override_postfooter_posttags() {
    if ( is_single() && !is_object_in_taxonomy( get_post_type(), 'category' ) ) {
        $tagtext = __('Tagged', 'thematic');
        $posttags = get_the_tag_list("<span class=\"tag-links\"> $tagtext ",', ','</span> ');
    } elseif ( is_single() ) {
        $tagtext = __('Tagged', 'thematic');
        $posttags = get_the_tag_list("<span class=\"tag-links\"> $tagtext ",', ','</span> ');
    } elseif ( is_tag() && $tag_ur_it = thematic_tag_ur_it(', ') ) {
        $posttags = '<span class="tag-links">' . __(' Also tagged ', 'thematic') . $tag_ur_it . '</span>' . "\n\n\t\t\t\t\t\t";
    } else {
        $tagtext = __('Tagged', 'thematic');
        $posttags = get_the_tag_list("<span class=\"tag-links\"> $tagtext ",', ','</span>' . "\n\n\t\t\t\t\t\t");
    }
    return apply_filters('thematic_postfooter_posttags',$posttags);
}



// post thumbnail sizing for the flexslider
// images need to be the same size, 750 is about the max visible width
add_theme_support( 'post-thumbnails' );
if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'featured-slider', 750, 425 ); // unlimited width and height
}

// add flexslider to blog home if it has sticky posts
// http://www.woothemes.com/flexslider/
function childtheme_flexslider_slider() {
    if ( is_home() && is_sticky() ) {
        ?>
        <div class="flex-container">
            <div class="flexslider">
                <h2 class="entry-title">Featured</h2>
                <ul class="slides">
                <?php
                query_posts(array('post__in'=>get_option('sticky_posts')));
                if(have_posts()) :
                while(have_posts()) : the_post();
                ?>
                    <li>
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('featured-slider'); ?></a>
                    <p class="flex-caption"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><span>Featured:</span> <?php the_title(); ?></a></p>
                    </li>
                <?php
                endwhile;
                endif;
                    wp_reset_query();
                ?>
                </ul>
            </div>
        </div>
    <?php }
}
add_action('thematic_above_indexloop', 'childtheme_flexslider_slider');

// add flexslider jQuery script only on home if it has sticky posts
// http://www.woothemes.com/flexslider/
function childtheme_flexslider_script() {
if ( is_home() && is_sticky() ) { ?>
<script>jQuery(window).load(function() { jQuery(".flexslider").flexslider(); });</script>
<?php }
}
add_action('wp_head', 'childtheme_flexslider_script');



//override the index loop and remove the sticky posts, which will now be handled by the slider
function childtheme_override_index_loop() {
    $count = 1;
    query_posts(array("post__not_in" =>get_option("sticky_posts"), 'paged' => ( get_query_var('paged') ? get_query_var('paged') : 1 )));
    while ( have_posts() ) : the_post();
        thematic_abovepost();
            echo '<div id="post-' . get_the_ID() . '" ';
            // Checking for defined constant to enable Thematic's post classes
            if ( ! ( THEMATIC_COMPATIBLE_POST_CLASS ) ) {
                post_class();
                echo '>';
            } else {
                echo 'class="';
                thematic_post_class();
                echo '">';
            }
            thematic_postheader();
            ?>
            <div class="entry-content">
                <?php thematic_content();
                wp_link_pages('before=<div class="page-link">' . __('Pages:', 'thematic') . '&after=</div>') ?>
            </div><!-- .entry-content -->
            <?php thematic_postfooter(); ?>
        </div><!-- #post -->

        <?php
        // action hook for insterting content below #post
        thematic_belowpost();
        comments_template();
        if ( $count == thematic_get_theme_opt( 'index_insert' ) ) {
            get_sidebar('index-insert');
        }
        $count = $count + 1;
    endwhile;
}



// kill access and add some new code to be used with the jQuery drop down menu
function childtheme_override_access() { ?>
    <div id="access">
        <div class="menu-button"><span class="menu-title">Menu</span><div class="button"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></div></div>
        <nav id="nav" class="access-nav cf" role="navigation">
               <?php
                if ( ( function_exists("has_nav_menu") ) && ( has_nav_menu( apply_filters('thematic_primary_menu_id', 'primary-menu') ) ) ) {
                    echo  wp_nav_menu(thematic_nav_menu_args());
                } else {
                    echo  thematic_add_menuclass(wp_page_menu(thematic_page_menu_args()));
                }
                ?>
        </nav>
    </div><!-- #access -->
    <?php
}


?>