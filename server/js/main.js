var ut=1000; 
var prev=''; 
var basePath = '/rfid/';
var imgPath = basePath + 'img/';

function e(i){return document.getElementById(i);}
function upd(){
	ajr( basePath + 'check.php','hr');
}
function hr(i,r){
	if (prev!=r) {
		prev = r;
		var obj = JSON.parse(r);
		e("ul-missed-codes").innerHTML = "";
		e("ul-previous-codes").innerHTML = "";
		obj['missed'].forEach(function(entry) {
			var img = document.createElement("img");
			img.setAttribute("src",imgPath+entry);
			e("ul-missed-codes").appendChild(img);
		});
		obj['previous'].forEach(function(entry) {
			var img = document.createElement("img");
			img.setAttribute("src",imgPath+entry);
			e("ul-previous-codes").appendChild(img);
		});
	}
}

function ajr(u,rf,fp){
	var x = false;
	if(typeof XMLHttpRequest!='undefined'){
		x = new XMLHttpRequest();
	}
	x.open("GET", u,true);
	x.onreadystatechange=function(){
		if((rf!='')&&(rf!=undefined)&&(x.readyState==4)){
			tf = rf+'(\''+fp+'\', x.responseText);';
			eval(tf);
		}
	}; 
	x.send(null);
}

window.onload  = function() {
	e("btn-scan").onclick = function() {
		e("last_scan").innerHTML = new Date();
		ajr(basePath + 'clear.php');
		ajr(basePath + 'check.php','hr');
	};
	e("last_scan").innerHTML = new Date();
}

window.setInterval("upd();",ut)
