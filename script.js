const rows = document.querySelectorAll(".table-row");
rows.forEach((row) => {
  row.addEventListener("click", function () {
    console.log("click");
  });
});