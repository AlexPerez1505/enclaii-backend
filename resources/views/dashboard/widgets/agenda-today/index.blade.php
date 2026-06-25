{{-- Widget: Agenda --}}
<div class="widget rise d4" data-widget-id="agenda-today" data-w="3">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card" style="cursor:pointer" onclick="window.location.href='{{ route('agendar') }}'">
    <h3>AGENDAR DÍA</h3>
    <div class="cal-head">
      <span>Junio 2026</span>
      <span class="arrows"><button aria-label="Mes anterior">‹</button><button aria-label="Mes siguiente">›</button></span>
    </div>
    <table class="cal">
      <thead>
        <tr><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th></tr>
      </thead>
      <tbody>
        <tr><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td class="today">6</td><td>7</td></tr>
        <tr><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td><td>13</td><td>14</td></tr>
        <tr><td>15</td><td>16</td><td>17</td><td>18</td><td>19</td><td>20</td><td>21</td></tr>
        <tr><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td><td>28</td></tr>
        <tr><td>29</td><td>30</td><td class="off">1</td><td class="off">2</td><td class="off">3</td><td class="off">4</td><td class="off">5</td></tr>
      </tbody>
    </table>
  </article>
  <span class="widget-resize-handle"></span>
</div>
