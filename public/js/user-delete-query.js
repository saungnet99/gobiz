$(document).on("click", ".open-model", function() {
    "use strict";
    $('#deleteModal').modal('show');
    var cardId = $(this).data('id');
    var link = "/user/card-status/" + cardId;
    var preview = document.getElementById("plan_id"); //getElementById instead of querySelectorAll
    preview.setAttribute("href", link);
    // As pointed out in comments,
    // it is unnecessary to have to manually call the modal.
});

$(document).on("click", ".open-plan-model", function() {
    "use strict";
    $('#planModal').modal('show');
    var planId = $(this).data('id');
    var link = "/user/checkout/" + planId;
    var preview = document.getElementById("plan_id"); //getElementById instead of querySelectorAll
    preview.setAttribute("href", link);
});

$(document).on("click", ".down-plan-model", function() {
    "use strict";
    $('#downPlanModel').modal('show');
});

$(document).on("click", ".open-qr", function() {
    "use strict";
    $('#openQR').modal('show');
    $('#openQR').css('display','block');
});

// QR
function downloadQr(){
    "use strict";

    var imgData = $(".qr-code").children().attr("src");
    var a = document.createElement("a");
    a.href = imgData;
    a.download = "qr.png";
    a.click();
}

function updateQr(cardURL) {
    "use strict";

    var qrtext = cardURL;
    var fill = "#000000";
    var bg = "#ffffff";
    $("#download").show();

    var normalParams ={
        mode: 0,
        ecLevel:'H',
        text: qrtext,
        render:'image',
        fill: fill,
        background: bg,
    };

    $(".qr-code").html("");
    $(".qr-code").qrcode(normalParams);
}