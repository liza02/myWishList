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