<?php
/*************************************************************************
 * Plugin Name: Genesis - Comment Title 
 * Author: Hit Reach
 * Author URI: http://www.hitreach.co.uk/
 * Description: Change the default Genesis Comment Title. Requires the Genesis theme framework
 * Version: 1
 * Plugin URI: http://www.hireach.co.uk/wordpress-plugins/genesis-comment-title/
 * License: GPL2
*************************************************************************/
#Copyright
/*************************************************************************
	Copyright 2010  Hit Reach  (email : jamie.fraser@hitreach.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*************************************************************************/
#Go!
/**********************
	Activation Hook
**********************/
register_activation_hook(__FILE__, 'CT_HR_Activate');
function CT_HR_Activate(){
	$latest = '1.5';
	$theme_info = get_theme_data(TEMPLATEPATH.'/style.css');
	if(basename(TEMPLATEPATH) != 'genesis') {
		deactivate_plugins(plugin_basename(__FILE__));
		wp_die('Sorry, you can\'t activate unless you have installed <a href="http://www.studiopress.com/themes/genesis">Genesis</a>');
	}
	if(version_compare( $theme_info['Version'], $latest, '<') ) {
			deactivate_plugins(plugin_basename(__FILE__));
			wp_die('Sorry, you can\'t activate without <a href="http://www.studiopress.com/support/showthread.php?t=19576">Genesis '.$latest.'</a> or greater');
	}
	if(!get_option( "CT_HR_CommentTitle" )){
		add_option("CT_HR_CommentTitle");
		update_option("CT_HR_CommentTitle", "Speak Your Mind");
	}
}
/**********************
	Work Filter
**********************/
add_filter('genesis_comment_form_args', 'CT_HR_Work');
function CT_HR_Work($args){
	if(get_option("CT_HR_CommentTitle")){
		$title = get_option("CT_HR_CommentTitle");
		$args['title_reply'] = $title;
 	  	return $args;
	}
	else{
		return $args;
	}
}
/**********************
	Options Page
**********************/
add_action('wp_dashboard_setup', 'CT_HR_Widget_Setup');
function CT_HR_Widget_Setup(){
	if(current_user_can("edit_theme_options")){
		wp_add_dashboard_widget('Genesis - Comment Title', 'Genesis - Comment Title', 'CT_HR_Widget');
	}
}
function CT_HR_Widget(){
	$title = get_option("CT_HR_CommentTitle");
?>
	<form method="post" action="options.php"> 
	<?php wp_nonce_field('update-options'); ?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="CT_HR_CommentTitle">Comment Title: </label></th>
			<td><input type="text" name="CT_HR_CommentTitle" id='CT_HR_CommentTitle' onblur="javascript:this.style.textDecoration='none'; this.style.cursor='pointer'" onclick="javascript:this.style.textDecoration='underline'; this.style.cursor='text';" style='width:88%; cursor:pointer; background:none !important;' value="<?php echo get_option('CT_HR_CommentTitle'); ?>" /></td>
		</tr>
	</table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="CT_HR_CommentTitle" />
	<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
	</form>
	<form method="post" action="options.php" onSubmit="return confirm('Are you sure?');"> 
	<?php wp_nonce_field('update-options'); ?>
	<input type="hidden" name="CT_HR_CommentTitle" id='CT_HR_CommentTitle' value="Speak Your Mind" />
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="CT_HR_CommentTitle" />
	<p class="submit" align='right' style='margin-top:-22px;'>
	<input type="submit" class="button-primary" value="<?php _e('Restore Defaults') ?>" />
	</p>
	</form>
<?php
}
?>