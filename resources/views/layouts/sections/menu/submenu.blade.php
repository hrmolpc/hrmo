<ul class="menu-sub">
  @if (isset($menu))
    @foreach ($menu as $submenu)
      @php
        $role = Auth::user()->role; // Ensure you have the user's role here
        // Check if the submenu should be displayed based on the user's role
        $canDisplay = $role === 'Admin' || (isset($submenu->role) && in_array($role, $submenu->role));
      @endphp

      @if ($canDisplay)
        {{-- Active menu method --}}
        @php
          $activeClass = '';
          $active = $configData["layout"] === 'vertical' ? 'active open' : 'active';
          $currentRouteName = Route::currentRouteName();

          if ($currentRouteName === $submenu->slug) {
              $activeClass = 'active';
          } elseif (isset($submenu->submenu)) {
              // Check for active state in submenus
              if (is_array($submenu->slug)) {
                  foreach ($submenu->slug as $slug) {
                      if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                          $activeClass = $active;
                          break; // Break out of the loop if we found an active submenu
                      }
                  }
              } else {
                  if (str_contains($currentRouteName, $submenu->slug) && strpos($currentRouteName, $submenu->slug) === 0) {
                      $activeClass = $active;
                  }
              }
          }
        @endphp

        <li class="menu-item {{ $activeClass }}">
          <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0)' }}" class="{{ isset($submenu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($submenu->target) && !empty($submenu->target)) target="_blank" @endif>
            @if (isset($submenu->icon))
              <i class="{{ $submenu->icon }}"></i>
            @endif
            <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>
          </a>

          {{-- Submenu --}}
          @if (isset($submenu->submenu))
            @include('layouts.sections.menu.submenu', ['menu' => $submenu->submenu])
          @endif
        </li>
      @endif
    @endforeach
  @endif
</ul>
