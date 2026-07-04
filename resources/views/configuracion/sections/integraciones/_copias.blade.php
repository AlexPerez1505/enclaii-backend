{{-- Acciones superiores --}}
<div class="int-actions">
  @php
    $configurationBackups = $configurationBackups ?? collect();
    $latestConfigurationBackup = $configurationBackups->first();
  @endphp

  <article class="card int-act int-backup-center rise d2">
    <div class="int-backup-head">
      <div class="ia-top">
        <span class="ia-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></span>
        <div>
          <div class="ia-t">Copias de configuración</div>
          <div class="ia-d">Protege tus preferencias y datos profesionales sin incluir información clínica ni credenciales.</div>
        </div>
      </div>
      <button type="button" class="int-backup-create" id="intBackupOpen">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Crear copia
      </button>
    </div>

    <div class="int-backup-summary">
      <div class="int-backup-summary-main">
        <span class="int-backup-ok">
          @if($latestConfigurationBackup)
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
          @else
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          @endif
        </span>
        <div>
          <strong>{{ $latestConfigurationBackup ? 'Última copia completada' : 'Todavía no hay copias' }}</strong>
          <span>
            {{ $latestConfigurationBackup
              ? format_user_date_time($latestConfigurationBackup->created_at)
              : 'Crea la primera para proteger tu configuración actual.' }}
          </span>
        </div>
      </div>
      <span class="int-backup-count">{{ $configurationBackups->count() }} {{ $configurationBackups->count() === 1 ? 'copia' : 'copias' }}</span>
    </div>

    <div class="int-backup-list-head">
      <strong>Historial reciente</strong>
      <span>Hasta 10 registros visibles</span>
    </div>

    <div class="int-backup-list">
      @forelse($configurationBackups as $backup)
        @php
          $backupSize = $backup->size < 1024
            ? $backup->size.' B'
            : number_format($backup->size / 1024, 1).' KB';
        @endphp
        <div class="int-backup-row" data-backup-row="{{ $backup->id }}">
          <div class="int-backup-info">
            <span class="int-backup-file {{ $backup->type === 'automatic' ? 'auto' : '' }}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><polyline points="9 15 11 17 15 13"/></svg>
            </span>
            <div style="min-width:0">
              <div class="int-backup-name">{{ $backup->name }}</div>
              <div class="int-backup-meta">
                {{ format_user_date_time($backup->created_at) }} · {{ $backupSize }}
                · {{ $backup->type === 'automatic' ? 'Automática' : 'Manual' }}
              </div>
            </div>
          </div>
          <div class="int-backup-actions">
            <button type="button" class="int-backup-action restore" data-backup-restore="{{ $backup->id }}" data-backup-name="{{ $backup->name }}" title="Restaurar">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.5 15a9 9 0 1 0 .5-5"/></svg>
            </button>
            <a class="int-backup-action" href="{{ route('configuracion.backups.download', $backup->id) }}" title="Descargar">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </a>
            <button type="button" class="int-backup-action delete" data-backup-delete="{{ $backup->id }}" data-backup-name="{{ $backup->name }}" title="Eliminar">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
          </div>
        </div>
      @empty
        <div class="int-backup-empty">
          Cuando crees una copia aparecerá aquí con opciones para restaurarla, descargarla o eliminarla.
        </div>
      @endforelse
    </div>
  </article>
</div>
