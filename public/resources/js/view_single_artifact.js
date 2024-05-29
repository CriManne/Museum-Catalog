const urlObject = "/api/generic/artifact";

$(document).ready(function () {
    const urlSearchParams = new URLSearchParams(window.location.search);
    const objectId = Object.fromEntries(urlSearchParams.entries())["id"];

    const response = makeRequest(urlObject + "?id=" + objectId);

    if (response.status === "404") {
        $("#artifact").hide();
        createAlert(response);
    } else {
        $("#artifact").removeClass("d-none");

        let images = makeRequest(urlImagesNames + "?id=" + objectId);

        if (images.length === 1) {
            $("#single-img").attr("src", images[0]);
        }

        if (images.length > 1) {
            $("#single-img").remove();
            $("#carousel").removeClass("d-none");
            let active = false;
            let index = 0;
            images.forEach(function (img) {
                $("#carousel-images").append(
                    '<div class="carousel-item ' +
                    (!active ? "active" : "") +
                    '">' +
                    '       <img src="' +
                    img +
                    '" class="d-block w-100" style="height:40vh; object-fit:contain;" alt="...">' +
                    "    </div>"
                );
                active = true;
                if (index > 0) {
                    $(".carousel-indicators").append(
                        '<button type="button" data-bs-target="#carousel" data-bs-slide-to="' + index + '"></button>'
                    )
                }
                index++;
            });
        }
    }

    $("#object-id").append(response.objectId);
    $("#object-title").append(response.title);

    for (const [key, value] of Object.entries(response.descriptors)) {
        $("#object-description").append("<p><b>" + key + "</b>: " + value + "</p>");
    }

    if (response.tag !== null) {
        $("#object-tags").append("<b>tags:</b> " + response.tag);
    }
    if (response.url !== null) {
        const conditions = ["http://", "https://"];
        if (!conditions.some((el) => response.url.includes(el))) {
            response.url = "https://" + response.url;
        }
        $("#object-url").append(
            "<b>url:</b> <a href='" + response.url + "' target='_blank'>Segui il link</a>"
        );
    }
    if (response.note !== null) {
        $("#object-note").append("<b>note:</b> " + response.note);
    }
});
