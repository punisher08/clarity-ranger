var tabulatortable ;


var minMaxFilterEditor = function(cell, onRendered, success, cancel, editorParams){


// console.log(cell.getField());
    var end;

    var container = document.createElement("span");
    var colfield = cell.getField();

    //create and style inputs
    var start = document.createElement("input");
    start.setAttribute("type", "number");
    start.setAttribute("placeholder", "Min");
    start.setAttribute("min", 0);
    start.setAttribute("max", 100);
    start.style.padding = "4px";
    start.style.width = "48%";
    start.style.boxSizing = "border-box";

    start.value = cell.getValue();

    function buildValues(){
        success({
            start:start.value,
            end:end.value,
			field : colfield
        });
    }

    function keypress(e){
        if(e.keyCode == 13){
            buildValues();
        }

        if(e.keyCode == 27){
            cancel();
        }
    }

    end = start.cloneNode();
    end.setAttribute("placeholder", "Max");

    start.addEventListener("change", buildValues);
    start.addEventListener("blur", buildValues);
    start.addEventListener("keydown", keypress);

    end.addEventListener("change", buildValues);
    end.addEventListener("blur", buildValues);
    end.addEventListener("keydown", keypress);


    container.appendChild(start);
    container.appendChild(end);

    return container;
 }

function minMaxFilterFunction(headerValue, rowValue, rowData, filterParams){
    //headerValue - the value of the header filter element
    //rowValue - the value of the column in this row
    //rowData - the data for the row being filtered
    //filterParams - params object passed to the headerFilterFuncParams property

        /*  if(rowValue){
			 console.log(rowValue);
			 console.log(headerValue);
			 console.log(rowValue >= headerValue.start && rowValue <= headerValue.end);


            if(headerValue.start != ""){
                if(headerValue.end != ""){
                    return rowValue >= headerValue.start && rowValue <= headerValue.end;
                }else{
                    return false;
                }
            } else{
                if(headerValue.end != ""){
                    return rowValue <= headerValue.end;
                }
            }
        }
		 */


		var start = headerValue.start != "" ? headerValue.start : 0 ;
		var end = headerValue.end != "" ? headerValue.end : 100000 ;
		var field = headerValue.field;
		if( headerValue.start != "" &&  headerValue.end != "" ){


			if(parseInt(headerValue.start) <= parseInt(rowData[field]) &&  parseInt(headerValue.end) >= parseInt(rowData[field])){

				return true;
			}
			else{
				return false;
			}
		}
		else{

			console.log(headerValue);
			console.log(rowData[field]);

			if(rowData[field] == "" && (start != "" && end != "" )  )return false;
			if(parseInt(rowData[field]) < parseInt(start) &&  start != "" )return false;
			if(parseInt(rowData[field]) > parseInt(end) && end != ""  )return false;

		}





    return true; //must return a boolean, true if it passes the filter.

	//return  rowValue < headerValue;
}

var filterOption = function(cell, onRendered, success, cancel, editorParams){
    //cell - the cell component for the editable cell
    //onRendered - function to call when the editor has been rendered
    //success - function to call to pass the successfuly updated value to Tabulator
    //cancel - function to call to abort the edit and return to a normal cell
    //editorParams - params object passed into the editorParams column definition property
	//var editor = document.createElement("input");


	 /*  console.log(cell.getElement());
	   console.log(jQuery(cell.getElement()).hasClass("tabulator-header-filter"));
	  console.log(onRendered); */

	  var isHeader = jQuery(cell.getElement()).hasClass("tabulator-header-filter");
	  
	  var getClass = jQuery(cell.getElement()).attr("class");
	  var editor = document.createElement("SELECT");
		var colfield = cell.getField();
		var getcolvalue = cell.getValue();
		console.log(getcolvalue);
		if(isHTML(getcolvalue) == true)getcolvalue=jQuery(getcolvalue).text();
    // editor.setAttribute("type", "date");

    //create and style input
    editor.style.padding = "3px";
    editor.style.width = "100%";
    editor.style.boxSizing = "border-box";
    
	
	var collecttarget = [];
		
	
			var data = {
				action: 'istarget_ready',
				meta_key: "rs_type",
				meta_value : "money"
			};

			jQuery.ajax({
				 type : "post",
				 url : objectsilo.myajaxurl,
				 data : data,
				 async: false,
				 success: function(response) {

					response = JSON.parse(response);
					
					var tabledata = JSON.parse(objectsilo.tabulator_data);
				console.log(response);
				   jQuery.each(response , function(key , value){
					  
						var data = tabledata.find(
											function(data) {
												  return data.id == value;
											}
											);
						collecttarget.push(data);
						 
					})
					
					
				 }
		  })

	console.log(collecttarget);

    //Set value of editor to the current value of the cell
    // editor.value = cell.getValue();

	opt = document.createElement ("option");


	if(getClass == "tabulator-cell"){
		editor.setAttribute("class", "typeclass");
		opt.appendChild ( document.createTextNode ("Remove Type"));
		  opt.setAttribute ("value", "");
		  opt.setAttribute ("text",  "Remove Type");
		  editor.appendChild(opt);
	}
	else{

		  opt.appendChild ( document.createTextNode ("All"));
		  opt.setAttribute ("value", "");
		  opt.setAttribute ("text",  "All");
		  editor.appendChild(opt);
	}

	

	 jQuery.each(editorParams.values , function(key , value){
		 
		 
		 
		 if(collecttarget.length > 0 && value == "money" && !isHeader && getcolvalue != "money"  )return;
		 
		 opt = document.createElement ("option");

		 opt.appendChild ( document.createTextNode (key));
		  opt.setAttribute ("value", value);
		  opt.setAttribute ("text",  key);
	
		  
		    console.log(value); 
		    console.log(getcolvalue); 
		  if( getcolvalue.toLowerCase() == value.toLowerCase())opt.setAttribute ("selected",  true);
		  editor.appendChild(opt); 


	})


    //set focus on the select box when the editor is selected (timeout allows for editor to be added to DOM)
    onRendered(function(){
        editor.focus();
        editor.style.css = "100%";
    });

    //when the value has been set, trigger the cell to update
  
    function successFunc(){
        // success({field : colfield , value : editor.value , colvalue : getcolvalue});
        success(editor.value );
    }
	
	
	
	/* if(colfield != 'il_topic') */editor.addEventListener("change", successFunc);
    
   /*  if(colfield != 'il_topic') */editor.addEventListener("blur", successFunc);
   

    //return the editor element
    return editor;


};


//custom max min filter function
function searchUpdateFilterFunction(headerValue, rowValue, rowData, filterParams){


	table_searchUpdate_index++;
	var rowCount = table_tabulator_count;
	
	
/* 	 console.log(headerValue);
	console.log(rowValue);
	console.log(rowData);
	console.log(filterParams);  */



	if(  rowCount <= table_searchUpdate_index && headerValue.length > 1){

		table_searchUpdate_index = 0;
		
		 
		 if(jQuery(".tabulator-table input[name='id']:checked").length > 0  ){

				 
				
		 
					

				 return true;

		}
	}


		if(headerValue == "" || jQuery(".tabulator-table input[name='id']:checked").length > 0 ) return true;
		
		
		
		
		var currowvalue = "";
		if(isHTML(rowData[filterParams['field']])){
			var currowvalue = jQuery(rowData[filterParams['field']]).text();
		}
		else{
			var currowvalue = rowData[filterParams['field']];
		}
		
		
		
		if (currowvalue.toLowerCase().includes( headerValue.toLowerCase() ) && filterParams['field'] == "il_topic")return true;
		
		if (currowvalue.toLowerCase().includes( headerValue.toLowerCase() ) && filterParams['field'] == "il_topic")return false;
	
		//if (!headerValue.includes( currowvalue.toLowerCase())  >= 0 && filterParams['field'] == "il_topic")return false;
		
		 return currowvalue.toLowerCase() == headerValue.toLowerCase();




}
jQuery(document).ready(function(){
	
	
	
			
		var tabledata = objectsilo.tabulator_data;
		tabledata = JSON.parse(tabledata);
		tabulatortable = new Tabulator("#reverse-silo", {
				data:tabledata,
				/*  index:"id", */
				  pagination: "local",
			  paginationSize: 20,
					layout:"fitColumns",
		fitColumns:true,
				columns:[
				 { 
					frozen:true,//frozen column group on left of table
					cssClass :"hide-header",//frozen column group on left of table
					columns:[
					    {title:'<input type="checkbox" class="triggerall" style="border-radius: 2px!important;height: 17px!important;min-height: 17px;margin: auto;display: block;margin-top: 16px;" >', field:"selectionid", align:"center", formatter:"html" ,headerSort:false , width:30 },
						 {title:"URLs", field:"url", width:200, headerFilter:"input" , formatter:"html" , hozAlign:"center" },
					]
				},

				{title:"ID", field:"id" ,  visible:false},

				
		 /*  , editorParams:{
						"":"Not Silo",
						"silo":"silo",
				} */
				{title:"Topic", field:"rs_topic",  width:80,  headerFilter:"input"  , editor:"input" , hozAlign:"center" 
				},
				{title:"#", field:"rs_number",  headerFilter:"input"  , editor:"input" , headerFilter:minMaxFilterEditor, headerFilterFunc:minMaxFilterFunction, headerFilterLiveFilter:false , hozAlign:"center" , width:50 },
				
				{title:"Type", field:"rs_type", width:80,  editor:filterOption , formatter:"html"  , headerFilterFunc:searchUpdateFilterFunction, editorParams:{values:{"Money":"money", "Support":"support" }}, headerFilter:true, headerFilterParams:{values:{"Money":"money", "Support":"support"} }, headerFilterFuncParams:{field:"rs_type"} , width:120  },
				
				{title:"Links Out Counter", width:80, field:"outl_counter", formatter:"html"  , headerFilter:"input" , hozAlign:"center"  },
				{title:"Links Outgoing",width:250,  field:"outl_outgoing", headerFilter:"input" , hozAlign:"center"  ,  formatter:"html"},
				{title:"Comment", field:"comment",  headerFilter:"input"  , hozAlign:"center" , formatter:"html"  },
				 
			  




		 

		  ],
			tableBuilt:function(){
				console.log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> table built');
			},
			 dataFiltered: function(filters, rows) {	  
			  
			},
			dataLoaded: function(data) {


				
			}
			,cellEdited:function(cell){
					console.log(cell);
					/* 	console.log(cell._cell.row.data.url);
					  console.log(cell._cell.value);
						console.log(cell._cell.column.definition.field); */

						var url =	cell._cell.row.data.url;
						var newval = cell._cell.value;
						var name = cell._cell.column.definition.field;


						// console.log(newval);
						// console.log(cell._cell.column.definition.field);

						if(isHTML(url) == true)url=jQuery(url).text();

						var data = {
							action: 'save_meta_silo',
							value: newval,
							name : name,
							url : url
						};


						var cellValue = cell.getValue();
						var id = cell.getRow().getIndex();
						var datarow = cell.getRow().getData();


						console.log(data);

					
					

						jQuery.post(objectsilo.myajaxurl, data, function(response) {



							console.log(response);
							//response = response.substring(0, response.length - 1);
							/* if(cell._cell.column.definition.field == "backlinks")tabulatortable.updateRow(id, {"total_links":(cellValue + intl_incoming)});
 */


						});
 
		  },
			dataFiltered:function(data, field, type, value){
				
			 
			
			}, 
			tableBuilt:function(){
				  
				 
			}
		});
		

		
		jQuery("button#refreshsilo").click(function(){

			var key = "rs_topic";
			var value = jQuery('div[tabulator-field=rs_topic] input[type="search"]').val();

				var data = {
							action: 'save_filter_silo',
							filter_key: key,
							filter_value : value
						};

						jQuery.ajax({
							 type : "post",
							 url : objectsilo.myajaxurl,
							 data : data,
							 success: function(response) {

							   location.reload();

							 }
					  })

		})
		
	if(objectsilo.filter_rs_topic != ""){
		tabulatortable.setFilter("rs_topic", "keywords", objectsilo.filter_rs_topic);	
		 jQuery('div[tabulator-field=rs_topic] input[type="search"]').val(objectsilo.filter_rs_topic);
	}	
	
	jQuery('div[tabulator-field=rs_topic] input[type="search"]').change(function(){
		var value = jQuery(this).val();
		console.log(value);
		tabulatortable.clearFilter();
	});
})




function isHTML(str) {
  var a = document.createElement('div');
  a.innerHTML = str;

  for (var c = a.childNodes, i = c.length; i--; ) {
    if (c[i].nodeType == 1) return true; 
  }

  return false;
}