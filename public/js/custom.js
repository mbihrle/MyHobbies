function confirm_delete(what, name, model, id) {
    let question =
        "Bist du sicher, dass du " +
        what +
        "\n" +
        name +
        "\n" +
        "löschen möchtest?";

    if (confirm(question)) {
        let form_action = "/" + model + "/" + id;
        console.log(form_action);
        $("#loeschen_formular")
            .attr("action", form_action)
            .submit();
    }
}
