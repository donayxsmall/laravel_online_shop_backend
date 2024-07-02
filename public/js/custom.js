/**
import { indexOf } from '../library/codemirror/src/util/misc';
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

function previewImage() {
    var input = document.getElementById('photo');
    var preview = document.getElementById('image-preview');

    var file = input.files[0];
    var reader = new FileReader();

    reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = 'block';

        // Set maximum width and height for the preview
        var maxWidth = 400;
        var maxHeight = 300;

        // Get the original width and height of the image
        var originalWidth = preview.width;
        var originalHeight = preview.height;

        // Calculate the new dimensions while maintaining aspect ratio
        var newWidth, newHeight;
        if (originalWidth > originalHeight) {
            newWidth = maxWidth;
            newHeight = (originalHeight / originalWidth) * maxWidth;
        } else {
            newHeight = maxHeight;
            newWidth = (originalWidth / originalHeight) * maxHeight;
        }

        // Set the new dimensions for the preview
        preview.width = newWidth;
        preview.height = newHeight;
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}

$(".confirm-delete").click(function(e) {
    e.preventDefault(); // Prevent the default form submission

    Swal.fire({
        title: "Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $(this).closest('form').submit();
        }
    });
});

$(".fade").delay(1500).slideUp(300);

var path = location.pathname.split('/');
var url = location.origin + '/' + path[1];

$('ul.sidebar-menu li a').each(function() {

    var urlOrigin = location.origin+'/';




    if($(this).attr('href').indexOf(url) !== -1 ) {
        if(url == urlOrigin){
            $('#sidebarMenu li').eq(1).addClass('active');
            // console.log("url home "+$(this).find('a').attr('href'));
        }else {
            $(this).parent().addClass('active').parent().parent('li').addClass('active');
            // console.log('not home '+JSON.stringify($(this).parent()));
        }

    }
});

console.log('path : '+location.pathname);
console.log('url : '+url);


var table = document.getElementById("product-details-table");

if(table){
    table.style.display = "none";
}


function toggleDetails() {
    var table = document.getElementById("product-details-table");
    var button = document.getElementById("toggle-button");
    if (table.style.display === "none") {
        table.style.display = "table";
        button.innerHTML = "-";
    } else {
        table.style.display = "none";
        button.innerHTML = "+";
    }
}
