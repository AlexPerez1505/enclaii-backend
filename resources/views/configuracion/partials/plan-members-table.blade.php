<table class="gp-table {{ $tableClass ?? '' }}">
  <thead>
    <tr><th>Usuario</th><th>Rol</th><th>Estado</th><th>Último acceso</th><th>Acciones</th></tr>
  </thead>
  <tbody>
    @foreach($clinicMembers as $member)
      @php
        $roleLabels = [
          'propietario' => 'Propietario',
          'administrador' => 'Administrador',
          'medico' => 'Médico',
          'recepcionista' => 'Recepcionista',
          'asistente' => 'Asistente',
        ];
        $lastActivity = $member->connected_sessions_max_last_activity
          ? \Carbon\Carbon::createFromTimestamp($member->connected_sessions_max_last_activity)->diffForHumans()
          : 'Sin acceso reciente';
      @endphp
      <tr>
        <td>
          <span class="gp-u">
            {{ $member->name }}
            @if($member->is(auth()->user()))<span class="gp-you">Tú</span>@endif
          </span>
          <small class="gp-member-email">{{ $member->email }}</small>
        </td>
        <td>{{ $roleLabels[$member->clinica_rol] ?? ucfirst($member->clinica_rol) }}</td>
        <td><span class="gp-st">Activo</span></td>
        <td>{{ $member->is(auth()->user()) ? 'Ahora' : $lastActivity }}</td>
        <td>
          @if($isClinicOwner && !$member->is(auth()->user()) && $member->clinica_rol !== 'propietario')
            <button type="button" class="gp-member-remove" data-member-id="{{ $member->id }}" data-member-name="{{ $member->name }}">Retirar</button>
          @else
            <span class="gp-no-action">—</span>
          @endif
        </td>
      </tr>
    @endforeach

    @foreach($clinicInvitations as $invitation)
      <tr>
        <td>
          <span class="gp-u">{{ $invitation->email }}</span>
          <small class="gp-member-email">Correo autorizado para crear cuenta</small>
        </td>
        <td>{{ $roleLabels[$invitation->rol] ?? ucfirst($invitation->rol) }}</td>
        <td><span class="gp-st pending">Pendiente</span></td>
        <td>Esperando registro</td>
        <td>
          @if($isClinicOwner)
            <button type="button" class="gp-invite-revoke" data-invitation-id="{{ $invitation->id }}">Cancelar</button>
          @else
            <span class="gp-no-action">—</span>
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
