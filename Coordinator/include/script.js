function deleteGroup(id) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            if(this.response == '1') {
		        location.reload();
	        } else {
                console.log(this.responseText)
            }
        }
    }

    xmlHttp.open('GET', 'include/script.php?id=' + id)
    xmlHttp.send();
}


