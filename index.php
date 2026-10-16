
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Sharing</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #f9fafb;
            background: #111827;
        }

        .card {
            width: 100%;
            max-width: 420px;
            padding: 30px;
            border-radius: 16px;
            background: #1f2937;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .25);
        }

        .icon {
            font-size: 48px;
        }

        h1 {
            font-size: 25px;
        }

        p {
            color: #d1d5db;
            line-height: 1.6;
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 12px;
            border: 0;
            border-radius: 9px;
            color: #082f49;
            background: #38bdf8;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        button:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        #status {
            margin-top: 20px;
            font-size: 14px;
            overflow-wrap: anywhere;
            white-space: pre-line;
        }

        .note {
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>

<body>
    <main class="card">
        <div class="icon">📍</div>

        <h1>Location Sharing</h1>

        <p>
            Share your current location with your consent.
            Your coordinates will be saved after you allow
            your browser to access your location.
        </p>

        <button id="shareButton" type="button">
            Share My Location
        </button>

        <p id="status" role="status" aria-live="polite">
            Ready to share your location.
        </p>

        <p class="note">
            Your browser must have location access enabled.
        </p>
    </main>

    <script src="script.js"></script>
</body>
</html>
