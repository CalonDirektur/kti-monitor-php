// KTI Monitor - Main JavaScript
document.addEventListener("DOMContentLoaded", function () {
  console.log("KTI Monitor loaded");

  // Auto refresh data every 5 minutes
  setInterval(refreshData, 300000);
});

function refreshData() {
  fetch("/api/gempa")
    .then((response) => response.json())
    .then((data) => console.log("Gempa data refreshed"))
    .catch((error) => console.error("Error:", error));
}

function formatDate(dateString) {
  const options = { day: "numeric", month: "long", year: "numeric" };
  return new Date(dateString).toLocaleDateString("id-ID", options);
}
