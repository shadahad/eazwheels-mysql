<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>401 Unauthorized</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-4">
        <div class="text-rose-500 font-mono text-5xl font-black">401</div>
        <h1 class="text-xl font-bold">Cryptographic Key Required</h1>
        <p class="text-slate-400 text-xs leading-relaxed">
            Administrative endpoints require a secure matching key. Pass your secret token via <code>X-Admin-Key</code> header or URL parameter <code>?admin_key=...</code>.
        </p>
        <form method="GET" action="/admin" class="mt-4 flex gap-2">
            <input type="password" name="admin_key" placeholder="Enter ADMIN_API_KEY" class="w-full text-xs px-3 py-2 rounded bg-slate-800 border border-slate-700 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <button type="submit" class="bg-indigo-600 px-4 py-2 rounded text-xs font-bold hover:bg-indigo-700">Authenticate</button>
        </form>
    </div>
</body>
</html>