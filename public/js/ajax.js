document.addEventListener('DOMContentLoaded', function() {
    fetch(API_URL)
            .then(response => response.json())
            .then(data => console.log(data));
});
