const urlSpecificArtifact = '/api/specific/artifact';
const urlImageDelete = '/api/images/delete';

$(document).ready(function () {

    $("artifact-form").on("reset", function (e) {
        e.preventDefault();
    });

});

function imageDelBtnHandler() {
    $(".image-del").on("click", function () {
        let imgPath = $(this).attr("data-id");
        let imgName = imgPath.split("/").at(-1);

        let response = makeRequest(urlImageDelete + "?image=" + imgName, 'DELETE');

        $(this).closest(".card").remove();
        createAlert(response);

        if ($("#images-container").html() === '') {
            $("#images-outer-container").hide();
        }
    });
}

function fillUpdateForm() {
    const urlSearchParams = new URLSearchParams(window.location.search);
    const objectId = Object.fromEntries(urlSearchParams.entries())['id'];
    const category = Object.fromEntries(urlSearchParams.entries())['category'];

    const object = makeRequest(urlSpecificArtifact + "?id=" + objectId + "&category=" + category);

    if (object.status === "404") {
        createAlert(object);
    } else {
        for (const property in object) {
            if (object[property] != null && typeof object[property] == 'object') {
                let innerObject = object[property];

                //IF IS NOT AN ARRAY THEN TURN IT INTO AN ARRAY
                if (!innerObject.hasOwnProperty(length)) {
                    innerObject = [innerObject];
                }
                let selectResetted = false;

                for (const item of innerObject) {
                    for (let propertyComponent in item) {

                        let nestedElemInput = document.getElementById(property + "." + propertyComponent);
                        if (nestedElemInput) {
                            nestedElemInput.value = item[propertyComponent];
                            nestedElemInput.setAttribute("defaultValue", item[propertyComponent]);
                            continue;
                        }

                        if (propertyComponent.includes("id")) {
                            let idSelector = property + "Id";

                            if (!selectResetted) {
                                $("#" + idSelector + " option").attr("selected", false);
                                selectResetted = true;
                            }
                            $("#" + idSelector + " option[value='" + item[propertyComponent] + "']").attr("selected", true);
                        }
                    }
                }
            } else {
                let item = $("#" + property);

                if (object[property] != null) {
                    item.val(object[property]);
                    item.prop("defaultValue", object[property]);
                } else {
                    item.val("");
                    item.prop("defaultValue", "");
                }
            }
        }
        let images = makeRequest(urlImagesNames + "?id=" + objectId);

        if (images.length > 0) {
            let item = $("#images-outer-container");
            item.empty();
            item.show();
            item.append(
                '<label class="form-label">Immagini già presenti</label>' +
                '<div class="d-flex flex-row gap-2 p-0" id="images-container">'
            );

            images.forEach(function (img) {
                $("#images-container").append(
                    '<div class="card" id="' + img + '" style="width: 18rem;">' +
                    '   <img src="' + img + '" class="card-img-top img-thumbnail" style="height:20vh; object-fit: contain;" alt="Artifact image">' +
                    '   <div class="card-body">' +
                    '       <button type="button" role="button" class="btn btn-danger image-del" data-id="' + img + '">Elimina immagine</button>' +
                    '   </div>' +
                    '</div>'
                );
            });
            item.append(
                '</div>'
            );
        }

        imageDelBtnHandler();
        $("#images").val("");
    }
}