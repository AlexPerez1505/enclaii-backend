@php
  $planUser = $billingUser ?? auth()->user()->billingUser();
  $clinicMembers = $clinicMembers ?? collect([auth()->user()]);
  $clinicInvitations = $clinicInvitations ?? collect();
  $clinicMemberLimit = $clinicMemberLimit ?? auth()->user()->clinicMemberLimit();
  $clinicMemberUsed = $clinicMembers->count() + $clinicInvitations->count();
  $clinicMemberRemaining = max(0, $clinicMemberLimit - $clinicMemberUsed);
  $clinicMemberPercent = min(100, (int) round(($clinicMemberUsed / max(1, $clinicMemberLimit)) * 100));
  $clinicMemberUpgradeOffer = auth()->user()->clinicMemberUpgradeOffer();
  $isClinicOwner = auth()->user()->clinica_rol === 'propietario';
@endphp

@include('configuracion.sections.plan._panel')
@include('configuracion.sections.plan._modal-plan')
@if($isClinicOwner)
  @include('configuracion.sections.plan._modal-invitacion')
@endif
@include('configuracion.sections.plan._styles')
@include('configuracion.sections.plan._scripts')
@include('configuracion.sections.plan._toast')
@include('configuracion.sections.plan._modal-pago')
