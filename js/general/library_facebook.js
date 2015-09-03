
var LibraryFacebook = (function(module, $) {

	// Public functions
	var init, newInstance, 

	// Private variables
	settings, signedRequest, setSignedRequest, userFbData, setFbData, getuserFbData, getSignedRequest,
	$appId, $signedRequest, $baseUrl, $controler, $namespace, $permission, $tabLiker, $tabNoLiker, $debug,$data,
	
	// Private functions
	parseSignedRequest, loadViewPageTab, loadViewPageApp, checkStatusPageTab, checkStatusPageApp, testMethod;	


	/**
	 * Initialise the plugin and define global options
	 */
	init = function(options) {
		// Default settings
		settings = {
			appId					 : null,
			signedRequest            : '',
			base                     : 'http://localhost/',
			controler			     : '',
			namespace   	   	     : '',
			permission				 : '',
			debug                    : false,
			tabLiker                 : 'liker',
			tabNoLiker               : 'noLiker',
			doesNtCare				 : false,
			content					 : '',
			isPageTab			     : false,
			data					 : ' ' 
		};
		// Override defaults with arguments
		$.extend(settings, options);
		
		$appId			   = settings.appId;
		$signedRequest	   = settings.signedRequest;
		$baseUrl           = settings.base;		
		$controler         = settings.controler;		
		$tabLiker          = settings.tabLiker;
		$tabNoLiker        = settings.tabNoLiker;		
		$namespace         = settings.namespace;
		$permission        = settings.permission;
		$content		   = settings.content;
		$doesNtCare        = settings.doesNtCare;
		$isPageTab		   = settings.isPageTab;
		$debug             = settings.debug;
		$data              = settings.data;
	};
	
	setSignedRequest = function( data ) {
		if (typeof signedRequest == 'object' )
			return;
		signedRequest = data;	
		
	};
	
	getSignedRequest = function() {
		return signedRequest;		
	};
	
	setUserFbData = function( data ) {		
		userFbData = data;
	};
	
	getUserFbData = function() {		
		return userFbData;
	};
	
	newInstance = function() {	
		if( !JSON.parse($debug).develop ){			
			FB.getLoginStatus(function(response) {					
				( $signedRequest != '' ) ? parseSignedRequest( $signedRequest ) : parseSignedRequest( response.authResponse.signedRequest);				
				if( signedRequest.page && $isPageTab ){
					loadViewPageTab( response );					
				}
				else{
					loadViewPageApp( response );					
				}
			});	
		}
		else{			
			testMethod();
		}							    		
	};
	
	loadViewPageTab = function( response ) {		
		if ( $doesNtCare ){			
			$( '#' + $content ).load( $baseUrl + $controler + '/' + $tabNoLiker  );
		}
		else{							
			if( signedRequest.page.liked ){				
				if ( $permission != '' ){
					checkStatusPageTab( response );	
				}
				else{
					$( '#' + $content ).load( $baseUrl + $controler + '/' + $tabLiker, { 'fb_page' : JSON.stringify( getSignedRequest() ) }  );
				}
											
			}
			else{
				$( '#' + $content ).load( $baseUrl + $controler + '/' + $tabNoLiker, { 'fb_page' : JSON.stringify( getSignedRequest() ) } );
			}						    				
		}			
	};
	
	checkStatusPageTab = function( response ) {
		if (response.status === 'connected') {
			//FB.api( '/v2.0/me?fields=id,name,first_name,last_name,username,location,gender,email', function(response)
			FB.api( '/v2.0/me?fields=id,name,first_name,last_name,location,gender,email', function(response) {	
				setUserFbData( response );
				$( '#' + $content ).load( $baseUrl + $controler + '/' + $tabLiker, { 'user' : JSON.stringify( response ), 'fb_page' : JSON.stringify( getSignedRequest() ), 'data' : $data }  );
			});							
		}
		else if ( response.status === 'not_authorized' && $permission != '' ){			
			FB.api( signedRequest.page.id + '?fields=id,name,username', function(response) {				
				if( response.error ){
					parent.location.href="https://www.facebook.com/dialog/oauth?client_id=" + $appId +
					"&redirect_uri=" + $baseUrl + $controler + "/pageTab/" + getSignedRequest().app_data + "/preproduccion.misiva/" + $appId + "/" + $namespace + 	    								
					"&scope=" + $permission;			
				}
				else{
					parent.location.href="https://www.facebook.com/dialog/oauth?client_id=" + $appId +
					"&redirect_uri=" + $baseUrl + $controler + "/pageTab/" + getSignedRequest().app_data + "/" + response.username + "/" + $appId + "/" + $namespace + 	    								
					"&scope=" + $permission;			
				}
										
			});
		}
		else if ( response.status === 'unknown' ) {
			parent.location.href = $baseUrl + $controler + "/pageTab/undefined";
		}	
	};
	
	loadViewPageApp = function( response ) {		
		if ( $isPageTab ){
			parent.location.href = $baseUrl + $controler + "/pageTab/undefined/undefined";			
		}
		else{			
			checkStatusPageApp( response );
			
		}		
	};
	
	checkStatusPageApp = function( response ) {		
		if (response.status === 'connected') {
			FB.api( '/v2.0/me', function(response) {		
				setUserFbData( response );
				$( '#' + $content ).load( $baseUrl + $controler + '/' + $tabLiker, {user: JSON.stringify( response ) }  );
			});			
		}
		else if ( response.status === 'not_authorized' && $permission != '' ){
			parent.location.href="https://www.facebook.com/dialog/oauth?client_id=" + $appId +
			"&redirect_uri=" + $baseUrl + $controler + "/pageApp/" + $namespace +	    								
			"&scope=" + $permission;						
		} 
		else if ( response.status === 'unknown' ) {
			parent.location.href = $baseUrl + $controler + "/pageApp/" + $namespace;
			
		}	
	};	
	
	parseSignedRequest = function( sr ) {
		
		$.ajax({		
			async : false,
			cache: false,
    		type: "POST",
    		url: $baseUrl + $controler + "/getGeneral",
    		data: { 'signedRequest' :  sr },
    		success: function( response ) {    			
    			setSignedRequest( JSON.parse( response ) );    			
    		}		
		});
	};
	
	testMethod = function() {
		console.debug("LA APP FUNCIONA PERO ESTAS EN MODO DE PRUEBAS CAMBIAR CUANDO ESTE EN PRODUCCION");
		var develop_signedRequest = { 
				algorithm : "HMAC-SHA256", 
				expires : 1365717600, 
				issued_at : 1365713731, 
				oauth_token : "BAAEQwRDO9ZBcBAG8ZAA3zerneadB0qKK48qobYxt9LynazfxgqO44y8Kd0XfFeZCYXUMtwPlcQ3d2ZBtpmduKs0FoDwVGoEucryQHUR6C1YtfHIzpsfrh57SrRNsrsoNa5RchnqVUVA5O1ZAwOM8uexGS9VSdFc17PbpcayC94zF7E12I9hx21JFurLgYQdrZAm855adOFcWqamSb2166v",
				page : { id : "195554460484731", liked : true, admin : true },
				user : { country : "ec", locale : "es_ES", age : { min: 21 }},
				user_id : "fbid" };		
		
		var develop_userData = { email : "developemail@mail.com", 
				first_name : "developFirstName",
				gender : "developGender",
				id : "developId",
				last_name : "developLastName",
				link : "https://www.facebook.com/developLink",
				locale : "es_ES",
				name : "developName",
				timezone : -5,
				updated_time : "2013-03-22T17:59:29+0000",
				username : "developUserName",
				verified : true,
				location : { "id" : "developId", "name" : "Quito, Ecuador" }
		};
		setSignedRequest( develop_signedRequest );
		setUserFbData( develop_userData );		
		if( JSON.parse($debug).like ){			
			$( '#' + $content ).load( $baseUrl + $controler + '/' + $tabLiker, { 'user' : JSON.stringify( getUserFbData() ), 'fb_page' : JSON.stringify( getSignedRequest() ), 'data' : $data }  );
		}
		else{		
			$( '#' + $content ).load( $baseUrl + $controler + '/' + $tabNoLiker  );
		}
		eval($('#' + $content ).find("script").text());	
		
	};
	
	module = {
		init         	: init,
		newInstance     : newInstance,
		setSignedRequest: setSignedRequest,
		getUserFbData   : getUserFbData,
		getSignedRequest: getSignedRequest
	};
	return module;

}(LibraryFacebook || {}, jQuery));

	
