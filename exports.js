function exportTableToPdf(data, nom_fichier, nom_client, siren) {
    // on recupere la premier ligne du tableau (le header)
    const header = data.shift();

    // on appelle l'API jspdf au format a4 avec une orientation portrait
    const { jsPDF } = window.jspdf; // Import jsPDF
    const doc = new jsPDF("p", "pt", "a4");

    // si on a mis les valeurs du client (donc c'est un client qui demande l'export)
    if (nom_client != "" && siren != "") {
        // on recupere le nom du ficheir pour savoir si on export des remises, des impayes ou des transactions(tresorerie)
        var path = window.location.pathname;
        var nom_page = path.split("/").pop();

        // on ecrit dans le document
        doc.text(
            "LISTE DES " +
                nom_page.toUpperCase() +
                " DE L'ENTREPRISE" +
                nom_client.toUpperCase() +
                " N° SIREN " +
                siren.toUpperCase(),
            100,
            20
        );
    }
    // on ajoute la table
    doc.autoTable({
        head: [header],
        body: data,
    });
    // on fait telechager
    doc.save(nom_fichier + ".pdf");
}

function exportTableToCSVouXLS(type, data, nom_fichier) {
    fetch("exporter.php?type=" + type, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((reponse) => reponse.blob())
        .then((blob) => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = nom_fichier + "." + type;
            document.body.appendChild(a);
            a.click();
            a.remove();
        })
        .catch((error) => {});
}

function exporter(contentId, nom_client = "", siren = "") {
    // on recupere le type d'export demander par l'utilisateur
    const select = document.querySelector("form.table-export select");
    const index = select.selectedIndex;
    const choix = select.options[index].value;
    // on recupere la date
    const date = new Date().toLocaleDateString();
    // on la met dans le nom du fichier
    const nom_fichier = "extrait_du_" + date;

    // on met dans un tableau les valeurs du tableau html
    const lignes = document.querySelectorAll("div#" + contentId + " tr");
    const data = [];
    lignes.forEach((ligne) => {
        let tds = ligne.querySelectorAll("td");
        const tmp = [];
        if (tds.length == 0) {
            tds = ligne.querySelectorAll("th");
            tds.forEach((td) => {
                tmp.push(td.textContent.toUpperCase());
            });
        } else {
            tds.forEach((td) => {
                tmp.push(td.textContent);
            });
        }
        data.push(tmp);
    });
    // selon le choix de l'utilisateur on utilise la fonction qui correspond
    if (choix === "pdf") {
        exportTableToPdf(data, nom_fichier, nom_client, siren);
    } else {
        exportTableToCSVouXLS(choix, data, nom_fichier);
    }
}
