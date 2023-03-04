function submitAnswer(id) {

    var answer = document.getElementById(id + "edit").value 
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

    xmlHttp.open('GET', 'include/queries.php?id=' + id + "&answer=" + answer + "&req=1")
    xmlHttp.send();
}

