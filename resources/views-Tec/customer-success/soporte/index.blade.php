@extends('layouts.app')
@section('title', 'Soporte — Chats')
@section('active', 'customer-success-soporte')
@section('header-title', 'Soporte')
@section('header-sub', 'Chats pendientes y activos de usuarios.')

@section('sidebar')
  @include('customer-success.partials.sidebar')
@endsection

@push('styles')
<style>
.ag-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px}
.ag-card{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:0;overflow:hidden}
.ag-card-header{padding:16px 20px;border-bottom:1px solid var(--stroke);display:flex;align-items:center;justify-content:space-between}
.ag-card-header h2{font-size:15px;font-weight:700;margin:0;display:flex;align-items:center;gap:8px}
.ag-badge{display:inline-flex;align-items:center;justify-content:center;min-width:20px;height:20px;border-radius:99px;font-size:11px;font-weight:700;padding:0 5px}
.ag-badge.pending{background:rgba(251,191,36,.2);color:#f59e0b}
.ag-badge.active{background:rgba(74,222,128,.15);color:#16a34a}
.ag-empty{padding:32px 20px;text-align:center;color:var(--txt-soft);font-size:13px}
.ag-conv-item{display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--stroke);transition:background .15s;cursor:pointer;text-decoration:none;color:inherit}
.ag-conv-item:last-child{border-bottom:0}
.ag-conv-item:hover{background:rgba(110,160,255,.05)}
.ag-conv-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:grid;place-items:center;flex-shrink:0;font-size:13px;font-weight:700;color:#fff}
.ag-conv-info{flex:1;min-width:0}
.ag-conv-info strong{display:block;font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ag-conv-info span{display:block;font-size:12px;color:var(--txt-soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ag-conv-time{font-size:11px;color:var(--txt-soft);flex-shrink:0}
</style>
@endpush

@section('content')
<div class="ag-grid">

  {{-- Pendientes --}}
  <div class="ag-card">
    <div class="ag-card-header">
      <h2>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Pendientes
        <span class="ag-badge pending" id="badgePending">{{ $pending->count() }}</span>
      </h2>
    </div>

    @if($pending->isEmpty())
      <div class="ag-empty">No hay chats pendientes 🎉</div>
    @else
      @foreach($pending as $conv)
        <a class="ag-conv-item" href="{{ route('customer-success.soporte.chat', $conv) }}">
          <div class="ag-conv-avatar">{{ mb_strtoupper(mb_substr($conv->user?->name ?? '?', 0, 1)) }}</div>
          <div class="ag-conv-info">
            <strong>{{ $conv->user?->name }} {{ $conv->user?->apellido_paterno }}</strong>
            <span>{{ $conv->latestMessage?->content ?? $conv->title }}</span>
          </div>
          <span class="ag-conv-time">{{ $conv->last_message_at?->diffForHumans() }}</span>
        </a>
      @endforeach
    @endif
  </div>

  {{-- Activos (míos) --}}
  <div class="ag-card">
    <div class="ag-card-header">
      <h2>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Mis chats activos
        <span class="ag-badge active">{{ $active->count() }}</span>
      </h2>
    </div>

    @if($active->isEmpty())
      <div class="ag-empty">No tienes chats activos</div>
    @else
      @foreach($active as $conv)
        <a class="ag-conv-item" href="{{ route('customer-success.soporte.chat', $conv) }}">
          <div class="ag-conv-avatar">{{ mb_strtoupper(mb_substr($conv->user?->name ?? '?', 0, 1)) }}</div>
          <div class="ag-conv-info">
            <strong>{{ $conv->user?->name }} {{ $conv->user?->apellido_paterno }}</strong>
            <span>{{ $conv->latestMessage?->content ?? $conv->title }}</span>
          </div>
          <span class="ag-conv-time">{{ $conv->last_message_at?->diffForHumans() }}</span>
        </a>
      @endforeach
    @endif
  </div>

</div>
@endsection

@push('scripts')
<script>
(function(){
  var pendingList  = document.querySelector('.ag-card:first-child');
  var badgePending = document.getElementById('badgePending');
  var pollUrl      = "{{ route('customer-success.api.soporte.pending') }}";
  var prevCount    = {{ $pending->count() }};

  function renderItem(conv){
    var initial = conv.user_name ? conv.user_name.trim().charAt(0).toUpperCase() : '?';
    var url = '/customer-success/soporte/' + conv.id;
    return '<a class="ag-conv-item" href="' + url + '">'
      + '<div class="ag-conv-avatar">' + initial + '</div>'
      + '<div class="ag-conv-info">'
      + '<strong>' + (conv.user_name || 'Usuario') + '</strong>'
      + '<span>' + (conv.last_message || conv.title || '') + '</span>'
      + '</div>'
      + '<span class="ag-conv-time">' + (conv.last_message_at || '') + '</span>'
      + '</a>';
  }

  setInterval(async function(){
    try {
      var r    = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
      var data = await r.json();
      if (!data.ok) return;

      var convs  = data.conversations;
      var count  = convs.length;

      badgePending.textContent = count;

      var existingItems = pendingList.querySelectorAll('.ag-conv-item');
      existingItems.forEach(function(el){ el.remove(); });

      var emptyEl = pendingList.querySelector('.ag-empty');
      if(emptyEl) emptyEl.remove();

      if(count === 0){
        var empty = document.createElement('div');
        empty.className = 'ag-empty';
        empty.textContent = 'No hay chats pendientes 🎉';
        pendingList.appendChild(empty);
      } else {
        convs.forEach(function(conv){
          pendingList.insertAdjacentHTML('beforeend', renderItem(conv));
        });
      }

      if(count > prevCount){
        badgePending.style.transform = 'scale(1.4)';
        setTimeout(function(){ badgePending.style.transform = ''; }, 400);
        try {
          var ctx = new (window.AudioContext || window.webkitAudioContext)();
          var osc = ctx.createOscillator();
          var gain = ctx.createGain();
          osc.connect(gain); gain.connect(ctx.destination);
          osc.frequency.value = 880;
          gain.gain.setValueAtTime(0.15, ctx.currentTime);
          gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
          osc.start(); osc.stop(ctx.currentTime + 0.3);
        } catch(e){}
      }

      prevCount = count;
    } catch(e){}
  }, 5000);
})();
</script>
@endpush
