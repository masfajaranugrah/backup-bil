<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f172a">
    <title>Offline</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #172033;
            background: #f5f7fb;
        }

        main {
            width: 100%;
            max-width: 440px;
            padding: 28px;
            border: 1px solid #d9e0eb;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
        }

        h1 {
            margin: 0 0 10px;
            font-size: 24px;
            line-height: 1.25;
        }

        p {
            margin: 0 0 20px;
            color: #5b667a;
            line-height: 1.6;
        }

        button {
            width: 100%;
            border: 0;
            border-radius: 6px;
            padding: 12px 16px;
            color: #ffffff;
            background: #2563eb;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <main>
        <h1>Koneksi terputus</h1>
        <p>Halaman ini tidak tersedia saat offline. Periksa koneksi internet Anda, lalu coba muat ulang.</p>
        <button type="button" onclick="window.location.reload()">Muat ulang</button>
    </main>
</body>
</html>
