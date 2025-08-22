var timeout = 30;
setTimeout(() => {
    var elem = document.getElementById('clock');
    elem.html(timeout);
    timeout--;
});

