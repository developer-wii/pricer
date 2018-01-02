var selectedColor = ['default', 'default', "default", "default", "default", "default"];
var columnCount = 0;
var columns = '';
var optionContentText = ['','','','','',''];
var optionCountArray = [0,0,0,0,0,0,0];
var selectedColumnforContent = 0;
var webindiaButtonStyle = [1,1,1,1,1,1];
var webindiaButtonEffect = [ 'no', 'no', 'no', 'no', 'no', 'no'];
var webindiaButtonComplete;

var webindiaRibbonAdded = [false, false, false, false, false, false];
var webindiaRibbonTybe = [1, 1, 1, 1, 1, 1];
var webindiaRibbonPosition = ["left", "left", "left", "left", "left", "left"];
var selectedRibbonColor = ['dark-aquamarine', 'dark-aquamarine', "dark-aquamarine", "dark-aquamarine", "dark-aquamarine", "dark-aquamarine"];
var $=jQuery;
var currentStyle = 1;

var tooltipColor = [new Array(15), new Array(15), new Array(15), new Array(15), new Array(15), new Array(15)];

for(var i=0; i < 6; i++){
    for(var j=0; j < 15; j++){
        tooltipColor[i[j]] = 'default';

        console.log(tooltipColor[i[j]]);
    }
}


generate();


//Generate Desired Table
function generate() {
	columnCount = document.getElementById("column-count").value;
	generateColumns(columnCount);
	generatewebindia();

	selectColumnforChanging();
	selectedColumnforContent = 0;
}


//Generate Columns with selected column number
function generateColumns(column_count){
	columns ='';
	for (var i = 1; i <= column_count; i++) {

        if (column_count == 5) {
            if (i == 1) {
                columns += getTableColumn('col-sm-2 col-sm-offset-1',  selectedColor[i-1], i, webindiaButtonStyle[i-1], webindiaButtonEffect[i-1]);

                i = 2;
            }
        }
        columns += getTableColumn(getResponsivenessNumber(), selectedColor[i-1], i, webindiaButtonStyle[i-1],  webindiaButtonEffect[i-1]);

    }
}

//Table Column
function getTableColumn(respNum, colColor, count, buttonStyle, buttonEffect) {
        return '<div class="' + respNum + '">\n<div class="webindia-plan webindia-color-' + colColor + '">\n' +
        '<div class="webindia-title"> \n' +
        '<h2 class="webindia-title-text">Plan</h2>\n </div> <!-- end title -->\n' +
        '<div class="webindia-price">\n' +
        '<span class="price-unit">$</span>\n' +
        '<span class="price-number">149</span>\n' +
        '<span class="price-cent">.99</span>\n' +
        '<span class="price-delay">/month</span></div>  <!-- end price -->\n' +
        '<div class="webindia-options">\n<ul>\n' +
				optionContentText[count-1] +
        '\n</ul></div> <!-- end options -->\n' +
        '<div class="webindia-button-section">\n' +
        '<a href="#" class="webindia-button webindia-button-'+buttonStyle+' webindia-button-effect-'+buttonEffect+'">Buy Now</a>\n</div> <!-- end button -->\n' +
        '</div>  <!-- end webindia plan and color -->\n</div> <!-- end plan -->\n';

}


// Bootstrap grid columns number
function getResponsivenessNumber(){
        switch (document.getElementById("column-count").value) {
            case '1':
                return 'col-sm-12';
                break;
            case '2':
                return 'col-sm-6';
                break;
            case '3':
                return 'col-sm-4';
                break;
            case '4':
                return 'col-sm-3';
                break;
            case '5':
                return 'col-sm-2';
                break;
            case '6':
                return 'col-sm-2';
                break;
        }
}

//Generate webindia Pricing Table
function generatewebindia() {
	var webindiaSection = '';
	webindiaSection = ('\n<div class="webindia-style-' + currentStyle + '">\n' +
                       '<div class="row">\n' + columns  +
                       '</div>\n</div>\n' +
                       '</div> <!-- end webindia style -->\n');

    //document.getElementById('webindia-pricing-table').innerHTML = webindiaSection;
    document.getElementsByClassName('webindia-section')[0].innerHTML = webindiaSection;
}


//webindia Price Table Style function
function styleFunction(style) {
    document.getElementsByClassName("webindia-style-" + currentStyle)[0].className = 'webindia-style-' + style;
    currentStyle = style;

	document.getElementById("style-button").innerHTML = "Style " + style +' '+'<span class="caret"></span>';
}


//Select Table Columns to apply changings
function selectColumnforChanging() {
	selectColumnForColorChanging();
	selectColumnForContentChanging();
	selectColumnForButtonChanging();
	selectColumnForRibbonChanging();
	selectColumnForTooltipChanging();
    selectOptionForTooltipChanging();
}

//Select Column to change color of the column
function selectColumnForColorChanging() {
    var newColumnCountOption;
    var optionText;
    var optionAttribute;
    jQuery('#selectColumnForColoring').empty();

    for ( var j = 0; j <= columnCount; j++) {
        newColumnCountOption = document.createElement('option');
        if (j == 0){
            optionText = document.createTextNode("All");
        } else {
            optionText = document.createTextNode(j);
        }
        newColumnCountOption.appendChild(optionText);

        optionAttribute = document.createAttribute('value');
        optionAttribute.value = j;
        newColumnCountOption.setAttributeNode(optionAttribute);

		document.getElementById("selectColumnForColoring").appendChild(newColumnCountOption);
    }
}

//Select Column to change Content of the column
function selectColumnForContentChanging() {
    var newColumnCountOption;
    var optionText;
    var optionAttribute;
    jQuery('#selectColumnForContent').empty();

    for ( var j = 0; j <= columnCount; j++) {
        newColumnCountOption = document.createElement('option');
        if (j == 0){
            optionText = document.createTextNode("All");
        } else {
            optionText = document.createTextNode(j);
        }
        newColumnCountOption.appendChild(optionText);

        optionAttribute = document.createAttribute('value');
        optionAttribute.value = j;
        newColumnCountOption.setAttributeNode(optionAttribute);
		document.getElementById("selectColumnForContent").appendChild(newColumnCountOption);
    }
}

//Select Column to change Button of the column
function selectColumnForButtonChanging() {
    var newColumnCountOption;
    var optionText;
    var optionAttribute;
   jQuery('#selectColumnForButton').empty();

    for ( var j = 0; j <= columnCount; j++) {
        newColumnCountOption = document.createElement('option');
        if (j == 0){
            optionText = document.createTextNode("All");
        } else {
            optionText = document.createTextNode(j);
        }
        newColumnCountOption.appendChild(optionText);

        optionAttribute = document.createAttribute('value');
        optionAttribute.value = j;
        newColumnCountOption.setAttributeNode(optionAttribute);
		document.getElementById("selectColumnForButton").appendChild(newColumnCountOption);
    }
}

//Select Column to change Ribbon of the column
function selectColumnForRibbonChanging() {
    var newColumnCountOption;
    var optionText;
    var optionAttribute;
    jQuery('#selectColumnForRibbon').empty();

    for ( var j = 1; j <= columnCount; j++) {
        newColumnCountOption = document.createElement('option');

		optionText = document.createTextNode(j);

        newColumnCountOption.appendChild(optionText);

        optionAttribute = document.createAttribute('value');
        optionAttribute.value = j;
        newColumnCountOption.setAttributeNode(optionAttribute);
		document.getElementById("selectColumnForRibbon").appendChild(newColumnCountOption);
    }
}

//Select Column to change Tooltip of the column
function selectColumnForTooltipChanging() {
    var newColumnCountOption;
    var optionText;
    var optionAttribute;
    jQuery('#selectColumnForTooltip').empty();

    for ( var j = 1; j <= columnCount; j++) {
        newColumnCountOption = document.createElement('option');

		optionText = document.createTextNode(j);

        newColumnCountOption.appendChild(optionText);

        optionAttribute = document.createAttribute('value');
        optionAttribute.value = j;
        newColumnCountOption.setAttributeNode(optionAttribute);
		document.getElementById("selectColumnForTooltip").appendChild(newColumnCountOption);
    }
}


//Select Option to change Tooltip of the column
function selectOptionForTooltipChanging() {
    var optionLength = document.getElementsByClassName('webindia-options')[document.getElementById('selectColumnForTooltip').value - 1].getElementsByTagName('li').length;
    var newColumnCountOption;
    var optionText;
    var optionAttribute;
    $('#selectOptionForTooltip').empty();

    for ( var j = 1; j <= optionLength; j++) {
        newColumnCountOption = document.createElement('option');

		optionText = document.createTextNode(j);

        newColumnCountOption.appendChild(optionText);

        optionAttribute = document.createAttribute('value');
        optionAttribute.value = j;
        newColumnCountOption.setAttributeNode(optionAttribute);
		document.getElementById("selectOptionForTooltip").appendChild(newColumnCountOption);
    }
}


//webindia Pricing Table Color function
function colorFunction(color) {
    var selectedColumnforColoring = document.getElementById("selectColumnForColoring").value;
    if (selectedColumnforColoring == 0){
        for(i = 0; i < document.getElementsByClassName("webindia-plan").length; i++) {
            document.getElementsByClassName("webindia-plan")[i].className = 'webindia-plan webindia-color-' + color;
        }
        for(q = 0; q < 6; q++) {
            selectedColor[q] = color;
        }
    }
    else {
        document.getElementsByClassName("webindia-plan")[selectedColumnforColoring-1].className = 'webindia-plan webindia-color-' + color;
        selectedColor[selectedColumnforColoring-1] = color;
    }
}



// Change content of the column
function contentChangeColumn() {
	document.getElementById("column-title").value = '';
    selectedColumnforContent = document.getElementById("selectColumnForContent").value;

	createOptions(selectedColumnforContent);


	document.getElementById("column-title").value = document.getElementsByClassName("webindia-title-text")[selectedColumnforContent-1].innerHTML;
	document.getElementById("price-unit").value = document.getElementsByClassName("price-unit")[selectedColumnforContent - 1].innerHTML;
	document.getElementById("price-count").value = document.getElementsByClassName("price-number")[selectedColumnforContent - 1].innerHTML;
	document.getElementById("price-cent").value = document.getElementsByClassName("price-cent")[selectedColumnforContent - 1].innerHTML;
	document.getElementById("price-delay").value = document.getElementsByClassName("price-delay")[selectedColumnforContent - 1].innerHTML;

	for(var i=0; i<document.getElementsByClassName("webindia-options")[selectedColumnforContent-1].getElementsByTagName("li").length; i++){
		document.getElementsByClassName("option-content")[i].value = document.getElementsByClassName("webindia-options")[selectedColumnforContent-1].getElementsByTagName("li")[i].childNodes[4].nodeValue;
	}

	document.getElementById("button-content").value = document.getElementsByClassName("webindia-button")[selectedColumnforContent - 1].innerHTML;


}

// Change the Content of the selected column's title
function columnTitleFunction() {
    if (selectedColumnforContent == 0){
        for(i = 0; i < document.getElementsByClassName("webindia-plan").length; i++) {
			document.getElementsByClassName("webindia-title-text")[i].innerHTML = document.getElementById("column-title").value;
        }
    }
    else {
		document.getElementsByClassName("webindia-title-text")[selectedColumnforContent - 1].innerHTML = document.getElementById("column-title").value;
	}
}

// Change the Content of the selected column's Price Unit
function priceUnitFunction() {
    if (selectedColumnforContent == 0){
        for(i = 0; i < document.getElementsByClassName("webindia-plan").length; i++) {
			document.getElementsByClassName("price-unit")[i].innerHTML = document.getElementById("price-unit").value;
        }
    }
    else {
		document.getElementsByClassName("price-unit")[selectedColumnforContent - 1].innerHTML = document.getElementById("price-unit").value;
	}
}

// Change the Content of the selected column's Price
function priceCountFunction() {
    if (selectedColumnforContent == 0){
        for(i = 0; i < document.getElementsByClassName("webindia-plan").length; i++) {
			document.getElementsByClassName("price-number")[i].innerHTML = document.getElementById("price-count").value;
        }
    }
    else {
		document.getElementsByClassName("price-number")[selectedColumnforContent - 1].innerHTML = document.getElementById("price-count").value;
	}
}

// Change the Content of the selected column's Price Cent
function priceCentFunction() {
    if (selectedColumnforContent == 0){
        for(i = 0; i < document.getElementsByClassName("webindia-plan").length; i++) {
			document.getElementsByClassName("price-cent")[i].innerHTML = /* '.' + */ document.getElementById("price-cent").value;
        }
    }
    else {
		document.getElementsByClassName("price-cent")[selectedColumnforContent - 1].innerHTML =  document.getElementById("price-cent").value;
	}
}

// Change the Content of the selected column's Price Delay
function priceDelayFunction() {
    if (selectedColumnforContent == 0){
        for(i = 0; i < document.getElementsByClassName("webindia-plan").length; i++) {
			document.getElementsByClassName("price-delay")[i].innerHTML = /* '/' + */ document.getElementById("price-delay").value;
        }
    }
    else {
		document.getElementsByClassName("price-delay")[selectedColumnforContent - 1].innerHTML =  document.getElementById("price-delay").value;
	}
}


// Create Option part of the Table Generator
function createOptions(seciliSutun) {
	$('#TextBoxesGroup').empty();

	if (optionCountArray[seciliSutun] != 0) {

		for(var a = 1; a <= optionCountArray[seciliSutun]; a++) {

		      var newTextBoxDiv = $(document.createElement('div'))
		          .attr({"id": 'TextBoxDiv' + a, "class": "table-option"});

		      newTextBoxDiv.after().html('<label>Option #' + a + ' : </label>' +
		                                 '<input type="text" name="textbox' + a +
		                                 '" id="textbox' + a + '" value="" class="option-text option-content" onkeyup="columnOptionFunction('+ a +')">');

		      newTextBoxDiv.appendTo("#TextBoxesGroup");
		  }
	}
}

// Add new option to Generator
function addNewOption() {
			if (optionCountArray[selectedColumnforContent] > 15) {
					alert("Maximum 16 options recommended");
					return false;
			}

    addOption(selectedColumnforContent);

	generatewebindiaOption();
}

// Add new option to Generator
function addOption(seciliSutun){
	var optionCount = optionCountArray[seciliSutun] + 1;
    if (seciliSutun == 0) {
        addOptionAll();
    }

		else{

			var newTextBoxDiv = $(document.createElement('div'))
                .attr({"id": 'TextBoxDiv' + optionCount, "class": "table-option"});

			newTextBoxDiv.after().html('<label>Option #' + optionCount + ' : </label>' +
                                       '<input type="text" name="textbox' + optionCount +
                                       '" id="textbox' + optionCount + '" value="" class="option-text option-content" onkeyup="columnOptionFunction('+ optionCount +')">');

			newTextBoxDiv.appendTo("#TextBoxesGroup");



			optionCountArray[seciliSutun] += 1;

						for (var i = 1; i < 7; i++) {
							if (optionCountArray[0] < optionCountArray[i]) {
								optionCountArray[0] = optionCountArray[i];
							}
						}

		}
}


// Add new option for all columns of Generator
function addOptionAll() {
	var optionCount = optionCountArray[0] + 1;

    // for(var i=0; i <= 6; i++) {
    //     if (optionCountArray[i] > 15) {
    //         alert("Maximum 15 options recommended");
    //         return false;
    //     }
    // }

    var newTextBoxDiv = $(document.createElement('div'))
    .attr({"id": 'TextBoxDiv' + optionCount, "class": "table-option"});

    newTextBoxDiv.after().html('<label>Option #' + optionCount + ' : </label>' +
                                   '<input type="text" name="textbox' + optionCount  +
                                   '" id="textbox' + optionCount + '" value="" class="option-text option-content" onkeyup="columnOptionFunction('+ optionCount +')">');

    newTextBoxDiv.appendTo("#TextBoxesGroup");
    for(var t = 1; t <= document.getElementsByClassName("webindia-plan").length; t++) {
        optionCountArray[t] = optionCountArray[t] + 1;
    }
  //  generateOption();
    optionCountArray[0] = optionCountArray[0] + 1;
}


// Change the Content of the selected column's Button
function buttonContentFunction() {
    if (selectedColumnforContent == 0){
        for(i = 0; i < document.getElementsByClassName("webindia-plan").length; i++) {
			document.getElementsByClassName("webindia-button")[i].innerHTML = document.getElementById("button-content").value;
        }
    }
    else {
		document.getElementsByClassName("webindia-button")[selectedColumnforContent - 1].innerHTML = document.getElementById("button-content").value;
	}
}




// Delete Option from selected Option
$(document).ready(function () {

	$("#removeButton").click(function () {
		var maxOption = optionCountArray[0];
		for (var i = 0; i <= document.getElementsByClassName("webindia-plan").length; i++) {
			if (maxOption > optionCountArray[i]){
				maxOption = optionCountArray[i];
			}
		}

		if (maxOption == 0) {
			if(optionCountArray[selectedColumnforContent] == 0) {
				alert("No more textbox to remove");
				return false;
			}
		}

		if (selectedColumnforContent == 0) {
			for(var i = 1; i <= document.getElementsByClassName("webindia-plan").length; i++) {
				optionCountArray[i] -= 1;
				var sutun = document.getElementsByClassName("webindia-options")[i-1];
				$(sutun.getElementsByTagName('li')[optionCountArray[i]]).remove();

				if(optionCountArray[i] < 0) {
					optionCountArray[i] = 0;
				}
				optionContentText[i-1] = document.getElementsByClassName("webindia-options")[i-1].getElementsByTagName('ul')[0].innerHTML;
			}

			$("#TextBoxDiv" + optionCountArray[0]).remove();

			optionCountArray[0] = optionCountArray[0] - 1;
			if(optionCountArray[0] < 0) {
					optionCountArray[0] = 0;
			}
		}
		else {

			$("#TextBoxDiv" + optionCountArray[selectedColumnforContent]).remove();
			optionCountArray[selectedColumnforContent] = optionCountArray[selectedColumnforContent] - 1;
			var sutun = document.getElementsByClassName("webindia-options")[selectedColumnforContent-1];

			$(sutun.getElementsByTagName('li')[optionCountArray[selectedColumnforContent]]).remove();

			if(optionCountArray[selectedColumnforContent] < 0) {
				optionCountArray[selectedColumnforContent] = 0;
			}

			optionContentText[selectedColumnforContent-1] = document.getElementsByClassName("webindia-options")[selectedColumnforContent-1].getElementsByTagName('ul')[0].innerHTML;
		}
	});

});





// Change the Content of the Column
function columnOptionFunction(count) {
    if (selectedColumnforContent == 0){
        for(i = 0; i < document.getElementsByClassName("webindia-plan").length; i++) {
			//option = document.getElementsByClassName("webindia-options")[i];
            //option.getElementsByTagName("li")[count-1].innerHTML = document.getElementsByClassName("option-content")[count-1].value;

			//optionContentText[i] = document.getElementsByClassName("webindia-options")[i].getElementsByTagName('ul')[0].innerHTML;


            var option = document.getElementsByClassName("webindia-options")[i].getElementsByTagName("li")[count-1];

            var optionContext = option.childNodes[0];

            optionContext.nodeValue = document.getElementsByClassName("option-content")[count-1].value;


            optionContentText[i] = document.getElementsByClassName("webindia-options")[i].getElementsByTagName('ul')[0].innerHTML;



        }
    }	else {
		    //var option = document.getElementsByClassName("webindia-options")[selectedColumnforContent - 1];
		    //option.getElementsByTagName("li")[count-1].innerHTML = document.getElementsByClassName("option-content")[count-1].value;

            var option = document.getElementsByClassName("webindia-options")[selectedColumnforContent - 1].getElementsByTagName("li")[count-1];

            var optionContext = option.childNodes[0];

            optionContext.nodeValue = document.getElementsByClassName("option-content")[count-1].value;


			optionContentText[selectedColumnforContent-1] = document.getElementsByClassName("webindia-options")[selectedColumnforContent-1].getElementsByTagName('ul')[0].innerHTML;

			}
}




// Generate webindia Pricing Table Option section
function generatewebindiaOption() {
    if (selectedColumnforContent == 0) {
        for(var ii = 0; ii < document.getElementsByClassName("webindia-plan").length; ii++) {

			var sutun = document.getElementsByClassName("webindia-options")[ii];
            var newLiOption = $(document.createElement('li'));
            var sutunSirasi = sutun.getElementsByTagName('li').length + 1;
            newLiOption.after().html('Option ' + sutunSirasi);
            newLiOption.appendTo(sutun.getElementsByTagName('ul'));


			optionContentText[ii] = document.getElementsByClassName("webindia-options")[ii].getElementsByTagName('ul')[0].innerHTML;
        }
    }
    else{
        var sutun = document.getElementsByClassName("webindia-options")[selectedColumnforContent-1];
        var newLiOption = $(document.createElement('li'));
        var sutunSirasi = sutun.getElementsByTagName('li').length + 1;
        newLiOption.after().html('Option ' + sutunSirasi);
        newLiOption.appendTo(sutun.getElementsByTagName('ul'));

				optionContentText[selectedColumnforContent-1] = document.getElementsByClassName("webindia-options")[selectedColumnforContent-1].getElementsByTagName('ul')[0].innerHTML;
		}
}



// Change the Style of the Pricing Table Button
function buttonStyle(style) {
	var selectedColumnforButton = document.getElementById("selectColumnForButton").value;
	if (selectedColumnforButton == 0){
        for(i = 0; i < document.getElementsByClassName("webindia-plan").length; i++) {
			document.getElementsByClassName("webindia-button")[i].className = "webindia-button webindia-button-" + style + ' webindia-button-effect-' + webindiaButtonEffect[i];
			webindiaButtonStyle[i] = style;
        }
    }	else {
			document.getElementsByClassName("webindia-button")[selectedColumnforButton - 1].className = "webindia-button webindia-button-" + style + ' webindia-button-effect-' + webindiaButtonEffect[selectedColumnforButton-1];
			webindiaButtonStyle[selectedColumnforButton-1] = style;
		}
}


// Change the Effect of the Pricing Table Button
function buttonEffectFunction(effect) {
	var selectedColumnforButton = document.getElementById("selectColumnForButton").value;
	if (selectedColumnforButton == 0){
        for(i = 0; i < document.getElementsByClassName("webindia-plan").length; i++) {
            if (effect == 0){
                document.getElementsByClassName("webindia-button")[i].className = "webindia-button webindia-button-" + webindiaButtonStyle[i] + " webindia-button-effect-no";
                webindiaButtonEffect[i] = 'no';
            }else{
                document.getElementsByClassName("webindia-button")[i].className = "webindia-button webindia-button-" + webindiaButtonStyle[i] + " webindia-button-effect-" + effect;
                webindiaButtonEffect[i] = effect;
            }
        }
    }	else {

            if(effect == 0){
                document.getElementsByClassName("webindia-button")[selectedColumnforButton - 1].className = "webindia-button webindia-button-" + webindiaButtonStyle[selectedColumnforButton-1] + " webindia-button-effect-no";
                webindiaButtonEffect[selectedColumnforButton-1] = 'no';
            }else{
                document.getElementsByClassName("webindia-button")[selectedColumnforButton - 1].className = "webindia-button webindia-button-" + webindiaButtonStyle[selectedColumnforButton-1] + " webindia-button-effect-" + effect;
                webindiaButtonEffect[selectedColumnforButton-1] = effect;
            }
		}

    var buttonEffectContent = document.getElementById("select-button-effect").getElementsByTagName("ul")[0].getElementsByTagName("li")[effect].getElementsByTagName("a")[0].innerHTML;
	document.getElementById("effect-button").innerHTML = buttonEffectContent+ " " + '<span class="caret"></span>';
}


// Get the created webindia Pricing Table HTML code
function takeCode() {
  var code = document.getElementById('allTable').innerHTML;
  var name = jQuery ('#url').val();
  var count = jQuery ('#column-count').val();
    alert(count);
  var encodedString = btoa(code);
  var encoded = encodeURIComponent(name);
  {

       jQuery.ajax({ type: 'POST',url: 'admin-ajax.php',data: { action:'save_data',f0: encodedString,f1:encoded ,f2:count}, success: function(result){
           alert('Table Saved Successfully'); 
        }}); }

	 jQuery('.alert').show();
}



//webindia Ribbon Function
function ribbonFunction(){

	var selectedColumnforRibbon = document.getElementById("selectColumnForRibbon").value;


	if (webindiaRibbonAdded[selectedColumnforRibbon-1] == false) {
		document.getElementById("useRibbon").checked = false;
	}
	else{
		document.getElementById("useRibbon").checked = true;
	}

	document.getElementsByClassName("ribbon-type")[webindiaRibbonTybe[selectedColumnforRibbon-1]-1].checked = true;

	document.getElementById("selectRibbonPosition").value = webindiaRibbonPosition[selectedColumnforRibbon-1];

	document.getElementById("ribbon-content").value = '';

}


//Create webindia Ribbon on the selected Column
function useRibbonFunction(){
	var selectedColumnforRibbon = document.getElementById("selectColumnForRibbon").value;
		if (webindiaRibbonAdded[selectedColumnforRibbon-1] == false) {
			webindiaRibbonAdded[selectedColumnforRibbon-1] = true;
			document.getElementById("useRibbon").checked = true;

			var newRibbonDiv = $(document.createElement('div'))
                .attr({"class": 'webindia-ribbon webindia-ribbon-'+webindiaRibbonTybe[selectedColumnforRibbon-1]+' '+webindiaRibbonPosition[selectedColumnforRibbon-1] +' '+ selectedRibbonColor[selectedColumnforRibbon-1]});

			newRibbonDiv.after().html('\n<span>Best Offer</span>');

			var ribbonColumn = document.getElementsByClassName("webindia-plan")[selectedColumnforRibbon-1];
			newRibbonDiv.prependTo(ribbonColumn);
		}
		else {
			var deletedRibbon = document.getElementsByClassName("webindia-plan")[selectedColumnforRibbon-1].getElementsByClassName("webindia-ribbon")[0];
			$(deletedRibbon).remove();
			webindiaRibbonAdded[selectedColumnforRibbon-1] = false;
			document.getElementById("useRibbon").checked = false;
		}
}

//Change the ribbon style
function ribbonStyle(ribbonType){
	var selectedColumnforRibbon = document.getElementById("selectColumnForRibbon").value;
	if (selectedColumnforRibbon != 0) {
		if (webindiaRibbonAdded[selectedColumnforRibbon-1] == true) {
			document.getElementsByClassName("webindia-plan")[selectedColumnforRibbon-1].getElementsByClassName("webindia-ribbon")[0].className = 'webindia-ribbon webindia-ribbon-'+ribbonType+' '+webindiaRibbonPosition[selectedColumnforRibbon-1] +' '+ selectedRibbonColor[selectedColumnforRibbon-1];

			webindiaRibbonTybe[selectedColumnforRibbon-1] = ribbonType;
		}
	}
}

//Change the ribbon Position on the Column
function ribbonPosition(){
	var selectedColumnforRibbon = document.getElementById("selectColumnForRibbon").value;
	if (selectedColumnforRibbon != 0) {
		if (webindiaRibbonAdded[selectedColumnforRibbon-1] == true) {
			document.getElementsByClassName("webindia-plan")[selectedColumnforRibbon-1].getElementsByClassName("webindia-ribbon")[0].className = 'webindia-ribbon webindia-ribbon-'+webindiaRibbonTybe[selectedColumnforRibbon-1]+' '+ document.getElementById("selectRibbonPosition").value + ' ' + selectedRibbonColor[selectedColumnforRibbon-1];

			webindiaRibbonPosition[selectedColumnforRibbon-1] = document.getElementById("selectRibbonPosition").value;


		}
	}
}

//Change the content of the Ribbon on selected column
function ribbonContentFunction() {
	var selectedColumnforRibbon = document.getElementById("selectColumnForRibbon").value;
	document.getElementsByClassName("webindia-ribbon")[selectedColumnforRibbon - 1].getElementsByTagName("span")[0].innerHTML = document.getElementById("ribbon-content").value;
}







//webindia Pricing Table Ribbon Color function
function ribbonColorFunction(color) {
	var selectedColumnforRibbon = document.getElementById("selectColumnForRibbon").value;
    var selectedColumnforRibbonColoring = document.getElementById("selectColumnForRibbon").value;

    document.getElementsByClassName("webindia-plan")[selectedColumnforRibbonColoring-1].getElementsByClassName("webindia-ribbon")[0].className = 'webindia-ribbon webindia-ribbon-'+webindiaRibbonTybe[selectedColumnforRibbon-1]+' '+ document.getElementById("selectRibbonPosition").value +' '+ color;

    selectedRibbonColor[selectedColumnforRibbon-1] = color;


}



















//Change column for tooltip adding
function tooltipColumnFunction(){
    document.getElementById("tooltip-content").value = '';

	var selectedColumnforTooltip = document.getElementById("selectColumnForTooltip").value;
	var selectedOptionforTooltip = document.getElementById("selectOptionForTooltip").value;

	selectOptionForTooltipChanging();

     var option = document.getElementsByClassName('webindia-options')[document.getElementById('selectColumnForTooltip').value - 1].getElementsByTagName('li')[selectedOptionforTooltip-1].getElementsByTagName('a').length;




    if (option != 0){

    document.getElementById("tooltip-content").value = $(document.getElementsByClassName("webindia-plan")[selectedColumnforTooltip-1].getElementsByClassName('webindia-options')[0].getElementsByTagName('li')[selectedOptionforTooltip-1].getElementsByTagName('a')).attr("data-original-title");
    }



	if (option == 0) {
		document.getElementById("useTooltip").checked = false;
	}
	else{
		document.getElementById("useTooltip").checked = true;
	}
}




//Tooltip Option Function
function tooltipOptionFunction(){
    document.getElementById("tooltip-content").value = '';

    var selectedColumnforTooltip = document.getElementById("selectColumnForTooltip").value;
	var selectedOptionforTooltip = document.getElementById("selectOptionForTooltip").value;

	var option = document.getElementsByClassName('webindia-options')[document.getElementById('selectColumnForTooltip').value - 1].getElementsByTagName('li')[selectedOptionforTooltip-1].getElementsByTagName('a').length;

    if (option != 0){

    document.getElementById("tooltip-content").value = $(document.getElementsByClassName("webindia-plan")[selectedColumnforTooltip-1].getElementsByClassName('webindia-options')[0].getElementsByTagName('li')[selectedOptionforTooltip-1].getElementsByTagName('a')).attr("data-original-title");




    }

	if (option == 0) {
		document.getElementById("useTooltip").checked = false;
	}
	else{
		document.getElementById("useTooltip").checked = true;

	}


    console.log($(document.getElementsByClassName("webindia-plan")[selectedColumnforTooltip-1].getElementsByClassName('webindia-options')[0].getElementsByTagName('li')[selectedOptionforTooltip-1].getElementsByTagName('a')));
}

//Create Tooltip on the selected Option
function useTooltipFunction(){
	var selectedColumnforTooltip = document.getElementById("selectColumnForTooltip").value;
    var selectedOptionForTooltip = document.getElementById("selectOptionForTooltip").value;
    var dataPlacement = document.getElementById("selectTooltipPosition").value;
    var position = document.getElementById("selectTooltipPlacement").value;

    var option = document.getElementsByClassName('webindia-options')[document.getElementById('selectColumnForTooltip').value - 1].getElementsByTagName('li')[selectedOptionForTooltip-1].getElementsByTagName('a').length;

		if (option == 0) {

			var newTooltipDiv = $(document.createElement('a'))
                .attr({"href": "#", "class": "webindia-tooltip tooltip-color-" + tooltipColor[(selectedColumnforTooltip - 1)[selectedOptionForTooltip - 1]]+ ' ' + position, "data-toggle": "tooltip", "data-placement": dataPlacement});

			newTooltipDiv.after().html('\n<span class="glyphicon glyphicon-info-sign webindia-option-tooltip-icon"></span>');

			var tooltipOption = document.getElementsByClassName("webindia-plan")[selectedColumnforTooltip-1].getElementsByClassName('webindia-options')[0].getElementsByTagName('li')[selectedOptionForTooltip-1];
			newTooltipDiv.appendTo(tooltipOption);

            document.getElementById("useTooltip").checked = true;
		}
		else {
			var deletedTooltip = document.getElementsByClassName("webindia-plan")[selectedColumnforTooltip-1].getElementsByClassName('webindia-options')[0].getElementsByTagName('li')[selectedOptionForTooltip-1].getElementsByTagName('a');
			$(deletedTooltip).remove();
			document.getElementById("useTooltip").checked = false;
		}


    console.log(tooltipColor[(selectedColumnforTooltip - 1)[selectedOptionForTooltip - 1]]);
}


//Change the content of the Tooltip on selected column and option
function tooltipContentFunction() {
	var selectedColumnforTooltip = document.getElementById("selectColumnForTooltip").value;
    var selectedOptionForTooltip = document.getElementById("selectOptionForTooltip").value;
    var newTitle = document.getElementById("tooltip-content").value;

    $(document.getElementsByClassName("webindia-plan")[selectedColumnforTooltip-1].getElementsByClassName('webindia-options')[0].getElementsByTagName('li')[selectedOptionForTooltip-1].getElementsByTagName('a')).attr({"data-original-title": newTitle}).tooltip('fixTitle');

}





// Regenerate the tooltip for position changings
function recreateTooltip(){
    var selectedColumnforTooltip = document.getElementById("selectColumnForTooltip").value;
    var selectedOptionForTooltip = document.getElementById("selectOptionForTooltip").value;
    var dataPlacement = document.getElementById("selectTooltipPosition").value;
    var position = document.getElementById("selectTooltipPlacement").value;


		if (document.getElementById("useTooltip").checked == true) {

            var deletedTooltip = document.getElementsByClassName("webindia-plan")[selectedColumnforTooltip-1].getElementsByClassName('webindia-options')[0].getElementsByTagName('li')[selectedOptionForTooltip-1].getElementsByTagName('a');
			$(deletedTooltip).remove();

            var newTooltipDiv = $(document.createElement('a'))
                .attr({"href": "#", "class": "webindia-tooltip tooltip-color-"+tooltipColor[(selectedColumnforTooltip - 1)[selectedOptionForTooltip - 1]]+ ' ' + position, "data-toggle": "tooltip", "data-placement": 'auto '+ dataPlacement});

			newTooltipDiv.after().html('\n<span class="glyphicon glyphicon-info-sign webindia-option-tooltip-icon"></span>');

			var tooltipOption = document.getElementsByClassName("webindia-plan")[selectedColumnforTooltip-1].getElementsByClassName('webindia-options')[0].getElementsByTagName('li')[selectedOptionForTooltip-1];
			newTooltipDiv.appendTo(tooltipOption);


            tooltipContentFunction();

            console.log(tooltipOption);

		}
}





//Change Color of Tooltip on the selected Option
function tooltipColorFunction(color){
	var selectedColumnforTooltip = document.getElementById("selectColumnForTooltip").value;
    var selectedOptionForTooltip = document.getElementById("selectOptionForTooltip").value;
    var position = document.getElementById("selectTooltipPlacement").value;

    document.getElementsByClassName("webindia-plan")[selectedColumnforTooltip-1].getElementsByClassName('webindia-options')[0].getElementsByTagName('li')[selectedOptionForTooltip-1].getElementsByTagName('a')[0].className = 'webindia-tooltip tooltip-color-' + color + ' ' + position;



    tooltipColor[(selectedColumnforTooltip - 1)[selectedOptionForTooltip - 1]] = color;

    console.log( document.getElementsByClassName("webindia-plan")[selectedColumnforTooltip-1].getElementsByClassName('webindia-options')[0].getElementsByTagName('li')[selectedOptionForTooltip-1].getElementsByTagName('a')[0].className);
}




function gutterFunction() {
  var toggleButton = document.getElementById("gutter-toggle");
  var rowClass = "no-gutter-webindia";

  if (toggleButton.checked == false) {
    document.getElementsByClassName("webindia-section")[0].getElementsByClassName("row")[0].className = "row " + rowClass;
  } else {
    document.getElementsByClassName("webindia-section")[0].getElementsByClassName("row")[0].className = "row";
  }

  console.log(toggleButton.checked);
}








$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});


$(function(){
    $("[data-hide]").on("click", function(){
        $("." + $(this).attr("data-hide")).hide();
    });
});


$('.alert').hide();


generateColumns(columnCount);
//generatewebindia();
for(var i=0; i<5;i++){
addNewOption();
}

//columnCount = document.getElementById("column-count").value;
document.getElementById("column-count").value = 3;





generate();
