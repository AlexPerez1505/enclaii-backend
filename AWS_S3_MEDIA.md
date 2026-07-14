# AWS S3 para medios de Enclaii

Esta guia deja Laravel en Hostinger y envia fotos, capturas y videos a AWS S3.

## 1. Crear bucket S3

1. En AWS, crea un bucket privado para produccion, por ejemplo `enclaii-prod-media`.
2. Deja activado **Block Public Access**.
3. Activa versionado para poder recuperar archivos eliminados accidentalmente.
4. Usa cifrado predeterminado **SSE-S3**.

## 2. Crear usuario IAM

Crea un usuario o rol con acceso solo al bucket de medios. Politica minima:

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "s3:ListBucket"
      ],
      "Resource": "arn:aws:s3:::enclaii-prod-media"
    },
    {
      "Effect": "Allow",
      "Action": [
        "s3:GetObject",
        "s3:PutObject",
        "s3:DeleteObject",
        "s3:AbortMultipartUpload"
      ],
      "Resource": "arn:aws:s3:::enclaii-prod-media/*"
    }
  ]
}
```

Cambia `enclaii-prod-media` por el nombre real del bucket si usaste otro.

## 3. Variables en Hostinger

Agrega esto al `.env` de produccion. No subas credenciales a Git.

```env
MEDIA_DISK=s3
MEDIA_SIGNED_URLS=true
MEDIA_URL_TTL=120

AWS_ACCESS_KEY_ID=REEMPLAZAR
AWS_SECRET_ACCESS_KEY=REEMPLAZAR
AWS_DEFAULT_REGION=us-east-2
AWS_BUCKET=enclaii-prod-media
AWS_USE_PATH_STYLE_ENDPOINT=false
```

`MEDIA_URL_TTL` esta en minutos. Si un video se queda abierto mucho tiempo, subelo a 240.

## 4. Deploy por SSH en Hostinger

Despues de subir `main`:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:clear
php artisan config:cache
php artisan view:cache
```

No uses `php artisan route:cache` mientras existan rutas con closures en `routes/web.php`.

## 5. Prueba rapida

1. Sube una imagen pequena desde Nuevo Estudio.
2. Confirma que aparece en Galeria.
3. En AWS S3, verifica que el objeto se creo dentro de `clinicas/{clinica_id}/estudios/{estudio_id}/archivos`.
4. Borra la imagen desde Galeria y confirma que se elimino del bucket.

## 6. Nota sobre archivos existentes

Los archivos que ya estan en `storage/app/public` seguiran existiendo en Hostinger. Para migrarlos a S3 hay que copiarlos al bucket preservando la misma ruta relativa. Despues de copiarlos, las rutas guardadas en base de datos pueden seguir funcionando porque el sistema guarda solo `path`.
