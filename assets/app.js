import "./styles/app.css";
import $ from "jquery";
import "bootstrap";





$(function() {

    $(".custom-file-input").on("change", function(e) {
        var inputFile = e.currentTarget;
        $(inputFile)
            .parent()
            .find(".custom-file-label")
            .html(inputFile.files[0].name);

        console.log(inputFile.files[0].name)
    });


});



// let inputElement = document.querySelector('.custom-file-input');
// inputElement.addEventListener("change", handleFiles, false);

// let handleFiles = () => {
//     let fileList = this.files; /* Vous pouvez maintenant manipuler la liste de fichiers */
//     console.log(fileList)
// }