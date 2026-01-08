<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Downloading PDF...</title>
</head>
<body>
    <p>Your download should start automatically...</p>

    <script>
        (function() {
            const fileName = "{{ $fileName }}";
            const pdfData = "{{ $pdfContent }}";

            const binary = atob(pdfData);
            const len = binary.length;
            const buffer = new Uint8Array(len);
            for (let i = 0; i < len; i++) {
                buffer[i] = binary.charCodeAt(i);
            }

            const blob = new Blob([buffer], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            setTimeout(() => URL.revokeObjectURL(url), 1000);
        })();
    </script>
</body>
</html>
