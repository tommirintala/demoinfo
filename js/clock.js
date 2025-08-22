var timeout = 29;
function showTime() {
    let elem = document.getElementById('clock');
    elem.innerHTML = "Refresh in: " + timeout;
    timeout--;
}

document.addEventListener("DOMContentLoaded", (event) => {
    console.log("Doc loaded");
    setInterval(showTime, 1000);    
});

