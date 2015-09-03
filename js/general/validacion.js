function clear_input(obj, defaultext){
	if(obj.id =="password"){
		if(obj.type == 'text'){
			obj.value = '';
			obj.type = 'password';
		}
	}
	else{
		if(obj.value == defaultext)
			obj.value = '';
	}	
}

function fill_input(obj, defaulttext) {
	if(obj.value==""){
		if(obj.id=="password"){
			obj.type="text";
			obj.value = defaulttext;
		}
		else{
			obj.value = defaulttext;
		}
	}			
}

function onlyNumber(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}





