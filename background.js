function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

var requestFilter = {
    urls: [ "https://www.google.com/recaptcha/api2/payload*"]
  },
  extraInfoSpec = ['blocking'],
  handler = function( details ) {
	if (details.type=="other") {
		console.log(details);
		
		var xhttp = new XMLHttpRequest();
		xhttp.onload = function(e) {		
			var arrayBuffer = xhttp.response;
			if (arrayBuffer) {
				var byteArray = new Uint8Array(arrayBuffer);
				
				var xhr = new XMLHttpRequest;
				xhr.open("POST", "http://127.0.0.1/getcode.php", false);
				xhr.onload = function(e) {
					console.log(xhr.responseText);
					chrome.tabs.query({active: true, currentWindow: true}, function(tabs) {
					  chrome.tabs.sendMessage(tabs[0].id, {code: xhr.responseText}, function(response) {
						console.log(response.farewell);
					  });
					});
				}
				xhr.send(byteArray);
			}
		};
		xhttp.open("GET", details.url, true);
		xhttp.responseType = 'arraybuffer';
		xhttp.send(null);
	}
  };
chrome.webRequest.onBeforeRequest.addListener( handler, requestFilter, extraInfoSpec );
