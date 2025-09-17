<?php
// ==== BACKEND: verify token if submitted ====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $secretKey = "6LcoC8wrAAAAAJRXXjgOigkytPxUSce9tmo2a38t"; // Replace with your v3 Secret Key
    $token     = $_POST['token'];

    $verifyURL = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret'   => $secretKey,
        'response' => $token
    ];

    $options = [
        "http" => [
            "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
            "method"  => "POST",
            "content" => http_build_query($data)
        ]
    ];

    $context  = stream_context_create($options);
    $result   = file_get_contents($verifyURL, false, $context);
    $response = json_decode($result, true);

    header("Content-Type: application/json");
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>reCAPTCHA v3 PHP Demo</title>
    <script src="https://www.google.com/recaptcha/api.js?render="6LcoC8wrAAAAAGyTWrbQUP7rmKWONMYhK-oY2aZR"></script>
    <script>
      function runRecaptcha() {
        grecaptcha.ready(function() {
          grecaptcha.execute('6LcoC8wrAAAAAGyTWrbQUP7rmKWONMYhK-oY2aZR', {action: 'submit'}).then(function(token) {
            // Send token to this same PHP file
            let form = new FormData();
            form.append("token", token);

            fetch("", { method: "POST", body: form })
              .then(res => res.json())
              .then(data => {
                document.getElementById("result").innerText =
                  JSON.stringify(data, null, 2);
              });
          });
        });
      }
    </script>
  </head>
  <body>
    <h2>reCAPTCHA v3 Verification (One PHP File)</h2>
    <button onclick="runRecaptcha()">Verify Human</button>
    <pre id="result"></pre>
  </body>
</html>
