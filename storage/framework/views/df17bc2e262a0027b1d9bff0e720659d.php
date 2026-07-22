<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Código de verificación</title>
</head>
<body>
    <h1>Tu código de verificación</h1>
    <p>Hola <?php echo e($user->name); ?>,</p>
    <p>Usa este código para completar tu acceso:</p>
    <h2 style="font-size:32px; letter-spacing:4px;"><?php echo e($code); ?></h2>
    <p>Este código expira en 5 minutos.</p>
    <p>Si no solicitaste este código, ignóralo.</p>
</body>
</html><?php /**PATH C:\Users\HP\enclaii-backend\resources\views/emails/two-factor-code.blade.php ENDPATH**/ ?>