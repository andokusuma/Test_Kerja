$(function () {
    var e = {};
    $(".table-edits tr").editable({
        dropdowns: { gender: ["Male", "Female"] },

        edit: function (t) {
            $(".edit i", this)
                .removeClass("fa-pencil-alt")
                .addClass("fa-save")
                .attr("title", "Save");
            var cancelBtn = $(
                "<a class='btn btn-outline-secondary btn-sm cancel' title='Cancel' id='cancel'>" +
                    "<i class='fas fa-times'></i>" +
                    "</a>"
            );

            // Append cancelBtn inside the <td class="actions">
            $("td:last-child", this).append(cancelBtn);
        },
        save: function (t) {
            $(".edit i", this)
                .removeClass("fa-save")
                .addClass("fa-pencil-alt")
                .attr("title", "Edit");

            if (this in e) {
                e[this].destroy();
                delete e[this];
            }

            // Remove the Cancel button
            $("#cancel", this).remove();
        },
        cancel: function (t) {
            $(".edit i", this)
                .removeClass("fa-save")
                .addClass("fa-pencil-alt")
                .attr("title", "Edit");

            $(".cancel i", this)
                .removeClass("fa-times")
                .addClass("fa-pencil-alt")
                .attr("title", "Cancel");

            if (this in e) {
                e[this].destroy();
                delete e[this];
            }

            // Remove the Cancel button
            $("#cancel", this).remove();
        },
    });
});
