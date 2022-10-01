$(document).ready(function () {
  $("#loading-container").remove();
  $("#main-container").removeClass("d-none");
});

function loadHeader() {
  $("#result-container").append("<div class='row' id='tb-container'></div>");
}

function displayResult(result) {
  result.forEach(function (elem) {
    let description = [];

    for (const [key, value] of Object.entries(elem.Descriptors)) {
      description.push("<b>" + key + "</b>: " + value);
    }

    let images = makeRequest(urlImagesNames + "?id=" + elem.ObjectID);

    let src = "";

    if (images.length < 1) {
      src = "https://dummyimage.com/404x404/dee2e6/6c757d.jpg";
    } else {
      src = images[0];
    }

    $("#tb-container").append(
      '<div class="card artifact-card card-'+elem.Category+' mx-2" role="button" data-id="' +
        elem.ObjectID +
        '">' +
        '  <div class="card-body">' +
        '        <h4 class="card-title">' +
        elem.Title +
        "</h4>" +
        '        <h6 class="card-subtitle text-muted">' +
        elem.ObjectID +
        "</h6>" +
        '        <p class="card-text p-y-1">' +
        description.join("<br>") +
        "</p>" +
        '        <p class="card-text p-y-1">' +
        elem.Category +
        "</p>" +
        "  </div>" +
        "</div>"
    );
  });

  $(".artifact-card")
    .unbind()
    .on("click", function () {
      window.location.href = "/view_artifact?id=" + $(this).attr("data-id");
    });
}
