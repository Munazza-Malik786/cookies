
fetch("db.php", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify(userData)
})
.then(res => res.text())
.then(data => {
  console.log("Server response:", data);
  // agar data save ho gaya to redirect
  if (data.includes("success")) {
    window.location.href = "index.php";
  }
})
.catch(err => console.error(err));

