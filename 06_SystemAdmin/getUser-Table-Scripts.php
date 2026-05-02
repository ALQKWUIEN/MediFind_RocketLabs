<!-- Export Libraries -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>

<script>
function toggleStatsCards() {
  const hero    = document.getElementById('statsHero');
  const label   = document.getElementById('statsToggleLabel');
  const chevron = document.getElementById('statsChevron');
  const isVisible = !hero.classList.contains('collapsed');
  hero.classList.toggle('collapsed', isVisible);
  label.textContent = isVisible ? 'Expand' : 'Collapse';
  chevron.classList.toggle('rotated', isVisible);
}

function getVisibleTableData(tableSelector) {
  const table   = document.querySelector(tableSelector);
  const headers = [...table.querySelectorAll('thead th')].map(th => th.innerText.trim());
  const rows    = [...table.querySelectorAll('tbody tr')]
    .filter(row => row.style.display !== 'none' && !row.classList.contains('d-none'))
    .map(row => [...row.querySelectorAll('td')].map(td => td.innerText.trim()));
  return { headers, rows };
}

function exportData(format, tableSelector = '#usersTable') {
  const { headers, rows } = getVisibleTableData(tableSelector);
  const filename = `users_export_${new Date().toISOString().slice(0, 10)}`;

  if (format === 'csv') {
    const csv  = [headers, ...rows]
      .map(r => r.map(c => `"${c.replace(/"/g, '""')}"`).join(','))
      .join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    triggerDownload(blob, `${filename}.csv`);
  }

  else if (format === 'excel') {
    const ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Users');
    XLSX.writeFile(wb, `${filename}.xlsx`);
  }

  else if (format === 'pdf') {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'landscape' });
    doc.autoTable({
      head: [headers],
      body: rows,
      styles: { fontSize: 9 },
      headStyles: { fillColor: [29, 158, 117] }
    });
    doc.save(`${filename}.pdf`);
  }
}

function triggerDownload(blob, filename) {
  const url = URL.createObjectURL(blob);
  const a   = document.createElement('a');
  a.href    = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);
}
</script>