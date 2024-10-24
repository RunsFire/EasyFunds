function exportTableToPdf(contentId) {
    const tableToExport = document.querySelector(
        "div#" + contentId + " div.table.frame"
    );
    console.log(tableToExport);
    const opt = {
        margin: 1,
        filename: "export.pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
    };

    html2pdf().set(opt).from(tableToExport).save();
}

function exportTableToCSV(contentId) {
    const lignes = document.querySelectorAll("div#" + contentId + " tr");
    const data = [];
    lignes.forEach((ligne) => {
        let tds = ligne.querySelectorAll("td");
        if (tds.length == 0) {
            tds = ligne.querySelectorAll("th");
        }
        const tmp = [];
        tds.forEach((td) => {
            tmp.push(td.textContent);
        });
        data.push(tmp);
    });

    fetch("exporter.php", {
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
            a.download = "export.csv";
            document.body.appendChild(a);
            a.click();
            a.remove();
        })
        .catch((error) => {});
}

function exportTableToXLS(contentId) {
    let table = document.querySelector("div#" + contentId + " div.table.frame");
    // Extract the HTML content of the table
    const html = table.outerHTML;

    // Create a Blob containing the HTML data with Excel MIME type
    const blob = new Blob([html], { type: "application/vnd.ms-excel" });

    // Create a URL for the Blob
    const url = URL.createObjectURL(blob);

    // Create a temporary anchor element for downloading
    const a = document.createElement("a");
    a.href = url;

    // Set the desired filename for the downloaded file
    a.download = "export.xls";

    // Simulate a click on the anchor to trigger download
    a.click();

    // Release the URL object to free up resources
    URL.revokeObjectURL(url);
}

function exporter(contentId) {
    const select = document.querySelector(
        "div#" + contentId + " form.table-export select"
    );
    const index = select.selectedIndex;
    const choix = select.options[index].value;
    if (choix === "csv") {
        exportTableToCSV(contentId);
    } else if (choix === "pdf") {
        exportTableToPdf(contentId);
    } else if (choix === "xls") {
        exportTableToXLS(contentId);
    }
}
