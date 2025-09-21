
/*
  Copyright 2025 Tommi Rintala <tommi.rintala@vamk.fi>
 
  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at
  
     http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
 */

/*  Default next refresh time. Since loading of page takes almost 1s. */
var timeout = 29;

/**
 * Show the next refresh timer in 'clock' -element.
 */
function showTime() {
    let elem = document.getElementById('clock');
    elem.innerHTML = "Refresh in: " + timeout;
    timeout--;
}

function loadNext() {
//    document.load(
}

/**
 * On page load, set interval loader to update time display on page.
 * This is done every second.
 */
document.addEventListener("DOMContentLoaded", (event) => {
    setInterval(showTime, 1000);
    setInterval(loadNext, 30000);
});

