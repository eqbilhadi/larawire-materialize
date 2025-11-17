<div>
    <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">
                       Sort Navigation
                    </h5>
                    <h6 class="card-subtitle text-muted fw-light">
                        Form to sort a navigation
                    </h6>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('rbac.nav.index') }}" class="btn btn-primary">
                        <i class="ri ri-arrow-left-circle-line me-sm-1 icon-20px"></i>
                        <span class="d-none d-sm-inline align-self-center">
                            Back
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body border-top">
            <form wire:submit.prevent="save">
                <ul class="list-group list-group-flush parent-zone" id="menu-parent-list" wire:ignore>
                    @foreach ($this->lists as $menu)
                        <li class="list-group-item parent-item" data-id="{{ $menu['id'] }}">
                            {{-- parent row --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="d-flex align-items-center gap-2 fs-5">
                                    <i class="drag-handle-parent cursor-move ri ri-menu-line icon-20px"></i>
                                    {{ $menu['label_name_en'] }}
                                </span>
                                <i class="{{ $menu['icon'] }} icon-20px"></i>
                            </div>

                            {{-- CHILDREN --}}
                            <ul class="list-group mt-2 ms-4 child-zone" data-parent="{{ $menu['id'] }}">
                                @foreach ($menu['children'] as $child)
                                    <li class="list-group-item child-item py-3" data-id="{{ $child['id'] }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="d-flex align-items-center gap-2 fs-5">
                                                <i class="drag-handle-child cursor-move ri ri-menu-line icon-20px"></i>
                                                {{ $child['label_name_en'] }}
                                            </span>
                                            <i class="{{ $child['icon'] }} icon-20px"></i>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach

                </ul>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary mb-3 mt-5">
                        Save Order
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('assets/vendor/libs/sortablejs/sortable.js') }}"></script>
        <script>
            document.addEventListener('livewire:initialized', function () {
                // ========================================================
                // FUNCTION: update Livewire state dari DOM
                // ========================================================
                function updateMenuStructure() {
                    let parentOrder = [];
                    let childOrders = {};

                    document.querySelectorAll('.parent-zone > li.parent-item').forEach(parent => {
                        let pid = parent.dataset.id;
                        parentOrder.push(pid);

                        let childList = parent.querySelector('.child-zone');
                        if (childList) {
                            let children = [...childList.children]
                                .filter(ch => ch.dataset.id)
                                .map(ch => ch.dataset.id);
                            childOrders[pid] = children;
                        }
                    });

                    @this.set('parentOrder', parentOrder);
                    @this.set('childOrders', childOrders);
                }

                // ========================================================
                // FUNCTION: pastikan parent punya child-zone
                // ========================================================
                function ensureChildZone(item) {
                    // hanya buat child-zone jika item masih level 1 (parent)
                    if (item.classList.contains('parent-item') && !item.querySelector('.child-zone')) {
                        let ul = document.createElement('ul');
                        ul.classList.add('list-group', 'mt-2', 'ms-4', 'child-zone');
                        ul.setAttribute('data-parent', item.dataset.id);
                        item.appendChild(ul);

                        // init SortableJS untuk child-zone baru
                        new Sortable(ul, {
                            group: 'menu-level',
                            animation: 150,
                            handle: '.drag-handle-parent, .drag-handle-child',
                            onAdd: function(evt) {
                                let moved = evt.item;

                                // ketika item dipindah ke child-zone, pastikan hanya level 2
                                if (moved.classList.contains('parent-item')) {
                                    moved.classList.remove('parent-item');
                                    moved.classList.add('child-item');
                                }

                                // level 2 tidak boleh punya child-zone
                                if (moved.querySelector('.child-zone')) {
                                    moved.removeChild(moved.querySelector('.child-zone'));
                                }

                                updateMenuStructure();
                            },
                            onEnd: function() {
                                updateMenuStructure();
                            }
                        });
                    }
                }


                // ========================================================
                // PARENT ZONE SORTABLE
                // ========================================================
                new Sortable(document.getElementById('menu-parent-list'), {
                    group: 'menu-level',
                    animation: 150,
                    handle: '.drag-handle-parent, .drag-handle-child',
                    onAdd: function(evt) {
                        let item = evt.item;

                        // Jika child naik jadi parent
                        if (evt.to.classList.contains('parent-zone')) {
                            item.classList.remove('child-item');
                            item.classList.add('parent-item');

                            // pastikan child-zone ada
                            ensureChildZone(item);
                        }

                        updateMenuStructure();
                    },
                    onEnd: function() {
                        updateMenuStructure();
                    }
                });

                // ========================================================
                // CHILD ZONES SORTABLE
                // ========================================================
                function initChildZones() {
                    document.querySelectorAll('.child-zone').forEach(zone => {
                        // pastikan SortableJS hanya diinit sekali
                        if (!zone.dataset.sortable) {
                            new Sortable(zone, {
                                group: 'menu-level',
                                animation: 150,
                                handle: '.drag-handle-parent, .drag-handle-child',
                                onAdd: function(evt) {
                                    let item = evt.item;
                                    // Jika parent turun jadi child
                                    if (evt.to.classList.contains('child-zone')) {
                                        item.classList.remove('parent-item');
                                        item.classList.add('child-item');
                                    }
                                    updateMenuStructure();
                                },
                                onEnd: function() {
                                    updateMenuStructure();
                                }
                            });
                            zone.dataset.sortable = true;
                        }
                    });
                }

                // Init semua child-zone awal
                initChildZones();
            });
        </script>
    @endpush
</div>
