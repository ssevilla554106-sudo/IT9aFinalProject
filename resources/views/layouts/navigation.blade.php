<nav x-data="{ open: false }" style="background: linear-gradient(90deg, #1a1a2e, #16213e); border-bottom: 1px solid rgba(255,255,255,0.1); box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <!-- Primary Navigation Menu -->
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; height: 70px;">
            <div style="display: flex; align-items: center;">
                <!-- Logo -->
                <div style="margin-right: 2rem; flex-shrink: 0;">
                    <a href="{{ route('dashboard') }}" style="text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 40px; height: 40px; background: #e94560; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.2rem;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span style="font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.3rem; color: white; display: none;">Event<span style="color: #e94560;">Hub</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div style="display: none; gap: 2rem; align-items: center;">
                    <a href="{{ route('dashboard') }}" style="color: {{ request()->routeIs('dashboard') ? '#ffffff' : '#94a3b8' }}; text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: all 0.2s; padding: 0.5rem 1rem; border-radius: 8px; {{ request()->routeIs('dashboard') ? 'background: rgba(233, 69, 96, 0.2);' : '' }}">
                        <i class="fas fa-chart-line" style="margin-right: 0.4rem;"></i>
                        Dashboard
                    </a>
                </div>
            </div>

            <!-- Desktop Settings Dropdown -->
            <div style="display: flex; align-items: center;">
                <div style="position: relative;" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    <button @click="open = ! open" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: rgba(255,255,255,0.08); color: #94a3b8; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; font-weight: 500; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; font-family: 'DM Sans', sans-serif;">
                        <span style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #e94560; border-radius: 8px; color: white; font-weight: 700; font-size: 0.85rem;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem; transition: transform 0.2s;" :style="open && 'transform: rotate(180deg)'"></i>
                    </button>

                    <div x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            style="position: absolute; top: calc(100% + 0.5rem); right: 0; z-index: 50; width: 220px; background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; display: none;"
                            @click="open = false">
                        
                        <!-- Dropdown Header -->
                        <div style="padding: 1rem; background: linear-gradient(135deg, #1a1a2e, #16213e); color: white; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <div style="font-weight: 600; font-size: 0.9rem; margin-bottom: 0.2rem;">{{ Auth::user()->name }}</div>
                            <div style="font-size: 0.8rem; color: #94a3b8;">{{ Auth::user()->email }}</div>
                        </div>

                        <!-- Dropdown Links -->
                        <div style="padding: 0.5rem;">
                            <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: #1e293b; text-decoration: none; font-size: 0.9rem; border-radius: 8px; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                <i class="fas fa-user-circle" style="width: 18px; text-align: center; color: #e94560;"></i>
                                <span>Edit Profile</span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                                @csrf
                                <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: #1e293b; background: none; border: none; font-size: 0.9rem; border-radius: 8px; transition: all 0.2s; cursor: pointer; font-family: 'DM Sans', sans-serif; margin-top: 0.25rem;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                    <i class="fas fa-sign-out-alt" style="width: 18px; text-align: center; color: #ef4444;"></i>
                                    <span>Log Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div style="display: none;">
                <button @click="open = ! open" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem; border-radius: 8px; color: #94a3b8; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); cursor: pointer;">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden" style="background: rgba(0,0,0,0.1);">
        <div style="padding: 1rem 1.5rem; display: flex; flex-direction: column; gap: 0.5rem;">
            <a href="{{ route('dashboard') }}" style="color: {{ request()->routeIs('dashboard') ? '#ffffff' : '#94a3b8' }}; text-decoration: none; font-weight: 500; font-size: 0.9rem; padding: 0.75rem 1rem; border-radius: 8px; {{ request()->routeIs('dashboard') ? 'background: rgba(233, 69, 96, 0.2);' : '' }}">
                <i class="fas fa-chart-line" style="margin-right: 0.4rem;"></i>
                Dashboard
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div style="padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); color: #94a3b8;">
            <div style="font-weight: 600; margin-bottom: 0.5rem;">{{ Auth::user()->name }}</div>
            <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 1rem;">{{ Auth::user()->email }}</div>

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <a href="{{ route('profile.edit') }}" style="color: #e94560; text-decoration: none; font-weight: 500; font-size: 0.9rem; padding: 0.75rem 1rem; border-radius: 8px; display: flex; align-items: center; gap: 0.5rem; background: rgba(233, 69, 96, 0.1);">
                    <i class="fas fa-user-circle"></i>
                    Edit Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                    @csrf
                    <button type="submit" style="color: #ef4444; background: rgba(239, 68, 68, 0.1); text-decoration: none; font-weight: 500; font-size: 0.9rem; padding: 0.75rem 1rem; border-radius: 8px; display: flex; align-items: center; gap: 0.5rem; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; width: 100%; text-align: left;">
                        <i class="fas fa-sign-out-alt"></i>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
