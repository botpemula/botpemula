<?php
// Fungsi untuk meminify kode
function minifyCode($code, $type = 'html') {
    if ($type === 'html') {
        $code = preg_replace('/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s', '', $code);
    } elseif ($type === 'css') {
        $code = preg_replace('/\/\*.*?\*\/|[\t\r\n]|[ ]{2,}/s', '', $code);
        $code = str_replace([' {', ' }', ' ;', '; ', ' :', ': '], ['{', '}', ';', ';', ':', ':'], $code);
    } elseif ($type === 'js') {
        $code = preg_replace('/\/\/.*?$|\/\*.*?\*\/|[\t\r\n]/ms', '', $code);
        $code = str_replace([' {', ' }', ' ;', '; ', ' :', ': ', ' =', '= '], ['{', '}', ';', ';', ':', ':', '=', '='], $code);
    } elseif ($type === 'python') {
        $code = preg_replace('/#.*?$/m', '', $code);
        $code = preg_replace('/\s+/s', ' ', $code);
    } elseif ($type === 'ruby') {
        $code = preg_replace('/#.*?$/m', '', $code);
        $code = preg_replace('/\s+/s', ' ', $code);
    } elseif ($type === 'php') {
        $code = preg_replace('/\/\/.*?$|\/\*.*?\*\/|#.*?$/ms', '', $code);
        $code = preg_replace('/\s+/s', ' ', $code);
    } elseif ($type === 'java') {
        $code = preg_replace('/\/\/.*?$|\/\*.*?\*\/|#.*?$/ms', '', $code);
        $code = preg_replace('/\s+/s', ' ', $code);
    } elseif ($type === 'c') {
        $code = preg_replace('/\/\/.*?$|\/\*.*?\*\/|#.*?$/ms', '', $code);
        $code = preg_replace('/\s+/s', ' ', $code);
    }
    return trim($code);
}

// Proses upload dan minify
$minifiedCode = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? 'html';
    $code = $_POST['code'] ?? '';

    if (!empty($code)) {
        $minifiedCode = minifyCode($code, $type);
    } else {
        $error = 'Kode tidak boleh kosong!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Minify</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #121212;
            color: #e0e0e0;
        }
        h1 {
            text-align: center;
            color: #ffffff;
        }
        form {
            max-width: 600px;
            margin: auto;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        textarea, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #2c2c2c;
            color: #e0e0e0;
            border: 1px solid #444;
            border-radius: 5px;
        }
        button:hover {
            background-color: #3d3d3d;
        }
        .output {
            margin-top: 20px;
            background: #1b5e20;
            color: #e0e0e0;
            padding: 15px;
            border-radius: 8px;
            overflow: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .error {
            color: #ff5252;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
        }
        .button-container button {
            width: 48%;
        }
    </style>
</head>
<body>
    <h1>Minify Code Botpemula</h1>
    <form method="post">
        <label for="code">Masukkan kode:</label>
        <textarea name="code" id="code" placeholder="Masukkan kode HTML, CSS, JavaScript, Python, Ruby, PHP, Java, C..."></textarea>
        <label for="type">Pilih jenis kode:</label>
        <select name="type" id="type">
            <option value="html">HTML</option>
            <option value="css">CSS</option>
            <option value="js">JavaScript</option>
            <option value="python">Python</option>
            <option value="ruby">Ruby</option>
            <option value="php">PHP</option>
            <option value="java">Java</option>
            <option value="c">C</option>
        </select>
        <button type="submit">Minify</button>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </form>

    <?php if (!empty($minifiedCode)): ?>
        <div class="output">
            <strong>Result Minify:</strong>
            <pre class="language-<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($minifiedCode) ?></pre>
        </div>
        <div class="button-container">
            <button onclick="copyToClipboard()">Copy</button>
            <button onclick="downloadFile()">Download</button>
        </div>
    <?php endif; ?>

    <script>
        function copyToClipboard() {
            const code = document.querySelector('.output pre').textContent;
            navigator.clipboard.writeText(code).then(() => {
                alert("Code copied to clipboard!");
            }).catch(err => {
                alert("Failed to copy text: " + err);
            });
        }

        function downloadFile() {
            const code = document.querySelector('.output pre').textContent;
            const blob = new Blob([code], { type: 'text/plain' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'minified_code.txt';
            link.click();
        }
    </script>
</body>
</html>
