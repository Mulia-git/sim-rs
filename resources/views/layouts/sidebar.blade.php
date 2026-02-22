<aside id="sidebar" class="sidebar">

    <div class="logo">
        <span>Kania Healthcare</span>
        <button id="toggleSidebar">☰</button>
    </div>

    <ul class="menu">
        @foreach(($menus[null] ?? []) as $parent)

            @php
                $children = $menus[$parent->id_menu] ?? [];
                $hasChild = count($children) > 0;
            @endphp

            <li class="menu-item {{ $hasChild ? 'has-tree' : '' }}">

                <a href="{{ $hasChild ? '#' : url($parent->menu_link) }}"
                   class="menu-link">

                    <i class="{{ $parent->icon }}"></i>
                    <span>{{ $parent->menu_name }}</span>

                    @if($hasChild)
                        <i class="arrow">›</i>
                    @endif
                </a>

                @if($hasChild)
                    <ul class="submenu">
                        @foreach($children as $child)
                            <li>
                                <a href="{{ url($child->menu_link) }}">
                                    <i class="far fa-circle"></i>
                                    <span>{{ $child->menu_name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </li>
        @endforeach
    </ul>

</aside>
