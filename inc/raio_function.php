<?php 
function raio_loaded(){
	/**
	 * Fires upon plugins_loaded WordPress hook.
	 */
	do_action( 'raio_loaded' );
}
add_action( 'plugins_loaded', 'raio_loaded' );

function raio_menu_icon() {
	return 'dashicons-forms';
}

class Raio_Manager
{
    /**
     * Holds the values to be used in the fields callbacks
     */
	private $options;
    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'raio_plugin_menu'));
        add_action('admin_init', array($this, 'raio_init'));
    }


 public function raio_plugin_menu() {
	
	$capability = apply_filters( 'raio_required_capabilities', 'manage_options' );
	$parent_slug = 'raio_main_menu';

	add_menu_page( __( 'Rockon All in One', 'rockon-all-in-one-ui' ), __( 'Rockon All in One', 'rockon-all-in-one-ui' ), $capability, $parent_slug, 'raio_dashboard', raio_menu_icon() );
	add_submenu_page( $parent_slug, __( 'Settings & Shortcode', 'rockon-all-in-one-ui' ), __( 'Settings & Shortcode', 'rockon-all-in-one-ui' ), $capability, 'raio_manage_setting', array($this, 'raio_manage_setting'));
	//add_submenu_page( $parent_slug, __( 'Shortcode', 'rockon-all-in-one-ui' ), __( 'Shortcode', 'rockon-all-in-one-ui' ), $capability, 'raio_manage_shortcode', array($this, 'raio_manage_setting') );
	//add_submenu_page( $parent_slug, __( 'Help', 'rockon-all-in-one-ui' ), __( 'Help', 'rockon-all-in-one-ui' ), $capability, 'raio_help', 'raio_help' );
	
	do_action( 'raio_extra_menu_items', $parent_slug, $capability );
 }
 
  public function raio_manage_setting(){
?>
    <div class="wrap">     
     <div id="icon-themes" class="icon32"></div>
        <h2>RockOn All in One</h2>
        <?php settings_errors(); ?>         
        <?php		
			$active_tab = 'raio_setting';
            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = $_GET[ 'tab' ];
            }
        ?>         
        <h2 class="nav-tab-wrapper">
            <a href="?page=raio_manage_setting&tab=raio_setting" class="nav-tab <?php echo $active_tab == 'raio_setting' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Settings', 'rockon-all-in-one');?></a>
            <a href="?page=raio_manage_setting&tab=raio_breadcrumb_options" class="nav-tab <?php echo $active_tab == 'raio_breadcrumb_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Breadcrumbs Options', 'rockon-all-in-one');?></a>
			<a href="?page=raio_manage_setting&tab=raio_hfscript_options" class="nav-tab <?php echo $active_tab == 'raio_hfscript_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Header & Footer Script Options', 'rockon-all-in-one');?></a>
			<a href="?page=raio_manage_setting&tab=raio_shortcode_options" class="nav-tab <?php echo $active_tab == 'raio_shortcode_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Shortcode', 'rockon-all-in-one');?></a>
        </h2>         
       
 	<?php if($active_tab == 'raio_setting' ) {		
		   $this->raio_manage_setting_section();  			
	     }	else if($active_tab == 'raio_breadcrumb_options' ) { 			
			$this->raio_breadcrumbs_setting_section();			
		 } else if($active_tab == 'raio_hfscript_options' ) {			 
		     $this->raio_hf_script_section(); 			 
		 } else { 	
	         $this->raio_tbshortcode_section();
         } 
	?>  		
    </div><!-- /.wrap -->
<?php
  }	

	public function raio_manage_setting_section()
    {        
?>
        <div class="wrap">	
		 <div id="raio-wrap">
		  <div class="wrap">
			<h2><?php esc_html_e('Settings', 'rockon-all-in-one');?></h2>
			<hr/>
		   <div class="raio-wrap">
			<form name="raiofollow" action="options.php" method="post">
			  <?php settings_fields( 'raio_script_options' );?>
			 <div class="raio-msf-section">
			  <b><i><?php esc_html_e('Move your scripts to the footer to help speed up perceived page load times and improve user experience.', 'rockon-all-in-one');?></i></b>
				<br><br>	
		<?php echo '<input type="checkbox" id="raio_act_hsf" name="raio_act_hsf" '.checked( get_option('raio_act_hsf'), 1, false ).' value="1">';?>
			   <label class="raio-labels" for="raio_act_hsf"><b><?php esc_html_e('Move Scripts to footer', 'rockon-all-in-one');?></b></label>     
			   <br/><br/>
				<b><?php esc_html_e('Note: ', 'rockon-all-in-one');?></b><i>
				<?php esc_html_e('if you checked check box then auto move all scripts on footer section.', 'rockon-all-in-one');?></i>	
				<hr>
			 </div>	
			<div class="raio-dc-section">
			  <h3><?php esc_html_e('Disable Comments', 'rockon-all-in-one');?></h3>
		<?php echo '<input type="checkbox" id="raio_comment_dc" name="raio_comment_dc" '.checked( get_option('raio_comment_dc'), 1, false).' value="1">';?>
			 <label class="raio-labels" for="raio_comment_dc"><b><?php esc_html_e('Everywhere: ', 'rockon-all-in-one');?></b>
			 <?php esc_html_e('Disable all comment-related controls and settings in WordPress.', 'rockon-all-in-one');?></label>
			 <br/><br/>
			 <b><?php esc_html_e('Warning: ', 'rockon-all-in-one');?></b>
			 <i><?php esc_html_e('This option is global and will affect your entire site. Use it only if you want to disable comments everywhere.', 'rockon-all-in-one');?></i>	 
			</div>
				<?php            
					 do_settings_sections( 'raio_script_options' ); 
					submit_button();
				?>
			</form>
		   </div>				
		  </div>
		 </div> 
        </div>
<?php
    }	
	
	public function raio_breadcrumbs_setting_section()
    {
?>
        <div class="wrap">		
		 <div id="raio-wrap">
		   <div class="wrap">
			   <h2><?php esc_html_e('Breadcrumb Settings', 'rockon-all-in-one');?></h2>
			  <hr/>					
		    <div class="raio-wrap">				
			 <form name="raiofollow" action="options.php" method="post">
					<?php settings_fields( 'raio_breadcrumb_options' ); ?>
               <label class="raio-labels" for="raio_bdc_separator"><b><?php esc_html_e('Breadcrumb Separator', 'rockon-all-in-one');?></b></label>		
			   <input type="text" id="raio_bdc_separator" name="raio_bdc_separator" value="<?php echo get_option('raio_bdc_separator');?>">
			    <br/><?php esc_html_e('Placed in between each breadcrumb', 'rockon-all-in-one');?><br><br>                        
			  <a href="admin.php?page=raio_manage_setting&tab=raio_shortcode_options#breadcrumbs-sc-section">
			  <?php esc_html_e('Click here', 'rockon-all-in-one');?></a> 
			  <?php esc_html_e('and get the breadcrumb short-code', 'rockon-all-in-one');?>							
				<?php               
					do_settings_sections( 'raio_breadcrumb_options' );
				   submit_button();
				?>
			 </form>
			</div>				
		   </div>
		  </div> 
        </div>
<?php
    }
    
     public function raio_hf_script_section()
    {
?>
        <div class="wrap">		
		  <div id="raio-wrap">
		   <div class="wrap">
				<?php //screen_icon(); ?>
			   <h2><?php esc_html_e('Header and Footer Scripts - Options', 'rockon-all-in-one');?></h2>
			  <hr/>
			 <div class="raio-wrap">					
			  <form name="raiofollow" action="options.php" method="post">
					<?php settings_fields( 'raio_hfscript_options' ); ?>
				<h3 class="raio-labels" for="raio_insert_header"><?php esc_html_e('Scripts in header:', 'rockon-all-in-one');?>	</h3>
  <textarea rows="5" cols="57" id="raio_insert_header" name="raio_insert_header"><?php echo esc_html(get_option('raio_insert_header'));?></textarea>
   <br/>
		 <?php esc_html_e('These scripts will be printed to the', 'rockon-all-in-one');?> <code>&lt;head&gt;</code>
		 <?php esc_html_e(' section.', 'rockon-all-in-one');?>                        
		<h3 class="raio-labels footerlabel" for="raio_insert_footer"><?php esc_html_e('Scripts in footer:', 'rockon-all-in-one');?></h3>
   <textarea rows="5" cols="57" id="raio_insert_footer" name="raio_insert_footer"><?php echo esc_html(get_option('raio_insert_footer'));?></textarea>
    <br/>
		<?php esc_html_e('These scripts will be printed to the', 'rockon-all-in-one');?> <code>&lt;footer&gt;</code>
		<?php esc_html_e(' section.', 'rockon-all-in-one');?>						
	<?php
            do_settings_sections('raio_hfscript_options');
          submit_button();
   ?>
			 </form>
			</div>				
		  </div>
		 </div> 
       </div>
<?php
    }

   public function raio_tbshortcode_section()
  {
	 settings_fields( 'raio_shortcode_options' ); 
?>
	 <div class="wrap">	
		<div id="raio-wrap">
		  <h2><?php esc_html_e('All Short-code', 'rockon-all-in-one');?></h2>
		  <hr/>
		<div id="content-limit-section" class="content-limit-section">
		 <div>
   		   <b><?php esc_html_e('Content Excerpt Short-code', 'rockon-all-in-one');?></b><pre><code>[rockon_content limit=25]</code></pre>		   
			<?php esc_html_e('Use this short-code anywhere, limit=25.. its showing first 25 word on post/page...', 'rockon-all-in-one');?><br><br><br>
		  <hr/>
		 </div>		  
		</div>
		<div id="breadcrumbs-sc-section" class="breadcrumbs-sc-section">
		 <div> 
   		   <b><?php esc_html_e('Breadcrumbs Short-code', 'rockon-all-in-one');?></b><pre><code>[rockon_breadcrumbs]</code></pre>		   
				<?php esc_html_e('Use this short-code anywhere on template', 'rockon-all-in-one');?>
		 </div>
		</div>
		</div>
	  </div>
<?php
	   do_settings_sections( 'raio_shortcode_options' ); 
	}
	
    /**
     * Register and add settings
     */
    public function raio_init()
    {
       register_setting('raio_hfscript_options', 'raio_insert_header', 'trim' );
	   register_setting('raio_hfscript_options', 'raio_insert_footer', 'trim' );		
	   register_setting('raio_script_options', 'raio_act_hsf', 'trim' );	
	   register_setting('raio_script_options', 'raio_comment_dc', 'trim' );	   
	   register_setting('raio_breadcrumb_options', 'raio_bdc_separator', 'trim' );		
    }
}

if(is_admin()){
    $raio_settings_page = new Raio_Manager();
}	
?>