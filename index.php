<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Munazza's Site</title>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #007bff, #00c6ff);
      color: white;
      text-align: center;
      margin: 0;
      padding: 0;
    }

    .cookies-popup {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: #222;
      padding: 20px;
      border-radius: 10px;
      color: white;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      z-index: 1000;
    }
    .cookies-popup button {
      background: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      margin-top: 10px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }

    .profile-card {
      display: none;
      background: white;
      color: black;
      max-width: 350px;
      margin: 60px auto;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.3);
      text-align: center;
    }

    .profile-card img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      margin-bottom: 15px;
      border: 3px solid #007bff;
    }

    .profile-card h2 {
      margin: 5px 0;
      color: #007bff;
    }

    .profile-card p {
      margin: 5px 0;
      color: #333;
    }

    .google-btn {
      margin-top: 20px;
      display: none;
      justify-content: center;
    }
  </style>
</head>
<body>

  <h1 style="margin-top:40px;">✨ Welcome to Munazza's Site ✨</h1>

  <!-- Cookies popup -->
  <div class="cookies-popup" id="cookiesPopup">
    <p>We use cookies to personalize content and analyze traffic. Please accept to continue.</p>
    <button onclick="acceptCookies()">Accept Cookies</button>
  </div>

  <!-- Google Auth button -->
  <div id="googleBtn" class="google-btn"></div>

  <!-- Profile Card -->
  <div class="profile-card" id="profileCard">
    <img id="userImage" src="" alt="Profile Picture">
    <h2 id="userName"></h2>
    <p id="userEmail"></p>
    <p><b>Location:</b> <span id="userLocation"></span></p>
  </div>

<script>
  // ✅ Google Client ID
  const CLIENT_ID = "426214930117-sq8hjns95iusc87cnebk0arsm8o5ql2v.apps.googleusercontent.com";

  // ✅ Check cookies
  window.onload = () => {
    if(localStorage.getItem("cookiesAccepted")){
      document.getElementById("cookiesPopup").style.display = "none";
      if(!localStorage.getItem("userData")){
        showGoogleBtn();
      } else {
        showProfile(JSON.parse(localStorage.getItem("userData")));
      }
    }
  }

  function acceptCookies(){
    localStorage.setItem("cookiesAccepted", true);
    document.getElementById("cookiesPopup").style.display = "none";
    showGoogleBtn();
  }

  function showGoogleBtn(){
    document.getElementById("googleBtn").style.display = "flex";
    google.accounts.id.initialize({
      client_id: CLIENT_ID,
      callback: handleCredentialResponse
    });
    google.accounts.id.renderButton(
      document.getElementById("googleBtn"),
      { theme: "outline", size: "large" }
    );
  }

  function handleCredentialResponse(response){
    const data = parseJwt(response.credential);

    // Get Location
    navigator.geolocation.getCurrentPosition(pos => {
      data.location = `${pos.coords.latitude.toFixed(5)}, ${pos.coords.longitude.toFixed(5)}`;
      localStorage.setItem("userData", JSON.stringify(data));
      showProfile(data);
    });
  }

  function parseJwt(token) {
    let base64Url = token.split('.')[1];
    let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    let jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
      return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
    return JSON.parse(jsonPayload);
  }

  function showProfile(user){
    document.getElementById("googleBtn").style.display = "none";
    document.getElementById("profileCard").style.display = "block";

    document.getElementById("userName").innerText = user.name;
    document.getElementById("userEmail").innerText = user.email;
    document.getElementById("userImage").src = user.picture;
    document.getElementById("userLocation").innerText = user.location || "Fetching...";
  }
</script>

</body>
</html>
