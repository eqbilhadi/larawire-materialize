<div>
    {{-- Card --}}
    <div class="card sort-card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">Sort Navigation</h5>
                    <h6 class="card-subtitle text-muted fw-light">Form to sort a navigation</h6>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('rbac.nav.index') }}" class="btn btn-outline-primary">
                        <i class="ri ri-arrow-left-circle-line me-sm-1 icon-20px"></i>
                        <span class="d-none d-sm-inline align-self-center">Back</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body border-top">
            <form wire:submit.prevent="save">
                {{-- Parent list (wire:ignore so Sortable manages DOM) --}}
                <ul class="list-group list-group-flush parent-zone" id="menu-parent-list" wire:ignore>
                    @foreach ($this->lists as $menu)
                        <li class="list-group-item parent-item" data-id="{{ $menu['id'] }}">
                            {{-- parent row --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="drag-wrapper d-flex align-items-center gap-2">
                                        <i class="drag-handle-parent ri ri-drag-move-2-line cursor-move"></i>
                                        <i class="{{ $menu['icon'] }} menu-icon ms-5"></i>
                                        <div class="item-label">
                                            <div class="fw-semibold">{{ $menu['label_name_en'] }}</div>
                                            <small class="text-muted d-block">{{ $menu['controller_name'] ?? '' }}</small>
                                        </div>
                                    </span>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary rounded-pill">Parent</span>
                                </div>
                            </div>

                            {{-- CHILDREN --}}
                            <ul class="list-group mt-3 ms-4 child-zone" data-parent="{{ $menu['id'] }}">
                                @foreach ($menu['children'] as $child)
                                    <li class="list-group-item child-item py-2" data-id="{{ $child['id'] }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="drag-handle-child ri ri-drag-move-2-line cursor-move"></i>
                                                <i class="{{ $child['icon'] }} menu-icon ms-5"></i>
                                                <div>
                                                    <div class="fw-medium">{{ $child['label_name_en'] }}</div>
                                                    <small class="text-muted d-block">{{ $child['controller_name'] ?? '' }}</small>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-secondary rounded-pill">Child</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-save-sort mt-4">
                        <i class="ri ri-save-line me-1"></i>
                        Save Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Styles: Style A — Elegant Modern --}}
    @push('styles')
    <style>
        /* Card */
        .sort-card {
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(22, 28, 37, 0.06);
            border: 1px solid #eef2f6;
            overflow: visible;
        }

        /* Parent item */
        .parent-item {
            background: #ffffff;
            border: 1px solid #f1f4f8;
            margin-bottom: 10px;
            border-radius: 10px;
            padding: 14px 18px;
            transition: transform .12s ease, box-shadow .12s ease;
        }
        .parent-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(14, 20, 30, 0.04);
        }

        /* Child container */
        .child-zone {
            padding-left: 12px;
            border-left: 3px solid rgba(13,110,253,0.08);
            margin-top: 12px;
        }

        /* Child item */
        .child-item {
            background: #fbfcfd;
            border: 1px solid #f5f7fa;
            margin-bottom: 8px;
            border-radius: 8px;
            padding: 10px 14px;
            transition: transform .08s ease, box-shadow .08s ease;
        }
        .child-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(14, 20, 30, 0.03);
        }

        /* Drag handle */
        .drag-handle-parent,
        .drag-handle-child {
            cursor: grab;
            font-size: 18px;
            color: #9aa5b1;
            padding: 6px;
            border-radius: 6px;
            transition: color .12s ease, background .12s ease;
        }
        .drag-handle-parent:hover,
        .drag-handle-child:hover {
            color: #0d6efd;
            background: rgba(13,110,253,0.06);
        }

        /* Icon */
        .item-icon {
            font-size: 20px;
            color: rgba(50, 60, 70, 0.85);
        }

        /* Label */
        .item-label .fw-semibold {
            font-size: 14px;
        }
        .item-label small {
            font-size: 11px;
        }

        /* List group flush fix */
        .list-group-flush > .list-group-item {
            border-width: 0;
        }

        /* Save button */
        .btn-save-sort {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Responsive tweaks */
        @media (max-width: 576px) {
            .item-label .fw-semibold { font-size: 13px; }
            .item-icon { font-size: 18px; }
        }
    </style>
    @endpush
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
