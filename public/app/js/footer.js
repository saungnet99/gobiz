var scroll = new SmoothScroll('a[href*="#"]');
function closeCookie() {
  $(".cookie-consent").fadeOut(300);
}

// Send vCard
(function ($) {
  "use strict";
  var openmodal = document.querySelectorAll('.send-modal-open')
  for (var i = 0; i < openmodal.length; i++) {
    openmodal[i].addEventListener('click', function (event) {
      event.preventDefault()
      toggleModal()
    })
  }

  const overlay = document.querySelector('.send-modal-overlay')
  overlay.addEventListener('click', toggleModal)

  var closemodal = document.querySelectorAll('.modal-close')
  for (var i = 0; i < closemodal.length; i++) {
    closemodal[i].addEventListener('click', toggleModal)
  }

  document.onkeydown = function (evt) {
    evt = evt || window.event
    var isEscape = false
    if ("key" in evt) {
      isEscape = (evt.key === "Escape" || evt.key === "Esc")
    } else {
      isEscape = (evt.keyCode === 27)
    }
    if (isEscape && document.body.classList.contains('modal-active')) {
      toggleModal()
    }
  };


  function toggleModal() {
    const body = document.querySelector('body')
    const modal = document.querySelector('.send-modal')
    modal.classList.toggle('opacity-0')
    modal.classList.toggle('pointer-events-none')
    body.classList.toggle('modal-active')
  }
})(jQuery);

// Scan QR
(function ($) {
  "use strict";
  var openmodal = document.querySelectorAll('.qr-modal-open')
  for (var i = 0; i < openmodal.length; i++) {
    openmodal[i].addEventListener('click', function (event) {
      event.preventDefault()
      toggleModal()
    })
  }

  const overlay = document.querySelector('.qr-modal-overlay')
  overlay.addEventListener('click', toggleModal)

  var closemodal = document.querySelectorAll('.modal-close')
  for (var i = 0; i < closemodal.length; i++) {
    closemodal[i].addEventListener('click', toggleModal)
  }

  document.onkeydown = function (evt) {
    evt = evt || window.event
    var isEscape = false
    if ("key" in evt) {
      isEscape = (evt.key === "Escape" || evt.key === "Esc")
    } else {
      isEscape = (evt.keyCode === 27)
    }
    if (isEscape && document.body.classList.contains('modal-active')) {
      toggleModal()
    }
  };


  function toggleModal() {
    const body = document.querySelector('body')
    const modal = document.querySelector('.qr-modal')
    modal.classList.toggle('opacity-0')
    modal.classList.toggle('pointer-events-none')
    body.classList.toggle('modal-active')
  }
})(jQuery);

// Download vcard qrcode
function downloadQr(cardURL, imageSize) {
  "use strict";

  var qrCode = new QRious({
    value: cardURL,
    size: imageSize,  // Set your desired image size
  });

  var a = document.createElement("a");
  a.href = qrCode.toDataURL("image/png");  // Change the format if needed
  a.download = "qr.png";  // Change the filename and format if needed
  a.click();
}

// Update vcard qrcode
function updateQr(cardURL) {
  "use strict";

  var qrCode = new QRious({
    value: cardURL,
    size: 200,  // Set the default image size if needed
  });

  // Display the QR code in the .qr-code div
  $(".qr-code").html(qrCode.image);
  
  $("#download").show();
}