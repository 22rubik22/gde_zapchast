<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Счет на оплату</title>
</head>
<body>
    <h1>Счет на оплату</h1>

    @if(isset($error))
        <p>{{ $error }}</p>
    @elseif(isset($invoiceData))
        <pre>{{ json_encode($invoiceData, JSON_PRETTY_PRINT) }}</pre>
    @else
        <p>Ошибка при получении счета.</p>
    @endif
</body>
</html>