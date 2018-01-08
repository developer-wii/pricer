<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
    * wpenlight_table_List_Table class that will display our custom table
    * records in nice table
    */
class wpenlight_table_List_Table extends WP_List_Table
{

    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'wpenlight',
            'plural' => 'wpenlights',
        ));
    }

    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    function column_age($item)
    {
        return '<em>' . $item['age'] . '</em>';
    }

    function column_name($item)
    {

        $actions = array(
            'edit' => sprintf('<a href="?page=wpenlight&id=%s">%s</a>', $item['id'], __('Edit', 'wpenlight_table')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'wpenlight_table')),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'name' => __('Name', 'wpenlight_table'),
            'shortcode' => __('Shortcode', 'wpenlight_table'),
            'author' => __('Author', 'wpenlight_table'),
        );
        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', true),
            'shortcode' => array('shortcode', false),
            'age' => array('age', false),
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ept_data'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ept_data'; 

        $per_page = 5; 

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        $paged = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ", $per_page, $paged), ARRAY_A);

        $this->set_pagination_args(array(
            'total_items' => $total_items, 
            'per_page' => $per_page, 
            'total_pages' => ceil($total_items / $per_page) 
        ));
    }
}

function wpenlight_table_admin_menu()
{
    /* add_menu_page(__('Enlight Pricer', 'wpenlight_table'), __('Enlight Pricer', 'wpenlight_table'), 'activate_plugins', 'enlight_pricer', 'wpenlight_table_enlight_pricer_page_handler','dashicons-chart-bar' , 32); */
	 $top_menu_item = 'wpenlight_table_enlight_pricer_page_handler';
	add_menu_page( '', 'Enlight Pricer', 'manage_options', 'wpenlight_table_enlight_pricer_page_handler', 'wpenlight_table_enlight_pricer_page_handler', 'dashicons-chart-bar' , 32);
	add_submenu_page( $top_menu_item, '', 'Enlight Pricer', 'manage_options', $top_menu_item, $top_menu_item );
   
    add_submenu_page('Enlight Pricer', __('Add new', 'wpenlight_table'), __('Add new', 'wpenlight_table'), 'activate_plugins', 'enlight_pricer_form', 'wpenlight_table_enlight_pricer_form_page_handler');
	add_submenu_page( $top_menu_item, '', 'Add new', 'manage_options', 'wpenlight_table_enlight_pricer_form_page_handler', 'wpenlight_table_enlight_pricer_form_page_handler' );
	add_submenu_page( null, 'Edit type','','manage_options','wpenlight','wpenlight' );
}

add_action('admin_menu', 'wpenlight_table_admin_menu');

//Edit page 
function wpenlight_table_enlight_pricer_page_handler()
{
    global $wpdb;

    $table = new wpenlight_table_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'wpenlight_table'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Enlight Pricer', 'wpenlight_table')?> <a class="add-new-h2"
                                    href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=enlight_pricer_form');?>"><?php _e('Add new', 'wpenlight_table')?></a>
    </h2>
    <?php echo $message; ?>

    <form id="enlight_pricer-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}
function wpenlight()
{
global $wpdb;
	$table_name_edit= $wpdb->prefix.'ept_data';
	if(isset($_REQUEST['id']))
	{
		$uid = $_REQUEST['id'];
		$select_where = "select * from $table_name_edit where id = '$uid'";
		$result = $wpdb->get_results($select_where);

	}
foreach($result as $data){ 
?>

            <label for="name"><?php _e('Name', 'wpenlight_table')?></label></br>
  
            <input id="title_wpenlight" name="title_wpenlight" type="text" style="width: 95%" value="<?php echo $data ->name;?>">
            <!--input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>"
                    size="50" class="code" placeholder="<?php _e('Your name', 'wpenlight_table')?>" required-->
        </td>
    </tr>
 <input type="hidden" name="url" id="url" value="<?php echo  plugin_dir_url( __FILE__ ) . 'table-data.php' ;?>">
    <section id="generator">
        <div class="container-fluid webindia-demo">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-primary" id="columns">
                            <div class="panel-heading main-panel" data-toggle="collapse" data-parent="#accordion" data-target="#collapseTwo">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-th">
                        </span>Columns <span class="caret"></span>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="column-count-div">
                                        <?php
                                       /* global $wpdb;
                                        $table_name=$wpdb->prefix.'ept_data';
                                      $numRows = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name");
                                     if($numRows == 0){
                                         */?>
                                        <label> Column count: </label> <input id="column-count" type="number" min="1" max="6" value="<?php echo $data->collumn_count;?>" onchange="generate()" />
                                        <?php/*
                                     }
                                     else{
                                        $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
                                     
                                        foreach ( $result as $print )   {
                                            $data = $print->no_collumn;
                                            
                                            $numRows = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name");                                  
                                         ?>
                                         <label> Column count: </label> <input id="column-count" type="number" min="1" max="6" value="<?php echo $data ;?>" onchange="generate()" /><?php
                                          }
                                     }*/?>
                                            
                                            <label id="column-recommendation">Max 6 column recommended</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary panel-group" id="design">
                            <div class="panel-heading main-panel" data-toggle="collapse" data-parent="#accordion" data-target="#collapseOne">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-wrench">
                        </span>Design <span class="caret"></span>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body text-center">
                                    <div class="dropdown design-button">
                                        <button class="btn btn-default dropdown-toggle" id="style-button" type="button" data-toggle="dropdown">Select Style <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#!" onclick="styleFunction(1)">Style 1</a></li>
                                            <li><a href="#!" onclick="styleFunction(2)">Style 2</a></li>
                                            <li><a href="#!" onclick="styleFunction(3)">Style 3</a></li>
                                        </ul>
                                    </div>
                                    <!--div class="toggle-button">
                          <span>Space between tables</span>
                          <input id="gutter-toggle" checked type="checkbox" data-toggle="toggle" onchange="gutterFunction()">
                        </div-->
                                    <div class="panel-group" id="designGroup">
                                        <div class="panel panel-default text-left" id="color-panel">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#designGroup" data-target="#collapseColor">
                                                <h4 class="panel-title">
                                                    <span class="glyphicon glyphicon-pencil">
                                    </span>Choose Color <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseColor" class="panel-collapse collapse">
                                                <div class="panel-body" id="color-panel-body">
                                                    <div id="select-column-div" class="text-center">
                                                        <label class="select-column" for="selectColumnForColoring">Select Column:</label>
                                                        <select class="form-control select-column" id="selectColumnForColoring">
                                      <option value="0">All</option>
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                      <option value="4">4</option>
                                    </select>
                                                    </div>
                                                    <ul class="nav nav-pills nav-stacked color-nav-list">
                                                        <li><a href="#!" class="color-list" onclick="colorFunction('default')">Default <div class="style-color-span" id="column-color-default"></div> </a></li>
                                                        <li><a href="#!" class="color-list" onclick="colorFunction('gray')">Gray <div class="style-color-span" id="column-color-gray"></div>  </a></li>
                                                        <li><a href="#!" class="color-list" onclick="colorFunction('brown')">Brown <div class="style-color-span" id="column-color-brown"></div> </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="button">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#designGroup" data-target="#collapseButton">
                                                <h4 class="panel-title text-left">
                                                    <span class="glyphicon glyphicon-modal-window">
                        </span>Button <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseButton" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div id="select-column-div" class="text-center">
                                                        <label class="select-column" for="selectColumnForButton">Select Column:</label>
                                                        <select class="form-control select-column" id="selectColumnForButton">
                                      <option value="0">All</option>
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                    </select>
                                                    </div>
                                                    <div id="button-type-div">
                                                        <label id="select-button">Select button type</label>
                                                        <form class="button-form text-left">
                                                            <div class="radio">
                                                                <label><input type="radio" checked name="optradio" class="button-type" onchange="buttonStyle(1)">Rectangle Button</label>
                                                            </div>
                                                            <div class="radio">
                                                                <label><input type="radio" name="optradio" class="button-type" onchange="buttonStyle(2)">Smooth Button</label>
                                                            </div>
                                                            <div class="radio">
                                                                <label><input type="radio" name="optradio" class="button-type" onchange="buttonStyle(3)">Oval Button</label>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="dropdown design-button" id="select-button-effect">
                                                        <button class="btn btn-default dropdown-toggle" id="effect-button" type="button" data-toggle="dropdown">Select Effect <span class="caret"></span></button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="#!" onclick="buttonEffectFunction(0)">No effect</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(1)">Sweep to right</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(2)">Sweep to left</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(3)">Sweep to bottom</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(4)">Sweep to top</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(5)">Radial out</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(6)">Radial in</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="ribbon">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#designGroup" data-target="#collapseRibbon">
                                                <h4 class="panel-title text-left">
                                                    <span class="glyphicon glyphicon-modal-window">
                        </span>Ribbon <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseRibbon" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div id="select-column-div" class="text-center">
                                                        <label class="select-column" for="selectColumnForButton">Select Column:</label>
                                                        <select class="form-control select-column" id="selectColumnForRibbon" onchange="ribbonFunction()">
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                    </select>
                                                    </div>
                                                    <label for="useRibbon" id="ribbonLabel"> Use Ribbon <input type="checkbox" id="useRibbon" onchange="useRibbonFunction()"> </label>
                                                    <div id="ribbon-type-div">
                                                        <label id="select-ribbon">Select ribbon type</label>
                                                        <form class="button-form" id="ribbon-type-radio">
                                                            <div class="radio">
                                                                <label><input type="radio" checked name="optradio" class="ribbon-type" onchange="ribbonStyle(1)">Ribbon 1</label>
                                                            </div>
                                                            <div class="radio">
                                                                <label><input type="radio" name="optradio" class="ribbon-type" onchange="ribbonStyle(2)">Ribbon 2</label>
                                                            </div>
                                                            <div class="radio">
                                                                <label><input type="radio" name="optradio" class="ribbon-type" onchange="ribbonStyle(3)">Ribbon 3</label>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div id="select-ribbon-position-div" class="text-center">
                                                        <label class="select-column" for="selectRibbonPosition">Ribbon Position:</label>
                                                        <select class="form-control select-column" id="selectRibbonPosition" onchange="ribbonPosition()">
                              <option value="left">Left</option>
                                <option value="right">Right</option>
                                <option value="top left">Top left</option>
                                <option value="top right">Top Right</option>
            </select>
                                                    </div>
                                                    <div id="column-button-div">
                                                        <label>Content: </label><input type='text' id='ribbon-content' class="option-text" onkeyup="ribbonContentFunction()">
                                                    </div>
                                                    <div class="panel panel-default" id="ribbonColor">
                                                        <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#ribbonColor" data-target="#collapseRibbonColor">
                                                            <h4 class="panel-title text-left">
                                                                <span class="glyphicon glyphicon-modal-window">
                        </span>Ribbon Color<span class="caret"></span>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseRibbonColor" class="panel-collapse collapse">
                                                            <div class="panel-body text-left">
                                                                <ul class="nav nav-pills nav-stacked color-nav-list">
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="ribbonColorFunction('dark-aquamarine')">Dark aquamarine <div class="style-color-span" id="ribbon-color-dark-aquamarine"></div> </a></li>
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="ribbonColorFunction('cated-blue')">Cated Blue <div class="style-color-span" id="ribbon-color-cated-blue"></div>  </a></li>
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="ribbonColorFunction('dark-green')">Dark Green <div class="style-color-span" id="ribbon-color-dark-green"></div>  </a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="tooltip">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#designGroup" data-target="#collapseTooltip">
                                                <h4 class="panel-title text-left">
                                                    <span class="glyphicon glyphicon-modal-window">
                        </span>Tooltip <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseTooltip" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div id="tooltip-select">
                                                        <div id="select-column-div-tooltip" class="text-center column-count-div">
                                                            <label class="select-column" for="selectColumnForTooltip">Select Column:</label>
                                                            <select class="form-control select-column" id="selectColumnForTooltip" onchange="tooltipColumnFunction()">
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                    </select>
                                                        </div>
                                                        <div id="select-option-div-tooltip" class="text-center column-count-div">
                                                            <label class="select-column" for="selectOptionforTooltip">Select Option:</label>
                                                            <select class="form-control select-column" id="selectOptionForTooltip" onchange="tooltipOptionFunction()">
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                    </select>
                                                        </div>
                                                    </div>
                                                    <label for="useTooltip" id="tooltipLabel"> Use Tooltip <input type="checkbox" id="useTooltip" onchange="useTooltipFunction()"> </label>
                                                    <div id="select-tooltip-position-div" class="text-center">
                                                        <label class="select-column" for="selectTooltipPlacement">Tooltip Position:</label>
                                                        <select class="form-control select-column" id="selectTooltipPlacement" onchange="recreateTooltip()">
                              <option value="left">Left</option>
                                <option value="right">Right</option>
            </select>
                                                    </div>
                                                    <div id="select-data-placement-div" class="text-center">
                                                        <label class="select-column" for="selectTooltipPosition">Data Placement:</label>
                                                        <select class="form-control select-column" id="selectTooltipPosition" onchange="recreateTooltip()">
                              <option value="left">Left</option>
                                <option value="right">Right</option>
                                <option value="top">Top</option>
                                <option value="bottom">Bottom</option>
            </select>
                                                    </div>
                                                    <div id="tooltip-content-div">
                                                        <label>Content: </label><input type='text' id='tooltip-content' class="option-text" onkeyup="tooltipContentFunction()">
                                                    </div>
                                                    <div class="panel panel-default" id="tooltipColor">
                                                        <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#tooltipColor" data-target="#collapseTooltipColor">
                                                            <h4 class="panel-title text-left">
                                                                <span class="glyphicon glyphicon-modal-window">
                                    </span>Tooltip Color<span class="caret"></span>
                                                            </h4>
                                                        </div>

                                                        <div id="collapseTooltipColor" class="panel-collapse collapse">
                                                            <div class="panel-body text-left">
                                                                <ul class="nav nav-pills nav-stacked color-nav-list">
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="tooltipColorFunction('default')">Default <div class="style-color-span" id="ribbon-color-black"></div>  </a></li>
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="tooltipColorFunction('dark-aquamarine')">Dark aquamarine <div class="style-color-span" id="ribbon-color-dark-aquamarine"></div> </a></li>
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="tooltipColorFunction('cated-blue')">Cated Blue <div class="style-color-span" id="ribbon-color-cated-blue"></div>  </a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary" id="content">
                            <div class="panel-heading main-panel" data-toggle="collapse" data-parent="#accordion" href="#collapseContent">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-file">
                        </span>Content <span class="caret"></span>
                                </h4>
                            </div>
                            <div id="collapseContent" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div id="select-column-div" class="text-center">
                                        <label class="select-column" for="selectColumnForContent">Select Column:</label>
                                        <select class="form-control select-column" id="selectColumnForContent" onchange="contentChangeColumn()">
															            <option value="0">All</option>
															            <option value="1">1</option>
															            <option value="2">2</option>
															            <option value="3">3</option>
															            <option value="4">4</option>
			          							</select>
                                    </div>
                                    <div class="panel-group" id="contentGroup">
                                        <div class="panel panel-default" id="title-content">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#contentGroup" data-target="#collapseTitleContent">
                                                <h4 class="panel-title">
                                                    <span class="glyphicon glyphicon-file">
                                </span>Title Content <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseTitleContent" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div id="column-title-div">
                                                        <label>Column title: </label><input type='text' id='column-title' class="option-text" onkeyup="columnTitleFunction()">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="price-content">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#contentGroup" data-target="#collapsePriceContent">
                                                <h4 class="panel-title">
                                                    <span class="glyphicon glyphicon-file">
                                </span>Price Content <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapsePriceContent" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div id="column-price-div">
                                                        <div class="prices">
                                                            <label>Price Unit: </label><input type='text' id='price-unit' class="option-text-price" onkeyup="priceUnitFunction()">
                                                        </div>
                                                        <div class="prices">
                                                            <label>Price Count: </label><input type='text' id='price-count' class="option-text-price" onkeyup="priceCountFunction()">
                                                        </div>
                                                        <div class="prices">
                                                            <label>Price Cent: </label><input type='text' id='price-cent' class="option-text-price" onkeyup="priceCentFunction()">
                                                        </div>
                                                        <div class="prices">
                                                            <label>Price Delay: </label><input type='text' id='price-delay' class="option-text-price" onkeyup="priceDelayFunction()">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="option-content">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#contentGroup" data-target="#collapseOptionContent">
                                                <h4 class="panel-title">
                                                    <span class="glyphicon glyphicon-file">
                                    </span>Options Content <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseOptionContent" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div class="option-content-buttons">
                                                        <button type="button" class="btn btn-default design-button" value='Add Button' id='addButton' onclick=" addNewOption()">Add</button>
                                                        <button type="button" class="btn btn-default design-button" value='Remove Button' id='removeButton'>Remove</button>
                                                    </div>
                                                    <div id='TextBoxesGroup'>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="title-content">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#contentGroup" data-target="#collapseButtonContent">
                                                <h4 class="panel-title">
                                                    <span class="glyphicon glyphicon-file">
                                </span>Button Content <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseButtonContent" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div id="column-button-div">
                                                        <label>Content: </label><input type='text' id='button-content' class="option-text" onkeyup="buttonContentFunction()">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-9 col-md-9">
                    <div class="well">
                        <div id="allTable">
                            <section class="webindia-section">
                                <div class="webindia-style-1">
                                   <?php	/*global $wpdb;
								  $table_name=$wpdb->prefix.'ept_data';
								  $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
								        foreach ( $result as $print )   {
													$decode = $print->data;
 								         echo base64_decode($decode);
											 */
											$decode =  $data->data;
											echo base64_decode($decode);?>
                                </div>
                            </section>
                        </div>
                        <div id="button-code-generate">
                            <button type="button" class="btn btn-default btn-lg design-button" value='Add Button' id='button-generate' onclick="takeCodeedit()">Generate</button>
                        </div>
                    </div>
                    <br>
                    <div class="well alert">
                        <a href="#!" class="close" data-hide="alert" aria-label="close" onclick="hideTableCode()">&times;</a>
					
									   <input type="hidden" id="shortcode" value="[ept_table=<?php echo $numRows;?>]">
                        <input class="form-control" rows="1" id="html-code" value="Please insert shortcode <?php echo $data->shortcode;?> to display the table in any post page or widget">
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </section>
<?php
}



$table_name= $wpdb->prefix.'ept_data';
$data=esc_html($_POST['f0']);
$Sanitize_Html = sanitize_html_class($data);
$id=get_current_user_id();
$Sanitize_User = sanitize_user($id);
$title = esc_html($_POST['f2']);
$shortcode = esc_html($_POST['f3']);
$wpdb->update( $table_name, 
			array('id'=>$uid, 'data'=>$data, 'user_id'=>$Sanitize_User, 'name'=>$title,'shortcode'=>$shortcode),
			array('id'=>$uid)
			);

}
add_action ('wp_ajax_norpiv_edit_data','wpenlight');
add_action ('wp_ajax_edit_data','wpenlight');

//New add table
function wpenlight_table_enlight_pricer_form_page_handler($title )
{
 ?>

            <label for="name"><?php _e('Name', 'wpenlight_table')?></label></br>
  
            <input id="title_wpenlight" name="title_wpenlight" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>">
            <!--input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>"
                    size="50" class="code" placeholder="<?php _e('Your name', 'wpenlight_table')?>" required-->
        </td>
    </tr>
 <input type="hidden" name="url" id="url" value="<?php echo  plugin_dir_url( __FILE__ ) . 'table-data.php' ;?>">
    <section id="generator">
        <div class="container-fluid webindia-demo">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-primary" id="columns">
                            <div class="panel-heading main-panel" data-toggle="collapse" data-parent="#accordion" data-target="#collapseTwo">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-th">
                        </span>Columns <span class="caret"></span>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="column-count-div">
                                        <?php
                                       /* global $wpdb;
                                        $table_name=$wpdb->prefix.'ept_data';
                                      $numRows = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name");
                                     if($numRows == 0){
                                         */?>
                                        <label> Column count: </label> <input id="column-count" type="number" min="1" max="6" value="6" onchange="generate()" />
                                        <?php/*
                                     }
                                     else{
                                        $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
                                     
                                        foreach ( $result as $print )   {
                                            $data = $print->no_collumn;
                                            
                                            $numRows = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name");                                  
                                         ?>
                                         <label> Column count: </label> <input id="column-count" type="number" min="1" max="6" value="<?php echo $data ;?>" onchange="generate()" /><?php
                                          }
                                     }*/?>
                                            
                                            <label id="column-recommendation">Max 6 column recommended</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary panel-group" id="design">
                            <div class="panel-heading main-panel" data-toggle="collapse" data-parent="#accordion" data-target="#collapseOne">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-wrench">
                        </span>Design <span class="caret"></span>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body text-center">
                                    <div class="dropdown design-button">
                                        <button class="btn btn-default dropdown-toggle" id="style-button" type="button" data-toggle="dropdown">Select Style <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#!" onclick="styleFunction(1)">Style 1</a></li>
                                            <li><a href="#!" onclick="styleFunction(2)">Style 2</a></li>
                                            <li><a href="#!" onclick="styleFunction(3)">Style 3</a></li>
                                        </ul>
                                    </div>
                                    <!--div class="toggle-button">
                          <span>Space between tables</span>
                          <input id="gutter-toggle" checked type="checkbox" data-toggle="toggle" onchange="gutterFunction()">
                        </div-->
                                    <div class="panel-group" id="designGroup">
                                        <div class="panel panel-default text-left" id="color-panel">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#designGroup" data-target="#collapseColor">
                                                <h4 class="panel-title">
                                                    <span class="glyphicon glyphicon-pencil">
                                    </span>Choose Color <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseColor" class="panel-collapse collapse">
                                                <div class="panel-body" id="color-panel-body">
                                                    <div id="select-column-div" class="text-center">
                                                        <label class="select-column" for="selectColumnForColoring">Select Column:</label>
                                                        <select class="form-control select-column" id="selectColumnForColoring">
                                      <option value="0">All</option>
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                      <option value="4">4</option>
                                    </select>
                                                    </div>
                                                    <ul class="nav nav-pills nav-stacked color-nav-list">
                                                        <li><a href="#!" class="color-list" onclick="colorFunction('default')">Default <div class="style-color-span" id="column-color-default"></div> </a></li>
                                                        <li><a href="#!" class="color-list" onclick="colorFunction('gray')">Gray <div class="style-color-span" id="column-color-gray"></div>  </a></li>
                                                        <li><a href="#!" class="color-list" onclick="colorFunction('brown')">Brown <div class="style-color-span" id="column-color-brown"></div> </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="button">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#designGroup" data-target="#collapseButton">
                                                <h4 class="panel-title text-left">
                                                    <span class="glyphicon glyphicon-modal-window">
                        </span>Button <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseButton" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div id="select-column-div" class="text-center">
                                                        <label class="select-column" for="selectColumnForButton">Select Column:</label>
                                                        <select class="form-control select-column" id="selectColumnForButton">
                                      <option value="0">All</option>
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                    </select>
                                                    </div>
                                                    <div id="button-type-div">
                                                        <label id="select-button">Select button type</label>
                                                        <form class="button-form text-left">
                                                            <div class="radio">
                                                                <label><input type="radio" checked name="optradio" class="button-type" onchange="buttonStyle(1)">Rectangle Button</label>
                                                            </div>
                                                            <div class="radio">
                                                                <label><input type="radio" name="optradio" class="button-type" onchange="buttonStyle(2)">Smooth Button</label>
                                                            </div>
                                                            <div class="radio">
                                                                <label><input type="radio" name="optradio" class="button-type" onchange="buttonStyle(3)">Oval Button</label>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="dropdown design-button" id="select-button-effect">
                                                        <button class="btn btn-default dropdown-toggle" id="effect-button" type="button" data-toggle="dropdown">Select Effect <span class="caret"></span></button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="#!" onclick="buttonEffectFunction(0)">No effect</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(1)">Sweep to right</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(2)">Sweep to left</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(3)">Sweep to bottom</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(4)">Sweep to top</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(5)">Radial out</a></li>
                                                            <li><a href="#!" onclick="buttonEffectFunction(6)">Radial in</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="ribbon">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#designGroup" data-target="#collapseRibbon">
                                                <h4 class="panel-title text-left">
                                                    <span class="glyphicon glyphicon-modal-window">
                        </span>Ribbon <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseRibbon" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div id="select-column-div" class="text-center">
                                                        <label class="select-column" for="selectColumnForButton">Select Column:</label>
                                                        <select class="form-control select-column" id="selectColumnForRibbon" onchange="ribbonFunction()">
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                    </select>
                                                    </div>
                                                    <label for="useRibbon" id="ribbonLabel"> Use Ribbon <input type="checkbox" id="useRibbon" onchange="useRibbonFunction()"> </label>
                                                    <div id="ribbon-type-div">
                                                        <label id="select-ribbon">Select ribbon type</label>
                                                        <form class="button-form" id="ribbon-type-radio">
                                                            <div class="radio">
                                                                <label><input type="radio" checked name="optradio" class="ribbon-type" onchange="ribbonStyle(1)">Ribbon 1</label>
                                                            </div>
                                                            <div class="radio">
                                                                <label><input type="radio" name="optradio" class="ribbon-type" onchange="ribbonStyle(2)">Ribbon 2</label>
                                                            </div>
                                                            <div class="radio">
                                                                <label><input type="radio" name="optradio" class="ribbon-type" onchange="ribbonStyle(3)">Ribbon 3</label>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div id="select-ribbon-position-div" class="text-center">
                                                        <label class="select-column" for="selectRibbonPosition">Ribbon Position:</label>
                                                        <select class="form-control select-column" id="selectRibbonPosition" onchange="ribbonPosition()">
                              <option value="left">Left</option>
                                <option value="right">Right</option>
                                <option value="top left">Top left</option>
                                <option value="top right">Top Right</option>
            </select>
                                                    </div>
                                                    <div id="column-button-div">
                                                        <label>Content: </label><input type='text' id='ribbon-content' class="option-text" onkeyup="ribbonContentFunction()">
                                                    </div>
                                                    <div class="panel panel-default" id="ribbonColor">
                                                        <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#ribbonColor" data-target="#collapseRibbonColor">
                                                            <h4 class="panel-title text-left">
                                                                <span class="glyphicon glyphicon-modal-window">
                        </span>Ribbon Color<span class="caret"></span>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseRibbonColor" class="panel-collapse collapse">
                                                            <div class="panel-body text-left">
                                                                <ul class="nav nav-pills nav-stacked color-nav-list">
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="ribbonColorFunction('dark-aquamarine')">Dark aquamarine <div class="style-color-span" id="ribbon-color-dark-aquamarine"></div> </a></li>
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="ribbonColorFunction('cated-blue')">Cated Blue <div class="style-color-span" id="ribbon-color-cated-blue"></div>  </a></li>
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="ribbonColorFunction('dark-green')">Dark Green <div class="style-color-span" id="ribbon-color-dark-green"></div>  </a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="tooltip">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#designGroup" data-target="#collapseTooltip">
                                                <h4 class="panel-title text-left">
                                                    <span class="glyphicon glyphicon-modal-window">
                        </span>Tooltip <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseTooltip" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div id="tooltip-select">
                                                        <div id="select-column-div-tooltip" class="text-center column-count-div">
                                                            <label class="select-column" for="selectColumnForTooltip">Select Column:</label>
                                                            <select class="form-control select-column" id="selectColumnForTooltip" onchange="tooltipColumnFunction()">
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                    </select>
                                                        </div>
                                                        <div id="select-option-div-tooltip" class="text-center column-count-div">
                                                            <label class="select-column" for="selectOptionforTooltip">Select Option:</label>
                                                            <select class="form-control select-column" id="selectOptionForTooltip" onchange="tooltipOptionFunction()">
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                    </select>
                                                        </div>
                                                    </div>
                                                    <label for="useTooltip" id="tooltipLabel"> Use Tooltip <input type="checkbox" id="useTooltip" onchange="useTooltipFunction()"> </label>
                                                    <div id="select-tooltip-position-div" class="text-center">
                                                        <label class="select-column" for="selectTooltipPlacement">Tooltip Position:</label>
                                                        <select class="form-control select-column" id="selectTooltipPlacement" onchange="recreateTooltip()">
                              <option value="left">Left</option>
                                <option value="right">Right</option>
            </select>
                                                    </div>
                                                    <div id="select-data-placement-div" class="text-center">
                                                        <label class="select-column" for="selectTooltipPosition">Data Placement:</label>
                                                        <select class="form-control select-column" id="selectTooltipPosition" onchange="recreateTooltip()">
                              <option value="left">Left</option>
                                <option value="right">Right</option>
                                <option value="top">Top</option>
                                <option value="bottom">Bottom</option>
            </select>
                                                    </div>
                                                    <div id="tooltip-content-div">
                                                        <label>Content: </label><input type='text' id='tooltip-content' class="option-text" onkeyup="tooltipContentFunction()">
                                                    </div>
                                                    <div class="panel panel-default" id="tooltipColor">
                                                        <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#tooltipColor" data-target="#collapseTooltipColor">
                                                            <h4 class="panel-title text-left">
                                                                <span class="glyphicon glyphicon-modal-window">
                                    </span>Tooltip Color<span class="caret"></span>
                                                            </h4>
                                                        </div>

                                                        <div id="collapseTooltipColor" class="panel-collapse collapse">
                                                            <div class="panel-body text-left">
                                                                <ul class="nav nav-pills nav-stacked color-nav-list">
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="tooltipColorFunction('default')">Default <div class="style-color-span" id="ribbon-color-black"></div>  </a></li>
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="tooltipColorFunction('dark-aquamarine')">Dark aquamarine <div class="style-color-span" id="ribbon-color-dark-aquamarine"></div> </a></li>
                                                                    <li><a href="#!" class="ribbon-color-list" onclick="tooltipColorFunction('cated-blue')">Cated Blue <div class="style-color-span" id="ribbon-color-cated-blue"></div>  </a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary" id="content">
                            <div class="panel-heading main-panel" data-toggle="collapse" data-parent="#accordion" href="#collapseContent">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-file">
                        </span>Content <span class="caret"></span>
                                </h4>
                            </div>
                            <div id="collapseContent" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div id="select-column-div" class="text-center">
                                        <label class="select-column" for="selectColumnForContent">Select Column:</label>
                                        <select class="form-control select-column" id="selectColumnForContent" onchange="contentChangeColumn()">
															            <option value="0">All</option>
															            <option value="1">1</option>
															            <option value="2">2</option>
															            <option value="3">3</option>
															            <option value="4">4</option>
			          							</select>
                                    </div>
                                    <div class="panel-group" id="contentGroup">
                                        <div class="panel panel-default" id="title-content">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#contentGroup" data-target="#collapseTitleContent">
                                                <h4 class="panel-title">
                                                    <span class="glyphicon glyphicon-file">
                                </span>Title Content <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseTitleContent" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div id="column-title-div">
                                                        <label>Column title: </label><input type='text' id='column-title' class="option-text" onkeyup="columnTitleFunction()">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="price-content">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#contentGroup" data-target="#collapsePriceContent">
                                                <h4 class="panel-title">
                                                    <span class="glyphicon glyphicon-file">
                                </span>Price Content <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapsePriceContent" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div id="column-price-div">
                                                        <div class="prices">
                                                            <label>Price Unit: </label><input type='text' id='price-unit' class="option-text-price" onkeyup="priceUnitFunction()">
                                                        </div>
                                                        <div class="prices">
                                                            <label>Price Count: </label><input type='text' id='price-count' class="option-text-price" onkeyup="priceCountFunction()">
                                                        </div>
                                                        <div class="prices">
                                                            <label>Price Cent: </label><input type='text' id='price-cent' class="option-text-price" onkeyup="priceCentFunction()">
                                                        </div>
                                                        <div class="prices">
                                                            <label>Price Delay: </label><input type='text' id='price-delay' class="option-text-price" onkeyup="priceDelayFunction()">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="option-content">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#contentGroup" data-target="#collapseOptionContent">
                                                <h4 class="panel-title">
                                                    <span class="glyphicon glyphicon-file">
                                    </span>Options Content <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseOptionContent" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div class="option-content-buttons">
                                                        <button type="button" class="btn btn-default design-button" value='Add Button' id='addButton' onclick=" addNewOption()">Add</button>
                                                        <button type="button" class="btn btn-default design-button" value='Remove Button' id='removeButton'>Remove</button>
                                                    </div>
                                                    <div id='TextBoxesGroup'>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="title-content">
                                            <div class="panel-heading secondary-panel" data-toggle="collapse" data-parent="#contentGroup" data-target="#collapseButtonContent">
                                                <h4 class="panel-title">
                                                    <span class="glyphicon glyphicon-file">
                                </span>Button Content <span class="caret"></span>
                                                </h4>
                                            </div>
                                            <div id="collapseButtonContent" class="panel-collapse collapse">
                                                <div class="panel-body text-center">
                                                    <div id="column-button-div">
                                                        <label>Content: </label><input type='text' id='button-content' class="option-text" onkeyup="buttonContentFunction()">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-9 col-md-9">
                    <div class="well">
                        <div id="allTable">
                            <section class="webindia-section">
                                <div class="webindia-style-1">
                                   <?php	/*global $wpdb;
								  $table_name=$wpdb->prefix.'ept_data';
								  $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
								        foreach ( $result as $print )   {
													$decode = $print->data;
 								         echo base64_decode($decode);
											 */?>
                                </div>
                            </section>
                        </div>
                        <div id="button-code-generate">
                            <button type="button" class="btn btn-default btn-lg design-button" value='Add Button' id='button-generate' onclick="takeCode()">Generate</button>
                        </div>
                    </div>
                    <br>
                    <div class="well alert">
                        <a href="#!" class="close" data-hide="alert" aria-label="close" onclick="hideTableCode()">&times;</a>
					 <?php
                                        global $wpdb;
                                        $table_name_shortcode=$wpdb->prefix.'ept_data';
                                      $numRows = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name_shortcode");
                                     if($numRows == 0){
                                         $numRows  =1;
                                     }
                                     else{                             
									 echo $wpdb->insert_id;
										$totalRows = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name_shortcode");
										$numRows =  $totalRows + 1;
                                       }  ?>
									   <input type="hidden" id="shortcode" value="[ept_table=<?php echo $numRows;?>]">
                        <input class="form-control" rows="1" id="html-code" value="Please insert shortcode [ept_table=<?php echo $numRows;?>] to display the table in any post page or widget">
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </section>
<?php

}

function wpenlight_table_enlight_pricer_form_meta_box_handler($item)
{
    ?>

<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="name"><?php _e('Name', 'wpenlight_table')?></label>
        </th>
        <td>
            <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>"
            <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>"
                    size="50" class="code" placeholder="<?php _e('Your name', 'wpenlight_table')?>" required>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="shortcode"><?php _e('E-Mail', 'wpenlight_table')?></label>
        </th>
        <td>
            <input id="email" name="email" type="email" style="width: 95%" value="<?php echo esc_attr($item['email'])?>"
                    size="50" class="code" placeholder="<?php _e('Your E-Mail', 'wpenlight_table')?>" required>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="age"><?php _e('Age', 'wpenlight_table')?></label>
        </th>
        <td>
            <input id="age" name="age" type="number" style="width: 95%" value="<?php echo esc_attr($item['age'])?>"
                    size="50" class="code" placeholder="<?php _e('Your age', 'wpenlight_table')?>" required>
        </td>
    </tr>
    </tbody>
</table>
<?php
}

function wpenlight_table_validate_person($item)
{
    $messages = array();

    if (empty($item['name'])) $messages[] = __('Name is required', 'wpenlight_table');
    if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'wpenlight_table');
    if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'wpenlight_table');
    if (empty($messages)) return true;
    return implode('<br />', $messages);
}

function wpenlight_table_languages()
{
    load_plugin_textdomain('wpenlight_table', false, dirname(plugin_basename(__FILE__)));
}

add_action('init', 'wpenlight_table_languages');