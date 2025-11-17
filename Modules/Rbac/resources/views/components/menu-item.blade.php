<tr wire:key={{ $menu->id }}>
  <td class="align-self-center">
      <div class="d-flex align-items-center">
          @for ($i = 1; $i < $loop->depth; $i++)
              <div class="align-self-center me-3">
                  &nbsp;&nbsp;&nbsp;&nbsp;
              </div>
          @endfor
          <div class="align-self-center me-3">
              <i class="{{ $menu->icon }} menu-icon"></i>
          </div>
          <span class="{{ $loop->depth === 1 ? 'fw-bold' : 'fw-semibold' }}">
              {{ $menu->label_name_en }}
          </span>
      </div>
  </td>
  <td>{{ $menu->controller_name }}</td>
  <td>{{ $menu->route_name }}</td>
  <td>{{ $menu->url }}</td>
  <td class="text-center">
      <span class="badge @if ($menu->is_active == 1) bg-success @else bg-danger @endif">
          <span wire:loading.remove wire:target="changeStatus('{{ $menu->id }}')">
              @if ($menu->is_active == 1)
                  Active
              @else
                  Inactive
              @endif
          </span>
          <span wire:loading wire:target="changeStatus('{{ $menu->id }}')">
              <i class="fa-solid fa-spinner-third fa-spin" style="--fa-animation-duration: 0.7s;"></i>
          </span>
      </span>
      @can('navigation-status')
          <button class="btn btn-icon btn-sm" wire:click="changeStatus('{{ $menu->id }}')">
              @if ($menu->is_active == 1)
                  <i class="fa-sharp fa-solid fa-toggle-on fa-rotate-270"></i>
              @else
                  <i class="fa-sharp fa-solid fa-toggle-off fa-rotate-270"></i>
              @endif
          </button>
      @endcan
  </td>
  @canany(['sort menu', 'edit menu', 'delete menu'])
      <td class="text-end">
          <div class="d-flex flex-row justify-content-end align-items-center gap-2">
              @can('edit menu')
                  <a href="{{ route('rbac.nav.edit', $menu->id) }}" class="btn btn-icon btn-warning">
                    <i class="icon-base ri ri-edit-2-line icon-22px text-white"></i>
                  </a>
              @endcan
              @can('delete menu')
                  <button type="button" class="btn btn-icon btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-id={{ "$menu->id" }}>
                    <i class="icon-base ri ri-delete-bin-5-line icon-22px text-white"></i>
                  </button>
              @endcan
          </div>
      </td>
  @endcanany
</tr>
{{-- @isset($menu->children)
  @foreach ($menu->children as $child)
      <x-rbac::menu-item :menu="$child" :$loop />
  @endforeach
@endisset --}}
