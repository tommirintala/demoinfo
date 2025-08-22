var timeout = 30;
function showTime() {
    let elem = document.getElementById('clock');
    elem.innerHTML = timeout;
    timeout--;
}

document.addEventListener("DOMContentLoaded", (event) => {
    console.log("Doc loaded");
    setInterval(showTime, 1000);    
});

