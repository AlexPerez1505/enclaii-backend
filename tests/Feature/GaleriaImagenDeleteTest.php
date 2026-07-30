<?php

namespace Tests\Feature;

use App\Mail\GalleryImageShareMail;
use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GaleriaImagenDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'filesystems.media_disk' => 'public',
            'filesystems.media_signed_urls' => false,
        ]);
    }

    public function test_patient_gallery_groups_media_by_study(): void
    {
        $user = User::create([
            'name' => 'Doctor Prueba',
            'email' => 'doctor-gallery@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-GAL-1',
            'nombre_completo' => 'Paciente Galeria',
        ]);
        $estudioUno = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-GAL-1',
            'tipo' => 'Colonoscopia',
            'fecha' => today()->subDay(),
        ]);
        $estudioDos = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-GAL-2',
            'tipo' => 'Gastroscopia',
            'fecha' => today(),
        ]);

        EstudioArchivo::create([
            'estudio_id' => $estudioUno->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'video',
            'nombre_original' => 'video-estudio-uno.mp4',
            'nombre' => 'video-estudio-uno',
            'path' => 'estudios/uno/video.mp4',
            'capturado_en' => now()->subHours(3),
        ]);
        EstudioArchivo::create([
            'estudio_id' => $estudioUno->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'captura-estudio-uno.jpg',
            'nombre' => 'captura-estudio-uno',
            'path' => 'estudios/uno/captura.jpg',
            'capturado_en' => now()->subHours(2),
        ]);
        EstudioArchivo::create([
            'estudio_id' => $estudioDos->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'captura-estudio-dos.jpg',
            'nombre' => 'captura-estudio-dos',
            'path' => 'estudios/dos/captura.jpg',
            'capturado_en' => now()->subHour(),
        ]);

        $this->actingAs($user)
            ->get(route('galeria.paciente', $paciente->id))
            ->assertOk()
            ->assertSee('Archivos por estudio')
            ->assertSee('Estudio E-GAL-1')
            ->assertSee('Estudio E-GAL-2')
            ->assertSee('video-estudio-uno.mp4')
            ->assertSee('captura-estudio-uno.jpg')
            ->assertSee('captura-estudio-dos.jpg');
    }

    public function test_authenticated_user_can_download_a_gallery_video(): void
    {
        $disk = media_disk();
        Storage::fake($disk);

        $user = User::create([
            'name' => 'Doctor Prueba',
            'email' => 'doctor-video-download@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-VIDEO-1',
            'nombre_completo' => 'Paciente Video',
        ]);
        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-VIDEO-1',
            'fecha' => today(),
        ]);

        $path = "estudios/{$estudio->id}/archivos/video-prueba.webm";
        Storage::disk($disk)->put($path, 'video');
        $video = EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'video',
            'nombre_original' => 'video-prueba.webm',
            'nombre' => 'video-prueba',
            'path' => $path,
            'mime_type' => 'video/webm',
            'size_bytes' => 5,
        ]);

        $this->actingAs($user)
            ->get(route('galeria.video.archivo', $video))
            ->assertOk()
            ->assertHeader('Content-Type', 'video/webm')
            ->assertHeader('Content-Disposition', 'attachment; filename="video-prueba.webm"; filename*=UTF-8\'\'video-prueba.webm');
    }

    public function test_authenticated_user_can_stream_a_gallery_video_inline_with_range(): void
    {
        $disk = media_disk();
        Storage::fake($disk);

        $user = User::create([
            'name' => 'Doctor Stream',
            'email' => 'doctor-video-stream@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-VIDEO-STREAM',
            'nombre_completo' => 'Paciente Video Stream',
        ]);
        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-VIDEO-STREAM',
            'fecha' => today(),
        ]);

        $path = "estudios/{$estudio->id}/archivos/video-stream.webm";
        Storage::disk($disk)->put($path, '0123456789');
        $video = EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'video',
            'nombre_original' => 'video-stream.webm',
            'nombre' => 'video-stream',
            'path' => $path,
            'mime_type' => 'video/webm',
            'size_bytes' => 10,
        ]);

        $response = $this->actingAs($user)
            ->withHeader('Range', 'bytes=2-5')
            ->get(route('galeria.video.stream', $video))
            ->assertStatus(206)
            ->assertHeader('Accept-Ranges', 'bytes')
            ->assertHeader('Content-Range', 'bytes 2-5/10')
            ->assertHeader('Content-Type', 'video/webm')
            ->assertHeader('Content-Disposition', 'inline; filename="video-stream.webm"; filename*=UTF-8\'\'video-stream.webm');

        $this->assertSame('2345', $response->streamedContent());
    }

    public function test_video_editor_uses_same_origin_stream_url_for_capture(): void
    {
        $disk = media_disk();
        Storage::fake($disk);

        $user = User::create([
            'name' => 'Doctor Editor',
            'email' => 'doctor-video-editor@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-VIDEO-EDITOR',
            'nombre_completo' => 'Paciente Video Editor',
        ]);
        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-VIDEO-EDITOR',
            'fecha' => today(),
        ]);

        $path = "estudios/{$estudio->id}/archivos/video-editor.webm";
        Storage::disk($disk)->put($path, 'video');
        $video = EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'video',
            'nombre_original' => 'video-editor.webm',
            'nombre' => 'video-editor',
            'path' => $path,
            'mime_type' => 'video/webm',
            'size_bytes' => 5,
        ]);

        $this->actingAs($user)
            ->get(route('galeria.video.editar', ['id' => $video->id, 'paciente' => $paciente->id]))
            ->assertOk()
            ->assertSee(route('galeria.video.stream', $video, false), false)
            ->assertSee(route('galeria.video.archivo', $video, false), false);
    }

    public function test_authenticated_user_can_delete_a_gallery_image(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Doctor Prueba',
            'email' => 'doctor@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-TEST-1',
            'nombre_completo' => 'Paciente Prueba',
        ]);
        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-TEST-1',
            'fecha' => today(),
        ]);

        $path = "estudios/{$estudio->id}/archivos/prueba.jpg";
        Storage::disk('public')->put($path, 'imagen');
        $imagen = EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'prueba.jpg',
            'nombre' => 'prueba',
            'path' => $path,
        ]);

        $token = $this->actingAs($user)
            ->postJson(route('configuracion.security.authorize'), [
                'scope' => 'studies',
                'current_password' => 'password',
            ])
            ->assertOk()
            ->json('token');

        $this->withHeader('X-Critical-Authorization', $token)
            ->deleteJson(route('galeria.imagen.destroy', $imagen))
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->assertDatabaseMissing('estudio_archivos', ['id' => $imagen->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_authenticated_user_can_email_a_gallery_image_from_gallery_using_the_configured_gmail_sender(): void
    {
        Mail::fake();
        config([
            'mail.from.address' => 'gmail-clinic@example.com',
            'mail.from.name' => 'ENCLAII Gmail',
        ]);

        $disk = media_disk();
        Storage::fake($disk);
        Storage::disk($disk)->put('imagenes/captura-correo.jpg', 'contenido de imagen');

        $user = User::create([
            'name' => 'Dra. Imagen',
            'email' => 'imagen@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-IMG-MAIL',
            'nombre_completo' => 'Paciente Imagen',
            'email' => 'paciente.imagen@example.com',
        ]);
        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-IMG-MAIL',
            'tipo' => 'Endoscopia',
            'fecha' => today(),
        ]);
        $imagen = EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'captura-correo.jpg',
            'nombre' => 'captura-correo.jpg',
            'path' => 'imagenes/captura-correo.jpg',
            'mime_type' => 'image/jpeg',
            'size_bytes' => 128,
            'capturado_en' => now(),
        ]);

        $this->actingAs($user)
            ->postJson(route('galeria.imagen.correo.send', $imagen), [
                'recipients' => 'contacto@example.com, familiar@example.com',
                'subject' => 'Imagen de Endoscopia',
                'message' => 'Te comparto la imagen del estudio.',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Correo enviado correctamente.')
            ->assertJsonPath('sent_to.0', 'contacto@example.com')
            ->assertJsonPath('sent_to.1', 'familiar@example.com');

        Mail::assertSent(GalleryImageShareMail::class, function (GalleryImageShareMail $mail) use ($imagen): bool {
            $mail->build();

            return $mail->archivo->is($imagen)
                && $mail->sender->email === 'imagen@example.com'
                && $mail->subjectLine === 'Imagen de Endoscopia'
                && $mail->hasFrom('gmail-clinic@example.com', 'ENCLAII Gmail')
                && $mail->hasReplyTo('imagen@example.com', 'Dra. Imagen')
                && $mail->hasTo('contacto@example.com')
                && $mail->hasTo('familiar@example.com');
        });
    }

    public function test_gallery_image_page_opens_the_gmail_email_modal_without_the_messages_dashboard(): void
    {
        $disk = media_disk();
        Storage::fake($disk);
        Storage::disk($disk)->put('imagenes/captura-galeria.jpg', 'contenido de imagen');

        $user = User::create([
            'name' => 'Dra. Galeria Imagen',
            'email' => 'galeria-imagen@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-IMG-GAL',
            'nombre_completo' => 'Paciente Imagen Galeria',
            'email' => 'paciente.galeria@example.com',
        ]);
        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-IMG-GAL',
            'tipo' => 'Endoscopia',
            'fecha' => today(),
        ]);
        $imagen = EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'captura-galeria.jpg',
            'nombre' => 'captura-galeria.jpg',
            'path' => 'imagenes/captura-galeria.jpg',
            'mime_type' => 'image/jpeg',
            'size_bytes' => 128,
            'capturado_en' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('galeria.imagen', ['id' => $imagen->id, 'paciente' => $paciente->id]))
            ->assertOk()
            ->assertSee('data-gallery-image-email-open', false)
            ->assertSee('Enviar imagen por Gmail')
            ->assertSee(route('galeria.imagen.correo.send', $imagen, false), false)
            ->assertDontSee('Enviar por WhatsApp')
            ->assertDontSee('canal=whatsapp', false);
    }

    public function test_gallery_image_route_rejects_videos(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Doctor Prueba',
            'email' => 'doctor2@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-TEST-2',
            'nombre_completo' => 'Paciente Prueba',
        ]);
        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-TEST-2',
            'fecha' => today(),
        ]);
        $video = EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'video',
            'nombre_original' => 'prueba.mp4',
            'nombre' => 'prueba',
            'path' => "estudios/{$estudio->id}/archivos/prueba.mp4",
        ]);

        $token = $this->actingAs($user)
            ->postJson(route('configuracion.security.authorize'), [
                'scope' => 'studies',
                'current_password' => 'password',
            ])
            ->assertOk()
            ->json('token');

        $this->withHeader('X-Critical-Authorization', $token)
            ->deleteJson(route('galeria.imagen.destroy', $video))
            ->assertNotFound();

        $this->assertDatabaseHas('estudio_archivos', ['id' => $video->id]);
    }
}
