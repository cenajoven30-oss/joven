
function viewReport(id) {
  // Open modal
  document.getElementById("reportModal").style.display = "block";
  document.getElementById("modal-body").innerHTML = "<p>Loading...</p>";

  // Fetch report data via AJAX
  fetch("get_report.php?id=" + id)
    .then(response => response.text())
    .then(data => {
      document.getElementById("modal-body").innerHTML = data;
    })
    .catch(error => {
      document.getElementById("modal-body").innerHTML = "<p style='color:red;'>Error loading report.</p>";
    });
}

function closeModal() {
  document.getElementById("reportModal").style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
  let modal = document.getElementById("reportModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

