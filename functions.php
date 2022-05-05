<?php

   /**
    * Eelco: get rid of google fonts, see https://ffw.press/blog/how-to/remove-google-fonts-wordpress/
    * 
    * Grep on the awaken theme:
    * 
    *    $ grep "googleapis\|gstatic" * -r
    * 
    * resulted in:
    * 
    *    functions.php:        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    * 
    * In functions.php, the $fonts_url was used to enque the style: 
    * 
    *   wp_enqueue_style( 'awaken-fonts', awaken_fonts_url(), array(), null );
    * 
    * So, here we dequeue the 'awaken-fonts' as described on the ffw.press blog
    * 
    */ 
   function awaken_remove_google_fonts() {
       wp_dequeue_style('awaken-fonts');
       wp_deregister_style('awaken-fonts');
   }

   add_action('wp_enqueue_scripts', 'awaken_remove_google_fonts', 100);

   add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );
   function enqueue_parent_theme_style() {
     wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
   }



   if ( ! function_exists( 'awaken_posted_on' ) ) :
   /**
    * Prints HTML with meta information for the current post-date/time and author.
    */
   function awaken_posted_on() {
         $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
         if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
         }

         $time_string = sprintf( $time_string,
                  esc_attr( get_the_date( 'c' ) ),
                  esc_html( get_the_date() ),
                  esc_attr( get_the_modified_date( 'c' ) ),
                  esc_html( get_the_modified_date() )
	);

         $posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

         $byline = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';


         /*
          * Added by Eelco: get the viewcount and echo it
          */

         global $post;
         if( isset( $post->ID ) ) {
            $post_id = $post->ID;
            $views = intval( get_post_meta( $post_id, '_post_views', true ) );
         } else {
            $views = "unkown";
         }

         echo '<span class="posted-on">' . $posted_on . '</span><span class="byline">' . $byline . '</span><span class="view-count">'.$views.' views</span>';

}
endif;

?>
