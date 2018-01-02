<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$dirname = dirname(__FILE__);
$root = false !== mb_strpos( $dirname, 'wp-content' ) ? mb_substr( $dirname, 0, mb_strpos( $dirname, 'wp-content' ) ) : $dirname;
function ept_dashboard_admin_page() {	?>
    <h1>
        <center>Wpenlight Pricer Table</center>
    </h1>
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
                                        global $wpdb;
                                        $table_name=$wpdb->prefix.'ept_data';
                                      $numRows = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name");
                                     if($numRows == 0){
                                         ?>
                                        <label> Column count: </label> <input id="column-count" type="number" min="1" max="6" value="6" onchange="generate()" />
                                        <?php
                                     }
                                     else{
                                        $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
                                     
                                        foreach ( $result as $print )   {
                                            $data = $print->no_collumn;
                                            
                                            $numRows = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name");
                                            echo $numRows;
                                         ?>
                                         <label> Column count: </label> <input id="column-count" type="number" min="1" max="6" value="<?php echo $data ;?>" onchange="generate()" /><?php
                                          }
                                     }?>
                                            
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
                                    <?php	global $wpdb;
								  $table_name=$wpdb->prefix.'ept_data';
								  $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
								        foreach ( $result as $print )   {
													$decode = $print->data;
 								         echo base64_decode($decode);
											 }?>
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
                        <input class="form-control" rows="1" id="html-code" value="<?php echo 'Please insert shortcode [ept_table] to display the table in any post page or widget';?>">
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </section>
    <?php
}
//register admin menus
add_action('admin_menu', 'ept_admin_menus');

// Admin Menu
function ept_admin_menus(){
  $top_menu_item = 'ept_dashboard_admin_page';

    add_menu_page( '', 'Enlight Pricer', 'manage_options', 'ept_dashboard_admin_page', 'ept_dashboard_admin_page', 'dashicons-chart-bar' , 32);

    add_submenu_page( $top_menu_item, '', 'Dashboard', 'manage_options', $top_menu_item, $top_menu_item );

}
?>