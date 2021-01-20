/*  ==========================================
    SHOW UPLOADED IMAGE
* ========================================== */

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imageResult')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        getFilePath();
    }
}

function showFileName( event ) {
    var input = event.target;
    var fileName = input.files[0].name;
    var infoArea = document.getElementById( 'upload-label' );
    infoArea.textContent = fileName;
}

function fileImageVraiURL(event){
    var imageResult = document.getElementById('imageResult');
    imageResult.src = document.getElementById( 'url_image').value;
}

// copie de texte dans le presse-papier
$('#myModal').on('shown.bs.modal', function () {
    $('#myInput').trigger('focus')
})

function copyClipboard() {
    var copyText = document.getElementById("myInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    alert("URL copiée dans le presse papier : " + copyText.value);
}

window.addEventListener('load', function() {
    var input = document.getElementById( 'upload');
    input.addEventListener( 'change', showFileName );
    input.addEventListener( 'change', readURL(input));

    var test = document.getElementById( 'url_image');
    test.addEventListener( 'change', fileImageVraiURL);
});