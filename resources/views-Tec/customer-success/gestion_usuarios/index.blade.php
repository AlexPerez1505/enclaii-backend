@extends('layouts.app')

@section('title', 'Gestión de usuarios - Customer Success')
@section('active', 'customer-success-usuarios')
@section('header-title', 'Customer Success')
@section('header-sub')
  Gestión de usuarios y roles
@endsection

@section('sidebar')
  @include('customer-success.partials.sidebar')
@endsection

@section('bottom-nav')
  @include('customer-success.partials.bottom-nav')
@endsection

@push('styles')
<style>
.cs-shell{max-width:900px}
.cs-card{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:16px;
  padding:24px;margin-bottom:20px;
}
.cs-card-title{font-size:16px;font-weight:700;color:var(--txt);margin-bottom:16px}
.cs-form{display:grid;gap:14px}
.cs-field{display:grid;gap:6px}
.cs-label{font-size:12px;font-weight:600;color:var(--txt-soft)}
.cs-input{
  width:100%;background:var(--panel);border:1px solid var(--stroke-strong);border-radius:10px;
  padding:10px 12px;color:var(--txt);font-size:13px;outline:none;box-sizing:border-box;
}
.cs-input:focus{border-color:var(--blue)}
.cs-row{display:flex;gap:12px;align-items:flex-end}
.cs-btn{
  display:inline-flex;align-items:center;gap:8px;height:40px;padding:0 18px;
  border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:all 150ms;
  border:none;text-decoration:none;
}
.cs-btn-primary{background:var(--blue);color:#fff}
.cs-btn-primary:hover{background:var(--cyan)}
.cs-btn-danger{background:rgba(220,38,38,.12);color:#ef4444}
.cs-btn-danger:hover{background:rgba(220,38,38,.2)}
.cs-table{width:100%;border-collapse:collapse}
.cs-table th,.cs-table td{padding:12px;text-align:left;font-size:13px;border-bottom:1px solid var(--stroke)}
.cs-table th{color:var(--txt-soft);font-weight:600}
.cs-table td{color:var(--txt)}
.cs-empty{text-align:center;padding:40px;color:var(--txt-soft)}
.cs-alert{padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:13px;display:none}
.cs-alert.success{background:rgba(34,197,94,.12);color:#22c55e}
.cs-alert.error{background:rgba(220,38,38,.12);color:#ef4444}
</style>
@endpush

@section('content')
<div class="cs-shell rise d1">

  <div class="cs-card">
    <div class="cs-card-title">Gestión de roles</div>
    <div class="cs-alert" id="csRoleAlert"></div>
    <div class="cs-form" id="csRoleForm">
      <div class="cs-field">
        <label class="cs-label">Usuario</label>
        <select class="cs-input" id="csUserSelect">
          <option value="">Selecciona un usuario</option>
        </select>
      </div>
      <div class="cs-row">
        <button class="cs-btn cs-btn-primary" type="button" id="csAssignRole">Asignar rol Customer Success</button>
        <button class="cs-btn cs-btn-danger" type="button" id="csRemoveRole">Quitar rol Customer Success</button>
      </div>
    </div>
  </div>

  <div class="cs-card">
    <div class="cs-card-title">Usuarios del sistema</div>
    <table class="cs-table" id="csUsersTable">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Email</th>
          <th>Roles</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

</div>
@endsection

@push('scripts')
<script>
(function(){
  const roleAlert = document.getElementById('csRoleAlert');
  const userSelect = document.getElementById('csUserSelect');
  const usersTableBody = document.querySelector('#csUsersTable tbody');

  function showRoleAlert(msg, type){
    roleAlert.textContent = msg;
    roleAlert.className = 'cs-alert ' + type;
    roleAlert.style.display = 'block';
    setTimeout(() => { roleAlert.style.display = 'none'; }, 4000);
  }

  async function loadUsers(){
    try {
      const res = await fetch('/api/customer-success/users', {
        headers: { 'Accept': 'application/json' },
      });
      if (!res.ok) throw new Error('Error al cargar usuarios');
      const users = await res.json();

      userSelect.innerHTML = '<option value="">Selecciona un usuario</option>';
      usersTableBody.innerHTML = '';

      users.forEach(user => {
        const option = document.createElement('option');
        option.value = user.id;
        option.textContent = user.name + ' (' + user.email + ')';
        userSelect.appendChild(option);

        const roles = user.roles.map(r => r.name).join(', ') || '—';
        const tr = document.createElement('tr');
        tr.innerHTML = '<td>' + user.name + '</td><td>' + user.email + '</td><td>' + roles + '</td>';
        usersTableBody.appendChild(tr);
      });
    } catch (err) {
      showRoleAlert('Error al cargar usuarios.', 'error');
    }
  }

  async function changeRole(action){
    const userId = userSelect.value;
    if (!userId) {
      showRoleAlert('Selecciona un usuario primero.', 'error');
      return;
    }

    const url = '/api/customer-success/users/' + userId + '/' + action;
    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ role: 'Customer Success' }),
      });

      const data = await res.json();
      if (res.ok) {
        showRoleAlert(data.message, 'success');
        loadUsers();
      } else {
        showRoleAlert(data.message || 'Error al cambiar el rol.', 'error');
      }
    } catch (err) {
      showRoleAlert('Error de conexión.', 'error');
    }
  }

  document.getElementById('csAssignRole').addEventListener('click', () => changeRole('assign-role'));
  document.getElementById('csRemoveRole').addEventListener('click', () => changeRole('remove-role'));

  loadUsers();
})();
</script>
@endpush
