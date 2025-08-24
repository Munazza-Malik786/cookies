<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Info Page</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .hidden { display: none; }
    .popup {
      position: fixed; inset: 0;
      background: rgba(0,0,0,0.5);
      display: flex; align-items: center; justify-content: center;
    }
    .popup-content {
      background: white; padding: 20px; border-radius: 12px;
      text-align: center; max-width: 300px;
    }
    button {
      margin-top: 10px; padding: 8px 16px;
      border: none; border-radius: 8px;
      background: #007bff; color: white; cursor: pointer;
    }
    button:hover { background: #0056b3; }
    .card { border: 1px solid #ccc; padding: 12px; margin-top: 12px; border-radius: 8px; }
  </style>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>

  <h1>User Info Page</h1>

  <!-- Cookies Popup -->
  <div id="cookiePopup" class="popup">
    <div class="popup-content">
      <h2>We use cookies 🍪</h2>
      <p>This site uses cookies to improve your experience. Please accept to continue.</p>
      <button onclick="acceptCookies()">Accept Cookies</button>
    </div>
  </div>

  <!-- Main Content -->
  <div id="content" class="hidden">
    <!-- Cookies -->
    <div class="card">
      <h2>Cookies:</h2>
      <pre id="cookieData"></pre>
    </div>

    <!-- Location -->
    <div class="card">
      <h2>Location:</h2>
      <p id="locationText">Not fetched yet</p>
      <button onclick="getLocation()">Get Location</button>
    </div>

    <!-- Google Auth -->
    <div class="card">
      <h2>Google Auth:</h2>
      <div id="g_id_onload"
        data-client_id="YOUR_GOOGLE_CLIENT_ID"
        data-callback="handleCredentialResponse">
      </div>
      <div class="g_id_signin" data-type="standard"></div>
      <div id="userInfo"></div>
    </div>
  </div>

  <script>
    // 🔹 Accept cookies
    function acceptCookies() {
      document.cookie = "cookiesAccepted=true; path=/";
      document.getElementById("cookiePopup").classList.add("hidden");
      document.getElementById("content").classList.remove("hidden");
      showCookies();
    }

    // 🔹 Show cookies
    function showCookies() {
      document.getElementById("cookieData").textContent = document.cookie;
    }

    // 🔹 Location fetch
    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((pos) => {
          document.getElementById("locationText").textContent =
            `Lat: ${pos.coords.latitude}, Lng: ${pos.coords.longitude}`;
        });
      } else {
        alert("Geolocation not supported");
      }
    }

    // 🔹 Google Sign-In callback
    function handleCredentialResponse(response) {
      const data = parseJwt(response.credential);
      document.getElementById("userInfo").innerHTML =
        `<p>Name: ${data.name}</p><p>Email: ${data.email}</p>`;
      document.cookie = `userName=${data.name}; path=/`;
      document.cookie = `userEmail=${data.email}; path=/`;
      showCookies();
    }

    // Decode JWT token (Google returns ID token)
    function parseJwt(token) {
      let base64Url = token.split('.')[1];
      let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
      let jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
          return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
      }).join(''));
      return JSON.parse(jsonPayload);
    }

    // 🔹 Auto check cookie on load
    window.onload = () => {
      if (document.cookie.includes("cookiesAccepted=true")) {
        document.getElementById("cookiePopup").classList.add("hidden");
        document.getElementById("content").classList.remove("hidden");
        showCookies();
      }
    };
  </script>
</body>
</html>
