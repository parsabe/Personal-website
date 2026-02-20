var comments = document.getElementById("comments");
var intro = document.getElementById("intro");

function show_comments() {
    comments.style.display = "block";
    intro.style.display = "none";
}
function show_subject(){
    comments.style.display = "none";
    intro.style.display = "block";
}