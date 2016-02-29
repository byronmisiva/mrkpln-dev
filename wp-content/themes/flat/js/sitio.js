var x = document.getElementsByClassName("contacto-talento");
if (x[0].addEventListener) {                    
    x[0].addEventListener("click", myFunction);
} else if (x[0].attachEvent) {                  
    x[0].attachEvent("onclick", myFunction);
}

  function myFunction() {
     alert('hello there');
    }

alert("asdas");
