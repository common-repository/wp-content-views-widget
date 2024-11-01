<?php
/**
 * Plugin Name: Query & Display with Wordpress Content Views Widget
 * Plugin URI: https://webdeveloping.gr/projects/display-wordpress-content-views-widget
 * Description: Query & Display any Wordpress Content in a widget with WP Content Views Widget on the fly!
 * Version: 2.0
 * Author: webdeveloping.gr
 * Author URI: https://webdeveloping.gr
 * License: GPL2
 * Created On: 14-06-2018
 * Updated On: 29-11-2020
 */

class Content_Widget extends WP_Widget {

		public $plugin = 'ContentWidget';		
		public $name = 'Content Widget';
		public $shortName = 'Content Widget';
		public $slug = 'content-widget';
		public $dashicon = 'dashicons-editor-table';
		public $proUrl = 'https://webdeveloping.gr/product/wordpress-content-views-widget-pro';
		public $menuPosition ='50';
		public $localizeBackend;
		public $localizeFrontend;
		public $description = 'Display content from post type or taxonomy terms in a widget';

	public function __construct() {	
		
		add_action('admin_enqueue_scripts', array($this, 'BackEndScripts') );
		add_action('wp_enqueue_scripts', array($this, 'FrontEndScripts') );
		
		$widget_details = array(
				'classname' => $this->slug,
				'description' => $this->description
		);

		parent::__construct( $this->slug, $this->name, $widget_details );
	
	}
	
	public function BackEndScripts(){
			wp_enqueue_style( $this->plugin."adminCss", plugins_url( "/css/backend.php", __FILE__ ) );	
			wp_enqueue_style( $this->plugin."adminCss");	

			if( ! wp_script_is( $this->plugin."_fa", 'enqueued' ) ) {
				wp_enqueue_style( $this->plugin."_fa", plugins_url( '/css/font-awesome.min.css', __FILE__ ));
			}
			
			wp_enqueue_script('jquery');		
			wp_enqueue_script( $this->plugin."adminJs", plugins_url( "/js/backend.js", __FILE__ ) , array('jquery') , null, true);	
						
			$this->localizeBackend = array( 
				'plugin_url' => plugins_url( '', __FILE__ ),
				'siteUrl'	=>	site_url(),
				'plugin_wrapper'=> $this->plugin,
			);		
			wp_localize_script($this->plugin."adminJs", $this->plugin , $this->localizeBackend );
			wp_enqueue_script( $this->plugin."adminJs");
			
	}
	
	public function FrontEndScripts(){
			wp_enqueue_style( $this->plugin."css", plugins_url( "/css/frontend.php", __FILE__ ) );	
			wp_enqueue_style( $this->plugin."css");


			wp_enqueue_script('jquery');
			wp_enqueue_script( $this->plugin."js", plugins_url( "/js/frontend.js", __FILE__ ) , array('jquery') , null, true);	
			
			$this->localizeFrontend = array( 
				'plugin_url' => plugins_url( '', __FILE__ ),
				'siteUrl'	=>	site_url(),
				'plugin_wrapper'=> $this->plugin,
			);		
			wp_localize_script($this->plugin."js", $this->plugin , $this->localizeFrontend );
			wp_enqueue_script( $this->plugin."js");
	}		
			
	
	public function form( $instance ) {
        $title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
		$chooseContent = ( !empty( $instance[$this->plugin.'chooseContent'] ) ) ? $instance[$this->plugin.'chooseContent'] : '';
		$contentType = ( !empty( $instance[$this->plugin.'contentType'] ) ) ? $instance[$this->plugin.'contentType'] : '';
		$taxonomy = ( !empty( $instance[$this->plugin.'taxonomy'] ) ) ? $instance[$this->plugin.'taxonomy'] : '';
		$contentTax = ( !empty( $instance[$this->plugin.'contentTax'] ) ) ? $instance[$this->plugin.'contentTax'] : '';
		$taxTerms = ( !empty( $instance[$this->plugin.'taxTerms'] ) ) ? $instance[$this->plugin.'taxTerms'] : '';
		$hide_empty = ( !empty( $instance[$this->plugin.'hide_empty'] ) ) ? $instance[$this->plugin.'hide_empty'] : '';
				
		$show_image = ( !empty( $instance[$this->plugin.'show_image'] ) ) ? $instance[$this->plugin.'show_image'] : '';
		$image_size = ( !empty( $instance[$this->plugin.'image_size'] ) ) ? $instance[$this->plugin.'image_size'] : '';
		
		$content = ( !empty( $instance[$this->plugin.'content'] ) ) ? $instance[$this->plugin.'content'] : '';
		$excerpt = ( !empty( $instance[$this->plugin.'excerpt'] ) ) ? $instance[$this->plugin.'excerpt'] : '';
		$showTaxonomies = ( !empty( $instance[$this->plugin.'showTaxonomies'] ) ) ? $instance[$this->plugin.'showTaxonomies'] : '';

		$order = ( !empty( $instance[$this->plugin.'order'] ) ) ? $instance[$this->plugin.'order'] : '';
		$orderBy = ( !empty( $instance[$this->plugin.'orderBy'] ) ) ? $instance[$this->plugin.'orderBy'] : '';

		$view = ( !empty( $instance[$this->plugin.'view'] ) ) ? $instance[$this->plugin.'view'] : '';
		$columns = ( !empty( $instance[$this->plugin.'columns'] ) ) ? $instance[$this->plugin.'columns'] : '';
		$animation = ( !empty( $instance[$this->plugin.'animation'] ) ) ? $instance[$this->plugin.'animation'] : '';
		$align = ( !empty( $instance[$this->plugin.'align'] ) ) ? $instance[$this->plugin.'align'] : '';
		
		$number = ( !empty( $instance[$this->plugin.'number'] ) ) ? $instance[$this->plugin.'number'] : '';
		
        ?>

        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>">Title: </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
		
		<p>
			<label for="<?php echo $this->get_field_name( $this->plugin.'chooseContent' ); ?>">Choose Content: </label>
			<select class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'chooseContent' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'chooseContent' ); ?>" >
				
					<?php if(!empty (esc_attr( $chooseContent )) ){
						?><option value='<?php echo esc_attr( $chooseContent ); ?>'><?php echo esc_attr( $chooseContent ); ?></option><?php
						}else ?><option value=''>Select...</option> <?php 
					?>
				<option value='taxonomy'>Category Terms</option>
				<option value='content'>Content Type</option>
			</select>
		</p>
		

		
		<?php if($chooseContent=='taxonomy'){ ?>
			<p class='selectTaxonomy'>
				<label for="<?php echo $this->get_field_name( $this->plugin.'taxonomy' ); ?>">Taxonomy: </label>
				<?php 
					
					print  "<select  class='widefat' id='".$this->get_field_id( $this->plugin.'taxonomy' )."' name='".$this->get_field_name( $this->plugin.'taxonomy' )."' >";
					
					if(!empty (esc_attr( $taxonomy )) ){
					?><option value='<?php echo esc_attr( $taxonomy ); ?>'><?php echo esc_attr( $taxonomy ); ?></option><?php
					}else ?><option value=''>Select...</option> 
					<option value='category'>category</option>
					<option value='post_tag'>post_tag</option>
					
					</select>
					<a class='pro' href='<?php print $this->proUrl; ?>' target='_blank'>Custom Taxonomies in PRO VERSION</a>
			</p>

				<label for="<?php echo $this->get_field_name( $this->plugin.'hide_empty' ); ?>">Hide Empty Terms: </label>
				<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'hide_empty' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'hide_empty' ); ?>" >
						<?php if(!empty (esc_attr( $hide_empty )) ){
							?><option value='<?php echo esc_attr( $hide_empty ); ?>'><?php echo esc_attr( $hide_empty ); ?></option><?php
							}else ?><option value=''>Select...</option>			
							<option value='true'>true</option>
							<option value='false'>false</option>
				</select>			
			
		<?php }elseif($chooseContent=='content'){ ?>
		
			<p class='selectPostType'>
				<label for="<?php echo $this->get_field_name( $this->plugin.'contentType' ); ?>">Content Type: </label>
				<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'contentType' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'contentType' ); ?>" >
				
							<?php if(!empty (esc_attr( $contentType )) ){
								?><option value='<?php echo esc_attr( $contentType ); ?>'><?php echo esc_attr( $contentType ); ?></option><?php
								}else ?><option value=''>Select...</option>
					<option value='post'>post</option>
					<option value='page'>page</option>
					
					</select>
					<a class='pro' href='<?php print $this->proUrl; ?>' target='_blank'>Custom Post Types in PRO VERSION</a>								
			</p>
			

			<?php if(!empty(esc_attr( $contentType ) )){ ?>

			
				<a class='pro' href='<?php print $this->proUrl; ?>' target='_blank'>Show Specific Post - in PRO Version</a>
				</label><br/>	
				<input class="widefat colorp"  readonly disabled type="text" placeholder='Select Specific Post' />	
					

			<label for="<?php echo $this->get_field_name( $this->plugin.'show_image' ); ?>">Show Image: </label>
			<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'show_image' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'show_image' ); ?>" >
					<?php if(!empty (esc_attr( $show_image )) ){
						?><option value='<?php echo esc_attr( $show_image ); ?>'><?php echo esc_attr( $show_image ); ?></option><?php
						}else ?><option value=''>Select...</option>			
						<option value='no'>no</option>
						<option value='yes'>yes</option>
			</select>
			
			<?php  if(!empty(esc_attr( $show_image ) ) && esc_attr( $show_image )=='yes'){ 
				$sizes = get_intermediate_image_sizes();
			?>
				<label for="<?php echo $this->get_field_name( $this->plugin.'image_size' ); ?>">Image Size: </label>
				<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'image_size' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'image_size' ); ?>" >
						<?php if(!empty (esc_attr( $image_size )) ){
							?><option value='<?php echo esc_attr( $image_size ); ?>'><?php echo esc_attr( $image_size ); ?></option><?php
							}else ?><option value=''>Select...</option>			
							<?php
								foreach ( $sizes as $size ) {
									echo "<option value='".esc_attr($size)."'>" . $size . "</option>";
												
								}								
							?>
				</select>			
			<?php } ?>

				<label for="<?php echo $this->get_field_name( $this->plugin.'content' ); ?>">Show Content: </label>
				<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'content' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'content' ); ?>" >
						<?php if(!empty (esc_attr( $content )) ){
							?><option value='<?php echo esc_attr( $content ); ?>'><?php echo esc_attr( $content ); ?></option><?php
							}else ?><option value=''>Select...</option>			
							<option value='no'>no</option>
							<option value='yes'>yes</option>
				</select>				
				
				<?php if(!empty(esc_attr( $content ) ) && esc_attr( $content ) =='no' ){ ?>
				<label for="<?php echo $this->get_field_name( $this->plugin.'excerpt' ); ?>">Show Excerpt: </label>
				<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'excerpt' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'excerpt' ); ?>" >
						<?php if(!empty (esc_attr( $excerpt )) ){
							?><option value='<?php echo esc_attr( $excerpt ); ?>'><?php echo esc_attr( $excerpt ); ?></option><?php
							}else ?><option value=''>Select...</option>			
							<option value='no'>no</option>
							<option value='yes'>yes</option>
				</select>
				<?php } ?>
			
			<?php
				$taxonomies = get_object_taxonomies( esc_attr( $contentType ) );					
				$tExclude = array('post_name','post_format','language','translation_of'); 
				if(!empty($taxonomies)){
					?>

				<p class='contentTax'>
					<label for="<?php echo $this->get_field_name( $this->plugin.'contentTax' ); ?>">Content Taxonomy: </label>
					<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'contentTax' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'contentTax' ); ?>" >
					<?php 

					if(!empty(esc_attr( $contentTax )) ){
						?><option value='<?php echo esc_attr( $contentTax ); ?>'><?php echo esc_attr( $contentTax ); ?></option><?php
					}else ?><option value=''>Select...</option> <?php
						
					foreach( $taxonomies as $tax){										
						if(!in_array($tax,$tExclude) ){
							echo "<option value='".esc_attr($tax)."'>" . $tax . "</option>";
						}	
					}					
					?></select>	
				</p>			
				<?php } ?>
				
				<?php if(!empty(esc_attr( $contentTax )) ){					
					$terms = get_terms( array( 
							'taxonomy' => esc_attr( $contentTax ),
							'hide_empty' => 'true',
							'number' => false,						
					)); ?>
					<label for="<?php echo $this->get_field_name( $this->plugin.'taxTerms' ); ?>">Taxonomy Term: </label>
					<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'taxTerms' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'taxTerms' ); ?>" >	<?php				
					if(!empty(esc_attr( $taxTerms )) ){
						$name = get_term_by('id',$taxTerms , esc_attr( $contentTax ) );
						
						?><option value='<?php echo $name->term_id; ?>'><?php echo $name->name; ?></option><?php
					}else ?><option value=''>Select...</option> <?php	
					
					foreach ($terms as $term) {	
						echo "<option value='".esc_attr($term->term_id)."'>" . $term->name . "</option>";
					 } ?>
					 
					 </select>
				 <?php } ?>

				<?php if(!empty($taxonomies)){ ?>
				
					<label for="<?php echo $this->get_field_name( $this->plugin.'showTaxonomies' ); ?>">Show Content Taxonomies: </label>
					<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'showTaxonomies' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'showTaxonomies' ); ?>" >
							<?php if(!empty (esc_attr( $showTaxonomies )) ){
								?><option value='<?php echo esc_attr( $showTaxonomies ); ?>'><?php echo esc_attr( $showTaxonomies ); ?></option><?php
								}else ?><option value=''>Select...</option>			
								<option value='no'>no</option>
								<option value='yes'>yes</option>
					</select>
				<?php } ?>	
				
			<?php } ?>			
		



				
		<?php }//end of checking if taxonomy or content to display ?>
				
				<hr/>
				
				
				
				<label for="<?php echo $this->get_field_name( $this->plugin.'order' ); ?>">Content Order: </label>
				<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'order' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'order' ); ?>" >
						<?php if(!empty (esc_attr( $order )) ){
							?><option value='<?php echo esc_attr( $order ); ?>'><?php echo esc_attr( $order ); ?></option><?php
							}else ?><option value=''>Select...</option>			
							<option value='DESC'>DESC</option>
							<option value='ASC'>ASC</option>
				</select>
				
				<label for="<?php echo $this->get_field_name( $this->plugin.'orderBy' ); ?>">Content Order By: </label>
				<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'orderBy' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'orderBy' ); ?>" >
						<?php if(!empty (esc_attr( $orderBy )) ){
							?><option value='<?php echo esc_attr( $orderBy ); ?>'><?php echo esc_attr( $orderBy ); ?></option><?php
							}else ?><option value='none'>Select...</option>
							<option value='none'>none</option>							
							<option value='ID'>ID</option>
							<option value='author'>author</option>
							<option value='title'>title</option>
							<option value='name'>name</option>
							<option value='date'>date</option>
							<option value='rand'>rand</option>
				</select>


				<label for="<?php echo $this->get_field_name( $this->plugin.'number' ); ?>">Number of Posts: </label>				
				
						<?php if(!empty (esc_attr( $number )) ){ ?>
							
							<input class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'number' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'number' ); ?>" type="number"  value="<?php echo esc_attr( $number ); ?>" />
							<?php
						}else{ ?>
							<input class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'number' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'number' ); ?>" type="number" placeholder='Number of Posts' />
						<?php } ?>
						
				<hr/>
				
				<div class=' contentWidgetStyleToggler'><a href='#'>Click to Style <i class='fa fa-angle-down'></i> </a></div>
				
				<div class='<?php print $this->plugin."Style" ; ?>'>
	
					<label for="<?php echo $this->get_field_name( $this->plugin.'view' ); ?>">How to View: </label>
					<a class='pro' href='<?php print $this->proUrl; ?>' target='_blank'>Slideshow included in PRO Version</a>
					<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'view' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'view' ); ?>" >
							<?php if(!empty (esc_attr( $view )) ){
								?><option value='<?php echo esc_attr( $view ); ?>'><?php echo esc_attr( $view ); ?></option><?php
								}else ?><option value=''>Select...</option>			
								<option value='List'>List</option>
					</select>

						<label for="<?php echo $this->get_field_name( $this->plugin.'columns' ); ?>">Number of Columns: </label>
						<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'columns' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'columns' ); ?>" >
								<?php if(!empty (esc_attr( $columns )) ){
									?><option value='<?php echo esc_attr( $columns ); ?>'><?php echo esc_attr( $columns ); ?></option><?php
									}else ?><option value=''>Select...</option>	
									<option value='column-1'>column-1</option>
									<option value='columns-2'>columns-2</option>
									<option value='columns-3'>columns-3</option>
									<option value='columns-4'>columns-4</option>
									<option value='columns-5'>columns-5</option>
									<option value='columns-6'>columns-6</option>
						</select>				
					
					<hr/>
					
					<label for="<?php echo $this->get_field_name( $this->plugin.'animation' ); ?>">
						<a class='pro' href='<?php print $this->proUrl; ?>' target='_blank'>Animation - in PRO Version</a>
					</label>

					<input class="widefat "  readonly disabled type="text" placeholder='Select Animation' />

					<label for="<?php echo $this->get_field_name( $this->plugin.'align' ); ?>">Alignment: </label>
					<select  class='widefat' id="<?php echo $this->get_field_id( $this->plugin.'align' ); ?>" name="<?php echo $this->get_field_name( $this->plugin.'align' ); ?>" >
							<?php if(!empty (esc_attr( $align )) ){
								?><option value='<?php echo esc_attr( $align ); ?>'><?php echo esc_attr( $align ); ?></option><?php
								}else ?><option value=''>Select...</option>			
										  <option value="left">left</option>
										  <option value="right">right</option>
										  <option value="center">center</option>


					</select>					
				
				</div>
				
				<p></p>
			<p style='text-align:center;font-style:16px;font-weight:bold;'>
				<a class='proButton' href='<?php print $this->proUrl; ?>' target='_blank'><i class='fa fa-tags'></i> Get Pro Version for 15&euro;!</a>
			</p>				
        <?php
	}
	

	
	public function widget( $args, $instance ) {

		echo $args['before_widget']; 
		if( !empty( $instance['title'] ) ) {
			echo "<h3>".$args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'] ."</h3>";
		}
	
		if($instance[$this->plugin.'chooseContent'] =='content'){
		
		
		if($instance[$this->plugin.'taxTerms'] !=''){
			$args = array(
				'post_type' => esc_attr($instance[$this->plugin.'contentType']),
				'p' => esc_attr($instance[$this->plugin.'getPosts']),
				'post__not_in'  => array(),
				'post_status' => array('publish' ), 		
				'taxonomy'  => array(esc_attr($instance[$this->plugin.'contentTax']) ),
				'tax_query' => array(
					array(
						'taxonomy' => esc_attr($instance[$this->plugin.'contentTax']),
						'field'    => 'id',
						'terms'    => esc_attr($instance[$this->plugin.'taxTerms']),
					),
				),
				'hide_empty' => false,
				'offset' => '',
				'orderby' => 'title',
				'order'   => 'DESC',
				'posts_per_page' => esc_attr($instance[$this->plugin.'number']),
			);		
		}else{
			$args = array(
				'post_type' => esc_attr($instance[$this->plugin.'contentType']),
				'post__not_in'  => array(),
				'post_status' => array('publish' ), 		
				'taxonomy'  => array(esc_attr($instance[$this->plugin.'contentTax']) ),
				'hide_empty' => false,
				'offset' => '',
				'orderby' => esc_attr($instance[$this->plugin.'orderBy']),
				'order' => esc_attr($instance[$this->plugin.'order']),	
				'posts_per_page' => esc_attr($instance[$this->plugin.'number']),
			);				
		}


		$the_query = new WP_Query( $args );		
		?>

		<?php if ( $the_query->have_posts() ) : ?>
		
			<ul class='clearfix <?php if( !empty( $instance[$this->plugin.'animation'] )) print esc_attr($instance[$this->plugin.'animation']); ?>  '>
			
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<li class='<?php if( !empty( $instance[$this->plugin.'columns'] ) && esc_attr($instance[$this->plugin.'view']) !='Slideshow' ) print esc_attr($instance[$this->plugin.'columns']); ?> 
				<?php if( !empty( $instance[$this->plugin.'align'] )) print esc_attr($instance[$this->plugin.'align']); ?>
				' >
					<h5>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
					</h5>
					
					<?php if( !empty( $instance[$this->plugin.'show_image'] ) && esc_attr(  $instance[$this->plugin.'show_image']=='yes' ) ) { ?>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php the_post_thumbnail( $instance[$this->plugin.'image_size'] ); ?>
						</a>
					<?php }  ?>

					<?php if( !empty( $instance[$this->plugin.'content'] ) && esc_attr(  $instance[$this->plugin.'content']=='yes' ) ) { ?>
							<?php the_content() ; ?>
					<?php }  ?>	
					
					<?php if( !empty( $instance[$this->plugin.'excerpt'] ) && esc_attr(  $instance[$this->plugin.'excerpt']=='yes' ) ) { ?>
							<?php the_excerpt() ; ?>
					<?php }  ?>					


					<?php if( !empty( $instance[$this->plugin.'showTaxonomies'] ) && esc_attr(  $instance[$this->plugin.'showTaxonomies']=='yes' ) ) { ?>
							<?php the_taxonomies(); ?>
					<?php }  ?>	
					
				</li>
			<?php endwhile; ?>
			</ul>
			
			<?php wp_reset_postdata(); ?>

		<?php else : ?>
			<p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
		<?php endif;
		
		}else{
			if(!empty(esc_attr($instance[$this->plugin.'hide_empty'])) && esc_attr($instance[$this->plugin.'hide_empty']=='false') ){
				$terms = get_terms( array( 
						'taxonomy' => esc_attr($instance[$this->plugin.'taxonomy']),
						'hide_empty' => false,
						'orderby' => esc_attr($instance[$this->plugin.'orderBy']),
						'order' => esc_attr($instance[$this->plugin.'order']),
						'number' => esc_attr($instance[$this->plugin.'number']),						
				));
		}else{
				$terms = get_terms( array( 
						'taxonomy' => esc_attr($instance[$this->plugin.'taxonomy']),
						'hide_empty' => true,
						'orderby' => esc_attr($instance[$this->plugin.'orderBy']),
						'order' => esc_attr($instance[$this->plugin.'order']),
						'number' => esc_attr($instance[$this->plugin.'number']),						
				));
		}
				?>
				
				<ul class='clearfix <?php if( !empty( $instance[$this->plugin.'animation'] )) print esc_attr($instance[$this->plugin.'animation']); ?> 
					<?php if( !empty( $instance[$this->plugin.'view'] ) && esc_attr($instance[$this->plugin.'view']=='Slideshow')) print $this->plugin.'Slideshow'; ?> '>
					
				<?php foreach ($terms as $term) {	?>			
					<li class='<?php if( !empty( $instance[$this->plugin.'columns'] ) && esc_attr($instance[$this->plugin.'view']) !='Slideshow' ) print esc_attr($instance[$this->plugin.'columns']); ?> 
						<?php if( !empty( $instance[$this->plugin.'align'] )) print esc_attr($instance[$this->plugin.'align']); ?> ' >
						<?php     
						$term_link = get_term_link( $term );   
						// If there was an error, continue to the next term.
						if ( is_wp_error( $term_link ) ) {
							continue;
						}
						?>
								<!--								
								<div class='img-container'>
								<a href="<?php echo get_term_link($term->slug, $instance[$this->plugin.'taxonomy'] ); ?>">		
									<?php if($instance[$this->plugin.'taxonomy']=='product_cat'){
									$thumb_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
									$term_img = wp_get_attachment_url(  $thumb_id );	
									print "<img src='".$term_img."' />";	
									}else{
										if(function_exists('z_taxonomy_image_url') &&  z_taxonomy_image_url($term->term_id) !=''){
											z_taxonomy_image($term->term_id);
										}
									}?>
									
								</a>
								</div>	
								-->
								<h5>
									<a href="<?php echo esc_url( $term_link ); ?>"><?php echo $term->name ; ?></a>
								</h5>								
						
					</li>	
										
				<?php }	?>
				</ul>
		<?php 		
		}//end of checking taxonomy or content type
		
		//echo $args['after_widget'];
		
		?>

		
		<?php
	}	
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ $this->plugin.'chooseContent' ] = strip_tags( $new_instance[ $this->plugin.'chooseContent' ] );
		$instance[ $this->plugin.'contentType' ] = strip_tags( $new_instance[ $this->plugin.'contentType' ] );
		$instance[ $this->plugin.'taxonomy' ] = strip_tags( $new_instance[ $this->plugin.'taxonomy' ] );
		$instance[ $this->plugin.'contentTax' ] = strip_tags( $new_instance[ $this->plugin.'contentTax' ] );
		$instance[ $this->plugin.'show_image' ] = strip_tags( $new_instance[ $this->plugin.'show_image' ] );
		$instance[ $this->plugin.'image_size' ] = strip_tags( $new_instance[ $this->plugin.'image_size' ] );
		$instance[ $this->plugin.'excerpt' ] = strip_tags( $new_instance[ $this->plugin.'excerpt' ] );
		$instance[ $this->plugin.'content' ] = strip_tags( $new_instance[ $this->plugin.'content' ] );
		$instance[ $this->plugin.'taxTerms' ] = strip_tags( $new_instance[ $this->plugin.'taxTerms' ] );
		$instance[ $this->plugin.'showTaxonomies' ] = strip_tags( $new_instance[ $this->plugin.'showTaxonomies' ] );
		$instance[ $this->plugin.'order' ] = strip_tags( $new_instance[ $this->plugin.'order' ] );
		$instance[ $this->plugin.'orderBy' ] = strip_tags( $new_instance[ $this->plugin.'orderBy' ] );	

		$instance[ $this->plugin.'view' ] = strip_tags( $new_instance[ $this->plugin.'view' ] );
		$instance[ $this->plugin.'columns' ] = strip_tags( $new_instance[ $this->plugin.'columns' ] );
		
		$instance[ $this->plugin.'animation' ] = strip_tags( $new_instance[ $this->plugin.'animation' ] );
		
		$instance[ $this->plugin.'align' ] = strip_tags( $new_instance[ $this->plugin.'align' ] );
		$instance[ $this->plugin.'hide_empty' ] = strip_tags( $new_instance[ $this->plugin.'hide_empty' ] );
		
		$instance[ $this->plugin.'number' ] = strip_tags( $new_instance[ $this->plugin.'number' ] );
		
		return $instance;
	}

	
	
}

$instantiate = new Content_Widget();
add_action( 'widgets_init', function(){
     register_widget( 'Content_Widget' );
});