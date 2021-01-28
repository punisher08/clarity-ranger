//custom max min header filter

var table_tabulator_count = 0;
var table_searchUpdate_index = 0;
var tabulatortable ;

jQuery.fn.extend({

    // get style attributes that were set on the first element of the jQuery object
    getStyle: function (prop) {
        var elem = this[0];
        var actuallySetStyles = {};
        for (var i = 0; i < elem.style.length; i++) {
            var key = elem.style[i];
            if (prop == key)
                 return (elem.style[key]).replace(/px/, "");;
				
                // return 60;
            actuallySetStyles[key] = elem.style[key];
        }
        if (!prop)
            // return 50;
            return actuallySetStyles;
    }
});

function isHTML(str) {
  var a = document.createElement('div');
  a.innerHTML = str;

  for (var c = a.childNodes, i = c.length; i--; ) {
    if (c[i].nodeType == 1) return true; 
  }

  return false;
}

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


	 // console.log(cell);

	  var getClass = jQuery(cell.getElement()).attr("class");
	  var editor = document.createElement("SELECT");
		var colfield = cell.getField();
		var getcolvalue = cell.getValue();
		
		if(isHTML(getcolvalue) == true)getcolvalue=jQuery(getcolvalue).text();
    // editor.setAttribute("type", "date");

    //create and style input
    editor.style.padding = "3px";
    editor.style.width = "100%";
    editor.style.boxSizing = "border-box";
    

    //Set value of editor to the current value of the cell
    // editor.value = cell.getValue();

	opt = document.createElement ("option");


	if(getClass == "tabulator-cell"){
		editor.setAttribute("class", "typeclass");
		opt.appendChild ( document.createTextNode ("Select Type"));
		  opt.setAttribute ("value", "");
		  opt.setAttribute ("text",  "Select Type");
		  editor.appendChild(opt);
	}
	else{

		  opt.appendChild ( document.createTextNode ("All"));
		  opt.setAttribute ("value", "");
		  opt.setAttribute ("text",  "All");
		  editor.appendChild(opt);
	}


	 jQuery.each(editorParams.values , function(key , value){
		 
		 
		 opt = document.createElement ("option");

		 opt.appendChild ( document.createTextNode (key));
		  opt.setAttribute ("value", value);
		  opt.setAttribute ("text",  key);
		 /*  console.log(getcolvalue.toLowerCase() == value.toLowerCase());
		  console.log(isHTML(getcolvalue));
		//  console.log(jQuery(getcolvalue).text());
		*/
		  
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
/* var tabledata = [
    {id:1, name:"Billy Bob", age:12, gender:"male", height:95, col:"red", dob:"14/05/2010"},
    {id:2, name:"Jenny Jane", age:42, gender:"female", height:142, col:"blue", dob:"30/07/1954"},
    {id:3, name:"Steve McAlistaire", age:35, gender:"male", height:176, col:"green", dob:"04/11/1982"},
];
console.log(tabledata); */
//Your Jquery Ajax. You can create as many as you want.
jQuery(document).ready(function(){


var getcountrowactive = 0;

/*Gather tabledata*/

var tabledata = jQuery('#clranger_tabular_data_text').val();
tabledata = JSON.parse(tabledata);
tabulatortable = new Tabulator("#internal-links", {
		data:tabledata,
		 index:"id",
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
                 {title:"URLs", field:"url", width:200, headerFilter:"input" , formatter:"html" , visible: myobject.setting_hide_show.url  },
            ]
        },

		{title:"ID", field:"id" ,  visible:false},

		
  
        {title:"Topic", field:"il_topic", editor:"input", headerFilter:"input" ,  headerFilterFunc:searchUpdateFilterFunction ,  headerFilterFuncParams:{field:"il_topic" , editor:"input"} , visible: myobject.setting_hide_show.il_topic  },
		 
      




 

        {title:"Page Type", field:"il_type", editor:filterOption , formatter:"html"  , headerFilterFunc:searchUpdateFilterFunction, editorParams:{values:{"money":"Money", "support":"Support" , "product":"Product"}}, headerFilter:true, headerFilterParams:{values:{"money":"Money", "support":"Support" , "product":"Product"} }, headerFilterFuncParams:{field:"il_type"} , width:120  , visible: myobject.setting_hide_show.il_type },







        {title:"Internal Links Anchor Text", field:"anchortext", hozAlign:"center", headerFilter:"text" , formatter:"html" , width:230 , visible: myobject.setting_hide_show.anchortext  },



 // ↙
	  

		{title:"Backlinks Anchor Text", field:"backlinks_anchortext", hozAlign:"center", headerFilter:"text" , formatter:"html" , width:230 , visible: myobject.setting_hide_show.backlinks_anchortext  },


  {title:"Int. Links", field:"intl_incoming", headerFilter:minMaxFilterEditor, headerFilterFunc:minMaxFilterFunction, headerFilterLiveFilter:false , value:"steve" , visible: myobject.setting_hide_show.intl_incoming  },

		{title:"Backlinks", field:"backlinks", hozAlign:"center", /* editor:"number", */  headerFilter:minMaxFilterEditor,  headerFilterFunc:minMaxFilterFunction, headerFilterLiveFilter:false , visible: myobject.setting_hide_show.backlinks },
		
		{title:"Total Links", field:"total_links", hozAlign:"center", headerFilter:minMaxFilterEditor,  headerFilterFunc:minMaxFilterFunction ,  headerFilterLiveFilter:false , formatter:"html" , visible: myobject.setting_hide_show.total_links },
		 // ↗
		{title:"Int. Links Out", field:"intl_outgoing", hozAlign:"center", headerFilter:minMaxFilterEditor,  headerFilterFunc:minMaxFilterFunction, headerFilterLiveFilter:false , visible: myobject.setting_hide_show.intl_outgoing },
		
		{title:"Ext. Links Out", field:"extl_links", hozAlign:"center",  headerFilter:minMaxFilterEditor, headerFilterFunc:minMaxFilterFunction,  headerFilterLiveFilter:false , visible: myobject.setting_hide_show.extl_links },
		
		{title:"Word Count", field:"word_count" , hozAlign:"center", headerFilter:minMaxFilterEditor,  headerFilterFunc:minMaxFilterFunction, headerFilterLiveFilter:false  , visible: myobject.setting_hide_show.word_count  },

  ],
	tableBuilt:function(){
		console.log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> table built');
	},
	 dataFiltered: function(filters, rows) {	  
	  jQuery('.tabulator-header-filter').each(function(){
          if(jQuery(this).find('input').val()!=''){
              jQuery(this).find('input').addClass('activeFilter');
          }else {
			  jQuery(this).find('input').removeClass('activeFilter');
		  }
      });
	  table_tabulator_count = rows.length;
    },
    dataLoaded: function(data) {


		 table_tabulator_count = data.length;
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
		action: 'internallinks_ajax',
		value: newval,
		name : name,
		url : url
	};


	var cellValue = cell.getValue();
	var id = cell.getRow().getIndex();
	var datarow = cell.getRow().getData();


	var intl_incoming = datarow['intl_incoming'];


	if(name == 'il_type' && newval != "" ){
		 var charfirst = newval.charAt(0);
		 var classtype = "whitetype";
		 
		 if(charfirst == "M" || charfirst == "m"){
			  classtype = "greentype";
		 }
		 if(charfirst == "S" || charfirst == "s"){
			  classtype = "orangetype";
		 }
		  if(charfirst == "P" || charfirst == "p"){
			  classtype = "navybluetype";
		 }
		tabulatortable.updateRow(id, {"il_type": "<span class='page-type "+classtype+"'>"+newval+"</span>"})
	}

  // console.log(data);

	jQuery.post(myobject.myajaxurl, data, function(response) {


		//response = response.substring(0, response.length - 1);
		if(cell._cell.column.definition.field == "backlinks")tabulatortable.updateRow(id, {"total_links":(cellValue + intl_incoming)});



	});

  },
	dataFiltered:function(data, field, type, value){
		jQuery('.tabulator-header-filter').each(function(){
          if(jQuery(this).find('input').val()!=''){
              jQuery(this).find('input').addClass('activeFilter');
          }else {
			  jQuery(this).find('input').removeClass('activeFilter');
		  }
        });
		//data - the subset of the total table data that has passed the filter and is now visible
		//field - the field being filtered
		//type - the type of filter being used
		//value - the value of the filter

		//set text in info element to show the number of rows and filters currently applied
	/* 	$("#example-table-info").text("rows:" + data.length + " of " + $("#example-table").tabulator("getData").length + ", filter:" + field  + type  + value); */
	
	console.log(data);
	console.log(data.length );
	console.log(field);
	console.log(type);
	console.log(value);
	 
	var getPageSize = this.getPageSize(); 
	var curretpage = jQuery('button.tabulator-page.active').text();
    getcountrowactive = field.length ;
	 
	jQuery('.tabulator-footer').find('.pagination-counter').remove();
	jQuery('.tabulator-footer').prepend("<div class='pagination-counter'> 1 - "+getPageSize +" of "+field.length +" items </div>")
	
	jQuery('.tabulator-footer').find('.pagination-limit').remove();
	jQuery('.tabulator-footer').append("<div class='pagination-limit'> <select ><option value='20' >20 / page</option><option value='50'>50 / page</option><option value='100'>100 / page</option></select> </div>")
	 
	
	 jQuery('button.tabulator-page.active').click(); 
	 
	
	}, 
	tableBuilt:function(){
		  
		 
    }
});
 
jQuery('body').on("change , keypress" , '.tabulator-header-filter input , .tabulator-header-filter select' , function(event){
	 jQuery('button.tabulator-page.active').click(); 
 })
jQuery('#clearfilters').click(function(){
    tabulatortable.clearFilter();
    jQuery(".tabulator-header-filter input").val("");
    tabulatortable.setHeaderFilterValue("il_topic", "");
    tabulatortable.setHeaderFilterValue("il_type", "");
    tabulatortable.setHeaderFilterValue("url", "");
    tabulatortable.setHeaderFilterValue("intl_incoming", "");
    tabulatortable.setHeaderFilterValue("backlinks", "");
    tabulatortable.setHeaderFilterValue("intl_outgoing", "");
    tabulatortable.setHeaderFilterValue("extl_links", "");
    tabulatortable.setHeaderFilterValue("word_count", "");
})
 
// tabulatortable.setPageSize(4);
jQuery('body').on("change" , '.pagination-limit select' , function(event){
	 var value = jQuery(this).val();
	 console.log(value);
	 tabulatortable.setPageSize(value); 
	  jQuery('button.tabulator-page.active').click();
 })
 jQuery('body').bind("click" , '.tabulator-pages button' , function(event){
			var getPageSize = tabulatortable.getPageSize();
			var curretpage = jQuery(".tabulator-page.active").attr("data-page");
			var lastrowcount = (parseInt(curretpage) * parseInt(getPageSize) ) > getcountrowactive ? getcountrowactive : (parseInt(curretpage) * parseInt(getPageSize) );
			var currentresultlist = jQuery('.tabulator-row').length;
			var start =  ((lastrowcount - currentresultlist) + 1);
			
			
			console.log(curretpage);
			
			 jQuery('.tabulator-footer').find('.pagination-counter').remove();
			jQuery('.tabulator-footer').prepend("<div class='pagination-counter'> "+ start  +" - "+ lastrowcount +" of "+ getcountrowactive +" items </div>") 
			
		 }); 


/* jQuery('div[tabulator-field="il_type"] input[type="search"]').on("change" , function(event){
    var value = jQuery(this).val(); 
    console.log(event.data.value)

	if(value != "All")
	Swal.fire({
	  title: 'Do you want to save the changes?',
	  showDenyButton: true,
	  showCancelButton: true,
	  confirmButtonText: "Save",
	  denyButtonText: "Don't save",
	}).then((result) => {

	  if (result.isConfirmed) {
		Swal.fire('Saved!', '', 'success')
	  } else if (result.isDenied) {
		Swal.fire('Changes are not saved', '', 'info')
	  }
	})
})

 */
//custom max min filter function
function searchUpdateFilterFunction(headerValue, rowValue, rowData, filterParams){


	table_searchUpdate_index++;
	var rowCount = table_tabulator_count;
	
	
/* 	 console.log(headerValue);
	console.log(rowValue);
	console.log(rowData);
	console.log(filterParams);  */



	if(  rowCount <= table_searchUpdate_index && headerValue.length > 1){
	//	console.log(headerValue.length);
		table_searchUpdate_index = 0;
		// jQuery('div[tabulator-field="il_type"] input[type="search"]').change({value : rowValue});
		
		 
		 if(jQuery(".tabulator-table input[name='id']:checked").length > 0  ){

				console.log(jQuery(".tabulator-col[tabulator-field='il_topic'] input").hasClass("filterFocused"));
				 
				if(filterParams['field'] == "il_topic" && jQuery(".tabulator-col[tabulator-field='il_topic'] input").hasClass("filterFocused")) return true;
		 

			// console.log(rowValue);

				  Swal.fire({
				  title: 'Do you want to save the changes?',
				  showDenyButton: true,
				  showCancelButton: true,
				  confirmButtonText: "Save",
				  denyButtonText: "Don't save",
				}).then((result) => {

				  if (result.isConfirmed) {


				
					  var checked = jQuery(".tabulator-table input[name='id']:checked").serializeArray();
					  var getfield = filterParams['field'] ;
						var data = {action: "bulk_update_type" , item : checked , input : headerValue , field : getfield };



						var tabulardata = { key : headerValue} ;

						tabulardata[getfield] = tabulardata.key;
						delete  tabulardata['key'];


 

						  jQuery.ajax({
									 type : "post",
									 dataType : "json",
									 url : myobject.myajaxurl,
									 data : data,
									 success: function(response) {

										if(response.success == true){
										 jQuery(".tabulator-table input[name='id']:checked").each(function(){
												var getid = jQuery(this).val();
												//console.log(id)
												
												 
												 var charfirst = tabulardata[getfield].charAt(0);
												 var classtype = "whitetype";
												 
												 if(charfirst == "M" || charfirst == "m"){
													  classtype = "greentype";
												 }
												 if(charfirst == "S" || charfirst == "s"){
													  classtype = "orangetype";
												 }
												 if(charfirst == "P" || charfirst == "p"){
													  classtype = "navybluetype";
												 }
												console.log(tabulardata);
												
												
												if(filterParams['field'] == "il_type"){
												
													tabulardata[getfield] = "<span class='page-type "+classtype+"'>"+tabulardata[getfield]+"</span>";
												}
												
												tabulatortable.updateRow(  getid ,tabulardata );
											}).promise().done(function () {
												// console.log('Did things to every .element, all done.');
												Swal.fire('Saved!', '', 'success');
												jQuery(".tabulator-table input[name='id']:checked").prop('checked', false);
												jQuery(".tabulator input.triggerall").prop('checked', false);
												jQuery('.tabulator-row').removeClass("seleced-row");
												if(filterParams['editor'] != undefined)jQuery("div[tabulator-field='il_topic'] input[type='search']").val('');


											});
										}else{
											Swal.fire('Changes are not saved', '', 'info')
										}
									 }
								  })




				  } else if (result.isDenied) {
						Swal.fire('Changes are not saved', '', 'info')
				  }
				}) 


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
		console.log(currowvalue.toLowerCase());
		console.log(headerValue.toLowerCase());
		console.log(headerValue.toLowerCase().includes( currowvalue.toLowerCase()));
		console.log(filterParams['field']);
		
		
		if (currowvalue.toLowerCase().includes( headerValue.toLowerCase() ) && filterParams['field'] == "il_topic")return true;
		
		if (currowvalue.toLowerCase().includes( headerValue.toLowerCase() ) && filterParams['field'] == "il_topic")return false;
	
		//if (!headerValue.includes( currowvalue.toLowerCase())  >= 0 && filterParams['field'] == "il_topic")return false;
		
		 return currowvalue.toLowerCase() == headerValue.toLowerCase();




}

jQuery(".tabulator-cell input[name='id']").change(function () {
	 jQuery(this).parents('.tabulator-row').toggleClass("seleced-row");

});
jQuery(".tabulator input.triggerall").change(function () {
    jQuery(".tabulator-table input[name='id']").prop('checked', this.checked);


	if(jQuery(this).is(':checked')){
			jQuery('.tabulator-row').addClass("seleced-row");



	}
	else{
		jQuery('.tabulator-row').removeClass("seleced-row");
	}


});


var original_height = 0;
jQuery("body").on("change" , 'input#show-others' ,function(){
 // jQuery(this).parents('.limit-items').find(".items").toggleClass("show-three-items"); // show 3 items
		jQuery(this).parents('.tabulator-cell').find("table.int-list-from").addClass("hide-anchor-list-from");
		
		jQuery(this).parents('.tabulator-cell').find("table.backlinks-table").addClass("hide-backlinks-table");
		
		jQuery(this).parents('.tabulator-cell').find(".open-anchor-other-details").attr( "class" , "fas fa-plus open-anchor-other-details");
	
        jQuery(this).parents('.limit-items').find(".items").toggleClass("show-all-items");
		

        //jQuery(this).parents('.tabulator-cell').toggleClass("autoheight");
		jQuery(this).closest('.tabulator-row').find('.tabulator-cell').toggleClass("autoheight");

        jQuery(this).parents('.tabulator-row').toggleClass("flextabulator");
		jQuery(this).parents('.limit-items').find("input#show-all").toggle();

		
	var thisinnerHeight = jQuery(this).parents('.tabulator-cell').innerHeight( );
	
	original_height = thisinnerHeight;

console.log(thisinnerHeight);


	if(jQuery(this).parents('.limit-items').find(".items").hasClass("show-all-items" ) ){
		 jQuery(this).parents('.tabulator-cell').addClass("autoheight");
	}
	
	
	jQuery(this).parents('.tabulator-row').find(".tabulator-cell:visible").not(".autoheight").not("div[tabulator-field='id']").animate({height: thisinnerHeight + 'px'} ,  { duration: 50, queue: false } );;
	
	jQuery(this).parents('.tabulator-row').find(".tabulator-cell:hidden").not(".autoheight").not("div[tabulator-field='id']").animate({height: thisinnerHeight + 'px'} ,  { duration: 50, queue: false }).hide();;
		

		/* if(jQuery(this).parents('.limit-items').find("input#show-all").is(':checked')){
			jQuery(this).parents('.limit-items').find(".items").toggleClass("show-all-items");

		} */

	/* jQuery(this).parents('.limit-items').find(".show-icon").toggleClass("show-all-icon");
			jQuery(this).parents('.limit-items').find(".show-icon").toggleClass("hide-all-icon"); */

		if(jQuery(this).is(':checked')){
			jQuery(this).toggleClass("fa-angle-down");
			jQuery(this).toggleClass("fa-angle-up");



		}
		else{
			jQuery(this).toggleClass("fa-angle-down");
			jQuery(this).toggleClass("fa-angle-up");
		}
		  // jQuery(this).parents('.limit-items').find(".items").toggleClass("show-all-items");

})

jQuery("body").on("click" ,".backlink-anchor-text-class > span > .open-anchor-other-details" , function(){	
	var thisElement =  jQuery(this);
	var data_anchor_id = jQuery(this).parents('.backlink-anchor-text-class').attr('data-backlink-id');
	
	
	if(  !jQuery(this).parents('.backlink-anchor-text-class').find("table.backlinks-table").hasClass("hide-backlinks-table")){
		
		jQuery(this).attr("class" , "fas fa-plus open-anchor-other-details");
		//jQuery(this).parents('.tabulator-cell').removeClass("autoheight");
		jQuery(this).closest('.tabulator-row').find('.tabulator-cell').removeClass("autoheight");	
		jQuery(this).closest('.tabulator-row').removeClass("flextabulator");
		jQuery(this).parents('li.backlink-anchor-text-class').find("table.backlinks-table").toggleClass("hide-backlinks-table");

		
		if(!jQuery(this).parents('.tabulator-cell').hasClass( "autoheight" )){
			//jQuery(this).parents('.tabulator-cell').toggleClass("autoheight");
		}


		if(jQuery(this).parents('.tabulator-cell').find('.items').hasClass( "show-all-items" ) ){
			
			
			
			 var thisinnerHeight = original_height;
			 /* console.log(jQuery(this).parents('.tabulator-cell').find('.items').hasClass( "show-all-items" ) );
			 console.log(original_height); */
		}
		else{
			var thisinnerHeight = jQuery(this).parents('.tabulator-cell').innerHeight( );
		}
		
//
		jQuery(this).parents('.tabulator-row').find(".tabulator-cell:visible").not(".autoheight").not("div[tabulator-field='id']").animate({height: thisinnerHeight + 'px'} , { duration: 50, queue: false });; 
		
		jQuery(this).parents('.tabulator-row').find(".tabulator-cell:hidden").not(".autoheight").not("div[tabulator-field='id']").animate({height: thisinnerHeight + 'px'} , { duration: 50, queue: false }).hide();;
		
		
		return ;
		
	}
	else{
	
				var data = {
						action: 'backlink_details',
						id: data_anchor_id
					};
			
			
				jQuery.ajax({
					 type : "post",
					 dataType : "json",
					 url : myobject.myajaxurl,
					 data : data,
					  beforeSend: function(){
							
							thisElement.attr("class" , "fas fa-dot-circle open-anchor-other-details");
						},
					 success: function(response) {
						 
						 thisElement.attr("class" , "fas fa-minus open-anchor-other-details");
						 
						var thisElemetnTable =  thisElement.parents('.backlink-anchor-text-class').find('table.backlinks-table tbody').html(response.html);
						 
						 
						 
						 
						
					
					var thisinnerHeight = thisElement.parents('.tabulator-cell').innerHeight( );
					
					
					//thisElement.parents('.tabulator-cell').removeClass("autoheight");
					thisElement.closest('.tabulator-row').find('.tabulator-cell').removeClass("autoheight");
					thisElement.parents('li.backlink-anchor-text-class').find("table.backlinks-table").toggleClass("hide-backlinks-table");

					 var thisIntListHeight = jQuery(thisElemetnTable).parents('li.backlink-anchor-text-class').find("table.backlinks-table").innerHeight( );

					
						
					if(!thisElement.parents('.tabulator-cell').hasClass( "autoheight" )){
						//thisElement.parents('.tabulator-cell').toggleClass("autoheight");
						thisElement.closest('.tabulator-row').find('.tabulator-cell').toggleClass("autoheight");
						thisElement.closest('.tabulator-row').toggleClass("flextabulator");
					}
					
					
					if(thisElement.parents('li.backlink-anchor-text-class').find("table.backlinks-table").hasClass( "hide-backlinks-table" )){
						
						thisinnerHeight = (thisinnerHeight - thisIntListHeight);
						console.log("-");
					}
					
					if(!thisElement.parents('li.backlink-anchor-text-class').find("table.backlinks-table").hasClass( "hide-backlinks-table" ) && !thisElement.parents('.tabulator-cell').find(".items").hasClass( "show-all-items" ) ){
						
						thisinnerHeight = (thisinnerHeight + thisIntListHeight);
						console.log("+");
					}
					
					if(!thisElement.parents('li.backlink-anchor-text-class').find("table.backlinks-table").hasClass( "hide-backlinks-table" ) && thisElement.parents('.tabulator-cell').find(".items").hasClass( "show-all-items" ) ){
						console.log(thisIntListHeight);
						thisinnerHeight = (thisinnerHeight + thisIntListHeight);
						console.log("anoterh +");
					}
					
					
					thisElement.parents('.tabulator-row').find(".tabulator-cell:hidden").not(".autoheight").not("div[tabulator-field='id']").animate({height: thisinnerHeight + 'px'} , { duration: 50, queue: false }).hide();;

					thisElement.parents('.tabulator-row').find(".tabulator-cell:visible").not(".autoheight").not("div[tabulator-field='id']").animate({height: thisinnerHeight + 'px'} ,  { duration: 50, queue: false });;
						 
						 
					 }
				})
	}
})
jQuery("body").on("click" ,"li.anchor-text-class > span > .open-anchor-other-details" , function(){
   var thisElement =  jQuery(this);
   
   	var data_anchor_url =  jQuery(this).parents('.anchor-text-class').attr('data-anchor-url');
	var data_anchor_text =  jQuery(this).parents('.anchor-text-class').attr('data-anchor-text');
	var data = {
				action: 'from_url_details_anchor',
				url: data_anchor_url,
				title: data_anchor_text
			};
	
	
	if(  !jQuery(this).parents('.anchor-text-class').find("table.int-list-from").hasClass("hide-anchor-list-from")){
		
		
			
			
			
			jQuery(this).attr("class" , "fas fa-plus open-anchor-other-details");			
			//jQuery(this).parents('.tabulator-cell').removeClass("autoheight");
			jQuery(this).closest('.tabulator-row').find('.tabulator-cell').removeClass("autoheight");	
			jQuery(this).closest('.tabulator-row').removeClass("flextabulator");
		
			 jQuery(this).parents('li.anchor-text-class').find("table.int-list-from").toggleClass("hide-anchor-list-from");


			if(!jQuery(this).parents('.tabulator-cell').hasClass( "autoheight" )){
				//jQuery(this).parents('.tabulator-cell').toggleClass("autoheight");
			}

	
			if(jQuery(this).parents('.tabulator-cell').find('.items').hasClass( "show-all-items" ) ){
				
				
				
				 var thisinnerHeight = original_height;
				 /* console.log(jQuery(this).parents('.tabulator-cell').find('.items').hasClass( "show-all-items" ) );
				 console.log(original_height); */
			}
			else{
				var thisinnerHeight = jQuery(this).parents('.tabulator-cell').innerHeight( );
			}
			
//
			
				
		
			
			
			
			
			
			
			
			jQuery(this).parents('.tabulator-row').find(".tabulator-cell:visible").not(".autoheight").not("div[tabulator-field='id']").animate({height: thisinnerHeight + 'px'} , { duration: 50, queue: false });; 
			
			jQuery(this).parents('.tabulator-row').find(".tabulator-cell:hidden").not(".autoheight").not("div[tabulator-field='id']").animate({height: thisinnerHeight + 'px'} , { duration: 50, queue: false }).hide();;
			
			
			
			
			
			return ;
		
	}
	
    jQuery.ajax({
		 type : "post",
		 dataType : "json",
		 url : myobject.myajaxurl,
		 data : data,
		  beforeSend: function(){
				
				thisElement.attr("class" , "fas fa-dot-circle open-anchor-other-details");
			},
		 success: function(response) {
			
			
			console.log(response.html);
			// thisElement.parents('.anchor-text-class').find("table.int-list-from body").html(response.html);
			thisElement.attr("class" , "fas fa-minus open-anchor-other-details");
			
			var thisElemetnTable = thisElement.parents('.anchor-text-class').find("table.int-list-from tbody").html(response.html);
			
			var thisinnerHeight = thisElement.parents('.tabulator-cell').innerHeight( );
				//thisElement.parents('.tabulator-cell').removeClass("autoheight");
			 	thisElement.closest('.tabulator-row').find('.tabulator-cell').removeClass("autoheight");
			 	thisElement.closest('.tabulator-row').removeClass("flextabulator");
				thisElement.parents('li.anchor-text-class').find("table.int-list-from").toggleClass("hide-anchor-list-from");
			 			

			 var thisIntListHeight = jQuery(thisElemetnTable).parents('li.anchor-text-class').find("table.int-list-from").innerHeight( );

			
				
			if(!thisElement.parents('.tabulator-cell').hasClass( "autoheight" )){
				//thisElement.parents('.tabulator-cell').toggleClass("autoheight");
				thisElement.closest('.tabulator-row').find('.tabulator-cell').toggleClass("autoheight");
				thisElement.closest('.tabulator-row').toggleClass("flextabulator");
			}
			
			
			if(thisElement.parents('li.anchor-text-class').find("table.int-list-from").hasClass( "hide-anchor-list-from" )){
				
				thisinnerHeight = (thisinnerHeight - thisIntListHeight);
				console.log("-");
			}
			
			if(!thisElement.parents('li.anchor-text-class').find("table.int-list-from").hasClass( "hide-anchor-list-from" ) && !thisElement.parents('.tabulator-cell').find(".items").hasClass( "show-all-items" ) ){
				
				thisinnerHeight = (thisinnerHeight + thisIntListHeight);
				console.log("+");
			}
			
			if(!thisElement.parents('li.anchor-text-class').find("table.int-list-from").hasClass( "hide-anchor-list-from" ) && thisElement.parents('.tabulator-cell').find(".items").hasClass( "show-all-items" ) ){
				console.log(thisIntListHeight);
				thisinnerHeight = (thisinnerHeight + thisIntListHeight);
				console.log("anoterh +");
			}
			
			
			thisElement.parents('.tabulator-row').find(".tabulator-cell:hidden").not(".autoheight").not("div[tabulator-field='id']").animate({height: thisinnerHeight + 'px'} , { duration: 50, queue: false }).hide();;

			thisElement.parents('.tabulator-row').find(".tabulator-cell:visible").not(".autoheight").not("div[tabulator-field='id']").animate({height: thisinnerHeight + 'px'} ,  { duration: 50, queue: false });;
			
			

		/* 
			console.log(jQuery(this).parents('.tabulator-cell').outerHeight( true ));
			console.log(jQuery(this).parents('.tabulator-cell').innerHeight( )); */
			
			
		


		   
			
		 }
	  })
	
})
/* jQuery('input#show-all').change(function(){
        jQuery(this).parents('.limit-items').find(".items").toggleClass("show-all-items");
        jQuery(this).toggleClass("checkedshow");





}) */
 
 jQuery('.tabulator-col.tabulator-sortable[tabulator-field="il_topic"] input').focus(function() {
	console.log( "Handler for .focus() called." );
	jQuery(this).addClass("filterFocused");
});
jQuery('.tabulator-col.tabulator-sortable[tabulator-field="il_topic"] input').focusout(function() {
	
	jQuery(this).removeClass("filterFocused");
});
	
	
jQuery('.tabulator-col.tabulator-sortable[tabulator-field="il_topic"] input').on('keypress',function(e) {
	console.log( "Handler for .unfocus() called." );
	console.log( e.which );
	if(e.which !== 13)return;
	
	
			
						var headerValue = jQuery(this).val();
					   if(jQuery(".tabulator-table input[name='id']:checked").length > 0  && headerValue != ""){

							console.log(jQuery(".tabulator-col[tabulator-field='il_topic'] input").hasClass("filterFocused"));
							 
							// if(filterParams['field'] == "il_topic" && jQuery(".tabulator-col[tabulator-field='il_topic'] input").hasClass("filterFocused")) return true;
					 

						// console.log(rowValue);

							  Swal.fire({
							  title: 'Do you want to save the changes?',
							  showDenyButton: true,
							  showCancelButton: true,
							  confirmButtonText: "Save",
							  denyButtonText: "Don't save",
							}).then((result) => {
								
									 var checked = jQuery(".tabulator-table input[name='id']:checked").serializeArray();
									 
									 var data = {action: "bulk_update_type" , item : checked , input : headerValue , field : 'il_topic' }; 
									
								
									
									
								 	var tabulardata = {};

									tabulardata['il_topic'] = headerValue;
								
									
									
									
									 
									 if (result.isConfirmed) {
										 
										 
										   jQuery.ajax({
												 type : "post",
												 dataType : "json",
												 url : myobject.myajaxurl,
												 data : data,
												 success: function(response) {

													if(response.success == true){
													 jQuery(".tabulator-table input[name='id']:checked").each(function(){
															var getid = jQuery(this).val();
															//console.log(id)
															
															
															tabulatortable.updateRow(  getid ,tabulardata );
															
														}).promise().done(function () {
															// console.log('Did things to every .element, all done.');
															Swal.fire('Saved!', '', 'success');
															jQuery(".tabulator-table input[name='id']:checked").prop('checked', false);
															jQuery(".tabulator input.triggerall").prop('checked', false);
															jQuery('.tabulator-row').removeClass("seleced-row");
															jQuery("div[tabulator-field='il_topic'] input[type='search']").val('');


														});
													}else{
														Swal.fire('Changes are not saved', '', 'info')
													}
												 }
											  })
										 
										 
									 }
									 else if (result.isDenied) {
											Swal.fire('Changes are not saved', '', 'info')
									  }
							 
							}) 


							 return true;

					}
}); 
jQuery("#Uploadbacklink").click(function(){
	
	
		Swal.fire({
		  title: '<strong>Import Backlink</strong>',
		  html:
			'<form id="backlinkupdateform" action="'+ myobject.import_backlinks_action + '" style="color: white;display: contents;" method="POST" enctype="multipart/form-data"><input type="file" accept=".csv" aria-label="Upload your profile picture" class="swal2-file" placeholder="" style="display: flex;" name="file" id="file" accept=".csv"> <input type="hidden" value="'+ myobject.current_url  +'"  name="redirect"></form> ',
		 showDenyButton: false,
		  showCancelButton: true,
		  confirmButtonText: 'Update',
		  denyButtonText: 'Don\'t save',
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {
			jQuery("#backlinkupdateform").submit();
		  } else if (result.isDenied) {
			Swal.fire('Changes are not saved', '', 'info')
		  }
		})
})
jQuery(".setting-hide-show").click(function(){
    jQuery("#setting-tabulator").toggleClass("hide-setting-tabulator");
	
})

jQuery(".ant-checkbox-column input.ant-checkbox-input").change(function(){
	
	var field = jQuery(this).val();
	var value = true;
	
    if (!jQuery(this).is(':checked')) {
       tabulatortable.hideColumn(field);
	     value = false;
    }
	else{
		tabulatortable.showColumn(field);
		var value = true;
	}
	
	var field = jQuery(this).val();
	
	 var data = {action: "clarity_setting_hide_show" , key : field , meta_value : value }; 
	
	  jQuery.ajax({
		 type : "post",
		 dataType : "json",
		 url : myobject.myajaxurl,
		 data : data,
		 success: function(response) {

				console.log(response);
		 }
	  })
})
jQuery(".ant-checkbox-url-title input.ant-checkbox-input").change(function(){
	
	
		var value = true;
		
		
		var thisinnerHeight = jQuery('.url-title-inside').parents('.tabulator-cell').innerHeight( );
		
		
		if (!jQuery(this).is(':checked')) {
		  jQuery('.url-title-inside').hide();
		  value = false;
		  console.log(thisinnerHeight);
		  jQuery(".tabulator-cell:visible").not("div[tabulator-field='id']").animate({height: 20 + 'px'} ,  { duration: 50, queue: false });
		  if(thisinnerHeight > 58){ 			 
			  	
		  }
		} 
		else{
			jQuery('.url-title-inside').show();
			value = true;
			jQuery(".tabulator-cell:visible").not("div[tabulator-field='id']").animate({height: (thisinnerHeight + 20) + 'px'} ,  { duration: 50, queue: false });
		}
	
	var field = jQuery(this).val();
	
	 var data = {action: "clarity_setting_hide_show" , key : field , meta_value : value }; 
	
	  jQuery.ajax({
		 type : "post",
		 dataType : "json",
		 url : myobject.myajaxurl,
		 data : data,
		 success: function(response) {

				console.log(response);
		 }
	  })

	
})

jQuery('#wpwrap').addClass( jQuery('#clranger_theme_color').val() );
	jQuery("#internal-links .tabulator-table input[name=keyword] , #internal-links .tabulator-table input[name=type], #internal-links .tabulator-table input[name=backlink_count]").on("change" , function(){
			var data = {
				action: 'internallinks_ajax',
				value: jQuery(this).val(),
				name : jQuery(this).attr("name"),
				id : jQuery(this).attr('data-page-id')
			};

			jQuery.post(myobject.ajaxurl, data, function(response) {
			response = response.substring(0, response.length - 1);

			  // console.log(response);

			});

	})


});

jQuery('.setting-row-size').click(function(){
	if(!jQuery('.tabulator-row').hasClass('height1') && !jQuery('.tabulator-row').hasClass('height2')){
		jQuery('.tabulator-row').addClass('height1');
	}else if(jQuery('.tabulator-row').hasClass('height1')){
		jQuery('.tabulator-row').removeClass('height1');
		jQuery('.tabulator-row').addClass('height2');
	}else if(jQuery('.tabulator-row').hasClass('height2')){
		jQuery('.tabulator-row').removeClass('height2');
	}
	return false;
});
/* 
function greet(){
	console.log("fasdf"); */

/* }
setTimeout(greet, 10000); */