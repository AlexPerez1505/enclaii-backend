<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=20260627-2">
<title>Perfil del Paciente</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f4f7fb;
}

.header{
    background:#0ea5e9;
    color:white;
    padding:20px;
    text-align:center;
}

.container{
    max-width:1100px;
    margin:30px auto;
    padding:20px;
}

.profile-card{
    background:white;
    border-radius:15px;
    padding:25px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
    display:flex;
    gap:30px;
    align-items:center;
}

.avatar{
    width:130px;
    height:130px;
    border-radius:50%;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:50px;
}

.info h2{
    color:#0ea5e9;
    margin-bottom:10px;
}

.info p{
    margin:6px 0;
}

.cards{
    margin-top:30px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.card h3{
    color:#0ea5e9;
    margin-bottom:10px;
}
</style>

</head>
<body>

<div class="header">
    <h1>Perfil del Paciente</h1>
</div>

<div class="container">

    <div class="profile-card">

        <div class="avatar">
            👤
        </div>

        <div class="info">
            <h2>Paulina Rocha</h2>

            <p><strong>Correo:</strong> paulina@email.com</p>
            <p><strong>Teléfono:</strong> 246-123-4567</p>
            <p><strong>Fecha de nacimiento:</strong> 15/08/2004</p>
            <p><strong>Género:</strong> Femenino</p>
        </div>

    </div>

    <div class="cards">

        <div class="card">
            <h3>📅 Próxima Cita</h3>
            <p>20 Junio 2026</p>
            <p>10:00 AM</p>
        </div>

        <div class="card">
            <h3>📋 Historial Médico</h3>
            <p>3 consultas registradas</p>
        </div>

        <div class="card">
            <h3>💊 Tratamiento</h3>
            <p>En seguimiento</p>
        </div>

    </div>

</div>

</body>
</html>
