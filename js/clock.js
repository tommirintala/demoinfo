var timeout = 30;
document.addEventListener("DOMContentLoaded", (event) => {
    console.log("Doc loaded");
    setTimeout(() => {
	var elem = document.getElementById('clock');
	elem.html(timeout);
	timeout--;
    }, 1);
});

