<?php
/*
Plugin Name: Lunch.com Communities
Plugin URI: http://www.lunch.com/
Description: Promote your Community on Lunch.com
Version: 0.1
Author: Lunch.com
Author URI: http://www.lunch.com/
*/


error_reporting(E_ALL);
add_action("widgets_init", array('lunchcom_widget_manager', 'register'));
register_activation_hook( __FILE__, array('lunchcom_widget_manager', 'activate'));
register_deactivation_hook( __FILE__, array('lunchcom_widget_manager', 'deactivate'));
class lunchcom_widget_manager {
  function activate(){
    $data = array( 'lunchcom_widget_manager_urlAlias' => 'Default value' ,'urlAlias' => "MBLocal", 'widgetType' => 1, 'width' => 200);
    if ( ! get_option('widget_name')){
      add_option('lunchcom_widget_manager' , $data);
    } else {
      update_option('widget_name' , $data);
    }
  }
  function deactivate(){
    delete_option('widget_name');
  }

  function control(){
    echo 'Promote your Community on Lunch by showing recent activity or an invitation to join.<br/><br/>';
  $data = get_option('lunchcom_widget_manager');
  ?>



  <p><label>Community URL:<br/> lunch.com/<input name="lunchcom_widget_manager_urlAlias"
type="text" value="<?php echo $data['urlAlias']; ?>" /></label></p>
  <p><label>Widget Type: <br/>
  <input name="lunchcom_widget_manager_widgetType" type="radio" value="1" <?php if ($data['widgetType'] == 1) {echo "checked";} ?> /> Recent Activity<br/>
  <input name="lunchcom_widget_manager_widgetType" type="radio" value="2" <?php if ($data['widgetType'] == 2) {echo "checked";} ?>/> Branding<br/>
  
</label></p>
	<p><label>Select the width of your sidebar column.  If unsure, use the default value of 150.<br/>
Width:  <select name="lunchcom_widget_manager_width">
        <option  value="150" <?php if ($data['width'] == 150) {echo "selected";} ?>  >150</option> 
        <option  value="200" <?php if ($data['width'] == 200) {echo "selected";} ?>  >200</option> 
        <option  value="300"  <?php if ($data['width'] == 300) {echo "selected";} ?> >300</option>
        </select> 
</label>
</p>

  <?php
   if (isset($_POST['lunchcom_widget_manager_urlAlias'])){

    $data['urlAlias'] = attribute_escape($_POST['lunchcom_widget_manager_urlAlias']);
    $data['widgetType'] = attribute_escape($_POST['lunchcom_widget_manager_widgetType']);
    $data['width'] = attribute_escape($_POST['lunchcom_widget_manager_width']);
    update_option('lunchcom_widget_manager', $data);
  }

  }




  function widget($args){
    echo $args['before_widget'];
    echo $args['before_title'] . ' ' . $args['after_title'];


    $data = get_option('lunchcom_widget_manager');
   $urlAlias = $data['urlAlias'];
   $widgetType = $data['widgetType'];
   $width = $data['width'];

$request = new WP_Http;
$result = $request->request( "http://widgets.lunch.com/$urlAlias/Widgets-$widgetType.html?w=$width");	
$body = $result['body'];

  echo $body;
    echo $args['after_widget'];
  }







  function register(){
    register_sidebar_widget('Lunch.com Communities', array('lunchcom_widget_manager', 'widget'));
    register_widget_control('Lunch.com Communities', array('lunchcom_widget_manager', 'control'));
  }
}


?>
