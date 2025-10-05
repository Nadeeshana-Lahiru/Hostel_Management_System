<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->title }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        h1 { color: #4e73df; font-size: 24px; }
        p { margin: 0; }
        .content { white-space: pre-wrap; font-size: 16px; } 
        hr { border: 0; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $announcement->title }}</h1>
        <hr>
        <p class="content">{{ $announcement->body }}</p>
    </div>
</body>
</html>