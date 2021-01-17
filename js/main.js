
/*  ==========================================
    SHOW UPLOADED IMAGE
* ========================================== */
function readURL(input) {
    console.log(input);
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imageResult')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$(function () {
    $('#upload').on('change', function () {
        readURL(input);
    });
});

/*  ==========================================
    SHOW UPLOADED IMAGE NAME
* ========================================== */
var input = document.getElementById( 'upload' );
var infoArea = document.getElementById( 'upload-label' );


function showFileName( event ) {
    var input = event.target;
    var fileName = input.files[0].name;
    console.log(event);
    infoArea.textContent = 'File name: ' + fileName;
}
input.addEventListener( 'change', showFileName );

// copie de texte dans le presse-papier
$('#myModal').on('shown.bs.modal', function () {
    $('#myInput').trigger('focus')
})


function copyClipboard() {
    var copyText = document.getElementById("myInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    alert("URL copié dans le presse papier : " + copyText.value);
}