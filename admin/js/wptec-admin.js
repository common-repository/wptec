/**
 * This file is for Plugin settings page. settings sorting list, events, AJAX request.
 * @name       wptec-admin.js
 * @Framework  Vue.js
 * @package    Wptec
 * @subpackage Wptec/admin/js
 * @author     javmah <jaedmah@gmail.com>
*/
//  check div exist or not  
const wptecAPP = document.getElementById('wptecApp') || false
//  is there is a Div then do the action 
if(wptecAPP){
	var wptecVue = new Vue({
		el: '#wptecApp',
		display: "Simple",
		order: 0,
		data(){
			return{
				drag: false,
				activeTab:"user",
				notification:"",
				editPanelDisplay: false,
				actionPanelDisplay: false,
				editProperty:  {"name": "", "title": "", "width" : "", "type" : "", "status" : true},
				columnListFrom:{"user" : false, "post" : false, "page" : false , "comment" : false, "media" : false, "product" : false, "order" : false},
				// Hello This is it,
				userList:    [],
				postList:    [],
				pageList:    [],
				commentList: [],
				mediaList:   [],
				productList: [],
				orderList:   [],
			}
		},
		methods:{
			listOrderChange: function(e){
				this.actionPanelDisplay = true;
			},
			settingsIconClicked:function(property, key){
				//  Display the editPanel 
				this.editPanelDisplay = true;
				this.editProperty     = property;
			},
			actionPanelSaveBtnClicked:function(){
				// AJAX Data 
				var data = {
					"action" 	: 'wptecAdminAJAX',
					"EventName" : 'save',
					"security" 	: wptec.wptecSecurity,
					"tableName" : this.activeTab,
					"data"      : JSON.stringify(this[this.activeTab + 'List']) 
				};
				// Request Object 
				let request = new XMLHttpRequest();
				request.onreadystatechange = function(){
					if(request.readyState == XMLHttpRequest.DONE){
						// Response data 
						console.log(request.responseText);
						// Displaying Message 
						Vue.set(wptecVue, 'notification', "SUCCESS: list saved.");
						//  Removing the Message 
						setTimeout(() => Vue.set(wptecVue, 'notification', ""), 5000);
						//  Hide the Panel 
						Vue.set(wptecVue, 'actionPanelDisplay', false);
					}
				}
				// Opening request and Initiating the request 
				request.open('POST', wptec.wptecAjaxURL, true);
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
				request.send(new URLSearchParams(data).toString());
			},
			actionPanelResetBtnClicked:function(){
				console.log("actionPanelResetBtnClicked");
				// AJAX Data 
				var data = {
					"action" 	: 'wptecAdminAJAX',
					"EventName" : 'resetDefault',
					"security" 	: wptec.wptecSecurity,
					"tableName" : this.activeTab,
				};
				// Request Object 
				let request = new XMLHttpRequest();
				request.onreadystatechange = function(){
					if(request.readyState == XMLHttpRequest.DONE){
						// Response data
						console.log(request.responseText);
						// Displaying Message
						Vue.set(wptecVue, 'notification', "SUCCESS: reset default.");
						//  Removing the Message
						setTimeout(() => Vue.set(wptecVue, 'notification', ""), 5000); 
						//  Hide the Panel
						Vue.set(wptecVue, 'actionPanelDisplay', false);
					}
				}
				// Opening request and Initiating the request 
				request.open('POST', wptec.wptecAjaxURL, true);
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
				request.send(new URLSearchParams(data).toString());
			},
			resetDefaultTables: function(e){
				// AJAX Data 
				var data = {
					"action" 	: 'wptecAdminAJAX',
					"EventName" : 'resetDefaultAll',
					"security" 	: wptec.wptecSecurity,
					"tableName" : this.activeTab,
				};
				// Request Object 
				let request = new XMLHttpRequest();
				request.onreadystatechange = function(){
					if(request.readyState == XMLHttpRequest.DONE){
						// Response data 
						console.log(request.responseText);
						// Displaying Message 
						Vue.set(wptecVue, 'notification', "SUCCESS: reset default all the list.");
						//  Removing the Message 
						setTimeout(() => Vue.set(wptecVue, 'notification', ""), 5000);
						//  Hide the Panel 
						Vue.set(wptecVue, 'actionPanelDisplay', false);
					}
				}
				// Opening request and Initiating the request 
				request.open('POST', wptec.wptecAjaxURL, true);
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
				request.send(new URLSearchParams(data).toString());
			}
		},
		mounted(){
			this.columnListFrom = columnListFrom;
			this.userList 		= wptecUserList;
			this.postList 		= wptecPostList;
			this.pageList 		= wptecPageList;
			this.commentList 	= wptecCommentList;
			this.mediaList 		= wptecMediaList;
			this.productList 	= wptecProductList;
			this.orderList 		= wptecOrderList;
		}
	});
}

