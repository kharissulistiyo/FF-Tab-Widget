<?php
/*
Plugin Name: FF Tab Widget
Version: 1.1
Description: Display popular posts, recent posts, recent commets, and tags in an animated tabs in a single widget.
Author: <a href="http://www.kharissulistiyono.com">Kharis Sulistiyono</a>
Author URI: http://www.kharissulistiyono.com
Plugin URI: https://github.com/kharissulistiyo/FF-Tab-Widget
*/




/**
 * Front-end scripts
 * @since 0.3
 */
 
 

/** 
 * Plugin setting
 * @since 1.1
 */
 
require_once('includes/fillpress.php'); 
 
 

/** 
* Custom Styles
* @since 1.1
*/

 
if(!function_exists('fftw_custom_frontend_style')){ 
	function fftw_custom_frontend_style(){
?>

<style type="text/css">

/* FFTW Custom Frontend Style*/

.tabs.fftw-nav li{
	background-color:<?php echo esc_html( get_option( 'fftw_nav_bg' ) ); ?>;
	color:<?php echo esc_html( get_option( 'fftw_nav_color' ) ); ?>;
	border:1px solid <?php echo esc_html( get_option( 'fftw_nav_border' ) ); ?>;
	border-right:none;	
}

.tabs.fftw-nav li:last-child{
	border-right:1px solid <?php echo esc_html( get_option( 'fftw_nav_border' ) ); ?> !important;
}

.tabs.fftw-nav li.tabs_active{
	border-color:<?php echo esc_html( get_option( 'fftw_nav_border' ) ); ?>;
	background-color:<?php echo esc_html( get_option( 'fftw_nav_bg_active' ) ); ?>;
	color:<?php echo esc_html( get_option( 'fftw_nav_color_active' ) ); ?>;
}

.fftw-panes{
	background-color:<?php echo esc_html( get_option( 'fftw_pane_bg' ) ); ?>;
}

.fftw-panes .tags a{
	background-color:<?php echo esc_html( get_option( 'fftw_nav_border' ) ); ?>;
}

</style>

<?php }}

add_action('wp_head', 'fftw_custom_frontend_style');
 
 

if(!function_exists('fftw_enqueue_scripts')){

	function fftw_enqueue_scripts(){
		
		// Styles
		wp_enqueue_style( 'jquery-tabs', plugins_url( 'includes/styles/jquery-tabs.css' , __FILE__ ), false, '0.0.1' );
		wp_enqueue_style( 'fftw', plugins_url( 'fftw.css' , __FILE__ ), false, '0.0.1' );
	
		// JSes
		wp_register_script('jquery-tabs', plugins_url( 'includes/js/jquery-tabs.js' , __FILE__ ), array('jquery'), '0.3.0', true );
		wp_register_script('jquery-tabs-init', plugins_url( 'includes/js/jquery-tabs-init.js' , __FILE__ ), array('jquery-tabs'), '0.0.1', true );
		
		wp_enqueue_script('jquery-tabs');
		wp_enqueue_script('jquery-tabs-init');
		
	}

}

add_action( 'wp_enqueue_scripts', 'fftw_enqueue_scripts' );




/**
 * Register FF Tab Widget
 * @since 0.0.1
 */
 
 
function register_ff_tab_widget() {
    register_widget( 'FF_Tab_Widget' );
}
add_action( 'widgets_init', 'register_ff_tab_widget' );


 
function fftw_set_post_views($postID) {
    $count_key = 'fftw_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); 





/**
 * Add filter to single post
 * @since 0.0.1
 * updated 1.0
 */
 

function fftw_post_view($content) {
	if(is_single()){
		$content .= fftw_set_post_views(get_the_ID());
		// return fftw_get_post_views(get_the_ID());
	}
	return $content;
} 

add_filter( 'the_content', 'fftw_post_view' ); 
 

/**
 * FF Tab Widget Class
 * @since 0.0.1
 */


class FF_Tab_Widget extends WP_Widget {


	
	/**
	 * Widget actual process
	 * Reference: http://codex.wordpress.org/Plugins/WordPress_Widgets_Api
	 * @since 0.0.1
	 */
	
	public function __construct() {
		$widget_ops = array('classname' => 'ff_tab_widget', 'description' => __('Display popular posts, recent posts, recent commets, and tags in an animated tabs.'));
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct(null, __('FF Tab Widget'), $widget_ops, $control_ops);
	}
	
	
	
	
	/**
	 * Widget output
	 * Reference: http://codex.wordpress.org/Plugins/WordPress_Widgets_Api
	 * @since 0.0.1
	 */ 
	 
	public function widget( $args, $instance ) {
		extract( $args );
		$pop = empty( $instance['pop'] ) ? '' : $instance['pop'];
		$poplimit = empty( $instance['poplimit'] ) ? '' : $instance['poplimit'];
		$recent = empty( $instance['recent'] ) ? '' : $instance['recent'];
		$recentlimit = empty( $instance['recentlimit'] ) ? '' : $instance['recentlimit'];
		$comment = empty( $instance['comment'] ) ? '' : $instance['comment'];
		$commentlimit = empty( $instance['commentlimit'] ) ? '' : $instance['commentlimit'];
		$tag = empty( $instance['tag'] ) ? '' : $instance['tag'];
		$taglimit = empty( $instance['taglimit'] ) ? '' : $instance['taglimit'];
		
		echo $before_widget;
	?>	
		
		
		
		
	
	<div class="ff-tab-widget-wrap">
	
		<ul class="fftw-nav tabs">
			<?php
				$fftw_nav1 = $pop;			
				$fftw_nav2 = $recent;
				$fftw_nav3 = $comment;
				$fftw_nav4 = $tag;
			?>
			
			<?php if(!empty($fftw_nav1)){ ?>
			<li class="fftw_nav1"><?php echo esc_html($fftw_nav1); ?></li>
			<?php } ?>
			<?php if(!empty($fftw_nav2)){ ?>
			<li class="fftw_nav2"><?php echo esc_html($fftw_nav2); ?></li>
			<?php } ?>
			<?php if(!empty($fftw_nav3)){ ?>
			<li class="fftw_nav3"><?php echo esc_html($fftw_nav3); ?></li>
			<?php } ?>
			<?php if(!empty($fftw_nav4)){ ?>
			<li class="fftw_nav4"><?php echo esc_html($fftw_nav4); ?></li>
			<?php } ?>
		</ul>
		
		<div class="fftw-panes tabs-panes">
		
			
			<?php if(!empty($fftw_nav1)){ ?>
			<div class="fftw-pane1">
				<?php 
				
				// Popular posts
							
				$popular = new WP_Query( array( 
					'posts_per_page' => $poplimit, 
					'meta_key' => 'fftw_post_views_count', 
					'orderby' => 'meta_value_num', 
					'order' => 'DESC'  
				) );
				
				$html = '<ul class="fftw-show-thumbnail">';
				while ( $popular->have_posts() ) : $popular->the_post();
					$html .= '<li>';
					$html .= '<a href="'. get_permalink() .'">';
					$html .= get_the_post_thumbnail(get_the_ID(), array(40,40));	
					$html .= '<span>' . get_the_title() . '</span>';
					$html .= '</a>';
					$html .= '</li>';
				endwhile;		
				$hmtl .= '</ul>';	
				
				echo $html; 
				
				?>
			</div><!-- /.fftw-pane1 -->	
			<?php } ?>
		
			
			<?php if(!empty($fftw_nav2)){ ?>
			<div class="fftw-pane2">
			
				<?php
				
				/**
				 * Recent posts
				 * ------------
				 */			 

				global $post;

				$args = array(
					'post_type' => 'post', 
					'numberposts' => $recentlimit		
				);

				$get_query_posts = get_posts($args);

				if($get_query_posts) :

					$count=0;
					$html = '<ul class="fftw-show-thumbnail">';
					foreach($get_query_posts as $post) : 
						setup_postdata($post);
						$count++;					
						
						$html .= '<li>';
						$html .= '<a href="'. get_permalink() .'">';
						$html .= get_the_post_thumbnail(get_the_ID(), array(40,40));	
						$html .= '<span>' . get_the_title() . '</span>';
						$html .= '</a>';
						$html .= '</li>';
						
					endforeach;	
					$html .= '</ul>';
					
					echo $html;

				endif;

				// End of recent posts
				?>		
			
			</div> <!-- /.fftw-pane-2 -->
			<?php } ?>
			
			
			<?php if(!empty($fftw_nav3)){ ?>
			<div class="fftw-pane3">
				<?php
				
				// Comments
				$GLOBALS['comment'] = $comment;
				$comments = get_comments( apply_filters( 'widget_comments_args', array( 
					'number' => $commentlimit, 
					'status' => 'approve', 
					'post_status' => 'publish' 
				) ) );	
				
				$output = '<ul class="fftw-show-thumbnail">';
				if ( $comments ) {
					// Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
					$post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
					_prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

					foreach ( (array) $comments as $comment) {
						$avatar = get_avatar( $comment, 40 );
						$output .=  '<li class="recentcomments">'. $avatar . ' ' . '<span>' . /* translators: comments widget: 1: comment author, 2: post link */ sprintf(_x('%1$s on %2$s', false), get_comment_author_link(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . get_the_title($comment->comment_post_ID) . '</a></span>') . '</li>';
					}
				}
				$output .= '</ul>';

				echo $output; 	
				
				?>
			</div><!-- /.fftw-pane3 -->
			<?php } ?>

			
			<?php if(!empty($fftw_nav4)){ ?>
			<div class="fftw-pane4">
				<?php
				
				// Tags
				
				$tags = get_tags(array('number' => $taglimit)); 
				$html = '<ul class="tags">';
				foreach ( $tags as $tag ) {
					$tag_link = get_tag_link( $tag->term_id );
					
					$html .= '<li>';	
					$html .= "<a href='{$tag_link}' title='{$tag->name}'>";
					$html .= "{$tag->name} ({$tag->count})</a>";
					$html .= '</li>';
					
				}
				$html .= '</ul>';
				echo $html;				
				
				?>
			</div><!-- /.fftw-pane4 -->
			<?php } ?>
			
			
		</div>
		
	</div>	
		
		
		
		
	<?php	
		
		echo $after_widget;
		
	}	
	

	
	
	/**
	 * Widget form on admin
	 * Reference: http://codex.wordpress.org/Plugins/WordPress_Widgets_Api
	 * @since 0.0.1
	 */ 
	
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array(  
			'pop' => 'Pop', 
			'poplimit' => 10,
			'recent' => 'Recent', 
			'recentlimit' => 10, 
			'comment' => 'Comments', 
			'commentlimit' => 10,  
			'tag' => 'Tags', 
			'taglimit' => 10 
		) );
		$pop = strip_tags($instance['pop']);
		$poplimit = strip_tags($instance['poplimit']);
		$recent = strip_tags($instance['recent']);
		$recentlimit = strip_tags($instance['recentlimit']);
		$comment = strip_tags($instance['comment']);
		$commentlimit = strip_tags($instance['commentlimit']);
		$tag = strip_tags($instance['tag']);
		$taglimit = strip_tags($instance['taglimit']);

	?>	
	
		
		<!-- = Popular posts setting -->
	
		<p><span><strong><?php _e('Popular posts:'); ?></strong></span><br />
		
		<label for="<?php echo $this->get_field_id('pop'); ?>" style="display:inline;"><?php _e('Label: ');?></label><input id="<?php echo $this->get_field_id('pop'); ?>" size="29" name="<?php echo $this->get_field_name('pop'); ?>" type="text" value="<?php echo esc_attr($pop); ?>" /> <br />
		
		<label for="<?php echo $this->get_field_id('poplimit'); ?>" style="display:inline;"><?php _e('Limit number: ');?></label><input id="<?php echo $this->get_field_id('poplimit'); ?>" size="2" name="<?php echo $this->get_field_name('poplimit'); ?>" type="text" value="<?php echo esc_attr($poplimit); ?>" />
		
		</p>
		
		<!-- / Popular posts setting -->
		
		
		<!-- = Recent posts setting -->
		
		<p><span><strong><?php _e('Recent posts:'); ?></strong></span><br />
		
		<label for="<?php echo $this->get_field_id('recent'); ?>" style="display:inline;"><?php _e('Label: ');?></label><input id="<?php echo $this->get_field_id('recent'); ?>" size="29" name="<?php echo $this->get_field_name('recent'); ?>" type="text" value="<?php echo esc_attr($recent); ?>" /> <br />
		
		<label for="<?php echo $this->get_field_id('recentlimit'); ?>" style="display:inline;"><?php _e('Limit number: ');?></label><input id="<?php echo $this->get_field_id('recentlimit'); ?>" size="2" name="<?php echo $this->get_field_name('recentlimit'); ?>" type="text" value="<?php echo esc_attr($recentlimit); ?>" />
		
		</p>
		
		<!-- / Recent posts setting -->
		
		
		
		<!-- = Recent comments setting -->
		
		<p><span><strong><?php _e('Recent comments:'); ?></strong></span><br />
		
		<label for="<?php echo $this->get_field_id('comment'); ?>" style="display:inline;"><?php _e('Label: ');?></label><input id="<?php echo $this->get_field_id('comment'); ?>" size="29" name="<?php echo $this->get_field_name('comment'); ?>" type="text" value="<?php echo esc_attr($comment); ?>" /> <br />
		
		<label for="<?php echo $this->get_field_id('commentlimit'); ?>" style="display:inline;"><?php _e('Limit number: ');?></label><input id="<?php echo $this->get_field_id('commentlimit'); ?>" size="2" name="<?php echo $this->get_field_name('commentlimit'); ?>" type="text" value="<?php echo esc_attr($commentlimit); ?>" />
		
		</p>
		
		<!-- / Recent comments setting -->
		
		
		<!-- = Tags setting -->
		
		<p><span><strong><?php _e('Tags:'); ?></strong></span><br />
		
		<label for="<?php echo $this->get_field_id('tag'); ?>" style="display:inline;"><?php _e('Label: ');?></label><input id="<?php echo $this->get_field_id('tag'); ?>" size="29" name="<?php echo $this->get_field_name('tag'); ?>" type="text" value="<?php echo esc_attr($tag); ?>" /> <br />
		
		<label for="<?php echo $this->get_field_id('taglimit'); ?>" style="display:inline;"><?php _e('Limit number: ');?></label><input id="<?php echo $this->get_field_id('taglimit'); ?>" size="2" name="<?php echo $this->get_field_name('taglimit'); ?>" type="text" value="<?php echo esc_attr($taglimit); ?>" />
		
		</p>
		
		<!-- / Tags setting -->
		
		<p class="description"><?php _e('Leave the "Label" field(s) blank to hide.'); ?></p>
		
	
	<?php
	}
	
	
	
	
	
	
	/**
	 * Processes widget options to be saved
	 * Reference: http://codex.wordpress.org/Plugins/WordPress_Widgets_Api
	 * @since 0.0.1
	 */ 
	 
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['pop'] = strip_tags($new_instance['pop']);
		$instance['poplimit'] = strip_tags($new_instance['poplimit']);
		$instance['recent'] = strip_tags($new_instance['recent']);
		$instance['recentlimit'] = strip_tags($new_instance['recentlimit']);	
		$instance['comment'] = strip_tags($new_instance['comment']);
		$instance['commentlimit'] = strip_tags($new_instance['commentlimit']);
		$instance['tag'] = strip_tags($new_instance['tag']);
		$instance['taglimit'] = strip_tags($new_instance['taglimit']);		
		return $instance;
	}	
	
	
	
	

}