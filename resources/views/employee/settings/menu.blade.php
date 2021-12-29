<ul class="nav nav-tabs" id="myTab5" role="tablist">
  <li class="nav-item">
    <a href="/employee/setting/{{ $employee->id }}/salary" class="nav-link {{ request()->is('employee/setting/*/salary') ? 'active' : '' }}  border-left-0">Gaji</a>
  </li>
  <li class="nav-item">
    <a href="/employee/setting/{{ $employee->id }}/bpjs" class="nav-link {{ request()->is('employee/setting/*/bpjs') ? 'active' : '' }}  border-left-0">BPJS</a>
  </li>
  <!-- <li class="nav-item">
  </li>
  <li class="nav-item">
    <a class="nav-link border-left-0 active show" id="home-tab-simple" data-toggle="tab" href="#home-simple" role="tab" aria-controls="home" aria-selected="true">BPJS</a>
  </li>
  
                <a class="nav-link" id="profile-tab-simple" data-toggle="tab" href="#profile-simple" role="tab" aria-controls="profile" aria-selected="false">Tab#2</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="contact-tab-simple" data-toggle="tab" href="#contact-simple" role="tab" aria-controls="contact" aria-selected="false">Tab#3</a>
              </li> -->
</ul>