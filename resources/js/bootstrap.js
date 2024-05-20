import axios from 'axios';
window.axios = axios;
document.addEventListener("DOMContentLoaded", function(event) {
    document.getElementById('defaultModalButton').click();
});
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
