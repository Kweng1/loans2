

var currentPage = 1;
var maxPage = 3; // Set to the total number of pages in your form

function nextPage() {
    if (currentPage < maxPage) {
        document.getElementById('page' + currentPage).style.display = 'none';
        currentPage++;
        document.getElementById('page' + currentPage).style.display = 'block';
    }
}

function prevPage() {
    if (currentPage > 1) {
        document.getElementById('page' + currentPage).style.display = 'none';
        currentPage--;
        document.getElementById('page' + currentPage).style.display = 'block';
    }
}