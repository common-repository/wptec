(function ($) {
	'use strict';
	$(document).ready(function(){
		// -------------------------------------------------------------------------------------------------------------------------
		//		 Updating the data from Frontend tables via AJAX
		// -------------------------------------------------------------------------------------------------------------------------
		$("td").on("dblclick", function(e){
			//  getting the value
			var inputData = $(this).find("span.wptecInput").data();
			//  Checking field is editable or not 
			if( typeof  inputData != 'undefined' && inputData['editable'] !== false){
				// Hiding Display span
				$(this).find("span.wptecDisplay").hide();
				// Show input span
				$(this).find("span.wptecInput").show();
			} else {
				// pronging the information on console.
				console.log("INFO: this field is not editable.");
			}
		});

		// -------------------------------------------------------------------------------------------------------------------------
		//		 Frontend update Ok button action. get the value and run AJAX function.
		// -------------------------------------------------------------------------------------------------------------------------
		$("span.wptecInput > button.yes").on("click", function(e){
			// console.log("clicked");
			// Getting display span data first 
			var inputData = $(this).parent("span.wptecInput").data();
			// Check displayData is defined 
			if(typeof inputData != 'undefined' && inputData['editable'] !== false){
				// Hiding input span
				$(this).parent(".wptecInput").hide();
				// Getting screen ID for AJAX request method
				if (wptec.wptecCurrentScreen == "users"){
					var action = "wptec_userAJAX";
				} else if (wptec.wptecCurrentScreen == "edit-post"){
					var action = "wptec_postAJAX";
				} else if (wptec.wptecCurrentScreen == "edit-page"){
					var action = "wptec_pageAJAX";
				} else if (wptec.wptecCurrentScreen == "edit-comments"){
					var action = "wptec_commentAJAX";
				} else if (wptec.wptecCurrentScreen == "upload"){
					var action = "wptec_mediaAJAX";
				} else if (wptec.wptecCurrentScreen == "edit-shop_order"){
					var action = "wptec_orderAJAX";
				} else if (wptec.wptecCurrentScreen == "edit-product"){
					var action = "wptec_productAJAX";
				} else {
					var action = "";
					console.log("ERROR: AJAX action is not defined or empty.");
					return;  // If there is no  AJAX action then return;
				}
				//  input value Placeholder 
				var  inputValue = "";
				//  Get the input value
				if(inputData['type'] == 'number'){
					var  inputValue = $("#" + inputData['id']  + inputData['name']).val();           	  // getting number value
				} else if (inputData['type'] == 'select'){
					var  inputValue = $("#" + inputData['id']  + inputData['name'] + ":selected").val();  // getting dropdown select value
				} else if (inputData['type'] == 'radio'){
					var  inputValue = $("#" + inputData['id']  + inputData['name'] + ":checked").val();   // getting radio button select value 
				}  else {
					var  inputValue =  $("#" + inputData['id'] + inputData['name']).val();				  // getting text input field settings value 
				}
				//  if there is a value and not empty 
				if(inputValue){
					console.log("INFO: Field input value is empty. Maybe you are Zeroing the value.");
				}
				// Creating AJAX request 
				var wptecRequestData = {
					"id":			 inputData['id'],			// user id, comment id, media id, post id, page id, product id, order id
					"action":    	 action,					// AJAX action 
					"dataKey":   	 inputData['name'],   		// column name
					"dataValue": 	 inputValue,  				// input update value
					"currentScreen": wptec.wptecCurrentScreen,	// current screen 
					"security": 	 wptec.wptecSecurity,
				};
				console.log(wptecRequestData);
				//  Now initiate a AJAX request;
				$.post(wptec.wptecAjaxURL, wptecRequestData, function(responseX){
					console.log(responseX)
					// var output = JSON.parse(responseX);
				});
				// inserted new value to the Display
				$(this).parent().siblings(".wptecDisplay").text(inputValue);
				// Showing content span
				$(this).parent().siblings(".wptecDisplay").show();
				
			}
		});

		// -------------------------------------------------------------------------------------------------------------------------
		//		 Don't do anything || cancels button action || just hiding the panel 
		// -------------------------------------------------------------------------------------------------------------------------
		$("span.wptecInput > button.no").on("click", function(e){
			// Hiding input span
			$(this).parent(".wptecInput").hide();
			// Showing content span
			$(this).parent().siblings(".wptecDisplay").show();
		});

		// -------------------------------------------------------------------------------------------------------------------------
		//		  Downloading the CSV File  via AJAX
		// -------------------------------------------------------------------------------------------------------------------------
		$(".wptecDownload").click(function(){
			console.log(".wptecDownload button clicked !");
			// Displaying message 
			$("#wptecDownloadMessage").show();
			// return;
			// Counter
			var i = 0;
			//  init the function
			var intervalId = window.setInterval(function(){
				// Tab array for show and hide   *** this should be done 
				// var tabArray = ["#userTab","#postTab","#pageTab","#commentTab","#mediaTab","#productTab","#orderTab"];
				// Getting Action table it will convert to AJAX request Method Name;
				var downloadAction = $(".wptecDownload").data("action");

				console.log(downloadAction);
				// AJAX data 
				var csvRequestData = {
										"action":	     downloadAction,
										"security": 	 wptec.wptecCurrentScreen,
										"currentScreen": "null",
									 };
				// Init Request 
				$.post(wptec.wptecAjaxURL, csvRequestData, function(rx){
					//  JSON.parse error handling try/catch
					console.log(rx);
					try{
						var r = JSON.parse(rx);
						// printing the value in the log 
						console.log("value of AJAX return :" + r);
						//  if Retene the true Break the F
						if (r[0] === true) {
							console.log("if Retene the true Break the F");
							// Download the file  || change the download file name 
							downloadFile(wptec.wptecDownloadURL, "download.csv");
							// Send A Ajax Query for Deleting the File from Database At admin class 
							// Remove the File After 50 second 
							// Hide The Download Message 
							$("#wptecDownloadMessage").hide();
							// Stop the Function for execution 
							clearInterval(intervalId);
						}
					} catch(e){
						throw new Error('Error occurred: ', e);
					}
				});
				// if i is 99 also break the F for test 
				if(i == 999){
					// Stop the Function for execution 
					clearInterval(intervalId);
					console.log("if i is 999 also break the F for test.");
				}
				// Counting the Function number of execution
				i++;
			}, 20000);
		});

		// -------------------------------------------------------------------------------------------------------------------------
		//		   Download file helper function;
		// -------------------------------------------------------------------------------------------------------------------------
		function downloadFile(downloadURL = '', fileName ="download.csv"){
			var link  = document.createElement("a");
			link.setAttribute('download', fileName);
			link.href = downloadURL
			document.body.appendChild(link);
			link.click();
			link.remove();
		};

		// -------------------------------------------------------------------------------------------------------------------------
		//		   Any testing should be Here
		// -------------------------------------------------------------------------------------------------------------------------

	});
	
})(jQuery);
