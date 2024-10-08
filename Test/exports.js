import html2pdf from 'html2pdf.js';

function exportTableToPdf(contentId) {
    const tableToExport = document.getElementById(contentId);
    const opt = {
        margin: 1,
        filename: "table-data.pdf",
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    }
    html2pdf().from(tableToExport).set(opt).save();
}